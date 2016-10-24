<?php
class ModelEbayChannelTaxDao extends Model {
	
	public function calculate($value, $tax_class_id, $calculate = true) {
		if ($tax_class_id && $calculate) {
			$amount = $this->getTax($value, $tax_class_id);
	
			return $value + $amount;
		} else {
			return $value;
		}

        if ($tax_class_id && $calculate) {
            $amount = 0;

            $tax_rates = $this->getRates($value, $tax_class_id);

            foreach ($tax_rates as $tax_rate) {
                if ($calculate != 'P' && $calculate != 'F') {
                    $amount += $tax_rate['amount'];
                } elseif ($tax_rate['type'] == $calculate) {
                    $amount += $tax_rate['amount'];
                }
            }

            return $value + $amount;
        } else {
            return $value;
        }

	}
	
	public function getTax($value, $tax_class_id) {
		$amount = 0;
	
		$tax_rates = $this->getRates($value, $tax_class_id);
	
		foreach ($tax_rates as $tax_rate) {
			$amount += $tax_rate['amount'];
		}
	
		return $amount;
	}
	
	public function getRates($value, $tax_class_id) {
		$tax_rates = array();
	
		$customer_group_id = $this->config->get('config_customer_group_id');
		$config_country_id = '';
		$config_zone_id = '';
		$ha = false;
		if ($this->config->get('config_tax_default') == 'shipping') {
			$config_country_id = $this->config->get('config_country_id');
			$config_zone_id = $this->config->get('config_zone_id');
			$ha = true;
		}
	
		if ($ha) {
			$tax_query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM "
					 . DB_PREFIX . "tax_rule tr1 LEFT JOIN "
					 . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) INNER JOIN " 
					 . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) LEFT JOIN "
					 . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN "
					 . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.tax_class_id = '" . (int)$tax_class_id 
					. "' AND tr1.based = 'shipping' AND tr2cg.customer_group_id = '" . (int)$customer_group_id 
					. "' AND z2gz.country_id = '" . (int)$config_country_id 
					. "' AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$config_zone_id . "') ORDER BY tr1.priority ASC");
	
			foreach ($tax_query->rows as $result) {
				$tax_rates[$result['tax_rate_id']] = array(
						'tax_rate_id' => $result['tax_rate_id'],
						'name'        => $result['name'],
						'rate'        => $result['rate'],
						'type'        => $result['type'],
						'priority'    => $result['priority']
				);
			}
		}
		
		$config_country_id = '';
		$config_zone_id = '';
		$ha = false;
		if ($this->config->get('config_tax_default') == 'payment') {
			$config_country_id = $this->config->get('config_country_id');
			$config_zone_id = $this->config->get('config_zone_id');
			$ha = true;
		}
	
		if ($ha) {
			$tax_query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " 
					. DB_PREFIX . "tax_rule tr1 LEFT JOIN " 
					. DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) INNER JOIN "
					. DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) LEFT JOIN " 
					. DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " 
					. DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.tax_class_id = '" . (int)$tax_class_id 
					. "' AND tr1.based = 'payment' AND tr2cg.customer_group_id = '" . (int)$customer_group_id 
					. "' AND z2gz.country_id = '" . (int)$config_country_id 
					. "' AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$config_zone_id . "') ORDER BY tr1.priority ASC");
	
			foreach ($tax_query->rows as $result) {
				$tax_rates[$result['tax_rate_id']] = array(
						'tax_rate_id' => $result['tax_rate_id'],
						'name'        => $result['name'],
						'rate'        => $result['rate'],
						'type'        => $result['type'],
						'priority'    => $result['priority']
				);
			}
		}
		
		$config_country_id = $this->config->get('config_country_id');
		$config_zone_id = $this->config->get('config_zone_id');
	
		$tax_query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " 
				. DB_PREFIX . "tax_rule tr1 LEFT JOIN " 
				. DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) INNER JOIN " 
				. DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) LEFT JOIN " 
				. DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " 
				. DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.tax_class_id = '" . (int)$tax_class_id 
				. "' AND tr1.based = 'store' AND tr2cg.customer_group_id = '" . (int)$customer_group_id 
				. "' AND z2gz.country_id = '" . (int)$config_country_id 
				. "' AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$config_zone_id . "') ORDER BY tr1.priority ASC");

		foreach ($tax_query->rows as $result) {
			$tax_rates[$result['tax_rate_id']] = array(
					'tax_rate_id' => $result['tax_rate_id'],
					'name'        => $result['name'],
					'rate'        => $result['rate'],
					'type'        => $result['type'],
					'priority'    => $result['priority']
			);
		}
	
		$tax_rate_data = array();
	
		foreach ($tax_rates as $tax_rate) {
			
			if (isset($tax_rate_data[$tax_rate['tax_rate_id']])) {
				$amount = $tax_rate_data[$tax_rate['tax_rate_id']]['amount'];
			} else {
				$amount = 0;
			}
	
			if ($tax_rate['type'] == 'F') {
				$amount += $tax_rate['rate'];
			} elseif ($tax_rate['type'] == 'P') {
                //$amount = round($value - (100 * $value)/(100 + $tax_rate['rate']), 2);
				$amount += ($value / 100 * $tax_rate['rate']);
			}
	
			$tax_rate_data[$tax_rate['tax_rate_id']] = array(
					'tax_rate_id' => $tax_rate['tax_rate_id'],
					'name'        => $tax_rate['name'],
					'rate'        => $tax_rate['rate'],
					'type'        => $tax_rate['type'],
					'amount'      => $amount
			);
		}
	
		return $tax_rate_data;
	}
	
	public function extractTaxes($value, $tax_class_id, $country_id) {
		$tax_query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " . DB_PREFIX . "tax_rule tr1 "
			. " LEFT JOIN ". DB_PREFIX ."tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) "
			. " LEFT JOIN ". DB_PREFIX ."zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) "
			. " LEFT JOIN ". DB_PREFIX ."geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.tax_class_id = '" . (int)$tax_class_id
			. "' AND tr1.based = 'shipping' "
			. "  AND z2gz.country_id = '" . (int)$country_id
			. "' ORDER BY tr1.priority ASC");
		$tax_rates = array();

		foreach ($tax_query->rows as $result) {
			$tax_rates[$result['tax_rate_id']] = array(
				'tax_rate_id' => $result['tax_rate_id'],
				'name'        => $result['name'],
				'rate'        => $result['rate'],
				'type'        => $result['type'],
				'priority'    => $result['priority']
			);
		}


		$tax_rate_data = array();
		foreach ($tax_rates as $tax_rate) {
			$amount = 0;
			if ($tax_rate['type'] == 'F') {
				$amount = $tax_rate['rate'];
			} elseif ($tax_rate['type'] == 'P') {
				$amount = (double)$value * ($tax_rate['rate'] / 100) / (1 + $tax_rate['rate'] / 100);
			}

			$tax_rate_data[$tax_rate['tax_rate_id']] = array(
				'tax_rate_id' => $tax_rate['tax_rate_id'],
				'name'        => $tax_rate['name'],
				'rate'        => $tax_rate['rate'],
				'type'        => $tax_rate['type'],
				'amount'      => $amount
			);
		}
		return $tax_rate_data;
	}
	
	
}
?>