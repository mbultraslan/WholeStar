<?php
class ModelCatalogManufacturer extends Model {
	
				public function getManufacturerSMP($manufacturer_id) {
					$manufacturer_data = array();

					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_smp WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

					foreach ($query->rows as $result) {
						$manufacturer_data[$result['language_id']] = $result;
					}

					return $manufacturer_data;
				}
			
	public function addManufacturer($data) {
		$this->event->trigger('pre.admin.manufacturer.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "'");

		$manufacturer_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET image = '" . $this->db->escape($data['image']) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		}

		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			
				if( $this->config->get('smp_sug_is_install') ) {
					if( ! is_array( $data['keyword'] ) )
						$data['keyword'] = array( $this->config->get('config_language_id') => $data['keyword'] );

					foreach( $data['keyword'] as $language_id => $keyword ) {
						if( $keyword != '' )
							$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($keyword) . "', smp_language_id = '" . $this->db->escape($language_id) . "'");
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
				}
			
		}


				if( $this->config->get( 'smp_is_install' ) && in_array( __FUNCTION__, array( 'editManufacturer' ) ) ) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_smp WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
				}
				
				if( $this->config->get( 'smp_is_install' ) && ! empty( $data['manufacturer_description'] ) ) {				
					foreach ($data['manufacturer_description'] as $language_id => $value) {
						if( ! empty( $value['meta_title'] ) || ! empty( $value['smp_h1_title'] ) || ! empty( $value['meta_description'] ) || ! empty( $value['meta_keyword'] ) || ! empty( $value['tag'] ) || ! empty( $value['name'] ) ) {
							$this->db->query("
								INSERT INTO 
									" . DB_PREFIX . "manufacturer_smp 
								SET 
									meta_keyword_ag = '" . ( ! isset( $value['meta_keyword-src'] ) || $value['meta_keyword'] != $value['meta_keyword-src'] ? '0' : $value['meta_keyword-ag'] ) . "',
									meta_description_ag = '" . ( ! isset( $value['meta_description-src'] ) || $value['meta_description'] != $value['meta_description-src'] ? '0' : $value['meta_description-ag'] ) . "',
									meta_title_ag = '" . ( ! isset( $value['meta_title-src'] ) || $value['meta_title'] != $value['meta_title-src'] ? '0' : $value['meta_title-ag'] ) . "',
									smp_h1_title_ag = '" . ( ! isset( $value['smp_h1_title-src'] ) || $value['smp_h1_title'] != $value['smp_h1_title-src'] ? '0' : $value['smp_h1_title-ag'] ) . "',
									tag_ag = '" . ( ! isset( $value['tag-src'] ) || $value['tag'] != $value['tag-src'] ? '0' : $value['tag-ag'] ) . "',
									description_ag = '" . ( ! isset( $value['description-src'] ) || $value['description'] != $value['description-src'] ? '0' : $value['description-ag'] ) . "',
						
									manufacturer_id = '" . (int)$manufacturer_id . "', 
									language_id = '" . (int)$language_id . "', 
									meta_title = '" . $this->db->escape($value['meta_title']) . "', 
									smp_h1_title = '" . $this->db->escape($value['smp_h1_title']) . "', 
									meta_description = '" . $this->db->escape($value['meta_description']) . "', 
									meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', 
									tag = '" . $this->db->escape($value['tag']) . "', 
									description = '" . $this->db->escape($value['description']) . "', 
									name = '" . $this->db->escape($value['name']) . "',
									url_alias_exists = '" . ( ! empty( $data['keyword'][$language_id] ) ? $language_id : 0 ) . "'
								"
							);
						}
					}
				}
				
				if( in_array( __FUNCTION__, array( 'addManufacturer', 'editManufacturer' ) ) && $this->config->get( 'smp_at_is_install' ) ) {
					require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/auto_generator.php' );

					SeoMegaPack_AutoGenerator::newInstance( $this )
						->createSeoUrlManufacturer( $manufacturer_id )
						->createMetaDescriptionManufacturer( $manufacturer_id )
						->createMetaKeywordsManufacturer( $manufacturer_id )
						->createSeoTitlesManufacturer( $manufacturer_id )
						->createSeoHeaderTitlesManufacturer( $manufacturer_id )
						->createTagsManufacturer( $manufacturer_id )
						->createDescriptionManufacturer( $manufacturer_id );
				}
			
		$this->cache->delete('manufacturer');

