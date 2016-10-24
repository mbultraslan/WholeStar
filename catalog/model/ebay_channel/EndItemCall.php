<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class EndItemCall extends BaseCall {
	
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
			$this->verb = "EndItem";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function endItem($itemId, $endreason=2) {
	    $requestXmlBody  = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<EndItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";
	    $requestXmlBody .= '<EndingReason>' . $this->getEndReasonById($endreason) . '</EndingReason>';
	    $requestXmlBody .= '<ItemID>'.$itemId.'</ItemID>';
	    $requestXmlBody .= "</EndItemRequest>";
	    
	    $session = new EbaySession($this->requestToken, $this->devID, $this->appID, $this->certID, $this->serverUrl, $this->compatLevel, $this->siteID, $this->verb);
	    $responseDoc = $this->call($requestXmlBody, $session);
	    
	    $response = array();
	    $errors = $this->getErrors();
	    if(!empty($errors)) {
	    	$response['errors'] = $errors;
	    }
	    
	    $EndTime = $responseDoc->getElementsByTagName('EndTime');
	    if($EndTime->length > 0) { 
	    	$response['end_time'] = $EndTime->item(0)->nodeValue;
	    }
	    return $response;
	        
	}
	
	private function getEndReasonById($id) {
		switch ($id) {
			case 1:	return "LostOrBroken";
			case 2:	return "NotAvailable";
			case 3:	return "Incorrect";
			case 4:	return "OtherListingError";
			case 5:	return "SellToHighBidder";
			default: return "NotAvailable";
		}
	}
	
}
?>