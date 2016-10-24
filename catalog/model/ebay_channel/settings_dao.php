<?php
class ModelEbayChannelSettingsDao extends Model {

    public function getSettings() {
        $settings['alert_email'] = null;
        $settings['alert_email_enabled'] = null;
        $settings['item_listed_enabled'] = null;
        $settings['item_closed_enabled'] = null;
        $settings['item_revised_enabled'] = null;
        $settings['item_sold_enabled'] = null;
        $settings['item_unsold_enabled'] = null;
        $settings['truncate_title_enabled'] = null;

        $settings['stock_and_price_interval'] = null;
        $settings['stock_and_price_interval_enabled'] = 0;

        $settings['orders_update_interval'] = null;
        $settings['orders_update_enabled'] = 0;

        $settings['syncronize_interval'] = null;
        $settings['syncronize_enabled'] = 0;

        $settings['image_type'] = null;

        $settings['import_items_enabled'] = 0;
        $settings['import_items_interval'] = null;

        $settings['end_item_on_delete_enabled'] = 1;
        $settings['end_item_out_stock_enabled'] = 1;

        $settings['revise_title_enabled'] = 1;
        $settings['revise_description_enabled'] = 1;
        $settings['revise_sku_enabled'] = 1;
        $settings['revise_paypalemail_enabled'] = 1;
        $settings['revise_dispatch_time_max_enabled'] = 1;
        $settings['revise_listing_duration_enabled'] = 1;
        $settings['revise_listing_type_enabled'] = 1;
        $settings['revise_postal_code_enabled'] = 1;
        $settings['revise_primary_category_enabled'] = 1;
        $settings['revise_payment_methods_enabled'] = 1;
        $settings['revise_picture_details_enabled'] = 1;
        $settings['revise_condition_enabled'] = 1;
        $settings['revise_return_policy_enabled'] = 1;
        $settings['revise_shipping_details_enabled'] = 1;
        $settings['revise_item_specifics_enabled'] = 1;
        $settings['revise_price_enabled'] = 1;
        $settings['revise_quantity_enabled'] = 1;

        $settings['special_price_enabled'] = 0;
        $settings['general_use_taxes_enabled'] = 0;
        $settings['general_prevent_timeout'] = 1;
        $settings['general_list_product_cover_image'] = 0;
        $settings['general_debug_enable'] = 0;


        $settings['import_new_products'] = 1;
        $settings['update_only_stock_and_price'] = 0;
        $settings['update_only_stock'] = 0;
        $settings['update_only_price'] = 0;


        $settings['disable_ended_items'] = 1;
        $settings['order_store_id'] = $this->config->get('config_store_id');
        $settings['order_customer_group_id'] = $this->config->get('config_order_status_id');
        $settings['order_hook_url'] = '';
        $settings['import_compleded'] = 0;

        $settings['default_status'] =  $this->config->get('config_order_status_id');
        $settings['completed_status'] =  $this->config->get('config_order_status_id');
        $settings['shipped_status'] =  $this->config->get('config_order_status_id');
        $settings['inprocess_status'] =  $this->config->get('config_order_status_id');
        $settings['cancelled_status'] =  $this->config->get('config_order_status_id');
        $settings['cancelpending_status'] =  $this->config->get('config_order_status_id');
        $settings['inactive_status'] =  $this->config->get('config_order_status_id');
        $settings['invalid_status'] =  $this->config->get('config_order_status_id');
        $settings['active_status'] =  $this->config->get('config_order_status_id');
        $settings['order_invoice_prefix'] =  $this->config->get('config_invoice_prefix');
        $settings['order_import_older'] = 1;
        $settings['order_subtract_stock'] = 1;
        $settings['order_tax_class_id'] = 0;

        $settings['product_import_categories'] = 0;
        $settings['product_import_variations'] = 0;
        $settings['product_import_specifics'] = 0;
        $settings['product_import_language_id'] = $this->config->get('config_language_id');
        $settings['product_import_currency'] = $this->config->get('config_currency');
        $settings['product_filter_tags'] = array();
        $settings['product_filter_site'] = array();
        $settings['product_entries_per_page'] = 50;

        $settings['feedback_enabled'] = 1;
        $settings['schedule_list_profile_enabled'] = 0;

        $settings['customer_import_enabled'] = 0;

        $dbSettings = $this->db->query("SELECT * from `" . DB_PREFIX . "channel_ebay_setting`")->rows;
        if(!empty($dbSettings)) {
            foreach($dbSettings as $setting) {
                if($setting['serialized'] > 0) {
                    if(!empty($setting['value'])) {
                        $settings[$setting['key']] = json_decode($setting['value'], true);
                    }
                } else {
                    $settings[$setting['key']] = $setting['value'];
                }
            }
        }

        return $settings;
    }

    public function getSettingsByName($names) {
        $settings = array();
        foreach($names as $name) {
            $settings[$name] = null;
        }

        $dbSettings = $this->db->query("SELECT * from `" . DB_PREFIX . "channel_ebay_setting` where `key`  in ('". implode("','", $names) ."')")->rows;
        if(!empty($dbSettings)) {
            foreach($dbSettings as $setting) {
                if($setting['serialized'] > 0) {
                    if(!empty($setting['value'])) {
                        $settings[$setting['key']] = json_decode($setting['value'], true);
                    }
                } else {
                    $settings[$setting['key']] = $setting['value'];
                }
            }
        }
        return $settings;
    }

    public function saveSetting($key, $value, $serialized = 0) {
        if(!empty($key)) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "channel_ebay_setting (`key`, `value`, `serialized`) VALUES('" . $this->db->escape($key) . "', '" . $this->db->escape($value) . "', ". (int)$serialized .") ON DUPLICATE KEY UPDATE `serialized` = '" . (int)$serialized ."', `value` = '" . $this->db->escape($value) . "'");
        }
    }


    public function resize($filename) {
        if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
            return;
        }
        return HTTP_SERVER . 'image/' . $filename;
    }


	public function resize1($filename, $width, $height, $type = "") {
		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
			return;
		} 
		
		$info = pathinfo($filename);
		
		$extension = $info['extension'];
		
		$old_image = $filename;
		$new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . $type .'.' . $extension;
		
		
 		$fn = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
 		$fn = str_replace(basename($fn), rawurlencode(basename($fn)), $fn);
 		$encodelImage = 'cache/' . $fn . '-' . $width . 'x' . $height . $type .'.' . $extension;
		
		if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
			$path = '';
			
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				
				if (!file_exists(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}		
			}

			list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_IMAGE . $old_image);
				$image->resize($width, $height, $type);
				$image->save(DIR_IMAGE . $new_image);
			} else {
				copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
			}
		}
		
		return HTTP_SERVER . 'image/' . $encodelImage;
	}
	
	
}
?>