<?php

class ModelEbayChannelProductDao extends Model {



    public function insert($data, $settings = array()) {

        if(isset($settings['import_new_products']) && !$settings['import_new_products']) {

            return false;

        }



        $this->db->query("INSERT INTO " . DB_PREFIX

            . "product SET quantity = '" . ((isset($data['quantity']))? $this->db->escape($data['quantity']) : '0' )

            . "', sku = '" . ((isset($data['sku']) && !empty($data['sku']))? $this->db->escape($data['sku']) : '' )

            . "', model = '" . ((isset($data['sku']) && !empty($data['sku']))? $this->db->escape($data['sku']) : $data['item_id'] )

            . "', minimum = '1"

            . "', subtract = '1"

            . "', stock_status_id = '7"

            . "', date_available = 'NOW()"

            . "', manufacturer_id = '0"

            . "', shipping = '1"

            . (($data['enabled_openstock'])? "', has_option = '" . $data['has_option'] : '')

            . "', price = '" . ((isset($data['start_price']))? $this->db->escape($data['start_price']) : '0' )

            . "', points = '0"

            . "', weight = '" . $data['weight']

            . "', weight_class_id = '" . $data['weight_class_id']

            . "', length = '" . $data['length']

            . "', width = '" . $data['width']

            . "', height = '" . $data['height']

            . "', length_class_id = '" . $data['length_class_id']

            . "', status = '1"

            . "', tax_class_id = '1"

            . "', sort_order = '0"

            . "', date_added = NOW()");



        $product_id = $this->db->getLastId();



        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '0'");



        if(isset($data['category_id'])) {

            $dbCategory = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category  WHERE ebay_id = '" . $this->db->escape($data['category_id']) . "'")->row;

            if(!empty($dbCategory)) {

                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$dbCategory['category_id'] . "'");

            }

        }



        if(isset($data['store_category_id'])) {

            $dbCategory = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category  WHERE ebay_store_id = '" . $this->db->escape($data['store_category_id']) . "'")->row;

            if(!empty($dbCategory)) {

                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$dbCategory['category_id'] . "'");

            } else {

                $mapping = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "channel_ebay_category_mapping  WHERE ebay_store_category_id = '" . $this->db->escape($data['store_category_id']) . "'")->row;

