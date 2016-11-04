<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_category_generator.php' );

class SeoMegaPack_CategoryDescriptionGenerator extends SeoMegaPack_AbstractCategoryGenerator {
	
	/**
	 * @var array 
	 */
	protected $_tags			= array(
		'{name}'			=> 'name',
		'{parent_name}'		=> 'parent_name',
		'{sample_products}'	=> 'sample_products',
		'{total_products}'	=> 'total_products',
		'{site_name}'		=> 'site_name',
		'{site_title}'		=> 'site_title'
	);
	
	/**
	 * @var string
	 */
	protected $_name		= 'category_description_generator';
	protected $_shortName	= 'cdg';
	
	/**
	 * @var string
	 */
	protected $_title		= 'Category Description Generator';
	
	/**
	 * @var int
	 */
	protected $_sort		= 6.2;
	
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
	protected $_tableName			= 'category_description';
	
	/**
	 * @var bool 
	 */
	protected $_defaultSetParams	= false;
	
	/**
	 * @return string 
	 */
	public function description() {
		return $this->_descriptionByCKEDITOR( '{name}' );
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
	
	/**
	 * @return string
	 */
	public function getParams( $language_id = null ) {
		$params = parent::getParams();
		
		if( $language_id !== null && is_array( $params ) ) {
			$params = isset( $params[$language_id] ) ? $params[$language_id] : null;
		}
		
		if( $params === NULL )
			$params = '{name}: {sample_products#total#3#sep#, }';
		
		return $params;
	}
}