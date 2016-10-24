<?php
/**
 * Created by PhpStorm.
 * User: Ion
 * Date: 6/3/2015
 * Time: 5:00 PM
 */

class ModelEbayChannelListingProfileDao extends Model {

    public function updateOrInsert($profile) {
        $listingId = null;
        if(isset($profile['id']) && !empty($profile['id'])) {
            $listingId = $this->update($profile);
        } else {
            $listingId = $this->insert($profile);
        }

        $this->load->model('ebay_channel/dao');
        $payments = array();
        $shippings = array();

        $this->removePaymentMethod($listingId);

        foreach ($profile['payment_method'] as $payment=>$index) {
            $this->addPaymentMethod(array('list_profile_id'=>$listingId, 'name'=>$payment));
            $payments[] = $payment;
        }

        $this->removeShippingService($listingId);
        foreach ($profile['shipping_services'] as $type=>$services) {
            foreach ($services as $service) {
                if(empty($service['locations']) && $service['is_international']) {
                    $service['locations'] = 'Worldwide';
                }

                $this->addShippingService(array('list_profile_id'=>$listingId,
                    'service_type'=>$type,
                    'service'=>$service['service'],
                    'locations'=>$service['locations'],
                    'cost'=>$service['cost'],
                    'each_additional'=>$service['each_additional'],
                    'is_international'=>$service['is_international'],
                    'is_free_shipping'=>$service['free_shipping']));
                $shippings[] = $service['service'];
            }
        }

        $md5 = $this->getByMD5($profile, $payments, $shippings);
        $this->updateCheckSum($listingId, $md5);
        return $listingId;
    }

    public function insert($profile) {
        if(!isset($profile['language_id']) || empty($profile['language_id'])) {
            $profile['language_id'] = $this->config->get('config_language_id');
        }

        $this->db->query(
            "INSERT INTO `" . DB_PREFIX . "channel_ebay_list_profile`
                	SET `name` = '" . $this->db->escape($profile['name'])
            . "', `is_default` = '" . $this->db->escape(($profile['is_default'])? 1 : 0)
            . "', `ebay_category_id` = '" . $this->db->escape($profile['ebay_category_id'])
            . "', `ebay_category_path` = '" . $this->db->escape($profile['ebay_category_path'])
            . "', `ebay_store_category_id` = '" . $this->db->escape($profile['ebay_store_category_id'])
            . "', `ebay_store_category_path` = '" . $this->db->escape($profile['ebay_store_category_path'])
            . "', `site_id` = '" . $this->db->escape($profile['site_id'])

            . "', `item_condition_id` = '" . $this->db->escape($profile['item_condition_id'])
            . "', `item_condition_description` = '" . $this->db->escape($profile['item_condition_description'])
            . "', `private_listing` = '" . $this->db->escape(($profile['private_listing'])? 1 : 0)
            . "', `template_id` = '" . $this->db->escape($profile['template_id'])
            . "', `title_suffix` = '" . $this->db->escape($profile['title_suffix'])
            . "', `subtitle` = '" . $this->db->escape($profile['subtitle'])
            . "', `listing_type` = '" . $this->db->escape($profile['listing_type'])
            . "', `duration` = '" . $this->db->escape($profile['duration'])
            . "', `price_option` = '" . $this->db->escape($profile['price_option'])
            . "', `price_plus_minus` = '" . $this->db->escape($profile['price_plus_minus'])
            . "', `price_modify_amount` = '" . $this->db->escape($profile['price_modify_amount'])
            . "', `price_modify_percent` = '" . $this->db->escape($profile['price_modify_percent'])
            . "', `price_custom_amount` = '" . $this->db->escape($profile['price_custom_amount'])
            . "', `bin_enabled` = '" . $this->db->escape(($profile['bin_enabled'])? 1 : 0)
            . "', `bin_option` = '" . $this->db->escape($profile['bin_option'])
            . "', `bin_plus_minus` = '" . $this->db->escape($profile['bin_plus_minus'])
            . "', `bin_modify_amount` = '" . $this->db->escape($profile['bin_modify_amount'])
            . "', `bin_modify_percent` = '" . $this->db->escape($profile['bin_modify_percent'])
            . "', `bin_custom_amount` = '" . $this->db->escape($profile['bin_custom_amount'])
            . "', `qty_to_sell` = '" . $this->db->escape($profile['qty_to_sell'])
            . "', `country` = '" . $this->db->escape($profile['country'])
            . "', `city_state` = '" . $this->db->escape($profile['city_state'])
            . "', `zip_postcode` = '" . $this->db->escape($profile['zip_postcode'])
            . "', `location` = '" . $this->db->escape($profile['location'])
            . "', `paypal_email` = '" . $this->db->escape($profile['paypal_email'])
            . "', `payment_instructions` = '" . $this->db->escape($profile['payment_instructions'])
            . "', `returns_accepted` = '" . $this->db->escape($profile['returns_accepted'])
            . "', `returns_within` = '" . $this->db->escape($profile['returns_within'])
            . "', `refunds` = '" . $this->db->escape($profile['refunds'])
            . "', `shippingcost_paidby` = '" . $this->db->escape($profile['shippingcost_paidby'])
            . "', `return_policy_description` = '" . $this->db->escape($profile['return_policy_description'])
            . "', `shipping_type` = '" . $this->db->escape($profile['shipping_type'])
            . "', `dispatch_time` = '" . $this->db->escape($profile['dispatch_time'])
            . "', `shipping_package` = '" . $this->db->escape($profile['shipping_package'])
            . "', `dimension_depth` = '" . $this->db->escape($profile['dimension_depth'])
            . "', `dimension_width` = '" . $this->db->escape($profile['dimension_width'])
            . "', `dimension_length` = '" . $this->db->escape($profile['dimension_length'])

            . "', `weight_major` = '" . $this->db->escape($profile['weight_major'])
            . "', `weight_minor` = '" . $this->db->escape($profile['weight_minor'])
            . "', `package_handling_fee` = '" . $this->db->escape($profile['package_handling_fee'])
            . "', `is_irregular_package` = '" . $this->db->escape(($profile['is_irregular_package'])? 1 : 0)


            . "', `currency` = '" . $this->db->escape($profile['currency'])
            . "', `language_id` = '" . $this->db->escape($profile['language_id'])

            . "', `ean_enabled` = '" . (isset($profile['ean_enabled'])?  $this->db->escape($profile['ean_enabled']) : '')
            . "', `upc_enabled` = '" . (isset($profile['upc_enabled'])?  $this->db->escape($profile['upc_enabled']) : '')
            . "', `isbn_enabled` = '" . (isset($profile['isbn_enabled'])?  $this->db->escape($profile['isbn_enabled']) : '')
            . "', `brandmpn_enabled` = '" . (isset($profile['brandmpn_enabled'])?  $this->db->escape($profile['brandmpn_enabled']) : '')


            . "', `paypal_required` = '" . $this->db->escape(($profile['paypal_required'])? 1 : 0)
            . "', `return_policy_enabled` = '" . $this->db->escape(($profile['return_policy_enabled'])? 1 : 0)
            . "', `handling_time_enabled` = '" . $this->db->escape(($profile['handling_time_enabled'])? 1 : 0)
            . "', `variations_enabled` = '" . $this->db->escape(($profile['variations_enabled'])? 1 : 0)
            . "', `attributes_enabled` = '" . $this->db->escape(($profile['attributes_enabled'])? 1 : 0)
            . "', `revise_quantity_allowed` = '" . $this->db->escape(($profile['revise_quantity_allowed'])? 1 : 0)
            . "', `revise_price_allowed` = '" . $this->db->escape(($profile['revise_price_allowed'])? 1 : 0)
            . "', `minimum_reserve_price` = '" . $this->db->escape($profile['minimum_reserve_price'])

            //. "', `vin` = '" . $this->db->escape($profile['ebay_motors_vin'])

            . "', `has_international_shipping` = '" . $this->db->escape(($profile['has_international_shipping'])? 1 : 0) . "'");

        $id = $this->db->getLastId();
        if($profile['is_default']) {
            $this->markAsDefault($id);
        }
        return $id;

    }