                if(!empty($mapping)) {

                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$mapping['category_id'] . "'");

                }

            }

        }



        if(isset($data['store_category2_id'])) {

            $dbCategory = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category  WHERE ebay_store_id = '" . $this->db->escape($data['store_category2_id']) . "'")->row;

            if(!empty($dbCategory)) {

                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$dbCategory['category_id'] . "'");

            } else {

                $mapping = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "channel_ebay_category_mapping  WHERE ebay_store_category_id = '" . $this->db->escape($data['store_category2_id']) . "'")->row;

                if(!empty($mapping)) {

                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$mapping['category_id'] . "'");

                }

            }

        }



        $languageIds = $this->db->query("SELECT language_id FROM ".DB_PREFIX."language")->rows;

        foreach ($languageIds as $language) {

            $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language['language_id']

                . "', name = '" . $this->db->escape($data['title'])

                . "', meta_title = '" . $this->db->escape($data['title'])

                . "', description = '" . ((isset($data['description']))? $this->db->escape($data['description']) : '' )

                . "'");

        }



        if (isset($data['pictures'])) {

            $i = 0;

            foreach ($data['pictures'] as $picture) {

                $image = $this->downloadProductImage($picture);

                if(!empty($image)) {

                    if($i < 1) {

                        $this->db->query("UPDATE " . DB_PREFIX . "product SET image = '". $this->db->escape($image) ."' WHERE product_id = '" . (int)$product_id . "'");

                    } else {

                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($image, ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$i . "'");

                    }



                    $i++;

                }

            }

        }





        // add image update in query





        $this->updateItemDetails($data, $product_id);



        $this->cache->delete('product');

        return $product_id;

    }



    public function update($product_id, $data, $settings = array()) {


        if($settings['update_only_stock_and_price'] || $settings['update_only_stock'] || $settings['update_only_price']) {
            $stock = ((isset($data['quantity']))? $data['quantity'] : 0);

            $price = ((isset($data['start_price']))? $data['start_price'] : '0' );

            if($settings['update_only_stock']) {

                $this->updateStockAndPrice($product_id, false, $stock);

            } else if($settings['update_only_price']) {

                $this->updateStockAndPrice($product_id, $price, false);

            } else if($settings['update_only_stock_and_price']) {

                $this->updateStockAndPrice($product_id, $price, $stock);

            }

        } else { //Update All fields

            $this->db->query("UPDATE " . DB_PREFIX

                . "product SET quantity = '" . ((isset($data['quantity']))? $this->db->escape($data['quantity']) : '0' )

                . "', sku = '" . ((isset($data['sku']) && !empty($data['sku']))? $this->db->escape($data['sku']) : '' )

                . "', model = '" . ((isset($data['sku']) && !empty($data['sku']))? $this->db->escape($data['sku']) :  $data['item_id'] )

                . "', manufacturer_id = '0"

                . (($data['enabled_openstock'])? "', has_option = '" . $data['has_option'] : '')

                . "', price = '" . ((isset($data['start_price']))? $this->db->escape($data['start_price']) : '0' )

                . "', status = '1"

                . "', date_modified = NOW() WHERE product_id='" . $this->db->escape($product_id) . "'");



            $languageIds = $this->db->query("SELECT language_id FROM ".DB_PREFIX."language")->rows;

            foreach ($languageIds as $language) {

                $this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language['language_id']. "'");

                $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language['language_id']

                    . "', name = '" . $this->db->escape($data['title'])

                    . "', meta_title = '" . $this->db->escape($data['title'])

                    . "', description = '" . ((isset($data['description']))? $this->db->escape($data['description']) : '' )

                    . "'");

            }



            $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");



            if(isset($data['category_id'])) {

                $dbCategory = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category  WHERE ebay_id = '" . $this->db->escape($data['category_id']) . "'")->row;

                if(!empty($dbCategory)) {

                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$dbCategory['category_id'] . "'");

                }

            }





            if(isset($data['store_category_id'])) {

                $dbCategory = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category  WHERE ebay_store_id = '" . $this->db->escape($data['store_category_id']) . "'")->row;

                if(!empty($dbCategory)) {

                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$dbCategory['category_id'] . "'");

                } else {

                    $mapping = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "channel_ebay_category_mapping  WHERE ebay_store_category_id = '" . $this->db->escape($data['store_category_id']) . "'")->row;

                    if(!empty($mapping)) {

                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$mapping['category_id'] . "'");

                    }

                }

            }



            if(isset($data['store_category2_id'])) {

                $dbCategory = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category  WHERE ebay_store_id = '" . $this->db->escape($data['store_category2_id']) . "'")->row;

                if(!empty($dbCategory)) {

                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$dbCategory['category_id'] . "'");

                } else {

                    $mapping = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "channel_ebay_category_mapping  WHERE ebay_store_category_id = '" . $this->db->escape($data['store_category2_id']) . "'")->row;

                    if(!empty($mapping)) {

                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$mapping['category_id'] . "'");

                    }

                }

            }



            if (isset($data['pictures'])) {

                $i = 0;



                $dbImages = $this->db->query("select image from " . DB_PREFIX . "product where product_id = '" . (int)$product_id . "' UNION select image from " . DB_PREFIX . "product_image where product_id = '" . (int)$product_id . "'")->rows;



                $this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");



                foreach ($data['pictures'] as $picture) {

                    $image = $this->downloadProductImage($picture);

                    if(!empty($image)) {

                        if($i < 1) {

                            $this->db->query("UPDATE " . DB_PREFIX . "product SET image = '". $this->db->escape($image) ."' WHERE product_id = '" . (int)$product_id . "'");

                        } else {

                            $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($image, ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$i . "'");

                        }

                        $i++;

                    }

                }



                foreach ($dbImages as $dbImage) {

                    if(!empty($dbImage['image'])) {

                        $imgPath = DIR_IMAGE . $dbImage['image'];

                        if (file_exists($imgPath)) {

                            unlink($imgPath);

                        }

                    }

                }

            }

        }



        $this->updateItemDetails($data, $product_id);

    }



    public function checkIfExistsOpenStock() {

        $table = $this->db->query("SHOW TABLES LIKE  '" . DB_PREFIX . "product_option_variant'")->row;

        return !empty($table);

    }



    public function insertOrUpdateLink($product_id, $data, $settings = array()) {

        $this->updateItemDetails($data, $product_id);

    }



    public function updateStockAndPrice($product_id, $price=false, $qty=false) {

        if($price && $qty) {

            $this->db->query("UPDATE " . DB_PREFIX

                . "product SET quantity = '" . $qty

                . "', price = '" . $price

                . "', date_modified = NOW() WHERE product_id='" . $this->db->escape($product_id) . "'");

        } else if($price) {

            $this->db->query("UPDATE " . DB_PREFIX

                . "product SET price = '" . $price

                . "', date_modified = NOW() WHERE product_id='" . $this->db->escape($product_id) . "'");

        } else if($qty) {

            $this->db->query("UPDATE " . DB_PREFIX

                . "product SET quantity = '" . $qty

                . "', date_modified = NOW() WHERE product_id='" . $this->db->escape($product_id) . "'");

        }

    }



    public function updateStock($product_id, $qty) {

        $this->db->query("UPDATE " . DB_PREFIX

            . "product SET quantity = '" . $qty

            . "', date_modified = NOW() WHERE product_id='" . $this->db->escape($product_id) . "'");

    }





    public function updateItemDetails($data, $product_id) {

        if(isset($data['listing_details']['start_time']) && isset($data['listing_details']['end_time'])) {

            $row =  $this->db->query("SELECT * FROM " . DB_PREFIX . "channel_ebay_product WHERE product_id = '" . $this->db->escape($product_id) . "'");

            if(empty($row)) {

                $this->db->query("INSERT INTO " . DB_PREFIX . "channel_ebay_product SET "

                    . "   `product_id` = '" . $this->db->escape($product_id)

                    . "', `ebay_id` = '" .  $this->db->escape($data['item_id'])

                    . "', `site_id` = '" . $this->db->escape($data['site'])

                    . "', `listing_mode` = '" . $this->db->escape($data['listing_mode'])

                    . "', `start_time` = '" . $this->db->escape($data['listing_details']['start_time'])

                    . "', `end_time` = '" .   $this->db->escape($data['listing_details']['end_time'])

                    . "', `qty` = '" .   $this->db->escape($data['quantity'])

                    . "', `price` = '" . ((isset($data['start_price']))? $this->db->escape($data['start_price']) : '0')

                    . "'"

                );

            } else {

                $this->db->query("UPDATE " . DB_PREFIX . "channel_ebay_product SET "

                    . "   `ebay_id` = '" .  $this->db->escape($data['item_id'])

                    . "', `site_id` = '" . $this->db->escape($data['site'])

                    . "', `listing_mode` = '" . $this->db->escape($data['listing_mode'])

                    . "', `start_time` = '" . $this->db->escape($data['listing_details']['start_time'])

                    . "', `end_time` = '" .   $this->db->escape($data['listing_details']['end_time'])

                    . "', `qty` = '" .   $this->db->escape($data['quantity'])

                    . "', `price` = '" . ((isset($data['start_price']))? $this->db->escape($data['start_price']) : '0')

                    . "' WHERE product_id = '" . $this->db->escape($product_id) . "'"

                );

            }

        }

    }



    public function disableProductById($product_id) {

        $this->db->query("UPDATE " . DB_PREFIX . "product SET status = '0', date_modified = NOW() WHERE product_id='" . $this->db->escape($product_id) . "'");

    }



    public function updateProductEbayLink($product_id, $data) {

        if(isset($data['listing_details']['start_time']) && isset($data['listing_details']['end_time'])) {

            $this->db->query("UPDATE `" . DB_PREFIX . "channel_ebay_product` SET "

                . "   `ebay_id` = '" .  $this->db->escape($data['item_id'])

                . "', `site_id` = '" . $this->db->escape($data['site'])

                . "', `listing_mode` = '" . $this->db->escape($data['listing_mode'])

                . "', `start_time` = '" . $this->db->escape($data['listing_details']['start_time'])

                . "', `end_time` = '" .   $this->db->escape($data['listing_details']['end_time'])

                . "', `price` = '" . ((isset($data['start_price']))? $this->db->escape($data['start_price']) : '0')

                . "' where product_id = '" . $this->db->escape($product_id) . "'"

            );

        }

    }



    public function addProductToCategory($product_id, $data) {

        if(isset($data['category_id'])) {

            $dbCategory = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category  WHERE ebay_id = '" . $this->db->escape($data['category_id']) . "'")->row;

            if(!empty($dbCategory)) {

                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$dbCategory['category_id'] . "'");

            }

        }

    }



    public function getOptionByName($name) {

        $option = $this->db->query("SELECT option_id FROM `" . DB_PREFIX . "option_description`  WHERE `name` = '" . $this->db->escape($name) . "' LIMIT 1")->row;

        if(!empty($option)) {

            return $option['option_id'];

        }

        return null;

    }



    public function addOrUpdateOption($name) {

        $optionId = $this->getOptionByName($name);

        if($optionId == null) {

            $this->db->query("INSERT INTO `" . DB_PREFIX . "option` SET type = 'select', sort_order = '1'");

            $optionId = $this->db->getLastId();

            $languageIds = $this->db->query("SELECT language_id FROM ".DB_PREFIX."language")->rows;

            foreach ($languageIds as $language) {

                $this->db->query("INSERT INTO `" . DB_PREFIX . "option_description` SET language_id = '". $this->db->escape((int)$language['language_id'])

                    ."', name = '" . $this->db->escape($name) . "', option_id = '" . $this->db->escape($optionId) . "'");

            }

        }

        return $optionId;

    }



    public function getOptionValueByName($value, $optionId) {

        $option = $this->db->query("SELECT option_value_id FROM `" . DB_PREFIX . "option_value_description`  WHERE `name` = '" . $this->db->escape($value)

            . "' AND option_id = '" . $this->db->escape($optionId) . "' LIMIT 1")->row;

        if(!empty($option)) {

            return $option['option_value_id'];

        }

        return null;

    }



    public function addOrUpdateOptionValue($value, $optionId) {

        $optionValueId = $this->getOptionValueByName($value, $optionId);

        if($optionValueId == null) {

            $this->db->query("INSERT INTO `" . DB_PREFIX . "option_value` SET option_id = '" . $this->db->escape($optionId) . "', sort_order = '1'");

            $optionValueId = $this->db->getLastId();

            $languageIds = $this->db->query("SELECT language_id FROM ".DB_PREFIX."language")->rows;

            foreach ($languageIds as $language) {

                $this->db->query("INSERT INTO `" . DB_PREFIX . "option_value_description` SET language_id = '". $this->db->escape((int)$language['language_id'])

                    ."', name = '" . $this->db->escape($value) . "', option_value_id = '" . $this->db->escape($optionValueId) . "', option_id = '" . $this->db->escape($optionId) . "'");

            }

        }

        return $optionValueId;

    }





    public function clearProductOption($productId) {

        $this->db->query("DELETE FROM ".DB_PREFIX."product_option WHERE `product_id` = '" . (int)$productId . "'");

        $this->db->query("DELETE FROM ".DB_PREFIX."product_option_value WHERE `product_id` = '" . (int)$productId . "'");

    }



    public function addProductOption($productId, $optionId) {

        $this->db->query("INSERT INTO `" . DB_PREFIX . "product_option` SET product_id = '" . $this->db->escape($productId)

            . "', option_id = '" . $this->db->escape($optionId)

            . "', required = '1'");

        return $this->db->getLastId();

    }



    public function addProductOptionValue($productId, $optionValue) {

        $this->db->query("INSERT INTO `" . DB_PREFIX . "product_option_value` SET product_id = '" . $this->db->escape($productId)

            . "', option_id = '" . $this->db->escape($optionValue['option_id'])

            . "', option_value_id = '" . $this->db->escape($optionValue['option_value_id'])

            . "', quantity = '" . $this->db->escape($optionValue['quantity'])

            . "', price = '" . $this->db->escape($optionValue['price'])

            . "', price_prefix = '" . $this->db->escape($optionValue['price_prefix'])

            . "', product_option_id = '" . $this->db->escape($optionValue['product_option_id'])

            . "'");

        return $this->db->getLastId();

    }



    //----Openstock-------------

    public function clearProductOptionVariant($productId) {

        $this->db->query("DELETE FROM ".DB_PREFIX."product_option_variant WHERE `product_id` = '" . (int)$productId . "'");

        $this->db->query("DELETE FROM ".DB_PREFIX."product_option_variant_value WHERE `product_id` = '" . (int)$productId . "'");

    }

    

    public function addProductOptionVariant($data, $productId) {

        $this->db->query("INSERT INTO `" . DB_PREFIX . "product_option_variant` SET product_id = '" . $this->db->escape($productId)

            . "', sku = '" . $this->db->escape($data['sku'])

            . "', stock = '" . $this->db->escape($data['stock'])

            . "', active = '" . $this->db->escape($data['active'])

            . "', subtract = '" . $this->db->escape($data['subtract'])

            . "', price = '" . $this->db->escape($data['price'])

            . "', weight = '" . $this->db->escape($data['weight'])

            . "'");

        return $this->db->getLastId();

    }



    public function addProductOptionVariantValue($data, $productId) {

        $this->db->query("INSERT INTO `" . DB_PREFIX . "product_option_variant_value` SET product_id = '" . $this->db->escape($productId)

            . "', product_option_variant_id = '" . $this->db->escape($data['product_option_variant_id'])

            . "', product_option_value_id = '" . $this->db->escape($data['product_option_value_id'])

            . "', sort_order = '" . $this->db->escape($data['sort_order'])

            . "'");

        return $this->db->getLastId();

    }









    public function addCategoryAndLinkProduct($product_id, $data) {

        if(isset($data['category_id'])) {

            $dbCategory = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category  WHERE ebay_id = '" . $this->db->escape($data['category_id']) . "'")->row;

            $category_id = 0;

            if(empty($dbCategory)) {



                $this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '0', ebay_id = '" . (int)$data['category_id'] . "', ebay_parent_id = '" . (int)$data['category_id'] . "', `top` = ' " . $top . "', `column` = '0', sort_order = '0', status = '1', date_modified = NOW(), date_added = NOW()");



                $category_id = $this->db->getLastId();



                $languageIds = $this->db->query("SELECT language_id FROM ".DB_PREFIX."language")->rows;

                foreach ($languageIds as $language) {

                    $this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape($data['category_name']) . "', meta_keyword = '', meta_description = '', description = ''");

                }



                $level = 0;

                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$parentId . "' ORDER BY `level` ASC");

                foreach ($query->rows as $result) {

                    $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

                    $level++;

                }



                $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");

                $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '0'");

                $this->cache->delete('category');

            } else {

                $category_id = $dbCategory['category_id'];

            }



            $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND category_id = '" . (int)$category_id . "'");

            $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");

        }

    }



    public function addCategory($category, $parent, $top = 0) {

        $dbCategory = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category  WHERE ebay_id = '" . $this->db->escape($category['id']) . "'")->row;

        $category_id = 0;

        if(empty($dbCategory)) {

            $this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '" . (int)$parent . "', ebay_id = '" . (int)$category['id'] . "', ebay_parent_id = '" . (int)$category['parent_id'] . "', `top` = ' " . $top . "', `column` = '0', sort_order = '0', status = '1', date_modified = NOW(), date_added = NOW()");

            $category_id = $this->db->getLastId();



            $languageIds = $this->db->query("SELECT language_id FROM ".DB_PREFIX."language")->rows;

            foreach ($languageIds as $language) {

                $this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape($category['name']) . "', meta_keyword = '', meta_description = '', description = ''");

            }



            $level = 0;

            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$parent . "' ORDER BY `level` ASC");

            foreach ($query->rows as $result) {

                $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

                $level++;

            }



            $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");

            $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '0'");

            $this->cache->delete('category');

        } else {

            $category_id = $dbCategory['category_id'];

        }

        return $category_id;

    }



    public function addEbayStoreCategory($categories, $parentId = 0, $top = 1) {

        foreach ($categories as $category) {

            $category_id = 0;

            $dbCategory = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category  WHERE ebay_store_id = '" . $this->db->escape($category['category_id']) . "'")->row;

            if(empty($dbCategory)) {

                $this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '" . (int)$parentId . "', ebay_store_id = '" . $this->db->escape($category['category_id']) . "', `top` = ' " . $top . "', `column` = '0', sort_order = '0', status = '1', date_modified = NOW(), date_added = NOW()");

                $category_id = $this->db->getLastId();



                $languageIds = $this->db->query("SELECT language_id FROM ".DB_PREFIX."language")->rows;

                foreach ($languageIds as $language) {

                    $this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape($category['name']) . "', meta_keyword = '', meta_description = '', description = ''");

                }



                $l = 0;

                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$parentId . "' ORDER BY `level` ASC");

                foreach ($query->rows as $result) {

                    $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$l . "'");

                    $l++;

                }

                $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$l . "'");

                $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '0'");

                $this->cache->delete('category');

            } else {

                $category_id = $dbCategory['category_id'];

            }



            if(!empty($category['child_category'])) {

                $this->addEbayStoreCategory($category['child_category'], $category_id, 0);

            }

        }

    }



    public function downloadProductImage($picUrl) {



        if (strpos($picUrl,'_12.JPG') !== false) {

            $picUrl = str_replace("_12.JPG","_57.JPG", $picUrl);

        }



        if (strpos($picUrl,'_1.JPG') !== false) {

            $picUrl = str_replace("_1.JPG","_57.JPG", $picUrl);

        }



        $name = uniqid() . basename(parse_url($picUrl, PHP_URL_PATH));

        $img = DIR_IMAGE.'data/product/' .  $name;

        if(file_exists($img)) {

            return 'data/product/'. $name;

        } else {

            // get image content

            $c = curl_init();

            curl_setopt($c, CURLOPT_HEADER, 0);

            curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($c, CURLOPT_TIMEOUT, 45);

            curl_setopt($c, CURLOPT_URL, $picUrl ); // url

            $g = curl_exec($c);

            $t = curl_getinfo($c, CURLINFO_CONTENT_TYPE);

            $z = curl_getinfo($c, CURLINFO_HTTP_CODE);

            curl_close($c);

            // 		$m = array('image/jpeg'=>'jpg','image/png'=>'png','image/gif'=>'gif');

            $name = uniqid() . basename(parse_url($picUrl, PHP_URL_PATH));

            if( $z == 200 ) {

                // 			$n = $productId.'.'.$m[$t];

                $img = DIR_IMAGE.'data/product/' .  $name;

                file_put_contents($img, $g);

                if(file_exists($img)) {

                    return 'data/product/'. $name;

                }

            }

        }



        return null;

    }



    public function getAttributeGroupByName($name) {

        $a = $this->db->query("SELECT attribute_group_id FROM `" . DB_PREFIX . "attribute_group_description`  WHERE `name` = '" . $this->db->escape($name) . "' LIMIT 1")->row;

        if(!empty($a)) {

            return $a['attribute_group_id'];

        }

        return null;

    }



    public function getAttributeByName($name) {

        $a = $this->db->query("SELECT attribute_id FROM `" . DB_PREFIX . "attribute_description`  WHERE `name` = '" . $this->db->escape($name) . "' LIMIT 1")->row;

        if(!empty($a)) {

            return $a['attribute_id'];

        }

        return null;

    }



    public function addOrUpdateAttributeGroup($name) {

        $id = $this->getAttributeGroupByName($name);

        if($id == null) {

            $this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_group` SET sort_order = '7'");

            $id = $this->db->getLastId();

            $languageIds = $this->db->query("SELECT language_id FROM ".DB_PREFIX."language")->rows;

            foreach ($languageIds as $language) {

                $this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_group_description` SET language_id = '". $this->db->escape((int)$language['language_id'])

                    ."', name = '" . $this->db->escape($name) . "', attribute_group_id='" . (int)$id . "'");

            }

        }

        return $id;

    }



    public function addOrUpdateAttribute($name, $attributeGroupId) {

        $id = $this->getAttributeByName($name);

        if($id == null) {

            $this->db->query("INSERT INTO `" . DB_PREFIX . "attribute` SET sort_order = '7', attribute_group_id=" . (int)$attributeGroupId);

            $id = $this->db->getLastId();

            $languageIds = $this->db->query("SELECT language_id FROM ".DB_PREFIX."language")->rows;

            foreach ($languageIds as $language) {

                $this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_description` SET language_id = '". $this->db->escape((int)$language['language_id'])

                    ."', name = '" . $this->db->escape($name) . "', attribute_id='" . (int)$id . "'");

            }

        }

        return $id;

    }



    public function clearProductAttributes($productId) {

        $this->db->query("DELETE FROM ".DB_PREFIX."product_attribute WHERE `product_id` = '" . (int)$productId . "'");

    }



    public function addAttributeToProduct($productId, $attributeId, $value) {

        $languageIds = $this->db->query("SELECT language_id FROM ".DB_PREFIX."language")->rows;

        foreach ($languageIds as $language) {

            $this->db->query("INSERT INTO `" . DB_PREFIX . "product_attribute` SET product_id = '" . $this->db->escape($productId)

                . "', language_id = '" . $this->db->escape((int)$language['language_id'])

                . "', attribute_id = '" . $this->db->escape($attributeId)

                . "', text = '" . $this->db->escape($value)	. "' ON DUPLICATE KEY UPDATE text = '" . $this->db->escape($value) . "'");

        }

    }





    public function getProductImages($product_id) {

        return $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'")->rows;

    }



    public function getManufacturers() {

        return $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer order by name asc")->rows;

    }



    public function getDbCategoriesTreeView($parent_id) {

        $data = array();

        $categories = $this->db->query("select c.category_id as id, c.parent_id, cd.name from `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd on cd.category_id = c.category_id

				  where parent_id=". $this->db->escape($parent_id) . " and cd.language_id =" . $this->config->get('config_language_id'))->rows;

        $ids = '';

        foreach($categories as $category) {

            $ids .= $category['id'] . ",";

        }

        $ids[strlen($ids)-1] = ' ';





        $categoriesCounts = $this->db->query("select parent_id, count(*) as cnt from `" . DB_PREFIX . "category` where parent_id in (" . $ids . ") and parent_id<>category_id group by parent_id")->rows;



        foreach($categories as $key => $category){

            $categories[$key]['childs_count'] = 0;

            foreach($categoriesCounts as $categoryCount) {

                if($categoryCount['parent_id'] == $category['id']) {

                    $categories[$key]['childs_count'] = $categoryCount['cnt'];

                }

            }

        }



        foreach($categories as $category) {

            $hasChilds = $category['childs_count'] > 0;

            $data[] = array('title'=> $category['name'], 'key' => $category['id'], 'isFolder'=> $hasChilds, 'isLazy'=> $hasChilds);

        }



        return $data;

    }



    public function getProduct($productId) {

        return $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` p where p.product_id = ". $this->db->escape($productId))->row;

    }



    public function getProductByEbaySku($sku) {

        $prefix = 'EC-';

        if (substr($sku, 0, strlen($prefix)) == $prefix) {

            $productId = substr($sku, strlen($prefix));

            return $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` p where p.product_id = ". $this->db->escape($productId))->row;

        }

        return null;

    }





    public function getProductIdBySku($sku) {

        return $this->db->query("SELECT p.product_id FROM `" . DB_PREFIX . "product` p where p.sku = '". $this->db->escape($sku) . "'")->row;

    }



    public function getProductIdById($id) {

        return $this->db->query("SELECT p.product_id FROM `" . DB_PREFIX . "product` p where p.product_id = ". $this->db->escape($id))->row;

    }



    public function getProductIdByName($name, $languageId) {

        $pid = $this->db->query("select p.product_id as id from `" . DB_PREFIX . "product_description` pd"

            . " left join " . DB_PREFIX . "product p on p.product_id = pd.product_id"

            . " WHERE pd.language_id = '" . $this->db->escape($languageId) . "'"

            . " and p.product_id is not null "

            . " and  lower(pd.name) = lower('" . $this->db->escape($name). "')")->row;







        if(!empty($pid)) {

            return $pid['id'];

        }



        return false;

    }



    public function getProductCheckSum($product, $ep=true, $eq=true, $et=true, $ed=true) {

        $sum = 'CHECK-';

        if($ep) {

            $sum .= $product['product']['price'];

        }



        if($eq) {

            $sum .= $product['product']['quantity'];

        }



        if($et) {

            $sum .= $product['product']['title'];

        }



        if($ed) {

            $sum .= $product['product']['description'];

        }



        if(isset($product['variations']['variations'])) {

            foreach ($product['variations']['variations'] as $variation) {

                if($ep) {

                    $sum .= $variation['price'];

                }

                if($eq) {

                    $sum .= $variation['qty'];

                }

            }

        }



        return md5($sum);

    }



    public function getQuantity($qty, $profile) {

        $q = $qty;

        $m = ($profile['max_qty_to_sell'])? $profile['max_qty_to_sell'] : 1;



        if(!$profile['qty_to_sell'] && ($qty > $m)) {

            $q = $m;

        }



        return $q;

    }





    public function getPrice($price, $profile) {

        $defaultCurrency =  $this->config->get('config_currency');

        if($profile['price_option'] == 'custom_price') {

            $price = $profile['price_custom_amount'];

        } elseif($profile['price_option'] =='price_extra') {

            if($profile['price_plus_minus'] == 'plus') {

                $price = $price + $profile['price_modify_amount'] + ($price * ($profile['price_modify_percent']/100));

            } else {

                $price = $price - $profile['price_modify_amount'] - ($price * ($profile['price_modify_percent']/100));

            }

        }



        $price = $this->currency->convert($price, $defaultCurrency, $profile['currency']);

        return $price;

    }



    public function getBinPrice($price, $profile) {

        $defaultCurrency =  $this->config->get('config_currency');

        if($profile['bin_option'] == 'custom_price') {

            $price = $profile['bin_custom_amount'];

        } elseif($profile['bin_option'] =='price_extra') {

            if($profile['bin_plus_minus'] == 'plus') {

                $price = $price + $profile['bin_modify_amount'] + ($price * ($profile['bin_modify_percent']/100));

            } else {

                $price = $price - $profile['bin_modify_amount'] - ($price * ($profile['bin_modify_percent']/100));

            }

        }

        $price = $this->currency->convert($price, $defaultCurrency, $profile['currency']);

        return $price;

    }



    public function getColumns() {

        return array('pr.product_id', 'pr.image', 'pd.name', 'pr.model', 'pr.price', 'pr.quantity', 'pr.status', 'ep.ebay_id', 'ep.end_time');

    }



    public function search($data) {



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

                $sOrder = " ORDER BY pr.product_id desc";

            }

        } else {

            $sOrder = " ORDER BY pr.product_id desc";

        }







        /*

         * Filtering

        * NOTE this does not match the built-in DataTables filtering which does it

        * word by word on any field. It's possible to do here, but concerned about efficiency

        * on very large tables, and MySQL's regex functionality is very limited

        */

        $sWhere = " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";





        if(isset($data['asp'])) {



            $hasParam = false;

            $qq = '';



            foreach ($data['asp'] as $param) {

                if(isset($param['value']) || isset($param['from']) || isset($param['to'])) {

                    $hasParam = true;

                    $logic = '=';

                    $logicS = '';

                    if($param['ologic'] == 'AND' ) {

                        $qq .= ' AND ';

                    } elseif ($param['ologic'] == 'OR') {

                        $qq .= ' OR ';

                    } elseif ($param['ologic'] == 'NOT') {

                        $qq .= ' AND ';

                        $logic = '<>';

                        $logicS = ' NOT ';

                    }



                    if($param['name'] == 'm') {

                        $qq .= " pr.model " .$logicS. " LIKE '%".$this->db->escape($param['value'])."%' ";

                    }



                    if($param['name'] == 'pn') {

                        $qq .= " pd.name " .$logicS. " LIKE  '%".$this->db->escape($param['value'])."%' ";

                    }



                    if($param['name'] == 'pc') {

                        $qq .= " pc.category_id ". $logic ." '".$this->db->escape($param['value'])."' ";

                    }



                    if($param['name'] == 'pm') {

                        $qq .= " pr.manufacturer_id ". $logic ." '".$this->db->escape($param['value'])."' ";

                    }



                    if($param['name'] == 'elt') {

                        $qq .= " ep.listing_type ". $logic ." '".$this->db->escape($param['value'])."' ";

                    }



                    if($param['name'] == 'em') {

                        $qq .= " ep.site_id ". $logic ." '".$this->db->escape($param['value'])."' ";

                    }



                    if($param['name'] == 'lp') {

                        $qq .= " lp.id ". $logic ." '".$this->db->escape($param['value'])."' ";

                    }



                    if($param['name'] == 'eid') {

                        $qq .= " ep.ebay_id ". $logic ." '".$this->db->escape($param['value'])."' ";

                    }



                    if($param['name'] == 's') {

                        $qq .= " pr.status ". $logic ." '".(($param['value'] == '1')? '1' : '0')."' ";

                    }





                    if($param['name'] == 'p') {

                        if(isset($param['from'])) {

                            $qq .= " pr.price >= '".$this->db->escape($param['from'])."' ";

                            if(isset($param['to'])) {

                                $qq .= ' AND ';

                            }

                        }



                        if(isset($param['to'])) {

                            $qq .= " pr.price <= '".$this->db->escape($param['to'])."' ";

                        }

                    }



                    if($param['name'] == 'q') {

                        if(isset($param['from'])) {

                            $qq .= " pr.quantity >= '".$this->db->escape($param['from'])."' ";

                            if(isset($param['to'])) {

                                $qq .= ' AND ';

                            }

                        }



                        if(isset($param['to'])) {

                            $qq .= " pr.quantity <= '".$this->db->escape($param['to'])."' ";

                        }

                    }



                }

            }



            if($hasParam) {

                $sWhere .= ' AND (' . $qq . ' ) ';

            }



            //$sWhere = substr_replace($sWhere, ")", -3);



            //var_dump($data['asp']);die();

        }







        if (isset($data['all_filter']) && !empty($data['all_filter'])) {

            $sWhere .= 'AND (';

            for ($i = 0; $i < count($aColumns); $i++) {

                $sWhere .= " " . $aColumns[$i]." LIKE '%".$this->db->escape($data['all_filter'])."%' " . " OR";

            }

            $sWhere = substr_replace($sWhere, ")", -3);

        }



        if (isset($data['pn_filter']) && $data['pn_filter'] != "") {

            $sWhere .= " AND pd.name LIKE '%".$this->db->escape($data['pn_filter'])."%' ";

        }



        if (isset($data['m_filter']) && $data['m_filter'] != "") {

            $sWhere .= " AND pr.model LIKE '%".$this->db->escape($data['m_filter'])."%' ";

        }



        if (isset($data['p_filter_from']) && $data['p_filter_from'] != "") {

            $sWhere .= " AND pr.price >= '".$this->db->escape($data['p_filter_from'])."' ";

        }



        if (isset($data['p_filter_to']) && $data['p_filter_to'] != "") {

            $sWhere .= " AND pr.price <= '".$this->db->escape($data['p_filter_to'])."' ";

        }



        if (isset($data['s_filter']) && $data['s_filter'] != "") {

            $sWhere .= " AND pr.status = '".(($data['s_filter'] == '1')? '1' : '0')."' ";

        }



        if (isset($data['q_filter_from']) && $data['q_filter_from'] != "") {

            $sWhere .= " AND pr.quantity >= '".$this->db->escape($data['q_filter_from'])."' ";

        }



        if (isset($data['q_filter_to']) && $data['q_filter_to'] != "") {

            $sWhere .= " AND pr.quantity <= '".$this->db->escape($data['q_filter_to'])."' ";

        }



        if (isset($data['eid_filter']) && $data['eid_filter'] != "") {

            $sWhere .= " AND ep.ebay_id = '".$this->db->escape($data['eid_filter'])."' ";

        }



        if (isset($data['eet_filter']) && $data['eet_filter'] != "") {

            $sWhere .= " AND ep.end_time = '".$this->db->escape($data['eet_filter'])."' ";

        }



        if (isset($data['elt_filter']) && $data['elt_filter'] != "") {

            $sWhere .= " AND ep.listing_type = '".$this->db->escape($data['elt_filter'])."' ";

        }



        if (isset($data['em_filter']) && $data['em_filter'] != "") {

            $sWhere .= " AND ep.site_id = '".$this->db->escape($data['em_filter'])."' ";

        }



        if (isset($data['lp_filter']) && $data['lp_filter'] != "") {

            $sWhere .= " AND lp.id = '".$this->db->escape($data['lp_filter'])."' ";

        }









        /* Individual column filtering */

        for ($i = 0; $i < count($aColumns); $i++) {

            if (isset($data['bSearchable_' . $i]) && $data['bSearchable_' . $i] == "true" && $data['sSearch_' . $i] != '') {

                if ($aColumns[$i] == "pr.price") {

                    $a = explode("~",$this->db->escape($data['sSearch_'.$i]) );

                    if( !empty($a[0]) && is_numeric($a[0]) ){

                        $sWhere .= " AND pr.price >= ".$a[0]." ";

                    }

                    if( !empty($a[1]) && is_numeric($a[1]) ){

                        $sWhere .= " AND pr.price <= ".$a[1]." ";

                    }

                } else {

                    $sWhere .= " AND ".$aColumns[$i]." LIKE '%".$this->db->escape($data['sSearch_'.$i])."%' ";

                }

            }

        }



        $sQuery = "SELECT pr.*, pd.name, pc.category_id, ep.ebay_id, ep.listing_type, ep.end_time, ep.site_id, ep.listing_mode, lp.id  FROM "

            . DB_PREFIX . "product pr LEFT JOIN " . DB_PREFIX . "product_description pd ON (pr.product_id = pd.product_id)" .

            " LEFT JOIN `" . DB_PREFIX . "channel_ebay_product` ep on pr.product_id = ep.product_id " .

            " LEFT JOIN `" . DB_PREFIX . "product_to_category` pc on pr.product_id = pc.product_id" .

            " LEFT JOIN `" . DB_PREFIX . "channel_ebay_list_profile` lp on ep.list_profile_id = lp.id" .

            $sWhere . " GROUP BY pr.product_id " .

            $sOrder . " " .

            $sLimit . " ";





        $result = $this->db->query($sQuery)->rows;

        $sPIds = array();

        $oPIds = array();

        $pIds = array();

        foreach ($result as $row) {

            $pIds[] = $row['product_id'];

            if(isset($row['has_option']) && $row['has_option'] > 0) {

                $oPIds[] = $row['product_id'];

            } else {

                $sPIds[] = $row['product_id'];

            }

        }



        if(!empty($pIds)) {

            $specials = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE `product_id` in (" . implode(',', $pIds) . ") ORDER BY priority, price")->rows;

            foreach ($result as $k=>$row) {

                foreach ($specials  as $product_special) {

                    if($row['product_id'] == $product_special['product_id']) {

                        if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {

                            $result[$k]['special_price'] = $product_special['price'];

                            break;

                        }

                    }

                }

            }

        }







        if(!empty($sPIds)) {

            $options = $this->db->query("SELECT product_id, count(*) as cnt, SUM(`quantity`) as qty FROM `" . DB_PREFIX . "product_option_value` WHERE `product_id` in (" . implode(',', $sPIds) . ") group by product_id")->rows;

            if(!empty($options)) {

                foreach ($result as $k=>$row) {

                    foreach ($options as $option) {

                        if($option['product_id'] == $row['product_id']) {

                            $result[$k]['quantity'] = '<span style="color: #000000;">' . $option['cnt'] . ' options</span>' . '<br>' . '<span style="color: #000000;">' . $option['qty'] . ' stock</span>';

                        }

                    }

                }

            }

        }





        if(!empty($oPIds)) {

            $options = $this->db->query("SELECT product_id, count(*) as cnt, SUM(stock) as qty FROM `" . DB_PREFIX . "product_option_variant` WHERE `product_id` in (" . implode(',', $oPIds) . ") group by product_id")->rows;

            if(!empty($options)) {

                foreach ($result as $k=>$row) {

                    foreach ($options as $option) {

                        if($option['product_id'] == $row['product_id']) {

                            $result[$k]['quantity'] = '<span style="color: #000000;">' . $option['cnt'] . ' variations</span>' . '<br>' . '<span style="color: #000000;">' . $option['qty'] . ' stock</span>';

                        }

                    }

                }

            }

        }



        //var_dump($sPIds); die();



        $cQuery = "SELECT count(distinct pr.product_id) as c FROM " . DB_PREFIX

            . "product pr LEFT JOIN " . DB_PREFIX . "product_description pd ON (pr.product_id = pd.product_id)" .

            " LEFT JOIN `" . DB_PREFIX . "channel_ebay_product` ep on pr.product_id = ep.product_id" .

            " LEFT JOIN `" . DB_PREFIX . "product_to_category` pc on  pr.product_id = pc.product_id" .

            " LEFT JOIN `" . DB_PREFIX . "channel_ebay_list_profile` lp on ep.list_profile_id = lp.id" .

            $sWhere;





        /* Data set length after filtering */

        $resultFilterTotal = $this->db->query($cQuery)->row['c'];



        $sQuery = "SELECT COUNT(*) as c FROM " .DB_PREFIX . "product";

        $resultTotal = $this->db->query($sQuery)->row['c'];



        $r = new stdClass();

        $r->result = $result;

        $r->count = $resultTotal;

        $r->filterCount = $resultFilterTotal;



        return $r;

    }



    public function getProductRecord($productId, $languageId = null, $profile) {

        $this->load->model('ebay_channel/tax_dao');

        $this->load->model('ebay_channel/settings_dao');

        $this->load->model('ebay_channel/template_dao');

        $this->load->model('tool/image');

        $customer_group_id = $this->config->get('config_customer_group_id');



        $settings = $this->model_ebay_channel_settings_dao->getSettings();

        $image_type = '';

        if(isset($settings['image_type'])) {

            $image_type = $settings['image_type'];

        }



        $product = array();

        $product['product'] = $this->db->query("SELECT p.product_id,

				p.*,

				m.name as brand,

				pd.name as title,

				pd.description as description FROM `" . DB_PREFIX . "product` p "

            ." left join `" . DB_PREFIX . "product_description` pd on pd.product_id = p.product_id"

            ." left join `" . DB_PREFIX . "manufacturer` m on m.manufacturer_id = p.manufacturer_id"

            ." where pd.language_id = ".$this->db->escape($languageId)

            ." and p.product_id = ". $this->db->escape($productId))->row;



        $product['p2e'] = $this->db->query("SELECT variations from " . DB_PREFIX . "channel_ebay_product where product_id = " . (int) $productId)->row;



        $product['product']['base_price'] = $product['product']['price'];



        $product['product']['base_price_with_tax'] = $product['product']['price'];

        if($settings['general_use_taxes_enabled']) {

            $product['product']['base_price_with_tax'] = $this->model_ebay_channel_tax_dao->calculate($product['product']['base_price'], $product['product']['tax_class_id'], $this->config->get('config_tax'));

        }



        if($settings['special_price_enabled']) {

            $specialPrice = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special ps

                                        WHERE ps.product_id = '" . $this->db->escape($productId) . "'

                                        AND ps.customer_group_id = '" . (int)$customer_group_id . "'

                                        AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW())

                                        AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))

                                        ORDER BY ps.priority ASC, ps.price ASC LIMIT 1")->row;



            if(isset($specialPrice['price'])) {

                $product['product']['price'] = $specialPrice['price'];

            }

        }



        if($languageId == null) {

            $languageId = (int) $this->config->get('config_language_id');

        }



        $product['product']['package_length'] = $this->getProductLength($product['product']);

        $product['product']['package_weight'] = $this->getProductWeight($product['product']);



        if(!empty($profile['title_suffix'])) {

            $product['product']['title'] = $product['product']['title'] . " " . $profile['title_suffix'];

        }



        if(!empty($product)) {

            if(isset($product['product']['has_option'])) {

                if($product['product']['has_option'] > 0) {

                    $product['product']['quantity'] = $product_option_query = $this->db->query("SELECT SUM(stock) as quantity FROM `" . DB_PREFIX . "product_option_variant` WHERE product_id = '" . (int)$productId . "'")->row['quantity'];

                    $product['variations'] = $this->getOpenStockVariations($product['product']['product_id'], $languageId, $product['product']['price'], $product['product']['quantity'], $profile);

                }

            } else {

                $product['variations'] = $this->getVariations($product['product']['product_id'], $languageId, $product['product']['price'], $product['product']['quantity'], $profile, $product['product']['tax_class_id']);

            }



            if(!empty($product['variations'])) {

                $qty = 0;

                foreach ($product['variations']['variations'] as $i=>$variation) {

                    $qty += $variation['qty'];



                    if($settings['general_use_taxes_enabled']) {

                        $product['variations']['variations'][$i]['price'] = $this->model_ebay_channel_tax_dao->calculate($variation['price'], $product['product']['tax_class_id'], $this->config->get('config_tax'));

                    }

                    $product['variations']['variations'][$i]['price'] = $this->getPrice($product['variations']['variations'][$i]['price'], $profile);



                }

                $product['product']['quantity'] = $qty;

            }



            $product['product']['base_quantity'] = $product['product']['quantity'];



            if(empty($product['variations'])) {

                $product['product']['quantity'] = $this->getQuantity($product['product']['quantity'], $profile);

            }



            if($product['product']['quantity'] < 0) {

                $product['product']['quantity'] = 0;

            }



            if($settings['general_use_taxes_enabled']) {

                $product['product']['price'] =  $this->model_ebay_channel_tax_dao->calculate($product['product']['price'], $product['product']['tax_class_id'], $this->config->get('config_tax'));

            }



            if($profile['bin_enabled']) {

                $product['product']['bin_price'] = $this->getBinPrice($product['product']['price'], $profile);

            }



            $product['product']['price'] = $this->getPrice($product['product']['price'], $profile);



            $product['has_template'] = false;

            if(!empty($profile['template_id'])) {

                $template = $this->model_ebay_channel_template_dao->getTemplateById($profile['template_id']);

                if($template) {

                    $product['has_template'] = true;

                    $product['template'] = array(

                        'html' => $template['html'],

                        'options' => array(

                            'price' => number_format($product['product']['price'], 2),

                            'model' => $product['product']['model'],

                            'sku' => $product['product']['sku'],

                            'manufacturer' => $product['product']['brand'],

                            'currency' => $profile['currency']

                        )



                    );

                    $extraData = $this->model_ebay_channel_template_dao->getProductExtraData($productId, $languageId, $template);

                    foreach($extraData as $key => $value) {

                        $product['template']['options'][$key] = $value;

                    }

                }

            }



            if ($product['product']['image']) {

                $product['product']['pictureUrl'] = $this->model_ebay_channel_settings_dao->resize($product['product']['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'), $image_type);

            } else {

                $product['product']['pictureUrl'] = null;

            }



            $images = $this->getProductImages($productId);

            if(!empty($images)) {

                $product['product']['images'] = array();

                foreach ($images as $image) {

                    $product['product']['images'][] =  $this->model_ebay_channel_settings_dao->resize($image['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'), $image_type);

                }

            }





            $product['product']['attributes'] = $this->getProductAttributes($productId);

        }



        $product = $this->getProductItemSpecifics($product, $profile);



        //print_r($product);



        return $product;

    }



    public function getProductAttributes($product_id) {

        $product_attribute_group_data = array();



        $product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");



        foreach ($product_attribute_group_query->rows as $product_attribute_group) {

            $product_attribute_data = array();



            $product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");



            foreach ($product_attribute_query->rows as $product_attribute) {

                $product_attribute_data[] = array(

                    'attribute_id' => $product_attribute['attribute_id'],

                    'name'         => $product_attribute['name'],

                    'text'         => $product_attribute['text']

                );

            }



            $product_attribute_group_data[] = array(

                'attribute_group_id' => $product_attribute_group['attribute_group_id'],

                'name'               => $product_attribute_group['name'],

                'attribute'          => $product_attribute_data

            );

        }



        return $product_attribute_group_data;

    }



    public function getProductOptions($product_id, $language_id) {

        $product_option_data = array();

        $product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$language_id . "' ORDER BY o.sort_order");



        foreach ($product_option_query->rows as $product_option) {

            if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image' || $product_option['type'] == 'color') {

                $product_option_value_data = array();



                $product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$language_id . "' ORDER BY ov.sort_order");



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

                        'weight'                  => $product_option_value['weight'],

                        'weight_prefix'           => $product_option_value['weight_prefix']

                    );

                }



                $product_option_data[] = array(

                    'product_option_id' => $product_option['product_option_id'],

                    'option_id'         => $product_option['option_id'],

                    'name'              => $product_option['name'],

                    'type'              => $product_option['type'],

                    'option_value'      => $product_option_value_data,

                    'required'          => $product_option['required']

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



    private function getOpenStockVariations($productId, $language_id, $pPrice, $pQty, $profile) {

        $Variations = array('variation_specifics_set' => array(), 'pictures' => array(), 'variations' => array());

        $productOptions = $this->getOpenStockProductOptions($productId, $language_id);

        $tv = array();

        foreach ($productOptions as $option) {

            foreach ($option['combi'] as $c) {

                $tv[$c['name']][$c['value']] = $c['value'];

            }

        }



        foreach ($tv as $name => $values) {

            $set = array('name'=>$name, 'values' => array());

            foreach ($values as $k=>$v) {

                $set['values'][] = $k;

            }

            $Variations['variation_specifics_set'][] = $set;

        }



        foreach ($productOptions as $option) {

            $sku = '';

            if(!empty($option['sku'])) {

               $sku = $option['sku'];

            }



            $price = $pPrice;

            if($option['price'] > 0) {

                $price = $option['price'];

            }

            $Variation = array();

            $Variation['variation_specifics'] = array();

            $Variation['price'] = $this->getPrice($price, $profile);

            $Variation['qty'] = ($option['stock'] < 0)? 0 : $option['stock'];

            $Variation['sku'] = $sku;

            $Variation['image'] = $option['image'];

            foreach ($option['combi'] as $c) {

                $Variation['variation_specifics'][] = array("name" => $c['name'], "value" => $c['value']);

            }

            $Variations['variations'][] = $Variation;

        }

        return $Variations;

    }





    public function getOpenStockProductOptions($product_id, $language_id) {

        $sql = "

                SELECT `" . DB_PREFIX . "product_option_value`.`product_option_value_id`, `" . DB_PREFIX . "option_value_description`.`name` as value, `" . DB_PREFIX . "product_option_value`.`option_id`, `" . DB_PREFIX . "option_description`.`name` as option_name

                FROM

                    `" . DB_PREFIX . "product_option_value`,

                    `" . DB_PREFIX . "option_value_description`,

                    `" . DB_PREFIX . "option_value`,

                    `" . DB_PREFIX . "option`,

                    `" . DB_PREFIX . "option_description`

                WHERE

                    `" . DB_PREFIX . "product_option_value`.`product_id` = '" . (int)$product_id . "'

                AND

                    `" . DB_PREFIX . "product_option_value`.`option_value_id` = `" . DB_PREFIX . "option_value_description`.`option_value_id`

                AND

					`" . DB_PREFIX . "option_value_description`.`language_id` = '" . (int) $language_id . "'

                AND

                    `" . DB_PREFIX . "option_value`.`option_value_id` = `" . DB_PREFIX . "product_option_value`.`option_value_id`

                AND

                    `" . DB_PREFIX . "option`.`option_id` = `" . DB_PREFIX . "option_value`.`option_id`

                    

                AND

					`" . DB_PREFIX . "option_description`.`language_id` = '" . (int) $language_id . "'

                AND

                    `" . DB_PREFIX . "option_description`.`option_id` = `" . DB_PREFIX . "option_value`.`option_id`    

                        

                ORDER BY `" . DB_PREFIX . "option`.`sort_order`, `" . DB_PREFIX . "option_value`.`sort_order` ASC";

        $options_qry = $this->db->query($sql);



        $option_values = array();

        foreach ($options_qry->rows as $row) {

            //$option_values[$row['product_option_value_id']] = $row['name'];

            $option_values[$row['product_option_value_id']] = array(

                'option_id'  => $row['option_id'],

                'name'  => $row['option_name'],

                'value' => $row['value']

            );

        }



        $variants = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option_variant` WHERE active='1' and `product_id` = '" . (int)$product_id . "' ORDER BY `product_option_variant_id` ASC");



        $variants_array = array();

        foreach ($variants->rows as $variant) {

            $variant_values = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option_variant_value` WHERE `product_id` = '" . (int)$product_id . "' AND `product_option_variant_id` = '" . (int)$variant['product_option_variant_id'] . "' ORDER BY `sort_order` ASC");

            $variant_combination = '';

            $variant_values_array = array();

            $combi = array();

            foreach ($variant_values->rows as $variant_value) {

                $optionValue = $option_values[$variant_value['product_option_value_id']];

                $variant_combination .= $optionValue['value'] . ' > ';



                $combi[] = $optionValue;



                $variant_values_array[$variant_value['sort_order']] = array(

                    'product_option_value_id'         => $variant_value['product_option_value_id'],

                    'product_option_variant_value_id' => $variant_value['product_option_variant_value_id']

                );

            }



            //variant specials

            $variant_specials = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option_variant_special` WHERE `product_id` = '" . (int)$product_id . "' AND `product_option_variant_id` = '" . (int)$variant['product_option_variant_id'] . "' ORDER BY `customer_group_id` ASC");



            $specials = array();

            foreach ($variant_specials->rows as $variant_special) {

                $specials[$variant_special['product_option_variant_special_id']] = array(

                    'customer_group_id' => $variant_special['customer_group_id'],

                    'price' 			=> $variant_special['price'],

                    'date_start' 		=> $variant_special['date_start'],

                    'date_end' 			=> $variant_special['date_end']

                );

            }



            //variant discounts

            $variant_discounts = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option_variant_discount` WHERE `product_id` = '" . (int)$product_id . "' AND `product_option_variant_id` = '" . (int)$variant['product_option_variant_id'] . "' ORDER BY `customer_group_id`, `quantity` ASC");



            $discounts = array();

            foreach ($variant_discounts->rows as $variant_discount) {

                $discounts[$variant_discount['product_option_variant_discount_id']] = array(

                    'price'             => $variant_discount['price'],

                    'quantity'          => $variant_discount['quantity'],

                    'customer_group_id' => $variant_discount['customer_group_id'],

                    'date_start'        => $variant_discount['date_start'],

                    'date_end'          => $variant_discount['date_end']

                );

            }



            $this->load->model('tool/image');



            $thumb = '';

            if (!empty($variant) && $variant['image'] && file_exists(DIR_IMAGE . $variant['image'])) {

                $thumb = $this->model_tool_image->resize($variant['image'], 100, 100);

            }



            $variants_array[$variant['product_option_variant_id']] = array(

                'product_option_variant_id'  => $variant['product_option_variant_id'],

                'sku'                        => $variant['product_id'] . '-' . $variant['sku'],

                'product_id'                 => $variant['product_id'],

                'combi'                      => $combi,

                'stock'                      => $variant['stock'],

                'active'                     => $variant['active'],

                'subtract'                   => $variant['subtract'],

                'price'                      => $variant['price'],

                'image'                      => $thumb,

                //'opts'                       => $option_values



            );

        }











        return $variants_array;

    }



    public function getOpenStockProductOptions1($product_id, $language_id) {

        $SQL = "

            SELECT `" . DB_PREFIX . "product_option_value`.`product_option_value_id`, `" . DB_PREFIX . "option_value_description`.`name` as value

            		, `" . DB_PREFIX . "option_description`.`name` as name

            FROM

                `" . DB_PREFIX . "product_option_value`,

                `" . DB_PREFIX . "option_value_description`,

                `" . DB_PREFIX . "option_description`,		

                `" . DB_PREFIX . "option_value`,

                `" . DB_PREFIX . "option`

            WHERE

                `" . DB_PREFIX . "product_option_value`.`product_id` = '".$product_id."'

            AND

                `" . DB_PREFIX . "product_option_value`.`option_value_id` = `" . DB_PREFIX . "option_value_description`.`option_value_id`

             AND

                `" . DB_PREFIX . "product_option_value`.`option_id` = `" . DB_PREFIX . "option_description`.`option_id`    		

            AND

                `" . DB_PREFIX . "option_value`.`option_value_id` = `" . DB_PREFIX . "product_option_value`.`option_value_id`

            AND

                `" . DB_PREFIX . "option`.`option_id` = `" . DB_PREFIX . "option_value`.`option_id`

            ORDER BY `" . DB_PREFIX . "option`.`sort_order`, `" . DB_PREFIX . "option_value`.`sort_order` ASC";





        $options_qry = $this->db->query($SQL);



        $optionValues = array();

        foreach($options_qry->rows as $row)

        {

            $optionValues[$row['product_option_value_id']] = $row;

        }



        $SQL = "SELECT * FROM `" . DB_PREFIX . "product_option_variant` WHERE `product_id` = '".$product_id."' ORDER BY `var` ASC";

        $mix_qry = $this->db->query($SQL);



        $optionsStockArray = array();



        foreach($mix_qry->rows as $row)

        {

            $options = explode(':', $row['var']);

            $combi = array();

            foreach($options as $k=>$v)

            {

                $combi[] = $optionValues[$v];

            }



            $optionsStockArray[$row['id']] = array(

                'id'            => $row['id'],

                'sku'           => $row['product_id'] . '-' . $row['sku'],

                'product_id'    => $row['product_id'],

                'combi'         => $combi,

                'stock'         => $row['stock'],

                'active'        => $row['active'],

                'var'           => $row['var'],

                'subtract'      => $row['subtract'],

                'price'         => $row['price'],

                'opts'          => $optionValues

            );

        }



        return $optionsStockArray;



    }







    private function getCobinations($arrays, $i = 0) {



        if (!isset($arrays[$i])) {

            return array();

        }

        if ($i == count($arrays) - 1) {

            $r = array();

            foreach ($arrays[$i] as $a) {

                $r[] = array($a);

            }

            return $r;

        }

        $tmp = $this->getCobinations($arrays, $i + 1);

        $result = array();

        foreach ($arrays[$i] as $v) {

            foreach ($tmp as $t) {

                $result[] = is_array($t) ?

                    array_merge(array($v), $t) :

                    array($v, $t);

            }

        }

        return $result;

    }



    private function getVariations($productId, $language_id, $pPrice, $pQty, $profile, $tax_class_id) {

        $this->load->model('ebay_channel/tax_dao');



        $dbOptions = array();

        $options = array();



        $Variations = array('variation_specifics_set' => array(), 'pictures' => array(), 'variations' => array());



        $productOptions = $this->getProductOptions($productId, $language_id);



        foreach ($productOptions as $option) {

            $values = array();

            if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image' || $option['type'] == 'color') {

                $set = array();

                $set['name'] = $option['name'];

                $set['values'] = array();



                $option_value_data = array();



                foreach ($option['option_value'] as $option_value) {

                    $values[] = $option_value['option_value_id'];

                    $set['values'][] = $option_value['name'];

// 					if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {

                    $price = $option_value['price'];

                    $option_value_data[] = array(

                        'product_option_value_id' => $option_value['product_option_value_id'],

                        'option_value_id'         => $option_value['option_value_id'],

                        'name'                    => $option_value['name'],

                        'quantity'                    => $option_value['quantity'],

                        'price'                   => $this->model_ebay_channel_tax_dao->calculate($price, $tax_class_id, $this->config->get('config_tax')),

                        'price_prefix'            => $option_value['price_prefix']

                    );

                    // 						if(!empty($option_value['image'])) {

                    // 							$Variations['pictures'][] = array('name'=>$option['name'], 'url'=>$this->model_tool_image->resize($option_value['image'], 50, 50), 'value' => $option_value['name']);

                    // 						}

// 					}

                }



                $dbOptions[] = array(

                    'product_option_id' => $option['product_option_id'],

                    'option_id'         => $option['option_id'],

                    'name'              => $option['name'],

                    'type'              => $option['type'],

                    'option_value'      => $option_value_data,

                    'required'          => $option['required']

                );

                $Variations['variation_specifics_set'][] = $set;

            }

            if(!empty($values)) {

                $options[] = $values;

            }

        }





        if(!empty($options)) {

            $combinations = $this->getCobinations($options);



            foreach ($combinations as $combination) {

                $price = $pPrice;

                $qty = 0;

                $Variation = array();

                $Variation['variation_specifics'] = array();

                foreach ($combination as $option) {

                    $name = '';

                    $value = '';

                    $sku = $productId;



                    foreach ($dbOptions as $dbOption) {

                        foreach ($dbOption['option_value'] as $dbOptionValue) {

                            if($option == $dbOptionValue['option_value_id']) {

                                $name = $dbOption['name'];

                                $value = $dbOptionValue['name'];

                                $sku .=  '-' . $dbOptionValue['option_value_id'];



                                if($qty == 0) {

                                    $qty = $dbOptionValue['quantity'];

                                } elseif ($dbOptionValue['quantity'] < $qty) {

                                    $qty = $dbOptionValue['quantity'];

                                }



                                if($dbOptionValue['price_prefix'] == '+') {

                                    $price += $dbOptionValue['price'];

                                } elseif ($dbOptionValue['price_prefix'] == '-') {

                                    $price -= $dbOptionValue['price'];

                                }

                            }

                        }

                    }



                    $Variation['variation_specifics'][] = array('name'=>$name, 'value' => $value, 'sku' => $sku);

                }

                $Variation['price'] = $this->getPrice($price, $profile);

                $Variation['qty'] = $qty;

                $Variation['sku'] = $sku;

                $Variations['variations'][] = $Variation;

            }





            return $Variations;

        }





        return array();

    }



    public function getProductDescription($description) {

        return '<meta http-equiv="X-UA-Compatible" content="IE=edge" />

					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> <script type="text/javascript">

						u = "' . HTTP_CATALOG . '";

						function ath(t, p) {

							var e = document.createElement(t);

							document.getElementsByTagName("head")[0].appendChild(e);

							if (p) {

								for ( var k in p) {

									e.setAttribute(k, p[k]);

								}

							}

						}

						ath("script", {src : u + "catalog/view/javascript/ebay_channel/product_init.js", epi : ebayItemID, url : u });

						ath("link", {href : u + "catalog/view/theme/default/stylesheet/ebay_channel/product.css", rel : "stylesheet"});

					</script>

					<div id="dpd">' . $description . '</div>';

    }



    public function insertCategory($category) {

        $parentId = 0;

        $top = 1;

        $dbCategory = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category  WHERE ebay_id = '" . $this->db->escape($category['categoryID']) . "'")->row;

        if(empty($dbCategory)) {

            if($category['categoryParentID'] != $category['categoryID']) {

                $parent = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category  WHERE ebay_id = '" . $this->db->escape($category['categoryParentID']) . "'")->row;

                if(!empty($parent)) {

                    $parentId = $parent['category_id'];

                }

                //ebay_parent_id

            } else {

                $top = 0;

            }



            $this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '" . (int)$parentId . "', ebay_id = '" . (int)$category['categoryID'] . "', ebay_parent_id = '" . (int)$category['categoryParentID'] . "', `top` = ' " . $top . "', `column` = '0', sort_order = '0', status = '1', date_modified = NOW(), date_added = NOW()");



            $category_id = $this->db->getLastId();



            $languageIds = $this->db->query("SELECT language_id FROM ".DB_PREFIX."language")->rows;

            foreach ($languageIds as $language) {

                $this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape($category['categoryName']) . "', meta_keyword = '', meta_description = '', description = ''");

            }



            $this->db->query("UPDATE `" . DB_PREFIX . "category` SET parent_id ='" . $category_id . "' WHERE `parent_id` = 0 AND `ebay_parent_id`='" . $category['categoryID'] . "' AND `ebay_parent_id`<>'" . $category['categoryParentID'] . "'");

            $level = 0;



            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$parentId . "' ORDER BY `level` ASC");

            foreach ($query->rows as $result) {

                $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

                $level++;

            }



            $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");

            $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '0'");

            $this->cache->delete('category');

        }

    }



    public function getProductsByName($data = array()) {

        $sql = "SELECT * FROM " . DB_PREFIX . "product p

				LEFT JOIN " . DB_PREFIX . "channel_ebay_product pte ON (p.product_id = pte.product_id)  

				LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";



        $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";



        if (!empty($data['filter_name'])) {

            $sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";

        }





        $sql .= " GROUP BY p.product_id";



        $sort_data = array(

            'pd.name',

            'p.model',

            'p.price',

            'p.quantity',

            'p.status',

            'p.sort_order'

        );



        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {

            $sql .= " ORDER BY " . $data['sort'];

        } else {

            $sql .= " ORDER BY pd.name";

        }



        if (isset($data['order']) && ($data['order'] == 'DESC')) {

            $sql .= " DESC";

        } else {

            $sql .= " ASC";

        }



        if (isset($data['start']) || isset($data['limit'])) {

            if ($data['start'] < 0) {

                $data['start'] = 0;

            }



            if ($data['limit'] < 1) {

                $data['limit'] = 20;

            }



            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];

        }



        $query = $this->db->query($sql);



        return $query->rows;

    }





    public function existsOpenbay() {

        $result = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "ebay_listing'")->rows;

        if(!empty($result)) {

            return $this->db->query("SELECT count(*) as cnt from " . DB_PREFIX . "ebay_listing")->row['cnt'] > 0;

        }

        return false;

    }



    public function getProductIdFromOpenbay($ebayItemId) {

        $row = $this->db->query("SELECT product_id from " . DB_PREFIX . "ebay_listing where ebay_item_id = '" . $this->db->escape($ebayItemId) . "'")->row;

        if(!empty($row)) {

            return $row['product_id'];

        }



        return false;

    }



    public function getLengthClassByName($unit) {

        $row = $this->db->query("SELECT length_class_id from " . DB_PREFIX . "length_class_description where unit = '" . $this->db->escape($unit) . "' LIMIT 1")->row;

        if(!empty($row)) {

            return $row['length_class_id'];

        }



        return false;

    }





    public function getProductLength($product) {

        $unit = $this->db->query("SELECT unit from " . DB_PREFIX . "length_class_description where length_class_id = '" . $this->db->escape($product['length_class_id']) . "' LIMIT 1")->row;

        $depth = $product['height'];

        $width = $product['width'];

        $length = $product['length'];



        if(!empty($unit)) {

            $unit = $unit['unit'];

            if ($depth || $width || $length) {

                $c = 1;

                if ($unit == 'cm') {

                    $c = 0.393701;

                } else if ($unit == 'mm') {

                    $c = 0.0393701;

                } else if ($unit == 'm') {

                    $c = 39.3701;

                }



                $depth *= $c;

                $width *= $c;

                $length *= $c;



                return array(

                    "depth" => $depth,

                    "width" => $width,

                    "length" => $length

                );

            }

        }

        return array();



    }





    public function getProductWeight($product) {

        $unit = $this->db->query("SELECT unit from " . DB_PREFIX . "weight_class_description where weight_class_id = '" . $this->db->escape($product['weight_class_id']) . "' LIMIT 1")->row;

        $weightMinor = 0;

        $weightMajor = 0;

        if(!empty($unit)) {

            $unit = $unit['unit'];

            if($unit == "kg") {

                $weightMajor = $product['weight'] * 2.20462;

            } else if($unit == "gr") {

                $weightMinor = $product['weight'] *  0.035274;

            } else if($unit == "mg") {

                $weightMinor = $product['weight'] * 3.5274;

            } if($unit == "lb" || $unit == "lbs") {

                $weightMajor = $product['weight'];

            } else if($unit == "oz") {

                $weightMinor = $product['weight'];

            } else {

                $weightMinor = $product['weight'];

            }



            if($weightMinor || $weightMajor) {

                return array(

                    "major" => $weightMajor,

                    "minor" => $weightMinor

                );

            }

        }

        return array();

    }



    public function getWeightClassByName($unit) {

        $row = $this->db->query("SELECT weight_class_id from " . DB_PREFIX . "weight_class_description where unit = '" . $this->db->escape($unit) . "' LIMIT 1")->row;

        if(!empty($row)) {

            return $row['weight_class_id'];

        }



        return false;

    }



    public function addWeightClass($unit, $language_id) {

        $id = $this->getWeightClassByName($unit);

        $value = 1;

        if(!$id) {

            $this->db->query("INSERT INTO " . DB_PREFIX . "weight_class SET `value` = '" . $this->db->escape($value) . "'");

            $id = $this->db->getLastId();

            $this->db->query("INSERT INTO " . DB_PREFIX . "weight_class_description SET "

                . "title = '" . $this->db->escape($unit) ."'"

                . ",unit = '" . $this->db->escape($unit) ."'"

                . ",weight_class_id = '" . $this->db->escape($id) ."'"

                . ",language_id = '" . $this->db->escape($language_id) ."'"

            );

        }

        return $id;

    }



    public function addLengthClass($unit, $language_id) {

        $title = $unit;

        $value = 1;

        if($unit == 'inches') {

            $unit = 'in';

        }



        if($unit == 'centimeters') {

            $unit = 'cm';

        }



        if($unit == 'millimeters') {

            $unit = 'mm';

        }





        $id = $this->getLengthClassByName($unit);

        if(!$id) {

            $this->db->query("INSERT INTO " . DB_PREFIX . "length_class SET `value` = '" . $this->db->escape($value) . "'");

            $id = $this->db->getLastId();

            $this->db->query("INSERT INTO " . DB_PREFIX . "length_class_description SET "

                . "title = '" . $this->db->escape($title) ."'"

                . ",unit = '" . $this->db->escape($unit) ."'"

                . ",length_class_id = '" . $this->db->escape($id) ."'"

                . ",language_id = '" . $this->db->escape($language_id) ."'"

            );

        }

        return $id;

    }



    public function getProductItemSpecifics($product, $profile) {

        $specifics = array();

        $productDetails = array();

        if(!empty($profile['item_specifics'])) {//New version

            $item_specifics = json_decode($profile['item_specifics'], true);

            foreach($item_specifics as $name => $value) {

                $label = $name;

                $productDetail = array();

                if($name == 'upc_required') {

                    $label = 'UPC';

                    $productDetail['name'] = $label;

                }

                if($name == 'ean_required') {

                    $label = 'EAN';

                    $productDetail['name'] = $label;

                }



                if (in_array(strtolower($name), array('brand', 'marke', 'hersteller'))) {

                    $productDetail['name'] = 'Brand';

                }



                if (in_array(strtolower($name), array('mpn', 'herstellernummer'))) {

                    $productDetail['name'] = 'MPN';

                }







                //Attributes

                if($this->startsWith($value, 'a.')) {

                    $attribute_id = substr($value, 2);



                    foreach ($product['product']['attributes'] as $group) {

                        foreach ($group['attribute'] as $attribute) {

                            if($attribute_id == $attribute['attribute_id']) {

                                //var_dump($attribute);

                                $specifics[$label] = $attribute['text'];

                                $productDetail['value'] = $attribute['text'];

                            }



                        }

                    }

                }



                //Unavailable

                if($this->startsWith($value, 'v.')) {

                    $specifics[$label] = substr($value, 2);

                    $productDetail['value'] = substr($value, 2);

                }



                //Custom value

                if($value == '00' && isset($item_specifics[$name . '_custom_value']) && !empty($item_specifics[$name . '_custom_value'])) {

                    $specifics[$label] = $item_specifics[$name . '_custom_value'];

                    $productDetail['value'] = $item_specifics[$name . '_custom_value'];

                }



                //Options

                if($this->startsWith($value, 'o.')) {

                    $option_id = substr($value, 2);



                    if(isset($product['variations']['variation_specifics_set'])) {

                        foreach($product['variations']['variation_specifics_set'] as $key => $specificSet) {

                            if($specificSet['option_id'] == $option_id) {

                                $product['variations']['variation_specifics_set'][$key]['name'] = $label;

                            }

                        }

                    }



                    if(isset($product['variations']['variations'])) {

                        foreach($product['variations']['variations'] as $variationKey => $variation) {

                            if(isset($variation['variation_specifics'])) {

                                foreach($variation['variation_specifics'] as $key => $specificSet) {

                                    if($specificSet['option_id'] == $option_id) {

                                        $product['variations']['variations'][$variationKey]['variation_specifics'][$key]['name'] = $label;

                                    }

                                }

                            }

                        }

                    }







                }



                //Product

                if($this->startsWith($value, 'p.')) {

                    $field = substr($value, 2);

                    if($field == 'manufacturer_id') {

                        $specifics[$label] = $product['product']['brand'];

                        $productDetail['value'] = $product['product']['brand'];

                    }



                    if($field == 'model') {

                        $specifics[$label] = $product['product']['model'];

                        $productDetail['value'] = $product['product']['model'];

                    }



                    if($field == 'sku') {

                        $specifics[$label] = $product['product']['sku'];

                        $productDetail['value'] = $product['product']['sku'];

                    }



                    if($field == 'ean') {

                        $specifics[$label] = $product['product']['ean'];

                        $productDetail['value'] = $product['product']['ean'];

                    }



                    if($field == 'mpn') {

                        $specifics[$label] = $product['product']['mpn'];

                        $productDetail['value'] = $product['product']['mpn'];

                    }

                }



                if(isset($productDetail['name']) && isset($productDetail['value']) && !empty($productDetail['name']) && !empty($productDetail['value'])) {

                    $productDetails[] = $productDetail;

                }

            }

        }



        foreach($specifics as $name => $value) {

            if(!empty($name) && !empty($value)) {

                $product['product']['item_specifics'][$name] = $value;

            }

        }

        $product['product']['product_details'] = $productDetails;

        return $product;

    }



    public function startsWith($haystack, $needle) {

        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;

    }





}

?>

