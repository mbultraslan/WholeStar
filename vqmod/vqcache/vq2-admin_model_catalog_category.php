<?php
class ModelCatalogCategory extends Model {
	public function addCategory($data) {
		$this->event->trigger('pre.admin.category.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");

		$category_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		foreach ($data['category_description'] as $language_id => $value) {
							
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET" . ( 
						$this->config->get( 'smp_is_install' ) ? " 
							meta_keyword_ag = '" . ( ! isset( $value['meta_keyword-src'] ) || $value['meta_keyword'] != $value['meta_keyword-src'] ? '0' : $value['meta_keyword-ag'] ) . "',
							meta_description_ag = '" . ( ! isset( $value['meta_description-src'] ) || $value['meta_description'] != $value['meta_description-src'] ? '0' : $value['meta_description-ag'] ) . "',
							meta_title_ag = '" . ( ! isset( $value['meta_title-src'] ) || $value['meta_title'] != $value['meta_title-src'] ? '0' : $value['meta_title-ag'] ) . "',
							smp_h1_title_ag = '" . ( ! isset( $value['smp_h1_title-src'] ) || $value['smp_h1_title'] != $value['smp_h1_title-src'] ? '0' : $value['smp_h1_title-ag'] ) . "',
							tag_ag = '" . ( ! isset( $value['tag-src'] ) || $value['tag'] != $value['tag-src'] ? '0' : $value['tag-ag'] ) . "',
							description_ag = '" . ( ! isset( $value['description-src'] ) || $value['description'] != $value['description-src'] ? '0' : $value['description-ag'] ) . "',
								
							smp_h1_title = '" . $this->db->escape($value['smp_h1_title']) . "',
							tag = '" . $this->db->escape($value['tag']) . "',
							url_alias_exists = '" . ( ! empty( $data['keyword'][$language_id] ) ? $language_id : 0 ) . "',
						" : ''
					) . "
			 category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$level = 0;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

			$level++;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");

		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		// Set which layout to use with this category
		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			
				if( $this->config->get('smp_sug_is_install') ) {					
					if( ! is_array( $data['keyword'] ) ) {
						$data['keyword'] = array( $this->config->get('config_language_id') => $data['keyword'] );
					}

					foreach( $data['keyword'] as $language_id => $keyword ) {
						if( $keyword != '' ) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($keyword) . "', smp_language_id = '" . $this->db->escape($language_id) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
				}
			
		}


				if( in_array( __FUNCTION__, array( 'addCategory', 'editCategory' ) ) && $this->config->get( 'smp_at_is_install' ) ) {
					require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/auto_generator.php' );

					SeoMegaPack_AutoGenerator::newInstance( $this )
						->createSeoUrlCategory( $category_id )
						->createMetaKeywordsCategory( $category_id )
						->createMetaDescriptionCategory( $category_id )
						->createSeoTitlesCategory( $category_id )
						->createSeoHeaderTitlesCategory( $category_id )
						->createTagsCategory( $category_id )
						->createDescriptionCategory( $category_id );
				}
			
		$this->cache->delete('category');

		$this->event->trigger('post.admin.category.add', $category_id);

		return $category_id;
	}

	public function editCategory($category_id, $data) {
		$this->event->trigger('pre.admin.category.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($data['category_description'] as $language_id => $value) {
							
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET" . ( 
						$this->config->get( 'smp_is_install' ) ? " 
							meta_keyword_ag = '" . ( ! isset( $value['meta_keyword-src'] ) || $value['meta_keyword'] != $value['meta_keyword-src'] ? '0' : $value['meta_keyword-ag'] ) . "',
							meta_description_ag = '" . ( ! isset( $value['meta_description-src'] ) || $value['meta_description'] != $value['meta_description-src'] ? '0' : $value['meta_description-ag'] ) . "',
							meta_title_ag = '" . ( ! isset( $value['meta_title-src'] ) || $value['meta_title'] != $value['meta_title-src'] ? '0' : $value['meta_title-ag'] ) . "',
							smp_h1_title_ag = '" . ( ! isset( $value['smp_h1_title-src'] ) || $value['smp_h1_title'] != $value['smp_h1_title-src'] ? '0' : $value['smp_h1_title-ag'] ) . "',
							tag_ag = '" . ( ! isset( $value['tag-src'] ) || $value['tag'] != $value['tag-src'] ? '0' : $value['tag-ag'] ) . "',
							description_ag = '" . ( ! isset( $value['description-src'] ) || $value['description'] != $value['description-src'] ? '0' : $value['description-ag'] ) . "',
								
							smp_h1_title = '" . $this->db->escape($value['smp_h1_title']) . "',
							tag = '" . $this->db->escape($value['tag']) . "',
							url_alias_exists = '" . ( ! empty( $data['keyword'][$language_id] ) ? $language_id : 0 ) . "',
						" : ''
					) . "
			 category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE path_id = '" . (int)$category_id . "' ORDER BY level ASC");

		if ($query->rows) {
			foreach ($query->rows as $category_path) {
				// Delete the path below the current one
				$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' AND level < '" . (int)$category_path['level'] . "'");

				$path = array();

				// Get the nodes new parents
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Get whats left of the nodes current path
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Combine the paths with a new level
				$level = 0;

				foreach ($path as $path_id) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_path['category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

					$level++;
				}
			}
		} else {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_id . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', level = '" . (int)$level . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "'");

		if ($data['keyword']) {
			
				if( $this->config->get('smp_sug_is_install') ) {					
					if( ! is_array( $data['keyword'] ) ) {
						$data['keyword'] = array( $this->config->get('config_language_id') => $data['keyword'] );
					}

					foreach( $data['keyword'] as $language_id => $keyword ) {
						if( $keyword != '' ) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($keyword) . "', smp_language_id = '" . $this->db->escape($language_id) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
				}
			
		}


				if( in_array( __FUNCTION__, array( 'addCategory', 'editCategory' ) ) && $this->config->get( 'smp_at_is_install' ) ) {
					require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/auto_generator.php' );

					SeoMegaPack_AutoGenerator::newInstance( $this )
						->createSeoUrlCategory( $category_id )
						->createMetaKeywordsCategory( $category_id )
						->createMetaDescriptionCategory( $category_id )
						->createSeoTitlesCategory( $category_id )
						->createSeoHeaderTitlesCategory( $category_id )
						->createTagsCategory( $category_id )
						->createDescriptionCategory( $category_id );
				}
			
		$this->cache->delete('category');

		$this->event->trigger('post.admin.category.edit', $category_id);
	}

	public function deleteCategory($category_id) {
		$this->event->trigger('pre.admin.category.delete', $category_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_path WHERE category_id = '" . (int)$category_id . "'");

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_path WHERE path_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$this->deleteCategory($result['category_id']);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "'");


				if( in_array( __FUNCTION__, array( 'addCategory', 'editCategory' ) ) && $this->config->get( 'smp_at_is_install' ) ) {
					require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/auto_generator.php' );

					SeoMegaPack_AutoGenerator::newInstance( $this )
						->createSeoUrlCategory( $category_id )
						->createMetaKeywordsCategory( $category_id )
						->createMetaDescriptionCategory( $category_id )
						->createSeoTitlesCategory( $category_id )
						->createSeoHeaderTitlesCategory( $category_id )
						->createTagsCategory( $category_id )
						->createDescriptionCategory( $category_id );
				}
			
		$this->cache->delete('category');

		$this->event->trigger('post.admin.category.delete', $category_id);
	}

	public function repairCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$parent_id . "'");

		foreach ($query->rows as $category) {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category['category_id'] . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$parent_id . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$category['category_id'] . "', level = '" . (int)$level . "'");

			$this->repairCategories($category['category_id']);
		}
	}

	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path, " . ( $this->config->get('smp_sug_is_install') ? "(SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "' AND (smp_language_id IS NULL OR smp_language_id = " . $this->config->get('config_language_id') . ") ORDER BY smp_language_id DESC LIMIT 1 ) AS keyword" : "(SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "' LIMIT 1 ) AS keyword" ) . " FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getCategories($data = array()) {
		$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY cp.category_id";

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
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

	public function getCategoryDescriptions($category_id) {
		$category_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],

				'meta_title' => empty( $result['meta_title'] ) ? '' : $result['meta_title'],
				'smp_h1_title' => empty( $result['smp_h1_title'] ) ? '' : $result['smp_h1_title'],
				'tag' => empty( $result['tag'] ) ? '' : $result['tag'],
				
				'meta_keyword_ag' => empty( $result['meta_keyword_ag'] ) ? '0' : '1',
				'meta_description_ag' => empty( $result['meta_description_ag'] ) ? '0' : '1',
				'meta_title_ag' => empty( $result['meta_title_ag'] ) ? '0' : '1',
				'smp_h1_title_ag' => empty( $result['smp_h1_title_ag'] ) ? '0' : '1',
				'tag_ag' => empty( $result['tag_ag'] ) ? '0' : '1',
				'description_ag' => empty( $result['description_ag'] ) ? '0' : '1',
			
				'meta_keyword'     => $result['meta_keyword'],
				'description'      => $result['description']
			);
		}

		return $category_description_data;
	}

	public function getCategoryFilters($category_id) {
		$category_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_filter_data[] = $result['filter_id'];
		}

		return $category_filter_data;
	}

	public function getCategoryStores($category_id) {
		$category_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_store_data[] = $result['store_id'];
		}

		return $category_store_data;
	}

	public function getCategoryLayouts($category_id) {
		$category_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $category_layout_data;
	}

	public function getTotalCategories() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category");

		return $query->row['total'];
	}
	
	public function getTotalCategoriesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}	
}