    public function update($profile) {
        $this->db->query(
            "UPDATE `" . DB_PREFIX . "channel_ebay_list_profile`
                	SET `name` = '" . $this->db->escape($profile['name'])
            . "', `is_default` = '" . $this->db->escape(($profile['is_default'])? 1 : 0)
            . "', `ebay_category_id` = '" . $this->db->escape($profile['ebay_category_id'])
            . "', `ebay_category_path` = '" . $this->db->escape($profile['ebay_category_path'])
            . "', `ebay_store_category_id` = '" . $this->db->escape($profile['ebay_store_category_id'])
            . "', `ebay_store_category_path` = '" . $this->db->escape($profile['ebay_store_category_path'])
            . "', `item_condition_id` = '" . $this->db->escape($profile['item_condition_id'])
            . "', `item_condition_description` = '" . $this->db->escape($profile['item_condition_description'])
            . "', `private_listing` = '" . $this->db->escape(($profile['private_listing'])? 1 : 0)
            . "', `template_id` = '" . $this->db->escape($profile['template_id'])
            . "', `title_suffix` = '" . $this->db->escape($profile['title_suffix'])
            . "', `subtitle` = '" . $this->db->escape($profile['subtitle'])
            . "', `listing_type` = '" . $this->db->escape($profile['listing_type'])
            . "', `duration` = '" . $this->db->escape($profile['duration'])
            . "', `price_option` = '" . $this->db->escape($profile['price_option'])
            . "', `price_plus_minus` = '" . $this->db->escape($profile['price_plus_minus'])
            . "', `price_modify_amount` = '" . $this->db->escape($profile['price_modify_amount'])
            . "', `price_modify_percent` = '" . $this->db->escape($profile['price_modify_percent'])
            . "', `price_custom_amount` = '" . $this->db->escape($profile['price_custom_amount'])
            . "', `bin_enabled` = '" . $this->db->escape(($profile['bin_enabled'])? 1 : 0)
            . "', `bin_option` = '" . $this->db->escape($profile['bin_option'])
            . "', `bin_plus_minus` = '" . $this->db->escape($profile['bin_plus_minus'])
            . "', `bin_modify_amount` = '" . $this->db->escape($profile['bin_modify_amount'])
            . "', `bin_modify_percent` = '" . $this->db->escape($profile['bin_modify_percent'])
            . "', `bin_custom_amount` = '" . $this->db->escape($profile['bin_custom_amount'])
            . "', `qty_to_sell` = '" . $this->db->escape($profile['qty_to_sell'])
            . "', `country` = '" . $this->db->escape($profile['country'])
            . "', `city_state` = '" . $this->db->escape($profile['city_state'])
            . "', `zip_postcode` = '" . $this->db->escape($profile['zip_postcode'])
            . "', `location` = '" . $this->db->escape($profile['location'])
            . "', `paypal_email` = '" . $this->db->escape($profile['paypal_email'])
            . "', `payment_instructions` = '" . $this->db->escape($profile['payment_instructions'])
            . "', `returns_accepted` = '" . $this->db->escape($profile['returns_accepted'])
            . "', `returns_within` = '" . $this->db->escape($profile['returns_within'])
            . "', `refunds` = '" . $this->db->escape($profile['refunds'])
            . "', `shippingcost_paidby` = '" . $this->db->escape($profile['shippingcost_paidby'])
            . "', `return_policy_description` = '" . $this->db->escape($profile['return_policy_description'])
            . "', `shipping_type` = '" . $this->db->escape($profile['shipping_type'])
            . "', `dispatch_time` = '" . $this->db->escape($profile['dispatch_time'])
            . "', `shipping_package` = '" . $this->db->escape($profile['shipping_package'])
            . "', `dimension_depth` = '" . $this->db->escape($profile['dimension_depth'])
            . "', `dimension_width` = '" . $this->db->escape($profile['dimension_width'])
            . "', `dimension_length` = '" . $this->db->escape($profile['dimension_length'])
            . "', `weight_major` = '" . $this->db->escape($profile['weight_major'])
            . "', `weight_minor` = '" . $this->db->escape($profile['weight_minor'])
            . "', `package_handling_fee` = '" . $this->db->escape($profile['package_handling_fee'])
            . "', `is_irregular_package` = '" . $this->db->escape(($profile['is_irregular_package'])? 1 : 0)
            . "', `currency` = '" . $this->db->escape($profile['currency'])

            . "', `ean_enabled` = '" . (isset($profile['ean_enabled'])? $this->db->escape($profile['ean_enabled']) : '' )
            . "', `upc_enabled` = '" . (isset($profile['upc_enabled'])? $this->db->escape($profile['upc_enabled']) : '' )
            . "', `isbn_enabled` = '" . (isset($profile['isbn_enabled'])? $this->db->escape($profile['isbn_enabled']) : '' )
            . "', `brandmpn_enabled` = '" . (isset($profile['brandmpn_enabled'])? $this->db->escape($profile['brandmpn_enabled']) : '' )

            . "', `paypal_required` = '" . $this->db->escape(($profile['paypal_required'])? 1 : 0)
            . "', `return_policy_enabled` = '" . $this->db->escape(($profile['return_policy_enabled'])? 1 : 0)
            . "', `handling_time_enabled` = '" . $this->db->escape(($profile['handling_time_enabled'])? 1 : 0)
            . "', `variations_enabled` = '" . $this->db->escape(($profile['variations_enabled'])? 1 : 0)
            . "', `attributes_enabled` = '" . $this->db->escape(($profile['attributes_enabled'])? 1 : 0)
            . "', `revise_quantity_allowed` = '" . $this->db->escape(($profile['revise_quantity_allowed'])? 1 : 0)
            . "', `revise_price_allowed` = '" . $this->db->escape(($profile['revise_price_allowed'])? 1 : 0)
            . "', `minimum_reserve_price` = '" . $this->db->escape($profile['minimum_reserve_price'])

            // . "', `vin` = '" . $this->db->escape($profile['ebay_motors_vin'])


            . "', `has_international_shipping` = '" . $this->db->escape(($profile['has_international_shipping'])? 1 : 0) . "'"
            . " where id = " . $this->db->escape($profile['id']));

        if($profile['is_default']) {
            $this->markAsDefault($profile['id']);
        }

        return $profile['id'];
    }

