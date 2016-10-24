<?php
class ModelKulerCp extends Model
{
	const TABLE_THEME = 'kcp_theme';
	const TABLE_SKIN = 'kcp_skin';
	const TABLE_SKIN_OPTION = 'kcp_skin_option';

	protected $skin_options = array();

	/**
	 * Get path of theme data
	 * @param $theme_id
	 * @return string
	 */
	public function getThemeDataPath($theme_id)
	{
		return DIR_APPLICATION . 'view/theme/' . $theme_id . '/data/';
	}

	/**
	 * Load theme options
	 * @param $theme_id
	 */
	public function loadThemeOptions($theme_id, $cache = true)
	{
		static $cache = array();

		// Get from cache if enabled
		if ($cache && isset($cache[$theme_id]))
		{
			return $cache[$theme_id];
		}

		// Get theme data path
		$theme_option_file = $this->getThemeDataPath($theme_id) . 'theme_options.php';

		// Check where the theme option file is exist or not
		if (!file_exists($theme_option_file))
		{
			throw new Exception(_t('error_theme_option_file_lost', 'The theme option does not exist!'));
		}

		// Get file contents and decode
		$theme_options = include($theme_option_file);

		// Cache
		$cache[$theme_id] = $theme_options;

		return $theme_options;;
	}

	/**
	 * Get all skins of theme by theme ID
	 * @param $theme_id string Theme ID
	 * @return array
	 */
	public function getSkinsByThemeId($theme_id)
	{
		$theme_id = $this->db->escape($theme_id);

		$query = $this->db->query("SELECT * FROM ". DB_PREFIX . self::TABLE_SKIN ." WHERE theme_id = '$theme_id'");

		return $query->rows;
	}

	public function getCurrentThemeId()
	{
		return $this->config->get('kuler_cp_theme_id');
	}

	public function getCurrentThemeVersion()
	{
		static $version;

		if (!$version)
		{
			$theme_id = $this->db->escape($this->getCurrentThemeId());

			$query = $this->db->query("SELECT * FROM ". DB_PREFIX . self::TABLE_THEME ." WHERE theme_id = '$theme_id' LIMIT 0, 1");

			$version = $query->row['version'];
		}

		return $version;
	}

	public function getCurrentSkinId()
	{
		return $this->config->get('kuler_cp_skin_id');
	}

	public function getCurrentRootSkinId()
	{
		static $root_skin_id;

		if (!$root_skin_id)
		{
			$theme_id = $this->db->escape($this->getCurrentThemeId());
			$skin_id = $this->db->escape($this->getCurrentSkinId());

			$query = $this->db->query("SELECT * FROM ". DB_PREFIX . self::TABLE_SKIN ." WHERE theme_id = '$theme_id' AND skin_id = '$skin_id' LIMIT 0, 1");

			$root_skin_id = $query->row['root_skin_id'];
		}

		return $root_skin_id;
	}

	public function getSkinOptions($theme_id, $skin_id, $from_cache = true)
	{
		$cache_id = $theme_id . '_' . $skin_id;

		if ($from_cache && !empty($this->skin_options[$cache_id]))
		{
			return $this->skin_options[$cache_id];
		}

		$theme_id = $this->db->escape($theme_id);
		$skin_id = $this->db->escape($skin_id);

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . self::TABLE_SKIN_OPTION . " WHERE theme_id = '$theme_id' AND skin_id = '$skin_id'");

		$options = array();
		foreach ($query->rows as $row)
		{
			$options[$row['option']] = json_decode($row['value'], true);
		}

		$this->skin_options[$cache_id] = $options;

