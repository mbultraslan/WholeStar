<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_product_generator.php' );

class SeoMegaPack_TagsGenerator extends SeoMegaPack_AbstractProductGenerator {
	
	/**
	 * @var array 
	 */
	protected $_tags			= array(
		'{product_name}'	=> 'product_name',
		'{category}'		=> 'category_name',
		'{model}'			=> 'model',
		'{brand}'			=> 'brand_name',
		'{sku}'				=> 'sku',
		'{upc}'				=> 'upc',
		'{site_name}'		=> 'site_name',
		'{site_title}'		=> 'site_title'
	);
	
	/**
	 * @var string
	 */
	protected $_name		= 'tags_generator';
	protected $_shortName	= 'tg';
	
	/**
	 * @var string
	 */
	protected $_title		= 'Tags Generator';
	
	/**
	 * @var int
	 */
	protected $_sort		= 0.5;
	
	/**
	 * @var string 
	 */
	protected $_icon		= 'tags';
	
	/**
	 * @var string 
	 */
	protected $_fieldName	= 'tag';
	
	/**
	 * @var string 
	 */
	protected $_version		= '2';
	
	protected function parseGenerated( $generate ) {
		return $this->parseTagGenerated( $generate );
	}
	
	/**
	 * Jeśli funkcja install() zwróci false system wywoła poniższą metodę aby podzielić instalację na etapy
	 * 
	 * @param int $index
	 * @return int|NULL 
	 */
	public function installprogress( $index ) {
		if( ! $this->db->query( "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '" . DB_PREFIX . "product_tag' AND TABLE_SCHEMA = '" . DB_DATABASE . "'" )->num_rows )
			return;
		
		$count	= $this->db->query( 'SELECT COUNT(*) AS c FROM `' . DB_PREFIX . 'product_tag`' )->row['c'];
		$limit	= 5000;
		
		foreach( $this->db->query( 'SELECT * FROM `' . DB_PREFIX . 'product_tag` LIMIT ' . ( $limit * $index ) . ', ' . $limit )->rows as $row ) {
			$this->db->query("
				UPDATE
					`" . DB_PREFIX . "product_description`
				SET
					`tag` = CONCAT( IF( `tag` IS NULL OR `tag` = '', '', ',' ), '" . $this->db->escape( $row['tag'] ) . "' )
				WHERE
					`product_id` = " . $row['product_id'] . " AND
					`language_id` = " . $row['language_id'] . "
			");
		}
			
		// oblicz postęp instalacji
		$progress = $count < 1 ? 100 : ( $limit * $index ) / $count * 100;
			
		if( $progress < 100 )
			return round( $progress, 2 );
	}
	
	/**
	 * @return void|bool
	 */
	public function install() {
		parent::install();
		
		$this->addColumn( 'tag', 'TEXT NOT NULL' );
		$this->addColumn( 'tag_ag', "ENUM('0','1') NOT NULL DEFAULT '0'" );
		
		if( $this->db->query( "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '" . DB_PREFIX . "product_tag' AND TABLE_SCHEMA = '" . DB_DATABASE . "'" )->num_rows ) {
			return false;
		}
	}
	
	/**
	 * @return void 
	 */
	public function uninstall() {
		$this->removeColumn( 'tag_ag' );
		
		parent::uninstall();
	}
	
	/**
	 * @return int
	 */
	public function getParams( $language_id = null ) {
		$params = parent::getParams();
		
		if( $language_id !== null && is_array( $params ) ) {
			$params = isset( $params[$language_id] ) ? $params[$language_id] : null;
		}
		
		if( $params === NULL )
			$params = '{product_name} {category} {brand}';
		
		return $params;
	}
}