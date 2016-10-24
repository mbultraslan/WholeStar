<?php

class EbaySession {
	
	private $requestToken;
	private $devID;
	private $appID;
	private $certID;
	private $serverUrl;
	private $compatLevel;
	private $siteID;
	private $verb;
	
	public function __construct($userRequestToken, $developerID, $applicationID, $certificateID, $serverUrl,
								$compatabilityLevel, $siteToUseID, $callName)
	{
		$this->requestToken = $userRequestToken;
		$this->devID = $developerID;
		$this->appID = $applicationID;
		$this->certID = $certificateID;
		$this->compatLevel = $compatabilityLevel;
		$this->siteID = $siteToUseID;
		$this->verb = $callName;
        $this->serverUrl = $serverUrl;	
	}
	
	public function getVerb() {
		return $this->verb;
	}
	
	public function getSiteId() {
		return $this->siteID;
	}
	
	
	public function sendHttpRequest($requestBody)
	{
		$headers = $this->buildEbayHeaders();
		$connection = curl_init();
		curl_setopt($connection, CURLOPT_URL, $this->serverUrl);
		curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($connection, CURLOPT_POST, 1);
		curl_setopt($connection, CURLOPT_POSTFIELDS, $requestBody);
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($connection);
		curl_close($connection);
		return $response;
	}
	
	public function sendHttpAndSave($requestBody, $fp)
	{
		
		$headers = $this->buildEbayHeaders();
		$connection = curl_init();
		curl_setopt($connection, CURLOPT_URL, $this->serverUrl);
		curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($connection, CURLOPT_POST, 1);
		curl_setopt($connection, CURLOPT_POSTFIELDS, $requestBody);
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($connection, CURLOPT_TIMEOUT, 50);
		curl_setopt($connection, CURLOPT_FILE, $fp); // write curl response to file
		curl_exec($connection);
		curl_close($connection);
		
	}
	
	private function buildEbayHeaders()
	{
		$headers = array (
			//Regulates versioning of the XML interface for the API
			'X-EBAY-API-COMPATIBILITY-LEVEL: ' . $this->compatLevel,
			
			//set the keys
			'X-EBAY-API-DEV-NAME: ' . $this->devID,
			'X-EBAY-API-APP-NAME: ' . $this->appID,
			'X-EBAY-API-CERT-NAME: ' . $this->certID,
			
			//the name of the call we are requesting
			'X-EBAY-API-CALL-NAME: ' . $this->verb,			
			
			//SiteID must also be set in the Request's XML
			//SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, ....
			//SiteID Indicates the eBay site to associate the call with
			'X-EBAY-API-SITEID: ' . $this->siteID,
			'Content-Type: text/xml',	
		);
		
		return $headers;
	}
}
?>