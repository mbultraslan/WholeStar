<?php
class ModelEbayChannelImportDao extends Model {

    public function importProduct($product, $account, $settings, $processId = null) {
        $this->load->model('ebay_channel/dao');
        $this->load->model('ebay_channel/product_dao');
        $this->load->model('ebay_channel/log_dao');
        $this->load->model('ebay_channel/product_to_ebay_dao');
        $this->load->model('ebay_channel/api');

        if( !file_exists(DIR_IMAGE . 'data/product') ) mkdir(DIR_IMAGE . 'data/product', 0777, TRUE);

        $defaultCurrency = $settings['product_import_currency'];
        $product['has_option'] = 0;
        $existsOpenStock = $this->model_ebay_channel_product_dao->checkIfExistsOpenStock();
        if($existsOpenStock && isset($product['variations']) && !empty($product['variations'])) {
            $product['has_option'] = 1;
        }


        $product['listing_mode'] = $account['listing_mode'];
        if($this->currency->has($product['currency'])) {

            if(isset($product['start_price'])) {//Convert to default currency
                $product['start_price'] = $this->currency->convert($product['start_price'], $product['currency'], $defaultCurrency);
            }

            $ebaySite = $this->getProductSite($product, $account);
            if($ebaySite) {
                $product['site_id'] = $product['site'];
                $product['site'] = $ebaySite['id'];
                $product['currency'] = $ebaySite['currency'];
                $product['enabled_openstock'] = $existsOpenStock;

                if(!empty($settings['product_import_categories']) && $settings['product_import_categories'] == 1) {
                    if(!$this->model_ebay_channel_dao->hasCategories($product['site'])) {
                        $categories = $this->model_ebay_channel_api->getCategoriesCall($account, $product['site'])->getCategories(array("DetailLevel" => "ReturnAll"));
                        $this->model_ebay_channel_dao->addEbayCategories($categories, $product['site']);
                    }

                    $tree = $this->model_ebay_channel_dao->getEbayCategoryTreeById($product['site'], $product['category_id']);
                    $parent = 0;
                    $top = 0;
                    foreach($tree as $ebayCategory) {
                        $parent = $this->model_ebay_channel_product_dao->addCategory($ebayCategory, $parent, $top++);
                    }
                }
            } else {
                return false;
            }

            if(!empty($settings['product_filter_tags'])) {
                $description = '';
                foreach($settings['product_filter_tags'] as $filter) {
                    libxml_use_internal_errors(TRUE);
                    $dom = new DOMDocument;
                    $dom->loadHTML(mb_convert_encoding($product['description'], 'HTML-ENTITIES', 'UTF-8'));
                    libxml_clear_errors();

                    $xp = new DOMXpath($dom);
                    $nodes = $xp->query('//' . $filter['tag'] . '[contains(@' . $filter['attribute'] . ',"' . $filter['value'] . '")]');
                    if($nodes) {
                        for($i=0; $i < $nodes->length; $i++) {
                            $tag = $nodes->item($i);
                            $innerHTML= '';
                            // $innerHTML = $tag->nodeValue;
                            $children = $tag->childNodes;
                            foreach ($children as $child) {
                                $innerHTML .= $child->ownerDocument->saveXML($child);
                            }
                            $description = $innerHTML;
                        }
                    }
                }

                if(!empty($description)) {
                    $product['description'] =  $description;
                }
            }


            $product['weight_class_id'] = 1;
            $product['weight'] = 0;
            if(isset($product['shipping_package_details']['weight_major'])) {
                $unit = $product['shipping_package_details']['weight_major']['unit'];
                $product['weight_class_id'] = $this->model_ebay_channel_product_dao->addWeightClass($unit, $settings['product_import_language_id']);
                $product['weight'] = $product['shipping_package_details']['weight_major']['value'];
            }

            if(empty($product['weight'])) {
                if(isset($product['shipping_package_details']['weight_minor'])) {
                    $unit = $product['shipping_package_details']['weight_minor']['unit'];
                    $product['weight_class_id'] = $this->model_ebay_channel_product_dao->addWeightClass($unit, $settings['product_import_language_id']);
                    $product['weight'] = $product['shipping_package_details']['weight_minor']['value'];
                }
            }

            $product['length_class_id'] = 1;
            $product['height'] = 0;
            if(isset($product['shipping_package_details']['package_depth'])) {
                $unit = $product['shipping_package_details']['package_depth']['unit'];
                $product['length_class_id'] = $this->model_ebay_channel_product_dao->addLengthClass($unit, $settings['product_import_language_id']);
                $product['height'] = $product['shipping_package_details']['package_depth']['value'];
            }

            $product['length_class_id'] = 1;
            $product['width'] = 0;
            if(isset($product['shipping_package_details']['package_width'])) {
                $unit = $product['shipping_package_details']['package_width']['unit'];
                $product['length_class_id'] = $this->model_ebay_channel_product_dao->addLengthClass($unit, $settings['product_import_language_id']);
                $product['width'] = $product['shipping_package_details']['package_width']['value'];
            }

            $product['length_class_id'] = 1;
            $product['length'] = 0;
            if(isset($product['shipping_package_details']['package_length'])) {
                $unit = $product['shipping_package_details']['package_length']['unit'];
                $product['length_class_id'] = $this->model_ebay_channel_product_dao->addLengthClass($unit, $settings['product_import_language_id']);
                $product['length'] = $product['shipping_package_details']['package_length']['value'];
            }



            if(!empty($description)) {
                $product['description'] =  htmlspecialchars($description);
            }

            $product['title'] = htmlspecialchars($product['title']);

            $product_id = 0;

            $p2e = $this->model_ebay_channel_product_to_ebay_dao->getProductToEbayByItemId($product['item_id']);
            if(!empty($p2e)) {
                $product_id = $p2e['product_id'];
            } else {
                $product_id = $this->model_ebay_channel_product_dao->getProductIdByName($product['title'], $settings['product_import_language_id']);
            }


            if(empty($product_id)) { //Link to openbay
                $existsOpenEbay = $this->model_ebay_channel_product_dao->existsOpenbay();
                if($existsOpenEbay) {
                    $product_id = $this->model_ebay_channel_product_dao->getProductIdFromOpenbay($product['item_id']);
                }
            }


            if(empty($product_id)) {
                $product_id = $this->model_ebay_channel_product_dao->insert($product, $settings);
            } else {
                $this->model_ebay_channel_product_dao->update($product_id, $product, $settings);
            }

            $this->model_ebay_channel_product_dao->clearProductOption($product_id);
            $this->model_ebay_channel_product_dao->clearProductAttributes($product_id);

            if($existsOpenStock) {
                $this->model_ebay_channel_product_dao->clearProductOptionVariant($product_id);
            }

            $ov = array();
            if(isset($product['variations'])) {
                foreach ($product['variations'] as $variation) {
                    $b = true;

                    $productOptionVariantId = 0;
                    if($existsOpenStock) {
                        $productOptionVariant = array(
                            'product_id' => $product_id,
                            'sku' => '',//TODO Get from variation
                            'stock' => $variation['quantity'],
                            'active' => 1,
                            'subtract' => 1,
                            'price' => $variation['price'],
                            'weight' => 0
                        );
                        $productOptionVariantId = $this->model_ebay_channel_product_dao->addProductOptionVariant($productOptionVariant, $product_id);
                    }


                    foreach ($variation['variation_specifics'] as $variation_specific) {
                        //echo $variation_specific['name'] . ';';
                        $optionId = $this->model_ebay_channel_product_dao->addOrUpdateOption($variation_specific['name']);
                        $optionValueId = $this->model_ebay_channel_product_dao->addOrUpdateOptionValue($variation_specific['value'], $optionId);



                        $ovp = 0;
                        $pricePrefix = '+';
                        if($b) {
                            $ovp =  $variation['price'] - $product['start_price'];
                            $ovp = $this->currency->convert($ovp, $product['currency'], $defaultCurrency);
                            $pricePrefix = '+';
                            if($ovp < 0) {
                                $pricePrefix = '-';
                            }
                            $b = false;
                        }

                        $hasValue = false;
                        foreach ($ov as $o) {
                            if($o['option_value_id'] == $optionValueId) {
                                $hasValue = true;
                            }
                        }

                        if(!$hasValue) {
                            $ov[] = array(
                                'option_id' => $optionId,
                                'option_value_id'=>$optionValueId,
                                'option_name'=> $variation_specific['name'],
                                'option_value'=> $variation_specific['value'],
                                'quantity'=> $variation['quantity'] ,
                                'price'=>$ovp,
                                'product_option_variant_id' => $productOptionVariantId,
                                'price_prefix' => $pricePrefix);
                        }

                    }
                }
            }





            $po = array();
            foreach ($ov as $key => $optionValue) {
                if(!isset($po[$optionValue['option_id']])) {
                    $product_option_id =  $this->model_ebay_channel_product_dao->addProductOption($product_id, $optionValue['option_id']);
                    $ov[$key]['product_option_id'] = $product_option_id;
                    $po[$optionValue['option_id']] = $product_option_id;
                } else {
                    $ov[$key]['product_option_id'] = $po[$optionValue['option_id']];
                }
            }

            foreach ($ov as $optionValue) {
                $productOptionValueId = $this->model_ebay_channel_product_dao->addProductOptionValue($product_id, $optionValue);
                if($existsOpenStock) {
                    $productOptionVariantValue = array(
                        'product_id' => $product_id,
                        'product_option_variant_id' => $optionValue['product_option_variant_id'],
                        'product_option_value_id' => $productOptionValueId,
                        'sort_order' => 0
                    );
                    $this->model_ebay_channel_product_dao->addProductOptionVariantValue($productOptionVariantValue, $product_id);
                }
            }


            if(!empty($settings['product_import_specifics'])) {
                $ri = $this->model_ebay_channel_api->getGetItemCall($account, $account['default_site'])->getItem(array(
                    "item_id"=>$product['item_id'],
                    "include_item_specifics"=>true,
                    "detail_level"=>"ItemReturnAttributes"
                ));

                if(!empty($ri['item_specifics'])) {
                    $groupId = $this->model_ebay_channel_product_dao->addOrUpdateAttributeGroup("Ebay Item Specifics");
                    foreach ($ri['item_specifics'] as $itemSpecific) {
                        $attributeId = $this->model_ebay_channel_product_dao->addOrUpdateAttribute($itemSpecific['name'], $groupId);
                        $this->model_ebay_channel_product_dao->addAttributeToProduct($product_id, $attributeId, $itemSpecific['value']);
                    }
                }
            }

            //import listing profile for this item
            $this->importListingProfile($product_id, $product, $account, $settings);

            if($processId != null) {
                $this->model_ebay_channel_process_dao->updateProcessed($processId);
            }
            return $product_id;


        } else {
            $this->model_ebay_channel_log_dao->addWarningLog("Import", "Please add currency " . $product['currency'] . " to import this item: " . $product['item_id'], 7);
        }


        if($processId != null) {
            $this->model_ebay_channel_process_dao->updateProcessed($processId);
        }
        return false;
    }


