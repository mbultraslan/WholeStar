<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_product_generator.php' );

class SeoMegaPack_RelatedProductsGenerator extends SeoMegaPack_AbstractGenerator {
	
	/**
	 * @var array 
	 */
	protected $_tags			= array();
	
	/**
	 * @var string
	 */
	protected $_name		= 'related_products_generator';
	protected $_shortName	= 'rpg';
	
	/**
	 * @var string
	 */
	protected $_title		= 'Related Products Generator';
	
	/**
	 * @var int
	 */
	protected $_sort		= 0.62;
	
	/**
	 * @var string 
	 */
	protected $_icon		= 'random';
	
	/**
	 * @var string 
	 */
	protected $_group		= 'products';
	
	/**
	 * @var bool 
	 */
	protected $_multiLanguages		= false;
	
	/**
	 * @var string 
	 */
	protected $_version		= '2';
	
	/**
	 * @param bool $preview
	 * @param int $start
	 * @param int $limit
	 */
	public function generate( $preview = false, $limit = NULL, $language_id = NULL, $mode = NULL ) {
		$results	= array();
		
		foreach( $this->_languages( true ) as $lang ) {
			$results[''] = $this->_generateByLang( $lang['language_id'], $preview, array(), $limit );
			
			break;
		}
		
		return $results;
	}
	
	/**
	 * 
	 */	
	public function generateInfo( $mode = NULL ) {
		$results	= array();
		
		if( isset( $this->_controller->session->data['smp_related_last_pid'] ) )
			unset( $this->_controller->session->data['smp_related_last_pid'] );
		
		$results[]	= array(
			'language_id'	=> '',
			'image'			=> '',
			'name'			=> '',
			'code'			=> '',
			'items'			=> $this->_generateInfoByLang( NULL )
		);
		
		return $results;
	}
	
	public function _generateInfoByLang( $language_id ) {
		$limit	= $this->getParams();
		$query	= "
			SELECT
				COUNT(*) AS c
			FROM
				" . DB_PREFIX . "product p
			WHERE
				p.status = '1' AND 
				p.date_available <= NOW() AND
				p.smp_related_products < " . $limit . "
		";
		
		return $this->db->query( $query )->row['c'];
	}
	
