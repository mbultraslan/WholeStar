<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class SetNotificationPreferencesCall extends BaseCall {
	
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
			$this->verb = "SetNotificationPreferences";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function setPreferences($settings) {
	    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<SetNotificationPreferencesRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";
	    
    	$requestXmlBody .= "<ApplicationDeliveryPreferences>";
    	$requestXmlBody .= "<DeviceType>Platform</DeviceType>";
    	
    	if(!empty($settings['alert_email'])) {
    		$requestXmlBody .= "<AlertEmail>mailto://" . $settings['alert_email'] . "</AlertEmail>\n";
    		$requestXmlBody .= "<ApplicationURL>" . HTTP_CATALOG . "index.php?route=ebay_channel/notification</ApplicationURL>\n";
    	}
    	
    	if(!empty($settings['alert_email_enabled'])) {
    		$requestXmlBody .= "<AlertEnable>". (($settings['alert_email_enabled'])? "Enable" : "Disable") ."</AlertEnable>\n";
    		$requestXmlBody .= "<ApplicationEnable>". (($settings['alert_email_enabled'])? "Enable" : "Disable") ."</ApplicationEnable>\n";
    	}
    	
    	$requestXmlBody .= "</ApplicationDeliveryPreferences>";
	    
	    
					
			$requestXmlBody .= "<UserDeliveryPreferenceArray>";
			
			$requestXmlBody .= "<NotificationEnable>";
			$requestXmlBody .= "<EventType>ItemListed</EventType>";
			$requestXmlBody .= "<EventEnable>". (($settings['alert_email_enabled'])? "Enable" : "Disable") ."</EventEnable>";
			$requestXmlBody .= "</NotificationEnable>\n";
		
			$requestXmlBody .= "<NotificationEnable>";
			$requestXmlBody .= "<EventType>ItemClosed</EventType>";
			$requestXmlBody .= "<EventEnable>". (($settings['item_closed_enabled'])? "Enable" : "Disable") ."</EventEnable>";
			$requestXmlBody .= "</NotificationEnable>\n";
		
			$requestXmlBody .= "<NotificationEnable>";
			$requestXmlBody .= "<EventType>ItemRevised</EventType>";
			$requestXmlBody .= "<EventEnable>". (($settings['item_revised_enabled'])? "Enable" : "Disable") ."</EventEnable>";
			$requestXmlBody .= "</NotificationEnable>\n";
		
			$requestXmlBody .= "<NotificationEnable>";
			$requestXmlBody .= "<EventType>ItemSold</EventType>";
			$requestXmlBody .= "<EventEnable>". (($settings['item_sold_enabled'])? "Enable" : "Disable") ."</EventEnable>";
			$requestXmlBody .= "</NotificationEnable>\n";
		
			$requestXmlBody .= "<NotificationEnable>";
			$requestXmlBody .= "<EventType>ItemUnsold</EventType>";
			$requestXmlBody .= "<EventEnable>". (($settings['item_unsold_enabled'])? "Enable" : "Disable") ."</EventEnable>";
			$requestXmlBody .= "</NotificationEnable>\n";
			
			$requestXmlBody .= "</UserDeliveryPreferenceArray>";
	    	
	    
	    $requestXmlBody .= '</SetNotificationPreferencesRequest>';
	    
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
	
}
?>