    public function importLink($product, $account, $settings, $processId = null) {
        $this->load->model('ebay_channel/dao');
        $this->load->model('ebay_channel/product_dao');
        $this->load->model('ebay_channel/log_dao');
        $this->load->model('ebay_channel/product_to_ebay_dao');
        $this->load->model('ebay_channel/api');



        $defaultCurrency = $settings['product_import_currency'];

        $product['listing_mode'] = $account['listing_mode'];
        if($this->currency->has($product['currency'])) {

            if(isset($product['start_price'])) {//Convert to default currency
                $product['start_price'] = $this->currency->convert($product['start_price'], $product['currency'], $defaultCurrency);
            }

            $ebaySite = $this->getProductSite($product, $account);
            if($ebaySite) {
                $product['site_id'] = $product['site'];
                $product['site'] = $ebaySite['id'];
                $product['currency'] = $ebaySite['currency'];

                if(!empty($settings['product_import_categories']) && $settings['product_import_categories'] == 1) {
                    if(!$this->model_ebay_channel_dao->hasCategories($product['site'])) {
                        $categories = $this->model_ebay_channel_api->getCategoriesCall($account, $product['site'])->getCategories(array("DetailLevel" => "ReturnAll"));
                        $this->model_ebay_channel_dao->addEbayCategories($categories, $product['site']);
                    }

                    $tree = $this->model_ebay_channel_dao->getEbayCategoryTreeById($product['site'], $product['category_id']);
                    $parent = 0;
                    $top = 0;
                    foreach($tree as $ebayCategory) {
                        $parent = $this->model_ebay_channel_product_dao->addCategory($ebayCategory, $parent, $top++);
                    }
                }
            } else {
                return false;
            }

            $product['title'] = htmlspecialchars($product['title']);

            $product_id = 0;

            $p2e = $this->model_ebay_channel_product_to_ebay_dao->getProductToEbayByItemId($product['item_id']);
            if(!empty($p2e)) {
                $product_id = $p2e['product_id'];
            } else {
                $product_id = $this->model_ebay_channel_product_dao->getProductIdByName($product['title'], $settings['product_import_language_id']);
            }


            if(empty($product_id)) { //Link to openbay
                $existsOpenEbay = $this->model_ebay_channel_product_dao->existsOpenbay();
                if($existsOpenEbay) {
                    $product_id = $this->model_ebay_channel_product_dao->getProductIdFromOpenbay($product['item_id']);
                }
            }


            if(!empty($product_id)) {
                $this->model_ebay_channel_product_dao->insertOrUpdateLink($product, $settings);
            }


            //import listing profile for this item
            $this->importListingProfile($product_id, $product, $account, $settings);

            if($processId != null) {
                $this->model_ebay_channel_process_dao->updateProcessed($processId);
            }
            return $product_id;


        } else {
            $this->model_ebay_channel_log_dao->addWarningLog("Import", "Please add currency " . $product['currency'] . " to import this item: " . $product['item_id'], 7);
        }


        if($processId != null) {
            $this->model_ebay_channel_process_dao->updateProcessed($processId);
        }
        return false;
    }

