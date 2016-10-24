<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class GetNotificationsUsageCall extends BaseCall {
	
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
			$this->verb = "GetNotificationsUsage";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function getUsage($query) {
	    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<GetNotificationsUsageRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";
	    
	    
	    if(isset($query['start_time'])) {
	    	$requestXmlBody .= '<StartTime>'. $query['start_time'] .'</StartTime>';
	    }
	    
		if(isset($query['end_time'])) {
	    	$requestXmlBody .= '<EndTime>'. $query['end_time'] .'</EndTime>';
	    }
	    
		if(isset($query['item_id'])) {
	    	$requestXmlBody .= '<ItemID>'. $query['item_id'] .'</ItemID>';
	    }
	    
	    
	    $requestXmlBody .= '</GetNotificationsUsageRequest>';
	    
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
    	$response['errors'] = $errors;
    	
    	
    	
		return $response;   	
	        
	}
	
	private function formatDate($date) {
		return $date->format('Y-m-d\TH:i:s\Z');
	} 
}
?>