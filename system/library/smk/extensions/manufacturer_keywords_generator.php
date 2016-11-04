<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_manufacturer_generator.php' );

class SeoMegaPack_ManufacturerKeywordsGenerator extends SeoMegaPack_AbstractManufacturerGenerator {
	
	/**
	 * @var string
	 */
	protected $_name		= 'manufacturer_keywords_generator';
	protected $_shortName	= 'mkg';
	
	/**
	 * @var string
	 */
	protected $_title		= 'Meta Keywords Generator';
	
	/**
	 * @var int
	 */
	protected $_sort		= 3.3;
	
	/**
	 * @var type 
	 */
	protected $_fieldName	= 'meta_keyword';
	
	/**
	 * @var string 
	 */
	protected $_icon		= 'list-alt';
	
	/**
	 * @var string 
	 */
	protected $_version		= '2';
	
	/**
	 * @return void
	 */
	public function install() {
		parent::install();
		
		$this->addColumn( 'meta_keyword', 'VARCHAR(255) NULL DEFAULT NULL' );
		$this->addColumn( 'meta_keyword_ag', "ENUM('0','1') NOT NULL DEFAULT '0'" );
	}
	
	/**
	 * @return void 
	 */
	public function uninstall() {
		//$this->removeColumn( 'meta_keyword_ag' );
		
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
		
		if( $params === NULL )
			$params = '{name}';
		
		return $params;
	}
}