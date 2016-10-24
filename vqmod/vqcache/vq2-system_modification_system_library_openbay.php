<?php
final class Openbay {
	private $registry;
	private $installed_modules = array();
	public $installed_markets = array();

	public function __construct($registry) {
		$this->registry = $registry;

		$this->getInstalled();

		foreach ($this->installed_markets as $market) {
			$class = ucfirst($market);
			$this->{$market} = new $class($registry);
		}

		$this->logger = new Log('openbay.log');
	}

	public function __get($name) {
		return $this->registry->get($name);
	}

	public function log($data, $write = true) {
		if ($this->logging == 1) {
			if (function_exists('getmypid')) {
				$process_id = getmypid();
				$data = $process_id . ' - ' . $data;
			}

			if ($write == true) {
				$this->logger->write($data);
			}
		}
	}

	public function encrypt($msg, $k, $base64 = false) {
		$td = mcrypt_module_open('rijndael-256', '', 'ctr', '');

		if (!$td) {
			return false;
		}

		$iv = mcrypt_create_iv(32, MCRYPT_RAND);

		if (mcrypt_generic_init($td, $k, $iv) !== 0) {
			return false;
		}

		$msg = mcrypt_generic($td, $msg);
		$msg = $iv . $msg;
		$mac = $this->pbkdf2($msg, $k, 1000, 32);
		$msg .= $mac;

		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		if ($base64) {
			$msg = base64_encode($msg);
		}

		return $msg;
	}

	public function decrypt($msg, $k, $base64 = false) {
		if ($base64) {
			$msg = base64_decode($msg);
		}

		if (!$td = mcrypt_module_open('rijndael-256', '', 'ctr', '')) {
			return false;
		}

		$iv = substr($msg, 0, 32);
		$mo = strlen($msg) - 32;
		$em = substr($msg, $mo);
		$msg = substr($msg, 32, strlen($msg) - 64);
		$mac = $this->pbkdf2($iv . $msg, $k, 1000, 32);

		if ($em !== $mac) {
			return false;
		}

		if (mcrypt_generic_init($td, $k, $iv) !== 0) {
			return false;
		}

		$msg = mdecrypt_generic($td, $msg);
		$msg = unserialize($msg);

		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		return $msg;
	}

	public function pbkdf2($p, $s, $c, $kl, $a = 'sha256') {
		$hl = strlen(hash($a, null, true));
		$kb = ceil($kl / $hl);
		$dk = '';

		for ($block = 1; $block <= $kb; $block++) {

			$ib = $b = hash_hmac($a, $s . pack('N', $block), $p, true);

			for ($i = 1; $i < $c; $i++)
				$ib ^= ($b = hash_hmac($a, $b, $p, true));

			$dk .= $ib;
		}

		return substr($dk, 0, $kl);
	}

	private function getInstalled() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'openbay'");

