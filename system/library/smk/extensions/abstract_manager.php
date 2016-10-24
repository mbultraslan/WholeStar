<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

abstract class SeoMegaPack_AbstractManager {
	
	/**
	 * @var ControllerModuleSeoMegaPack
	 */
	protected $_controller					= NULL;
	
	/**
	 * @var int
	 */
	protected $_sort						= 100;
	
	/**
	 * @var bool
	 */
	protected $_hasColumnName				= true;
	
	/**
	 * @var bool
	 */
	protected $_hasColumnUrl				= true;
	
	/**
	 * @var bool
	 */
	protected $_hasColumnTitle				= true;
	
	/**
	 * @var bool
	 */
	protected $_hasColumnHeadingTitle		= true;
	
	/**
	 * @var bool
	 */
	protected $_hasColumnMetaKeywords		= false;
	
	/**
	 * @var bool
	 */
	protected $_hasColumnMetaDescription	= false;
	
	/**
	 * @var bool 
	 */
	protected $_isMultilanguage				= true;
	
	/**
	 * @var bool
	 */
	protected $_hasColumnTags				= false;
	
	protected $_installed					= true;
	
	protected $_version						= '2';
	
	protected $_queryCount					= false;
	
	////////////////////////////////////////////////////////////////////////////
	
	/**
	 * @param ControllerModuleSeoMegaPack $controller
	 */
	public function __construct( ControllerModuleSeoMegaPack $controller ) {
		$this->_controller = $controller;
	}
	
	public function queryCount( $count = NULL ) {
		$this->_queryCount = (bool) $count;
		
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function sort() {
		return $this->_sort;
	}	
	
	public function version() {
		return $this->_version;
	}
	
	/**
	 * @return bool
	 */
	public function installed() {
		return $this->_installed;
	}
	
	protected function _updateUrlAlias( $type, $id, $language_id, $keyword ) {
		// usuń alians URL dla aktualnego języka
		$this->_controller->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query='" . $type . "_id=" . $id . "' AND ( smp_language_id=" . $language_id . ' )' );

		// ustaw nowy alians URL jeśli użytkownik go podał
		if( ! empty( $keyword ) ) {
			$this->_controller->db->query("
				INSERT INTO
					" . DB_PREFIX . "url_alias
				SET
					keyword='" . $this->_controller->db->escape( $this->_controller->_createUniqueKeyword( $keyword, NULL, $type . '_id=' . $id ) ) . "',
					query='" . $type . "_id=" . $id . "',
					smp_language_id=" . $language_id . "
			");
		}
	}
	
	public function addUrlAlias( $data, & $items ) {
		return $items;
	}
	
	// Has /////////////////////////////////////////////////////////////////////
	
	public function isMultilanguage() {
		return $this->_isMultilanguage;
	}
	
	public function hasColumnName() {
		return $this->_hasColumnName;
	}
	
	public function hasColumnUrl() {
		return $this->_hasColumnUrl;
	}
	
	public function hasColumnTitle() {
		return $this->_hasColumnTitle;
	}
	
	public function hasColumnHeadingTitle() {
		return $this->_hasColumnHeadingTitle;
	}
	
	public function hasColumnMetaKeywords() {
		return $this->_hasColumnMetaKeywords;
	}
	
	public function hasColumnMetaDescription() {
		return $this->_hasColumnMetaDescription;
	}
	
	public function hasColumnTags() {
		return $this->_hasColumnTags;
	}
	
	// Abstract ////////////////////////////////////////////////////////////////
	
	/**
	 * @param array $data
	 * @param array $session
	 * @param bool $count
	 * @return string
	 */
	abstract public function getQuery( $data, $session );
	
	/**
	 * @return string
	 */
	abstract public function getTabLabel();
	
	/**
	 * @param array $post
	 * @param int $id
	 * @param int $language_id
	 * @return void
	 */
	abstract public function save( $post, $id, $language_id );
}