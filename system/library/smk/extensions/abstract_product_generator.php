<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_generator.php' );

class SeoMegaPack_AbstractProductGenerator extends SeoMegaPack_AbstractGenerator {
	
	/**
	 * @var array 
	 */
	protected $_tags			= array(
		'{product_name}'	=> 'product_name',
		'{description}'		=> 'description',
		'{category}'		=> 'category_name',
		'{model}'			=> 'model',
		'{brand}'			=> 'brand_name',
		'{sku}'				=> 'sku',
		'{upc}'				=> 'upc',
		'{site_name}'		=> 'site_name',
		'{site_title}'		=> 'site_title'
	);
	
	/**
	 * @var int
	 */
	protected $_sort		= 0;
	
	/**
	 * @var string 
	 */
	protected $_group		= 'products';
	
	/**
	 * @var string 
	 */
	protected $_tableName	= 'product_description';
	
	public function _generateInfoByLang( $language_id ) {
		$query = '
			SELECT
				COUNT(*) AS c
			FROM
				' . DB_PREFIX . 'product p
			INNER JOIN
				' . DB_PREFIX . $this->_tableName . ' pd
			ON
				pd.product_id = p.product_id AND pd.language_id = ' . $language_id . '
			WHERE
				( pd.' . $this->_fieldName . ' IS NULL OR pd.' . $this->_fieldName . ' = "" )
		';
		
		return $this->db->query( $query )->row['c'];
	}
	
	/**
	 * @param int $language_id
	 * @param bool $preview
	 * @return string
	 */
	public function _generateByLang( $language_id, $preview, $productIds = array(), $limit = NULL ) {
		$results	= array();
		$sql		= '';
		
		if( $this->_fieldName == 'description' ) {
			$sql .= " OR pd." . $this->_fieldName . " = '" . $this->db->escape( '<p><br></p>' ) . "' ";
			$sql .= " OR pd." . $this->_fieldName . " = '" . $this->db->escape( '&lt;p&gt;&lt;br&gt;&lt;/p&gt;' ) . "' ";
		}
		
		$query		= '
			SELECT
				p.product_id,
				p.sku,
				p.upc,
				p.model,
				pd.name AS product_name,
				pd.description,
				m.name AS brand_name,
				( SELECT category_id FROM ' . DB_PREFIX . 'product_to_category ptc WHERE ptc.product_id = p.product_id LIMIT 1 ) AS category_id
			FROM
				' . DB_PREFIX . 'product p
			INNER JOIN
				' . DB_PREFIX . $this->_tableName . ' pd
			ON
				pd.product_id = p.product_id AND pd.language_id = ' . $language_id . '
			LEFT JOIN
				' . DB_PREFIX . 'manufacturer m
			ON
				m.manufacturer_id = p.manufacturer_id
			WHERE
				( pd.' . $this->_fieldName . ' IS NULL OR pd.' . $this->_fieldName . ' = "" ' . $sql . ' )
		';
		
		if( $productIds )
			$query .= ' AND p.product_id IN(' . implode(',', $productIds) . ')';
		
		if( $preview ) {
			$query .= ' ORDER BY RAND() LIMIT 20';
		} else if( $limit !== NULL ) {
			$query .= ' LIMIT ' . (int) $limit;
		}
		
		$params	= $this->convertParamsToConfig( $language_id );
		
		foreach( $this->db->query( $query )->rows as $item ) {
			$generate = $this->getParams( $language_id );
			
			if( is_array( $generate ) )
				$generate = isset( $generate[$language_id] ) ? $generate[$language_id] : '';
			
			foreach( $params as $search => $param ) {
				if( ! isset( $this->_tags['{'.$param['tag'].'}'] ) ) continue;
				
				$field	= $this->_tags['{'.$param['tag'].'}'];
				$value	= isset( $item[$field] ) ? $item[$field] : $param['value'];
				
				switch( $param['tag'] ) {
					case 'description' : {
						$value = $this->getSentences( $item['description'], $param['config']['sentences'] );
						
						break;
					}
					case 'category' : {
						$value = $this->_getCategory( $item['category_id'], $language_id );
						$value = $value['name'];
						
						break;
					}
					case 'category_description' : {
						$value = $this->_getCategory( $item['category_id'], $language_id, true );
						$value = $value['description'];
						
						break;
					}
				}
				
				$generate = str_replace( $search, $value, $generate );
			}
			
			$generate = $this->parseGenerated( $generate );
			
			if( ! $preview ) {
				$this->db->query("
					UPDATE
						" . DB_PREFIX . $this->_tableName . "
					SET
						`" . $this->_fieldName . "` = '" . $this->db->escape( $generate ) . "',
						`" . $this->_fieldName . "_ag` = '1'
					WHERE
						product_id = " . (int) $item['product_id'] . " AND
						language_id = " . (int) $language_id . "
				");
			}
			
			$results[] = array( $item['product_name'], htmlspecialchars_decode( $generate ) );
		}
		
		return $results;
	}
}