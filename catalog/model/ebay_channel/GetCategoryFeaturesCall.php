<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class GetCategoryFeaturesCall extends BaseCall {
	
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
			$this->verb = "GetCategoryFeatures";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function getFeatures($categoryId, $detailLevels) {
	    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<GetCategoryFeaturesRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";
		$requestXmlBody .= '<CategoryID>'. $categoryId  .'</CategoryID>';	
	    foreach ($detailLevels as $detailLevel) {
			$requestXmlBody .= '<DetailLevel>'. $detailLevel  .'</DetailLevel>';	    	
	    }
		
	    $requestXmlBody .= '</GetCategoryFeaturesRequest>';
	    
	    $session = new EbaySession($this->requestToken, $this->devID, $this->appID, $this->certID, $this->serverUrl, $this->compatLevel, $this->siteID, $this->verb);
	    $responseDoc = $this->call($requestXmlBody, $session);
	    
	    $response = array();
	 	$errors = $this->getErrors();
	    if(!empty($errors)) {
	    	foreach ($errors as $error) {
	    		if($error['severity_code'] == 'Error') {
	    			throw new Exception($error['long_message'], $error['code']);
	    		}
	    	}
	    }
	    
	    	$features = array();
	    	//Site Defaults
	    	foreach ($responseDoc->getElementsByTagName('SiteDefaults') as $xmlSiteDefault) {
				if($xmlSiteDefault->getElementsByTagName('ConditionEnabled')->length > 0) {
	    			$features['ConditionEnabled'] = $xmlSiteDefault->getElementsByTagName('ConditionEnabled')->item(0)->nodeValue;
				} 
				
				if($xmlSiteDefault->getElementsByTagName('PayPalRequired')->length > 0) {
	    			$features['PayPalRequired'] = $xmlSiteDefault->getElementsByTagName('PayPalRequired')->item(0)->nodeValue;
				}

                if($xmlSiteDefault->getElementsByTagName('UPCEnabled')->length > 0) {
                    $features['UPCEnabled'] = $xmlSiteDefault->getElementsByTagName('UPCEnabled')->item(0)->nodeValue;
                }

                if($xmlSiteDefault->getElementsByTagName('EANEnabled')->length > 0) {
                    $features['EANEnabled'] = $xmlSiteDefault->getElementsByTagName('EANEnabled')->item(0)->nodeValue;
                }

                if($xmlSiteDefault->getElementsByTagName('ISBNEnabled')->length > 0) {
                    $features['ISBNEnabled'] = $xmlSiteDefault->getElementsByTagName('ISBNEnabled')->item(0)->nodeValue;
                }

                if($xmlSiteDefault->getElementsByTagName('BrandMPNIdentifierEnabled')->length > 0) {
                    $features['BrandMPNEnabled'] = $xmlSiteDefault->getElementsByTagName('BrandMPNIdentifierEnabled')->item(0)->nodeValue;
                }

	    		if($xmlSiteDefault->getElementsByTagName('HandlingTimeEnabled')->length > 0) {
	    			$features['HandlingTimeEnabled'] = $xmlSiteDefault->getElementsByTagName('HandlingTimeEnabled')->item(0)->nodeValue;
				}
				
	    		if($xmlSiteDefault->getElementsByTagName('ItemSpecificsEnabled')->length > 0) {
	    			$features['ItemSpecificsEnabled'] = $xmlSiteDefault->getElementsByTagName('ItemSpecificsEnabled')->item(0)->nodeValue;
				}
				
	    		if($xmlSiteDefault->getElementsByTagName('VariationsEnabled')->length > 0) {
	    			$features['VariationsEnabled'] = $xmlSiteDefault->getElementsByTagName('VariationsEnabled')->item(0)->nodeValue;
				}
				
				if($xmlSiteDefault->getElementsByTagName('ReturnPolicyEnabled')->length > 0) {
	    			$features['ReturnPolicyEnabled'] = $xmlSiteDefault->getElementsByTagName('ReturnPolicyEnabled')->item(0)->nodeValue;
				} 
				
	    		if($xmlSiteDefault->getElementsByTagName('MinimumReservePrice')->length > 0) {
	    			$features['MinimumReservePrice'] = $xmlSiteDefault->getElementsByTagName('MinimumReservePrice')->item(0)->nodeValue;
				}
				
				if($xmlSiteDefault->getElementsByTagName('ReviseQuantityAllowed')->length > 0) {
	    			$features['ReviseQuantityAllowed'] = $xmlSiteDefault->getElementsByTagName('ReviseQuantityAllowed')->item(0)->nodeValue;
				} 
				
				if($xmlSiteDefault->getElementsByTagName('RevisePriceAllowed')->length > 0) {
	    			$features['RevisePriceAllowed'] = $xmlSiteDefault->getElementsByTagName('RevisePriceAllowed')->item(0)->nodeValue;
				} 
				
	    		if($xmlSiteDefault->getElementsByTagName('MaxFlatShippingCost')->length > 0) {
	    			$features['MaxFlatShippingCost'] = $xmlSiteDefault->getElementsByTagName('MaxFlatShippingCost')->item(0)->nodeValue;
	    			$features['MaxFlatShippingCostCurrencyID'] = $xmlSiteDefault->getElementsByTagName('MaxFlatShippingCost')->item(0)->getAttribute('currencyID');
				}
				
				$xmlConditions = $xmlSiteDefault->getElementsByTagName('ConditionValues');
				if($xmlConditions->length > 0) {
					foreach ($xmlConditions->item(0)->getElementsByTagName('Condition') as $xmlCondition) {
						$features['ConditionValues'][] = array(
										'DisplayName' => $xmlCondition->getElementsByTagName('DisplayName')->item(0)->nodeValue,
										'ID' => $xmlCondition->getElementsByTagName('ID')->item(0)->nodeValue);
					}
				}
				if($xmlSiteDefault->getElementsByTagName('PaymentMethod')->length > 0) {
		   			foreach ($xmlSiteDefault->getElementsByTagName('PaymentMethod') as $xmlPaymentMethod) {
						$features['PaymentMethods'][] = $xmlPaymentMethod->nodeValue;
					}
				}
	    		if($xmlSiteDefault->getElementsByTagName('ListingDuration')->length > 0) {
		   			foreach ($xmlSiteDefault->getElementsByTagName('ListingDuration') as $xmlListingDuration) {
						$features['ListingDurations'][] = $xmlListingDuration->nodeValue;
					}
				}
				
				
			}
	    	
	    	//Category
	   		foreach ($responseDoc->getElementsByTagName('Category') as $xmlCategory) {
	   			$ConditionEnabled = $xmlCategory->getElementsByTagName('ConditionEnabled');
				$features['ConditionEnabled'] = ($ConditionEnabled->length>0)? $ConditionEnabled->item(0)->nodeValue : 'Disabled'; 

								
				
	   			if($xmlCategory->getElementsByTagName('PayPalRequired')->length > 0) {
	    			$features['PayPalRequired'] = $xmlCategory->getElementsByTagName('PayPalRequired')->item(0)->nodeValue;
				}

                if($xmlCategory->getElementsByTagName('UPCEnabled')->length > 0) {
                    $features['UPCEnabled'] = $xmlCategory->getElementsByTagName('UPCEnabled')->item(0)->nodeValue;
                }

                if($xmlCategory->getElementsByTagName('EANEnabled')->length > 0) {
                    $features['EANEnabled'] = $xmlCategory->getElementsByTagName('EANEnabled')->item(0)->nodeValue;
                }

                if($xmlCategory->getElementsByTagName('ISBNEnabled')->length > 0) {
                    $features['ISBNEnabled'] = $xmlCategory->getElementsByTagName('ISBNEnabled')->item(0)->nodeValue;
                }

                if($xmlCategory->getElementsByTagName('BrandMPNIdentifierEnabled')->length > 0) {
                    $features['BrandMPNEnabled'] = $xmlCategory->getElementsByTagName('BrandMPNIdentifierEnabled')->item(0)->nodeValue;
                }





				
				if($xmlCategory->getElementsByTagName('MinimumReservePrice')->length > 0) {
	    			$features['MinimumReservePrice'] = $xmlCategory->getElementsByTagName('MinimumReservePrice')->item(0)->nodeValue;
				} 
				
				if($xmlCategory->getElementsByTagName('HandlingTimeEnabled')->length > 0) {
	    			$features['HandlingTimeEnabled'] = $xmlCategory->getElementsByTagName('HandlingTimeEnabled')->item(0)->nodeValue;
				} 
				
				if($xmlCategory->getElementsByTagName('ReviseQuantityAllowed')->length > 0) {
	    			$features['ReviseQuantityAllowed'] = $xmlCategory->getElementsByTagName('ReviseQuantityAllowed')->item(0)->nodeValue;
				} 
				
				if($xmlCategory->getElementsByTagName('VariationsEnabled')->length > 0) {
	    			$features['VariationsEnabled'] = $xmlCategory->getElementsByTagName('VariationsEnabled')->item(0)->nodeValue;
				} 
				
				if($xmlCategory->getElementsByTagName('ItemSpecificsEnabled')->length > 0) {
	    			$features['ItemSpecificsEnabled'] = $xmlCategory->getElementsByTagName('ItemSpecificsEnabled')->item(0)->nodeValue;
				} 
				
				if($xmlCategory->getElementsByTagName('VariationsEnabled')->length > 0) {
	    			$features['VariationsEnabled'] = $xmlCategory->getElementsByTagName('VariationsEnabled')->item(0)->nodeValue;
				}

	   			if($xmlCategory->getElementsByTagName('ReturnPolicyEnabled')->length > 0) {
	    			$features['ReturnPolicyEnabled'] = $xmlCategory->getElementsByTagName('ReturnPolicyEnabled')->item(0)->nodeValue;
				}
				
	   			if($xmlCategory->getElementsByTagName('RevisePriceAllowed')->length > 0) {
	    			$features['RevisePriceAllowed'] = $xmlCategory->getElementsByTagName('RevisePriceAllowed')->item(0)->nodeValue;
				}
				
	   			if($xmlCategory->getElementsByTagName('MaxFlatShippingCost')->length > 0) {
	    			$features['MaxFlatShippingCost'] = $xmlCategory->getElementsByTagName('MaxFlatShippingCost')->item(0)->nodeValue;
	    			$features['MaxFlatShippingCostCurrencyID'] = $xmlCategory->getElementsByTagName('MaxFlatShippingCost')->item(0)->getAttribute('currencyID');
				}
				
				
				
				$xmlConditions = $xmlCategory->getElementsByTagName('ConditionValues');
				if($xmlConditions->length > 0) {
					foreach ($xmlConditions->item(0)->getElementsByTagName('Condition') as $xmlCondition) {
						$features['ConditionValues'][] = array(
										'DisplayName' => $xmlCondition->getElementsByTagName('DisplayName')->item(0)->nodeValue,
										'ID' => $xmlCondition->getElementsByTagName('ID')->item(0)->nodeValue);
					}
				}
				
	   			foreach ($xmlCategory->getElementsByTagName('PaymentMethod') as $xmlPaymentMethod) {
					$features['PaymentMethods'][] = $xmlPaymentMethod->nodeValue;
				}
				
	   			if($xmlCategory->getElementsByTagName('ListingDuration')->length > 0) {
		   			foreach ($xmlCategory->getElementsByTagName('ListingDuration') as $xmlListingDuration) {
						$features['ListingDurations'][] = $xmlListingDuration->nodeValue;
					}
				}
			}
			 
			//FeatureDefinitions
			foreach ($responseDoc->getElementsByTagName('FeatureDefinitions') as $xmlFeatureDefinition) {
				
				//HandlingTimeEnabled
				
				$xmlListingDurations = $xmlFeatureDefinition->getElementsByTagName('ListingDuration');
				if($xmlListingDurations->length > 0) {
					foreach ($xmlListingDurations as $xmlListingDuration) {
						$durationSetID = $xmlListingDuration->getAttribute('durationSetID');
						foreach ($xmlListingDuration->getElementsByTagName('Duration') as $duration) {
							$features['FeatureListingDurations'][] = array(
									'Duration' => $duration->nodeValue,
									'durationSetID' => $durationSetID
							);
						}
						
						if($xmlListingDuration->getElementsByTagName('ReturnPolicyEnabled')->length > 0) {
	    					$features['ReturnPolicyEnabled'] = $xmlListingDuration->getElementsByTagName('ReturnPolicyEnabled')->item(0)->nodeValue;
						} 
					} 
				}
			}
			
			return $features;   	
	}
	
}
?>