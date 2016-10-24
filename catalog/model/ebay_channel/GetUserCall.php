<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class GetUserCall extends BaseCall {
	
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
			$this->verb = "GetUser";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function getUser() {
	    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<GetUserRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";
	    $requestXmlBody .= '</GetUserRequest>';
	    
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
	    
    	$Status = $responseDoc->getElementsByTagName('Status');
    	$UserID = $responseDoc->getElementsByTagName('UserID');
    	$Email = $responseDoc->getElementsByTagName('Email');
    	$SellerInfo_StoreSite = $responseDoc->getElementsByTagName('StoreSite');

    	if($Status->length > 0) {
    		$response['status'] = $Status->item(0)->nodeValue;
    	}
    	
    	if($UserID->length > 0)
    	$response['userID'] = $UserID->item(0)->nodeValue;
    	
    	if($Email->length > 0)
    	$response['email'] = $Email->item(0)->nodeValue;
    	
    	if($SellerInfo_StoreSite->length > 0)
    	$response['sellerInfo_storeSite'] = $SellerInfo_StoreSite->item(0)->nodeValue;
    	
		return $response;   	
	        
	}
	
}
?>