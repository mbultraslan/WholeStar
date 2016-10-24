<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_product_generator.php' );

class SeoMegaPack_DescriptionGenerator extends SeoMegaPack_AbstractProductGenerator {
	
	/**
	 * @var array 
	 */
	protected $_tags			= array(
		'{product_name}'			=> 'product_name',
		'{category}'				=> 'category_name',
		'{category_description}'	=> 'category_description',
		'{model}'					=> 'model',
		'{brand}'					=> 'brand_name',
		'{sku}'						=> 'sku',
		'{upc}'						=> 'upc',
		'{site_name}'				=> 'site_name',
		'{site_title}'				=> 'site_title'
	);
	
	/**
	 * @var string
	 */
	protected $_name		= 'description_generator';
	protected $_shortName	= 'dg';
	
	/**
	 * @var string
	 */
	protected $_title		= 'Product Description Generator';
	
	/**
	 * @var int
	 */
	protected $_sort		= 6.1;
	
	/**
	 * @var string 
	 */
	protected $_icon		= 'pencil';
	
	/**
	 * @var string 
	 */
	protected $_version		= '2';
	
	/**
	 * @var string 
	 */
	protected $_group		= 'description';
	
	/**
	 * @var string 
	 */
	protected $_fieldName			= 'description';
	
	/**
	 * @var string 
	 */
	protected $_tableName			= 'product_description';
	
	/**
	 * @var bool 
	 */
	protected $_defaultSetParams	= false;
	
	/**
	 * @return string 
	 */
	public function description() {
		return $this->_descriptionByCKEDITOR( '{product_name}' );
	}
	
	/**
	 * @return void
	 */
	public function install() {
		parent::install();
		
		$this->addColumn( 'description_ag', "ENUM('0','1') NOT NULL DEFAULT '0'" );
	}
	
	/**
	 * @return void 
	 */
	public function uninstall() {
		$this->removeColumn( 'description_ag' );
		
		parent::uninstall();
	}
}