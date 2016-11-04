<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_product_generator.php' );

class SeoMegaPack_SeoAltImagesGenerator extends SeoMegaPack_AbstractProductGenerator {
	
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
	protected $_name		= 'seo_alt_images_generator';
	protected $_shortName	= 'saig';
	
	/**
	 * @var string
	 */
	protected $_title		= 'SEO ALT Images Generator';
	
	/**
	 * @var int
	 */
	protected $_sort		= 0.6;
	
	/**
	 * @var string 
	 */
	protected $_icon		= 'picture';
	
	protected $_fieldName	= 'smp_alt_images';
	
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
			$params = '{product_name} {category}';
		
		return $params;
	}
	
	/**
	 * @return void
	 */
	public function install() {
		$this->addColumn( 'smp_alt_images', 'VARCHAR(255) NULL DEFAULT NULL' );
		$this->addColumn( 'smp_alt_images_ag', "ENUM('0','1') NOT NULL DEFAULT '0'" );
		
		parent::install();
	}
	
	/**
	 * @return void 
	 */
	public function uninstall() {
		$this->removeColumn( 'smp_alt_images' );
		$this->removeColumn( 'smp_alt_images_ag' );
		
		parent::uninstall();
	}
}