<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class ReviseInventoryStatusCall extends BaseCall {
	
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
			$this->verb = "ReviseInventoryStatus";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function reviseInventory($itemId, $price, $qty, $profile, $settings = false) {

        $response = array();
        if($settings && !$settings['revise_quantity_enabled'] && !$settings['revise_price_enabled']) {
            return $response;
        }

	    $requestXmlBody  = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<ReviseInventoryStatusRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";
	    
	    $requestXmlBody .= '<InventoryStatus>';
	    $requestXmlBody .= '<ItemID>'.$itemId.'</ItemID>';

        if($settings) {
            //---------------Quantity----------------------------------------------
            if($settings['revise_quantity_enabled']) {
                $requestXmlBody .= '<Quantity>'.$qty.'</Quantity>';
            }

            //---------------Price----------------------------------------------
            if($settings['revise_price_enabled']) {
                $requestXmlBody .= '<StartPrice>'. number_format($price, 2, '.', '')  ."</StartPrice>\n";
            }
        } else {
            $requestXmlBody .= '<Quantity>'.$qty.'</Quantity>';
            $requestXmlBody .= '<StartPrice>'. number_format($price, 2, '.', '')  ."</StartPrice>\n";
        }

	    
	    $requestXmlBody .= '</InventoryStatus>';
	    $requestXmlBody .= "</ReviseInventoryStatusRequest>";
	    
		$session = new EbaySession($this->requestToken, $this->devID, $this->appID, $this->certID, $this->serverUrl, $this->compatLevel, $this->siteID, $this->verb);
		$responseDoc = $this->call($requestXmlBody, $session);
		  
		    
		$errors = $this->getErrors();
		if(!empty($errors)) {
			$criticals = array();
			foreach ($errors as $error) {
				if($error['severity_code'] == 'Error') {
					$criticals[] = $error;
				}
			}
			if(!empty($criticals)) {
				$response['errors'] = $criticals;
			}
		} 
		
		$item = $responseDoc->getElementsByTagName('InventoryStatus');
		if($item->length > 0) {
			$item = $item->item(0);
			$response['inventory_status'] = array();
			$response['inventory_status']['item_id'] = $item->getElementsByTagName('ItemID')->item(0)->nodeValue;
			$response['inventory_status']['price'] = $item->getElementsByTagName('StartPrice')->item(0)->nodeValue;
			$response['inventory_status']['quantity'] = $item->getElementsByTagName('Quantity')->item(0)->nodeValue;
			if($item->getElementsByTagName('SKU')->length > 0) {
				$response['item']['sku'] = $item->getElementsByTagName('SKU')->item(0)->nodeValue;
			}
		}
		
	    return $response;    
	}
	
}
?>