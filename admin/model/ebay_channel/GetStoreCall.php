<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class GetStoreCall extends BaseCall {
	
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
			$this->verb = "GetStore";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function getStore($query) {
	    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<GetStoreRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";
	    
	    if(isset($query['CategoryStructureOnly'])) {
	    	$requestXmlBody .= '<CategoryStructureOnly>'. (($query['CategoryStructureOnly'])? 'True' : 'False' )  .'</CategoryStructureOnly>';
	    }
	    
		if(isset($query['LevelLimit'])) {
	    	$requestXmlBody .= '<LevelLimit>'. $query['LevelLimit']  .'</LevelLimit>';
	    }
	    
		if(isset($query['RootCategoryID'])) {
	    	$requestXmlBody .= '<RootCategoryID>'. $query['RootCategoryID']  .'</RootCategoryID>';
	    }
	    
		if(isset($query['UserID'])) {
	    	$requestXmlBody .= '<UserID>'. $query['UserID']  .'</UserID>';
	    }
	    
	    $requestXmlBody .= '</GetStoreRequest>';
	    
	    
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
	    
	    
    	$Name = $responseDoc->getElementsByTagName('Name');
    	$URL = $responseDoc->getElementsByTagName('URL');
    	$Email = $responseDoc->getElementsByTagName('Email');

    	
    	if($Name->length > 0)
    	$response['name'] = $Name->item(0)->nodeValue;
    	
    	if($URL->length > 0)
    	$response['url'] = $URL->item(0)->nodeValue;
    	
    	if($Email->length > 0)
    	$response['email'] = $Email->item(0)->nodeValue;
    	
    	$CustomCategories = $responseDoc->getElementsByTagName('CustomCategories');
    	if($CustomCategories->length > 0) {
    		$response['categories'] = array();
    		$CustomCategories = $responseDoc->getElementsByTagName('CustomCategory');
    		foreach ($CustomCategories as $CustomCategory) {
    			$category = array('category_id'=> $CustomCategory->getElementsByTagName('CategoryID')->item(0)->nodeValue, 'name'=> $CustomCategory->getElementsByTagName('Name')->item(0)->nodeValue, 'order' => $CustomCategory->getElementsByTagName('Order')->item(0)->nodeValue);
    			$category['child_category'] = $this->getCategories($CustomCategory);
    			$response['categories'][] = $category;
    		}
    		
//     		var_dump($response['categories']);
//     		die();
    	}

    	
    
    	
		return $response;   	
	}
	
	private function getCategories($CustomCategory) {
		$ChildCategories = $CustomCategory->getElementsByTagName('ChildCategory');
		$categories = array();
		foreach ($ChildCategories as $CustomCategory) {
			$category = array('category_id'=> $CustomCategory->getElementsByTagName('CategoryID')->item(0)->nodeValue, 'name'=> $CustomCategory->getElementsByTagName('Name')->item(0)->nodeValue, 'order' => $CustomCategory->getElementsByTagName('Order')->item(0)->nodeValue);
			$category['child_category'] = $this->getCategories($CustomCategory);
			$categories[] = $category;
		}
		return $categories;
	}
	
}
?>