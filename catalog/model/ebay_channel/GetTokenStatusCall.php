<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class GetTokenStatusCall extends BaseCall {
	
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
			$this->verb = "GetTokenStatus";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function getTokenStatus() {
	    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<GetTokenStatusRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";
	    $requestXmlBody .= '</GetTokenStatusRequest>';
	    
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
	    
    	$eiasToken = $responseDoc->getElementsByTagName('EIASToken');
    	$tokenStatus = $responseDoc->getElementsByTagName('Status');
    	$expirationTime = $responseDoc->getElementsByTagName('ExpirationTime');
    	$revocationTime = $responseDoc->getElementsByTagName('RevocationTime');

    	if($tokenStatus->length > 0) {
    		$response['status'] = $tokenStatus->item(0)->nodeValue;
    	}
    	
    	if($eiasToken->length > 0)
    	$response['eiasToken'] = $eiasToken->item(0)->nodeValue;
    	
    	if($expirationTime->length > 0)
    	$response['expirationTime'] = $expirationTime->item(0)->nodeValue;
    	
    	if($revocationTime->length > 0)
    	$response['revocationTime'] = $revocationTime->item(0)->nodeValue;
    	
		return $response;   	
	        
	}
	
}
?>