    public function importListingProfile($product_id, $product, $account, $settings) {
        if(empty($product_id)) {
            return false;
        }

        $this->load->model('ebay_channel/dao');
        $this->load->model('ebay_channel/product_dao');
        $this->load->model('ebay_channel/product_to_ebay_dao');
        $this->load->model('ebay_channel/log_dao');
        $this->load->model('ebay_channel/listing_profile_dao');

        $listProfile = array();

        if(!isset($product['site_id']) || !isset($product['currency'])) {
            $ebaySite = $this->getProductSite($product, $account);
            if($ebaySite) {
                $product['site'] = $ebaySite['id'];
                $product['currency'] = $ebaySite['currency'];
            } else {
                return false;
            }
        }

        $listProfile['site_id'] = $product['site'];

        $listProfile['name'] = 'Listing Profile #' . $product['item_id'];
        $listProfile['ebay_store_category_id'] = '';
        $listProfile['ebay_store_category_path'] = '';
        $listProfile['is_default'] = false;
        $listProfile['language_id'] = $settings['product_import_language_id'];
        $listProfile['ebay_category_id'] = $product['category_id'];
        $listProfile['ebay_category_path'] = $this->model_ebay_channel_dao->getEbayCategoryPath($listProfile['site_id'], $product['category_id']);
        $listProfile['currency'] = $product['currency'];

        if(isset($product['store_category_id']) && !empty($product['store_category_id'])) {
            $listProfile['ebay_store_category_id'] = $product['store_category_id'];
            $listProfile['ebay_store_category_path'] = $this->model_ebay_channel_dao->getEbayStoreCategoryPath($product['store_category_id']);
        }

        $listProfile['private_listing'] = ($product['private_listing'] == 'false')? 0 : 1;
        $listProfile['attributes_enabled'] = false;

        $listProfile['item_condition_id'] = null;
        if(isset($product['condition_id']) && !empty($product['condition_id'])) {
            $listProfile['item_condition_id'] = $product['condition_id'];
        }

        $listProfile['item_condition_description'] = null;
        if(isset($product['condition_description'])  && !empty($product['condition_description'])) {
            $listProfile['item_condition_description'] = $product['condition_description'];
        }

        $listProfile['listing_type'] = $product['listing_type'];
        $listProfile['duration'] = $product['listing_duration'];


        //TODO on update load old data
        $listProfile['template_id'] = '';
        $listProfile['title_suffix'] = '';
        $listProfile['subtitle'] = '';
        $listProfile['qty_to_sell'] = 1;
        $listProfile['max_qty_to_sell'] = null;

        $listProfile['price_option'] = 'product_price';
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

        //TODO on update load old data
        $listProfile['paypal_required'] = 0;
        $listProfile['return_policy_enabled'] = 0;
        $listProfile['handling_time_enabled'] = 0;
        $listProfile['variations_enabled'] = 0;
        $listProfile['revise_quantity_allowed'] = 0;
        $listProfile['revise_price_allowed'] = 0;
        $listProfile['minimum_reserve_price'] = 0;

        $listProfile['ean_enabled'] = null;
        $listProfile['upc_enabled'] = null;
        $listProfile['isbn_enabled'] = null;
        $listProfile['brandmpn_enabled'] = null;
        $listProfile['item_specifics'] = null;


        $listProfile['country'] = null;
        if(isset($product['country'])  && !empty($product['country'])) {
            $listProfile['country'] = $product['country'];
        }

        $listProfile['zip_postcode'] = null;
        if(isset($product['postal_code'])  && !empty($product['postal_code'])) {
            $listProfile['zip_postcode'] = $product['postal_code'];
        }

        $listProfile['location'] = null;
        if(isset($product['location'])  && !empty($product['location'])) {
            $listProfile['location'] = $product['location'];
        }


        $listProfile['city_state'] = null;
        if(isset($product['location'])  && !empty($product['location'])) {
            $listProfile['city_state'] = $product['location'];
        }

        $listProfile['paypal_email'] = null;
        if(isset($product['paypal_email_address'])  && !empty($product['paypal_email_address'])) {
            $listProfile['paypal_email'] = $product['paypal_email_address'];
        }

        $listProfile['dispatch_time'] = null;
        if(isset($product['dispatch_time_max'])  && !empty($product['dispatch_time_max'])) {
            $listProfile['dispatch_time'] = $product['dispatch_time_max'];
        }

        $listProfile['payment_instructions'] = null;
        $listProfile['returns_accepted'] = null;
        $listProfile['returns_within'] = null;
        $listProfile['refunds'] = null;
        $listProfile['shippingcost_paidby'] = null;
        $listProfile['return_policy_description'] = null;

        if(isset($product['return_policy'])) {
            if(!empty($product['return_policy']['returns_accepted_option'])) {
                $listProfile['returns_accepted'] = $product['return_policy']['returns_accepted_option'];
            }

            if(!empty($product['return_policy']['returns_within_option'])) {
                $listProfile['returns_within'] = $product['return_policy']['returns_within_option'];
            }

            if(!empty($product['return_policy']['refund_option'])) {
                $listProfile['refunds'] = $product['return_policy']['refund_option'];
            }

            if(!empty($product['return_policy']['shipping_cost_paid_by_option'])) {
                $listProfile['shippingcost_paidby'] = $product['return_policy']['shipping_cost_paid_by_option'];
            }

            if(!empty($product['return_policy']['description'])) {
                $listProfile['return_policy_description'] = $product['return_policy']['description'];
            }
        }


        $listProfile['shipping_type'] = null;
        $listProfile['dimension_depth'] = null;
        $listProfile['dimension_width'] = null;
        $listProfile['dimension_length'] = null;

        $listProfile['weight_major'] = null;
        $listProfile['weight_minor'] = null;
        $listProfile['is_irregular_package'] = 0;
        $listProfile['package_handling_fee'] = 0;
        $listProfile['shipping_package'] = null;

        if(isset($product['shipping_details'])) {

            if(isset($product['shipping_details']['shipping_type'])) {
                $listProfile['shipping_type'] = $product['shipping_details']['shipping_type'];
            }

            if(isset($product['shipping_details']['calculated_shipping_rate'])) {

                if(isset($product['shipping_details']['calculated_shipping_rate']['shipping_package'])) {
                    $listProfile['shipping_package'] = $product['shipping_details']['calculated_shipping_rate']['shipping_package'];
                }

                if(isset($product['shipping_details']['calculated_shipping_rate']['weight_major'])) {
                    $listProfile['weight_major'] = $product['shipping_details']['calculated_shipping_rate']['weight_major'];
                }

                if(isset($product['shipping_details']['calculated_shipping_rate']['weight_minor'])) {
                    $listProfile['weight_minor'] = $product['shipping_details']['calculated_shipping_rate']['weight_minor'];
                }

                if(isset($product['shipping_details']['calculated_shipping_rate']['shipping_irregular'])) {
                    $listProfile['weight_minor'] = ($product['shipping_details']['calculated_shipping_rate']['shipping_irregular'] == 'true')? 1 : 0;
                }

                if(isset($product['shipping_details']['calculated_shipping_rate']['packaging_handling_costs'])) {
                    $listProfile['package_handling_fee'] = $product['shipping_details']['calculated_shipping_rate']['packaging_handling_costs'];
                }

            }

            $listProfile['has_international_shipping'] = isset($product['shipping_details']['international_shipping_service_option']);
        }

        // Build checksum
        $payments = array();
        $shippings = array();

        if(isset($product['payment_methods']) && !empty($product['payment_methods'])) {
            foreach ($product['payment_methods'] as $payment) {
                $payments[] = $payment;
            }
        }

        if(isset($product['shipping_details']['international_shipping_service_option'])) {
            foreach ($product['shipping_details']['international_shipping_service_option'] as $shippingService) {
                $shippings[] = $shippingService['shipping_service'];
            }
        }

        if(isset($product['shipping_details']['shipping_service_options'])) {
            foreach ($product['shipping_details']['shipping_service_options'] as $shippingService) {
                $shippings[] = $shippingService['shipping_service'];
            }
        }

        $md5 = $this->model_ebay_channel_listing_profile_dao->getByMD5($listProfile, $payments, $shippings);
        $listingId = $this->model_ebay_channel_listing_profile_dao->getIdByChecksum($md5);
        if(empty($listingId)) {
            $listingId = $this->model_ebay_channel_listing_profile_dao->insert($listProfile);
            $this->model_ebay_channel_listing_profile_dao->updateCheckSum($listingId, $md5);


            if(!empty($listingId)) {

                if(isset($product['payment_methods']) && !empty($product['payment_methods'])) {
                    foreach ($product['payment_methods'] as $payment) {
                        $this->model_ebay_channel_listing_profile_dao->addPaymentMethod(array('list_profile_id'=>$listingId, 'name'=>$payment));
                    }
                }



                if(isset($product['shipping_details'])) {

                    $t = 'f';
                    if($listProfile['shipping_type'] == 'Calculated') {
                        $t = 'c';
                    }

                    if(isset($product['shipping_details']['international_shipping_service_option'])) {
                        foreach ($product['shipping_details']['international_shipping_service_option'] as $shippingService) {
                            $this->model_ebay_channel_listing_profile_dao->addShippingService(array('list_profile_id'=>$listingId,
                                'service_type'=> $t . 'i',
                                'service'=>$shippingService['shipping_service'],
                                'locations'=>implode(',', $shippingService['ship_to_location']),
                                'cost'=> '0',
                                'each_additional'=> '0',
                                'is_international'=>1,
                                'is_free_shipping'=> 0));
                        }
                    }

                    if(isset($product['shipping_details']['shipping_service_options'])) {
                        foreach ($product['shipping_details']['shipping_service_options'] as $shippingService) {
                            $this->model_ebay_channel_listing_profile_dao->addShippingService(array('list_profile_id'=>$listingId,
                                'service_type'=> $t . 'd',
                                'service'=>$shippingService['shipping_service'],
                                'locations'=>'',
                                'cost'=> '0',
                                'each_additional'=> '0',
                                'is_international'=>0,
                                'is_free_shipping'=> 0));
                        }
                    }
                }

                $features = $this->model_ebay_channel_api->getCategoryFeaturesCall($account, $listProfile['site_id'])->getFeatures($listProfile['ebay_category_id'], array('ReturnAll'));

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

                if(isset($features['PayPalRequired']) && strtolower($features['PayPalRequired'])=='true') {
                    $listProfile['paypal_required'] = 1;
                }

                if(isset($features['ReturnPolicyEnabled']) && strtolower($features['ReturnPolicyEnabled'])=='true') {
                    $listProfile['return_policy_enabled'] = 1;
                }

                if(isset($features['HandlingTimeEnabled']) && strtolower($features['HandlingTimeEnabled'])=='true') {
                    $listProfile['handling_time_enabled'] = 1;
                }


                if(isset($features['VariationsEnabled']) && strtolower($features['VariationsEnabled'])=='true') {
                    $listProfile['variations_enabled'] = 1;
                }


                if(isset($features['ReviseQuantityAllowed']) && strtolower($features['ReviseQuantityAllowed'])=='true') {
                    $listProfile['revise_quantity_allowed'] = 1;
                }


                if(isset($features['RevisePriceAllowed']) && strtolower($features['RevisePriceAllowed'])=='true') {
                    $listProfile['revise_price_allowed'] = 1;
                }

                if(isset($features['MinimumReservePrice'])) {
                    $listProfile['minimum_reserve_price'] = $features['MinimumReservePrice'];
                }

                $listProfile['id'] = $listingId;

                $this->model_ebay_channel_listing_profile_dao->update($listProfile);



                $hasEbayItemSpecifics = $this->model_ebay_channel_dao->hasEbayItemSpecific($listProfile['ebay_category_id'], $listProfile['site_id']);
                if(!$hasEbayItemSpecifics) {
                    $specifics = $this->model_ebay_channel_api->GetCategorySpecificsCall($account, $listProfile['site_id'])->getFeatures($listProfile['ebay_category_id']);
                    if(isset($specifics['NameRecommendations'])) {
                        foreach ($specifics['NameRecommendations'] as $specific) {
                            $this->model_ebay_channel_dao->addEbayItemSpecific($specific, $listProfile['ebay_category_id'],	$listProfile['site_id']);
                        }
                    }
                }
            }
        }
        //End build checksum

        $this->model_ebay_channel_product_to_ebay_dao->addProductToEbay($product_id, $product['start_price'],
            $product['quantity'],
            $product['item_id'],
            $listProfile['site_id'],
            $listingId,
            $account['listing_mode'],
            $listProfile['listing_type'],
            $product['listing_details']['start_time'],
            $product['listing_details']['end_time'], '');

    }

