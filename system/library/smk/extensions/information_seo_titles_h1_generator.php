<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_information_generator.php' );

class SeoMegaPack_InformationSeoTitlesH1Generator extends SeoMegaPack_AbstractInformationGenerator {
	
	/**
	 * @var array 
	 */
	protected $_tags			= array(
		'{title}'			=> 'title',
		'{site_name}'		=> 'site_name',
		'{site_title}'		=> 'site_title'
	);
	
	/**
	 * @var string
	 */
	protected $_name		= 'information_seo_titles_h1_generator';
	protected $_shortName	= 'isth1g';
	
	/**
	 * @var string
	 */
	protected $_title		= 'SEO Heading Titles Generator';
	
	/**
	 * @var int
	 */
	protected $_sort		= 2.2;
	
	/**
	 * @var string 
	 */
	protected $_icon		= 'header';
	
	protected $_fieldName	= 'smp_h1_title';
	
	/**
	 * @var string 
	 */
	protected $_version		= '2';
	
	/**
	 * @return void
	 */
	public function install() {
		$this->addColumn( 'smp_h1_title', 'VARCHAR(255) NULL DEFAULT NULL' );
		$this->addColumn( 'smp_h1_title_ag', "ENUM('0','1') NOT NULL DEFAULT '0'" );
		
		parent::install();
	}
	
	/**
	 * @return void 
	 */
	public function uninstall() {
		$this->removeColumn( 'smp_h1_title' );
		$this->removeColumn( 'smp_h1_title_ag' );
		
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
			$params = '{title}';
		}
		
		return $params;
	}
}