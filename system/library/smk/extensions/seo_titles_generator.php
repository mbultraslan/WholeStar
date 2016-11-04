<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_product_generator.php' );

class SeoMegaPack_SeoTitlesGenerator extends SeoMegaPack_AbstractProductGenerator {
	
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
	protected $_name		= 'seo_titles_generator';
	protected $_shortName	= 'stg';
	
	/**
	 * @var string
	 */
	protected $_title		= 'SEO Titles Generator';
	
	/**
	 * @var int
	 */
	protected $_sort		= 0.1;
	
	/**
	 * @var string 
	 */
	protected $_group		= 'products';
	
	/**
	 * @var string 
	 */
	protected $_icon		= 'tower';
	
	/**
	 * @var string 
	 */
	protected $_fieldName	= 'meta_title';
	
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
		
		if( $params === NULL ) {
			$params = '{product_name}';
		}
		
		return $params;
	}
	
	/**
	 * @return void
	 */
	public function install() {
		$this->addColumn( 'meta_title_ag', "ENUM('0','1') NOT NULL DEFAULT '0'" );
		
		parent::install();
	}
	
	/**
	 * @return void 
	 */
	public function uninstall() {
		$this->removeColumn( 'meta_title_ag' );
		
		parent::uninstall();
	}
}