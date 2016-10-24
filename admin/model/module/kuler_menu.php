<?php

class ModelModuleKulerMenu extends Model
{
	public function getCategories(array $data = array()) {
		foreach ($data as &$value) {
			$value = urldecode($value);
		}

		$sql = "SELECT cp.category_id AS category_id,
					GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name,
					c1.parent_id, c1.sort_order
				FROM " . DB_PREFIX . "category_path cp
				LEFT JOIN ". DB_PREFIX ."category_to_store c2s
					ON (cp.category_id = c2s.category_id)
				LEFT JOIN " . DB_PREFIX . "category c1
					ON (cp.category_id = c1.category_id)
				LEFT JOIN " . DB_PREFIX . "category c2
					ON (cp.path_id = c2.category_id)
				LEFT JOIN " . DB_PREFIX . "category_description cd1
					ON (cp.path_id = cd1.category_id)
				LEFT JOIN " . DB_PREFIX . "category_description cd2
					ON (cp.category_id = cd2.category_id)
				WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "'
					AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$data['filter_name'] = urldecode($data['filter_name']);

			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['store_id'])) {
			$sql .= ' AND c2s.store_id = ' . intval($data['store_id']);
		} else {
			$sql .= ' AND c2s.store_id = 0';
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

		$query = $this->db->query($sql);

		return $query->rows;
	}


	public function getProducts($data = array())
	{
		$sql = "SELECT *
				FROM " . DB_PREFIX . "product p
				LEFT JOIN ". DB_PREFIX ."product_to_store p2s
					ON (p.product_id = p2s.product_id)
				LEFT JOIN " . DB_PREFIX . "product_description pd
					ON (p.product_id = pd.product_id)
				WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$data['filter_name'] = urldecode($data['filter_name']);

			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['store_id'])) {
			$sql .= " AND p2s.store_id = " . intval($data['store_id']);
		} else {
			$sql .= " AND p2s.store_id = 0";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
}
