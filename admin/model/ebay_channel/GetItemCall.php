<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class GetItemCall extends BaseCall {

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
		$this->verb = "GetItem";
		$this->serverUrl = $serverUrl;
	}

	public function getItem($query) {
		$requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
		$requestXmlBody .= '<GetItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";



		if(isset($query['include_cross_promotion'])) {
			$requestXmlBody .= '<IncludeCrossPromotion>'.(($query['include_cross_promotion'])? "true" : "false").'</IncludeCrossPromotion>';
		}


		if(isset($query['include_watch_count'])) {
			$requestXmlBody .= '<IncludeWatchCount>'.(($query['include_watch_count'])? "true" : "false").'</IncludeWatchCount>';
		}

		if(isset($query['include_item_compatibility_list'])) {
			$requestXmlBody .= '<IncludeItemCompatibilityList>'.(($query['include_item_compatibility_list'])? "true" : "false").'</IncludeItemCompatibilityList>';
		}

		if(isset($query['include_item_specifics'])) {
			$requestXmlBody .= '<IncludeItemSpecifics>'.(($query['include_item_specifics'])? "true" : "false").'</IncludeItemSpecifics>';
		}

		if(isset($query['include_tax_table'])) {
			$requestXmlBody .= '<IncludeTaxTable>'.(($query['include_tax_table'])? "true" : "false").'</IncludeTaxTable>';
		}

		if(isset($query['item_id'])) {
			$requestXmlBody .= '<ItemID>'.$query['item_id'].'</ItemID>';
		}

		if(isset($query['sku'])) {
			$requestXmlBody .= '<SKU>'.$query['sku'].'</SKU>';
		}

		if(isset($query['transaction_id'])) {
			$requestXmlBody .= '<TransactionID>'.$query['transaction_id'].'</TransactionID>';
		}

		if(isset($query['variation_sku'])) {
			$requestXmlBody .= '<VariationSKU>'.$query['variation_sku'].'</VariationSKU>';
		}

		if(isset($query['detail_level'])) {
			$requestXmlBody .= '<DetailLevel>'.$query['detail_level'].'</DetailLevel>';
		}


		$requestXmlBody .= '</GetItemRequest>';

		$session = new EbaySession($this->requestToken, $this->devID, $this->appID, $this->certID, $this->serverUrl, $this->compatLevel, $this->siteID, $this->verb);
		$responseDoc = $this->call($requestXmlBody, $session);


		$response = array();
		$errors = $this->getErrors();
		if(!empty($errors)) {
			$response['errors'] = $errors;
		} else {
			$item = $responseDoc->getElementsByTagName('Item');
			if($item->length > 0) {
				$item = $item->item(0);
				$response['item'] = array();
				$response['item']['ItemID'] = $item->getElementsByTagName('ItemID')->item(0)->nodeValue;
				$response['item']['StartPrice'] = $item->getElementsByTagName('StartPrice')->item(0)->nodeValue;
				$response['item']['Quantity'] = $item->getElementsByTagName('Quantity')->item(0)->nodeValue;
				$response['item']['StartTime'] = $item->getElementsByTagName('StartTime')->item(0)->nodeValue;
				$response['item']['EndTime'] = $item->getElementsByTagName('EndTime')->item(0)->nodeValue;


				$response['item']['buy_it_now_price'] = $item->getElementsByTagName('BuyItNowPrice')->item(0)->nodeValue;
				$response['item']['country'] = $item->getElementsByTagName('Country')->item(0)->nodeValue;
				$response['item']['currency'] = $item->getElementsByTagName('Currency')->item(0)->nodeValue;


				$response['item']['item_id'] = $item->getElementsByTagName('ItemID')->item(0)->nodeValue;
				$response['item']['listing_duration'] = $item->getElementsByTagName('ListingDuration')->item(0)->nodeValue;
				$response['item']['listing_type'] = $item->getElementsByTagName('ListingType')->item(0)->nodeValue;
				$response['item']['location'] = $item->getElementsByTagName('Location')->item(0)->nodeValue;
				$response['item']['quantity'] = $item->getElementsByTagName('Quantity')->item(0)->nodeValue;
				$response['item']['reserve_price'] = $item->getElementsByTagName('ReservePrice')->item(0)->nodeValue;
				$response['item']['start_price'] = $item->getElementsByTagName('StartPrice')->item(0)->nodeValue;
				$response['item']['title'] = $item->getElementsByTagName('Title')->item(0)->nodeValue;

				$response['item']['description'] = '';
				if($item->getElementsByTagName('Description')->length > 0) {
					$d = $item->getElementsByTagName('Description')->item(0)->nodeValue;
					$response['item']['description'] = $d;
				}

				if($item->getElementsByTagName('ViewItemURL')->length > 0) {
					$response['item']['view_item_url'] = $item->getElementsByTagName('ViewItemURL')->item(0)->nodeValue;
				}

				if($item->getElementsByTagName('SellingStatus')->length > 0) {
					$SellingStatus = $item->getElementsByTagName('SellingStatus')->item(0);
					if($SellingStatus->getElementsByTagName('CurrentPrice')->length > 0) {
						$response['item']['current_price'] = $SellingStatus->getElementsByTagName('CurrentPrice')->item(0)->nodeValue;
					}

					if($SellingStatus->getElementsByTagName('QuantitySold')->length > 0) {
						$QuantitySold = $SellingStatus->getElementsByTagName('QuantitySold')->item(0)->nodeValue;
						$response['item']['quantity'] = $response['item']['quantity'] - $QuantitySold;
					}
				}



				$site = $item->getElementsByTagName('Site');
				if($site->length > 0) {
					$response['item']['site'] = $item->getElementsByTagName('Site')->item(0)->nodeValue;
				}

				$PrimaryCategory = $item->getElementsByTagName('PrimaryCategory');
				if($PrimaryCategory->length > 0) {
					$response['item']['category_id'] = $PrimaryCategory->item(0)->getElementsByTagName('CategoryID')->item(0)->nodeValue;
					$response['item']['category_name'] = $PrimaryCategory->item(0)->getElementsByTagName('CategoryName')->item(0)->nodeValue;
				}

				$Storefront = $item->getElementsByTagName('Storefront');
				if($Storefront->length > 0) {
					$response['item']['store_category_id'] = $Storefront->item(0)->getElementsByTagName('StoreCategoryID')->item(0)->nodeValue;
					$response['item']['store_category2_id'] = $Storefront->item(0)->getElementsByTagName('StoreCategory2ID')->item(0)->nodeValue;
				}


				$SKU = $item->getElementsByTagName('SKU');
				$response['item']['sku'] = '';
				if($SKU->length > 0) {
					$response['item']['sku'] = $SKU->item(0)->nodeValue;
				}

				$PostalCode = $item->getElementsByTagName('PostalCode');
				$response['item']['postal_code'] = '';
				if($PostalCode->length > 0) {
					$response['item']['postal_code'] = $PostalCode->item(0)->nodeValue;
				}

				$PostalCode = $item->getElementsByTagName('PostalCode');
				$response['item']['postal_code'] = '';
				if($PostalCode->length > 0) {
					$response['item']['postal_code'] = $PostalCode->item(0)->nodeValue;
				}

				$Country = $item->getElementsByTagName('Country');
				$response['item']['country'] = '';
				if($Country->length > 0) {
					$response['item']['country'] = $Country->item(0)->nodeValue;
				}

				$ConditionDescription = $item->getElementsByTagName('ConditionDescription');
				$response['item']['condition_description'] = '';
				if($ConditionDescription->length > 0) {
					$response['item']['condition_description'] = $ConditionDescription->item(0)->nodeValue;
				}

				$ConditionID = $item->getElementsByTagName('ConditionID');
				$response['item']['condition_id'] = '';
				if($ConditionID->length > 0) {
					$response['item']['condition_id'] = $ConditionID->item(0)->nodeValue;
				}

				$ConditionDisplayName = $item->getElementsByTagName('ConditionDisplayName');
				$response['item']['condition_display_name'] = '';
				if($ConditionDisplayName->length > 0) {
					$response['item']['condition_display_name'] = $ConditionDisplayName->item(0)->nodeValue;
				}

				$PrivateListing = $item->getElementsByTagName('PrivateListing');
				$response['item']['private_listing'] = 'false';
				if($PrivateListing->length > 0) {
					$response['item']['private_listing'] = $PrivateListing->item(0)->nodeValue;
				}

				$PayPalEmailAddress = $item->getElementsByTagName('PayPalEmailAddress');
				$response['item']['paypal_email_address'] = '';
				if($PayPalEmailAddress->length > 0) {
					$response['item']['paypal_email_address'] = $PayPalEmailAddress->item(0)->nodeValue;
				}

				$DispatchTimeMax = $item->getElementsByTagName('DispatchTimeMax');
				$response['item']['dispatch_time_max'] = '';
				if($DispatchTimeMax->length > 0) {
					$response['item']['dispatch_time_max'] = $DispatchTimeMax->item(0)->nodeValue;
				}

				$listingDetails = $item->getElementsByTagName('ListingDetails');
				if($listingDetails->length > 0) {
					$listingDetails = $listingDetails->item(0);
					$response['item']['listing_details'] = array();
					$response['item']['listing_details']["start_time"] =  $listingDetails->getElementsByTagName('StartTime')->item(0)->nodeValue;
					$response['item']['listing_details']["end_time"] =  $listingDetails->getElementsByTagName('EndTime')->item(0)->nodeValue;
					if($listingDetails->getElementsByTagName('RelistedItemID')->length > 0) {
						$response['item']['listing_details']["relisted_item_id"] =  $listingDetails->getElementsByTagName('RelistedItemID')->item(0)->nodeValue;
					}
				}


				$ReturnPolicy = $item->getElementsByTagName('ReturnPolicy');
				$response['item']['return_policy'] = array(
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
						$response['item']['return_policy']['description'] = $value->item(0)->nodeValue;
					}

					$value = $ReturnPolicy->getElementsByTagName('ReturnsAccepted');
					if($value->length > 0) {
						$response['item']['return_policy']['returns_accepted'] = $value->item(0)->nodeValue;
					}

					$value = $ReturnPolicy->getElementsByTagName('Refund');
					if($value->length > 0) {
						$response['item']['return_policy']['refund'] = $value->item(0)->nodeValue;
					}

					$value = $ReturnPolicy->getElementsByTagName('RefundOption');
					if($value->length > 0) {
						$response['item']['return_policy']['refund_option'] = $value->item(0)->nodeValue;
					}

					$value = $ReturnPolicy->getElementsByTagName('ReturnsAcceptedOption');
					if($value->length > 0) {
						$response['item']['return_policy']['returns_accepted_option'] = $value->item(0)->nodeValue;
					}

					$value = $ReturnPolicy->getElementsByTagName('ReturnsWithin');
					if($value->length > 0) {
						$response['item']['return_policy']['returns_within'] = $value->item(0)->nodeValue;
					}

					$value = $ReturnPolicy->getElementsByTagName('ReturnsWithinOption');
					if($value->length > 0) {
						$response['item']['return_policy']['returns_within_option'] = $value->item(0)->nodeValue;
					}

					$value = $ReturnPolicy->getElementsByTagName('ShippingCostPaidBy');
					if($value->length > 0) {
						$response['item']['return_policy']['shipping_cost_paid_by'] = $value->item(0)->nodeValue;
					}

					$value = $ReturnPolicy->getElementsByTagName('ShippingCostPaidByOption');
					if($value->length > 0) {
						$response['item']['return_policy']['shipping_cost_paid_by_option'] = $value->item(0)->nodeValue;
					}
				}

				$ShippingDetails = $item->getElementsByTagName('ShippingDetails');
				$response['item']['shipping_details'] = array(
					'shipping_type'=>'',
					'calculated_shipping_rate'=>array()
				);
				if($ShippingDetails->length > 0) {
					$ShippingDetails = $ShippingDetails->item(0);

					$value = $ShippingDetails->getElementsByTagName('ShippingType');
					if($value->length > 0) {
						$response['item']['shipping_details']['shipping_type'] = $value->item(0)->nodeValue;
					}

					$value = $ShippingDetails->getElementsByTagName('InternationalShippingServiceOption');
					if($value->length > 0) {
						$response['item']['shipping_details']['international_shipping_service_option'] = array();
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



							$response['item']['shipping_details']['international_shipping_service_option'][] = $a;
						}
					}

					$value = $ShippingDetails->getElementsByTagName('ShippingServiceOptions');
					if($value->length > 0) {
						$response['item']['shipping_details']['shipping_service_options'] = array();
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

							$response['item']['shipping_details']['shipping_service_options'][] = $a;
						}
					}


					$value = $ShippingDetails->getElementsByTagName('CalculatedShippingRate');
					if($value->length > 0) {
						$value = $value->item(0);

						$s = $value->getElementsByTagName('PackagingHandlingCosts');
						if($s->length > 0) {
							$response['item']['shipping_details']['calculated_shipping_rate']['packaging_handling_costs'] = $s->item(0)->nodeValue;
						}

						$s = $value->getElementsByTagName('OriginatingPostalCode');
						if($s->length > 0) {
							$response['item']['shipping_details']['calculated_shipping_rate']['originating_postal_code'] = $s->item(0)->nodeValue;
						}

						$s = $value->getElementsByTagName('OriginatingPostalCode');
						if($s->length > 0) {
							$response['item']['shipping_details']['calculated_shipping_rate']['originating_postal_code'] = $s->item(0)->nodeValue;
						}

						$s = $value->getElementsByTagName('ShippingIrregular');
						if($s->length > 0) {
							$response['item']['shipping_details']['calculated_shipping_rate']['shipping_irregular'] = $s->item(0)->nodeValue;
						}

						$s = $value->getElementsByTagName('ShippingPackage');
						if($s->length > 0) {
							$response['item']['shipping_details']['calculated_shipping_rate']['shipping_package'] = $s->item(0)->nodeValue;
						}

						$s = $value->getElementsByTagName('WeightMajor');
						if($s->length > 0) {
							$response['item']['shipping_details']['calculated_shipping_rate']['weight_major'] = $s->item(0)->nodeValue;
						}

						$s = $value->getElementsByTagName('WeightMinor');
						if($s->length > 0) {
							$response['item']['shipping_details']['calculated_shipping_rate']['weight_minor'] = $s->item(0)->nodeValue;
						}
					}


				}



				$response['item']['shipping_package_details'] = array();
				$ShippingPackageDetails = $item->getElementsByTagName('ShippingPackageDetails');
				if($ShippingPackageDetails->length > 0) {
					$PackageDepth = $ShippingPackageDetails->item(0)->getElementsByTagName('PackageDepth');
					if($PackageDepth->length) {
						$response['item']['shipping_package_details']['package_depth']['measurement_system'] = $PackageDepth->item(0)->getAttribute('measurementSystem');
						$response['item']['shipping_package_details']['package_depth']['unit'] = $PackageDepth->item(0)->getAttribute('unit');
						$response['item']['shipping_package_details']['package_depth']['value'] = $PackageDepth->item(0)->nodeValue;
					}

					$PackageLength = $ShippingPackageDetails->item(0)->getElementsByTagName('PackageLength');
					if($PackageLength->length) {
						$response['item']['shipping_package_details']['package_length']['measurement_system'] = $PackageLength->item(0)->getAttribute('measurementSystem');
						$response['item']['shipping_package_details']['package_length']['unit'] = $PackageLength->item(0)->getAttribute('unit');
						$response['item']['shipping_package_details']['package_length']['value'] = $PackageLength->item(0)->nodeValue;
					}

					$PackageWidth = $ShippingPackageDetails->item(0)->getElementsByTagName('PackageWidth');
					if($PackageWidth->length) {
						$response['item']['shipping_package_details']['package_width']['measurement_system'] = $PackageWidth->item(0)->getAttribute('measurementSystem');
						$response['item']['shipping_package_details']['package_width']['unit'] = $PackageWidth->item(0)->getAttribute('unit');
						$response['item']['shipping_package_details']['package_width']['value'] = $PackageWidth->item(0)->nodeValue;
					}

					$WeightMajor = $ShippingPackageDetails->item(0)->getElementsByTagName('WeightMajor');
					if($WeightMajor->length) {
						$response['item']['shipping_package_details']['weight_major']['measurement_system'] = $WeightMajor->item(0)->getAttribute('measurementSystem');
						$response['item']['shipping_package_details']['weight_major']['unit'] = $WeightMajor->item(0)->getAttribute('unit');
						$response['item']['shipping_package_details']['weight_major']['value'] = $WeightMajor->item(0)->nodeValue;
					}

					$WeightMinor = $ShippingPackageDetails->item(0)->getElementsByTagName('WeightMinor');
					if($WeightMinor->length) {
						$response['item']['shipping_package_details']['weight_minor']['measurement_system'] = $WeightMinor->item(0)->getAttribute('measurementSystem');
						$response['item']['shipping_package_details']['weight_minor']['unit'] = $WeightMinor->item(0)->getAttribute('unit');
						$response['item']['shipping_package_details']['weight_minor']['value'] = $WeightMinor->item(0)->nodeValue;
					}


				}




				$PaymentMethods = $item->getElementsByTagName('PaymentMethods');
				if($PaymentMethods->length > 0) {
					$response['item']['payment_methods'] = array();
					foreach ($PaymentMethods as $PaymentMethod) {
						$response['item']['payment_methods'][] = $PaymentMethod->nodeValue;
					}
				}


				$pictures = $item->getElementsByTagName('PictureURL');
				if($pictures->length > 0) {
					$response['item']['pictures'] = array();
					foreach ($pictures as $picture) {
						$response['item']['pictures'][] = $picture->nodeValue;
					}
				}

				$variations = $item->getElementsByTagName('Variations');
				if($variations->length > 0) {
					$response['item']['variations'] = array();
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

						$response['item']['variations'][] = $myVariation;
					}
				}

				if($item->getElementsByTagName('QuantityThreshold')->length > 0) {
					$response['item']['QuantityThreshold'] = $item->getElementsByTagName('QuantityThreshold')->item(0)->nodeValue;
				}


				$ItemSpecifics = $item->getElementsByTagName('ItemSpecifics');
				$response['item_specifics'] = array();
				if($ItemSpecifics->length > 0) {
					$ItemSpecifics = $ItemSpecifics->item(0);
					$NameValueList = $ItemSpecifics->getElementsByTagName('NameValueList');
					foreach ($NameValueList as $NameValue) {
						$response['item_specifics'][] = array(
							"name" => $NameValue->getElementsByTagName('Name')->item(0)->nodeValue,
							"source" => ($NameValue->getElementsByTagName('Source')->length > 0)? $NameValue->getElementsByTagName('Source')->item(0)->nodeValue : '',
							"value" => $NameValue->getElementsByTagName('Value')->item(0)->nodeValue
						);
					}

				}


			}
		}


		return $response;

	}

	private function formatDate($date) {
		return $date->format('Y-m-d\TH:i:s\Z');
	}

}
?>