    public function markAsDefault($list_profile_id) {
        $this->db->query("UPDATE `" . DB_PREFIX . "channel_ebay_list_profile` SET is_default = 0");
        $this->db->query("UPDATE `" . DB_PREFIX . "channel_ebay_list_profile` SET is_default = 1  where `id`=". $this->db->escape($list_profile_id));
    }

    public function getIdByChecksum($md5) {
        $row = $this->db->query("SELECT id FROM `" . DB_PREFIX . "channel_ebay_list_profile` where profile_checksum='". $this->db->escape($md5) . "' LIMIT 1")->row;
        if(!empty($row)) {
            return $row['id'];
        }

        return null;
    }

    public function updateCheckSum($id, $profile_checksum) {
        $this->db->query("UPDATE `" . DB_PREFIX . "channel_ebay_list_profile` SET `profile_checksum` = '" . $this->db->escape($profile_checksum) . "' where id = " . $this->db->escape($id));
    }

    public function updateLanguage($id, $language_id) {
        $this->db->query("UPDATE `" . DB_PREFIX . "channel_ebay_list_profile` SET `language_id` = '" . $this->db->escape($language_id) . "'"
            . " where id = " . $this->db->escape($id));
    }

    public function getByMD5($profile, $payments = array(), $shippings = array()) {
        $value = $profile['ebay_category_id'];
        if(!empty($profile['ebay_store_category_id'])) {
            $value .= $profile['ebay_store_category_id'];
        }

        if(!empty($profile['site_id'])) {
            $value .= $profile['site_id'];
        }

        if(!empty($profile['item_condition_id'])) {
            $value .= $profile['item_condition_id'];
        }

        if(!empty($profile['item_condition_description'])) {
            $value .= $profile['item_condition_description'];
        }

        if(!empty($profile['private_listing'])) {
            $value .= $profile['private_listing'];
        }

        if(!empty($profile['listing_type'])) {
            $value .= $profile['listing_type'];
        }

        if(!empty($profile['duration'])) {
            $value .= $profile['duration'];
        }

        if(!empty($profile['country'])) {
            $value .= $profile['country'];
        }

        if(!empty($profile['city_state'])) {
            $value .= $profile['city_state'];
        }

        if(!empty($profile['zip_postcode'])) {
            $value .= $profile['zip_postcode'];
        }

        if(!empty($profile['location'])) {
            $value .= $profile['location'];
        }

        if(!empty($profile['paypal_email'])) {
            $value .= $profile['paypal_email'];
        }

        if(!empty($profile['returns_accepted'])) {
            $value .= $profile['returns_accepted'];
        }

        if(!empty($profile['returns_within'])) {
            $value .= $profile['returns_within'];
        }

        if(!empty($profile['refunds'])) {
            $value .= $profile['refunds'];
        }

        if(!empty($profile['shippingcost_paidby'])) {
            $value .= $profile['shippingcost_paidby'];
        }

        if(!empty($profile['return_policy_description'])) {
            $value .= $profile['return_policy_description'];
        }

        if(!empty($profile['currency'])) {
            $value .= $profile['currency'];
        }

        foreach ($payments as $payment) {
            $value .= $payment;
        }

        foreach ($shippings as $shipping) {
            $value .= $shipping;
        }

        return md5($value);
    }

    public function getCount() {
        return $this->db->query("SELECT count(lp.id) as c FROM " . DB_PREFIX . "channel_ebay_list_profile lp ")->row['c'];
    }

    public function getCurrencies() {
        return $this->db->query("SELECT distinct currency FROM " . DB_PREFIX . "channel_ebay_list_profile")->rows;
    }

    public function getColumns() {
        return array('lp.name', 'lp.site_id', 'products_count', 'lp.is_default', 'lp.id');
    }

    public function remove($list_profile_id) {
        $this->removePaymentMethod($list_profile_id);
        $this->removeShippingService($list_profile_id);
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_list_profile` where `id`=". $this->db->escape($list_profile_id));
    }

    public function removeShippingService($list_profile_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_list_shipping_service` where `list_profile_id`=". $this->db->escape($list_profile_id));
    }

    public function addShippingService($service) {
        $this->db->query(
            "INSERT INTO `" . DB_PREFIX . "channel_ebay_list_shipping_service`
                	SET `list_profile_id` = '" . $this->db->escape($service['list_profile_id'])
            . "', `service` = '" . $this->db->escape($service['service'])
            . "', `locations` = '" . $this->db->escape($service['locations'])
            . "', `cost` = '" . $this->db->escape($service['cost'])
            . "', `each_additional` = '" . $this->db->escape($service['each_additional'])
            . "', `is_free_shipping` = '" . $this->db->escape(($service['is_free_shipping'])? 1 : 0)
            . "', `is_international` = '" . $this->db->escape(($service['is_international'])? 1 : 0)
            . "', `service_type` = '" . $this->db->escape($service['service_type']) . "'");
        return $this->db->getLastId();
    }

