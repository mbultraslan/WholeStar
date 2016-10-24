<?php
class ModelEbayChannelCategoryMappingDao extends Model {
	
	public function saveCategoriesMapping($mappings) {
		$sql = "insert into `" . DB_PREFIX . "channel_ebay_category_mapping` (category_id, ebay_store_category_id) values ";
		$values = array();
		$del = array();
		foreach ($mappings as $ebayStoreCategoryId => $categoryId) {
			if(empty($categoryId)) {
				$del[] = $this->db->escape($ebayStoreCategoryId);
			} else {
				$values[] = "('" . $this->db->escape($categoryId) . "', '" .  $this->db->escape($ebayStoreCategoryId) ."')";
			}
		}
		
		if(!empty($del)) {
			$this->db->query("delete from `" . DB_PREFIX . "channel_ebay_category_mapping` where ebay_store_category_id in (" . implode(",", $del) . ")");
		}
		
		if(!empty($values)) {
			$sql.= implode(",", $values) . "ON DUPLICATE KEY UPDATE category_id=VALUES(category_id), ebay_store_category_id = VALUES(ebay_store_category_id)";
			$this->db->query($sql);
		}
// 		$this->db->query("DELETE cm from " . DB_PREFIX . "channel_ebay_category_mapping cm
// 							LEFT JOIN " . DB_PREFIX . "channel_ebay_store_category esc on esc.id = cm.ebay_store_category_id
// 							WHERE esc.id is null");
	}
	
	public function getCategoriesMappings() {
		return $this->db->query("select * from `" . DB_PREFIX . "channel_ebay_category_mapping`")->rows;
	}
	
	
	public function getCategories() {
		$rows = $this->db->query("SELECT c.category_id as category_id, c.parent_id as parent_id, cd.name as name from `" . DB_PREFIX . "category` c "
				." left join `" . DB_PREFIX . "category_description` cd on cd.category_id = c.category_id " .
				" where cd.language_id = '" . $this->config->get('config_language_id') . "' order by cd.name asc ")->rows;
		
		$sort = array();
		foreach ($rows as $k => $row) {
			$rows[$k]['path'] = $this->getCategoryPath($row['category_id'], $rows);
			$sort[$k] = $rows[$k]['path'];
		}
		
		array_multisort($sort, SORT_ASC, $rows);
		return $rows;
	}
	
	public function getEbayStoreCategories() {
		$rows = $this->db->query("SELECT id as category_id, parent_id as parent_id, name from `" . DB_PREFIX . "channel_ebay_store_category` order by name asc ")->rows;
	
		$sort = array();
		foreach ($rows as $k => $row) {
			$rows[$k]['path'] = $this->getCategoryPath($row['category_id'], $rows);
			$sort[$k] = $rows[$k]['path'];
		}
	
		array_multisort($sort, SORT_ASC, $rows);
		return $rows;
	}
	
	
	
	
	private function getCategoryPath($id, $rows) {
		
		foreach ($rows as $row) {
			if($row['category_id'] == $id) {
				if(!empty($row['parent_id'])) {
					if($row['parent_id'] != $row['category_id']) {
						return  $this->getCategoryPath($row['parent_id'], $rows) . ' > ' . $row['name'];
					} else {
						return $row['name'];
					}
				} else {
					return $row['name'];
				}
			} 
		}
		
	}
	
	
	
}
?>