	/**
	 * @param int $language_id
	 * @param bool $preview
	 * @return string
	 */
	public function _generateByLang( $language_id, $preview, $productIds = array(), $limit = NULL ) {
		$results		= array();
		$limitParams	= $this->getParams();
		$query			= "
			SELECT
				p.product_id,
				p.manufacturer_id,
				p.smp_related_products,
				pd.name AS product_name,
				GROUP_CONCAT( ptc.category_id ) AS category_id
			FROM
				" . DB_PREFIX . "product p
			LEFT JOIN
				" . DB_PREFIX . "product_description pd
			ON
				pd.product_id = p.product_id AND pd.language_id = " . $language_id . "
			LEFT JOIN
				" . DB_PREFIX . "product_to_category ptc
			ON
				ptc.product_id = p.product_id
			WHERE
				p.status = '1' AND 
				p.date_available <= NOW() AND
				p.smp_related_products < " . $limitParams . "
		";
		
		if( $productIds ) {
			$query .= ' AND p.product_id IN(' . implode(',', $productIds) . ')';
		} else if( ! empty( $this->_controller->session->data['smp_related_last_pid']['time'] ) && $this->_controller->session->data['smp_related_last_pid']['time'] > time() ) {
			$query .= ' AND p.product_id > ' . (int) $this->_controller->session->data['smp_related_last_pid']['pid'];
		}
		
		$query .= '
			GROUP BY
				p.product_id';
		
		if( $preview ) {
			$query .= ' ORDER BY RAND() LIMIT 20';
		} else if( $limit !== NULL ) {
			$query .= ' ORDER BY p.product_id ASC LIMIT ' . (int) $limit;
		}
		
		$products = array();
		
		foreach( $this->db->query( $query )->rows as $item ) {
			$exists		= $this->db->query("
				SELECT
					GROUP_CONCAT( pr.related_id ) AS related_id
				FROM
					" . DB_PREFIX . "product_related pr
				WHERE
					pr.product_id = " . $item['product_id'] . "
			"); 
			$related	= $this->db->query("
				SELECT
					p.product_id,
					pd.name AS product_name
				FROM
					" . DB_PREFIX . "product p
				LEFT JOIN
					" . DB_PREFIX . "product_description pd
				ON
					pd.product_id = p.product_id AND pd.language_id = " . $language_id . "
				LEFT JOIN
					" . DB_PREFIX . "product_to_category ptc
				ON
					ptc.product_id = p.product_id
				WHERE
					p.status = '1' AND 
					p.date_available <= NOW() AND
					p.product_id != " . $item['product_id'] . " AND 
					p.smp_related_products < " . $limitParams . "
					" . ( $item['category_id'] ? " AND ptc.category_id IN( " . $item['category_id'] . " )" : '' ) . "
					" . ( $exists->row['related_id'] ? " AND p.product_id NOT IN(" . $exists->row['related_id'] . " )" : '' ) . "
				GROUP BY
					p.product_id
				ORDER BY
					RAND()
				LIMIT
					" . $limitParams . "
			");
			
			foreach( $related->rows as $row ) {
				$products[$item['product_id']][] = $row['product_name'];
				
				if( ! $preview ) {
					$this->db->query("
						INSERT INTO
							" . DB_PREFIX . "product_related
						SET
							`product_id` = '" . $item['product_id'] . "',
							`related_id` = '" . $row['product_id'] . "',
							`smp_ag` = '1'
					");
				}
				
				if( count( $products[$item['product_id']] ) >= $limitParams - $item['smp_related_products'] )
					break;
			}
			
			$results[] = array( $item['product_name'], empty( $products[$item['product_id']] ) ? '-' : implode( ', ', $products[$item['product_id']] ) );
		}
		
		if( ! $preview ) {
			foreach( $products as $product_id => $items ) {
				$this->db->query("
					UPDATE
						" . DB_PREFIX . "product
					SET
						smp_related_products = smp_related_products + " . count( $items ) . "
					WHERE
						product_id = " . $product_id . "
				");
			
				$this->_controller->session->data['smp_related_last_pid'] = array(
					'time'	=> time() + 60 * 30,
					'pid'	=> $product_id
				);
			}
		}
		
		return $results;
	}
	
	public function description() {
		return 'Total number of related products:';
	}
	
	public function clear($mode = NULL, $onlyAutoGenerated = false) {
		$q = "
			DELETE FROM
				" . DB_PREFIX . "product_related
		";
		
		if( $onlyAutoGenerated )
			$q .= "
				WHERE 
					`smp_ag` = '1'
			";
		
		$this->db->query( $q );
		
		$this->_updateRelated();
	}
	
	protected function _updateRelated() {
		$this->db->query("
			UPDATE
				" . DB_PREFIX . "product p
			SET
				p.smp_related_products = (
					SELECT
						COUNT(*)
					FROM
						" . DB_PREFIX . "product_related r
					WHERE
						r.product_id = p.product_id
				)
		");
	}
	
	/**
	 * @return void
	 */
	public function install() {
		parent::install();
		
		$this->addColumn( 'smp_ag', "ENUM('0','1') NOT NULL DEFAULT '0'", 'product_related' );
		
		if( $this->addColumn( 'smp_related_products', 'INT(11) NOT NULL DEFAULT 0', 'product' ) ) {			
			$this->_updateRelated();
		}
	}
	
	/**
	 * @return void 
	 */
	public function uninstall() {
		$this->removeColumn( 'smp_related_products', 'product' );
		$this->removeColumn( 'smp_ag', 'product_related' );
		
		parent::uninstall();
	}
	
	/**
	 * @return int
	 */
	public function getParams() {
		$params = (int) parent::getParams();
		
		if( $params < 1 )
			$params = 5;
		
		return $params;
	}
}