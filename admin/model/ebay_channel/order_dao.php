<?php
class ModelEbayChannelOrderDao extends Model {

    public function getOrderByEbayId() {
        $result = $this->db->query("SELECT * from `" . DB_PREFIX . "order` WHERE ebay_order_id='". $this->db->escape($data['ebay_order_id']) ."'")->row;
        if(!empty($result)) {
            return $result;
        }

        return false;
    }
	
	public function updateOrInsert($data, $settings) {

		$result = $this->db->query("SELECT order_id from `" . DB_PREFIX . "order` WHERE ebay_order_id='". $this->db->escape($data['ebay_order_id']) ."'")->row;
		if(!empty($result['order_id'])) {
			$data['order_id'] = $result['order_id'];
			return $this->updateOrder($data);
		} else {
			return $this->addOrder($data, $settings);
		}
		
	}
	
	public function addOrder($data, $settings) {

		//----Import orders older n days-----
		$b = true;
		$importOlder = $settings['order_import_older'];
		if(!empty($importOlder)) {
			if(strtotime($data['date_added']) <= strtotime('-' . $importOlder . ' days')) {
				$b = false;
			}
		}

		if(!$b) {
			return null;
		}
		//--------------------------------
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) 
				. "', store_id = '" . (int)$data['store_id'] 
				. "', store_name = '" . $this->db->escape($data['store_name']) 
				. "', store_url = '" . $this->db->escape($data['store_url']) 
				. "', customer_id = '" . (int)$data['customer_id']
				. "', order_status_id = '" . (int)$data['order_status_id']
				. "', customer_group_id = '" . (int)$data['customer_group_id'] 
				. "', firstname = '" . $this->db->escape($data['firstname']) 
				. "', lastname = '" . $this->db->escape($data['lastname']) 
				. "', email = '" . $this->db->escape($data['email']) 
				. "', telephone = '" . $this->db->escape($data['telephone']) 
				. "', fax = '" . $this->db->escape($data['fax']) 
				. "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) 
				. "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) 
				. "', payment_company = '" . $this->db->escape($data['payment_company']) 
				. "', payment_company_id = '" . $this->db->escape($data['payment_company_id']) 
				. "', payment_tax_id = '" . $this->db->escape($data['payment_tax_id']) 
				. "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) 
				. "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) 
				. "', payment_city = '" . $this->db->escape($data['payment_city']) 
				. "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) 
				. "', payment_country = '" . $this->db->escape($data['payment_country']) 
				. "', payment_country_id = '" . (int)$data['payment_country_id'] 
				. "', payment_zone = '" . $this->db->escape($data['payment_zone']) 
				. "', payment_zone_id = '" . (int)$data['payment_zone_id'] 
				. "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) 
				. "', payment_method = '" . $this->db->escape($data['payment_method']) 
				. "', payment_code = '" . $this->db->escape($data['payment_code']) 
				. "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) 
				. "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) 
				. "', shipping_company = '" . $this->db->escape($data['shipping_company']) 
				. "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) 
				. "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) 
				. "', shipping_city = '" . $this->db->escape($data['shipping_city']) 
				. "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) 
				. "', shipping_country = '" . $this->db->escape($data['shipping_country']) 
				. "', shipping_country_id = '" . (int)$data['shipping_country_id'] 
				. "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) 
				. "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] 
				. "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) 
				. "', shipping_method = '" . $this->db->escape($data['shipping_method']) 
				. "', shipping_code = '" . $this->db->escape($data['shipping_code']) 
				. "', comment = '" . $this->db->escape($data['comment']) 
				. "', total = '" . (float)$data['total'] 
				. "', language_id = '" . (int)$data['language_id'] 
				. "', currency_id = '" . (int)$data['currency_id'] 
				. "', currency_code = '" . $this->db->escape($data['currency_code']) 
				. "', ebay_order_id = '" . $this->db->escape($data['ebay_order_id'])
				. "', ebay_payment_method = '" . $this->db->escape($data['ebay_payment_method'])
				. "', ebay_shipping_service = '" . $this->db->escape($data['ebay_shipping_service'])
				. "', ebay_amount_paid = '" . $this->db->escape($data['ebay_amount_paid'])
				
				
				. "', ebay_buyer_user_id = '" . $this->db->escape($data['ebay_buyer_user_id'])
				. "', ebay_order_status = '" . $this->db->escape($data['ebay_order_status'])
				. "', ebay_paid_time = '" . $this->db->escape($data['ebay_paid_time'])
				. "', ebay_shipped_time = '" . $this->db->escape($data['ebay_shipped_time'])
				. "', ebay_buyer_checkout_message = '" . $this->db->escape($data['ebay_buyer_checkout_message'])
				. "', ebay_address_owner = '" . $this->db->escape($data['ebay_address_owner'])
				. "', ebay_city_name = '" . $this->db->escape($data['ebay_city_name'])
				. "', ebay_country = '" . $this->db->escape($data['ebay_country'])
				. "', ebay_state_or_province = '" . $this->db->escape($data['ebay_state_or_province'])
				
				
				. "', currency_value = '" . (float)$data['currency_value'] 
				. "', ip = '" . $this->db->escape($data['ip']) 
				. "', forwarded_ip = '" .  $this->db->escape($data['forwarded_ip']) 
				. "', user_agent = '" . $this->db->escape($data['user_agent']) 
				. "', accept_language = '" . $this->db->escape($data['accept_language']) 
				. "', date_added = '" .$this->db->escape($data['date_added'])."', date_modified = NOW()");

		
		$order_id = $this->db->getLastId();
		foreach ($data['products'] as $product) {
			$itemsForSync[] = $product['ebay_item_id'];


            $eOrderId = $this->getEbayOrderIdByEbayTransactionId($product['ebay_order_line_item_id']);

            if($eOrderId && $eOrderId != $data['ebay_order_id']) {
                $this->deleteTransaction($product['ebay_order_line_item_id']);
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$data['order_status_id'] . "', notify = '0', comment = 'eBay TransactionId ". $product['ebay_order_line_item_id'] ." was changed!!', date_added = NOW()");
            } elseif($settings['order_subtract_stock'] == '1') {

                $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "' AND subtract = '1' ");

                if(!empty($product['options'])) {
                    $product_option_values = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_id='" . (int)$product['product_id'] . "' AND  option_value_id in ('" . implode("','", $product['options']) . "')")->rows;
                    foreach ($product_option_values as $option) {
                        $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
                    }
                }
            }

			$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id 
					. "', product_id = '" . (int)$product['product_id'] 
					. "', name = '" . $this->db->escape($product['name']) 
					. "', model = '" . $this->db->escape($product['model']) 
					. "', quantity = '" . (int)$product['quantity'] 
					. "', ebay_transaction_id = '" . $product['ebay_transaction_id']
					. "', ebay_order_line_item_id = '" . $product['ebay_order_line_item_id']
					. "', ebay_shipment_tracking_number = '" . $product['ebay_shipment_tracking_number']
					. "', price = '" . (float)$product['price'] 
					. "', total = '" . (float)$product['total'] 
					. "', tax = '" . (float)$product['tax'] 
					. "', reward = '" . (int)$product['reward'] . "'");

		}

		foreach ($data['totals'] as $total) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
		}

		if($settings['order_subtract_stock'] == '2') {//SYNC items with ebay
			$this->load->model('ebay_channel/cron_dao');
			$this->model_ebay_channel_cron_dao->updateProductsStock(implode(",", $itemsForSync));
		}

		return $order_id;
	}
	
	public function updateOrder($data) {
	
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix'])
				. "', firstname = '" . $this->db->escape($data['firstname'])
				. "', lastname = '" . $this->db->escape($data['lastname'])
				. "', telephone = '" . $this->db->escape($data['telephone'])
				. "', fax = '" . $this->db->escape($data['fax'])
				. "', payment_firstname = '" . $this->db->escape($data['payment_firstname'])
				. "', payment_lastname = '" . $this->db->escape($data['payment_lastname'])
				. "', payment_company = '" . $this->db->escape($data['payment_company'])
				. "', payment_company_id = '" . $this->db->escape($data['payment_company_id'])
				. "', payment_tax_id = '" . $this->db->escape($data['payment_tax_id'])
				. "', payment_address_1 = '" . $this->db->escape($data['payment_address_1'])
				. "', payment_address_2 = '" . $this->db->escape($data['payment_address_2'])
				. "', payment_city = '" . $this->db->escape($data['payment_city'])
				. "', payment_postcode = '" . $this->db->escape($data['payment_postcode'])
				. "', payment_country = '" . $this->db->escape($data['payment_country'])
				. "', payment_country_id = '" . (int)$data['payment_country_id']
				. "', payment_zone = '" . $this->db->escape($data['payment_zone'])
				. "', payment_zone_id = '" . (int)$data['payment_zone_id']
				. "', payment_address_format = '" . $this->db->escape($data['payment_address_format'])
				. "', payment_method = '" . $this->db->escape($data['payment_method'])
				. "', payment_code = '" . $this->db->escape($data['payment_code'])
				. "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname'])
				. "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname'])
				. "', shipping_company = '" . $this->db->escape($data['shipping_company'])
				. "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1'])
				. "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2'])
				. "', shipping_city = '" . $this->db->escape($data['shipping_city'])
				. "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode'])
				. "', shipping_country = '" . $this->db->escape($data['shipping_country'])
				. "', shipping_country_id = '" . (int)$data['shipping_country_id']
				. "', shipping_zone = '" . $this->db->escape($data['shipping_zone'])
				. "', shipping_zone_id = '" . (int)$data['shipping_zone_id']
				. "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format'])
				. "', shipping_method = '" . $this->db->escape($data['shipping_method'])
				. "', shipping_code = '" . $this->db->escape($data['shipping_code'])
				. "', comment = '" . $this->db->escape($data['comment'])
				. "', total = '" . (float)$data['total']
				. "', date_added = '" .$this->db->escape($data['date_added'])."', date_modified = NOW() WHERE order_id='". $this->db->escape($data['order_id']) ."'");
	

        if(count($data['products']) > 1) {
            foreach ($data['products'] as $product) {
                if(isset($product['ebay_order_line_item_id']) && !empty($product['ebay_order_line_item_id'])) {
                    $this->db->query("UPDATE " . DB_PREFIX . "order_product SET ebay_shipment_tracking_number = '" . $product['ebay_shipment_tracking_number']
                        . "', price = '" . (float)$product['price']
                        . "', model = '" . $this->db->escape($product['model'])
                        . "', tax = '" . (float)$product['tax']
                        . "', total = '" . (float)$product['total']
                        . "' WHERE  order_id='". $this->db->escape($data['order_id']) ."' and ebay_order_line_item_id = '" . $this->db->escape($product['ebay_order_line_item_id']) . "'");
                }
            }
        } else {
            foreach ($data['products'] as $product) {
                $this->db->query("UPDATE " . DB_PREFIX . "order_product SET ebay_shipment_tracking_number = '" . $product['ebay_shipment_tracking_number']
                    . "', price = '" . (float)$product['price']
                    . "', model = '" . $this->db->escape($product['model'])
                    . "', tax = '" . (float)$product['tax']
                    . "', total = '" . (float)$product['total']
                    . "' WHERE  order_id='" . $this->db->escape($data['order_id']) . "'");
            }
        }

		return $data['order_id'];
	}

    public function getOrderStatuses() {
        return $this->db->query('select * from '.DB_PREFIX.'order_status')->rows;
    }

    private function deleteTransaction($ebay_order_line_item_id) {
        $dOrderId = $this->getOrderIdByEbayTransactionId($ebay_order_line_item_id);
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` where ebay_order_line_item_id='" . $this->db->escape($ebay_order_line_item_id) . "'");
        $count = $this->db->query("SELECT count(*) as cnt FROM `" . DB_PREFIX . "order_product` where order_id='" . $this->db->escape($dOrderId) . "'")->row['cnt'];
        if($count < 1) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "order` where order_id='" . $this->db->escape($dOrderId) . "'");
        }
    }

    public function getEbayOrderIdByEbayTransactionId($transactionId) {
        $row = $this->db->query(
             "select o.ebay_order_id as ebay_order_id from ".DB_PREFIX."order_product op
                left join `".DB_PREFIX."order` o on o.order_id = op.order_id
                where op.ebay_order_line_item_id = '" . $this->db->escape($transactionId) . "'")->row;

        if(!empty($row)) {
            return $row['ebay_order_id'];
        }

        return false;
    }

    public function getOrderIdByEbayTransactionId($transactionId) {
        $row = $this->db->query(
            "select o.order_id as order_id from ".DB_PREFIX."order_product op
                left join `".DB_PREFIX."order` o on o.order_id = op.order_id
                where op.ebay_order_line_item_id = '" . $this->db->escape($transactionId) . "'")->row;

        if(!empty($row)) {
            return $row['order_id'];
        }

        return false;
    }

    public function getOrderStatusKey($status) {
        if($status == 'Completed') {
            return 'completed_status';
        } elseif($status == 'Cancelled') {
            return 'cancelled_status';
        } elseif($status == 'CancelPending') {
            return 'cancelpending_status';
        } elseif($status == 'Inactive') {
            return 'inactive_status';
        } elseif($status == 'InProcess') {
            return 'inprocess_status';
        } elseif($status == 'Invalid') {
            return 'invalid_status';
        } elseif($status == 'Shipped') {
            return 'shipped_status';
        }

        return 'default_status';
    }
}
?>