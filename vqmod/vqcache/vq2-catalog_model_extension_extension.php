<?php
class ModelExtensionExtension extends Model {

				public function getInstalled($type) {
					$extension_data = array();

					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");

					foreach ($query->rows as $result) {
						$extension_data[] = $result['code'];
					}

					return $extension_data;
				}
			
	function getExtensions($type) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");

		return $query->rows;
	}
}