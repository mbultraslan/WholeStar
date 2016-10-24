<?php
class ModelEbayChannelDao extends Model {

    private $version = '2.1.8';
    private $vqmod = '_21';

    public function getVersion() {
        return $this->version;
    }

    public function getDurationDisplayName($duration) {
        if($duration == "GTC") {
            return "Good 'Til Cancelled";
        } else if($duration == "Days_1") {
            return "1 Day";
        } else if($duration == "Days_10") {
            return "10 Days";
        } else if($duration == "Days_120") {
            return "120 Days";
        } else if($duration == "Days_14") {
            return "14 Days";
        } else if($duration == "Days_21") {
            return "21 Days";
        } else if($duration == "Days_3") {
            return "3 Days";
        } else if($duration == "Days_30") {
            return "30 Days";
        } else if($duration == "Days_5") {
            return "5 Days";
        } else if($duration == "Days_60") {
            return "60 Days";
        } else if($duration == "Days_7") {
            return "7 Days";
        } else if($duration == "Days_90") {
            return "90 Days";
        }
        return $duration;
    }

    public function getEbayAccount() {
        $account = $this->db->query("SELECT * FROM `" . DB_PREFIX . "channel_ebay_acount` LIMIT 1")->row;
        if(!empty($account) && (!isset($account['version']) || $account['version'] != $this->version)) {
            set_time_limit(0);
            $clearLink = $this->url->link('extension/modification/refresh', 'token=' . $this->session->data['token'], 'SSL');

            $this->updateOrInstall();
            $this->db->query("UPDATE `" . DB_PREFIX . "channel_ebay_acount` SET version='" . $this->db->escape($this->version) . "'");
            $this->session->data['ebay_global_message'] = "<p>Ebay Channel was updated to version " . $this->version
                . ', please refresh modification </p> <p><a href="' .$clearLink . '" class="btn btn-danger">Refresh Modification</a></p>';
        }
        return $account;
    }

