<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class GetApiAccessRulesCall extends BaseCall {

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
        $this->verb = "GetApiAccessRules";
        $this->serverUrl = $serverUrl;
    }

    public function getApiAccessRules() {
        $requestXmlBody  = '<?xml version="1.0" encoding="utf-8" ?>';
        $requestXmlBody .= '<GetApiAccessRulesRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
        $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";
        $requestXmlBody .= "</GetApiAccessRulesRequest>";

        $session = new EbaySession($this->requestToken, $this->devID, $this->appID, $this->certID, $this->serverUrl, $this->compatLevel, $this->siteID, $this->verb);
        $responseDoc = $this->call($requestXmlBody, $session);

        $response = array();
        $errors = $this->getErrors();
        if(!empty($errors)) {
            $response['errors'] = $errors;
        }

        $ApiAccessRules = $responseDoc->getElementsByTagName('ApiAccessRule');
        $response['api_access_rules'] = array();
        foreach($ApiAccessRules as $ApiAccessRule) {
            $response['api_access_rules'][] = array(
                'call_name' => $ApiAccessRule->getElementsByTagName('CallName')->item(0)->nodeValue,
                'counts_toward_aggregate' => $ApiAccessRule->getElementsByTagName('CountsTowardAggregate')->item(0)->nodeValue,
                'daily_hard_limit' => $ApiAccessRule->getElementsByTagName('DailyHardLimit')->item(0)->nodeValue,
                'daily_soft_limit' => $ApiAccessRule->getElementsByTagName('DailySoftLimit')->item(0)->nodeValue,
                'daily_usage' => $ApiAccessRule->getElementsByTagName('DailyUsage')->item(0)->nodeValue,
                'mod_time' => $ApiAccessRule->getElementsByTagName('ModTime')->item(0)->nodeValue,
                'period' => $ApiAccessRule->getElementsByTagName('Period')->item(0)->nodeValue,
                'rule_current_status' => $ApiAccessRule->getElementsByTagName('RuleCurrentStatus')->item(0)->nodeValue,
            );
        }
        return $response;

    }

    private function getEndReasonById($id) {
        switch ($id) {
            case 1:	return "LostOrBroken";
            case 2:	return "NotAvailable";
            case 3:	return "Incorrect";
            case 4:	return "OtherListingError";
            case 5:	return "SellToHighBidder";
            default: return "NotAvailable";
        }
    }

}
?>