    public function importOrder($order, $settings, $processId=null) {
        $this->load->model('ebay_channel/process_dao');
        $this->load->model('ebay_channel/dao');
        $this->load->model('ebay_channel/order_dao');
        $this->load->model('ebay_channel/product_dao');
        $this->load->model('localisation/country');
        $this->load->model('ebay_channel/product_to_ebay_dao');
        $this->load->model('ebay_channel/customer_dao');
        $this->load->model('ebay_channel/tax_dao');


        $countries = $this->model_localisation_country->getCountries();

        if(!empty($settings['import_compleded'])) {
            if(!($order['order_status'] == 'Completed' || $order['order_status'] == 'Shipped')) {
                if($processId != null) {
                    $this->model_ebay_channel_process_dao->updateProcessed($processId);
                }
                return false;
            }
        }

        $data = array();
        $data['invoice_prefix'] = $settings['order_invoice_prefix'];

        if (!empty($settings['order_store_id'])) {
            $store = $this->model_ebay_channel_dao->getStoreById($settings['order_store_id']);
            if(!empty($store)) {
                $data['store_url'] = $store['url'];
                $data['store_id'] = $store['store_id'];
                $data['store_name'] = $store['name'];
            }
        }

        if(!isset($data['store_id'])) {
            $data['store_url'] = $this->config->get('config_url');
            $data['store_id'] = $this->config->get('config_store_id');
            $data['store_name'] = $this->config->get('config_name');
        }

        $mappedStatusKey = $this->model_ebay_channel_order_dao->getOrderStatusKey($order['order_status']);
        $data['order_status_id'] = $settings[$mappedStatusKey];

        $data['customer_group_id'] = $settings['order_customer_group_id'];
        $data['language_id'] = $settings['product_import_language_id'];
        $data['currency_id'] = $this->currency->getId($order['total_currency']);
        $data['currency_code'] = $order['total_currency'];
        $data['currency_value'] = $this->currency->getValue($order['total_currency']);
        $data['ip'] = $this->request->server['REMOTE_ADDR'];

        if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
            $data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
        } elseif(!empty($this->request->server['HTTP_CLIENT_IP'])) {
            $data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
        } else {
            $data['forwarded_ip'] = '';
        }

