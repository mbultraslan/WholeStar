<?php
class ModelEbayChannelProductToEbayDao extends Model {

    public function addProductToEbay($productId, $price, $qty, $ebayItemId, $siteId, $list_profile_id, $listing_mode, $listing_type, $startTime, $endTime, $checksum, $variations= array()) {
        if(!empty($productId)) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_product` where site_id='" . $this->db->escape($siteId) . "' and  product_id=". $this->db->escape($productId));
            $this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_product` SET "
                . "   `product_id` = '" . $this->db->escape($productId)
                . "', `ebay_id` = '" . $this->db->escape($ebayItemId)
                . "', `site_id` = '" . $this->db->escape($siteId)
                . "', `list_profile_id` = '" . $this->db->escape($list_profile_id)
                . "', `listing_mode` = '" . $this->db->escape($listing_mode)
                . "', `listing_type` = '" . $this->db->escape($listing_type)
                . "', `start_time` = '" . $this->db->escape($startTime)
                . "', `end_time` = '" . $this->db->escape($endTime)
                . "',  variations='" . $this->db->escape(json_encode($variations))
                . "', `product_checksum` = '" . $this->db->escape($checksum)
                . "', `price` = '" . $this->db->escape($price)
                . "', `qty` = '" . $this->db->escape($qty)
                . "'"
            );
        }
    }

    public function removeByProductId($productId) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_product` where product_id=". $this->db->escape($productId));
    }

    public function clear() {
        $this->db->query("DELETE ep from " . DB_PREFIX . "channel_ebay_product ep
							LEFT JOIN " . DB_PREFIX . "product p on p.product_id = ep.product_id
							WHERE p.product_id is null");
    }

    public function updateEndTime($productId, $endTime, $checksum) {
        $this->db->query("UPDATE  `" . DB_PREFIX . "channel_ebay_product` SET `end_time`='" . $this->db->escape($endTime) . "', `product_checksum`='" . $this->db->escape($checksum) . "'  where product_id=". $this->db->escape($productId));
    }

    public function update($productId, $endTime, $startTime, $price = false, $qty = false, $variations = array()) {

        if($price && $qty) {
            $this->db->query("UPDATE  `" . DB_PREFIX . "channel_ebay_product` SET `end_time`='" . $this->db->escape($endTime)
                . "',  start_time='" . $this->db->escape($startTime)
                . "',  variations='" . $this->db->escape(json_encode($variations))
                . "',  price='" . $this->db->escape($price)
                . "',  qty = '" . $this->db->escape($qty)
                . "'  where product_id=". $this->db->escape($productId));
        } else if($qty) {
            $this->db->query("UPDATE  `" . DB_PREFIX . "channel_ebay_product` SET `end_time`='" . $this->db->escape($endTime)
                . "',  start_time='" . $this->db->escape($startTime)
                . "',  variations='" . $this->db->escape(json_encode($variations))
                . "',  qty = '" . $this->db->escape($qty)
                . "'  where product_id=". $this->db->escape($productId));
        } else if($price) {
            $this->db->query("UPDATE  `" . DB_PREFIX . "channel_ebay_product` SET `end_time`='" . $this->db->escape($endTime)
                . "',  start_time='" . $this->db->escape($startTime)
                . "',  variations='" . $this->db->escape(json_encode($variations))
                . "',  price = '" . $this->db->escape($price)
                . "'  where product_id=". $this->db->escape($productId));
        } else {
            $this->db->query("UPDATE  `" . DB_PREFIX . "channel_ebay_product` SET `end_time`='" . $this->db->escape($endTime)
                . "',  start_time='" . $this->db->escape($startTime)
                . "',  variations='" . $this->db->escape(json_encode($variations))
                . "'  where product_id=". $this->db->escape($productId));
        }

    }

    public function updateInventory($productId, $price, $qty) {
        $this->db->query("UPDATE  `" . DB_PREFIX . "channel_ebay_product` SET `price`='" . $this->db->escape($price) . "', qty='". $this->db->escape($qty) ."'  where product_id=". $this->db->escape($productId));
    }

    public function getProductToEbay($productId) {
        return $this->db->query("SELECT * FROM `" . DB_PREFIX . "channel_ebay_product` where product_id=". $this->db->escape($productId))->row;
    }

    public function getProductToEbayByItemId($itemId) {
        return $this->db->query("SELECT * FROM `" . DB_PREFIX . "channel_ebay_product` where ebay_id=". $this->db->escape($itemId))->row;
    }

    public function getProductNamesByEbayItemId($itemIds) {
        return $this->db->query("SELECT pd.name, pd.product_id, pte.ebay_id FROM " . DB_PREFIX . "channel_ebay_product pte, " . DB_PREFIX . "product_description pd
				 where pd.language_id='" . $this->db->escape($this->config->get('config_language_id')) . "' and pd.product_id = pte.product_id and pte.ebay_id in ('". implode("','", $itemIds) . "')")->rows;
    }

    public function getProductsByEbayItemIds($itemIds) {

        $sQuery = "SELECT p.*, pd.name, ep.ebay_id FROM " . DB_PREFIX . "channel_ebay_product ep"
                  . " LEFT JOIN " .DB_PREFIX . "product p on p.product_id = ep.product_id "
                  . " LEFT JOIN " .DB_PREFIX . "product_description pd on pd.product_id = p.product_id"
                  . " WHERE ep.ebay_id in ('" . implode("','", $itemIds) . "')"
                  . " AND pd.language_id=" . (int) $this->config->get('config_language_id');


        $result = $this->db->query($sQuery)->rows;
        $sPIds = array();
        $oPIds = array();
        foreach ($result as $k=>$row) {
            if(isset($row['has_option']) && $row['has_option'] > 0) {
                $oPIds[] = $row['product_id'];
            } else {
                $sPIds[] = $row['product_id'];
            }

            $result[$k]['iQty'] = $row['quantity'];
            $result[$k]['iVariations'] = 0;
        }

// 		if(!empty($sPIds)) {
// 			$options = $this->db->query("SELECT product_id, count(*) as cnt, SUM(`quantity`) as qty FROM `" . DB_PREFIX . "product_option_value` WHERE `product_id` in (" . implode(',', $sPIds) . ") group by product_id")->rows;
// 			if(!empty($options)) {
// 				foreach ($result as $k=>$row) {
// 					foreach ($options as $option) {
// 						if($option['product_id'] == $row['product_id']) {
// 							$result[$k]['quantity'] = '<span style="color: #000000;">' . $option['cnt'] . ' options</span>' . '<br>' . '<span style="color: #000000;">' . $option['qty'] . ' stock</span>';
// 							$result[$k]['iQty'] = $option['qty'];
// 							$result[$k]['iVariations'] = $option['cnt'];
// 						}
// 					}
// 				}
// 			}
// 		}


        if(!empty($oPIds)) {
            $exists_product_option_relation = $this->openstockExistsOptionRelationTable();
            if($exists_product_option_relation) {
                $options = $this->db->query("SELECT product_id, count(*) as cnt, SUM(stock) as qty FROM `" . DB_PREFIX . "product_option_relation` WHERE `product_id` in (" . implode(',', $oPIds) . ") group by product_id")->rows;
            } else {
                $options = $this->db->query("SELECT product_id, count(*) as cnt, SUM(stock) as qty FROM `" . DB_PREFIX . "product_option_variant` WHERE `product_id` in (" . implode(',', $oPIds) . ") group by product_id")->rows;
            }


            if(!empty($options)) {
                foreach ($result as $k=>$row) {
                    foreach ($options as $option) {
                        if($option['product_id'] == $row['product_id']) {
                            $result[$k]['quantity'] = '<span style="color: #000000;">' . $option['cnt'] . ' variations</span>' . '<br>' . '<span style="color: #000000;">' . $option['qty'] . ' stock</span>';
                            $result[$k]['iQty'] = $option['qty'];
                            $result[$k]['iVariations'] = $option['cnt'];
                        }
                    }
                }
            }
        }




        return $result;


    }

    private function openstockExistsOptionRelationTable() {
        $product_option_relation = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "product_option_relation'")->rows;
        return count($product_option_relation) > 0;
    }

    public function getEbayProductsCount() {
        return $this->db->query("SELECT COUNT(*) as c FROM " . DB_PREFIX . "channel_ebay_product ")->row['c'];
    }

    public function getEbayProducts($offset=0, $limit=10) {
        return $this->db->query("SELECT * FROM " . DB_PREFIX . "channel_ebay_product order by id desc LIMIT " . $offset . ", " . $limit)->rows;
    }

    public function getModifiedStockAndPrice() {

        $dtz = new DateTimeZone('GMT-0');
        $ebayNowTime = new DateTime('now', $dtz);

        $ebayTime = $ebayNowTime->format('Y-m-d H:i:s');

        $mods = array();

        $count = $this->db->query("SELECT COUNT(*) as c FROM " .DB_PREFIX . "product p, `" . DB_PREFIX . "channel_ebay_product` cep where cep.product_id = p.product_id AND end_time > '" . $this->db->escape($ebayTime) . "'")->row['c'];

        $itemsPerPage = 1000;
        $totalPage = ($count + $itemsPerPage - 1) / $itemsPerPage;

        for ($page = 1; $page <= $totalPage; $page++) {

            $offset = ($page - 1) * $itemsPerPage;
            $rows = $this->db->query("SELECT p.*, cep.qty as ebay_qty, cep.price as ebay_price, cep.id as ep_id, cep.ebay_id, cep.list_profile_id FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "channel_ebay_product` cep where cep.product_id = p.product_id AND end_time > '" . $this->db->escape($ebayTime) . "' LIMIT " . $offset . ", " . $itemsPerPage)->rows;
            $sPIds = array();
            $oPIds = array();
            foreach ($rows as $row) {
                if(isset($row['has_option']) && $row['has_option'] > 0) {
                    $oPIds[] = $row['product_id'];
                } else {
                    $sPIds[] = $row['product_id'];
                }
            }

            if(!empty($sPIds)) {
                $options = $this->db->query("SELECT product_id, count(*) as cnt, SUM(`quantity`) as qty FROM `" . DB_PREFIX . "product_option_value` WHERE quantity > -1 and `product_id` in (" . implode(',', $sPIds) . ") group by product_id")->rows;
                if(!empty($options)) {
                    foreach ($rows as $row) {
                        foreach ($options as $option) {
                            if($option['product_id'] == $row['product_id'] && ($row['price'] != $row['ebay_price'] || $option['qty'] != $row['ebay_qty'])) {
                                $mods[] = array(
                                    'id' => $row['ep_id'],
                                    'product_id' => $row['product_id'],
                                    'price' => $row['price'],
                                    'quantity' => $row['ebay_qty'],
                                    'ebay_id' => $row['ebay_id'],
                                    'list_profile_id' => $row['list_profile_id']
                                );
                            }
                        }
                    }
                }

                foreach ($rows as $row) {
                    $i = 0;
                    if(!empty($options)) {
                        foreach ($options as $option) {
                            if($option['product_id'] == $row['product_id']) {
                                $i = 1;
                            }
                        }
                    }

                    if(empty($i)) {
                        if($row['price'] != $row['ebay_price'] || $row['quantity'] != $row['ebay_qty']) {
                            $mods[] = array(
                                'id' => $row['ep_id'],
                                'product_id' => $row['product_id'],
                                'price' => $row['price'],
                                'quantity' => $row['ebay_qty'],
                                'ebay_id' => $row['ebay_id'],
                                'list_profile_id' => $row['list_profile_id']
                            );
                        }
                    }
                }

            }


            if(!empty($oPIds)) {
                $exists_product_option_relation = $this->openstockExistsOptionRelationTable();
                if($exists_product_option_relation) {
                    $options = $this->db->query("SELECT product_id, count(*) as cnt, SUM(stock) as qty FROM `" . DB_PREFIX . "product_option_relation` WHERE stock > -1 and  `product_id` in (" . implode(',', $oPIds) . ") group by product_id")->rows;
                } else {
                    $options = $this->db->query("SELECT product_id, count(*) as cnt, SUM(stock) as qty FROM `" . DB_PREFIX . "product_option_variant` WHERE stock > -1 and  `product_id` in (" . implode(',', $oPIds) . ") group by product_id")->rows;
                }


                if(!empty($options)) {
                    foreach ($rows as $row) {
                        foreach ($options as $option) {
                            if($option['product_id'] == $row['product_id'] && ($row['price'] != $row['ebay_price'] || $option['qty'] != $row['ebay_qty'])) {
                                $mods[] = array(
                                    'id' => $row['ep_id'],
                                    'product_id' => $row['product_id'],
                                    'price' => $row['price'],
                                    'quantity' => $row['quantity'],
                                    'ebay_id' => $row['ebay_id'],
                                    'list_profile_id' => $row['list_profile_id']
                                );
                            }
                        }
                    }
                }
            }





        }
        return $mods;
    }


    public function getModifiedStockAndPriceByProduct($productId) {

        $dtz = new DateTimeZone('GMT-0');
        $ebayNowTime = new DateTime('now', $dtz);

        $ebayTime = $ebayNowTime->format('Y-m-d H:i:s');

        $mods = array();

        $rows = $this->db->query("SELECT p.*, cep.qty as ebay_qty, cep.price as ebay_price, cep.id as ep_id, cep.ebay_id, cep.list_profile_id FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "channel_ebay_product` cep where p.product_id='". (int) $productId . "' and cep.product_id = p.product_id AND end_time > '" . $this->db->escape($ebayTime) . "' ")->rows;
        $sPIds = array();
        $oPIds = array();


        foreach ($rows as $row) {
            if(isset($row['has_option']) && $row['has_option'] > 0) {
                $oPIds[] = $row['product_id'];
            } else {
                $sPIds[] = $row['product_id'];
            }
        }

        if(!empty($sPIds)) {
            $options = $this->db->query("SELECT product_id, count(*) as cnt, SUM(`quantity`) as qty FROM `" . DB_PREFIX . "product_option_value` WHERE quantity > 0 and `product_id` in (" . implode(',', $sPIds) . ") group by product_id")->rows;
            if(!empty($options)) {
                foreach ($rows as $row) {
                    foreach ($options as $option) {
                        if($option['product_id'] == $row['product_id'] && ($row['price'] != $row['ebay_price'] || $option['qty'] != $row['ebay_qty'])) {
                            $mods[] = array(
                                'id' => $row['ep_id'],
                                'product_id' => $row['product_id'],
                                'price' => $row['price'],
                                'quantity' => $option['qty'],
                                'ebay_id' => $row['ebay_id'],
                                'list_profile_id' => $row['list_profile_id']
                            );
                        }
                    }
                }
            }

            foreach ($rows as $row) {
                $i = 0;
                if(!empty($options)) {
                    foreach ($options as $option) {
                        if($option['product_id'] == $row['product_id']) {
                            $i = 1;
                        }
                    }
                }

                if(empty($i)) {
                    if($row['price'] != $row['ebay_price'] || $row['quantity'] != $row['ebay_qty']) {
                        $mods[] = array(
                            'id' => $row['ep_id'],
                            'product_id' => $row['product_id'],
                            'price' => $row['price'],
                            'quantity' => $row['quantity'],
                            'ebay_id' => $row['ebay_id'],
                            'list_profile_id' => $row['list_profile_id']
                        );
                    }
                }
            }
        }


        if(!empty($oPIds)) {
            $exists_product_option_relation = $this->openstockExistsOptionRelationTable();
            if($exists_product_option_relation) {
                $options = $this->db->query("SELECT product_id, count(*) as cnt, SUM(stock) as qty FROM `" . DB_PREFIX . "product_option_relation` WHERE stock > 0 and `product_id` in (" . implode(',', $oPIds) . ") group by product_id")->rows;
            } else {
                $options = $this->db->query("SELECT product_id, count(*) as cnt, SUM(stock) as qty FROM `" . DB_PREFIX . "product_option_variant` WHERE stock > -1 and  `product_id` in (" . implode(',', $oPIds) . ") group by product_id")->rows;
            }

            if(!empty($options)) {
                foreach ($rows as $row) {
                    foreach ($options as $option) {
                        if($option['product_id'] == $row['product_id'] && ($row['price'] != $row['ebay_price'] || $option['qty'] != $row['ebay_qty'])) {
                            $mods[] = array(
                                'id' => $row['ep_id'],
                                'product_id' => $row['product_id'],
                                'price' => $row['price'],
                                'quantity' => $option['qty'],
                                'ebay_id' => $row['ebay_id'],
                                'list_profile_id' => $row['list_profile_id']
                            );
                        }
                    }
                }
            }
        }
        if(!empty($mods)) {
            return $mods[0];
        }
        return $mods;
    }


    public function getEbayProductsByProfileId($profileId, $offset=0, $limit=10) {
        return $this->db->query("SELECT * FROM `" . DB_PREFIX . "channel_ebay_product` where list_profile_id='" . $this->db->escape($profileId) ."' order by id desc LIMIT " . $offset . ", " . $limit)->rows;
    }

    public function getEbayProductsByProfileIdCount($profileId) {
        return $this->db->query("SELECT count(*) as cnt FROM `" . DB_PREFIX . "channel_ebay_product` where list_profile_id='" . $this->db->escape($profileId) ."' ")->row['cnt'];
    }

    public function endEbayItem($productId, $endreason = 2, $account) {
        $this->load->model('ebay_channel/api');
        $pte = $this->getProductToEbay($productId);
        $r = array('response_status' => false, 'type' => 'EndItem');
        if(!empty($pte)) {
            if($endreason == 5) {
                $this->removeByProductId($productId);
                $r['response_status'] = true;
            } else {
                $r = $this->model_ebay_channel_api->getEndItemCall($account, $account['default_site'])->endItem($pte['ebay_id'], $endreason);
                if(isset($r['end_time'])) {
                    $this->updateEndTime($productId, $r['end_time'], '');
                    $r['response_status'] = true;
                } elseif(isset($r['errors'])) {
                    $r['response_status'] = false;
                    foreach ($r['errors'] as $error) {
                        if($error['code'] == '1047') {
                            //syncronize item
                            $r = $this->syncronizeItem($productId, $pte['ebay_id'], $account);
                            if(isset($r['item'])) {
                                $r['end_time'] = $r['item']['EndTime'];
                            }
                        }
                    }
                }
            }
        }

        return $r;
    }

    public function listEbayItem($productId, $account, $profile, $settings) {
        $this->load->model('ebay_channel/api');
        $this->load->model('ebay_channel/dao');
        $this->load->model('ebay_channel/product_dao');
        $this->load->model('ebay_channel/date_util');

        $r = array();

        $this->load->model('ebay_channel/ebay_details_dao');
        $profile['ebay_details'] = $this->model_ebay_channel_ebay_details_dao->getDetails(array('ProductDetails'), $profile['list_profile']['site_id']);

        $product = $this->model_ebay_channel_product_dao->getProductRecord($productId, $profile['list_profile']['language_id'], $profile['list_profile']);
        $price =  $product['product']['price'];
        $basePrice = $product['product']['base_price'];
        $baseQty = $product['product']['base_quantity'];
        $qty = $product['product']['quantity'];

        if($profile['list_profile']['listing_type'] == 'Chinese') {
            $r = $this->model_ebay_channel_api->getAddItemCall($account, $profile['list_profile']['site_id'])->addItem($product, $profile, $settings);
            $r['type'] = 'AddItem';
        } else {
            $r = $this->model_ebay_channel_api->getAddFixedPriceItemCall($account, $profile['list_profile']['site_id'])->addFixedPriceItem($product, $profile, $settings);
            $r['type'] = 'AddFixedPriceItem';
        }

        if(isset($r['item_id']) && !empty($r['item_id'])) {
            $variations = isset($product['variations']['variations'])? $product['variations']['variations'] : array();
            $this->addProductToEbay($productId, $basePrice, $baseQty, $r['item_id'],
                $profile['list_profile']['site_id'],
                $profile['list_profile']['id'],
                $account['listing_mode'],
                $profile['list_profile']['listing_type'],
                $r['start_time'], $r['end_time'], '', $variations);

            if(!empty($variations)) {
                $this->syncronizeItem($productId, $r['item_id'], $account);
            }
            $r['response_status'] = true;
        } else {
            $r['response_status'] = false;
        }

        return $r;
    }

    public function endDeletedProducts($account) {
        $rows = $this->db->query("SELECT ep.product_id as id from " . DB_PREFIX . "channel_ebay_product ep
							LEFT JOIN " . DB_PREFIX . "product p on p.product_id = ep.product_id
							WHERE p.product_id is null")->rows;
        foreach ($rows as $row) {
            $this->deleteProduct($row['id'], $account);
        }

        return count($rows);
    }

    public function deleteProduct($productId, $account) {
        $r = $this->endEbayItem($productId, 2, $account);
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_product` where product_id=". $this->db->escape($productId));
    }

    public function relistEbayItem($productId, $account, $profile, $settings) {
        $this->load->model('ebay_channel/api');
        $this->load->model('ebay_channel/dao');
        $this->load->model('ebay_channel/product_dao');
        $r = array();
        $p2e = $this->getProductToEbay($productId);


        $this->load->model('ebay_channel/ebay_details_dao');
        $profile['ebay_details'] = $this->model_ebay_channel_ebay_details_dao->getDetails(array('ProductDetails'), $profile['list_profile']['site_id']);

        $product = $this->model_ebay_channel_product_dao->getProductRecord($productId, $profile['list_profile']['language_id'], $profile['list_profile']);
        $price =  $product['product']['price'];
        $basePrice = $product['product']['base_price'];
        $baseQty = $product['product']['base_quantity'];
        $qty = $product['product']['quantity'];

        if($profile['list_profile']['listing_type'] == 'Chinese') {
            $r = $this->model_ebay_channel_api->getRelistItemCall($account, $profile['list_profile']['site_id'])->relistItem($p2e['ebay_id'], $product, $profile, $settings);
            $r['type'] = 'RelistItem';
        } else {
            $r = $this->model_ebay_channel_api->getRelistFixedPriceItemCall($account, $profile['list_profile']['site_id'])->relistItem($p2e['ebay_id'], $product, $profile, $settings);
            $r['type'] = 'RelistFixedPriceItem';
        }

        if(isset($r['item_id']) && !empty($r['item_id'])) {
            $variations = isset($product['variations']['variations'])? $product['variations']['variations'] : array();
            $this->addProductToEbay($productId, $basePrice, $baseQty, $r['item_id'],
                $profile['list_profile']['site_id'],
                $profile['list_profile']['id'],
                $account['listing_mode'],
                $profile['list_profile']['listing_type'],
                $r['start_time'], $r['end_time'], '', $variations);
            if(!empty($variations)) {
                $this->syncronizeItem($productId, $r['item_id'], $account);
            }
            $r['response_status'] = true;
        } else {
            $r['response_status'] = false;
        }



        return $r;
    }

    public function reviseEbayItem($productId, $account, $profile = array(), $settings = array()) {
        $this->load->model('ebay_channel/api');
        $this->load->model('ebay_channel/dao');
        $this->load->model('ebay_channel/product_dao');
        $this->load->model('ebay_channel/settings_dao');
        $this->load->model('ebay_channel/settings_dao');
        $this->load->model('ebay_channel/listing_profile_dao');
        $this->load->model('ebay_channel/ebay_details_dao');

        if(empty($settings)) {
            $settings = $this->model_ebay_channel_settings_dao->getSettings();
        }

        $r = array();
        $r['response_status'] = false;

        $p2e = $this->getProductToEbay($productId);
        if(empty($profile)) {
            $profile = $this->model_ebay_channel_listing_profile_dao->getRecord($p2e['list_profile_id']);
        }

        $profile['ebay_details'] = $this->model_ebay_channel_ebay_details_dao->getDetails(array('ProductDetails'), $profile['list_profile']['site_id']);
        $product = $this->model_ebay_channel_product_dao->getProductRecord($productId, $profile['list_profile']['language_id'], $profile['list_profile']);
        $price =  $product['product']['price'];
        $basePrice = $product['product']['base_price'];
        $baseQty = $product['product']['base_quantity'];
        $qty = $product['product']['quantity'];

        if($settings['end_item_out_stock_enabled'] && $baseQty < 1) {
            $r = $this->endEbayItem($productId, 2, $account);
            $r['response_status'] = !isset($r['errors']);
            $r['type'] = 'EndItem';
        } else {
            if($profile['list_profile']['listing_type'] == 'Chinese') {
                $r = $this->model_ebay_channel_api->getReviseItemCall($account, $profile['list_profile']['site_id'])->reviseItem($p2e['ebay_id'], $product, $profile, $settings);
                $r['type'] = 'ReviseItem';
            } else {
                $r = $this->model_ebay_channel_api->getReviseFixedPriceItemCall($account, $profile['list_profile']['site_id'])->reviseItem($p2e['ebay_id'], $product, $profile, $settings);
                $r['type'] = 'ReviseFixedPriceItem';
            }

            if(isset($r['item_id']) && !empty($r['item_id'])) {
                $variations = isset($product['variations']['variations'])? $product['variations']['variations'] : array();
                $this->addProductToEbay($productId, $basePrice, $baseQty, $r['item_id'],
                    $profile['list_profile']['site_id'],
                    $profile['list_profile']['id'],
                    $account['listing_mode'],
                    $profile['list_profile']['listing_type'],
                    $r['start_time'], $r['end_time'], '', $variations);
                if(!empty($variations)) {
                    $this->syncronizeItem($productId, $r['item_id'], $account);
                }

                $r['response_status'] = true;
            } else {
                $r['response_status'] = false;
            }

        }
        return $r;
    }

    public function verifyEbayItem($productId, $account, $profile) {
        $this->load->model('ebay_channel/api');
        $this->load->model('ebay_channel/dao');
        $this->load->model('ebay_channel/product_dao');
        $r = array();

        $this->load->model('ebay_channel/ebay_details_dao');
        $profile['ebay_details'] = $this->model_ebay_channel_ebay_details_dao->getDetails(array('ProductDetails'), $profile['list_profile']['site_id']);

        $product = $this->model_ebay_channel_product_dao->getProductRecord($productId, $profile['list_profile']['language_id'], $profile['list_profile']);
        $price =  $product['product']['price'];
        $qty = $product['product']['quantity'];

        if($profile['list_profile']['listing_type'] == 'Chinese') {
            $r = $this->model_ebay_channel_api->getVerifyAddItemCall($account, $profile['list_profile']['site_id'])->verifyItem($product, $profile);
            $r['type'] = 'VerifyAddItem';

        } else {
            $r = $this->model_ebay_channel_api->getVerifyAddFixedPriceItemCall($account, $profile['list_profile']['site_id'])->verifyAddFixedPriceItem($product, $profile);
            $r['type'] = 'VerifyAddFixedPriceItem';
        }

        if(isset($r['fees']) && !empty($r['fees'])) {
            $r['response_status'] = true;
        } else {
            $r['response_status'] = false;
        }

        return $r;
    }

    public function syncronizeItem($productId, $ebayItemId, $account) {
        $this->load->model('ebay_channel/api');


        $r = $this->model_ebay_channel_api->getGetItemCall($account, $account['default_site'])->getItem(array(
            "item_id"=>$ebayItemId,
            "detail_level"=>"ReturnAll"
        ));

        $r['type'] = 'SyncronizeItem';

        if(isset($r['item'])) {
            $item = $r['item'];
            $variations = isset($item['variations'])? $item['variations'] : array();
            $this->update($productId, $item['EndTime'], $item['StartTime'], $item['StartPrice'], $item['Quantity'], $variations);
            $r['response_status'] = true;
        }

        return $r;
    }

    public function getItem($ebayItemId, $account) {
        $this->load->model('ebay_channel/api');
        $r = $this->model_ebay_channel_api->getGetItemCall($account, $account['default_site'])->getItem(array(
            "item_id"=>$ebayItemId,
            "detail_level"=>"ReturnAll"
        ));
        return $r;
    }



    public function updateEbayInventory($productId, $account) {

        $this->load->model('ebay_channel/api');
        $this->load->model('ebay_channel/dao');
        $this->load->model('ebay_channel/product_dao');
        $this->load->model('ebay_channel/settings_dao');
        $this->load->model('ebay_channel/listing_profile_dao');

        $settings = $this->model_ebay_channel_settings_dao->getSettings();

        $r = array();
        $p2e = $this->getProductToEbay($productId);
        if(!empty($p2e)) {
            $profile = $this->model_ebay_channel_listing_profile_dao->getRecord($p2e['list_profile_id']);

            $this->load->model('ebay_channel/ebay_details_dao');
            $profile['ebay_details'] = $this->model_ebay_channel_ebay_details_dao->getDetails(array('ProductDetails'), $profile['list_profile']['site_id']);

            $product = $this->model_ebay_channel_product_dao->getProductRecord($productId, $profile['list_profile']['language_id'], $profile['list_profile']);
            $price =  $product['product']['price'];
            $basePrice = $product['product']['base_price'];
            $baseQty = $product['product']['base_quantity'];
            $qty = $product['product']['quantity'];
            $type = '';
            if($settings['end_item_out_stock_enabled'] && $baseQty < 1) {
                $type = 'EndItem';
                $r = $this->endEbayItem($productId, 2, $account);
                $r['response_status'] = !isset($r['errors']);
            } else {
                if((isset($product['variations']) && !empty($product['variations'])) || $profile['list_profile']['listing_type'] == 'Chinese') {
                    if($profile['list_profile']['listing_type'] == 'Chinese') {
                        $type = 'ReviseItem';
                        $r = $this->model_ebay_channel_api->getReviseItemCall($account, $profile['list_profile']['site_id'])->reviseItem($p2e['ebay_id'], $product, $profile, $settings);
                    } else {
                        $type = 'ReviseFixedPriceItem';
                        $r = $this->model_ebay_channel_api->getReviseFixedPriceItemCall($account, $profile['list_profile']['site_id'])->reviseItem($p2e['ebay_id'], $product, $profile, $settings);
                    }

                    if(isset($r['item_id']) && !empty($r['item_id'])) {
                        $r['response_status'] = true;
                    }
                } else {
                    $type = 'ReviseInventoryStatus';
                    $r = $this->model_ebay_channel_api->getReviseInventoryStatusCall($account, $profile['list_profile']['site_id'])->reviseInventory($p2e['ebay_id'], $price, $qty, $profile, $settings);
                    if(isset($r['inventory_status'])) {
                        $r['response_status'] = true;
                    }
                }
            }

            if(!isset($r['response_status'])) {
                $r['response_status'] = false;
            }

            $r['type'] = $type;
            if($r['response_status']) {
                $this->updateInventory($productId, $basePrice, $baseQty);
            } elseif(isset($r['errors'])) {
                foreach ($r['errors'] as $error) {
                    if($error['code'] == '21916750') {
                        //syncronize item
                        $this->syncronizeItem($productId, $p2e['ebay_id'], $account);
                    }
                }
            }

        }
        return $r;
    }

}
?>