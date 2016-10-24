<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class GetCategorySpecificsCall extends BaseCall {
	
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
			$this->verb = "GetCategorySpecifics";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function getFeatures($categoryId) {
	    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<GetCategorySpecificsRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";
	    $requestXmlBody .= '<CategorySpecific><CategoryID>'. $categoryId  .'</CategoryID></CategorySpecific>';	
	    $requestXmlBody .= '</GetCategorySpecificsRequest>';
	    

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
	    	$recommendations = array();
	    	if($responseDoc->getElementsByTagName('Recommendations')->length > 0) {
	    		foreach ($responseDoc->getElementsByTagName('Recommendations') as $xmlRecommendations) {
	    			$recommendations['CategoryID'] = $xmlRecommendations->getElementsByTagName('CategoryID')->item(0)->nodeValue;
	    			if($xmlRecommendations->getElementsByTagName('NameRecommendation')->length > 0) {
	    				foreach ($xmlRecommendations->getElementsByTagName('NameRecommendation') as $xmlNameRecommendation) {
	    					$values = array();
	    					foreach ($xmlNameRecommendation->getElementsByTagName('ValueRecommendation') as $xmlValueRecommendation) {
	    						if($xmlValueRecommendation->getElementsByTagName('Value')->length > 0) {
	    							$values[] = $xmlValueRecommendation->getElementsByTagName('Value')->item(0)->nodeValue;
	    						}
	    					}
	    					
	    					$xmlValidationRules = $xmlNameRecommendation->getElementsByTagName('ValidationRules')->item(0);
	    					$recommendations['NameRecommendations'][] = array(
	    								'Name' => $xmlNameRecommendation->getElementsByTagName('Name')->item(0)->nodeValue,
	    								'Values' => $values,
	    								'MaxValues'=> (($xmlValidationRules->getElementsByTagName('MaxValues')->length > 0)? $xmlValidationRules->getElementsByTagName('MaxValues')->item(0)->nodeValue : 1),
	    						        'MinValues'=> (($xmlValidationRules->getElementsByTagName('MinValues')->length > 0)? $xmlValidationRules->getElementsByTagName('MinValues')->item(0)->nodeValue : 0),
	    								'ValueFormat'=> (($xmlValidationRules->getElementsByTagName('ValueFormat')->length > 0)? $xmlValidationRules->getElementsByTagName('ValueFormat')->item(0)->nodeValue : 0),
	    								'ValueType'=> (($xmlValidationRules->getElementsByTagName('ValueType')->length > 0)? $xmlValidationRules->getElementsByTagName('ValueType')->item(0)->nodeValue : 0),
	    								'VariationPicture'=> (($xmlValidationRules->getElementsByTagName('VariationPicture')->length > 0)? $xmlValidationRules->getElementsByTagName('VariationPicture')->item(0)->nodeValue : 0),
	    								'VariationSpecifics'=> (($xmlValidationRules->getElementsByTagName('VariationSpecifics')->length > 0)? $xmlValidationRules->getElementsByTagName('VariationSpecifics')->item(0)->nodeValue : 0)
	    					);
	    				}
	    			}
				}
	    	}
	    	
	    	return $recommendations;
	        
	}
	
}
?>