		$this->event->trigger('post.admin.manufacturer.add', $manufacturer_id);

		return $manufacturer_id;
	}

	public function editManufacturer($manufacturer_id, $data) {
		$this->event->trigger('pre.admin.manufacturer.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET image = '" . $this->db->escape($data['image']) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "'");

		if ($data['keyword']) {
			
				if( $this->config->get('smp_sug_is_install') ) {
					if( ! is_array( $data['keyword'] ) )
						$data['keyword'] = array( $this->config->get('config_language_id') => $data['keyword'] );

					foreach( $data['keyword'] as $language_id => $keyword ) {
						if( $keyword != '' )
							$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($keyword) . "', smp_language_id = '" . $this->db->escape($language_id) . "'");
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
				}
			
		}


				if( $this->config->get( 'smp_is_install' ) && in_array( __FUNCTION__, array( 'editManufacturer' ) ) ) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_smp WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
				}
				
				if( $this->config->get( 'smp_is_install' ) && ! empty( $data['manufacturer_description'] ) ) {				
					foreach ($data['manufacturer_description'] as $language_id => $value) {
						if( ! empty( $value['meta_title'] ) || ! empty( $value['smp_h1_title'] ) || ! empty( $value['meta_description'] ) || ! empty( $value['meta_keyword'] ) || ! empty( $value['tag'] ) || ! empty( $value['name'] ) ) {
							$this->db->query("
								INSERT INTO 
									" . DB_PREFIX . "manufacturer_smp 
								SET 
									meta_keyword_ag = '" . ( ! isset( $value['meta_keyword-src'] ) || $value['meta_keyword'] != $value['meta_keyword-src'] ? '0' : $value['meta_keyword-ag'] ) . "',
									meta_description_ag = '" . ( ! isset( $value['meta_description-src'] ) || $value['meta_description'] != $value['meta_description-src'] ? '0' : $value['meta_description-ag'] ) . "',
									meta_title_ag = '" . ( ! isset( $value['meta_title-src'] ) || $value['meta_title'] != $value['meta_title-src'] ? '0' : $value['meta_title-ag'] ) . "',
									smp_h1_title_ag = '" . ( ! isset( $value['smp_h1_title-src'] ) || $value['smp_h1_title'] != $value['smp_h1_title-src'] ? '0' : $value['smp_h1_title-ag'] ) . "',
									tag_ag = '" . ( ! isset( $value['tag-src'] ) || $value['tag'] != $value['tag-src'] ? '0' : $value['tag-ag'] ) . "',
									description_ag = '" . ( ! isset( $value['description-src'] ) || $value['description'] != $value['description-src'] ? '0' : $value['description-ag'] ) . "',
						
									manufacturer_id = '" . (int)$manufacturer_id . "', 
									language_id = '" . (int)$language_id . "', 
									meta_title = '" . $this->db->escape($value['meta_title']) . "', 
									smp_h1_title = '" . $this->db->escape($value['smp_h1_title']) . "', 
									meta_description = '" . $this->db->escape($value['meta_description']) . "', 
									meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', 
									tag = '" . $this->db->escape($value['tag']) . "', 
									description = '" . $this->db->escape($value['description']) . "', 
									name = '" . $this->db->escape($value['name']) . "',
									url_alias_exists = '" . ( ! empty( $data['keyword'][$language_id] ) ? $language_id : 0 ) . "'
								"
							);
						}
					}
				}
				
				if( in_array( __FUNCTION__, array( 'addManufacturer', 'editManufacturer' ) ) && $this->config->get( 'smp_at_is_install' ) ) {
					require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/auto_generator.php' );

					SeoMegaPack_AutoGenerator::newInstance( $this )
						->createSeoUrlManufacturer( $manufacturer_id )
						->createMetaDescriptionManufacturer( $manufacturer_id )
						->createMetaKeywordsManufacturer( $manufacturer_id )
						->createSeoTitlesManufacturer( $manufacturer_id )
						->createSeoHeaderTitlesManufacturer( $manufacturer_id )
						->createTagsManufacturer( $manufacturer_id )
						->createDescriptionManufacturer( $manufacturer_id );
				}
			
		$this->cache->delete('manufacturer');

		$this->event->trigger('post.admin.manufacturer.edit');
	}

	public function deleteManufacturer($manufacturer_id) {

				if( $this->config->get( 'smp_is_install' ) ) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_smp WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
				}
			
		$this->event->trigger('pre.admin.manufacturer.delete', $manufacturer_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "'");


				if( $this->config->get( 'smp_is_install' ) && in_array( __FUNCTION__, array( 'editManufacturer' ) ) ) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_smp WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
				}
				
				if( $this->config->get( 'smp_is_install' ) && ! empty( $data['manufacturer_description'] ) ) {				
					foreach ($data['manufacturer_description'] as $language_id => $value) {
						if( ! empty( $value['meta_title'] ) || ! empty( $value['smp_h1_title'] ) || ! empty( $value['meta_description'] ) || ! empty( $value['meta_keyword'] ) || ! empty( $value['tag'] ) || ! empty( $value['name'] ) ) {
							$this->db->query("
								INSERT INTO 
									" . DB_PREFIX . "manufacturer_smp 
								SET 
									meta_keyword_ag = '" . ( ! isset( $value['meta_keyword-src'] ) || $value['meta_keyword'] != $value['meta_keyword-src'] ? '0' : $value['meta_keyword-ag'] ) . "',
									meta_description_ag = '" . ( ! isset( $value['meta_description-src'] ) || $value['meta_description'] != $value['meta_description-src'] ? '0' : $value['meta_description-ag'] ) . "',
									meta_title_ag = '" . ( ! isset( $value['meta_title-src'] ) || $value['meta_title'] != $value['meta_title-src'] ? '0' : $value['meta_title-ag'] ) . "',
									smp_h1_title_ag = '" . ( ! isset( $value['smp_h1_title-src'] ) || $value['smp_h1_title'] != $value['smp_h1_title-src'] ? '0' : $value['smp_h1_title-ag'] ) . "',
									tag_ag = '" . ( ! isset( $value['tag-src'] ) || $value['tag'] != $value['tag-src'] ? '0' : $value['tag-ag'] ) . "',
									description_ag = '" . ( ! isset( $value['description-src'] ) || $value['description'] != $value['description-src'] ? '0' : $value['description-ag'] ) . "',
						
									manufacturer_id = '" . (int)$manufacturer_id . "', 
									language_id = '" . (int)$language_id . "', 
									meta_title = '" . $this->db->escape($value['meta_title']) . "', 
									smp_h1_title = '" . $this->db->escape($value['smp_h1_title']) . "', 
									meta_description = '" . $this->db->escape($value['meta_description']) . "', 
									meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', 
									tag = '" . $this->db->escape($value['tag']) . "', 
									description = '" . $this->db->escape($value['description']) . "', 
									name = '" . $this->db->escape($value['name']) . "',
									url_alias_exists = '" . ( ! empty( $data['keyword'][$language_id] ) ? $language_id : 0 ) . "'
								"
							);
						}
					}
				}
				
				if( in_array( __FUNCTION__, array( 'addManufacturer', 'editManufacturer' ) ) && $this->config->get( 'smp_at_is_install' ) ) {
					require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/auto_generator.php' );

					SeoMegaPack_AutoGenerator::newInstance( $this )
						->createSeoUrlManufacturer( $manufacturer_id )
						->createMetaDescriptionManufacturer( $manufacturer_id )
						->createMetaKeywordsManufacturer( $manufacturer_id )
						->createSeoTitlesManufacturer( $manufacturer_id )
						->createSeoHeaderTitlesManufacturer( $manufacturer_id )
						->createTagsManufacturer( $manufacturer_id )
						->createDescriptionManufacturer( $manufacturer_id );
				}
			
		$this->cache->delete('manufacturer');

		$this->event->trigger('post.admin.manufacturer.delete', $manufacturer_id);
	}

	public function getManufacturer($manufacturer_id) {
		$query = $this->db->query("SELECT DISTINCT *, " . ( $this->config->get('smp_sug_is_install') ? "(SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "' AND (smp_language_id IS NULL OR smp_language_id = " . $this->config->get('config_language_id') . ") ORDER BY smp_language_id DESC LIMIT 1 ) AS keyword" : "(SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "' LIMIT 1 ) AS keyword" ) . " FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row;
	}

	public function getManufacturers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "manufacturer";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getManufacturerStores($manufacturer_id) {
		$manufacturer_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		foreach ($query->rows as $result) {
			$manufacturer_store_data[] = $result['store_id'];
		}

		return $manufacturer_store_data;
	}

	public function getTotalManufacturers() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manufacturer");

		return $query->row['total'];
	}
}