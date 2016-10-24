<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class GetFeedbackCall extends BaseCall {
	
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
			$this->verb = "GetFeedback";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function getFeedback() {
	    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<GetFeedbackRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";
	    
	    
	    $requestXmlBody .= '</GetFeedbackRequest>';
	    
	  
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
	    	$periods = array(
	    		'positive'=>'PositiveFeedbackPeriodArray',
	    		'neutral'=>'NeutralFeedbackPeriodArray',
	    		'negative'=>'NegativeFeedbackPeriodArray',
	    	);
	    	$resp = array();
	    	foreach ($periods as $name=>$xmlName) {
		    	$feedbackPeriodArray = $responseDoc->getElementsByTagName($xmlName);
		    	if($feedbackPeriodArray->length > 0) {
		    		$feedbackPeriods = $feedbackPeriodArray->item(0)->getElementsByTagName('FeedbackPeriod');
		    		$resp[$name] = array();
		    		foreach ($feedbackPeriods as $feedbackPeriod) {
		    			$resp[$name][] = array(
		    				'count' => $feedbackPeriod->getElementsByTagName('Count')->item(0)->nodeValue,
		    				'period_in_days' => $feedbackPeriod->getElementsByTagName('PeriodInDays')->item(0)->nodeValue
		    			);
		    		}
		    	}
	    	}
	    	
	    	$sellerRatingSummaryArray = $responseDoc->getElementsByTagName("SellerRatingSummaryArray");
	    	
	    	$resp['seller_rating_summary'] = array();
	    	if($sellerRatingSummaryArray->length > 0) {
	    		$averageRatingSummaries = $sellerRatingSummaryArray->item(0)->getElementsByTagName('AverageRatingSummary');
	    		foreach ($averageRatingSummaries as $averageRatingSummary) {
	    			$feedbackSummaryPeriod = $averageRatingSummary->getElementsByTagName('FeedbackSummaryPeriod')->item(0)->nodeValue;
	    			$averageRatingDetails = $averageRatingSummary->getElementsByTagName('AverageRatingDetails');
	    			$o = array('period'=>$feedbackSummaryPeriod, 'details'=>array());
	    			foreach ($averageRatingDetails as $averageRatingDetail) {
	    				$o['details'][] = array(
	    					'rating'=>$averageRatingDetail->getElementsByTagName('Rating')->item(0)->nodeValue,
	    					'rating_count'=>$averageRatingDetail->getElementsByTagName('RatingCount')->item(0)->nodeValue,
	    				    'rating_detail'=>$averageRatingDetail->getElementsByTagName('RatingDetail')->item(0)->nodeValue
	    				);
	    				
	    			}
	    			$resp['seller_rating_summary'][] = $o;
	    		}
	    	}
	    	
	    	return $resp;
	}


    public function getFeedbackReceived($user, $page, $entriesPerPage) {
        $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
        $requestXmlBody .= '<GetFeedbackRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
        $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";

        $requestXmlBody .= "<UserID>" . $user . "</UserID>";
        $requestXmlBody .= "<FeedbackType>FeedbackReceivedAsSeller</FeedbackType>";
        $requestXmlBody .= "<DetailLevel>ReturnAll</DetailLevel>";

        $requestXmlBody .= "<Pagination><EntriesPerPage>" . $entriesPerPage . "</EntriesPerPage> <PageNumber>". $page ."</PageNumber></Pagination>";

        $requestXmlBody .= '</GetFeedbackRequest>';


        $session = new EbaySession($this->requestToken, $this->devID, $this->appID, $this->certID, $this->serverUrl, $this->compatLevel, $this->siteID, $this->verb);
        $responseDoc = $this->call($requestXmlBody, $session);

        $response = array();
        $errors = $this->getErrors();
        if(!empty($errors)) {
            $response['errors'] = $errors;
        }

        $response['page_number'] = ($responseDoc->getElementsByTagName('PageNumber')->length) ? $responseDoc->getElementsByTagName('PageNumber')->item(0)->nodeValue: '0';
        $response['total_number_of_entries'] = ($responseDoc->getElementsByTagName('TotalNumberOfEntries')->length) ? $responseDoc->getElementsByTagName('TotalNumberOfEntries')->item(0)->nodeValue: '0';
        $response['total_number_of_pages'] = ($responseDoc->getElementsByTagName('TotalNumberOfPages')->length) ? $responseDoc->getElementsByTagName('TotalNumberOfPages')->item(0)->nodeValue: '0';

        $response['feedbacks'] = array();
        $feedbacks = $responseDoc->getElementsByTagName('FeedbackDetail');
        foreach ($feedbacks as $feedback) {
            $response['feedbacks'][] = array(
                'feedback_id' => ($feedback->getElementsByTagName('FeedbackID')->length) ? $feedback->getElementsByTagName('FeedbackID')->item(0)->nodeValue: '',
                'user' => ($feedback->getElementsByTagName('CommentingUser')->length) ? $feedback->getElementsByTagName('CommentingUser')->item(0)->nodeValue: '',
                'role' => ($feedback->getElementsByTagName('Role')->length) ? $feedback->getElementsByTagName('Role')->item(0)->nodeValue: '',
                'score' => ($feedback->getElementsByTagName('CommentingUserScore')->length) ? $feedback->getElementsByTagName('CommentingUserScore')->item(0)->nodeValue: '',
                'comment_text' => ($feedback->getElementsByTagName('CommentText')->length) ? $feedback->getElementsByTagName('CommentText')->item(0)->nodeValue: '',
                'comment_time' => ($feedback->getElementsByTagName('CommentTime')->length) ? $feedback->getElementsByTagName('CommentTime')->item(0)->nodeValue: '',
                'comment_type' => ($feedback->getElementsByTagName('CommentType')->length) ? $feedback->getElementsByTagName('CommentType')->item(0)->nodeValue: '',
                'item_id' => ($feedback->getElementsByTagName('ItemID')->length) ? $feedback->getElementsByTagName('ItemID')->item(0)->nodeValue: '',
                'item_price' => ($feedback->getElementsByTagName('ItemPrice')->length) ? $feedback->getElementsByTagName('ItemPrice')->item(0)->nodeValue : '',
                'item_price_currency' => ($feedback->getElementsByTagName('ItemPrice')->length) ? $feedback->getElementsByTagName('ItemPrice')->item(0)->getAttribute('currencyID'): '',
                'item_title' => ($feedback->getElementsByTagName('ItemTitle')->length) ? $feedback->getElementsByTagName('ItemTitle')->item(0)->nodeValue: '',
                'order_line_item_id' => ($feedback->getElementsByTagName('OrderLineItemID')->length) ? $feedback->getElementsByTagName('OrderLineItemID')->item(0)->nodeValue: '',
                'transaction_id' => ($feedback->getElementsByTagName('TransactionID')->length) ? $feedback->getElementsByTagName('TransactionID')->item(0)->nodeValue: ''
            );
        }

        return $response;
    }
	
}
?>