    public function getCurrentUser($user_id) {
        return $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . $this->db->escape($user_id) . "'")->row;
    }

    public function getStoreById($store_id) {
        return $this->db->query("SELECT * FROM " . DB_PREFIX . "store WHERE store_id = '" . $this->db->escape($store_id) . "'")->row;
    }

    public function deleteEbayAccount() {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_acount`");
    }

    public function addEbayItemSpecific($itemSpecific, $category_id, $site_id) {
        $this->db->query(
            "INSERT INTO `" . DB_PREFIX . "channel_ebay_item_specific`
                	SET `ebay_category_id` = '" . $this->db->escape($category_id)
            . "', `is_required` = '" . $this->db->escape(($itemSpecific['MinValues'] > 0)? 1 : 0)
            . "', `site_id` = '" . $this->db->escape($site_id)
            . "', `name` = '" . $this->db->escape($itemSpecific['Name']) . "'");
        $id = $this->db->getLastId();

        if(isset($itemSpecific['Values'])) {
            foreach ($itemSpecific['Values'] as $value) {
                $this->db->query(
                    "INSERT INTO `" . DB_PREFIX . "channel_ebay_item_specific_values`
                	SET `ebay_category_id` = '" . $this->db->escape($category_id)
                    . "', `item_specific_id` = '" . $id
                    . "', `site_id` = '" . $this->db->escape($site_id)
                    . "', `value` = '" . $this->db->escape($value) . "'");
            }
        }
    }

    public function removeEbayItemSpecificBySiteId($site_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_item_specific` where `site_id`=". $this->db->escape($site_id));
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_item_specific_values` where `site_id`=". $this->db->escape($site_id));
    }

    public function getEbayItemSpecific($category_id, $site_id) {
        $specifics = $this->db->query("SELECT * FROM " . DB_PREFIX . "channel_ebay_item_specific"
            . " where is_required=1 "
            . " and site_id= '" . $this->db->escape($site_id)
            . "' and ebay_category_id='". $this->db->escape($category_id) . "'")->rows;
        if(!empty($specifics)) {
            $ids = '';
            foreach ($specifics as $specific) {
                $ids .= $specific['id'] . ',';
            }
            $ids = rtrim($ids, ",");

            $values = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'channel_ebay_item_specific_values` where item_specific_id in ('. $ids . ')')->rows;
            if(!empty($specifics)) {
                foreach ($specifics as $key => $specific) {
                    foreach ($values as $value) {
                        if($value['item_specific_id'] == $specific['id']) {
                            $specifics[$key]['values'][] = $value;
                        }
                    }
                }
            }
        }
        return $specifics;
    }

    public function hasEbayItemSpecific($category_id, $site_id) {
        $count = $this->db->query("SELECT count(*) as cnt FROM `" . DB_PREFIX . "channel_ebay_item_specific` where site_id = '" . $this->db->escape($site_id)  ."' and ebay_category_id=". $this->db->escape($category_id))->row;
        return $count['cnt'] > 0;
    }

    public function getLanguages() {
        return $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` order by sort_order asc")->rows;
    }

    public function addEbayAccount($data) {
        $this->deleteEbayAccount();
        $this->db->query(
            "INSERT INTO `" . DB_PREFIX . "channel_ebay_acount`
                	SET `dev_id` = '" . $this->db->escape($data['dev_id'])

            . "', `eias_token` = '" . $this->db->escape($data['eiasToken'])
            . "', `expirationTime` = '" . $this->db->escape($data['expirationTime'])
            . "', `revocationTime` = '" . $this->db->escape($data['revocationTime'])
            . "', `status` = '" . $this->db->escape($data['status'])
            . "', `user_id` = '" . $this->db->escape($data['user_id'])
            . "', `store_url` = '" . ((isset($data['store_url']))? $data['store_url'] : '')
            . "', `store_name` = '" . ((isset($data['store_name']))? $data['store_name'] : '')
            . "', `version` = '" . $this->db->escape($this->version)
            . "', `app_id` = '" . $this->db->escape($data['app_id'])
            . "', `cert_id` = '" . $this->db->escape($data['cert_id'])
            . "', `token` = '" . $this->db->escape($data['token'])
            . "', `listing_mode` = '" . $this->db->escape($data['listing_mode'])
            . "', `default_site` = '" . $this->db->escape($data['default_site']) . "'");
    }

    public function getCategoriesCountBySiteId($siteId) {
        return $this->db->query("SELECT count(*) as cnt1 FROM " . DB_PREFIX . "channel_ebay_category where site_id='". $this->db->escape($siteId) . "'")->row['cnt1'];
    }

    public function getStoreCategoriesCount() {
        return $this->db->query('SELECT count(*) as cnt FROM ' . DB_PREFIX . 'channel_ebay_store_category')->row['cnt'];
    }


    public function getEbaySites($default_site=0) {
        $sites = array();
        $sites[] = array('id'=>0, 'code'=>'US', 'name'=>'United States', 'site'=>'ebay.com', 'currency'=>'USD', 'selected'=>(($default_site == 0)? true : false));
        $sites[] = array('id'=>100, 'code'=>'eBayMotors', 'name'=>'US eBay Motors', 'site'=>'ebay.com', 'currency'=>'USD', 'selected'=>(($default_site == 100)? true : false));

        $sites[] = array('id'=>2, 'code'=>'Canada', 'name'=>'Canada', 'site'=>'ebay.ca', 'currency'=>'CAD', 'selected'=>(($default_site == 2)? true : false));

        $sites[] = array('id'=>3, 'code'=>'UK', 'name'=>'United Kingdom', 'site'=>'ebay.co.uk', 'currency'=>'GBP', 'selected'=>(($default_site == 3)? true : false));

        $sites[] = array('id'=>15, 'code'=>'Australia', 'name'=>'Australia', 'site'=>'ebay.com.au', 'currency'=>'AUD', 'selected'=>(($default_site == 15)? true : false));
        $sites[] = array('id'=>16, 'code'=>'Austria', 'name'=>'Austria', 'site'=>'ebay.at', 'currency'=>'EUR', 'selected'=>(($default_site == 16)? true : false));

        $sites[] = array('id'=>23, 'code'=>'Belgium_French', 'name'=>'Belgium (French)', 'site'=>'ebay.be', 'currency'=>'EUR', 'selected'=>(($default_site == 23)? true : false));
        $sites[] = array('id'=>123, 'code'=>'Belgium_Dutch', 'name'=>'Belgium (Dutch)', 'site'=>'ebay.be', 'currency'=>'EUR', 'selected'=>(($default_site == 123)? true : false));

        $sites[] = array('id'=>71, 'code'=>'France', 'name'=>'France', 'site'=>'ebay.fr', 'currency'=>'EUR', 'selected'=>(($default_site == 71)? true : false));
        $sites[] = array('id'=>77, 'code'=>'Germany', 'name'=>'Germany', 'site'=>'ebay.de', 'currency'=>'EUR', 'selected'=>(($default_site == 77)? true : false));
        $sites[] = array('id'=>205, 'code'=>'Ireland', 'name'=>'Ireland', 'site'=>'ebay.de', 'currency'=>'EUR', 'selected'=>(($default_site == 77)? true : false));
        $sites[] = array('id'=>193, 'code'=>'Switzerland', 'name'=>'Switzerland', 'site'=>'ebay.de', 'currency'=>'CHF', 'selected'=>(($default_site == 77)? true : false));
        $sites[] = array('id'=>101, 'code'=>'Italy', 'name'=>'Italy', 'site'=>'ebay.it', 'currency'=>'EUR', 'selected'=>(($default_site == 101)? true : false));
        $sites[] = array('id'=>146, 'code'=>'Netherlands', 'name'=>'Netherlands', 'site'=>'ebay.nl', 'currency'=>'EUR', 'selected'=>(($default_site == 146)? true : false));


        $sites[] = array('id'=>186, 'code'=>'Spain', 'name'=>'Spain', 'site'=>'ebay.es', 'currency'=>'EUR', 'selected'=>(($default_site == 186)? true : false));
        $sites[] = array('id'=>201, 'code'=>'HongKong', 'name'=>'Hong Kong', 'site'=>'ebay.com.hk', 'currency'=>'HKD', 'selected'=>(($default_site == 201)? true : false));
        $sites[] = array('id'=>203, 'code'=>'India', 'name'=>'India', 'site'=>'ebay.in', 'currency'=>'INR', 'selected'=>(($default_site == 203)? true : false));
        $sites[] = array('id'=>207, 'code'=>'Malaysia', 'name'=>'Malaysia', 'site'=>'ebay.com.my', 'currency'=>'MYR', 'selected'=>(($default_site == 207)? true : false));
        $sites[] = array('id'=>211, 'code'=>'Philippines', 'name'=>'Philippines', 'site'=>'ebay.ph', 'currency'=>'PHP', 'selected'=>(($default_site == 211)? true : false));
        $sites[] = array('id'=>212, 'code'=>'Poland', 'name'=>'Poland', 'site'=>'ebay.pl', 'currency'=>'PLN', 'selected'=>(($default_site == 212)? true : false));
        $sites[] = array('id'=>216, 'code'=>'Singapore', 'name'=>'Singapore', 'site'=>'ebay.com.sg', 'currency'=>'SGD', 'selected'=>(($default_site == 216)? true : false));

        return $sites;
    }

    public function getEbaySite($siteId) {
        $sites = $this->getEbaySites($siteId);
        foreach ($sites as $site) {
            if($site['id'] == $siteId) {
                return $site;
            }
        }
        return false;
    }

    public function getEbaySiteByCode($siteCode) {
        $sites = $this->getEbaySites();
        foreach ($sites as $site) {
            if($site['code'] == $siteCode) {
                return $site;
            }
        }

        return false;
    }

    public function getEbayCategoryPath($site_id, $category_id) {
        $category = $this->getEbayCategoryById($site_id, $category_id);
        if(!empty($category)) {
            if($category['id'] == $category['parent_id']) {
                return $category['name'];
            } else {
                return $this->getEbayCategoryPath($site_id, $category['parent_id']) . " > " . $category['name'];
            }
        }
        return "";
    }

    public function getEbayStoreCategoryPath($category_id) {
        $category = $this->getEbayStoreCategoryById($category_id);
        if(!empty($category)) {
            if($category['id'] == $category['parent_id']) {
                return $category['name'];
            } else {
                return $this->getEbayStoreCategoryPath($category['parent_id']) . " > " . $category['name'];
            }
        }
        return "";
    }

    public function getEbayCategoryTreeById($site_id, $category_id) {
        $tree = array();
        $category = $this->getEbayCategoryById($site_id, $category_id);
        if(!empty($category)) {
            if($category['id'] == $category['parent_id']) {
                $tree[] = $category;
            } else {
                //array_push
                $tree = $this->getEbayCategoryTreeById($site_id, $category['parent_id']);
                array_push($tree, $category);
            }
        }
        return $tree;
    }

    public function getEbayShippingService($code) {
        return $this->db->query("select * from `" . DB_PREFIX . "channel_ebay_shipping_service` where shipping_service='" . $this->db->escape($code) . "'" )->rows;
    }

    public function getEbayShippingServiceTypes($site_id) {
        $domFlat = $this->db->query("select count(*) as cnt from `" . DB_PREFIX . "channel_ebay_shipping_service` where site_id="
            . $this->db->escape($site_id) . " and is_flat=1 and is_international_service=0")->row;
        $domCalculated = $this->db->query("select count(*) as cnt from `" . DB_PREFIX . "channel_ebay_shipping_service` where site_id="
            . $this->db->escape($site_id) . " and is_calculated=1 and is_international_service=0")->row;

        $intFlat = $this->db->query("select count(*) as cnt from `" . DB_PREFIX . "channel_ebay_shipping_service` where site_id="
            . $this->db->escape($site_id) . " and is_flat=1 and is_international_service=1")->row;
        $intCalculated = $this->db->query("select count(*) as cnt from `" . DB_PREFIX . "channel_ebay_shipping_service` where site_id="
            . $this->db->escape($site_id) . " and is_calculated=1 and is_international_service=1")->row;

        $serviceTypes = array();
        if($domFlat['cnt'] > 0) {
            $serviceTypes[] = array('name'=> 'Flat');
        }

        if($domCalculated['cnt'] > 0) {
            $serviceTypes[] = array('name'=> 'Calculated');
        }

        if($domFlat['cnt'] > 0 && $intCalculated['cnt'] > 0) {
            $serviceTypes[] = array('name'=> 'FlatDomesticCalculatedInternational');
        }

        if($domCalculated['cnt'] > 0 && $intFlat['cnt'] > 0) {
            $serviceTypes[] = array('name'=> 'CalculatedDomesticFlatInternational');
        }

        return $serviceTypes;
    }

    public function getEbayHandlingTime($site_id) {
        return $this->db->query("select description as label, dispatch_time_max as code from `" . DB_PREFIX . "channel_ebay_dispatch_time_max` where site_id="
            . $this->db->escape($site_id). " order by dispatch_time_max")->rows;
    }

    public function getEbayShippingPackages($site_id) {
        return $this->db->query("select description as label, shipping_package as code from `" . DB_PREFIX . "channel_ebay_shipping_package` where site_id="
            . $this->db->escape($site_id). " order by description")->rows;
    }

    public function getEbayCarriers($site_id) {
        return $this->db->query("select code, description from `" . DB_PREFIX . "channel_ebay_shipping_carrier` where site_id="
            . $this->db->escape($site_id). " order by description ")->rows;
    }

    public function getEbayFlatDomestic($site_id) {
        return $this->db->query("select shipping_service, description, shipping_carrier from `" . DB_PREFIX . "channel_ebay_shipping_service` where site_id="
            . $this->db->escape($site_id). " and is_flat = 1 and is_international_service = 0 order by shipping_service asc ")->rows;
    }

    public function getEbayCalculatedDomestic($site_id) {
        return $this->db->query("select shipping_service, description, shipping_carrier from `" . DB_PREFIX . "channel_ebay_shipping_service` where site_id="
            . $this->db->escape($site_id). " and is_calculated = 1 and is_international_service = 0 order by shipping_service asc ")->rows;
    }

    public function getEbayFlatInternational($site_id) {
        return $this->db->query("select shipping_service, description, shipping_carrier from `" . DB_PREFIX . "channel_ebay_shipping_service` where site_id="
            . $this->db->escape($site_id). " and is_flat = 1 and is_international_service = 1 order by shipping_service asc ")->rows;
    }

    public function getEbayCalculatedInternational($site_id) {
        return $this->db->query("select shipping_service, description, shipping_carrier from `" . DB_PREFIX . "channel_ebay_shipping_service` where site_id="
            . $this->db->escape($site_id). " and is_calculated = 1 and is_international_service = 1 order by shipping_service asc ")->rows;
    }

    public function getEbayReturnsAccepted($site_id) {
        return $this->db->query("select * from `" . DB_PREFIX . "channel_ebay_return_accepted` where site_id=" . $this->db->escape($site_id) )->rows;
    }

    public function getEbayReturnsWithin($site_id) {
        return $this->db->query("select * from `" . DB_PREFIX . "channel_ebay_return_within` where site_id=" . $this->db->escape($site_id) )->rows;
    }

    public function getEbayRefunds($site_id) {
        return $this->db->query("select * from `" . DB_PREFIX . "channel_ebay_refund` where site_id=" . $this->db->escape($site_id) )->rows;
    }

    public function getEbayShippingCostPaidBy($site_id) {
        return $this->db->query("select * from `" . DB_PREFIX . "channel_ebay_shippingcost_paidby` where site_id=" . $this->db->escape($site_id) )->rows;
    }



    private function getEbayCategoryById($site_id, $category_id) {
        return $this->db->query("select * from `" . DB_PREFIX . "channel_ebay_category` where site_id="
            . $this->db->escape($site_id)
            . " and id=".$this->db->escape($category_id). " limit 1")->row;
    }

    private function getEbayStoreCategoryById($category_id) {
        return $this->db->query("select * from `" . DB_PREFIX . "channel_ebay_store_category` where "
            . " id=".$this->db->escape($category_id). " limit 1")->row;
    }


    public function getEbayCategoriesTreeView($site_id, $parent_id) {
        $data = array();
        $categories = $this->db->query("select * from `" . DB_PREFIX . "channel_ebay_category` where site_id="
            . $this->db->escape($site_id)
            . ((empty($parent_id))? " and id=parent_id " : " and  id<>parent_id and parent_id=". $this->db->escape($parent_id)))->rows;
        $ids = '';
        foreach($categories as $category) {
            $ids .= $category['id'] . ",";
        }
        $ids[strlen($ids)-1] = ' ';

        $categoriesCounts = $this->db->query("select parent_id, count(*) as cnt from `" . DB_PREFIX . "channel_ebay_category` where site_id="
            . $this->db->escape($site_id)
            . " and parent_id in (" . $ids . ") group by parent_id")->rows;

        foreach($categories as $key => $category){
            $categories[$key]['childs_count'] = 0;
            foreach($categoriesCounts as $categoryCount) {
                if($categoryCount['parent_id'] == $category['id']) {
                    $categories[$key]['childs_count'] = $categoryCount['cnt'];
                }
            }
        }

        foreach($categories as $category) {
            $hasChilds = $category['childs_count'] > 1;
            $data[] = array('title'=> $category['name'], 'key' => $category['id'], 'isFolder'=> $hasChilds, 'isLazy'=> $hasChilds, "unselectable"=>$hasChilds);
        }

        return $data;
    }

    public function getEbayStoreCategoriesTreeView($parent_id) {
        $data = array();
        $categories = $this->db->query("select * from `" . DB_PREFIX . "channel_ebay_store_category` where "
            . ((empty($parent_id))? " id=parent_id " : " id<>parent_id and parent_id=". $this->db->escape($parent_id)))->rows;
        $ids = '';
        foreach($categories as $category) {
            $ids .= $category['id'] . ",";
        }
        $ids[strlen($ids)-1] = ' ';

        $categoriesCounts = $this->db->query("select parent_id, count(*) as cnt from `" . DB_PREFIX . "channel_ebay_store_category` where "
            . " parent_id in (" . $ids . ") group by parent_id")->rows;

        foreach($categories as $key => $category){
            $categories[$key]['childs_count'] = 0;
            foreach($categoriesCounts as $categoryCount) {
                if($categoryCount['parent_id'] == $category['id']) {
                    $categories[$key]['childs_count'] = $categoryCount['cnt'];
                }
            }
        }

        foreach($categories as $category) {
            $hasChilds = $category['childs_count'] > 1;
            $data[] = array('title'=> $category['name'], 'key' => $category['id'], 'isFolder'=> $hasChilds, 'isLazy'=> $hasChilds, "unselectable"=>$hasChilds);
        }

        return $data;
    }



    public function addEbayCountries($countries, $site_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_country` where site_id=" . $this->db->escape($site_id) . "");
        $insert = "INSERT INTO " . DB_PREFIX . "channel_ebay_country (country, description, site_id) values ";
        $hasForInsert = false;
        foreach($countries as $country) {
            $insert .= "("
                ."'".$this->db->escape($country['Country']) . "',"
                ."'".$this->db->escape($country['Description']) . "',"
                ."'".$this->db->escape($site_id) . "'"
                ."),";
            $hasForInsert = true;
        }
        if($hasForInsert) {
            $insert[strlen($insert)-1] = ' ';
            $this->db->query($insert);
        }
    }

    public function addEbayShippingToLocations($locations, $site_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_shipptolocation` where site_id=" . $this->db->escape($site_id) . "");
        $insert = "INSERT INTO " . DB_PREFIX . "channel_ebay_shipptolocation (location, description, site_id) values ";
        $hasForInsert = false;
        foreach($locations as $location) {
            $insert .= "("
                ."'".$this->db->escape($location['ShippingLocation']) . "',"
                ."'".$this->db->escape($location['Description']) . "',"
                ."'".$this->db->escape($site_id) . "'"
                ."),";
            $hasForInsert = true;
        }
        if($hasForInsert) {
            $insert[strlen($insert)-1] = ' ';
            $this->db->query($insert);
        }
    }


    public function addEbayRefunds($values, $site_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_refund` where site_id=" . $this->db->escape($site_id) . "");
        $insert = "INSERT INTO " . DB_PREFIX . "channel_ebay_refund (`option`, `description`, `site_id`) values ";
        $hasForInsert = false;
        foreach($values as $value) {
            $insert .= "("
                ."'".$this->db->escape($value['RefundOption']) . "',"
                ."'".$this->db->escape($value['Description']) . "',"
                ."'".$this->db->escape($site_id) . "'"
                ."),";
            $hasForInsert = true;
        }
        if($hasForInsert) {
            $insert[strlen($insert)-1] = ' ';
            $this->db->query($insert);
        }
    }

    public function addEbayReturnsAccepted($values, $site_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_return_accepted` where site_id=" . $this->db->escape($site_id) . "");
        $insert = "INSERT INTO " . DB_PREFIX . "channel_ebay_return_accepted (`option`, `description`, `site_id`) values ";
        $hasForInsert = false;
        foreach($values as $value) {
            $insert .= "("
                ."'".$this->db->escape($value['ReturnsAcceptedOption']) . "',"
                ."'".$this->db->escape($value['Description']) . "',"
                ."'".$this->db->escape($site_id) . "'"
                ."),";
            $hasForInsert = true;
        }
        if($hasForInsert) {
            $insert[strlen($insert)-1] = ' ';
            $this->db->query($insert);
        }
    }

    public function addEbayReturnWithin($values, $site_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_return_within` where site_id=" . $this->db->escape($site_id) . "");
        $insert = "INSERT INTO " . DB_PREFIX . "channel_ebay_return_within (`option`, `description`, `site_id`) values ";
        $hasForInsert = false;
        foreach($values as $value) {
            $insert .= "("
                ."'".$this->db->escape($value['ReturnsWithinOption']) . "',"
                ."'".$this->db->escape($value['Description']) . "',"
                ."'".$this->db->escape($site_id) . "'"
                ."),";
            $hasForInsert = true;
        }
        if($hasForInsert) {
            $insert[strlen($insert)-1] = ' ';
            $this->db->query($insert);
        }
    }

    public function addEbayShippingCostPaidBy($values, $site_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_shippingcost_paidby` where site_id=" . $this->db->escape($site_id) . "");
        $insert = "INSERT INTO " . DB_PREFIX . "channel_ebay_shippingcost_paidby (`option`, `description`, `site_id`) values ";
        $hasForInsert = false;
        foreach($values as $value) {
            $insert .= "("
                ."'".$this->db->escape($value['ShippingCostPaidByOption']) . "',"
                ."'".$this->db->escape($value['Description']) . "',"
                ."'".$this->db->escape($site_id) . "'"
                ."),";
            $hasForInsert = true;
        }
        if($hasForInsert) {
            $insert[strlen($insert)-1] = ' ';
            $this->db->query($insert);
        }
    }



    public function addEbayDispatchTimeMax($dispatch_times, $site_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_dispatch_time_max` where site_id=" . $this->db->escape($site_id) . "");
        $insert = "INSERT INTO " . DB_PREFIX . "channel_ebay_dispatch_time_max (description, is_extended_handling, dispatch_time_max, site_id) values ";
        $hasForInsert = false;
        foreach($dispatch_times as $dispatch_time) {
            $insert .= "("
                ."'".$this->db->escape($dispatch_time['Description']) . "',"
                ."'".$this->db->escape(($dispatch_time['ExtendedHandling'] == 'false')? 0 : 1) . "',"
                ."'".$this->db->escape($dispatch_time['DispatchTimeMax']) . "',"
                ."'".$this->db->escape($site_id) . "'"
                ."),";
            $hasForInsert = true;
        }
        if($hasForInsert) {
            $insert[strlen($insert)-1] = ' ';
            $this->db->query($insert);
        }
    }

    public function getEbayCountries($site_id) {
        return $this->db->query("select description as name, country as code from `" . DB_PREFIX . "channel_ebay_country` where site_id="
            . $this->db->escape($site_id)
            . " order by description asc")->rows;
    }

    public function getEbayShippingLocations($site_id) {
        return $this->db->query("select description as name, location as code from `" . DB_PREFIX . "channel_ebay_shipptolocation` where site_id="
            . $this->db->escape($site_id)
            . " order by description asc")->rows;
    }


    public function addEbayShippingCarriers($carriers, $site_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_shipping_carrier` where site_id=" . $this->db->escape($site_id) . "");
        $insert = "INSERT INTO " . DB_PREFIX . "channel_ebay_shipping_carrier (id, code, description, site_id) values ";
        $hasForInsert = false;
        foreach($carriers as $carrier) {
            $insert .= "("
                ."'".$this->db->escape($carrier['ShippingCarrierID']) . "',"
                ."'".$this->db->escape($carrier['ShippingCarrier']) . "',"
                ."'".$this->db->escape($carrier['Description']) . "',"
                ."'".$this->db->escape($site_id) . "'"
                ."),";
            $hasForInsert = true;
        }
        if($hasForInsert) {
            $insert[strlen($insert)-1] = ' ';
            $this->db->query($insert);
        }
    }

    public function addEbayShippingPackages($packages, $site_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_shipping_package` where site_id=" . $this->db->escape($site_id) . "");
        $insert = "INSERT INTO " . DB_PREFIX . "channel_ebay_shipping_package (id, description, shipping_package, is_default, site_id) values ";
        $hasForInsert = false;
        foreach($packages as $package) {
            $insert .= "("
                ."'".$this->db->escape($package['PackageID']) . "',"
                ."'".$this->db->escape($package['Description']) . "',"
                ."'".$this->db->escape($package['ShippingPackage']) . "',"
                ."'".(($package['DefaultValue'] == 'false')? '0' : '1') . "',"
                ."'".$this->db->escape($site_id) . "'"
                ."),";
            $hasForInsert = true;
        }
        if($hasForInsert) {
            $insert[strlen($insert)-1] = ' ';
            $this->db->query($insert);
        }
    }

    public function addEbayShippingServices($packages, $site_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_shipping_service` where site_id=" . $this->db->escape($site_id) . "");
        $insert = "INSERT INTO " . DB_PREFIX . "channel_ebay_shipping_service (id, description, is_international_service,
									shipping_carrier, shipping_package, 
									is_calculated, is_flat, shipping_service, shipping_category, 
									is_cod, is_dimensions_required, is_weight_required, shipping_time_max, shipping_time_min, site_id) values ";
        $hasForInsert = false;
        foreach($packages as $package) {
            $insert .= "("
                ."'".$this->db->escape($package['ShippingServiceID']) . "',"
                ."'".$this->db->escape($package['Description']) . "',"
                ."'".(($package['InternationalService'] == 'false')? '0' : '1') . "',"
                ."'".$this->db->escape($package['ShippingCarrier']) . "',"
                ."'".$this->db->escape($package['ShippingPackage']) . "',"
                ."'".(($package['is_calculated'])? '1' : '0') . "',"
                ."'".(($package['is_flat'])? '1' : '0') . "',"
                ."'".$this->db->escape($package['ShippingService']) . "',"
                ."'".$this->db->escape($package['ShippingCategory']) . "',"
                ."'".(($package['CODService'] == 'false')? '0' : '1') . "',"
                ."'".(($package['DimensionsRequired'] == 'false')? '0' : '1') . "',"
                ."'".(($package['WeightRequired'] == 'false')? '0' : '1') . "',"
                ."'".$this->db->escape($package['ShippingTimeMax']) . "',"
                ."'".$this->db->escape($package['ShippingTimeMin']) . "',"
                ."'".$this->db->escape($site_id) . "'"
                ."),";
            $hasForInsert = true;
        }
        if($hasForInsert) {
            $insert[strlen($insert)-1] = ' ';
            $this->db->query($insert);
        }
    }

    public function hasCategories($site_id) {
        $cnt = $this->db->query("SELECT count(*) as cnt FROM " . DB_PREFIX . "channel_ebay_category where site_id= '" . $this->db->escape($site_id) . "'" )->row['cnt'];
        return $cnt > 0;
    }

    public function addEbayCategories($categories, $site_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_category` where site_id=" . $this->db->escape($site_id) . "");
        $insert = "INSERT INTO " . DB_PREFIX . "channel_ebay_category (id, name, parent_id, level, site_id) values ";
        $batchCount = 0;
        $hasForInsert = false;
        foreach($categories as $category) {
            $insert .= "("
                ."'".$this->db->escape($category['categoryID']) . "',"
                ."'".$this->db->escape($category['categoryName']) . "',"
                ."'".$this->db->escape($category['categoryParentID']) . "',"
                ."'".$this->db->escape($category['categoryLevel']) . "',"
                ."'".$this->db->escape($site_id) . "'"
                ."),";
            $hasForInsert = true;
            $batchCount++;
            if($batchCount > 99) {
                $insert[strlen($insert)-1] = ' ';
                $this->db->query($insert);
                $insert = "INSERT INTO " . DB_PREFIX . "channel_ebay_category (id, name, parent_id, level, site_id) values ";
                $hasForInsert = false;
                $batchCount = 0;
            }
        }
        if($hasForInsert) {
            $insert[strlen($insert)-1] = ' ';
            $this->db->query($insert);
        }
    }

    public function clearEbayStoreCategories() {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_store_category`");
    }

    public function addEbayStoreCategories($categories, $parentId = '0') {
        foreach($categories as $category) {
            $this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "channel_ebay_store_category SET
								parent_id = '" .  (($parentId == 0)? $category['category_id'] : $parentId) . "',
								id = '" . $this->db->escape($category['category_id']) . "', 
								name = '" . $this->db->escape($category['name']) . "'");


            if(!empty($category['child_category'])) {
                $this->addEbayStoreCategories($category['child_category'], $category['category_id']);
            }

        }
    }

    public function getStoreOptions() {
        return $this->db->query("select od.name, o.option_id from `" . DB_PREFIX . "option` o "
            . " left join `" . DB_PREFIX . "option_description` od on od.option_id = o.option_id"
            . " where od.language_id = '" . (int)$this->config->get('config_language_id') . "' and o.type in ('select', 'checkbox', 'radio') order by od.name")->rows;
    }

    public function getStoreAttributes() {
        return $this->db->query("select ad.name as name, a.attribute_id as id, ag.name as group_name from `" . DB_PREFIX . "attribute` a "
            . " left join `" . DB_PREFIX . "attribute_description` ad on ad.attribute_id = a.attribute_id"
            . " left join `" . DB_PREFIX . "attribute_group_description` ag on ag.attribute_group_id = a.attribute_group_id"
            . " where ad.language_id = '" . (int)$this->config->get('config_language_id') . "'"
            . " and ag.language_id = '" . (int)$this->config->get('config_language_id') . "'")->rows;
    }

    public function upgradeEbayAccountTable() {
        $table_name = 'channel_ebay_acount';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "dev_id", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "app_id", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "cert_id", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "status", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "eias_token", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "user_id", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "expirationTime", "Type" => "timestamp", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "revocationTime", "Type" => "timestamp", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "token", "Type" => "varchar(1000)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "store_url", "Type" => "varchar(500)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "store_name", "Type" => "varchar(100)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "default_site", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "listing_mode", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "version", "Type" => "varchar(50)", "Null" => "NO", "Default" => $this->version , "Extra" => "");

        $this->upgradeTable($table_name, $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` ( `dev_id` varchar(255) NOT NULL, PRIMARY KEY (`dev_id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayCategoryTable() {
        $table_name = 'channel_ebay_category';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "name", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "parent_id", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "level", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");

        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` ( `id` varchar(255) NOT NULL, `site_id` int(11) NOT NULL, PRIMARY KEY (`id`, `site_id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayStoreCategoryTable() {
        $table_name = 'channel_ebay_store_category';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "name", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "parent_id", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` ( `id` varchar(255) NOT NULL, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayShippingCarrierTable() {
        $table_name = 'channel_ebay_shipping_carrier';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "code", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "description", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` ( `id` varchar(255) NOT NULL, `site_id` int(11) NOT NULL, PRIMARY KEY (`id`, `site_id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayDispatchTimeMaxTable() {
        $table_name = 'channel_ebay_dispatch_time_max';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "is_extended_handling", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "dispatch_time_max", "Type" => "int(11)", "Null" => "YES", "Default" => "", "Extra" => "");
        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` ( `description` varchar(255) NOT NULL, `site_id` int(11) NOT NULL, PRIMARY KEY (`description`,  `site_id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayShippingServiceTable() {
        $table_name = 'channel_ebay_shipping_service';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "description", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "is_international_service", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "shipping_carrier", "Type" => "varchar(255)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "shipping_package", "Type" => "varchar(255)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "is_flat", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "is_calculated", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "shipping_service", "Type" => "varchar(255)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "shipping_category", "Type" => "varchar(255)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "is_cod", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "is_dimensions_required", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "is_weight_required", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "shipping_time_max", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "shipping_time_min", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` ( `id` varchar(255) NOT NULL, `site_id` int(11) NOT NULL, PRIMARY KEY (`id`, `site_id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayShippingPackageTable() {
        $table_name = 'channel_ebay_shipping_package';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "description", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "shipping_package", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "is_default", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");

        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` ( `id` varchar(255) NOT NULL, `site_id` int(11) NOT NULL, PRIMARY KEY (`id`, `site_id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayCountryTable() {
        $table_name = 'channel_ebay_country';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "description", "Type" => "varchar(200)", "Null" => "NO", "Default" => "", "Extra" => "");

        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`country` varchar(5) NOT NULL, `site_id` int(11) NOT NULL, PRIMARY KEY (`country`, `site_id`)) DEFAULT COLLATE=utf8_general_ci;");
    }


    public function upgradeEbayShippToLocationTable() {
        $table_name = 'channel_ebay_shipptolocation';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "description", "Type" => "varchar(200)", "Null" => "NO", "Default" => "", "Extra" => "");

        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`location` varchar(100) NOT NULL, `site_id` int(11) NOT NULL, PRIMARY KEY (`location`, `site_id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayRefundTable() {
        $table_name = 'channel_ebay_refund';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "description", "Type" => "varchar(200)", "Null" => "NO", "Default" => "", "Extra" => "");

        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`option` varchar(50) NOT NULL, `site_id` int(11) NOT NULL, PRIMARY KEY (`option`, `site_id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayReturnAcceptedTable() {
        $table_name = 'channel_ebay_return_accepted';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "description", "Type" => "varchar(200)", "Null" => "NO", "Default" => "", "Extra" => "");

        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`option` varchar(50) NOT NULL, `site_id` int(11) NOT NULL, PRIMARY KEY (`option`, `site_id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayReturnWithinTable() {
        $table_name = 'channel_ebay_return_within';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "description", "Type" => "varchar(200)", "Null" => "NO", "Default" => "", "Extra" => "");

        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`option` varchar(50) NOT NULL, `site_id` int(11) NOT NULL, PRIMARY KEY (`option`, `site_id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayShippingcostPaidbyTable() {
        $table_name = 'channel_ebay_shippingcost_paidby';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "description", "Type" => "varchar(200)", "Null" => "NO", "Default" => "", "Extra" => "");

        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`option` varchar(50) NOT NULL, `site_id` int(11) NOT NULL, PRIMARY KEY (`option`, `site_id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayListProfileTable() {
        $table_name = 'channel_ebay_list_profile';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "name", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "is_default", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "ebay_category_id", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "ebay_category_path", "Type" => "varchar(1000)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "ebay_store_category_id", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "ebay_store_category_path", "Type" => "varchar(1000)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "profile_checksum", "Type" => "varchar(50)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "site_id", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "item_condition_id", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "item_condition_description", "Type" => "varchar(1000)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "private_listing", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "listing_type", "Type" => "varchar(50)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "duration", "Type" => "varchar(50)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "language_id", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "template_id", "Type" => "int(11)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "title_suffix", "Type" => "varchar(255)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "subtitle", "Type" => "varchar(60)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "currency", "Type" => "varchar(3)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "paypal_required", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "ean_enabled", "Type" => "varchar(20)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "upc_enabled", "Type" => "varchar(20)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "isbn_enabled", "Type" => "varchar(20)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "brandmpn_enabled", "Type" => "varchar(20)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "minimum_reserve_price", "Type" => "decimal(15,4)", "Null" => "YES", "Default" => "", "Extra" => "");

        $tabel_fields[] = array("Field" => "return_policy_enabled", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "handling_time_enabled", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "variations_enabled", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "attributes_enabled", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "revise_quantity_allowed", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "revise_price_allowed", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");

        $tabel_fields[] = array("Field" => "price_option", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "price_plus_minus", "Type" => "varchar(50)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "price_modify_amount", "Type" => "decimal(15,4)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "price_modify_percent", "Type" => "int(5)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "price_custom_amount", "Type" => "decimal(15,4)", "Null" => "YES", "Default" => "", "Extra" => "");


        $tabel_fields[] = array("Field" => "bin_enabled", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "bin_option", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "bin_plus_minus", "Type" => "varchar(50)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "bin_modify_amount", "Type" => "decimal(15,4)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "bin_modify_percent", "Type" => "int(5)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "bin_custom_amount", "Type" => "decimal(15,4)", "Null" => "YES", "Default" => "", "Extra" => "");

        $tabel_fields[] = array("Field" => "qty_to_sell", "Type" => "INT(10)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "max_qty_to_sell", "Type" => "INT(10)", "Null" => "NO", "Default" => "1", "Extra" => "");

        $tabel_fields[] = array("Field" => "country", "Type" => "varchar(5)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "city_state", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "zip_postcode", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "location", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");

        $tabel_fields[] = array("Field" => "paypal_email", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "payment_instructions", "Type" => "varchar(5000)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "returns_accepted", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "returns_within", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "refunds", "Type" => "varchar(50)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "shippingcost_paidby", "Type" => "varchar(50)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "return_policy_description", "Type" => "varchar(5000)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "shipping_type", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "dispatch_time", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "shipping_package", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");

        $tabel_fields[] = array("Field" => "dimension_depth", "Type" => "decimal(15,8)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "dimension_width", "Type" => "decimal(15,8)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "dimension_length", "Type" => "decimal(15,8)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "weight_major", "Type" => "decimal(15,8)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "weight_minor", "Type" => "decimal(15,8)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "package_handling_fee", "Type" => "decimal(15,8)", "Null" => "YES", "Default" => "", "Extra" => "");

        $tabel_fields[] = array("Field" => "has_international_shipping", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "is_irregular_package", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "item_specifics", "Type" => "text", "Null" => "YES", "Default" => "", "Extra" => "");

        $tabel_fields[] = array("Field" => "vin", "Type" => "varchar(50)", "Null" => "YES", "Default" => "", "Extra" => "");

        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayListPaymentMethodTable() {
        $table_name = 'channel_ebay_list_payment_method';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "list_profile_id", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "name", "Type" => "varchar(200)", "Null" => "NO", "Default" => "", "Extra" => "");
        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayListItemSpecificTable() {
        $table_name = 'channel_ebay_list_item_specific';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "list_profile_id", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "name", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "value", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");

        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayListShippingServiceTable() {
        $table_name = 'channel_ebay_list_shipping_service';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "service_type", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "list_profile_id", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "service", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "cost", "Type" => "decimal(15,4)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "each_additional", "Type" => "decimal(15,4)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "is_free_shipping", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "is_international", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "locations", "Type" => "varchar(255)", "Null" => "YES", "Default" => "", "Extra" => "");

        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayItemSpecificTable() {
        $table_name = 'channel_ebay_item_specific';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "is_required", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");

        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (
				     `id` int(11) NOT NULL AUTO_INCREMENT,
		             `site_id` int(11) NOT NULL,
		             `ebay_category_id` varchar(50) NOT NULL,
		             `name` varchar(40) NOT NULL,
		              UNIQUE KEY `channel_ebay_item_specifics_unique_key` (`ebay_category_id`, `site_id`, `name`),
		              PRIMARY KEY (`id`)
		        ) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayItemSpecificValuesTable() {
        $table_name = 'channel_ebay_item_specific_values';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "item_specific_id", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "site_id", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "ebay_category_id", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "value", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }


    public function upgradeEbayFeedbackSummaryTable() {
        $table_name = 'channel_ebay_feedback_summary';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "name", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "period_in_days", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "count", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(20) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbaySellerRatingSummaryTable() {
        $table_name = 'channel_ebay_seller_rating_summary';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "name", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "period", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "rating_detail", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "rating_count", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "rating", "Type" => " decimal(15,2)", "Null" => "NO", "Default" => "", "Extra" => "");
        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(20) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }


    public function upgradeEbaySellerDashboardTable() {
        $table_name = 'channel_ebay_seller_dashboard';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "name", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "status", "Type" => "varchar(50)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "level", "Type" => "varchar(50)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "percent", "Type" => " decimal(15,2)", "Null" => "NO", "Default" => "", "Extra" => "");
        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(20) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbaySellerDashboardAlertTable() {
        $table_name = 'channel_ebay_seller_dashboard_alert';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "seller_dashboard_id", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "alert_severity", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "alert_text", "Type" => "text", "Null" => "YES", "Default" => "", "Extra" => "");
        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(20) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbaySellingSummaryTable() {
        $table_name = 'channel_ebay_selling_summary';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "active_auction_count", "Type" => "int(11)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "amount_limit_remaining", "Type" => "decimal(15,2)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "total_auction_selling_value", "Type" => "decimal(15,2)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "total_sold_value", "Type" => "decimal(15,2)", "Null" => "YES", "Default" => "", "Extra" => "");

        $tabel_fields[] = array("Field" => "amount_limit_remaining_currency", "Type" => "varchar(5)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "total_auction_selling_value_currency", "Type" => "varchar(5)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "total_sold_value_currency", "Type" => "varchar(5)", "Null" => "YES", "Default" => "", "Extra" => "");

        $tabel_fields[] = array("Field" => "auction_bid_count", "Type" => "int(11)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "auction_selling_count", "Type" => "int(11)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "classified_ad_count", "Type" => "int(11)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "classified_ad_offer_count", "Type" => "int(11)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "quantity_limit_remaining", "Type" => "int(11)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "sold_duration_in_days", "Type" => "int(11)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "total_lead_count", "Type" => "int(11)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "total_listings_with_leads", "Type" => "int(11)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "total_sold_count", "Type" => "int(11)", "Null" => "YES", "Default" => "", "Extra" => "");
        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(20) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayProductTable() {
        $table_name = 'channel_ebay_product';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "product_id", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "", "Index"=>true);
        $tabel_fields[] = array("Field" => "ebay_id", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "", "Index"=>true);
        $tabel_fields[] = array("Field" => "site_id", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "list_profile_id", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "", "Index"=>true);
        $tabel_fields[] = array("Field" => "listing_mode", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "listing_type", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "start_time", "Type" => "timestamp", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "end_time", "Type" => "timestamp", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "price", "Type" => "decimal(15,4)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "qty", "Type" => "int(4)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "variations", "Type" => "text", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "product_checksum", "Type" => "varchar(50)", "Null" => "YES", "Default" => "", "Extra" => "");
        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(20) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayCategoryMappingTable() {
        $table_name = 'channel_ebay_category_mapping';
        $tabel_fields = array();
        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`category_id` int(20) NOT NULL, `ebay_store_category_id` varchar(20) NOT NULL, PRIMARY KEY (`category_id`, `ebay_store_category_id`)) DEFAULT COLLATE=utf8_general_ci;");
    }



    public function upgradeEbayProductVariationOptionTable() {
        $table_name = 'channel_ebay_variation_option';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "ebay_variation_id", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "name", "Type" => "varchar(255)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "value", "Type" => "varchar(255)", "Null" => "YES", "Default" => "", "Extra" => "");
        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(20) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }


    public function upgradeEbayCronTable() {
        $table_name = 'channel_ebay_cron';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "running", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "user_id", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "update_time", "Type" => "timestamp", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "start_time", "Type" => "timestamp", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "job_type", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "interval", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(20) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbaySettingTable() {
        $table_name = 'channel_ebay_setting';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "key", "Type" => "varchar(64)", "Null" => "NO", "Default" => "", "Extra" => "", "UNIQUE" => true);
        $tabel_fields[] = array("Field" => "value", "Type" => "text", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "serialized", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $this->upgradeTable($table_name, $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(20) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");

        if($this->checkIfExistsTable('channel_ebay_settings')) {
            $oldSettings = $this->db->query('select * from '.DB_PREFIX.'channel_ebay_settings limit 1')->row;
            if(!empty($oldSettings)) {
                $newSettings = array(
                    "truncate_title_enabled" => $oldSettings['truncate_title_enabled'],
                    "orders_update_enabled" => $oldSettings['orders_update_enabled'],
                    "syncronize_enabled" => $oldSettings['syncronize_enabled'],
                    "stock_and_price_interval_enabled" => $oldSettings['stock_and_price_interval_enabled'],
                    "stock_and_price_interval" => $oldSettings['stock_and_price_interval'],
                    "syncronize_interval" => $oldSettings['syncronize_interval'],
                    "orders_update_interval" => $oldSettings['orders_update_interval'],
                    "image_type" => $oldSettings['image_type'],
                    "import_items_enabled" => $oldSettings['import_items_enabled'],
                    "import_items_interval" => $oldSettings['import_items_interval'],
                    "end_item_on_delete_enabled" => $oldSettings['end_item_on_delete_enabled'],
                    "end_item_out_stock_enabled" => $oldSettings['end_item_out_stock_enabled'],
                    "revise_title_enabled" => $oldSettings['revise_title_enabled'],
                    "revise_description_enabled" => $oldSettings['revise_description_enabled'],
                    "revise_sku_enabled" => $oldSettings['revise_sku_enabled'],
                    "revise_paypalemail_enabled" => $oldSettings['revise_paypalemail_enabled'],
                    "revise_dispatch_time_max_enabled" => $oldSettings['revise_dispatch_time_max_enabled'],
                    "revise_listing_duration_enabled" => $oldSettings['revise_listing_duration_enabled'],
                    "revise_listing_type_enabled" => $oldSettings['revise_listing_type_enabled'],
                    "revise_postal_code_enabled" => $oldSettings['revise_postal_code_enabled'],
                    "revise_primary_category_enabled" => $oldSettings['revise_primary_category_enabled'],
                    "revise_payment_methods_enabled" => $oldSettings['revise_payment_methods_enabled'],
                    "revise_picture_details_enabled" => $oldSettings['revise_picture_details_enabled'],
                    "revise_condition_enabled" => $oldSettings['revise_condition_enabled'],
                    "revise_return_policy_enabled" => $oldSettings['revise_return_policy_enabled'],
                    "revise_shipping_details_enabled" => $oldSettings['revise_shipping_details_enabled'],
                    "revise_item_specifics_enabled" => $oldSettings['revise_item_specifics_enabled'],
                    "import_new_products" => $oldSettings['import_new_products'],
                    "update_only_stock_and_price" => $oldSettings['update_only_stock_and_price'],
                    "disable_ended_items" => $oldSettings['disable_ended_items'],
                    "revise_description_enabled" => $oldSettings['revise_description_enabled']
                );

                foreach($newSettings as $key=>$value) {
                    $this->db->query(
                        "INSERT IGNORE INTO " . DB_PREFIX . $table_name . "
                	      SET `key` = '" . $this->db->escape($key)
                        . "', `value` = '" . $this->db->escape($value) . "'");
                }
            }
            $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_settings`");
        }
    }

    public function upgradeEbayLogTable() {
        $table_name = 'channel_ebay_log';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "type", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "ebay_product_id", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "create_time", "Type" => "timestamp", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "message", "Type" => "text", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "severity_code", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "code", "Type" => "int(11)", "Null" => "YES", "Default" => "", "Extra" => "");

        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(20) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeApiAccessRuleTable() {
        $table_name = 'channel_ebay_api_access_rule';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "call_name", "Type" => "varchar(100)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "daily_hard_limit", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "daily_soft_limit", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "daily_usage", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "mod_time", "Type" => "timestamp", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "period", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "rule_current_status", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "counts_toward_aggregate", "Type" => "varchar(7)", "Null" => "NO", "Default" => "", "Extra" => "");

        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(20) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }





    public function upgradeEbayTemplatesTable() {
        $table_name = 'channel_ebay_templates';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "name", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "html", "Type" => "MEDIUMTEXT", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "template_id", "Type" => "varchar(100)", "Null" => "YES", "Default" => "", "Extra" => "");
        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(20) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }



    public function upgradeEbayProcessTable() {
        $table_name = 'channel_ebay_process';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "name", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "total", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "processed", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "start_time", "Type" => "timestamp", "Null" => "NO", "Default" => "", "Extra" => "");
        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(20) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayFeedback() {
        $table_name = 'channel_ebay_feedback';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "feedback_id", "Type" => "varchar(255)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "user", "Type" => "varchar(100)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "role", "Type" => "varchar(60)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "score", "Type" => "int(11)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "comment_text", "Type" => "varchar(255)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "comment_time", "Type" => "timestamp", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "comment_type", "Type" => "varchar(60)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "item_id", "Type" => "varchar(60)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "item_price", "Type" => "decimal(15,4)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "item_price_currency", "Type" => "varchar(10)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "item_title", "Type" => "varchar(255)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "order_line_item_id", "Type" => "varchar(255)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "transaction_id", "Type" => "varchar(255)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "order_id", "Type" => "int(11)", "Null" => "YES", "Default" => "", "Extra" => "");

        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(20) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeShippingProfile() {
        $table_name = 'channel_ebay_shipping_profile';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "site_id", "Type" => "int(11)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "name", "Type" => "varchar(255)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "is_default", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "added_date", "Type" => "timestamp", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "shipping_terms_in_description", "Type" => "TINYINT(1)", "Null" => "NO", "Default" => "0", "Extra" => "");
        $tabel_fields[] = array("Field" => "description", "Type" => "varchar(600)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "country", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "postal_code", "Type" => "varchar(50)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "location", "Type" => "varchar(50)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "dispatch_time_max", "Type" => "int(11)", "Null" => "NO", "Default" => "1", "Extra" => "");
        $tabel_fields[] = array("Field" => "service_type", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "package_type", "Type" => "varchar(50)", "Null" => "NO", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "dimension_depth", "Type" => "decimal(15,8)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "dimension_width", "Type" => "decimal(15,8)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "dimension_length", "Type" => "decimal(15,8)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "weight_major", "Type" => "decimal(15,8)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "weight_minor", "Type" => "decimal(15,8)", "Null" => "YES", "Default" => "", "Extra" => "");
        $tabel_fields[] = array("Field" => "package_handling_fee", "Type" => "decimal(15,8)", "Null" => "YES", "Default" => "", "Extra" => "");

        $tabel_fields[] = array("Field" => "service_options", "Type" => "TEXT", "Null" => "NO", "Default" => "", "Extra" => "");
        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` (`id` int(20) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;");
    }

    public function upgradeEbayDetailsTable() {
        $table_name = 'channel_ebay_details';
        $tabel_fields = array();
        $tabel_fields[] = array("Field" => "value", "Type" => "text", "Null" => "NO", "Default" => "", "Extra" => "");

        $this->upgradeTable($table_name,
            $tabel_fields,
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table_name."` ( `name` varchar(255) NOT NULL, `site_id` int(11) NOT NULL, PRIMARY KEY (`name`, `site_id`)) DEFAULT COLLATE=utf8_general_ci;");
    }


    public function install(){
        $this->upgradeEbayAccountTable();
    }


    public function updateOrInstall(){
        $this->upgradeEbayAccountTable();
        $this->upgradeEbayCategoryTable();
        $this->upgradeEbayStoreCategoryTable();
        $this->upgradeEbayShippingCarrierTable();
        $this->upgradeEbayDispatchTimeMaxTable();
        $this->upgradeEbayShippingServiceTable();
        $this->upgradeEbayShippingPackageTable();
        $this->upgradeEbayCountryTable();
        $this->upgradeEbayShippToLocationTable();
        $this->upgradeEbayRefundTable();
        $this->upgradeEbayReturnAcceptedTable();
        $this->upgradeEbayReturnWithinTable();
        $this->upgradeEbayShippingcostPaidbyTable();
        $this->upgradeEbayListProfileTable();
        $this->upgradeEbayListPaymentMethodTable();
        $this->upgradeEbayListItemSpecificTable();
        $this->upgradeEbayListShippingServiceTable();
        $this->upgradeEbayItemSpecificTable();
        $this->upgradeEbayItemSpecificValuesTable();
        $this->upgradeEbayFeedbackSummaryTable();
        $this->upgradeEbaySellerRatingSummaryTable();
        $this->upgradeEbaySellerDashboardTable();
        $this->upgradeEbaySellerDashboardAlertTable();
        $this->upgradeEbaySellingSummaryTable();
        $this->upgradeEbayProductTable();
        $this->upgradeEbayProductVariationOptionTable();
        $this->upgradeEbayCategoryMappingTable();
        $this->upgradeEbayCronTable();
        $this->upgradeEbaySettingTable();
        $this->upgradeEbayLogTable();
        $this->upgradeEbayTemplatesTable();
        $this->upgradeEbayProcessTable();
        $this->upgradeApiAccessRuleTable();
        $this->upgradeEbayFeedback();
        $this->upgradeShippingProfile();
        $this->upgradeEbayDetailsTable();

        $this->upgradeColumns('order', array(
            "ebay_order_id" => "ALTER TABLE `" . DB_PREFIX . "order` ADD ebay_order_id varchar(80)",
            "ebay_buyer_user_id" => "ALTER TABLE `" . DB_PREFIX . "order` ADD ebay_buyer_user_id varchar(200)",
            "ebay_order_status" => "ALTER TABLE `" . DB_PREFIX . "order` ADD ebay_order_status varchar(200)",
            "ebay_paid_time" => "ALTER TABLE `" . DB_PREFIX . "order` ADD ebay_paid_time timestamp DEFAULT 0",
            "ebay_shipped_time" => "ALTER TABLE `" . DB_PREFIX . "order` ADD ebay_shipped_time timestamp",
            "ebay_buyer_checkout_message" => "ALTER TABLE `" . DB_PREFIX . "order` ADD ebay_buyer_checkout_message text",
            "ebay_address_owner" => "ALTER TABLE `" . DB_PREFIX . "order` ADD ebay_address_owner varchar(200)",
            "ebay_city_name" => "ALTER TABLE `" . DB_PREFIX . "order` ADD ebay_city_name varchar(200)",
            "ebay_country" => "ALTER TABLE `" . DB_PREFIX . "order` ADD ebay_country varchar(200)",
            "ebay_state_or_province" => "ALTER TABLE `" . DB_PREFIX . "order` ADD ebay_state_or_province varchar(200)",
            "ebay_payment_method" => "ALTER TABLE `" . DB_PREFIX . "order` ADD ebay_payment_method varchar(200)",
            "ebay_shipping_service" => "ALTER TABLE `" . DB_PREFIX . "order` ADD ebay_shipping_service varchar(200)",
            "ebay_amount_paid" => "ALTER TABLE `" . DB_PREFIX . "order` ADD ebay_amount_paid decimal(15,4)"
        ));


        $this->upgradeColumns('order_product', array(
            "ebay_transaction_id" => "ALTER TABLE `" . DB_PREFIX . "order_product` ADD ebay_transaction_id varchar(100)",
            "ebay_order_line_item_id" => "ALTER TABLE `" . DB_PREFIX . "order_product` ADD ebay_order_line_item_id varchar(100)",
            "ebay_shipment_tracking_number" => "ALTER TABLE `" . DB_PREFIX . "order_product` ADD ebay_shipment_tracking_number varchar(300)",
            "ebay_paid_time" => "ALTER TABLE `" . DB_PREFIX . "order_product` ADD ebay_paid_time timestamp DEFAULT 0",
        ));

        $this->upgradeColumns('category', array(
            "ebay_id" => "ALTER TABLE `" . DB_PREFIX . "category` ADD ebay_id varchar(80)",
            "ebay_store_id" => "ALTER TABLE `" . DB_PREFIX . "category` ADD ebay_store_id varchar(80)",
            "ebay_parent_id" => "ALTER TABLE `" . DB_PREFIX . "category` ADD ebay_parent_id varchar(80)"
        ));


        $this->load->model('user/user_group');

        $this->model_user_user_group->addPermission($this->user->getId(), 'access', 'ebay_channel/cron');
        $this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'ebay_channel/cron');

        $this->load->model('ebay_channel/cron_dao');
        $this->model_ebay_channel_cron_dao->sendInstall();

        $this->installModifications();


    }

    private function installModifications() {
        $xml = file_get_contents(DIR_APPLICATION . 'model/ebay_channel/vq_ebay_channel'. $this->vqmod .  '.xml');
        if ($xml) {
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->loadXml($xml);

            $name = $dom->getElementsByTagName('name')->item(0);

            if ($name) {
                $name = $name->nodeValue;
            } else {
                $name = '';
            }

            $code = $dom->getElementsByTagName('code')->item(0);

            if ($code) {
                $code = $code->nodeValue;

                $author = $dom->getElementsByTagName('author')->item(0);

                if ($author) {
                    $author = $author->nodeValue;
                } else {
                    $author = '';
                }

                $version = $dom->getElementsByTagName('version')->item(0);

                if ($version) {
                    $version = $version->nodeValue;
                } else {
                    $version = '';
                }

                $link = $dom->getElementsByTagName('link')->item(0);

                if ($link) {
                    $link = $link->nodeValue;
                } else {
                    $link = '';
                }

                $modification_data = array(
                    'name'    => $name,
                    'code'    => $code,
                    'author'  => $author,
                    'version' => $version,
                    'link'    => $link,
                    'xml'     => $xml,
                    'status'  => 1
                );
                $this->db->query("DELETE FROM " . DB_PREFIX . "modification WHERE code = '" . $code . "'");
                $this->db->query("INSERT INTO " . DB_PREFIX . "modification SET code = '" . $this->db->escape($modification_data['code']) . "', name = '" . $this->db->escape($modification_data['name']) . "', author = '" . $this->db->escape($modification_data['author']) . "', version = '" . $this->db->escape($modification_data['version']) . "', link = '" . $this->db->escape($modification_data['link']) . "', xml = '" . $this->db->escape($modification_data['xml']) . "', status = '" . (int)$modification_data['status'] . "', date_added = NOW()");
            }
        }
    }

    public function upgradeColumns($table_name, $sqlArray) {
        $fields = $this->db->query("describe `" . DB_PREFIX . $table_name."`")->rows;
        foreach ($sqlArray as $fieldName=>$sql) {
            $dbField = null;
            foreach ($fields as $field) {
                if($field['Field'] == $fieldName) {
                    $dbField = $field;
                }
            }
            if(empty($dbField)) {
                $this->db->query($sql);
            }
        }
    }


    private function checkIfExistsTable($table_name) {
        $table = $this->db->query("SHOW TABLES LIKE  '" . DB_PREFIX . $table_name."'")->row;
        return !empty($table);
    }

    private  function upgradeTable($table_name, $tabel_fields, $createSql) {
        if(!$this->checkIfExistsTable($table_name)) {
            $this->db->query($createSql);
        }

        $fields = $this->db->query("describe `" . DB_PREFIX . $table_name."`")->rows;
        foreach ($tabel_fields as $tabel_field) {
            $dbField = null;
            foreach ($fields as $field) {
                if($field['Field'] == $tabel_field['Field']) {
                    $dbField = $field;
                }
            }
            if(empty($dbField)) {
                $this->db->query("ALTER TABLE `" . DB_PREFIX . $table_name . "` ADD `" . $tabel_field['Field'] . "` " . $tabel_field['Type']
                    . (($tabel_field['Null'] == 'NO')? " NOT NULL " : "") . " "
                    . ((!empty($tabel_field['Default']))? " DEFAULT '" . $tabel_field['Default'] ."' " : " "));
                if(isset($tabel_field['UNIQUE']) && isset($tabel_field['UNIQUE'])) {
                    $this->db->query("ALTER TABLE `" . DB_PREFIX . $table_name . "` ADD UNIQUE(`" . $tabel_field['Field'] . "`)");
                }
            }
        }

        $indexes = $this->db->query("SHOW INDEX FROM `" . DB_PREFIX . $table_name."`")->rows;

        foreach ($tabel_fields as $tabel_field) {
            if(isset($tabel_field['Index']) && $tabel_field['Index']) {
                $exists = false;
                foreach($indexes as $index) {
                    if($tabel_field['Field'] == $index['Column_name']) {
                        $exists = true;
                    }
                }

                if(!$exists){
                    $this->db->query("ALTER TABLE `" . DB_PREFIX . $table_name."` ADD INDEX (`". $tabel_field['Field'] ."`)");
                }

            }
        }


    }


    public function uninstall(){
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_acount`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_category`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_shipping_carrier`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_shipping_service`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_shipping_package`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_country`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_shipptolocation`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_dispatch_time_max`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_refund`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_return_accepted`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_return_within`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_shippingcost_paidby`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_list_profile`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_list_payment_method`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_list_item_specific`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_list_shipping_service`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_item_specific`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_item_specific_values`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_feedback_summary`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_seller_rating_summary`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_seller_dashboard`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_seller_dashboard_alert`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_selling_summary`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_product`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_cron`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_log`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_templates`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_store_category`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_process`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_category_mapping`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "channel_ebay_details`");


        try {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order` DROP ebay_order_id");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order` DROP ebay_buyer_user_id");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order` DROP ebay_order_status");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order` DROP ebay_paid_time");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order` DROP ebay_shipped_time");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order` DROP ebay_buyer_checkout_message");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order` DROP ebay_address_owner");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order` DROP ebay_city_name");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order` DROP ebay_country");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order` DROP ebay_state_or_province");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order` DROP ebay_payment_method");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order` DROP ebay_shipping_service");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order` DROP ebay_amount_paid");

            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order_product` DROP ebay_transaction_id");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order_product` DROP ebay_order_line_item_id");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order_product` DROP ebay_shipment_tracking_number");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order_product` DROP ebay_paid_time");

        } catch(Exception $e) {

        }



        $path_parts = pathinfo(DIR_APPLICATION);
        if (file_exists($path_parts['dirname'] . '/vqmod/xml/vq_ebay_channel'. $this->vqmod .  '.xml')) {
            unlink($path_parts['dirname'] . '/vqmod/xml/vq_ebay_channel'. $this->vqmod .  '.xml');
        }

    }
}
?>