<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class CompleteSaleCall extends BaseCall {

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
        $this->verb = "CompleteSale";
        $this->serverUrl = $serverUrl;
    }

    public function completeSale($orderData) {
        $requestXmlBody  = '<?xml version="1.0" encoding="utf-8" ?>';
        $requestXmlBody .= '<CompleteSaleRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
        $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>";

        if(isset($orderData['order_line_item_id']) && !empty($orderData['order_line_item_id'])) {
            $requestXmlBody .= '<OrderLineItemID>' . $orderData['order_line_item_id'] .'</OrderLineItemID>';
        }

        if(isset($orderData['order_id']) && !empty($orderData['order_id'])) {
            $requestXmlBody .= '<OrderID>' . $orderData['order_id'] .'</OrderID>';
        }

        if(isset($orderData['paid'])) {
           $requestXmlBody .= '<Paid>' . ((!empty($orderData['paid']))? 'True' : 'False') .'</Paid>';
        }

        if(!isset($orderData['shipping_carrier_used']) || empty($orderData['shipping_carrier_used'])) {
            $orderData['shipping_carrier_used'] = 'Other';
        }

        if(isset($orderData['shipment_tracking_number'])) {
            $requestXmlBody .= '<Shipment><ShipmentTrackingDetails><ShippingCarrierUsed>' . $orderData['shipping_carrier_used'] .'</ShippingCarrierUsed><ShipmentTrackingNumber>' . $orderData['shipment_tracking_number'] .'</ShipmentTrackingNumber></ShipmentTrackingDetails></Shipment>';
        }

        $requestXmlBody .= "</CompleteSaleRequest>";



        $session = new EbaySession($this->requestToken, $this->devID, $this->appID, $this->certID, $this->serverUrl, $this->compatLevel, $this->siteID, $this->verb);
        $responseDoc = $this->call($requestXmlBody, $session);

        $response = array();
        $errors = $this->getErrors();
        if(!empty($errors)) {
            $response['errors'] = $errors;
        }

        $Ack = $responseDoc->getElementsByTagName('Ack');
        if($Ack->length > 0) {
            $response['status'] = $Ack->item(0)->nodeValue;
        }
        return $response;

    }



}
?>