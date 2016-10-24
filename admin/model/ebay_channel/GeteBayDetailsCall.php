<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class GeteBayDetailsCall extends BaseCall {
	
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
			$this->verb = "GeteBayDetails";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function getDetails($detailNames) {
	    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<GeteBayDetailsRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";
		
	    foreach ($detailNames as $detailName) {
			$requestXmlBody .= '<DetailName>'. $detailName  .'</DetailName>';	    	
	    }
		
	    $requestXmlBody .= '</GeteBayDetailsRequest>';

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
	    	
	    	$details = array();
	    	foreach ($detailNames as $detailName) {

	    		if($detailName == 'ShippingCarrierDetails') {
					foreach ($responseDoc->getElementsByTagName('ShippingCarrierDetails') as $xmlShippingCarrier) {
						$details[$detailName][] = array(
										'Description'=> $xmlShippingCarrier->getElementsByTagName('Description')->item(0)->nodeValue,
										'ShippingCarrier'=> $xmlShippingCarrier->getElementsByTagName('ShippingCarrier')->item(0)->nodeValue,
										'ShippingCarrierID'=> $xmlShippingCarrier->getElementsByTagName('ShippingCarrierID')->item(0)->nodeValue,
										); 
						
					}
	    		}
	    		
	    		if($detailName == 'ShippingLocationDetails') {
	    			foreach ($responseDoc->getElementsByTagName('ShippingLocationDetails') as $xmlShippingLocation) {
	    				$details[$detailName][] = array(
	    						'Description'=> $xmlShippingLocation->getElementsByTagName('Description')->item(0)->nodeValue,
	    						'ShippingLocation'=> $xmlShippingLocation->getElementsByTagName('ShippingLocation')->item(0)->nodeValue
	    				);
	    			}
	    		}
	    		
	    		
	    		
	    		
	    		if($detailName == 'ShippingPackageDetails') {
					foreach ($responseDoc->getElementsByTagName('ShippingPackageDetails') as $xmlShippingCarrier) {
						$default = $xmlShippingCarrier->getElementsByTagName('DefaultValue');     
						$details[$detailName][] = array(
										'DefaultValue'=> ($default->length > 0)? $default->item(0)->nodeValue : 'false',
										'Description'=> $xmlShippingCarrier->getElementsByTagName('Description')->item(0)->nodeValue,
										'PackageID'=> $xmlShippingCarrier->getElementsByTagName('PackageID')->item(0)->nodeValue,
										'ShippingPackage'=> $xmlShippingCarrier->getElementsByTagName('ShippingPackage')->item(0)->nodeValue,
										); 
					}
	    		}
	    		
	    		if($detailName == 'DispatchTimeMaxDetails') {
					foreach ($responseDoc->getElementsByTagName('DispatchTimeMaxDetails') as $xmlDispatchTimeMaxDetail) {
						$extendedHandling = $xmlDispatchTimeMaxDetail->getElementsByTagName('ExtendedHandling');     
						$dispatchTimeMax = $xmlDispatchTimeMaxDetail->getElementsByTagName('DispatchTimeMax');    
						$details[$detailName][] = array(
										'ExtendedHandling'=> ($extendedHandling->length > 0)? $extendedHandling->item(0)->nodeValue : 'false',
										'Description'=> $xmlDispatchTimeMaxDetail->getElementsByTagName('Description')->item(0)->nodeValue,
										'DispatchTimeMax'=> ($dispatchTimeMax->length > 0)? $dispatchTimeMax->item(0)->nodeValue : null,
										); 
					}
	    		}
	    		
	    		
	    		
	    		
	    		if($detailName == 'ShippingServiceDetails') {
					foreach ($responseDoc->getElementsByTagName('ShippingServiceDetails') as $xmlShippingCarrier) {
						$ValidForSellingFlow = $xmlShippingCarrier->getElementsByTagName('ValidForSellingFlow');
						if($ValidForSellingFlow->length > 0 && $ValidForSellingFlow->item(0)->nodeValue == 'true') {
							$CODService = $xmlShippingCarrier->getElementsByTagName('CODService'); 
							$DimensionsRequired = $xmlShippingCarrier->getElementsByTagName('DimensionsRequired');     
							$InternationalService = $xmlShippingCarrier->getElementsByTagName('InternationalService');
							$WeightRequired = $xmlShippingCarrier->getElementsByTagName('WeightRequired');
							$ShippingTimeMax = $xmlShippingCarrier->getElementsByTagName('ShippingTimeMax');
							$ShippingTimeMin = $xmlShippingCarrier->getElementsByTagName('ShippingTimeMin');
							$ShippingCarrier = $xmlShippingCarrier->getElementsByTagName('ShippingCarrier');
							$ShippingPackage = $xmlShippingCarrier->getElementsByTagName('ShippingPackage');
							$ServiceTypes = $xmlShippingCarrier->getElementsByTagName('ServiceType');
							$is_flat = false;
							$is_calculated = false;
							if($ServiceTypes->length > 0) {
								foreach ($ServiceTypes as $ServiceType) {
									if($ServiceType->nodeValue == 'Calculated') {
										$is_calculated = true;
									}
									if($ServiceType->nodeValue == 'Flat') {
										$is_flat = true;
									}
								}
							}
							
							
							
							$details[$detailName][] = array(
											'CODService'=> ($CODService->length > 0)? $CODService->item(0)->nodeValue : 'false',
											'DimensionsRequired'=> ($DimensionsRequired->length > 0)? $DimensionsRequired->item(0)->nodeValue : 'false',
											'InternationalService'=> ($InternationalService->length > 0)? $InternationalService->item(0)->nodeValue : 'false',
											'WeightRequired'=> ($WeightRequired->length > 0)? $WeightRequired->item(0)->nodeValue : 'false',
											'Description'=> $xmlShippingCarrier->getElementsByTagName('Description')->item(0)->nodeValue,
											'is_flat'=> $is_flat,
											'is_calculated'=> $is_calculated,
											'ShippingCarrier'=> ($ShippingCarrier->length > 0)? $ShippingCarrier->item(0)->nodeValue : null,
											'ShippingPackage'=> ($ShippingPackage->length > 0)? $ShippingPackage->item(0)->nodeValue : null,											
											'ShippingCategory'=> ($xmlShippingCarrier->getElementsByTagName('ShippingCategory')->length > 0 )?  $xmlShippingCarrier->getElementsByTagName('ShippingCategory')->item(0)->nodeValue : '',
											'ShippingService'=> $xmlShippingCarrier->getElementsByTagName('ShippingService')->item(0)->nodeValue,
											'ShippingServiceID'=> $xmlShippingCarrier->getElementsByTagName('ShippingServiceID')->item(0)->nodeValue,
											'ShippingTimeMax'=> ($ShippingTimeMax->length > 0)? $ShippingTimeMax->item(0)->nodeValue : null,
											'ShippingTimeMin'=> ($ShippingTimeMin->length > 0)? $ShippingTimeMin->item(0)->nodeValue : null
							); 
						}				
					}
	    		}
	    		
	    		
	    		if($detailName == 'CountryDetails') {
					foreach ($responseDoc->getElementsByTagName('CountryDetails') as $xmlShippingCarrier) {
						$details[$detailName][] = array(
									'Country'=> $xmlShippingCarrier->getElementsByTagName('Country')->item(0)->nodeValue,
									'Description'=> $xmlShippingCarrier->getElementsByTagName('Description')->item(0)->nodeValue,
									'UpdateTime'=> $xmlShippingCarrier->getElementsByTagName('UpdateTime')->item(0)->nodeValue
						); 
					}
	    		}

                if($detailName == 'ProductDetails') {
                    if($responseDoc->getElementsByTagName('ProductDetails')->length > 0) {
                        $ProductDetails = $responseDoc->getElementsByTagName('ProductDetails')->item(0);
                        if($ProductDetails->getElementsByTagName('ProductIdentifierUnavailableText')->length > 0) {
                            $value = $ProductDetails->getElementsByTagName('ProductIdentifierUnavailableText')->item(0)->nodeValue;
                            $details[$detailName]['ProductIdentifierUnavailableText'] = $value;
                        }
                    }
                }


	    		
	    		if($detailName == 'ReturnPolicyDetails') {
	    			$ReturnPolicyDetails = $responseDoc->getElementsByTagName('ReturnPolicyDetails');
					if($ReturnPolicyDetails->length > 0 ) {
						
						$details[$detailName] = array('Description'=> $ReturnPolicyDetails->item(0)->getElementsByTagName('Description')->item(0)->nodeValue); 
						$refunds = $ReturnPolicyDetails->item(0)->getElementsByTagName('Refund');
						if($refunds->length > 0) {
							foreach ($refunds as $refund) {
								$details[$detailName]['Refunds'][] = array(
									'Description'=> $refund->getElementsByTagName('Description')->item(0)->nodeValue,
									'RefundOption'=> $refund->getElementsByTagName('RefundOption')->item(0)->nodeValue
								); 
								
							}
						}
						
						$ReturnsAccepted = $ReturnPolicyDetails->item(0)->getElementsByTagName('ReturnsAccepted');
						if($ReturnsAccepted->length > 0) {
							foreach ($ReturnsAccepted as $value) {
								$details[$detailName]['ReturnsAccepted'][] = array(
									'Description'=> $value->getElementsByTagName('Description')->item(0)->nodeValue,
									'ReturnsAcceptedOption'=> $value->getElementsByTagName('ReturnsAcceptedOption')->item(0)->nodeValue
								); 
								
							}
						}
						
						$ReturnsWithin = $ReturnPolicyDetails->item(0)->getElementsByTagName('ReturnsWithin');
						if($ReturnsWithin->length > 0) {
							foreach ($ReturnsWithin as $value) {
								$details[$detailName]['ReturnsWithin'][] = array(
									'Description'=> $value->getElementsByTagName('Description')->item(0)->nodeValue,
									'ReturnsWithinOption'=> $value->getElementsByTagName('ReturnsWithinOption')->item(0)->nodeValue
								); 
								
							}
						}
						
						$ShippingCostPaidBy = $ReturnPolicyDetails->item(0)->getElementsByTagName('ShippingCostPaidBy');
						if($ShippingCostPaidBy->length > 0) {
							foreach ($ShippingCostPaidBy as $value) {
								$details[$detailName]['ShippingCostPaidBy'][] = array(
									'Description'=> $value->getElementsByTagName('Description')->item(0)->nodeValue,
									'ShippingCostPaidByOption'=> $value->getElementsByTagName('ShippingCostPaidByOption')->item(0)->nodeValue
								); 
								
							}
						}
					
					}
	    		}
	    		
	    		
	    	}
			return $details;   	
	}
	
}
?>