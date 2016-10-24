<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_manager.php' );

class SeoMegaPack_ManagerProduct extends SeoMegaPack_AbstractManager {
	
	/**
	 * @var int
	 */
	protected $_sort			= -4;
	
	/**
	 * @var string 
	 */
	protected $_version		= '2';
	
	public function __construct($controller) {
		parent::__construct($controller);
		
		$this->_hasColumnMetaKeywords		= true;
		$this->_hasColumnMetaDescription	= true;
		$this->_hasColumnTags				= true;
	}
	
	/**
	 * @return string
	 */
	public function getTabLabel() {
		return 'Product';
	}
	
	////////////////////////////////////////////////////////////////////////////
	
	/**
	 * @param array $post
	 * @param int $id
	 * @param int $language_id
	 * @return void
	 */
	public function save( $post, $id, $language_id ) {
		$current = $this->_controller->db->query("
			SELECT
				*
			FROM
				" . DB_PREFIX . "product_description
			WHERE
				product_id=" . $id . " AND language_id=" . $language_id . "
		");
		
		// SEO tytuł, SEO nagłówek, Meta opis, Meta słowa kluczowe, Tagi
		$this->_controller->db->query("
			UPDATE
				" . DB_PREFIX . "product_description
			SET
				meta_title='" . $this->_controller->db->escape( $post['seo_title'] ) . "',
				meta_title_ag = '" . ( $current->num_rows && $post['seo_title'] == $current->row['meta_title'] ? $current->row['meta_title_ag'] : '0' ) . "',
				smp_h1_title='" . $this->_controller->db->escape( $post['seo_h1_title'] ) . "',
				smp_h1_title_ag = '" . ( $current->num_rows && $post['seo_h1_title'] == $current->row['smp_h1_title'] ? $current->row['smp_h1_title_ag'] : '0' ) . "',
				meta_description='" . $this->_controller->db->escape( $post['meta_description'] ) . "',
				meta_description_ag = '" . ( $current->num_rows && $post['meta_description'] == $current->row['meta_description'] ? $current->row['meta_description_ag'] : '0' ) . "',
				meta_keyword='" . $this->_controller->db->escape( $post['meta_keywords'] ) . "',
				meta_keyword_ag = '" . ( $current->num_rows && $post['meta_keywords'] == $current->row['meta_keyword'] ? $current->row['meta_keyword_ag'] : '0' ) . "',
				tag='" . $this->_controller->db->escape( $post['tags'] ) . "',
				tag_ag = '" . ( $current->num_rows && $post['tags'] == $current->row['tag'] ? $current->row['tag_ag'] : '0' ) . "'
			WHERE
				product_id = " . $id . " AND
				language_id = " . $language_id . "
		");
				
		// aktualizuj alias URL
		$this->_updateUrlAlias( 'product', $id, $language_id, $post['seo_url'] );
	}
	
	public function addUrlAlias( $data, & $items ) {
		if( ! $items )
			return $items;
		
		$ids	= array();
		$q		= '';
		$rows	= array();
		
		foreach( $items as $item ) {
			$ids[]				= $item['id'];
			
			if( ! isset( $item['seo_url'] ) )
				$item['seo_url'] = '';
			
			$rows[$item['id']]	= $item;
			
			$q .= $q ? ' OR ' : '';
			$q .= '( query = "product_id=' . $item['id'] . '" AND ( smp_language_id IS NULL OR smp_language_id = ' . $data['language_id'] . ' ) )';
		}
		
		$items	= array();
		$q		= '
			SELECT
				*
			FROM
				' . DB_PREFIX . 'url_alias
			WHERE
				' . $q . '
			ORDER BY
				smp_language_id ASC
		';
		
		foreach( $this->_controller->db->query( $q )->rows as $item ) {
			list( $a, $b ) = explode( '=', $item['query'] );
			
			$rows[$b]['seo_url'] = $item['keyword'];
		}
		
		return $rows;
	}
	
	private function _preGetQuery() {
		if( ! $this->_controller->config->get( 'smp_mp_check' ) ) {
			$query = $this->_controller->db->query('SHOW COLUMNS FROM ' . DB_PREFIX . 'product_description LIKE "tag"');

			// sprawdź czy istnieje takie pole
			if( ! $query->num_rows )
				$this->_controller->db->query('ALTER TABLE ' . DB_PREFIX . 'product_description ADD `tag` VARCHAR(255) NULL DEFAULT NULL');
			
			
			$this->_controller->load->model('setting/setting');
			$this->_controller->model_setting_setting->editSetting('smp_mp_check', array(
				'smp_mp_check' => '1'
			));
		}
	}
	
	/**
	 * @return string
	 */
	public function getQuery( $data, $session ) {
		$this->_preGetQuery();
		
		$query = '
			SELECT
				pd.name,
				pd.product_id AS id,
				pd.meta_title AS seo_title,
				pd.smp_h1_title AS seo_h1_title,
				pd.meta_description,
				pd.meta_keyword,
				pd.tag' . ( $this->_queryCount && empty( $session['smp_manager_filter'][$data['type']]['filter_seo_url'] ) ? '' : ',
				(
					SELECT
						keyword
					FROM
						' . DB_PREFIX . 'url_alias
					WHERE
						query = CONCAT( "product_id=", pd.product_id ) AND ( smp_language_id IS NULL OR smp_language_id = ' . $data['language_id'] . ' ) 
					ORDER BY
						smp_language_id DESC
					LIMIT
						1
				) AS seo_url' ) . '
			FROM
				' . DB_PREFIX . 'product_description pd
			WHERE
				pd.language_id = ' . $data['language_id'];
				
		// Nazwa
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_name'] ) )
			$query .= " AND pd.name LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_name'] ) . "%'";
				
		// SEO tytuł
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_seo_title'] ) )
			$query .= " AND pd.meta_title LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_seo_title'] ) . "%'";
				
		// SEO nagłówek
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_seo_h1_title'] ) )
			$query .= " AND pd.smp_h1_title LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_seo_h1_title'] ) . "%'";
				
		// Meta słowa kluczowe
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_meta_keyword'] ) )
			$query .= " AND pd.meta_keyword LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_meta_keyword'] ) . "%'";
				
		// Meta opis
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_meta_description'] ) )
			$query .= " AND pd.meta_description LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_meta_description'] ) . "%'";
				
		// Tagi
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_tag'] ) )
			$query .= " AND pd.tag LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_tag'] ) . "%'";
				
		// Alians URL
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_seo_url'] ) )
			$query .= " HAVING seo_url LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_seo_url'] ) . "%'";
				
		$query .= '
			ORDER BY
				pd.name
		';
		
		return $query;
	}
}