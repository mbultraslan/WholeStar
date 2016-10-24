<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class GetOrdersCall extends BaseCall {
	
	private $requestToken;
	private $devID;
	private $appID;
	private $certID;
	private $serverUrl;
	private $compatLevel;
	private $siteID;
	private $verb;
	
	public function __construct($userRequestToken, $developerID, $applicationID, $certificateID, $serverUrl,
									$compatabilityLevel, $siteToUseID)
		{
			$this->requestToken = $userRequestToken;
			$this->devID = $developerID;
			$this->appID = $applicationID;
			$this->certID = $certificateID;
			$this->compatLevel = $compatabilityLevel;
			$this->siteID = $siteToUseID;
			$this->verb = "GetOrders";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function find($query) {
	    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<GetOrdersRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";


	    //$requestXmlBody .= '<DetailLevel>ReturnAll</DetailLevel>';

	    if(isset($query['create_time_from']) && isset($query['create_time_to'])) {
	    	$requestXmlBody .= '<CreateTimeFrom>'.$this->formatDate($query['create_time_from']).'</CreateTimeFrom>';
	    	$requestXmlBody .= '<CreateTimeTo>'.$this->formatDate($query['create_time_to']).'</CreateTimeTo>';
	    }
	    
	    
	    if(isset($query['mod_time_from']) && isset($query['mod_time_to'])) {
	    	$requestXmlBody .= '<ModTimeFrom>'.$this->formatDate($query['mod_time_from']).'</ModTimeFrom>';
	    	$requestXmlBody .= '<ModTimeTo>'.$this->formatDate($query['mod_time_to']).'</ModTimeTo>';
	    }
	    
	    if(isset($query['number_of_days'])) {
	    	$requestXmlBody .= '<NumberOfDays>'.$query['number_of_days'].'</NumberOfDays>';
	    }

        if(isset($query['order_ids']) && count($query['order_ids']) > 0) {
            $requestXmlBody .= '<OrderIDArray>';
            foreach($query['order_ids'] as $order_id) {
                $requestXmlBody .= '<OrderID>'. $order_id .'</OrderID>';
            }
            $requestXmlBody .= '</OrderIDArray>';
        }

	    if(isset($query['pagination'])) {
	    	$requestXmlBody .= '<Pagination>';
	    	$requestXmlBody .= '<EntriesPerPage>'.$query['pagination']['entries_per_page'].'</EntriesPerPage>';
	    	$requestXmlBody .= '<PageNumber>'.$query['pagination']['page_number'].'</PageNumber>';
	    	$requestXmlBody .= '</Pagination>';
	    }
	    
	    //Ascending
	    //Descending
	    if(isset($query['sorting_order'])) {
	    	$requestXmlBody .= '<SortingOrder>'.$query['sorting_order'].'</SortingOrder>';
	    }
	     
	    $requestXmlBody .= '</GetOrdersRequest>';
	    
	  
	    $session = new EbaySession($this->requestToken, $this->devID, $this->appID, $this->certID, $this->serverUrl, $this->compatLevel, $this->siteID, $this->verb);
	    $responseDoc = $this->call($requestXmlBody, $session);
	    
	    $response = array();
	    $errors = $this->getErrors();
	    if(!empty($errors)) {
	    	$response['errors'] = $errors;
	    }
	    
	    $paginationResult = $responseDoc->getElementsByTagName('PaginationResult');
	    if($paginationResult->length > 0) {
	    	$response['pagination_result'] = array();
	    	$paginationResult = $paginationResult->item(0);
	    	$response['pagination_result']['total_number_of_pages'] = $paginationResult->getElementsByTagName('TotalNumberOfPages')->item(0)->nodeValue;
	    	$response['pagination_result']['total_number_of_entries'] = $paginationResult->getElementsByTagName('TotalNumberOfEntries')->item(0)->nodeValue;
	    }
	    
	    if($responseDoc->getElementsByTagName('OrdersPerPage')->length > 0) {
	    	$response['orders_per_page'] = $responseDoc->getElementsByTagName('OrdersPerPage')->item(0)->nodeValue;
	    }
	    
	    if($responseDoc->getElementsByTagName('PageNumber')->length > 0) {
	    	$response['page_number'] = $responseDoc->getElementsByTagName('PageNumber')->item(0)->nodeValue;
	    }
	    
	    $response['orders'] = array();
	    $orders = $responseDoc->getElementsByTagName('Order');
	    if($orders->length > 0) {
	    	foreach ($orders as $order) {
	    		$ebayOrder = array();
	    		$ebayOrder['id'] = $order->getElementsByTagName('OrderID')->item(0)->nodeValue;
	    		if($order->getElementsByTagName('AmountPaid')->length > 0) {
	    			$ebayOrder['amount_paid'] = $order->getElementsByTagName('AmountPaid')->item(0)->nodeValue;
	    		}
	    		
	    		if($order->getElementsByTagName('BuyerUserID')->length > 0) {
	    			$ebayOrder['buyer_user_id'] = $order->getElementsByTagName('BuyerUserID')->item(0)->nodeValue;
	    		}
	    		
	    		if($order->getElementsByTagName('CreatedTime')->length > 0) {
	    			$ebayOrder['created_time'] = $order->getElementsByTagName('CreatedTime')->item(0)->nodeValue;
	    		}
	    		
	    		if($order->getElementsByTagName('OrderStatus')->length > 0) {
	    			$ebayOrder['order_status'] = $order->getElementsByTagName('OrderStatus')->item(0)->nodeValue;
	    		}
	    		
	    		if($order->getElementsByTagName('PaidTime')->length > 0) {
	    			$ebayOrder['paid_time'] = $order->getElementsByTagName('PaidTime')->item(0)->nodeValue;
	    		}
	    		
	    		if($order->getElementsByTagName('ShippedTime')->length > 0) {
	    			$ebayOrder['shipped_time'] = $order->getElementsByTagName('ShippedTime')->item(0)->nodeValue;
	    		}
	    		
	    		if($order->getElementsByTagName('Total')->length > 0) {
	    			$ebayOrder['total'] = $order->getElementsByTagName('Total')->item(0)->nodeValue;
	    			$ebayOrder['total_currency'] = $order->getElementsByTagName('Total')->item(0)->getAttribute('currencyID');
	    		}
	    		
	    		if($order->getElementsByTagName('Subtotal')->length > 0) {
	    			$ebayOrder['subtotal'] = $order->getElementsByTagName('Subtotal')->item(0)->nodeValue;
	    		}
	    		
	    		if($order->getElementsByTagName('OrderLineItemID')->length > 0) {
	    			$ebayOrder['order_line_item_id'] = $order->getElementsByTagName('OrderLineItemID')->item(0)->nodeValue;
	    		}
	    		
	    		
	    		if($order->getElementsByTagName('BuyerCheckoutMessage')->length > 0) {
	    			$ebayOrder['buyer_checkout_message'] = $order->getElementsByTagName('BuyerCheckoutMessage')->item(0)->nodeValue;
	    		}
	    		
	    		if($order->getElementsByTagName('PaymentMethods')->length > 0) {
	    			$ebayOrder['payment_method'] = $order->getElementsByTagName('PaymentMethods')->item(0)->nodeValue;
	    		}
	    		
	    		
	    		$ebayOrder['shipping_address'] = array();
	    		foreach ($order->getElementsByTagName('ShippingAddress') as $shippingAddress) {
	    			if($shippingAddress->getElementsByTagName('AddressID')->length > 0) {
	    				$ebayOrder['shipping_address']['address_id'] = $shippingAddress->getElementsByTagName('AddressID')->item(0)->nodeValue;
	    			}
	    			
	    			if($shippingAddress->getElementsByTagName('AddressOwner')->length > 0) {
	    				$ebayOrder['shipping_address']['address_owner'] = $shippingAddress->getElementsByTagName('AddressOwner')->item(0)->nodeValue;
	    			}
	    			
	    			if($shippingAddress->getElementsByTagName('CityName')->length > 0) {
	    				$ebayOrder['shipping_address']['city_name'] = $shippingAddress->getElementsByTagName('CityName')->item(0)->nodeValue;
	    			}
	    			
	    			if($shippingAddress->getElementsByTagName('Country')->length > 0) {
	    				$ebayOrder['shipping_address']['country'] = $shippingAddress->getElementsByTagName('Country')->item(0)->nodeValue;
	    			}
	    			
	    			if($shippingAddress->getElementsByTagName('ExternalAddressID')->length > 0) {
	    				$ebayOrder['shipping_address']['external_address_id'] = $shippingAddress->getElementsByTagName('ExternalAddressID')->item(0)->nodeValue;
	    			}
	    			
	    			if($shippingAddress->getElementsByTagName('Name')->length > 0) {
	    				$ebayOrder['shipping_address']['name'] = $shippingAddress->getElementsByTagName('Name')->item(0)->nodeValue;
	    			}
	    			
	    			if($shippingAddress->getElementsByTagName('Phone')->length > 0) {
	    				$ebayOrder['shipping_address']['phone'] = $shippingAddress->getElementsByTagName('Phone')->item(0)->nodeValue;
	    			}
	    			
	    			if($shippingAddress->getElementsByTagName('PostalCode')->length > 0) {
	    				$ebayOrder['shipping_address']['postal_code'] = $shippingAddress->getElementsByTagName('PostalCode')->item(0)->nodeValue;
	    			}
	    			
	    			if($shippingAddress->getElementsByTagName('StateOrProvince')->length > 0) {
	    				$ebayOrder['shipping_address']['state_or_province'] = $shippingAddress->getElementsByTagName('StateOrProvince')->item(0)->nodeValue;
	    			}
	    			
	    			if($shippingAddress->getElementsByTagName('Street1')->length > 0) {
	    				$ebayOrder['shipping_address']['street1'] = $shippingAddress->getElementsByTagName('Street1')->item(0)->nodeValue;
	    			}
	    			
	    			if($shippingAddress->getElementsByTagName('Street2')->length > 0) {
	    				$ebayOrder['shipping_address']['street2'] = $shippingAddress->getElementsByTagName('Street2')->item(0)->nodeValue;
	    			}
	    		}
	    		
	    		$shippingServiceSelected = $order->getElementsByTagName('ShippingServiceSelected');
	    		if($shippingServiceSelected->length > 0) {
	    			$ebayOrder['shipping_service'] = array();
	    			$shippingServiceSelected = $shippingServiceSelected->item(0);
	    			if($shippingServiceSelected->getElementsByTagName('ShippingService')->length > 0) {
	    				$ebayOrder['shipping_service']['shipping_service'] = $shippingServiceSelected->getElementsByTagName('ShippingService')->item(0)->nodeValue;
	    			}
	    			
	    			if($shippingServiceSelected->getElementsByTagName('ExpeditedService')->length > 0) {
	    				$ebayOrder['shipping_service']['expedited_service'] = $shippingServiceSelected->getElementsByTagName('ExpeditedService')->item(0)->nodeValue;
	    			}
	    			
	    			if($shippingServiceSelected->getElementsByTagName('ShippingServiceCost')->length > 0) {
	    				$ebayOrder['shipping_service']['shipping_service_cost'] = $shippingServiceSelected->getElementsByTagName('ShippingServiceCost')->item(0)->nodeValue;
	    			}
	    			
	    			if($shippingServiceSelected->getElementsByTagName('ShippingServiceAdditionalCost')->length > 0) {
	    				$ebayOrder['shipping_service']['shipping_service_additional_cost'] = $shippingServiceSelected->getElementsByTagName('ShippingServiceAdditionalCost')->item(0)->nodeValue;
	    			}
	    		}
	    		
	    		
	    		$TransactionArray = $order->getElementsByTagName('TransactionArray');
	    		if($TransactionArray->length > 0) {
	    			$TransactionArray = $TransactionArray->item(0);
	    			$ebayOrder['transactions'] = array();
	    			foreach ($TransactionArray->getElementsByTagName('Transaction') as $Transaction) {
	    				$eTransaction = array();
	    				
	    				if($Transaction->getElementsByTagName('QuantityPurchased')->length > 0) {
	    					$eTransaction['quantity'] = $Transaction->getElementsByTagName('QuantityPurchased')->item(0)->nodeValue;
	    				}
	    				
	    				if($Transaction->getElementsByTagName('CreatedDate')->length > 0) {
	    					$eTransaction['created_date'] = $Transaction->getElementsByTagName('CreatedDate')->item(0)->nodeValue;
	    				}
	    				
	    				if($Transaction->getElementsByTagName('OrderLineItemID')->length > 0) {
	    					$eTransaction['order_line_item_id'] = $Transaction->getElementsByTagName('OrderLineItemID')->item(0)->nodeValue;
	    				}
	    				
	    				if($Transaction->getElementsByTagName('ShippedTime')->length > 0) {
	    					$eTransaction['shipped_time'] = $Transaction->getElementsByTagName('ShippedTime')->item(0)->nodeValue;
	    				}

                        if($Transaction->getElementsByTagName('PaidTime')->length > 0) {
                            $eTransaction['paid_time'] = $Transaction->getElementsByTagName('PaidTime')->item(0)->nodeValue;
                        }
	    				
	    				if($Transaction->getElementsByTagName('ShipmentTrackingNumber')->length > 0) {
	    					$eTransaction['shipment_tracking_number'] = $Transaction->getElementsByTagName('ShipmentTrackingNumber')->item(0)->nodeValue;
	    				}
	    				
	    				if($Transaction->getElementsByTagName('ShippingCarrierUsed')->length > 0) {
	    					$eTransaction['shipping_carrier_used'] = $Transaction->getElementsByTagName('ShippingCarrierUsed')->item(0)->nodeValue;
	    				}
	    				
	    				if($Transaction->getElementsByTagName('TransactionID')->length > 0) {
	    					$eTransaction['transaction_id'] = $Transaction->getElementsByTagName('TransactionID')->item(0)->nodeValue;
	    				}
	    				
	    				if($Transaction->getElementsByTagName('TransactionPrice')->length > 0) {
	    					$eTransaction['transaction_price'] = $Transaction->getElementsByTagName('TransactionPrice')->item(0)->nodeValue;
	    				}
	    				
	    				if($Transaction->getElementsByTagName('TransactionSiteID')->length > 0) {
	    					$eTransaction['transaction_site_id'] = $Transaction->getElementsByTagName('TransactionSiteID')->item(0)->nodeValue;
	    				}
	    				
	    				if($Transaction->getElementsByTagName('Platform')->length > 0) {
	    					$eTransaction['platform'] = $Transaction->getElementsByTagName('Platform')->item(0)->nodeValue;
	    				}
	    				
	    				if($Transaction->getElementsByTagName('Buyer')->length > 0) {
	    					$Buyer = $Transaction->getElementsByTagName('Buyer')->item(0);
	    					$eTransaction['buyer'] = array();
	    					if($Buyer->getElementsByTagName('Email')->length > 0) {
	    						$eTransaction['buyer']['email'] = $Transaction->getElementsByTagName('Email')->item(0)->nodeValue;
	    					}
	    				}
	    				
	    				$item = $Transaction->getElementsByTagName('Item')->item(0);
	    				$eTransaction['item'] = array();
	    				if($item->getElementsByTagName('ItemID')->length > 0) {
	    					$eTransaction['item']['item_id'] = $item->getElementsByTagName('ItemID')->item(0)->nodeValue;
	    				}
	    				
	    				if($item->getElementsByTagName('SellerInventoryID')->length > 0) {
	    					$eTransaction['item']['seller_inventory_id'] = $item->getElementsByTagName('SellerInventoryID')->item(0)->nodeValue;
	    				}
	    				
	    				if($item->getElementsByTagName('Site')->length > 0) {
	    					$eTransaction['item']['site'] = $item->getElementsByTagName('Site')->item(0)->nodeValue;
	    				}
	    				
	    				if($item->getElementsByTagName('SKU')->length > 0) {
	    					$eTransaction['item']['sku'] = $item->getElementsByTagName('SKU')->item(0)->nodeValue;
	    				}
	    				
	    				if($item->getElementsByTagName('Title')->length > 0) {
	    					$eTransaction['item']['title'] = $item->getElementsByTagName('Title')->item(0)->nodeValue;
	    				}
	    				
	    				$Variation = $Transaction->getElementsByTagName('Variation');
	    				$eTransaction['variation'] = array();
	    				if($Variation->length > 0) {
	    					$Variation = $Variation->item(0);
	    					$eTransaction['variation']['title'] = $Variation->getElementsByTagName('VariationTitle')->item(0)->nodeValue;
	    					if($Variation->getElementsByTagName('SKU')->length > 0) {
	    						$eTransaction['variation']['sku'] = $Variation->getElementsByTagName('SKU')->item(0)->nodeValue;
	    					}
	    				}
	    				
	    				$ebayOrder['transactions'][] = $eTransaction;
	    			}
	    		}
	    		
	    		
	    		
	    		
	    		
	    		
	    		
	    		$response['orders'][] = $ebayOrder;
	    		
	    	}
	    	
	    }
	    
	    //die($responseXml);
	    return $response;
	        
	}
	
	private function formatDate($date) {
		return $date->format('Y-m-d\TH:i:s\Z');
	} 
	
}
?>