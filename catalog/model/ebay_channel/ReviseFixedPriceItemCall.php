<?php require_once('EbaySession.php') ?>
<?php require_once('BaseCall.php') ?>
<?php
class ReviseFixedPriceItemCall extends BaseCall {
	
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
			$this->verb = "ReviseFixedPriceItem";
	        $this->serverUrl = $serverUrl;	
		}
		
	public function reviseItem($itemId, $product, $profile, $settings = array()) {
	    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
	    $requestXmlBody .= '<ReviseFixedPriceItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
	    $requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$this->requestToken</eBayAuthToken></RequesterCredentials>\n";

        if(isset($settings['revise_item_specifics_enabled'])) {
            if($settings['revise_item_specifics_enabled'] && (!isset($profile['item_specifics']) || empty($profile['item_specifics']))) {
                $requestXmlBody .= '<DeletedField>Item.ItemSpecifics</DeletedField>';
            }
        } else if(!isset($profile['item_specifics']) || empty($profile['item_specifics'])) {
            $requestXmlBody .= '<DeletedField>Item.ItemSpecifics</DeletedField>';
        }

        if(empty($profile['list_profile']['subtitle'])) {
            $requestXmlBody .= '<DeletedField>Item.SubTitle</DeletedField>';
        }



	    $requestXmlBody .= '<Item>';
	    $requestXmlBody .= '<ItemID>'.$itemId.'</ItemID>';


        //--------SKU---------------------
        if(isset($settings['revise_sku_enabled'])) {
            if($settings['revise_sku_enabled']) {
                $requestXmlBody .= $this->getSkuXML($product);
            }
        } else {
            $requestXmlBody .= $this->getSkuXML($product);
        }

        //-------PayPalEmailAddress-----------------
        if(isset($settings['revise_paypalemail_enabled'])) {
            if($settings['revise_paypalemail_enabled']) {
                $requestXmlBody .= $this->getPayPalEmailAddressXML($profile);
            }
        } else {
            $requestXmlBody .= $this->getPayPalEmailAddressXML($profile);
        }


        //-------DispatchTimeMax-----------------
        if(isset($settings['revise_dispatch_time_max_enabled'])) {
            if($settings['revise_dispatch_time_max_enabled']) {
                $requestXmlBody .= $this->getDispatchTimeMaxXML($profile);
            }
        } else {
            $requestXmlBody .= $this->getDispatchTimeMaxXML($profile);
        }

        //-------ListingDuration-----------------
        if(isset($settings['revise_listing_duration_enabled'])) {
            if($settings['revise_listing_duration_enabled']) {
                $requestXmlBody .= $this->getListingDurationXML($profile);
            }
        } else {
            $requestXmlBody .= $this->getListingDurationXML($profile);
        }

        //-------ListingType-----------------
        if(isset($settings['revise_listing_type_enabled'])) {
            if($settings['revise_listing_type_enabled']) {
                $requestXmlBody .= $this->getListingTypeXML($profile);
            }
        } else {
            $requestXmlBody .= $this->getListingTypeXML($profile);
        }

        //-------Location-----------------
        if(isset($settings['revise_postal_code_enabled'])) {
            if($settings['revise_postal_code_enabled']) {
                $requestXmlBody .= $this->getCountryXML($profile);
                $requestXmlBody .= $this-> getPostalCodeXML($profile);
            }
        } else {
            $requestXmlBody .= $this->getCountryXML($profile);
            $requestXmlBody .= $this-> getPostalCodeXML($profile);
        }


        //-------PrimaryCategory-------
        if(isset($settings['revise_primary_category_enabled'])) {
            if($settings['revise_primary_category_enabled']) {
                $requestXmlBody .= $this->getPrimaryCategoryXML($profile);
            }
        } else {
            $requestXmlBody .= $this->getPrimaryCategoryXML($profile);
        }

        //----------PaymentMethods---------------------------
        if(isset($settings['revise_payment_methods_enabled'])) {
            if($settings['revise_payment_methods_enabled']) {
                $requestXmlBody .= $this->getPaymentMethodsXML($profile);
            }
        } else {
            $requestXmlBody .= $this->getPaymentMethodsXML($profile);
        }

        //---------------Item Title-----
        if(isset($settings['revise_title_enabled'])) {
            if($settings['revise_title_enabled']) {
                $requestXmlBody .= $this->getItemTitleXML($product);
            }
        } else {
            $requestXmlBody .= $this->getItemTitleXML($product);
        }

        //---------------Item Description-----
        if(isset($settings['revise_description_enabled'])) {
            if($settings['revise_description_enabled']) {
                $requestXmlBody .= $this->getItemDescriptionXML($product);
            }
        } else {
            $requestXmlBody .= $this->getItemDescriptionXML($product);
        }

        //--------------PictureDetails---------------
        if(isset($settings['revise_picture_details_enabled'])) {
            if($settings['revise_picture_details_enabled']) {
                $requestXmlBody .= $this->getPictureDetailsXML($product, $settings['general_list_product_cover_image']);
            }
        } else {
            $requestXmlBody .= $this->getPictureDetailsXML($product, $settings['general_list_product_cover_image']);
        }

        //------------item_specifics-----------------
        if(isset($settings['revise_item_specifics_enabled'])) {
            if($settings['revise_item_specifics_enabled']) {
                $requestXmlBody .= $this->getItemSpecificsXML($product, $profile);
            }
        } else {
            $requestXmlBody .= $this->getItemSpecificsXML($product, $profile);
        }

        //-------Condition-------
        if(isset($settings['revise_condition_enabled'])) {
            if($settings['revise_condition_enabled']) {
                $requestXmlBody .= $this->getConditionXML($profile);
            }
        } else {
            $requestXmlBody .= $this->getConditionXML($profile);
        }


        //--------------ReturnPolicy--------------------------------
        if(isset($settings['revise_return_policy_enabled'])) {
            if($settings['revise_return_policy_enabled']) {
                $requestXmlBody .= $this->getReturnPolicyXML($profile);
            }
        } else {
            $requestXmlBody .= $this->getReturnPolicyXML($profile);
        }

		//---------------Quantity----------------------------------------------
        if($settings['revise_quantity_enabled']) {
            $requestXmlBody .= $this->getQuantityXML($product, $profile);
        }

		//---------------Price----------------------------------------------
        if($settings['revise_price_enabled']) {

            //-------Currency-----------------
            $requestXmlBody .= $this->getCurrencyXML($profile);
            $requestXmlBody .= $this->getPriceXML($product, $profile);
        }


        //--------------------ShippingDetails-----------------------------
        if(isset($settings['revise_shipping_details_enabled'])) {
            if($settings['revise_shipping_details_enabled']) {
                $requestXmlBody .= $this->getShippingDetailsXML($product, $profile);
            }
        } else {
            $requestXmlBody .= $this->getShippingDetailsXML($product, $profile);
        }

		//---------------Variations----------------------------------------------
		$requestXmlBody .= $this->getVariationsXML($product, $profile);

	    $requestXmlBody .= "</Item>";
	    
	    $requestXmlBody .= "</ReviseFixedPriceItemRequest>";
	    
	    //echo $requestXmlBody;
	    
	   	$session = new EbaySession($this->requestToken, $this->devID, $this->appID, $this->certID, $this->serverUrl, $this->compatLevel, $this->siteID, $this->verb);
		$responseDoc = $this->call($requestXmlBody, $session);
		  
		    
		$response = array();
		$errors = $this->getErrors();
		if(!empty($errors)) {
			$response['errors'] = $errors;
		}
	    
		$fees = $responseDoc->getElementsByTagName('Fee');
    	if($fees->length > 0) {
    		$response['fees'] = array();
	    	foreach ($fees as $fee) {
	    		$name = $fee->getElementsByTagName('Name');
	    		if($name->length >0) {
	    			$response['fees'][] = array(
	    			"name" => $name->item(0)->nodeValue,
    				"fee" => floatval($fee->getElementsByTagName('Fee')->item(0)->nodeValue),
	    			"currency" => $fee->getElementsByTagName('Fee')->item(0)->getAttribute('currencyID')
	    		);
	    		}
	    		
	    		
	    	}
    	}
    	$itemId = $responseDoc->getElementsByTagName('ItemID');
    	if($itemId->length > 0) {
    		$response['item_id'] =  $itemId->item(0)->nodeValue;
    	}
    	
		$StartTime = $responseDoc->getElementsByTagName('StartTime');
    	if($StartTime->length > 0) {
    		$response['start_time'] =  $StartTime->item(0)->nodeValue;
    	}
    	
		$EndTime = $responseDoc->getElementsByTagName('EndTime');
    	if($EndTime->length > 0) {
    		$response['end_time'] =  $EndTime->item(0)->nodeValue;
    	}
    	
	    return $response;      
	}
	
}
?>