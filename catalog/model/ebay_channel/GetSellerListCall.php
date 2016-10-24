<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class GetSellerListCall extends BaseCall {
	
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
			$this->verb = "GetSellerList";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function getItems($query) {
	    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<GetSellerListRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";
	    
	    if(isset($query['start_time_from']) && isset($query['start_time_to'])) {
	    	$requestXmlBody .= '<StartTimeTo>'.$query['start_time_to'].'</StartTimeTo>';
	    	$requestXmlBody .= '<StartTimeFrom>'.$query['start_time_from'].'</StartTimeFrom>';
	    }
	    
	    if(isset($query['end_time_from']) && isset($query['end_time_to'])) {
	    	$requestXmlBody .= '<EndTimeFrom>'.$query['end_time_from'].'</EndTimeFrom>';
	    	$requestXmlBody .= '<EndTimeTo>'.$query['end_time_to'].'</EndTimeTo>';
	    }
	    
	    if(isset($query['include_variations'])) {
	    	$requestXmlBody .= '<IncludeVariations>'.(($query['include_variations'])? "true" : "false").'</IncludeVariations>';
	    }
	    
	    
	    if(isset($query['sku_array']) && !empty($query['sku_array'])) {
	    	$requestXmlBody .=  '<SKUArray>';
	    	foreach ($query['sku_array'] as $sku) {
	    		$requestXmlBody .= '<SKU>' . $sku . '</SKU>';
	    	}
	    	$requestXmlBody .=  '</SKUArray>';
	    }
	    
	    if(isset($query['include_watch_count'])) {
	    	$requestXmlBody .= '<IncludeWatchCount>'.(($query['include_watch_count'])? "true" : "false").'</IncludeWatchCount>';
	    }
	    
		if(isset($query['pagination'])) {
	    	$requestXmlBody .= '<Pagination>';
	    	$requestXmlBody .= '<EntriesPerPage>'.$query['pagination']['entries_per_page'].'</EntriesPerPage>';
	    	$requestXmlBody .= '<PageNumber>'.$query['pagination']['page_number'].'</PageNumber>';
	    	$requestXmlBody .= '</Pagination>';
	    }
	    
		if(isset($query['detail_level'])) {
	    	$requestXmlBody .= '<DetailLevel>'.$query['detail_level'].'</DetailLevel>';
	    }
	    
	    
	    $requestXmlBody .= '</GetSellerListRequest>';
	    
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
	    
	    if($responseDoc->getElementsByTagName('PageNumber')->length > 0) {
	    	$response['page_number'] = $responseDoc->getElementsByTagName('PageNumber')->item(0)->nodeValue;
	    }
	    
		if($responseDoc->getElementsByTagName('ItemsPerPage')->length > 0) {
	    	$response['items_per_page'] = $responseDoc->getElementsByTagName('ItemsPerPage')->item(0)->nodeValue;
	    }
	    
	    
	    $items = $responseDoc->getElementsByTagName('Item');
	    $response['items'] = array();
	    foreach ($items as $eItem) {
	    	$item['buy_it_now_price'] = $eItem->getElementsByTagName('BuyItNowPrice')->item(0)->nodeValue;
	    	$item['country'] = $eItem->getElementsByTagName('Country')->item(0)->nodeValue;
	    	$item['currency'] = $eItem->getElementsByTagName('Currency')->item(0)->nodeValue;
	    	$item['description'] = $eItem->getElementsByTagName('Description')->item(0)->nodeValue;
	    	$item['item_id'] = $eItem->getElementsByTagName('ItemID')->item(0)->nodeValue;
	    	$item['listing_duration'] = $eItem->getElementsByTagName('ListingDuration')->item(0)->nodeValue;
	    	$item['listing_type'] = $eItem->getElementsByTagName('ListingType')->item(0)->nodeValue;
	    	$item['location'] = $eItem->getElementsByTagName('Location')->item(0)->nodeValue;
	    	$item['quantity'] = $eItem->getElementsByTagName('Quantity')->item(0)->nodeValue;
	    	$item['reserve_price'] = $eItem->getElementsByTagName('ReservePrice')->item(0)->nodeValue;
	    	$item['start_price'] = $eItem->getElementsByTagName('StartPrice')->item(0)->nodeValue;
	    	$item['title'] = $eItem->getElementsByTagName('Title')->item(0)->nodeValue;
	    	
	    	
	    	$site = $eItem->getElementsByTagName('Site');
	    	if($site->length > 0) {
	    		$item['site'] = $eItem->getElementsByTagName('Site')->item(0)->nodeValue;
	    	} 
	    	
	    	$PrimaryCategory = $eItem->getElementsByTagName('PrimaryCategory');
	    	if($PrimaryCategory->length > 0) {
	    		$item['category_id'] = $PrimaryCategory->item(0)->getElementsByTagName('CategoryID')->item(0)->nodeValue;
	    		$item['category_name'] = $PrimaryCategory->item(0)->getElementsByTagName('CategoryName')->item(0)->nodeValue;
	    	}
	    	
	    	$Storefront = $eItem->getElementsByTagName('Storefront');
	    	if($Storefront->length > 0) {
	    		$item['store_category_id'] = $Storefront->item(0)->getElementsByTagName('StoreCategoryID')->item(0)->nodeValue;
	    		$item['store_category2_id'] = $Storefront->item(0)->getElementsByTagName('StoreCategory2ID')->item(0)->nodeValue;
	    	}

            $SellingStatus = $eItem->getElementsByTagName('SellingStatus');
            $item['quantity_sold'] = 0;
            if($SellingStatus->length > 0) {
                $SellingStatus = $SellingStatus->item(0);
                if($SellingStatus->getElementsByTagName('CurrentPrice')->length > 0) {
                    $item['start_price'] = $SellingStatus->getElementsByTagName('CurrentPrice')->item(0)->nodeValue;
                }

                if($SellingStatus->getElementsByTagName('QuantitySold')->length > 0) {
                    $quantitySold = $SellingStatus->getElementsByTagName('QuantitySold')->item(0)->nodeValue;
                    $item['quantity'] = $item['quantity'] - $quantitySold;
                }
            }

	    	$SKU = $eItem->getElementsByTagName('SKU');
	    	$item['sku'] = '';
	    	if($SKU->length > 0) {
	    		$item['sku'] = $SKU->item(0)->nodeValue;
	    	}
	    	
	    	$PostalCode = $eItem->getElementsByTagName('PostalCode');
	    	$item['postal_code'] = '';
	    	if($PostalCode->length > 0) {
	    		$item['postal_code'] = $PostalCode->item(0)->nodeValue;
	    	}
	    	
	    	$PostalCode = $eItem->getElementsByTagName('PostalCode');
	    	$item['postal_code'] = '';
	    	if($PostalCode->length > 0) {
	    		$item['postal_code'] = $PostalCode->item(0)->nodeValue;
	    	}
	    	
	    	$Country = $eItem->getElementsByTagName('Country');
	    	$item['country'] = '';
	    	if($Country->length > 0) {
	    		$item['country'] = $Country->item(0)->nodeValue;
	    	}
	    	
	    	$ConditionDescription = $eItem->getElementsByTagName('ConditionDescription');
	    	$item['condition_description'] = '';
	    	if($ConditionDescription->length > 0) {
	    		$item['condition_description'] = $ConditionDescription->item(0)->nodeValue;
	    	}
	    	
	    	$ConditionID = $eItem->getElementsByTagName('ConditionID');
	    	$item['condition_id'] = '';
	    	if($ConditionID->length > 0) {
	    		$item['condition_id'] = $ConditionID->item(0)->nodeValue;
	    	}
	    	
	    	$ConditionDisplayName = $eItem->getElementsByTagName('ConditionDisplayName');
	    	$item['condition_display_name'] = '';
	    	if($ConditionDisplayName->length > 0) {
	    		$item['condition_display_name'] = $ConditionDisplayName->item(0)->nodeValue;
	    	}
	    	
	    	$PrivateListing = $eItem->getElementsByTagName('PrivateListing');
	    	$item['private_listing'] = 'false';
	    	if($PrivateListing->length > 0) {
	    		$item['private_listing'] = $PrivateListing->item(0)->nodeValue;
	    	}
	    	
	    	$PayPalEmailAddress = $eItem->getElementsByTagName('PayPalEmailAddress');
	    	$item['paypal_email_address'] = '';
	    	if($PayPalEmailAddress->length > 0) {
	    		$item['paypal_email_address'] = $PayPalEmailAddress->item(0)->nodeValue;
	    	}
	    	
	    	$DispatchTimeMax = $eItem->getElementsByTagName('DispatchTimeMax');
	    	$item['dispatch_time_max'] = '';
	    	if($DispatchTimeMax->length > 0) {
	    		$item['dispatch_time_max'] = $DispatchTimeMax->item(0)->nodeValue;
	    	}
	    	
	    	$listingDetails = $eItem->getElementsByTagName('ListingDetails');
	    	if($listingDetails->length > 0) {
	    		$listingDetails = $listingDetails->item(0);
	    		$item['listing_details'] = array();
	    		$item['listing_details']["start_time"] =  $listingDetails->getElementsByTagName('StartTime')->item(0)->nodeValue;
	    		$item['listing_details']["end_time"] =  $listingDetails->getElementsByTagName('EndTime')->item(0)->nodeValue;
	    		if($listingDetails->getElementsByTagName('RelistedItemID')->length > 0) {
	    			$item['listing_details']["relisted_item_id"] =  $listingDetails->getElementsByTagName('RelistedItemID')->item(0)->nodeValue;
	    		}
	    	}
	    	
	    	
	    	$ReturnPolicy = $eItem->getElementsByTagName('ReturnPolicy');
	    	$item['return_policy'] = array(
	    			'description'=>'',
	    			'returns_accepted'=>'',
	    			'returns_accepted_option'=>'',
	    			'returns_within'=>'',
	    			'shipping_cost_paid_by'=>'',
	    			'shipping_cost_paid_by_option'=>'',
	    			'refund_option'=>'',
	    			'refund'=>''
	    	);
	    	if($ReturnPolicy->length > 0) {
	    		$ReturnPolicy = $ReturnPolicy->item(0);
	    		
	    		$value = $ReturnPolicy->getElementsByTagName('Description');
	    		if($value->length > 0) {
	    			$item['return_policy']['description'] = $value->item(0)->nodeValue;
	    		}
	    		
	    		$value = $ReturnPolicy->getElementsByTagName('ReturnsAccepted');
	    		if($value->length > 0) {
	    			$item['return_policy']['returns_accepted'] = $value->item(0)->nodeValue;
	    		}
	    		
	    		$value = $ReturnPolicy->getElementsByTagName('Refund');
	    		if($value->length > 0) {
	    			$item['return_policy']['refund'] = $value->item(0)->nodeValue;
	    		}
	    		
	    		$value = $ReturnPolicy->getElementsByTagName('RefundOption');
	    		if($value->length > 0) {
	    			$item['return_policy']['refund_option'] = $value->item(0)->nodeValue;
	    		}
	    		
	    		$value = $ReturnPolicy->getElementsByTagName('ReturnsAcceptedOption');
	    		if($value->length > 0) {
	    			$item['return_policy']['returns_accepted_option'] = $value->item(0)->nodeValue;
	    		}
	    		
	    		$value = $ReturnPolicy->getElementsByTagName('ReturnsWithin');
	    		if($value->length > 0) {
	    			$item['return_policy']['returns_within'] = $value->item(0)->nodeValue;
	    		}
	    		
	    		$value = $ReturnPolicy->getElementsByTagName('ReturnsWithinOption');
	    		if($value->length > 0) {
	    			$item['return_policy']['returns_within_option'] = $value->item(0)->nodeValue;
	    		}
	    		
	    		$value = $ReturnPolicy->getElementsByTagName('ShippingCostPaidBy');
	    		if($value->length > 0) {
	    			$item['return_policy']['shipping_cost_paid_by'] = $value->item(0)->nodeValue;
	    		}
	    		
	    		$value = $ReturnPolicy->getElementsByTagName('ShippingCostPaidByOption');
	    		if($value->length > 0) {
	    			$item['return_policy']['shipping_cost_paid_by_option'] = $value->item(0)->nodeValue;
	    		}
	    	}
	    	
	    	
	    	$ShippingDetails = $eItem->getElementsByTagName('ShippingDetails');
	    	$item['shipping_details'] = array(
	    			'shipping_type'=>'',
	    			'calculated_shipping_rate'=>array()
	    	);
	    	if($ShippingDetails->length > 0) {
	    		$ShippingDetails = $ShippingDetails->item(0);
	    		 
	    		$value = $ShippingDetails->getElementsByTagName('ShippingType');
	    		if($value->length > 0) {
	    			$item['shipping_details']['shipping_type'] = $value->item(0)->nodeValue;
	    		}
	    		
	    		$value = $ShippingDetails->getElementsByTagName('InternationalShippingServiceOption');
	    		if($value->length > 0) {
	    			$item['shipping_details']['international_shipping_service_option'] = array();
	    			foreach ($value as $shippingServiceOption) {
	    				$a = array();
	    				$s = $shippingServiceOption->getElementsByTagName('ShippingService');
	    				if($s->length > 0) {
	    					$a['shipping_service'] = $s->item(0)->nodeValue;
	    				}
	    				
	    				$s = $shippingServiceOption->getElementsByTagName('ShipToLocation');
	    				if($s->length > 0) {
	    					$a['ship_to_location'] = array();
	    					foreach ($s as $l) {
	    						$a['ship_to_location'][] = $l->nodeValue;
	    					}
	    				}
	    				
	    				$s = $shippingServiceOption->getElementsByTagName('ShippingServiceAdditionalCost');
	    				if($s->length > 0) {
	    					$a['shipping_service_additional_cost'] = $s->item(0)->nodeValue;
	    				}
	    				
	    				$s = $shippingServiceOption->getElementsByTagName('ShippingServiceCost');
	    				if($s->length > 0) {
	    					$a['shipping_service_cost'] = $s->item(0)->nodeValue;
	    				}
	    				
	    				$s = $shippingServiceOption->getElementsByTagName('FreeShipping');
	    				if($s->length > 0) {
	    					$a['free_shipping'] = $s->item(0)->nodeValue;
	    				}
	    				 
	    				 
	    				
	    				$item['shipping_details']['international_shipping_service_option'][] = $a;
	    			}
	    		}
	    		
	    		$value = $ShippingDetails->getElementsByTagName('ShippingServiceOptions');
	    		if($value->length > 0) {
	    			$item['shipping_details']['shipping_service_options'] = array();
	    			foreach ($value as $shippingServiceOption) {
	    				$a = array();
	    				$s = $shippingServiceOption->getElementsByTagName('ShippingService');
	    				if($s->length > 0) {
	    					$a['shipping_service'] = $s->item(0)->nodeValue;
	    				}
	    				 
	    				$s = $shippingServiceOption->getElementsByTagName('ShipToLocation');
	    				if($s->length > 0) {
	    					$a['ship_to_location'] = $s->item(0)->nodeValue;
	    				}
	    				
	    				$s = $shippingServiceOption->getElementsByTagName('ShippingServiceAdditionalCost');
	    				if($s->length > 0) {
	    					$a['shipping_service_additional_cost'] = $s->item(0)->nodeValue;
	    				}
	    				 
	    				$s = $shippingServiceOption->getElementsByTagName('ShippingServiceCost');
	    				if($s->length > 0) {
	    					$a['shipping_service_cost'] = $s->item(0)->nodeValue;
	    				}
	    				 
	    				$s = $shippingServiceOption->getElementsByTagName('FreeShipping');
	    				if($s->length > 0) {
	    					$a['free_shipping'] = $s->item(0)->nodeValue;
	    				}
	    				
	    				$item['shipping_details']['shipping_service_options'][] = $a;
	    			}
	    		}
	    		
	    		
	    		$value = $ShippingDetails->getElementsByTagName('CalculatedShippingRate');
	    		if($value->length > 0) {
	    			$value = $value->item(0);
	    			
	    			$s = $value->getElementsByTagName('PackagingHandlingCosts');
	    			if($s->length > 0) {
	    				$item['shipping_details']['calculated_shipping_rate']['packaging_handling_costs'] = $s->item(0)->nodeValue;
	    			}
	    			
	    			$s = $value->getElementsByTagName('OriginatingPostalCode');
	    			if($s->length > 0) {
	    				$item['shipping_details']['calculated_shipping_rate']['originating_postal_code'] = $s->item(0)->nodeValue;
	    			}
	    			
	    			$s = $value->getElementsByTagName('OriginatingPostalCode');
	    			if($s->length > 0) {
	    				$item['shipping_details']['calculated_shipping_rate']['originating_postal_code'] = $s->item(0)->nodeValue;
	    			}
	    			
	    			$s = $value->getElementsByTagName('ShippingIrregular');
	    			if($s->length > 0) {
	    				$item['shipping_details']['calculated_shipping_rate']['shipping_irregular'] = $s->item(0)->nodeValue;
	    			}
	    			
	    			$s = $value->getElementsByTagName('ShippingPackage');
	    			if($s->length > 0) {
	    				$item['shipping_details']['calculated_shipping_rate']['shipping_package'] = $s->item(0)->nodeValue;
	    			}
	    			
	    			$s = $value->getElementsByTagName('WeightMajor');
	    			if($s->length > 0) {
	    				$item['shipping_details']['calculated_shipping_rate']['weight_major'] = $s->item(0)->nodeValue;
	    			}
	    			
	    			$s = $value->getElementsByTagName('WeightMinor');
	    			if($s->length > 0) {
	    				$item['shipping_details']['calculated_shipping_rate']['weight_minor'] = $s->item(0)->nodeValue;
	    			}
	    		}
	    		
	    		
	    	}

            $item['shipping_package_details'] = array();
            $ShippingPackageDetails = $eItem->getElementsByTagName('ShippingPackageDetails');
            if($ShippingPackageDetails->length > 0) {
                $PackageDepth = $ShippingPackageDetails->item(0)->getElementsByTagName('PackageDepth');
                if($PackageDepth->length) {
                    $item['shipping_package_details']['package_depth']['measurement_system'] = $PackageDepth->item(0)->getAttribute('measurementSystem');
                    $item['shipping_package_details']['package_depth']['unit'] = $PackageDepth->item(0)->getAttribute('unit');
                    $item['shipping_package_details']['package_depth']['value'] = $PackageDepth->item(0)->nodeValue;
                }

                $PackageLength = $ShippingPackageDetails->item(0)->getElementsByTagName('PackageLength');
                if($PackageLength->length) {
                    $item['shipping_package_details']['package_length']['measurement_system'] = $PackageLength->item(0)->getAttribute('measurementSystem');
                    $item['shipping_package_details']['package_length']['unit'] = $PackageLength->item(0)->getAttribute('unit');
                    $item['shipping_package_details']['package_length']['value'] = $PackageLength->item(0)->nodeValue;
                }

                $PackageWidth = $ShippingPackageDetails->item(0)->getElementsByTagName('PackageWidth');
                if($PackageWidth->length) {
                    $item['shipping_package_details']['package_width']['measurement_system'] = $PackageWidth->item(0)->getAttribute('measurementSystem');
                    $item['shipping_package_details']['package_width']['unit'] = $PackageWidth->item(0)->getAttribute('unit');
                    $item['shipping_package_details']['package_width']['value'] = $PackageWidth->item(0)->nodeValue;
                }

                $WeightMajor = $ShippingPackageDetails->item(0)->getElementsByTagName('WeightMajor');
                if($WeightMajor->length) {
                    $item['shipping_package_details']['weight_major']['measurement_system'] = $WeightMajor->item(0)->getAttribute('measurementSystem');
                    $item['shipping_package_details']['weight_major']['unit'] = $WeightMajor->item(0)->getAttribute('unit');
                    $item['shipping_package_details']['weight_major']['value'] = $WeightMajor->item(0)->nodeValue;
                }

                $WeightMinor = $ShippingPackageDetails->item(0)->getElementsByTagName('WeightMinor');
                if($WeightMinor->length) {
                    $item['shipping_package_details']['weight_minor']['measurement_system'] = $WeightMinor->item(0)->getAttribute('measurementSystem');
                    $item['shipping_package_details']['weight_minor']['unit'] = $WeightMinor->item(0)->getAttribute('unit');
                    $item['shipping_package_details']['weight_minor']['value'] = $WeightMinor->item(0)->nodeValue;
                }
            }
	    	
	    	
	    	$PaymentMethods = $eItem->getElementsByTagName('PaymentMethods');
	    	if($PaymentMethods->length > 0) {
	    		$item['payment_methods'] = array();
	    		foreach ($PaymentMethods as $PaymentMethod) {
	    			$item['payment_methods'][] = $PaymentMethod->nodeValue;
	    		}
	    	}
	    	
	    	
	    	$pictures = $eItem->getElementsByTagName('PictureURL');
	    	if($pictures->length > 0) {
	    		$item['pictures'] = array();
		    	foreach ($pictures as $picture) {
		    		$item['pictures'][] = $picture->nodeValue;
		    	}
	    	}
	    	
	    	$variations = $eItem->getElementsByTagName('Variations');
	    	if($variations->length > 0) {
	    		$item['variations'] = array();
	    		$variationList = $variations->item(0)->getElementsByTagName('Variation');
	    		foreach ($variationList as $variation) {
	    			$myVariation = array();
	    			$myVariation['price'] = $variation->getElementsByTagName('StartPrice')->item(0)->nodeValue;
	    			$myVariation['quantity'] = $variation->getElementsByTagName('Quantity')->item(0)->nodeValue;
	    			
	    			$vSku = $variation->getElementsByTagName('SKU');
	    			if($vSku->length > 0) {
	    				$myVariation['sku'] = $vSku->item(0)->nodeValue;
	    			}
	    			
	    			$VariationSpecifics = $variation->getElementsByTagName('VariationSpecifics');
	    			if($VariationSpecifics->length > 0) {
	    				$myVariation['variation_specifics'] = array();
	    				$NameValueList = $VariationSpecifics->item(0)->getElementsByTagName('NameValueList');
	    				foreach ($NameValueList as $ValueList) {
	    					$Name = $ValueList->getElementsByTagName('Name')->item(0)->nodeValue;
	    					$Value = $ValueList->getElementsByTagName('Value')->item(0)->nodeValue;
	    					$nameValue = array('name' => $Name, 'value'=>$Value);
	    					$myVariation['variation_specifics'][] = $nameValue;
	    				}
	    			}
	    			
	    			$item['variations'][] = $myVariation; 
	    		}
	    	}

	    	$response['items'][] = $item;
	    }
	   return $response;
	}
	
	
}
?>