		foreach ($query->rows as $result) {
			$this->installed_markets[] = $result['code'];
		}
	}

	public function getInstalledMarkets() {
		return $this->installed_markets;
	}

	public function putStockUpdateBulk($product_id_array, $end_inactive = false) {
		/**
		 * putStockUpdateBulk
		 *
		 * Takes an array of product id's where stock has been modified
		 *
		 * @param $product_id_array
		 */

		foreach ($this->installed_markets as $market) {
			if ($this->config->get($market . '_status') == 1) {
				$this->{$market}->putStockUpdateBulk($product_id_array, $end_inactive);
			}
		}
	}

	public function testDbColumn($table, $column) {
		$res = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "` LIKE '" . $column . "'");
		if($res->num_rows != 0) {
			return true;
		}else{
			return false;
		}
	}

	public function testDbTable($table) {
		$res = $this->db->query("SELECT `table_name` AS `c` FROM `information_schema`.`tables` WHERE `table_schema` = DATABASE()");

		$tables = array();

		foreach($res->rows as $row) {
			$tables[] = $row['c'];
		}

		if(in_array($table, $tables)) {
			return true;
		}else{
			return false;
		}
	}

	public function splitName($name) {
		$name = explode(' ', $name);
		$fname = $name[0];
		unset($name[0]);
		$lname = implode(' ', $name);

		return array(
			'firstname' => $fname,
			'surname'   => $lname
		);
	}

	public function getTaxRates($tax_class_id) {
		$tax_rates = array();

		$tax_query = $this->db->query("SELECT
					tr2.tax_rate_id,
					tr2.name,
					tr2.rate,
					tr2.type,
					tr1.priority
				FROM " . DB_PREFIX . "tax_rule tr1
				LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id)
				INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id)
				LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id)
				LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id)
				WHERE tr1.tax_class_id = '" . (int)$tax_class_id . "'
				AND tr1.based = 'shipping'
				AND tr2cg.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'
				AND z2gz.country_id = '" . (int)$this->config->get('config_country_id') . "'
				AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$this->config->get('config_zone_id') . "')
				ORDER BY tr1.priority ASC");

		foreach ($tax_query->rows as $result) {
			$tax_rates[$result['tax_rate_id']] = array(
				'tax_rate_id' => $result['tax_rate_id'],
				'name'        => $result['name'],
				'rate'        => $result['rate'],
				'type'        => $result['type'],
				'priority'    => $result['priority']
			);
		}

		return $tax_rates;
	}

	public function getTaxRate($class_id) {
		$rates = $this->getTaxRates($class_id);
		$percentage = 0.00;
		foreach($rates as $rate) {
			if($rate['type'] == 'P') {
				$percentage += $rate['rate'];
			}
		}

		return $percentage;
	}

	public function getZoneId($name, $country_id) {
		$query = $this->db->query("SELECT `zone_id` FROM `" . DB_PREFIX . "zone` WHERE `country_id` = '" . (int)$country_id . "' AND status = '1' AND `name` = '" . $this->db->escape($name) . "'");

		if($query->num_rows > 0) {
			return $query->row['zone_id'];
		}else{
			return 0;
		}
	}

	public function newOrderAdminNotify($order_id, $order_status_id) {
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);

		$language = new Language($order_info['language_directory']);
		$language->load('default');
		$language->load('mail/order');

		$order_status = $this->db->query("SELECT `name` FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT 1")->row['name'];

		// Order Totals
		$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = '" . (int)$order_id . "' ORDER BY `sort_order` ASC");

		//Order contents
		$order_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '" . (int)$order_id . "'");

		$subject = sprintf($language->get('text_new_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $order_id);

		$file = DIR_SYSTEM . 'library/emailtemplate/email_template.php';

		if (file_exists($file)) {
			include_once(VQMod::modCheck($file));
		} else {
			trigger_error('Error: Could not load library ' . $file . '!');
			exit();
		}

          	$template = new EmailTemplate($this->request, $this->registry);

          	if (!empty($order_info['customer_group_id'])) {
           	$this->load->model('account/customer_group');
			$template->data['customer_group'] = $this->model_account_customer_group->getCustomerGroup($order_info['customer_group_id']);
           }

		if (!empty($order_info['affiliate_id'])) {
           	$this->load->model('affiliate/affiliate');
			$template->data['affiliate'] = $this->model_affiliate_affiliate->getAffiliate($order_info['affiliate_id']);
           }

		$template->addData($order_info);

		$template->addData('order_status_id', $order_status_id);

		$template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));

		if ($order_info['order_status_id'] != $order_status_id){
			$template->data['prev_order_status_id'] = $order_info['order_status_id'];
		}

		if ($order_info['comment']) {
           	$template->data['comment'] = str_replace(array("\r\n", "\r", "\n"), "<br />", $order_info['comment']);
        }

		$template->data['order_status'] = $order_status;

		if ($order_info['customer_id']) {
			$template->data['order_url'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;
			$template->data['order_url_tracking'] = $template->getTracking($template->data['order_url']);
		}

		$this->load->model('tool/image');
 			$this->load->model('tool/upload');
		$this->load->model('catalog/product');

		$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		$order_voucher_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");

		$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");



		// Text
		$text  = $language->get('text_new_received') . "\n\n";
		$text .= $language->get('text_new_order_id') . ' ' . $order_info['order_id'] . "\n";
		$text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
		$text .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n";
		$text .= $language->get('text_new_products') . "\n";

		foreach ($order_product_query->rows as $product) {
			$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";

			$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

			foreach ($order_option_query->rows as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
				}

				$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20) ? utf8_substr($value, 0, 20) . '..' : $value . "\n";
			}
		}

		if(isset($order_voucher_query) && is_array($order_voucher_query)) {
			foreach ($order_voucher_query->rows as $voucher) {
				$text .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']);
			}
		}

		$text .= "\n";
		$text .= $language->get('text_new_order_total') . "\n";

		foreach ($order_total_query->rows as $total) {
			$text .= $total['title'] . ': ' . html_entity_decode($total['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
		}

		$text .= "\n";

		if ($order_info['comment']) {
			$text .= $language->get('text_new_comment') . "\n\n";
			$text .= $order_info['comment'] . "\n\n";
		}


		$template->data['text_order_detail'] = $language->get('text_new_order_detail');
		$template->data['text_order_id'] = $language->get('text_new_order_id');
		$template->data['text_date_added'] = $language->get('text_new_date_added');
		$template->data['text_payment_method'] = $language->get('text_new_payment_method');
		$template->data['text_shipping_method'] = $language->get('text_snew_hipping_method');
		$template->data['text_email'] = $language->get('text_new_email');
		$template->data['text_telephone'] = $language->get('text_new_telephone');
		$template->data['text_ip'] = $language->get('text_new_ip');
		$template->data['text_products'] = $language->get('text_new_products');
		$template->data['text_product'] = $language->get('text_new_product');
		$template->data['text_model'] = $language->get('text_new_model');
		$template->data['text_quantity'] = $language->get('text_new_quantity');
		$template->data['text_price'] = $language->get('text_new_price');
		$template->data['text_total'] = $language->get('text_new_total');

		$template->data['products'] = array();

		foreach ($order_product_query->rows as $product) {
			$product_data = $this->model_catalog_product->getProduct($product['product_id']);
			
			$option_data = array();

			$order_option_query = $this->db->query("SELECT oo.*, pov.* FROM " . DB_PREFIX . "order_option oo LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (pov.product_option_value_id = oo.product_option_value_id) WHERE oo.order_id = '" . (int)$order_id . "' AND oo.order_product_id = '" . (int)$product['order_product_id'] . "'");

			foreach ($order_option_query->rows as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

					if ($upload_info) {
						$value = $upload_info['name'];
					} else {
						$value = '';
					}
				}

				$option_data[] = array(
					'name'  => $option['name'],
					'price' => ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option['price']) ? $this->currency->format($this->tax->calculate($option['price'], $product_data['tax_class_id'], $this->config->get('config_tax') ? 'P' : false)) : 0,
					'price_prefix' => isset($option['price_prefix']) ? $option['price_prefix'] : '',
					'stock_quantity' => isset($option['quantity']) ? $option['quantity'] : '',
					'stock_subtract' => isset($option['subtract']) ? $option['subtract'] : '',
					'value' => (utf8_strlen($value) > 120 ? utf8_substr($value, 0, 120) . '..' : $value)
				);
			}

			if (isset($product_data['image'])) {
				$this->model_tool_image->setUrl($order_info['store_url']);

				$image = $this->model_tool_image->resize($product_data['image'], 50, 50);
			} else {
				$image = '';
			}

			$url = $this->url->link('product/product', 'product_id='.$product['product_id'], 'SSL');

			if ($this->config->get('config_stock_checkout') && $product_data['subtract']) {
				$stock_quantity = $product_data['quantity'] + $product['quantity']; // quantity before order
			} else {
				$stock_quantity = false;
			}

			if ($stock_quantity && $product['quantity'] > $stock_quantity) {
				$stock_backorder = $product['quantity'] - $stock_quantity;
			} else {
				$stock_backorder = false;
			}
			
			$template->data['products'][] = array(
				'product_id'       => $product_data['product_id'],
				'url'     		   => $url,
				'url_tracking' 	   => $template->getTracking($url),
				'image'     	   => $image,
				'weight'		   => ($product_data['weight'] > 0) ? $this->weight->format($product_data['weight'], $product_data['weight_class_id']) : 0,
				'description'      => utf8_substr(strip_tags(html_entity_decode($product_data['description'], ENT_QUOTES, 'UTF-8')), 0, 200) . '..',
				'manufacturer'     => $product_data['manufacturer'],
				'sku'              => $product_data['sku'],
				'stock_status'     => $product_data['stock_status'],
				'stock_subtract'   => $product_data['subtract'],
				'stock_quantity'   => $stock_quantity,
				'stock_backorder'  => $stock_backorder,
				'name'     => $product['name'],
				'model'    => $product['model'],
				'option'   => $option_data,
				'quantity' => $product['quantity'],
				'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
			);
		}

		$template->data['vouchers'] = array();

		foreach ($order_voucher_query->rows as $voucher) {
			$template->data['vouchers'][] = array(
				'description' => $voucher['description'],
				'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
			);
		}

		foreach ($order_total_query->rows as $total) {
			$template->data['totals'][] = array(
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
			);
		}

		$template->data['order_link'] = (defined('HTTP_ADMIN') ? HTTP_ADMIN : HTTPS_SERVER.'admin/') . 'index.php?route=sale/order/info&order_id=' . $order_id;

		// Total cart weight
		if (!empty($order_info['weight'])) {
			$template->data['order_weight'] = $this->weight->format($order_info['weight'], $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
		}

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');
		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($order_info['store_name']);
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
		
				$template->load('openbay.admin');

				$template->build();

				$mail = $template->hook($mail);

				if ($template->data['emailtemplate']['attach_invoice']) {
		    		$this->load->model('module/pdf_invoice');

		    		$pdf_invoice = $this->model_module_pdf_invoice->getInvoice($order_info, true);

		    		if ($pdf_invoice && file_exists($pdf_invoice)) {
		    			$template->data['emailtemplate_invoice_pdf'] = $pdf_invoice;

		    			$mail->addAttachment($pdf_invoice);
		    		}
		    	}

				$mail->send();

				$template->sent();

		// Send to additional alert emails
		$emails = explode(',', $this->config->get('config_alert_emails'));

		foreach ($emails as $email) {
			if ($email && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
				$mail->setTo($email);
				$mail->send();
			}
		}
	}

	public function orderDelete($order_id) {
		/**
		 * Called when an order is deleted in the admin
		 * Use it to add stock back to the marketplaces
		 */
		foreach ($this->installed_markets as $market) {
			if ($this->config->get($market . '_status') == 1) {
				$this->{$market}->orderDelete($order_id);
			}
		}
	}

	public function getProductModelNumber($product_id, $sku = null) {
		if($sku != null) {
			$qry = $this->db->query("SELECT `sku` FROM `" . DB_PREFIX . "product_option_relation` WHERE `product_id` = '" . (int)$product_id . "' AND `var` = '" . $this->db->escape($sku) . "'");

			if($qry->num_rows > 0) {
				return $qry->row['sku'];
			}else{
				return false;
			}
		}else{
			$qry = $this->db->query("SELECT `model` FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$product_id . "' LIMIT 1");

			if($qry->num_rows > 0) {
				return $qry->row['model'];
			}else{
				return false;
			}
		}
	}

	public function addonLoad($addon) {
		$addon = strtolower((string)$addon);

		if (empty($this->installed_modules)) {
			$this->installed_modules = array();

			$rows = $this->db->query("SELECT `code` FROM " . DB_PREFIX . "extension")->rows;

			foreach ($rows as $row) {
				$this->installed_modules[] = strtolower($row['code']);
			}
		}

		return in_array($addon, $this->installed_modules);
	}

	public function getUserByEmail($email) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE `email` = '" . $this->db->escape($email) . "'");

		if($qry->num_rows){
			return $qry->row['customer_id'];
		}else{
			return false;
		}
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($product_option_query->rows as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				$product_option_value_data = array();

				$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'name'                    => $product_option_value['name'],
						'image'                   => $product_option_value['image'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'points'                  => $product_option_value['points'],
						'points_prefix'           => $product_option_value['points_prefix'],
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix']
					);
				}

				$product_option_data[] = array(
					'product_option_id'    => $product_option['product_option_id'],
					'option_id'            => $product_option['option_id'],
					'name'                 => $product_option['name'],
					'type'                 => $product_option['type'],
					'product_option_value' => $product_option_value_data,
					'required'             => $product_option['required']
				);
			} else {
				$product_option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option['option_value'],
					'required'          => $product_option['required']
				);
			}
		}

		return $product_option_data;
	}
}