        if (isset($this->request->server['HTTP_USER_AGENT'])) {
            $data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
        } else {
            $data['user_agent'] = '';
        }

        if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
            $data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
        } else {
            $data['accept_language'] = '';
        }


        $data['firstname'] = $order['buyer_user_id'];
        $data['lastname'] = '';
        $data['customer_id'] = 0;


        $data['payment_company'] = '';
        $data['payment_company_id'] = '';
        $data['payment_tax_id'] = '';
        $data['payment_address_1'] = '';
        $data['payment_address_2'] = '';
        $data['payment_city']  = '';
        $data['payment_postcode']  = '';
        $data['payment_zone'] = '';
        $data['payment_zone_id']  = '';
        $data['payment_country']  = '';
        $data['payment_country_id']  = '';
        $data['payment_address_format']  = '';
        $data['payment_method'] = '';
        $data['payment_code'] = '';
        $data['payment_lastname'] = '';

        $data['shipping_lastname'] = '';
        $data['shipping_company'] = '';
        $data['shipping_address_1'] = '';
        $data['shipping_address_2'] = '';
        $data['shipping_city'] = '';
        $data['shipping_postcode'] = '';
        $data['shipping_zone'] = '';
        $data['shipping_zone_id'] = '';
        $data['shipping_country'] = '';
        $data['shipping_country_id'] = '';
        $data['shipping_address_format'] = '';
        $data['shipping_code'] = '';


        if (isset($order['shipping_address']['name']) && !empty($order['shipping_address']['name'])) {
            $name = explode(" ", $order['shipping_address']['name']);
            $part = ceil(count($name) / 2);
            $data['firstname'] = ucfirst(strtolower(implode(' ', array_slice($name, 0, $part))));
            $data['lastname'] = ucfirst(strtolower(implode(' ', array_slice($name, $part, $part))));
        }

        if(!isset($data['email'])) {
            $data['email'] = "no email";
        }

        $data['fax'] = '';
        if(isset($order['shipping_address']['phone'])) {
            $data['telephone'] = $order['shipping_address']['phone'];
        } else {
            $data['telephone'] = '';
        }

        $data['fax'] = '';
        if(isset($order['shipping_address']['phone'])) {
            $data['telephone'] = $order['shipping_address']['phone'];
        } else {
            $data['telephone'] = '';
        }


        $data['payment_firstname'] = $data['firstname'];
        $data['shipping_firstname'] = $data['firstname'];

        $data['payment_lastname'] = $data['lastname'];
        $data['shipping_lastname'] = $data['lastname'];
        
        if(isset($order['shipping_address']['street1'])) {
            $data['payment_address_1'] = $order['shipping_address']['street1'];
            $data['shipping_address_1'] = $order['shipping_address']['street1'];
        }
        if(isset($order['shipping_address']['street2'])) {
            $data['payment_address_2'] = $order['shipping_address']['street2'];
            $data['shipping_address_2'] = $order['shipping_address']['street2'];
        }
        if(isset($order['shipping_address']['postal_code'])) {
            $data['payment_postcode'] = $order['shipping_address']['postal_code'];
            $data['shipping_postcode'] = $order['shipping_address']['postal_code'];
        }
        if(isset($order['shipping_address']['city_name'])) {
            $data['payment_city'] = $order['shipping_address']['city_name'];
            $data['shipping_city'] = $order['shipping_address']['city_name'];
        }
        if(isset($order['shipping_address']['state_or_province'])) {
            $data['payment_zone'] = $order['shipping_address']['state_or_province'];
            $data['shipping_zone'] = $order['shipping_address']['state_or_province'];
        }
        if(isset($order['shipping_address']['country'])) {
            $data['payment_country'] = $order['shipping_address']['country'];
            $data['shipping_country'] = $order['shipping_address']['country'];
        }

        $data['ebay_payment_method'] = '';
        if(isset($order['payment_method'])) {
            $data['payment_method'] = $order['payment_method'];
            $data['ebay_payment_method'] = $order['payment_method'];
        }

        $data['ebay_shipping_service'] = '';
        if(isset($order['shipping_service']['shipping_service'])) {
            $data['shipping_method'] = $order['shipping_service']['shipping_service'];
            $data['ebay_shipping_service'] = $order['shipping_service']['shipping_service'];
        }


        $data['ebay_address_owner'] = '';
        if(isset($order['shipping_address']['address_owner'])) {
            $data['ebay_address_owner'] = $order['shipping_address']['address_owner'];
        }

        $data['ebay_city_name'] = '';
        if(isset($order['shipping_address']['city_name'])) {
            $data['ebay_city_name'] = $order['shipping_address']['city_name'];
        }

        $data['ebay_country'] = '';
        $country_id = 0;
        if(isset($order['shipping_address']['country'])) {
            $data['ebay_country'] = $order['shipping_address']['country'];
            foreach ($countries as $country) {
                if ($country['iso_code_2'] == $order['shipping_address']['country'] || $country['iso_code_3'] == $order['shipping_address']['country']) {
                    $data['payment_country_id'] = $country['country_id'];
                    $data['shipping_country_id'] = $country['country_id'];
                    $country_id = $country['country_id'];
                    $data['payment_country'] =  $country['name'];
                    $data['shipping_country'] =  $country['name'];
                }
            }
        }

        $data['ebay_state_or_province'] = '';
        if(isset($order['shipping_address']['tate_or_province'])) {
            $data['ebay_state_or_province'] = $order['shipping_address']['tate_or_province'];
        }

        $data['ebay_amount_paid'] = 0;
        if(isset($order['amount_paid'])) {
            $data['ebay_amount_paid'] = $order['amount_paid'];
        }



        $product_data = array();
        $total_taxes = array();

        $products_total = 0;
        $products_total_net = 0;
        foreach ($order['transactions'] as $transaction) {
            if(isset($transaction['buyer']['email'])) {
                $data['email'] = $transaction['buyer']['email'];
            }

            $model = $transaction['item']['item_id'];
            $product_id = 0;
            if(isset($transaction['item']['sku'])) {
                $product = $this->model_ebay_channel_product_dao->getProductByEbaySku($transaction['item']['sku']);
                if(!empty($product)) {
                    $product_id = $product['product_id'];
                }
            }

            if(empty($product_id)) {
                $p2e = $this->model_ebay_channel_product_to_ebay_dao->getProductToEbayByItemId($transaction['item']['item_id']);
                if(!empty($p2e)) {
                    $product_id = $p2e['product_id'];
                }
            }

            if(empty($product_id)) {
                $product_id = $this->model_ebay_channel_product_dao->getProductIdByName($transaction['item']['title'], $settings['product_import_language_id']);
            }

            if(!empty($product_id)) {
                $product = $this->model_ebay_channel_product_dao->getProduct($product_id);
                if(!empty($product)) {
                    $model = $product['model'];
                    $product_id = $product['product_id'];
                }
            }


            $options = array();
            if(isset($transaction['variation']['sku'])) {
                $variationSku = $transaction['variation']['sku'];
                $variationOptions = explode("-", $variationSku);
                foreach ($variationOptions as $i => $value) {
                    if($i > 0) {
                        $options[] = $value;
                    }
                }
            }


            $quantity = $transaction['quantity'];
            $price = $transaction['transaction_price'];
            $tax_total = 0;
            if(!empty($settings['order_tax_class_id']) && !empty($country_id)) {
                $tax_rate_data = $this->model_ebay_channel_tax_dao->extractTaxes($price, $settings['order_tax_class_id'], $country_id);
                foreach ($tax_rate_data as $id => $tax) {
                    $tax_total += $tax['amount'];
                    if(isset($total_taxes[$id])) {
                        $total_taxes[$id]['total'] += sprintf('%.4f', $tax['amount'] * $quantity);
                    } else {
                        $total_taxes[$id] = array("name" => $tax['name'], "total" => sprintf('%.4f', $tax['amount'] * $quantity));
                    }
                }
            }

            $price_net = $price - $tax_total;
            $product_total = $price * $quantity;
            $product_total_net = $price_net * $quantity;

            $products_total += $product_total;
            $products_total_net += $product_total_net;

            $product_data[] = array(
                'product_id' => $product_id,
                'name'       => $transaction['item']['title'],
                'model'      => $model,
                'options'     => $options,
                'quantity'   => $transaction['quantity'],
                'subtract'   => '',
                'price'      => $price_net,
                'total'      =>  sprintf('%.4f', $product_total_net),
                'ebay_item_id' => $transaction['item']['item_id'],
                'ebay_transaction_id' => $transaction['transaction_id'],
                'ebay_order_line_item_id' => $transaction['order_line_item_id'],
                'ebay_shipment_tracking_number' =>(isset($transaction['shipment_tracking_number'])? $transaction['shipment_tracking_number'] : '') ,
                'ebay_paid_time' =>(isset($transaction['paid_time'])? $transaction['paid_time'] : null) ,
                'tax'        => $tax_total,
                'reward'     => ''
            );
        }

        $data['products'] = $product_data;



        if(isset($order['buyer_checkout_message'])) {
            $data['comment'] = $order['buyer_checkout_message'];
        } else {
            $data['comment'] = '';
        }

        $data['date_added'] = $order['created_time'];
        $data['ebay_order_id'] = $order['id'];

        $data['ebay_buyer_user_id'] =  $order['buyer_user_id'];
        $data['ebay_order_status'] =  $order['order_status'];


        $data['ebay_paid_time'] = null;
        if(isset($order['paid_time'])) {
            $data['ebay_paid_time'] = $order['paid_time'];
        }

        $data['ebay_shipped_time'] = null;
        if(isset($order['shipped_time'])) {
            $data['ebay_shipped_time'] = $order['shipped_time'];
        }

        $data['ebay_buyer_checkout_message'] = '';
        if(isset($order['buyer_checkout_message'])) {
            $data['ebay_buyer_checkout_message'] = $order['buyer_checkout_message'];
        }




        $data['total'] = $order['total'];
        $data['totals'] = array();
        $data['totals'][] = array('code' => 'sub_total', 'title'=> 'Sub-Total', 'value'=> $products_total_net, 'sort_order'=> 1);


        foreach ($total_taxes as $total_tax) {
            $data['totals'][] = array('code' => 'tax', 'title'=> $total_tax['name'], 'value'=> $total_tax['total'], 'sort_order'=> 2);
        }



        if(isset($order['shipping_service']['shipping_service_cost'])) {
            $data['totals'][] = array('code' => 'shipping', 'title'=> $data['shipping_method'] . ' - cost', 'value'=>$order['shipping_service']['shipping_service_cost'], 'sort_order'=> 3);
        }

        if(isset($order['shipping_service']['shipping_service_additional_cost'])) {
            $data['totals'][] = array('code' => 'shipping', 'title'=> $data['shipping_method'] . ' - additional cost', 'value'=>$order['shipping_service']['shipping_service_additional_cost'], 'sort_order'=> 4);
        }

        $data['totals'][] = array('code' => 'total', 'title'=> 'Total', 'value'=>$order['total'], 'sort_order'=> 5);

        //add customer
        if($settings["customer_import_enabled"]) {
            $password = uniqid();
            $salt = substr(md5(uniqid(rand(), true)), 0, 9);
            $customer = array(
                "customer_group_id" => $data['customer_group_id'],
                "store_id" => $data['store_id'],
                "firstname" => $data['firstname'],
                "lastname" => $data['lastname'],
                "email" => $data['email'],
                "telephone" => $data['telephone'],
                "fax" => $data['fax'],
                "password" => sha1($salt . sha1($salt . sha1($password))),
                "salt" => $salt,
                "status" => '0',
                "address" =>array(
                    "firstname" => $data['firstname'],
                    "lastname" => $data['lastname'],
                    "address_1" => $data['payment_address_1'],
                    "address_2" => $data['payment_address_2'],
                    "city" => $data['payment_city'],
                    "postcode" => $data['payment_postcode'],
                    "country_id" => $data['payment_country_id']
                )
            );

            $data['customer_id'] = $this->model_ebay_channel_customer_dao->save($customer);
        }
        
        $this->model_ebay_channel_order_dao->updateOrInsert($data, $settings);
        
        if($processId != null) {
            $this->model_ebay_channel_process_dao->updateProcessed($processId);
        }
        return true;
    }



    private function getProductSite($product, $account) {
        $this->load->model('ebay_channel/dao');
        if(isset($product['site'])) {
            $ebaySite = $this->model_ebay_channel_dao->getEbaySiteByCode($product['site']);
            if($ebaySite) {
                $cc = $this->model_ebay_channel_dao->getCategoriesCountBySiteId($ebaySite['id']);
                if(empty($cc)) {
                    $this->importCategories($account, $ebaySite['id']);
                }
                return $ebaySite;
            } else {
                $this->model_ebay_channel_log_dao->addWarningLog("Import", "Site Id not found " . $product['site'], 7);
            }

        } else {
            $this->model_ebay_channel_log_dao->addWarningLog("Import", "Site Id not defined " . $product['item_id'], 7);
        }
        return false;
    }

    public function importCategories($account, $siteId) {
        $this->load->model('ebay_channel/api');
        $this->load->model('ebay_channel/dao');
        $categories = $this->model_ebay_channel_api->getCategoriesCall($account, $siteId)->getCategories(array("DetailLevel" => "ReturnAll"));
        $this->model_ebay_channel_dao->addEbayCategories($categories, $siteId);
        $this->model_ebay_channel_dao->removeEbayItemSpecificBySiteId($siteId);
        $this->importSiteDetails($account, $siteId);
    }

    public function importSiteDetails($account, $siteId) {
        $this->load->model('ebay_channel/api');
        $this->load->model('ebay_channel/dao');

        $details = array('ShippingCarrierDetails',
            'ShippingPackageDetails',
            'ShippingLocationDetails',
            'ShippingServiceDetails',
            'DispatchTimeMaxDetails',
            'ProductDetails',
            'CountryDetails', 'ReturnPolicyDetails');

        $eBayDetails = $this->model_ebay_channel_api->geteBayDetailsCall($account, $siteId)->getDetails($details);
        foreach($eBayDetails as $name=>$value) {
            $json = json_encode($value);
            $this->db->query("INSERT INTO " . DB_PREFIX . "channel_ebay_details (`name`, `value`, `site_id`) VALUES('" . $this->db->escape($name)
                . "', '" . $this->db->escape($json) . "', ". (int)$siteId .") ON DUPLICATE KEY UPDATE `value` = '" . $this->db->escape($json) . "'");
        }


        if(isset($eBayDetails['CountryDetails'])) {
            $this->model_ebay_channel_dao->addEbayCountries($eBayDetails['CountryDetails'], $siteId);
        }

        if(isset($eBayDetails['ShippingLocationDetails'])) {
            $this->model_ebay_channel_dao->addEbayShippingToLocations($eBayDetails['ShippingLocationDetails'], $siteId);
        }

        if(isset($eBayDetails['ShippingCarrierDetails'])) {
            $this->model_ebay_channel_dao->addEbayShippingCarriers($eBayDetails['ShippingCarrierDetails'], $siteId);
        }

        if(isset($eBayDetails['ShippingPackageDetails'])) {
            $this->model_ebay_channel_dao->addEbayShippingPackages($eBayDetails['ShippingPackageDetails'], $siteId);
        }

        if(isset($eBayDetails['ShippingServiceDetails'])) {
            $this->model_ebay_channel_dao->addEbayShippingServices($eBayDetails['ShippingServiceDetails'], $siteId);
        }

        if(isset($eBayDetails['DispatchTimeMaxDetails'])) {
            $this->model_ebay_channel_dao->addEbayDispatchTimeMax($eBayDetails['DispatchTimeMaxDetails'], $siteId);
        }

        if(isset($eBayDetails['ReturnPolicyDetails'])) {
            if(isset($eBayDetails['ReturnPolicyDetails']['Refunds'])) {
                $this->model_ebay_channel_dao->addEbayRefunds($eBayDetails['ReturnPolicyDetails']['Refunds'], $siteId);
            }

            if(isset($eBayDetails['ReturnPolicyDetails']['ReturnsAccepted'])) {
                $this->model_ebay_channel_dao->addEbayReturnsAccepted($eBayDetails['ReturnPolicyDetails']['ReturnsAccepted'], $siteId);
            }

            if(isset($eBayDetails['ReturnPolicyDetails']['ReturnsWithin'])) {
                $this->model_ebay_channel_dao->addEbayReturnWithin($eBayDetails['ReturnPolicyDetails']['ReturnsWithin'], $siteId);
            }

            if(isset($eBayDetails['ReturnPolicyDetails']['ShippingCostPaidBy'])) {
                $this->model_ebay_channel_dao->addEbayShippingCostPaidBy($eBayDetails['ReturnPolicyDetails']['ShippingCostPaidBy'], $siteId);
            }
        }
    }

    public function importStoreCategories($account) {
        $this->load->model('ebay_channel/api');
        $this->load->model('ebay_channel/product_dao');
        $this->load->model('ebay_channel/dao');
        $r = $this->model_ebay_channel_api->getGetStoreCall($account, $account['default_site'])->getStore(array('CategoryStructureOnly' => true));
        if(isset($r['categories'])) {
            $this->model_ebay_channel_product_dao->addEbayStoreCategory($r['categories'], 0, 1);
        }
    }

    public function importFeedback($feedbacks, $processId = null) {
        $this->load->model('ebay_channel/feedback_dao');
        $this->load->model('ebay_channel/process_dao');

        $count = 0;
        foreach($feedbacks as $feedback) {
            $r = $this->model_ebay_channel_feedback_dao->addFeedBack($feedback);
            if($r) {
                $count++;
            }

            if($processId != null) {
                $this->model_ebay_channel_process_dao->updateProcessed($processId);
            }
        }
        return $count;
    }

}
?>