<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class GetSellerDashboardCall extends BaseCall {
	
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
			$this->verb = "GetSellerDashboard";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function getSellerDashboard() {
	    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<GetSellerDashboardRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";
	    $requestXmlBody .= '</GetSellerDashboardRequest>';
	    
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
	    	
	    	$resp['buyerSatisfaction'] = array();
	    	$node = $responseDoc->getElementsByTagName("BuyerSatisfaction");
	    	if($node->length > 0) {
	    		$alerts = $node->getElementsByTagName("Alert");
	    		$resp['buyerSatisfaction']['alerts'] = array();
	    		$resp['buyerSatisfaction']['status'] = $node->getElementsByTagName('Status')->item(0)->nodeValue;
	    		foreach ($alerts as $alert) {
	    			$resp['buyerSatisfaction']['alerts'][] = array(
	    				'severity'=>$alert->getElementsByTagName('Severity')->item(0)->nodeValue,
	    				'text'=>$alert->getElementsByTagName('Text')->item(0)->nodeValue
	    			);
	    		}
	    	}
	    	
	    	$resp['sellerAccount'] = array();
	    	$node = $responseDoc->getElementsByTagName("SellerAccount");
	    	if($node->length > 0) {
	    		$alerts = $node->item(0)->getElementsByTagName("Alert");
	    		$resp['sellerAccount']['alerts'] = array();
	    		$resp['sellerAccount']['status'] = $node->item(0)->getElementsByTagName('Status')->item(0)->nodeValue;
	    		foreach ($alerts as $alert) {
	    			$resp['sellerAccount']['alerts'][] = array(
	    				'severity'=>$alert->getElementsByTagName('Severity')->item(0)->nodeValue,
	    				'text'=>$alert->getElementsByTagName('Text')->item(0)->nodeValue
	    			);
	    		}
	    	}
	    	
	    	
	    	$resp['sellerFeeDiscount'] = array();
	    	$node = $responseDoc->getElementsByTagName("SellerFeeDiscount");
	    	if($node->length > 0) {
	    		$resp['sellerFeeDiscount']['percent'] = $node->item(0)->getElementsByTagName('Percent')->item(0)->nodeValue;
	    	}
	    	
	   		$resp['searchStanding'] = array();
	    	$node = $responseDoc->getElementsByTagName("SearchStanding");
	    	if($node->length > 0) {
	    		$resp['searchStanding']['status'] = $node->item(0)->getElementsByTagName('Status')->item(0)->nodeValue;
	    	}
	    	
	    	$resp['powerSellerStatus'] = array();
	    	$node = $responseDoc->getElementsByTagName("PowerSellerStatus");
	    	if($node->length > 0) {
	    		$alerts = $node->item(0)->getElementsByTagName("Alert");
	    		$resp['powerSellerStatus']['level'] = $node->item(0)->getElementsByTagName('Level')->item(0)->nodeValue;
	    		$resp['powerSellerStatus']['alerts'] = array();
	    		foreach ($alerts as $alert) {
	    			$resp['powerSellerStatus']['alerts'][] = array(
	    				'severity'=>$alert->getElementsByTagName('Severity')->item(0)->nodeValue,
	    				'text'=>$alert->getElementsByTagName('Text')->item(0)->nodeValue
	    			);
	    		}
	    	}
	    	
	    	$resp['performance'] = array();
	    	$node = $responseDoc->getElementsByTagName("Performance");
	    	foreach ($node as $performance) {
	    		$p = array();
	    		$sites = $performance->getElementsByTagName("Site");
	    		$p['sites'] = array();
	    		foreach ($sites as $site) {
	    			$p['sites'][] = $site->nodeValue;
	    		}
	    		
	    		$alerts = $performance->getElementsByTagName("Alert");
	    		$p['alerts'] = array();
	    		foreach ($alerts as $alert) {
	    			$p['alerts'][] = array(
	    				'severity'=>$alert->getElementsByTagName('Severity')->item(0)->nodeValue,
	    				'text'=>$alert->getElementsByTagName('Text')->item(0)->nodeValue
	    			);
	    		}
	    		
	    		$resp['performance'][] = $p;
	    	}
	    	
	    	return $resp;
	        
	}
	
}
?>