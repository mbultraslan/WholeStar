<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

abstract class SeoMegaPack_AbstractParseUrl {
	
	/**
	 * @var ControllerCommonSeoMegaPackProUrl
	 */
	protected $_controller		= NULL;
	
	protected $_installed		= false;
	
	/**
	 * Counts
	 *
	 * @var array
	 */
	protected $_counts		= array();
	
	/**
	 * Alias
	 *
	 * @var array
	 */
	protected $_alias			= array();
	
	////////////////////////////////////////////////////////////////////////////
	
	private static $_adminDir = NULL;
	
	public static function adminDir() {
		if( self::$_adminDir === NULL ) {
			self::$_adminDir = defined( 'SMP_ADMIN_DIR' ) ? SMP_ADMIN_DIR : 'admin';
		}
		
		return self::$_adminDir;
	}
	
	////////////////////////////////////////////////////////////////////////////
	
	/**
	 * @param ControllerModuleSeoMegaPack $controller
	 */
	public function __construct( ControllerCommonSeoMegaPackProUrl $controller ) {
		$this->_controller = $controller;
	}
	
	/**
	 * @return bool
	 */
	public function installed() {
		return $this->_installed;
	}
	
	////////////////////////////////////////////////////////////////////////////
	
	abstract public function _part_1( $part, $parts, $params );
	
	abstract public function _part_2( $part, $parts, $params, $url );
	
	abstract public function _part_3( $part, $parts, $params );
	
	abstract public function _rewrite( & $url_data, & $url );
}