<?php
/**
 * Created by PhpStorm.
 * User: Ion
 * Date: 6/3/2015
 * Time: 5:00 PM
 */

class ModelEbayChannelShippingProfileDao extends Model {

    public function insert($data) {

        $rServices = $data['services'];
        $lService = array();

        foreach($rServices['domestic_service'] as $domestic_service) {
            $isEmpty = true;
            if(!empty($domestic_service['flat'])) {
                $isEmpty = false;
            }
            if(!empty($domestic_service['calculated'])) {
                $isEmpty = false;
            }

           if(!$isEmpty) {

           }

        }

        //var_dump($data['services']);die();

        $this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_shipping_profile` SET "
            . "   `site_id` = '" . $this->db->escape($data['site_id'])
            . "', `name` = '" . $this->db->escape($data['name'])
            . "', `is_default` = '" . ((isset($data['is_default']) && !empty($data['is_default']))? '1' : '0')
            . "', `is_irregular_package` = '" . ((isset($data['is_irregular_package']) && !empty($data['is_irregular_package']))? '1' : '0')
            . "', `has_international_shipping` = '" . ((isset($data['has_international_shipping']) && !empty($data['has_international_shipping']))? '1' : '0')
            . "', `shipping_terms_in_description` = '" . ((isset($data['shipping_terms_in_description']) && !empty($data['shipping_terms_in_description']))? '1' : '0')
            . "', `description` = '" . $this->db->escape($data['description'])
            . "', `country` = '" . $this->db->escape($data['country'])
            . "', `postal_code` = '" . $this->db->escape($data['postal_code'])
            . "', `location` = '" . $this->db->escape($data['location'])
            . "', `dispatch_time_max` = '" . $this->db->escape($data['dispatch_time_max'])
            . "', `service_type` = '" . $this->db->escape($data['service_type'])
            . "', `package_type` = '" . $this->db->escape($data['package_type'])
            . "', `dimension_depth` = '" . $this->db->escape($data['dimension_depth'])
            . "', `dimension_width` = '" . $this->db->escape($data['dimension_width'])
            . "', `dimension_length` = '" . $this->db->escape($data['dimension_length'])
            . "', `weight_major` = '" . $this->db->escape($data['weight_major'])
            . "', `weight_minor` = '" . $this->db->escape($data['weight_minor'])
            . "', `package_handling_fee` = '" . $this->db->escape($data['package_handling_fee'])
            . "', `service_options` = '" . json_encode($data['services'])

            . "', `added_date` = now()");

    }

    public function deleteById($id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_shipping_profile` WHERE id = " . (int) $id);
    }

    public function findById($id) {
       return $this->db->query("SELECT * FROM `" . DB_PREFIX . "channel_ebay_shipping_profile` WHERE id = " . (int) $id)->row;
    }

    public function getColumns() {
        return array('sp.name', 'sp.is_default', 'sp.id');
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

        $sQuery = "SELECT sp.name, sp.is_default, sp.id
						FROM ". DB_PREFIX ."channel_ebay_shipping_profile sp"
            . " " . $sOrder . " " . $sLimit . " ";

        $result = $this->db->query($sQuery)->rows;


        $sQuery =  "SELECT count(*) as c FROM " . DB_PREFIX . "channel_ebay_shipping_profile";
        $resultTotal = $this->db->query($sQuery)->row['c'];

        $r = new stdClass();
        $r->result = $result;
        $r->count = $resultTotal;
        $r->filterCount = $resultTotal;

        return $r;
    }


}