    public function removeItemSpecific($list_profile_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_list_item_specific` where `list_profile_id`=". $this->db->escape($list_profile_id));
    }

    public function addItemSpecific($item_specific) {
        $this->db->query(
            "INSERT INTO `" . DB_PREFIX . "channel_ebay_list_item_specific`
                	SET `list_profile_id` = '" . $this->db->escape($item_specific['list_profile_id'])
            . "', `name` = '" . $this->db->escape($item_specific['name'])
            . "', `value` = '" . $this->db->escape($item_specific['value']) . "'");
        return $this->db->getLastId();
    }

    public function removePaymentMethod($list_profile_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_list_payment_method` where `list_profile_id`=". $this->db->escape($list_profile_id));
    }

    public function addPaymentMethod($method) {
        $this->db->query(
            "INSERT INTO `" . DB_PREFIX . "channel_ebay_list_payment_method`
                	SET `list_profile_id` = '" . $this->db->escape($method['list_profile_id'])
            . "', `name` = '" . $this->db->escape($method['name']) . "'");
        return $this->db->getLastId();
    }

    public function getList($data) {

        $aColumns = $this->getColumns();

        $sLimit = '';
        if (isset($data['iDisplayStart']) && $data['iDisplayLength'] != '-1') {
            $sLimit .= " LIMIT " . intval($data['iDisplayStart']) . ", " . intval($data['iDisplayLength']);
        }

        /*
        * Ordering
        */
        $sOrder = "";
        if (isset($data['iSortCol_0'])) {
            $sOrder = " ORDER BY  ";
            for ($i = 0; $i < intval($data['iSortingCols']); $i++) {
                if ($data['bSortable_' . intval($data['iSortCol_' . $i])] == "true") {
                    $sOrder .= " " . $aColumns[intval($data['iSortCol_' . $i])] . " " .
                        ($data['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == " ORDER BY") {
                $sOrder = "";
            }
        }
        $sQuery = "SELECT lp.id, lp.name, lp.is_default, lp.ebay_category_path, lp.ebay_store_category_path, lp.site_id, lp.listing_type, count(cep.product_id) as products_count FROM " . DB_PREFIX . "channel_ebay_list_profile lp LEFT JOIN " . DB_PREFIX . "channel_ebay_product cep on cep.list_profile_id = lp.id group by lp.id"
            . " " . $sOrder . " " . $sLimit . " ";


        //echo $sQuery; die();

        $result = $this->db->query($sQuery)->rows;


        $sQuery =  "SELECT count(lp.id) as c FROM " . DB_PREFIX . "channel_ebay_list_profile lp ";
        $resultTotal = $this->db->query($sQuery)->row['c'];

        $r = new stdClass();
        $r->result = $result;
        $r->count = $resultTotal;
        $r->filterCount = $resultTotal;

        return $r;
    }

    public function getById($id) {
        return $this->db->query('SELECT * FROM `' . DB_PREFIX . 'channel_ebay_list_profile` where id='. $this->db->escape($id))->row;
    }

    public function listAll() {
        return $this->db->query('SELECT * FROM `' . DB_PREFIX . 'channel_ebay_list_profile` order by name asc')->rows;
    }

    public function getRecord($id) {
        $profile = array();
        $lp = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'channel_ebay_list_profile` where id='. $this->db->escape($id))->row;
        $lpm = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'channel_ebay_list_payment_method` where list_profile_id='. $this->db->escape($id))->rows;
        $lis = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'channel_ebay_list_item_specific` where list_profile_id='. $this->db->escape($id))->rows;
        $lss = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'channel_ebay_list_shipping_service` where list_profile_id='. $this->db->escape($id) . ' order by id asc')->rows;

        if(!empty($lp)) {
            $profile['list_profile'] = $lp;
        }

        if(!empty($lpm)) {
            $profile['list_payment_method'] = $lpm;
        }

        $profile['item_specifics'] = array();
        if(!empty($lis)) {
            foreach ($lis as $row) {
                $profile['item_specifics'][$row['name']] = $row['value'];
            }
        }

        if(!empty($lss)) {
            $profile['list_shipping_service'] = $lss;
        }

