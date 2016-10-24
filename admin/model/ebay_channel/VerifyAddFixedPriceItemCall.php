<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class VerifyAddFixedPriceItemCall extends BaseCall {
	
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
			$this->verb = "VerifyAddFixedPriceItem";
	        $this->serverUrl = $serverUrl;
		}
		
	public function verifyAddFixedPriceItem($product, $profile) {
	    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<VerifyAddFixedPriceItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>\n";
	    
	    $requestXmlBody .= '<Item>';
	    
	    //--------SKU---------------------
	   $requestXmlBody .= $this->getSkuXML($product);
	    
	   //-------Currency-----------------
	     $requestXmlBody .= $this->getCurrencyXML($profile);
	    
	    //-------PayPalEmailAddress-----------------
	     $requestXmlBody .= $this->getPayPalEmailAddressXML($profile);
	    
	    //-------DispatchTimeMax-----------------
	     $requestXmlBody .= $this->getDispatchTimeMaxXML($profile);
	    
	    //-------ListingDuration-----------------
	     $requestXmlBody .= $this->getListingDurationXML($profile);
	    
	    //-------ListingType-----------------
	     $requestXmlBody .= $this->getListingTypeXML($profile);
	    
	    //-------PostalCode-----------------
	    $requestXmlBody .= $this-> getPostalCodeXML($profile);
	    
	    //-------Country-----------------
	    $requestXmlBody .= $this->getCountryXML($profile);
	    
	    //-------PrimaryCategory-------
	    $requestXmlBody .= $this->getPrimaryCategoryXML($profile);
	    
	    //----------PaymentMethods---------------------------
	    $requestXmlBody .= $this->getPaymentMethodsXML($profile);
	    
		//---------------Item Title and Description-----
	    $requestXmlBody .= $this->getItemTitleAndDescriptionXML($product);
		
		//--------------PictureDetails---------------
	    $requestXmlBody .= $this->getPictureDetailsXML($product);
		
		//------------item_specifics-----------------
		$requestXmlBody .= $this->getItemSpecificsXML($product, $profile);
		
		//-------Condition-------
		$requestXmlBody .= $this->getConditionXML($profile);
		
		//--------------ReturnPolicy--------------------------------
		$requestXmlBody .= $this->getReturnPolicyXML($profile);
		
		//---------------Quantity----------------------------------------------
		$requestXmlBody .= $this->getQuantityXML($product, $profile);
		
		//---------------Price----------------------------------------------
		$requestXmlBody .= $this->getPriceXML($product, $profile);
		
		//---------------Variations----------------------------------------------
		$requestXmlBody .= $this->getVariationsXML($product, $profile);
		
		//--------------------ShippingDetails-----------------------------
		$requestXmlBody .= $this->getShippingDetailsXML($product, $profile);
		
	    
	    $requestXmlBody .= "</Item>";
	    
	    $requestXmlBody .= "</VerifyAddFixedPriceItemRequest>";
	    
	    
		$session = new EbaySession($this->requestToken, $this->devID, $this->appID, $this->certID, $this->serverUrl, $this->compatLevel, $this->siteID, $this->verb);
	    $responseDoc = $this->call($requestXmlBody, $session);
	   
	    $response = array();
	    $errors = $this->getErrors();
	    if(!empty($errors)) {
	    	$response['errors'] = $errors;
	    }
	    
		$fees = $responseDoc->getElementsByTagName('Fee');
    	if($fees->length > 0) {
    		$response['fees'] = array();
	    	foreach ($fees as $fee) {
	    		$name = $fee->getElementsByTagName('Name');
	    		if($name->length >0) {
	    			$response['fees'][] = array(
	    			"name" => $name->item(0)->nodeValue,
    				"fee" => floatval($fee->getElementsByTagName('Fee')->item(0)->nodeValue),
	    			"currency" => $fee->getElementsByTagName('Fee')->item(0)->getAttribute('currencyID')
	    		);
	    		}
	    		
	    		
	    	}
    	}
    	
    	//var_dump($response); die();
	    return $response;      
	}
	
}
?>