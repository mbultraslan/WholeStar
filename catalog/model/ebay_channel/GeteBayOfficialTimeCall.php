<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class GeteBayOfficialTimeCall extends BaseCall {
	
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
			$this->verb = "GeteBayOfficialTime";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function getTime() {
	    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<GeteBayOfficialTimeRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";
	    $requestXmlBody .= '</GeteBayOfficialTimeRequest>';
	    
	    $session = new EbaySession($this->requestToken, $this->devID, $this->appID, $this->certID, $this->serverUrl, $this->compatLevel, $this->siteID, $this->verb);
	    $responseXml = $session->sendHttpRequest($requestXmlBody);
	    if(stristr($responseXml, 'HTTP 404') || $responseXml == '') {
	    	throw new Exception("Error sending request");
	    }	
	        
	    $responseDoc = new DomDocument();
	    $responseDoc->loadXML($responseXml);
	    
	    
	    $errors = $responseDoc->getElementsByTagName('Errors');
	    
	    if($errors->length > 0) {
	    	$code = $errors->item(0)->getElementsByTagName('ErrorCode');
	        $shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
	        $longMsg = $errors->item(0)->getElementsByTagName('LongMessage');
	        if(count($longMsg) > 0) {
	    		throw new Exception($longMsg->item(0)->nodeValue, $code->item(0)->nodeValue);
	    	} else {
	    		throw new Exception($shortMsg->item(0)->nodeValue, $code->item(0)->nodeValue);
	    	}
	    	
	    	
	        
	        
	        
	        
//	        echo '<P>', $code->item(0)->nodeValue, ' : ', str_replace(">", "&gt;", str_replace("<", "&lt;", $shortMsg->item(0)->nodeValue));
//	        if(count($longMsg) > 0)
//	            echo '<BR>', str_replace(">", "&gt;", str_replace("<", "&lt;", $longMsg->item(0)->nodeValue));
	    } 
	    else //no errors
	    {
	        $eBayTime = $responseDoc->getElementsByTagName('Timestamp');
	        echo '<P><B>The Official eBay Time is ', $eBayTime->item(0)->nodeValue, ' GMT</B>';
	        die();
	    }
	        
	}
	
}
?>