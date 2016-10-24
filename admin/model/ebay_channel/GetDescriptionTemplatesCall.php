<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class GetDescriptionTemplatesCall extends BaseCall {

    private $requestToken;
    private $devID;
    private $appID;
    private $certID;
    private $serverUrl;
    private $compatLevel;
    private $siteID;
    private $verb;

    public function __construct($userRequestToken, $developerID, $applicationID, $certificateID, $serverUrl, $compatLevel, $siteToUseID)
    {
        $this->requestToken = $userRequestToken;
        $this->devID = $developerID;
        $this->appID = $applicationID;
        $this->certID = $certificateID;
        $this->compatLevel = $compatLevel;
        $this->siteID = $siteToUseID;
        $this->verb = "GetDescriptionTemplates";
        $this->serverUrl = $serverUrl;
    }

    public function getDescriptionTemplates($filter = array()) {
        $requestXmlBody  = '<?xml version="1.0" encoding="utf-8" ?>';
        $requestXmlBody .= '<GetDescriptionTemplatesRequest xmlns="urn:ebay:apis:eBLBaseComponents">';

        $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";
        if(isset($filter['category_id']) && !empty($filter['category_id'])) {
            $requestXmlBody .= '<CategoryID>' . $filter['category_id'] . '</CategoryID>';
        }

        $requestXmlBody .= "</GetDescriptionTemplatesRequest>";

        $session = new EbaySession($this->requestToken, $this->devID, $this->appID, $this->certID, $this->serverUrl, $this->compatLevel, $this->siteID, $this->verb);
        $responseDoc = $this->call($requestXmlBody, $session);

        $response = array();
        $errors = $this->getErrors();
        if(!empty($errors)) {
            $response['errors'] = $errors;
        }


        return $response;

    }



}
?>