<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_manufacturer_generator.php' );

class SeoMegaPack_ManufacturerTagsGenerator extends SeoMegaPack_AbstractManufacturerGenerator {
	
	/**
	 * @var string
	 */
	protected $_name		= 'manufacturer_tags_generator';
	protected $_shortName	= 'mtg';
	
	/**
	 * @var string
	 */
	protected $_title		= 'Tags Generator';
	
	/**
	 * @var int
	 */
	protected $_sort		= 3.5;
	
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
	 * @return void
	 */
	public function install() {
		parent::install();
		
		$this->addColumn( 'tag', 'TEXT NOT NULL' );
		$this->addColumn( 'tag_ag', "ENUM('0','1') NOT NULL DEFAULT '0'" );
	}
	
	/**
	 * @return void 
	 */
	public function uninstall() {
		//$this->removeColumn( 'tag_ag' );
		
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