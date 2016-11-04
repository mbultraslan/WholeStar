<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_product_generator.php' );

class SeoMegaPack_KeywordsGenerator extends SeoMegaPack_AbstractProductGenerator {
	
	/**
	 * @var string
	 */
	protected $_name		= 'keywords_generator';
	protected $_shortName	= 'kg';
	
	/**
	 * @var string
	 */
	protected $_title		= 'Meta Keywords Generator';
	
	/**
	 * @var int
	 */
	protected $_sort		= 0.3;
	
	/**
	 * @var string 
	 */
	protected $_icon		= 'list-alt';
	
	protected $_fieldName	= 'meta_keyword';
	
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
		
		if( $params === NULL )
			$params = '{product_name} {category} {brand}';
		
		return $params;
	}
	
	/**
	 * @return void
	 */
	public function install() {
		$this->addColumn( 'meta_keyword_ag', "ENUM('0','1') NOT NULL DEFAULT '0'" );
		
		parent::install();
	}
	
	/**
	 * @return void 
	 */
	public function uninstall() {
		$this->removeColumn( 'meta_keyword_ag' );
		
		parent::uninstall();
	}
}