        return $profile;
    }

    public function toDraftDTO($data, $sessionData, $features) {
        $entity = $this->toEntity($data, $sessionData, $features);
        $dto = $this->toDTO(array("list_profile" => $entity));
        return $dto;
    }

    public function getDTO($id) {
        $record = $this->getRecord($id);
        return $this->toDTO($record);
    }

    public function toDTO($profile) {
        $listProfile = array();

        $listProfile['ebay_motors_vin'] = false;
        if($profile['list_profile']['site_id'] == 100) {
            $listProfile['ebay_motors_vin'] = (isset($profile['list_profile']['vin']) && $profile['list_profile']['vin'] != null)? $profile['list_profile']['vin'] : '';
        }


        $listProfile['has_international_shipping'] = false;
        $listProfile['shipping_type'] = 'Flat';

        $listProfile['condition_id'] =  $this->getValue($profile['list_profile'], 'item_condition_id');
        $listProfile['template_id'] = $this->getValue($profile['list_profile'], 'template_id');
        $listProfile['title_suffix'] = $this->getValue($profile['list_profile'], 'title_suffix');
        $listProfile['subtitle'] = $this->getValue($profile['list_profile'], 'subtitle');


        $listProfile['condition_description'] = $this->getValue($profile['list_profile'], 'item_condition_description');
        $listProfile['listing_type'] = $this->getValue($profile['list_profile'], 'listing_type');
        $listProfile['duration'] = $this->getValue($profile['list_profile'], 'duration');
        $listProfile['price_option'] = $this->getValue($profile['list_profile'], 'price_option');
        $listProfile['qty_to_sell'] = $this->getValue($profile['list_profile'], 'qty_to_sell');
        $listProfile['private_listing'] = $this->getValue($profile['list_profile'], 'private_listing');
        $listProfile['attributes_enabled'] = $this->getValue($profile['list_profile'], 'attributes_enabled');



        if($listProfile['price_option'] == 'price_extra') {
            $listProfile['price_extra']['price_plus_minus'] = $this->getValue($profile['list_profile'], 'price_plus_minus');
            $listProfile['price_extra']['price_modify_amount'] = $this->getValue($profile['list_profile'], 'price_modify_amount');
            $listProfile['price_extra']['price_modify_percent'] = $this->getValue($profile['list_profile'], 'price_modify_percent');
        } else if($listProfile['price_option'] == 'custom_price') {
            $listProfile['custom_price']['price_custom_amount'] = $this->getValue($profile['list_profile'], 'price_custom_amount');
        }

        $listProfile['bin_enabled'] = $profile['list_profile']['bin_enabled'] > 0;
        if($listProfile['bin_enabled']) {
            $listProfile['bin_option'] = $profile['list_profile']['bin_option'];
            if($listProfile['bin_option'] == 'price_extra') {
                $listProfile['bin_price_extra']['price_plus_minus'] = $profile['list_profile']['bin_plus_minus'];
                $listProfile['bin_price_extra']['price_modify_amount'] = $profile['list_profile']['bin_modify_amount'];
                $listProfile['bin_price_extra']['price_modify_percent'] = $profile['list_profile']['bin_modify_percent'];
            } else if($listProfile['bin_option'] == 'custom_price') {
                $listProfile['bin_custom_price']['price_custom_amount'] = $profile['list_profile']['bin_custom_amount'];
            }
        }

        $listProfile['country'] = $profile['list_profile']['country'];
        $listProfile['city_state'] = $profile['list_profile']['city_state'];
        $listProfile['zip_postcode'] = $profile['list_profile']['zip_postcode'];
        $listProfile['location'] = $profile['list_profile']['location'];
        $listProfile['paypal_email'] = $profile['list_profile']['paypal_email'];
        $listProfile['payment_instructions'] = $profile['list_profile']['payment_instructions'];


        if(isset($profile['list_payment_method'])) {
            foreach ($profile['list_payment_method'] as $payment_method) {
                $listProfile['payment_method'][$payment_method['name']] = 1;
            }
        } else if(isset($profile['list_profile']['payment_method'])) {
            $listProfile['payment_method'] = $profile['list_profile']['payment_method'];
        }

        $listProfile['returns_accepted'] = $profile['list_profile']['returns_accepted'];
        $listProfile['returns_within'] = $profile['list_profile']['returns_within'];
        $listProfile['refunds'] = $profile['list_profile']['refunds'];
        $listProfile['shippingcost_paidby'] = $profile['list_profile']['shippingcost_paidby'];
        $listProfile['return_policy_description'] = $profile['list_profile']['return_policy_description'];

        $listProfile['shipping_type'] = $profile['list_profile']['shipping_type'];
        $listProfile['dispatch_time'] = $profile['list_profile']['dispatch_time'];
        $listProfile['has_international_shipping'] = $profile['list_profile']['has_international_shipping'] > 0;

        $shipping_type = '';
        if(isset($listProfile['shipping_type'])) {
            $shipping_type = $listProfile['shipping_type'];
        } else if(isset($profile['list_profile']['shipping_type'])) {
            $shipping_type =  $listProfile['shipping_type'];
        }

        if($shipping_type == 'Calculated' || $shipping_type == 'FlatDomesticCalculatedInternational' || $shipping_type == 'CalculatedDomesticFlatInternational') {
            $listProfile['shipping_package'] = $profile['list_profile']['shipping_package'];
            $listProfile['dimension_depth'] = $profile['list_profile']['dimension_depth'];
            $listProfile['dimension_width'] = $profile['list_profile']['dimension_width'];
            $listProfile['dimension_length'] = $profile['list_profile']['dimension_length'];
            $listProfile['is_irregular_package'] = $profile['list_profile']['is_irregular_package'];
            $listProfile['weight_major'] = $profile['list_profile']['weight_major'];
            $listProfile['weight_minor'] = $profile['list_profile']['weight_minor'];
            $listProfile['package_handling_fee'] = $profile['list_profile']['package_handling_fee'];
        }

        $st = array();
        if($listProfile['shipping_type'] == 'Calculated') {
            $st[] = 'cd';
            if($listProfile['has_international_shipping']) {
                $st[] = 'ci';
            }
        } else if($listProfile['shipping_type'] == 'Flat') {
            $st[] = 'fd';
            if($listProfile['has_international_shipping']) {
                $st[] = 'fi';
            }
        } else if($listProfile['shipping_type'] == 'FlatDomesticCalculatedInternational') {
            $st[] = 'fd';
            $st[] = 'ci';
        } else if($listProfile['shipping_type'] == 'CalculatedDomesticFlatInternational') {
            $st[] = 'cd';
            $st[] = 'fi';
        }

        $listProfile['shipping_services'] = array();
        $list_shipping_service = array();
        if(isset($profile['list_shipping_service'])) {
            foreach ($profile['list_shipping_service'] as $shipping_service) {
                $locations = array();
                if(!empty($shipping_service['locations'])) {
                    $locations = explode(",", $shipping_service['locations']);
                }

                $listProfile['shipping_services'][$shipping_service['service_type']][]
                    = array('service'=>$shipping_service['service'],
                    'each_additional'=>$shipping_service['each_additional'],
                    'free_shipping'=>$shipping_service['is_free_shipping'] > 0,
                    'alocations'=> $locations,
                    'cost'=>$shipping_service['cost']);
            }
        } else if(isset($profile['list_profile']['shipping_services'])) {
            $listProfile['shipping_services'] = $profile['list_profile']['shipping_services'];
        }

        return $listProfile;
    }


    public function validateStep2($data, $features) {
        $error = array();

        if (!$this->user->hasPermission('modify', 'module/ebay_channel')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $is_item_conditions_required = ($features['ConditionEnabled'] == 'Required');
        if($is_item_conditions_required && !isset($data['condition'])) {
            $error['error_condition_id'] = $this->language->get('error_condition_id');
        }

        if(!isset($error['error_condition_id']) && $features['ConditionEnabled'] == 'Required' || $features['ConditionEnabled'] == 'Enabled') {
            $v = false;
            foreach($features['ConditionValues'] as $condition) {
                if($data['condition'] == $condition['ID']) {
                    $v = true;
                }
            }
            if ($features['ConditionEnabled'] == 'Required' && !$v) {
                $error['error_condition_id'] = $this->language->get('error_condition_id');
            }
        }

        if(!isset($data['listing_type']) || !$data['listing_type']) {
            $error['error_listing_type'] = $this->language->get('error_listing_type');
        } else {

            if($data['listing_type'] == 'Chinese') {
                if(isset($data['start_price_option'])) {
                    if($data['start_price_option'] == 'price_extra') {
                        if(!isset($data['startprice_plus_minus'])) {
                            $error['error_price_plus_minus'] = $this->language->get('error_price_plus_minus');
                        }
                    }
                } else {
                    $error['error_price_option'] = $this->language->get('error_price_option');
                }
            } else if($data['listing_type'] == 'FixedPriceItem') {

                if(!isset($data['fixed_price_option'])) {
                    $error['error_fixed_price_option'] = $this->language->get('error_fixed_price_option');
                } else if($data['fixed_price_option'] == 'price_extra') {
                    if(!isset($data['fixedprice_plus_minus'])) {
                        $error['error_price_plus_minus'] = $this->language->get('error_price_plus_minus');
                    }
                }
            }
        }



        if(!isset($data['qty_to_sell']) || !is_numeric($data['qty_to_sell'])) {
            $error['error_qty_to_sell'] = $this->language->get('error_qty_to_sell');
        }

        if(isset($data['subtitle'])) {
            if(strlen($data['subtitle']) > 55) {
                $error['error_subtitle'] = "max length 55 chars";
            }
        }


        if(!($data['auction_duration'] || $data['fixed_duration'])) {
            $error['error_duration'] = $this->language->get('error_duration');
        }

        if(!isset($data['country'])) {
            $error['error_country'] = $this->language->get('error_country');
        }

        if(!isset($data['city_state']) || empty($data['city_state'])) {
            $error['error_city_state'] = $this->language->get('error_city_state');
        }

        if(!isset($data['zip_postcode']) || empty($data['zip_postcode'])) {
            $error['error_zip_postcode'] = $this->language->get('error_zip_postcode');
        }

        $listProfile['location'] = null;
        if(isset($data['location']) && !empty($data['location'])) {
            if(strlen($data['location']) > 45) {
                $error['error_location'] = $this->language->get('error_location');
            }
        }



        if(!isset($data['payment_method']) || empty($data['payment_method'])) {
            $error['error_payment_method'] = $this->language->get('error_payment_method');
        }

        if(!isset($data['paypal_email']) || empty($data['paypal_email'])) {
            $error['error_paypal_email'] = $this->language->get('error_paypal_email');
        }

        $listProfile['returns_accepted'] = $data['returns_accepted'];
        if($data['returns_accepted'] == 'ReturnsAccepted') {

            if(!isset($data['returns_within']) || empty($data['returns_within'])) {
                $error['error_returns_within'] = $this->language->get('error_returns_within');
            }

        }

        if(isset($data['shipping_type']) && !empty($data['shipping_type'])) {

            if($data['shipping_type'] == 'Calculated' || $data['shipping_type'] == 'FlatDomesticCalculatedInternational' || $data['shipping_type'] == 'CalculatedDomesticFlatInternational') {

                if(empty($data['dimension_depth'])) {
                    $error['error_dimension_depth'] = $this->language->get('error_dimension_depth');
                }

                if(empty($data['dimension_width'])) {
                    $error['error_dimension_width'] = $this->language->get('error_dimension_width');
                }

                if(!isset($data['weight_major']) || !is_numeric($data['weight_major'])) {
                    $error['error_weight_major'] = $this->language->get('error_weight_major');
                }

                if(empty($data['dimension_length'])) {
                    $error['error_dimension_length'] = $this->language->get('error_dimension_length');
                }

            }
        } else {
            $error['error_shipping_type'] = $this->language->get('error_shipping_type');
        }

        return $error;
    }

    public function validateStep1($data) {
        $error = array();
        if (!$this->user->hasPermission('modify', 'module/ebay_channel')) {
            $error['warning'] = $this->language->get('error_permission');
        }

        if (!$data['list_name']) {
            $error['error_list_name'] = $this->language->get('error_list_name');
        }

        if (!isset($data['site_id'])) {
            $error['error_site_id'] = $this->language->get('error_site_id');
        } else {
            if (!$data['ebay_category_id_'. $data['site_id'] ]) {
                $error['error_ebay_category_id'] = $this->language->get('error_ebay_category_id');
            }
        }
        return $error;
    }


    public function toEntity($data, $sessionData, $features) {

        $this->load->model('ebay_channel/dao');

        $listProfile = array();
        $listProfile['has_international_shipping'] = false;
        $listProfile['item_condition_id'] = null;
        $listProfile['item_condition_description'] = null;
        $listProfile['price_plus_minus'] = null;
        $listProfile['price_modify_amount'] = null;
        $listProfile['price_modify_percent'] = null;
        $listProfile['price_custom_amount'] = null;
        $listProfile['bin_enabled'] = null;
        $listProfile['bin_option'] = null;
        $listProfile['bin_plus_minus'] = null;
        $listProfile['bin_modify_amount'] = null;
        $listProfile['bin_modify_percent'] = null;
        $listProfile['bin_custom_amount'] = null;
        $listProfile['payment_instructions'] = null;

        $listProfile['shipping_type'] = '';
        $listProfile['dispatch_time'] = '';
        $listProfile['shipping_package'] = '';
        $listProfile['dimension_depth'] = '';
        $listProfile['dimension_width'] = '';
        $listProfile['dimension_length'] = '';
        $listProfile['is_irregular_package'] = '';
        $listProfile['weight_major'] = '';
        $listProfile['weight_minor'] = '';
        $listProfile['package_handling_fee'] = '';
        //$listProfile['ebay_motors_vin'] = '';
        $listProfile['template_id'] = '';
        $listProfile['title_suffix'] = '';
        $listProfile['subtitle'] = '';
        $listProfile['country'] = '';
        $listProfile['city_state'] = '';
        $listProfile['zip_postcode'] = '';
        $listProfile['location'] = null;
        $listProfile['paypal_email'] = '';
        $listProfile['returns_within'] = null;
        $listProfile['refunds'] = null;
        $listProfile['shippingcost_paidby'] = null;
        $listProfile['return_policy_description'] = null;

        $listProfile['returns_accepted'] = '';
        $listProfile['return_policy_description'] = '';

        $listProfile['listing_profile_id'] = '';
        if(isset($sessionData['id'])){
            $listProfile['id'] = $sessionData['id'];
        }

        $site = $this->model_ebay_channel_dao->getEbaySite($sessionData['site_id']);
        $listProfile['currency'] = $site['currency'];
        $listProfile['name'] = $sessionData['name'];
        $listProfile['is_default'] = $sessionData['is_default'];
        $listProfile['site_id'] = $sessionData['site_id'];
        $listProfile['ebay_category_id'] = $sessionData['ebay_category_id'];
        $listProfile['ebay_category_path'] = $this->model_ebay_channel_dao->getEbayCategoryPath($listProfile['site_id'], $listProfile['ebay_category_id']);
        if(isset($sessionData['ebay_store_category_id']) && !empty($sessionData['ebay_store_category_id'])) {
            $listProfile['ebay_store_category_id'] = $sessionData['ebay_store_category_id'];
            $listProfile['ebay_store_category_path'] = $this->model_ebay_channel_dao->getEbayStoreCategoryPath($listProfile['ebay_store_category_id']);
        } else {
            $listProfile['ebay_store_category_id'] = '';
            $listProfile['ebay_store_category_path'] = '';
        }

        $conditionEnabled = $features['ConditionEnabled'];
        $conditionValue = $this->getValue($data, 'condition');
        if($conditionEnabled == 'Required' || $conditionEnabled == 'Enabled') {
            foreach($features['ConditionValues'] as $condition) {
                if($conditionValue == $condition['ID']) {
                    $listProfile['item_condition_id'] = $condition['ID'];
                }
            }
            $listProfile['item_condition_description'] = $this->getValue($data, 'condition_description');
        }


        $listProfile['listing_type'] = $this->getValue($data, 'listing_type');
        $listProfile['duration'] = '';
        if($listProfile['listing_type'] == 'Chinese') {
            $listProfile['duration'] =  $this->getValue($data, 'auction_duration');

            if(isset($data['start_price_option'])) {
                $listProfile['price_option'] = $this->getValue($data, 'start_price_option');
                if($listProfile['price_option'] == 'price_extra') {
                    $listProfile['price_extra']['price_plus_minus'] = $this->getValue($data, 'startprice_plus_minus');
                    $listProfile['price_extra']['price_modify_amount'] = $this->getValue($data, 'startprice_modify_amount');
                    $listProfile['price_extra']['price_modify_percent'] = $this->getValue($data, 'startprice_modify_percent', 0);
                } else if($listProfile['price_option'] == 'custom_price') {
                    $listProfile['custom_price']['price_custom_amount'] = $this->getValue($data, 'startprice_custom_amount', 0);
                }
            }

            $listProfile['bin_enabled'] = false;
            if(isset($data['bin_enabled'])) {
                $listProfile['bin_enabled'] = true;
                $listProfile['bin_option'] = $this->getValue($data, 'startprice_custom_amount');

                if($listProfile['bin_option'] == 'price_extra') {
                    $listProfile['bin_price_extra']['price_plus_minus'] = $this->getValue($data, 'bin_plus_minus');
                    $listProfile['bin_price_extra']['price_modify_amount'] = $this->getValue($data, 'bin_modify_amount', 0);
                    $listProfile['bin_price_extra']['price_modify_percent'] = $this->getValue($data, 'bin_modify_percent', 0);
                } else if($listProfile['bin_option'] == 'custom_price') {
                    $listProfile['bin_custom_price']['price_custom_amount'] = $this->getValue($data, 'bin_custom_amount', 0);
                }
            }

        } else if($listProfile['listing_type'] == 'FixedPriceItem') {
            $listProfile['duration'] = $this->getValue($data, 'fixed_duration');
            $listProfile['price_option'] = $this->getValue($data, 'fixed_price_option');

            if($listProfile['price_option'] == 'price_extra') {
                $listProfile['price_extra']['price_plus_minus'] = $this->getValue($data, 'fixedprice_plus_minus');
                $listProfile['price_extra']['price_modify_amount'] = $this->getValue($data, 'fixedprice_modify_amount', 0);
                $listProfile['price_extra']['price_modify_percent'] = $this->getValue($data, 'fixedprice_modify_percent', 0);
            } else if($listProfile['price_option'] == 'custom_price') {
                $listProfile['custom_price']['price_custom_amount'] = $this->getValue($data, 'fixedprice_custom_amount', 0);
            }
        }

        $listProfile['qty_to_sell'] = $this->getValue($data, 'qty_to_sell', 1);
        $listProfile['private_listing'] = isset($data['private_listing']);
        $listProfile['attributes_enabled'] = isset($data['attributes_enabled']);
        //$listProfile['ebay_motors_vin'] = $this->getValue($data, 'ebay_motors_vin');
        $listProfile['template_id'] = $this->getValue($data, 'template_id');
        $listProfile['title_suffix'] = $this->getValue($data, 'title_suffix');
        $listProfile['subtitle'] = $this->getValue($data, 'subtitle');

        $listProfile['country'] = $this->getValue($data, 'country');
        $listProfile['city_state'] = $this->getValue($data, 'city_state');
        $listProfile['zip_postcode'] = $this->getValue($data, 'zip_postcode');
        $listProfile['paypal_email'] = $this->getValue($data, 'paypal_email');


        $listProfile['paypal_required'] = 0;
        if(isset($features['PayPalRequired']) && strtolower($features['PayPalRequired'])=='true') {
            $listProfile['paypal_required'] = 1;
        }

        $listProfile['ean_enabled'] = null;
        if(isset($features['EANEnabled']) && !empty($features['EANEnabled'])) {
            $listProfile['ean_enabled'] = $features['EANEnabled'];
        }

        $listProfile['upc_enabled'] = null;
        if(isset($features['UPCEnabled']) && !empty($features['UPCEnabled'])) {
            $listProfile['upc_enabled'] = $features['UPCEnabled'];
        }

        $listProfile['isbn_enabled'] = null;
        if(isset($features['ISBNEnabled']) && !empty($features['ISBNEnabled'])) {
            $listProfile['isbn_enabled'] = $features['ISBNEnabled'];
        }

        $listProfile['brandmpn_enabled'] = null;
        if(isset($features['BrandMPNEnabled']) && !empty($features['BrandMPNEnabled'])) {
            $listProfile['brandmpn_enabled'] = $features['BrandMPNEnabled'];
        }

        $listProfile['return_policy_enabled'] = 0;
        if(isset($features['ReturnPolicyEnabled']) && strtolower($features['ReturnPolicyEnabled'])=='true') {
            $listProfile['return_policy_enabled'] = 1;
        }

        $listProfile['handling_time_enabled'] = 0;
        if(isset($features['HandlingTimeEnabled']) && strtolower($features['HandlingTimeEnabled'])=='true') {
            $listProfile['handling_time_enabled'] = 1;
        }

        $listProfile['variations_enabled'] = 0;
        if(isset($features['VariationsEnabled']) && strtolower($features['VariationsEnabled'])=='true') {
            $listProfile['variations_enabled'] = 1;
        }

        $listProfile['revise_quantity_allowed'] = 0;
        if(isset($features['ReviseQuantityAllowed']) && strtolower($features['ReviseQuantityAllowed'])=='true') {
            $listProfile['revise_quantity_allowed'] = 1;
        }

        $listProfile['revise_price_allowed'] = 0;
        if(isset($features['RevisePriceAllowed']) && strtolower($features['RevisePriceAllowed'])=='true') {
            $listProfile['revise_price_allowed'] = 1;
        }


        $listProfile['minimum_reserve_price'] = 0;
        if(isset($features['MinimumReservePrice'])) {
            $listProfile['minimum_reserve_price'] = $features['MinimumReservePrice'];
        }

        if(isset($data['location']) && !empty($data['location'])) {
            $listProfile['location'] = $data['location'];
        }



        foreach ($this->getValue($data, 'payment_method', array()) as $payment_method) {
            $listProfile['payment_method'][$payment_method] = 1;
        }

        if(isset($data['payment_instructions']) && !empty($data['payment_instructions'])) {
            $listProfile['payment_instructions'] = $data['payment_instructions'];
        }

        $listProfile['returns_accepted'] = $this->getValue($data, 'returns_accepted');
        if($listProfile['returns_accepted'] == 'ReturnsAccepted') {

            if(isset($data['returns_within']) && !empty($data['returns_within'])) {
                $listProfile['returns_within'] = $data['returns_within'];
            }

            if(isset($data['refunds']) && !empty($data['refunds'])) {
                $listProfile['refunds'] = $data['refunds'];
            }

            if(isset($data['shippingcost_paidby']) && !empty($data['shippingcost_paidby'])) {
                $listProfile['shippingcost_paidby'] = $data['shippingcost_paidby'];
            }

            if(isset($data['return_policy_description']) && !empty($data['return_policy_description'])) {
                //TODO max 5000 chars length allowed
                $listProfile['return_policy_description'] = $data['return_policy_description'];
            }
        }

        if(isset($data['shipping_type']) && !empty($data['shipping_type'])) {
            $listProfile['shipping_type'] = $data['shipping_type'];
            $listProfile['dispatch_time'] = $data['dispatch_time'];

            if(isset($data['has_international_shipping'])) {
                $listProfile['has_international_shipping'] = true;
            } else {
                $listProfile['has_international_shipping'] = false;
            }

            $listProfile['shipping_services'] = array();
            $st = array();

            if($data['shipping_type'] == 'Calculated' || $data['shipping_type'] == 'FlatDomesticCalculatedInternational' || $data['shipping_type'] == 'CalculatedDomesticFlatInternational') {
                $listProfile['shipping_package'] = $data['shipping_package'];
                $listProfile['dimension_depth'] = $data['dimension_depth'];
                $listProfile['dimension_width'] = $data['dimension_width'];
                $listProfile['dimension_length'] = $data['dimension_length'];
                $listProfile['weight_major'] = $data['weight_major'];
                $listProfile['weight_minor'] = $data['weight_minor'];

                $listProfile['is_irregular_package'] = isset($data['is_irregular_package']);
                $listProfile['package_handling_fee'] = 0;
                if(!empty($data['package_handling_fee'])) {
                    $listProfile['package_handling_fee'] = $data['package_handling_fee'];
                }
            }
            if($data['shipping_type'] == 'Calculated') {
                $st[] = 'cd';
                if($listProfile['has_international_shipping']) {
                    $st[] = 'ci';
                }
            } else if($data['shipping_type'] == 'Flat') {
                $st[] = 'fd';
                if($listProfile['has_international_shipping']) {
                    $st[] = 'fi';
                }
            } else if($data['shipping_type'] == 'FlatDomesticCalculatedInternational') {
                $st[] = 'fd';
                $st[] = 'ci';
            } else if($data['shipping_type'] == 'CalculatedDomesticFlatInternational') {
                $st[] = 'cd';
                $st[] = 'fi';
            }

            foreach ($st as $t) {
                for($i=1; $i<=3; $i++) {
                    $shipping_service = $data['ss'][$i][$t];
                    $shipping_service['is_international'] = false;
                    if(!is_numeric($shipping_service['cost'])) {
                        $shipping_service['cost'] = 0;
                    }
                    if(!is_numeric($shipping_service['each_additional'])) {
                        $shipping_service['each_additional'] = 0;
                    }
                    if(isset($shipping_service['free_shipping'])) {
                        $shipping_service['free_shipping'] = true;
                    } else {
                        $shipping_service['free_shipping'] = false;
                    }

                    $shipping_service['locations'] = '';
                    $shipping_service['alocations'] = array();
                    if(isset($shipping_service['shipping_location'])) {
                        $locations = array();
                        foreach ($shipping_service['shipping_location'] as $key => $value) {
                            $locations[] = $key;
                        }
                        $shipping_service['locations'] = implode(",", $locations);
                        $shipping_service['alocations'] = $locations;
                    }

                    if($t == 'ci' || $t == 'fi') {
                        $shipping_service['is_international'] = true;
                    }


                    if(!empty($shipping_service['service'])) {
                        $listProfile['shipping_services'][$t][] = $shipping_service;
                    }
                }
            }

        }

        if($listProfile['price_option'] == 'price_extra') {
            $listProfile['price_plus_minus'] = $listProfile[$listProfile['price_option']]['price_plus_minus'];
            $listProfile['price_modify_amount'] = $listProfile[$listProfile['price_option']]['price_modify_amount'];
            $listProfile['price_modify_percent'] = $listProfile[$listProfile['price_option']]['price_modify_percent'];
        } else if ($listProfile['price_option'] == 'custom_price') {
            $listProfile['price_custom_amount'] = $listProfile[$listProfile['price_option']]['price_custom_amount'];
        }

        if(isset($listProfile['bin_enabled']) && $listProfile['bin_enabled']) {
            $listProfile['bin_option'] = $listProfile['bin_option'];
            $listProfile['bin_enabled'] = $listProfile['bin_enabled'];
            if($listProfile['bin_option'] == 'price_extra') {
                $listProfile['bin_plus_minus'] = $listProfile['bin_price_extra']['price_plus_minus'];
                $listProfile['bin_modify_amount'] = $listProfile['bin_price_extra']['price_modify_amount'];
                $listProfile['bin_modify_percent'] = $listProfile['bin_price_extra']['price_modify_percent'];
            } else if ($listProfile['bin_option'] == 'custom_price') {
                $listProfile['bin_custom_amount'] = $listProfile['bin_custom_price']['price_custom_amount'];
            }

        }

        return $listProfile;
    }

    private function getValue($object, $key, $default = '') {
        return (isset($object[$key]))? $object[$key] : $default;
    }

}