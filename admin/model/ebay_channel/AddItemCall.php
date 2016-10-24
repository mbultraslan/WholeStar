<?php require_once('EbaySession.php') ?>
<?php
class AddItemCall extends BaseCall {
	
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
			$this->verb = "AddItem";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function addItem($product, $profile, $settings = array()) {
	    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<AddItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
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
        $requestXmlBody .= $this->getPictureDetailsXML($product, $settings['general_list_product_cover_image']);
		
		//------------item_specifics-----------------
		$requestXmlBody .= $this->getItemSpecificsXML($product, $profile);
		
		//-------Condition-------
		$requestXmlBody .= $this->getConditionXML($profile);
		
		//--------------ReturnPolicy--------------------------------
		$requestXmlBody .= $this->getReturnPolicyXML($profile);
		
		//---------------Price----------------------------------------------
		$requestXmlBody .= $this->getPriceXML($product, $profile);
		$requestXmlBody .= $this->getAuctionPriceXML($product, $profile);
		
		
		//---------------Variations----------------------------------------------
		$requestXmlBody .= $this->getVariationsXML($product, $profile);
		
		//--------------------ShippingDetails-----------------------------
		$requestXmlBody .= $this->getShippingDetailsXML($product, $profile);
		
	    
	    $requestXmlBody .= "</Item>";
	    
	    $requestXmlBody .= "</AddItemRequest>";
	    
	   // echo $requestXmlBody;
	    
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
    	$itemId = $responseDoc->getElementsByTagName('ItemID');
    	if($itemId->length > 0) {
    		$response['item_id'] =  $itemId->item(0)->nodeValue;
    	}
    	
		$StartTime = $responseDoc->getElementsByTagName('StartTime');
    	if($StartTime->length > 0) {
    		$response['start_time'] =  $StartTime->item(0)->nodeValue;
    	}
    	
		$EndTime = $responseDoc->getElementsByTagName('EndTime');
    	if($EndTime->length > 0) {
    		$response['end_time'] =  $EndTime->item(0)->nodeValue;
    	}
    	
	    return $response;      
	}
	
}
?>