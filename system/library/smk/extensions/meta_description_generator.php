<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_product_generator.php' );

class SeoMegaPack_MetaDescriptionGenerator extends SeoMegaPack_AbstractProductGenerator {
	
	/**
	 * @var string
	 */
	protected $_name		= 'meta_description_generator';
	protected $_shortName	= 'mdg';
	
	/**
	 * @var string
	 */
	protected $_title		= 'Meta Description Generator';
	
	/**
	 * @var int
	 */
	protected $_sort		= 0.4;
	
	/**
	 * @var string 
	 */
	protected $_icon		= 'list-alt';
	
	/**
	 * @var string 
	 */
	protected $_fieldName	= 'meta_description';
	
	/**
	 * @var string 
	 */
	protected $_version		= '2';
	
	/**
	 * @return int
	 */
	public function getParams( $language_id = null ) {
		$params = parent::getParams();
		
		if( $language_id !== null && is_array( $params ) ) {
			$params = isset( $params[$language_id] ) ? $params[$language_id] : null;
		}
		
		if( is_array( $params ) || $params === NULL ) {
			$params = '{product_name} - {model} ({brand}). {description#sentences#1}';
		}
		
		return $params;
	}
	
	/**
	 * @return void
	 */
	public function install() {
		$this->addColumn( 'meta_description_ag', "ENUM('0','1') NOT NULL DEFAULT '0'" );
		
		parent::install();
	}
	
	/**
	 * @return void 
	 */
	public function uninstall() {
		$this->removeColumn( 'meta_description_ag' );
		
		parent::uninstall();
	}
}