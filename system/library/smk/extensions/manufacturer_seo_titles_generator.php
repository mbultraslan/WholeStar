<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_manufacturer_generator.php' );

class SeoMegaPack_ManufacturerSeoTitlesGenerator extends SeoMegaPack_AbstractManufacturerGenerator {
	
	/**
	 * @var string
	 */
	protected $_name		= 'manufacturer_seo_titles_generator';
	protected $_shortName	= 'mstg';
	
	/**
	 * @var string
	 */
	protected $_title		= 'SEO Titles Generator';
	
	/**
	 * @var string 
	 */
	protected $_icon		= 'tower';
	
	/**
	 * @var int
	 */
	protected $_sort		= 3.1;
	
	/**
	 * @var string 
	 */
	protected $_fieldName	= 'meta_title';
	
	/**
	 * @var string 
	 */
	protected $_version		= '2.1';
	
	/**
	 * @return void
	 */
	public function install() {
		parent::install();
		
		if( $this->db->query('SHOW COLUMNS FROM ' . DB_PREFIX . $this->_tableName . ' LIKE "smp_title"')->num_rows ) {
			$this->db->query( 'ALTER TABLE ' . DB_PREFIX . $this->_tableName . ' CHANGE `smp_title` `meta_title` VARCHAR(255) NULL DEFAULT NULL' );
		}
		
		if( $this->db->query('SHOW COLUMNS FROM ' . DB_PREFIX . $this->_tableName . ' LIKE "smp_title_ag"')->num_rows ) {
			$this->db->query( 'ALTER TABLE ' . DB_PREFIX . $this->_tableName . " CHANGE `smp_title_ag` `meta_title_ag` ENUM('0','1') NOT NULL DEFAULT '0'" );
		}
		
		$this->addColumn( 'meta_title', 'VARCHAR(255) NULL DEFAULT NULL' );
		$this->addColumn( 'meta_title_ag', "ENUM('0','1') NOT NULL DEFAULT '0'" );
		
		/**
		 * @since 1.3.9 
		 */
		$this->addColumn( 'name', 'VARCHAR(255) NULL DEFAULT NULL' );
	}
	
	/**
	 * @return void 
	 */
	public function uninstall() {
		//$this->removeColumn( 'smp_title' );
		//$this->removeColumn( 'smp_title_ag' );
		
		parent::uninstall();
	}
	
	/**
	 * @return string
	 */
	public function getParams( $language_id = null ) {
		$params = parent::getParams();
		
		if( $language_id !== null && is_array( $params ) ) {
			$params = isset( $params[$language_id] ) ? $params[$language_id] : null;
		}
		
		if( $params === NULL ) {
			$params = '{name}';
		}
		
		return $params;
	}
}