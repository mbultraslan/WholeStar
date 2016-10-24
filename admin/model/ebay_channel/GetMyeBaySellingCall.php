<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class GetMyeBaySellingCall extends BaseCall {
	
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
			$this->verb = "GetMyeBaySelling";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function getMyeBaySelling($filter=array()) {
	    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";
		
	    if(isset($filter['selling_summary']) && $filter['selling_summary']) {
	    	$requestXmlBody .= '<SellingSummary><Include>true</Include></SellingSummary>';
	    }
	    
	    
	    if(!empty($filter['active_list']) && !empty($filter['active_list'])) {
	    	$requestXmlBody .= '<ActiveList>';
	    	$requestXmlBody .= '<Include>true</Include>';
	    	
	    	if(isset($filter['active_list']['include_notes']) && $filter['active_list']['include_notes']) {
	    		$requestXmlBody .= '<IncludeNotes>true</IncludeNotes>';
	    	}
	    	
	    	if(isset($filter['active_list']['listing_type'])) {
	    		$requestXmlBody .= '<ListingType>' . $filter['active_list']['listing_type'] . '</ListingType>';
	    	}
	    	
	    	$requestXmlBody .= '<Pagination>';
	    	$requestXmlBody .= '<EntriesPerPage>' . $filter['active_list']['entries_per_page'] . '</EntriesPerPage>';
	    	$requestXmlBody .= '<PageNumber>' . $filter['active_list']['page_number'] . '</PageNumber>';
	    	$requestXmlBody .= '</Pagination>';
	    	
	    	if(isset($filter['active_list']['sort'])) {
	    		$requestXmlBody .= '<Sort>' . $filter['active_list']['sort'] . '</Sort>';
	    	} else {
	    		$requestXmlBody .= '<Sort>StartTime</Sort>';
	    	}
	    	
	    	$requestXmlBody .= '</ActiveList>';
	    	
	    }
		
	    $requestXmlBody .= '</GetMyeBaySellingRequest>';
	    
	  
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
	    	$data = array();
	    	
	    	
	    	if(isset($filter['selling_summary']) && $filter['selling_summary']) {
	    	$data['active_auction_count'] = null;
	    	$element = $responseDoc->getElementsByTagName('ActiveAuctionCount');
	    	if($element->length > 0) {
	    		$data['active_auction_count'] = $element->item(0)->nodeValue;
	    	}
	    	
	    	$data['amount_limit_remaining'] = null;
	    	$data['amount_limit_remaining_currency'] = null;
	    	$element = $responseDoc->getElementsByTagName('AmountLimitRemaining');
	    	if($element->length > 0) {
	    		$data['amount_limit_remaining'] = $element->item(0)->nodeValue;
	    		$data['amount_limit_remaining_currency'] = $element->item(0)->getAttribute('currencyID');
	    	}
	    	
	    	$data['auction_bid_count'] = null;
	    	$element = $responseDoc->getElementsByTagName('AuctionBidCount');
	    	if($element->length > 0) {
	    		$data['auction_bid_count'] = $element->item(0)->nodeValue;
	    	}
	    	
	   		$data['auction_selling_count'] = null;
	    	$element = $responseDoc->getElementsByTagName('AuctionSellingCount');
	    	if($element->length > 0) {
	    		$data['auction_selling_count'] = $element->item(0)->nodeValue;
	    	}
	    	
	    	$data['classified_ad_count'] = null;
	    	$element = $responseDoc->getElementsByTagName('ClassifiedAdCount');
	    	if($element->length > 0) {
	    		$data['classified_ad_count'] = $element->item(0)->nodeValue;
	    	}
	    	
	    	$data['classified_ad_offer_count'] = null;
	    	$element = $responseDoc->getElementsByTagName('ClassifiedAdOfferCount');
	    	if($element->length > 0) {
	    		$data['classified_ad_offer_count'] = $element->item(0)->nodeValue;
	    	}
	    	
	    	$data['quantity_limit_remaining'] = null;
	    	$element = $responseDoc->getElementsByTagName('QuantityLimitRemaining');
	    	if($element->length > 0) {
	    		$data['quantity_limit_remaining'] = $element->item(0)->nodeValue;
	    	}
	    	
	    	$data['sold_duration_in_days'] = null;
	    	$element = $responseDoc->getElementsByTagName('SoldDurationInDays');
	    	if($element->length > 0) {
	    		$data['sold_duration_in_days'] = $element->item(0)->nodeValue;
	    	}
	    	
	    	$data['total_auction_selling_value'] = null;
	    	$data['total_auction_selling_value_currency'] = null;
	    	$element = $responseDoc->getElementsByTagName('TotalAuctionSellingValue');
	    	if($element->length > 0) {
	    		$data['total_auction_selling_value'] = $element->item(0)->nodeValue;
	    		$data['total_auction_selling_value_currency'] = $element->item(0)->getAttribute('currencyID');
	    	}
	    	
	    	$data['total_lead_count'] = null;
	    	$element = $responseDoc->getElementsByTagName('TotalLeadCount');
	    	if($element->length > 0) {
	    		$data['total_lead_count'] = $element->item(0)->nodeValue;
	    	}
	    	
	    	$data['total_listings_with_leads'] = null;
	    	$element = $responseDoc->getElementsByTagName('TotalListingsWithLeads');
	    	if($element->length > 0) {
	    		$data['total_listings_with_leads'] = $element->item(0)->nodeValue;
	    	}
	    	
	    	$data['total_sold_count'] = null;
	    	$element = $responseDoc->getElementsByTagName('TotalSoldCount');
	    	if($element->length > 0) {
	    		$data['total_sold_count'] = $element->item(0)->nodeValue;
	    	}
	    	
	    	$data['total_sold_value'] = null;
	    	$data['total_auction_selling_value_currency'] = null;
	    	$element = $responseDoc->getElementsByTagName('TotalSoldValue');
	    	if($element->length > 0) {
	    		$data['total_sold_value'] = $element->item(0)->nodeValue;
	    		$data['total_sold_value_currency'] = $element->item(0)->getAttribute('currencyID');
	    	}
	    	}
	    	
	    	
	    	$data['pagination_result'] = array();
	    	$data['pagination_result']['total_number_of_pages'] = 0;
	    	$data['pagination_result']['total_number_of_entries'] = 0;
	    	$data['items'] = array();
	    	
	    	if(!empty($filter['active_list']) && !empty($filter['active_list'])) {
	    		
	    		if($responseDoc->getElementsByTagName('ActiveList')->length > 0 
	    					&& $responseDoc->getElementsByTagName('ActiveList')->item(0)->getElementsByTagName('ItemArray')->length > 0) {
	    			
	    			$items = $responseDoc->getElementsByTagName('ActiveList')->item(0)
	    						->getElementsByTagName('ItemArray')->item(0)
	    						->getElementsByTagName('Item');
	    		
		    		
		    		foreach ($items as $item) {
		    			$ebayItem = array(); 
		    			$ebayItem['item_id'] = $item->getElementsByTagName('ItemID')->item(0)->nodeValue;
		    			$ebayItem['title'] = $item->getElementsByTagName('Title')->item(0)->nodeValue;
		    			
		    			if($item->getElementsByTagName('QuantityAvailable')->length > 0) {
		    				$ebayItem['quantity'] = $item->getElementsByTagName('QuantityAvailable')->item(0)->nodeValue;
		    			} else if($item->getElementsByTagName('Quantity')->length > 0) {
		    				$ebayItem['quantity'] = $item->getElementsByTagName('Quantity')->item(0)->nodeValue;
		    			} else {
		    				$ebayItem['quantity'] = 0;
		    			}


                        $ebayItem['image'] = '';

                        if($item->getElementsByTagName('PictureDetails')->length > 0 && $item->getElementsByTagName('PictureDetails')->item(0)->getElementsByTagName('GalleryURL')->length > 0) {
                            $ebayItem['image'] = $item->getElementsByTagName('PictureDetails')->item(0)
                                ->getElementsByTagName('GalleryURL')->item(0)
                                ->nodeValue;
                        }



		    			$CurrentPrice = $item->getElementsByTagName('SellingStatus')->item(0)->getElementsByTagName('CurrentPrice')->item(0);
									    			
		    			$ebayItem['price'] = $CurrentPrice->nodeValue;
		    			$ebayItem['price_currency_id'] = $CurrentPrice->getAttribute('currencyID');
		    			
		    			$ebayItem['link'] = $item->getElementsByTagName('ListingDetails')->item(0)
								    			->getElementsByTagName('ViewItemURL')->item(0)
								    			->nodeValue;


		    			$ebayItem['variations'] = array();
		    			$Variations = $item->getElementsByTagName('Variations');
		    			if($Variations->length > 0) {
		    				foreach ($Variations->item(0)->getElementsByTagName('Variation') as $Variation) {
								$variationQty = $Variation->getElementsByTagName('Quantity');

		    					$ebayItem['variations'][] = array(
		    						"quantity" => ($variationQty->length > 0)? $variationQty->item(0)->nodeValue : 0,
		    						"price" => $Variation->getElementsByTagName('StartPrice')->item(0)->nodeValue,
		    						"price_currency_id" => $Variation->getElementsByTagName('StartPrice')->item(0)->getAttribute('currencyID')
		    					);
		    				}
		    			}

		    			$data['items'][] = $ebayItem;
		    		}
		    		
		    		$paginationResult = $responseDoc->getElementsByTagName('PaginationResult');
		    		if($paginationResult->length > 0) {
		    			$data['pagination_result'] = array();
		    			$paginationResult = $paginationResult->item(0);
		    			$data['pagination_result']['total_number_of_pages'] = $paginationResult->getElementsByTagName('TotalNumberOfPages')->item(0)->nodeValue;
		    			$data['pagination_result']['total_number_of_entries'] = $paginationResult->getElementsByTagName('TotalNumberOfEntries')->item(0)->nodeValue;
		    		}
	    		}
	    	}
	    	
	    	return $data;
	}
	
}
?>