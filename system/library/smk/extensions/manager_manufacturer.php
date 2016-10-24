<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_manager.php' );

class SeoMegaPack_ManagerManufacturer extends SeoMegaPack_AbstractManager {
	
	/**
	 * @var int
	 */
	protected $_sort			= -2;
	
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
		return 'Manufacturer';
	}
	
	////////////////////////////////////////////////////////////////////////////// 
	
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
				" . DB_PREFIX . "manufacturer_smp
			WHERE
				manufacturer_id=" . $id . " AND language_id=" . $language_id . "
		");
		
		// usuń SEO tytuł i SEO nagłówek
		$this->_controller->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_smp WHERE manufacturer_id=" . $id . " AND language_id=" . $language_id );
				
		// ustaw SEO tytuł i/lub SEO nagłówek
		if( ! empty( $post['seo_title'] ) || ! empty( $post['seo_h1_title'] ) || ! empty( $post['meta_description'] ) || ! empty( $post['meta_keyword'] ) || ! empty( $post['tag'] ) ) {			
			$this->_controller->db->query("
				INSERT INTO
					" . DB_PREFIX . "manufacturer_smp
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
					tag_ag = '" . ( $current->num_rows && $post['tags'] == $current->row['tag'] ? $current->row['tag_ag'] : '0' ) . "',
					description = '" . $this->_controller->db->escape( $current->num_rows ? $current->row['description'] : '' ) . "',
					description_ag = '" . ( $current->num_rows ? $current->row['description_ag'] : '0' ) . "',
					manufacturer_id=" . $id . ",
					language_id=" . $language_id . "
			");
		}
				
		// aktualizuj alias URL
		$this->_updateUrlAlias( 'manufacturer', $id, $language_id, $post['seo_url'] );
	}
	
	protected function _updateUrlAlias( $type, $id, $language_id, $keyword ) {
		// usuń alians URL dla aktualnego języka
		$this->_controller->db->query("
			DELETE FROM " . DB_PREFIX . "url_alias WHERE query='" . $type . "_id=" . $id . "' AND ( smp_language_id = NULL OR smp_language_id = '" . (int) $language_id . "' )
		" );

		// ustaw nowy alians URL jeśli użytkownik go podał
		if( ! empty( $keyword ) ) {
			$this->_controller->db->query("
				INSERT INTO
					" . DB_PREFIX . "url_alias
				SET
					keyword='" . $this->_controller->db->escape( $this->_controller->_createUniqueKeyword( $keyword, NULL, $type . '_id=' . $id ) ) . "',
					query='" . $type . "_id=" . $id . "',
					smp_language_id='" . (int) $language_id . "'
			");
		}
	}
	
	/**
	 * @return string
	 */
	public function getQuery( $data, $session ) {
		$query = '
			SELECT
				IF( ms.name IS NULL OR ms.name = "", m.name, ms.name ) AS name,
				m.manufacturer_id AS id,
				ms.meta_title AS seo_title,
				ms.smp_h1_title AS seo_h1_title,
				ms.meta_description,
				ms.meta_keyword,
				ms.tag' . ( $this->_queryCount && empty( $session['smp_manager_filter'][$data['type']]['filter_seo_url'] ) ? '' : ',
				(
					SELECT
						keyword
					FROM
						' . DB_PREFIX . 'url_alias
					WHERE
						query = CONCAT( "manufacturer_id=", m.manufacturer_id ) AND ( smp_language_id IS NULL OR smp_language_id = ' . $data['language_id'] . ' ) 
					ORDER BY
						smp_language_id DESC
					LIMIT
						1
				) AS seo_url' ) . '
			FROM
				' . DB_PREFIX . 'manufacturer m
			LEFT JOIN
				' . DB_PREFIX . 'manufacturer_smp ms
			ON
				m.manufacturer_id = ms.manufacturer_id AND ms.language_id = ' . $data['language_id'] . '
		';
		
		$where	= array();
		$having	= array();
				
		// Nazwa
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_name'] ) )
			$where[] = " m.name LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_name'] ) . "%'";
				
		// SEO tytuł
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_seo_title'] ) )
			$where[] = " ms.meta_title LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_seo_title'] ) . "%'";
				
		// SEO nagłówek
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_seo_h1_title'] ) )
			$where[] = " ms.smp_h1_title LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_seo_h1_title'] ) . "%'";
				
		// Meta słowa kluczowe
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_meta_keyword'] ) )
			$where[] = " ms.meta_keyword LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_meta_keyword'] ) . "%'";
				
		// Meta opis
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_meta_description'] ) )
			$where[] = " ms.meta_description LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_meta_description'] ) . "%'";
				
		// Tagi
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_tag'] ) )
			$where[] = " ms.tag LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_tag'] ) . "%'";
				
		// Alians URL
		if( ! empty( $session['smp_manager_filter'][$data['type']]['filter_seo_url'] ) )
			$having[] = " seo_url LIKE '%" . $this->_controller->db->escape( $session['smp_manager_filter'][$data['type']]['filter_seo_url'] ) . "%'";
				
		if( $where )
			$query .= ' WHERE ' . implode( ' AND ', $where );
				
		if( $having )
			$query .= ' HAVING ' . implode( ' AND ', $having );
				
		$query .= '
			ORDER BY
				m.name
		';
		
		return $query;
	}
}