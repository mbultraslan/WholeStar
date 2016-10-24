<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_manager.php' );

class SeoMegaPack_ManagerInformation extends SeoMegaPack_AbstractManager {
	
	/**
	 * @var int
	 */
	protected $_sort			= -1;
	
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
		return 'Information';
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
				" . DB_PREFIX . "information_description
			WHERE
				information_id=" . $id . " AND language_id=" . $language_id . "
		");
		
		// SEO tytuł, SEO nagłówek
		$this->_controller->db->query("
			UPDATE
				" . DB_PREFIX . "information_description
			SET
				meta_title = '" . $this->_controller->db->escape( $post['seo_title'] ) . "',
				meta_title_ag = '" . ( $current->num_rows && $post['seo_title'] == $current->row['meta_title'] ? $current->row['meta_title_ag'] : '0' ) . "',
				smp_h1_title = '" . $this->_controller->db->escape( $post['seo_h1_title'] ) . "',
				smp_h1_title_ag = '" . ( $current->num_rows && $post['seo_h1_title'] == $current->row['smp_h1_title'] ? $current->row['smp_h1_title_ag'] : '0' ) . "',
				meta_description = '" . $this->_controller->db->escape( $post['meta_description'] ) . "',
				meta_description_ag = '" . ( $current->num_rows && $post['meta_description'] == $current->row['meta_description'] ? $current->row['meta_description_ag'] : '0' ) . "',
				meta_keyword = '" . $this->_controller->db->escape( $post['meta_keywords'] ) . "',
				meta_keyword_ag = '" . ( $current->num_rows && $post['meta_keywords'] == $current->row['meta_keyword'] ? $current->row['meta_keyword_ag'] : '0' ) . "',
				tag = '" . $this->_controller->db->escape( $post['tags'] ) . "',
				tag_ag = '" . ( $current->num_rows && $post['tags'] == $current->row['tag'] ? $current->row['tag_ag'] : '0' ) . "'
			WHERE
				information_id = " . $id . " AND
				language_id = " . $language_id . "
		");
				
		// aktualizuj alias URL
		$this->_updateUrlAlias( 'information', $id, $language_id, $post['seo_url'] );
	}
	
	/**
	 * @return string
	 */
	public function getQuery( $data, $session ) {
		$query = '
			SELECT
				id.title AS name,
				id.meta_title AS seo_title,
				id.smp_h1_title AS seo_h1_title,
				id.information_id AS id,
				id.meta_description AS meta_description,
				id.meta_keyword AS meta_keyword,
				id.tag AS tag' . ( $this->_queryCount && empty( $session['smp_manager_filter'][$data['type']]['filter_seo_url'] ) ? '' : ',
				(
					SELECT
						keyword
					FROM
						' . DB_PREFIX . 'url_alias
					WHERE
						query = CONCAT( "information_id=", id.information_id ) AND ( smp_language_id IS NULL OR smp_language_id = ' . $data['language_id'] . ' ) 
					ORDER BY
						smp_language_id DESC
					LIMIT
						1
				) AS seo_url' ) . '
			FROM
				' . DB_PREFIX . 'information_description id
			WHERE
				id.language_id = ' . $data['language_id'];
				
		// Nazwa
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_name'] ) )
			$query .= " AND id.title LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_name'] ) . "%'";
				
		// SEO tytuł
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_seo_title'] ) )
			$query .= " AND id.meta_title LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_seo_title'] ) . "%'";
				
		// SEO nagłówek
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_seo_h1_title'] ) )
			$query .= " AND id.smp_h1_title LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_seo_h1_title'] ) . "%'";
				
		// Meta słowa kluczowe
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_meta_keyword'] ) )
			$query .= " AND id.meta_keyword LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_meta_keyword'] ) . "%'";
				
		// Meta opis
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_meta_description'] ) )
			$query .= " AND id.meta_description LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_meta_description'] ) . "%'";
				
		// Tagi
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_tag'] ) )
			$query .= " AND id.tag LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_tag'] ) . "%'";
				
		// Alians URL
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_seo_url'] ) )
			$query .= " HAVING seo_url LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_seo_url'] ) . "%'";
				
		$query .= '
			ORDER BY
				id.title
		';
		
		return $query;
	}
}