<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class GetCategoriesCall extends BaseCall {
	
	private $requestToken;
	private $devID;
	private $appID;
	private $certID;
	private $serverUrl;
	private $compatLevel;
	private $siteID;
	private $verb;
	public $categories;
	
	public function __construct($userRequestToken, $developerID, $applicationID, $certificateID, $serverUrl,
									$compatabilityLevel, $siteToUseID)
		{
			$this->requestToken = $userRequestToken;
			$this->devID = $developerID;
			$this->appID = $applicationID;
			$this->certID = $certificateID;
			$this->compatLevel = $compatabilityLevel;
			$this->siteID = $siteToUseID;
			$this->verb = "GetCategories";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function getCategories($query) {
	    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<GetCategoriesRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";
		
	    
		if(isset($query['CategorySiteID'])) {
	    	$requestXmlBody .= '<CategorySiteID>'. $query['CategorySiteID']  .'</CategorySiteID>';
	    }
	    
		if(isset($query['DetailLevel'])) {
	    	$requestXmlBody .= '<DetailLevel>'. $query['DetailLevel']  .'</DetailLevel>';
	    }
	    
	    $requestXmlBody .= '</GetCategoriesRequest>';
	    
	    $session = new EbaySession($this->requestToken, $this->devID, $this->appID, $this->certID, $this->serverUrl, $this->compatLevel, $this->siteID, $this->verb);
	    $this->callOpt($requestXmlBody, 'Category', $session, 0);
	    
	    
	    $response = array();
	    $errors = $this->getErrors();
	    if(!empty($errors)) {
	    	$response['errors'] = $errors;
	    }
	    
	    $file = DIR_DOWNLOAD . 'ebay_api/calls/'.$session->getVerb(). '_' .$this->siteID . '.xml';
	    
	    
	    $this->categories = array();
	    $handle = fopen($file, 'r');
	    $this->nodeStringFromXMLFile($handle, '<Category>', '</Category>');
	    fclose($handle);
	    
	    
	    if (file_exists($file)) {
	    	unlink($file);
	    }
	  // var_dump($this->categories); 
	  // die();
	   
	    
// 	    $categories = array();
// 	    foreach ($responseDoc->getElementsByTagName('Category') as $xmlCategory) {
// 	    	$expired = $xmlCategory->getElementsByTagName('Expired');
// 	    	if($expired->length == 0) {
// 	    		$categoryName = $xmlCategory->getElementsByTagName('CategoryName')->item(0)->nodeValue;
// 	    		$categoryParentID = $xmlCategory->getElementsByTagName('CategoryParentID')->item(0)->nodeValue;
// 	    		$categoryLevel = $xmlCategory->getElementsByTagName('CategoryLevel')->item(0)->nodeValue;
// 	    		$categoryID = $xmlCategory->getElementsByTagName('CategoryID')->item(0)->nodeValue;
// 	    		$category['categoryName'] = $categoryName;
// 	    		$category['categoryParentID'] = $categoryParentID;
// 	    		$category['categoryLevel'] = $categoryLevel;
// 	    		$category['categoryID'] = $categoryID;
// 	    		$categories[] = $category;
// 	    	}
// 	    }
	    
	    return $this->categories;
	}
	
	private function addCategory($nodeText) {
		
		$xmlCategory = new DomDocument();
		$xmlCategory->loadXML($nodeText);
		
		
		$expired = $xmlCategory->getElementsByTagName('Expired');
		if($expired->length == 0) {
			
			if($xmlCategory->getElementsByTagName('CategoryName')->length == 0) {
				echo $nodeText; die();
			}
			
			$categoryName = $xmlCategory->getElementsByTagName('CategoryName')->item(0)->nodeValue;
			$categoryParentID = $xmlCategory->getElementsByTagName('CategoryParentID')->item(0)->nodeValue;
			$categoryLevel = $xmlCategory->getElementsByTagName('CategoryLevel')->item(0)->nodeValue;
			$categoryID = $xmlCategory->getElementsByTagName('CategoryID')->item(0)->nodeValue;
			$category['categoryName'] = $categoryName;
			$category['categoryParentID'] = $categoryParentID;
			$category['categoryLevel'] = $categoryLevel;
			$category['categoryID'] = $categoryID;
			$this->categories[] = $category;
			
		}
	}
	
	
	
	private function nodeStringFromXMLFile($handle, $startNode, $endNode, $callback=null) {
		$cursorPos = 0;
		while(true) {
			// Find start position
			$startPos = $this->getPos($handle, $startNode, $cursorPos);
			// We reached the end of the file or an error
			if($startPos === false) {
				break;
			}
			// Find where the node ends
			$endPos = $this->getPos($handle, $endNode, $startPos) + strlen($endNode);
			// Jump back to the start position
			fseek($handle, $startPos);
			// Read the data
			$data = fread($handle, ($endPos - $startPos));
			// pass the $data into the callback
			$this->addCategory($data);
			// next iteration starts reading from here
			$cursorPos = ftell($handle);
		}
	}
	
	private function getPos($handle, $string, $startFrom=0, $chunk=1024) {
		// Set the file cursor on the startFrom position
		fseek($handle, $startFrom, SEEK_SET);
		// Read data
		$data = fread($handle, $chunk);
		// Try to find the search $string in this chunk
		$stringPos = strpos($data, $string);
		// We found the string, return the position
		if($stringPos !== false ) {
			return $stringPos+$startFrom;
		}
		// We reached the end of the file
		if(feof($handle)) {
			return false;
		}
		// Recurse to read more data until we find the search $string it or run out of disk
		return $this->getPos($handle, $string, $chunk+$startFrom);
	}
	
	private function startsWith($haystack, $needle)
	{
		return $needle === "" || strpos($haystack, $needle) === 0;
	}
	
	private function endsWith($haystack, $needle)
	{
		return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
	}
	
}
?>