		return $options;
	}

	public function getSkinOption($theme_id, $skin_id, $property)
	{
		$cache_id = $theme_id . '_' . $skin_id;

		$value = isset($this->skin_options[$cache_id]) && isset($this->skin_options[$cache_id][$property]) ? $this->skin_options[$cache_id][$property] : null;

		if ($value !== null)
		{
			if ($value === 'true')
			{
				$value = true;
			}

			if ($value === 'false')
			{
				$value = false;
			}
		}

		return $value;
	}

	public function processFontFamily($font)
	{
		$fonts = $this->getGoogleFonts();

		$variants = array();
		$is_exist = false;
		foreach ($fonts['items'] as $font_item)
		{
			if ($font_item['family'] == $font)
			{
				$is_exist = true;

				if (!empty($font_item['variants']))
				{
					$variants = $font_item['variants'];
				}
			}
		}

		if (!$is_exist)
		{
			return false;
		}

		$font_family = preg_replace('/(\w)\s+(\w)/', '$1+$2', $font);

		if (!empty($variants))
		{
			$font_family .= ':' . implode(',', $variants);
		}

		return $font_family;
	}

	public function getGoogleFonts()
	{
		static $fonts;

		if (!$fonts)
		{
			$font_contents = file_get_contents(dirname(__FILE__) . '/' . 'fonts.json');

			$fonts = json_decode($font_contents, true);
		}

		return $fonts;
	}

	public function getRecursiveCategories()
	{
		$paths = isset($this->request->get['path']) ? $this->request->get['path'] : '';
		$paths = explode('_', $paths);

		$categories = $this->model_catalog_category->getCategories(0);

		$top_categories = array();

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$product_total = $this->model_catalog_product->getTotalProducts($data);

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
						'category_id' => $child['category_id'],
						'path'  => $category['category_id'] . '_' . $child['category_id'],
						'active'   => isset($paths[1]) && $paths[1] == $child['category_id'] ? true : false
					);
				}

				// Level 1
				$top_categories[] = array(
					'category_id' => $category['category_id'],
					'name'	 => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'	 => $this->url->link('product/category', 'path=' . $category['category_id']),
					'active'   => isset($paths[0]) && $paths[0] == $category['category_id'] ? true : false
				);
			}
		}

		$categories = $top_categories;

		foreach ($categories as &$tcategory)
		{
			foreach ($tcategory['children'] as &$sub_category)
			{
				$sub_category['children'] = $this->_getRecursiveCategoriesAtLevel3($sub_category['category_id'], $sub_category['path'], 2, $paths);
			}
		}

		return $categories;
	}

	public function subscribeNewsletter(array $info)
	{
		$kuler = Kuler::getInstance();
		$mail_service = $kuler->getSkinOption('mail_service');

		$result = false;

		$contact_list = $kuler->getSkinOption('contact_list');

		if (empty($contact_list))
		{
			throw new Exception(_t('error_the_newsletter_system_is_not_configured_please_contact_to_site_admin'));
		}

		switch ($mail_service)
		{
			case 'mailchimp':
				require_once('mail_service/mailchimp.php');

				$api_key = $kuler->getSkinOption('mailchimp_api_key');

				if (empty($api_key))
				{
					throw new Exception(_t('error_the_newsletter_system_is_not_configured_please_contact_to_site_admin'));
				}

				$api = new MCAPI($api_key);

				$mail = $info['mail'];
				$merge = array(
					'FNAME' => 'Email',
					'LNAME' => ' :' . $mail,
				);

				$api->listSubscribe($contact_list, $mail, $merge);

				if ($api->errorCode)
				{
					$message = $api->errorMessage;

					if ($api->errorCode == 214)
					{
						$message = _t('error_your_email_is_already_subscribed_to_list');
					}

					throw new Exception($message);
				}

				$result = true;

				break;
			case 'icontact':
				require_once('mail_service/icontact.php');

				$key = $kuler->getSkinOption('icontact_app_key');
				$username = $kuler->getSkinOption('icontact_username');
				$password = $kuler->getSkinOption('icontact_password');

				if (empty($key) || empty($username) || empty($password))
				{
					throw new Exception(_t('error_the_newsletter_system_is_not_configured_please_contact_to_site_admin'));
				}

				iContactApi::getInstance()->setConfig(array(
					'appId' => $key,
					'apiUsername' => $username,
					'apiPassword' => $password,
				));

				$oiContact = iContactApi::getInstance();

				try
				{
					$result = $oiContact->addContact($info['mail']);
					$result = $oiContact->subscribeContactToList($result->contactId, $contact_list, 'normal');
				}
				catch (Exception $e)
				{
					throw new Exception($e->getMessage());
				}

				$result = true;

				break;
		}

		return $result;
	}

	protected function _getRecursiveCategoriesAtLevel3($category_id, $path, $depth, $paths)
	{
		if ($depth == 6)
		{
			return array();
		}

		$categories = $this->model_catalog_category->getCategories($category_id);

		$results = array();

		foreach ($categories as $category) {
			$data = array(
				'filter_category_id'  => $category['category_id'],
				'filter_sub_category' => true
			);

			$product_total = $this->model_catalog_product->getTotalProducts($data);

			$new_path = $path . '_' . $category['category_id'];

			$results[] = array(
				'name'  => $category['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
				'href'  => $this->url->link('product/category', 'path=' . $new_path),
				'active' => isset($paths[$depth]) && $paths[$depth] == $category['category_id'] ? true : false,
				'children' => $this->_getRecursiveCategoriesAtLevel3($category['category_id'], $new_path, $depth + 1, $paths),
			);
		}

		return $results;
	}

	public function addOrder($data, $completed = false) {
		if ($completed) {
			$this->event->trigger('pre.order.add', $data);
		}

		if (empty($data['order_id'])) {
			$data['order_id'] = 'NULL';
		} else {
			$data['order_id'] = intval($data['order_id']);
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET order_id = {$data['order_id']}, invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_custom_field = '" . $this->db->escape(isset($data['payment_custom_field']) ? serialize($data['payment_custom_field']) : '') . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_custom_field = '" . $this->db->escape(isset($data['shipping_custom_field']) ? serialize($data['shipping_custom_field']) : '') . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', marketing_id = '" . (int)$data['marketing_id'] . "', tracking = '" . $this->db->escape($data['tracking']) . "', language_id = '" . (int)$data['language_id'] . "', currency_id = '" . (int)$data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', forwarded_ip = '" .  $this->db->escape($data['forwarded_ip']) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', accept_language = '" . $this->db->escape($data['accept_language']) . "', date_added = NOW(), date_modified = NOW()");

		$order_id = $this->db->getLastId();

		// Products
		foreach ($data['products'] as $product) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "'");

			$order_product_id = $this->db->getLastId();

			foreach ($product['option'] as $option) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
			}
		}

		// Gift Voucher
		$this->load->model('checkout/voucher');

		// Vouchers
		foreach ($data['vouchers'] as $voucher) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float)$voucher['amount'] . "'");

			$order_voucher_id = $this->db->getLastId();

			$voucher_id = $this->model_checkout_voucher->addVoucher($order_id, $voucher);

			$this->db->query("UPDATE " . DB_PREFIX . "order_voucher SET voucher_id = '" . (int)$voucher_id . "' WHERE order_voucher_id = '" . (int)$order_voucher_id . "'");
		}

		// Totals
		foreach ($data['totals'] as $total) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
		}

		if ($completed) {
			$this->event->trigger('post.order.add', $order_id);
		}

		return $order_id;
	}

	public function deleteOrder($order_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_option` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_history` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_fraud` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE `or`, ort FROM `" . DB_PREFIX . "order_recurring` `or`, `" . DB_PREFIX . "order_recurring_transaction` `ort` WHERE order_id = '" . (int)$order_id . "' AND ort.order_recurring_id = `or`.order_recurring_id");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "affiliate_transaction` WHERE order_id = '" . (int)$order_id . "'");

		// Gift Voucher
		$this->load->model('checkout/voucher');

		$this->model_checkout_voucher->disableVoucher($order_id);
	}
}