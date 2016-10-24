<?php require_once('h2o.php') ?>
<?php
class BaseCall {

    private $maximumRetries = 3;
    private $delay = 10;
    private $errors = array();

    public function call($requestXmlBody, $session, $retryCall = 0) {

        $debug = false;




        //echo $requestXmlBody; die();

        $responseXml = $session->sendHttpRequest($requestXmlBody);
        if(stristr($responseXml, 'HTTP 404') || $responseXml == '') {
            if($retryCall < $this->maximumRetries) {
                sleep($this->delay);
                return $this->call($requestXmlBody, $session, $retryCall + 1);
            } else {
                throw new Exception("Error sending request");
            }
        }


        if($debug) {
            $delLine = "\n=======================================================================================\n";
            $selLine = "\n---------------------------------------------------------------------------------------\n";
            if( !file_exists(DIR_DOWNLOAD . 'ebay_api/logs') ) mkdir(DIR_DOWNLOAD . 'ebay_api/logs', 0777, TRUE);
            file_put_contents(DIR_DOWNLOAD . 'ebay_api/logs/requests.log', $delLine . $requestXmlBody . $selLine . $responseXml, FILE_APPEND);
        }

        //echo $responseXml; 	die();

        $responseDoc = new DomDocument();
        $responseDoc->loadXML($responseXml);

        $this->errors = array();
        $xErrors = $responseDoc->getElementsByTagName('Errors');
        if($xErrors->length > 0) {
            foreach ($xErrors as $error) {
                $errorCode = $error->getElementsByTagName('ErrorCode')->item(0)->nodeValue;
                $severity = $error->getElementsByTagName('SeverityCode')->item(0)->nodeValue;
                $errorParameters = $error->getElementsByTagName('ErrorParameters');
                $parameters = array();
                foreach ($errorParameters as $errorParameter) {
                    if(!empty($errorParameter->nodeValue)) {
                        $parameters[] = array('value'=>htmlspecialchars($errorParameter->nodeValue));
                    }
                }


                $this->errors[] = array(
                    "code" => $errorCode,
                    "short_message" => $error->getElementsByTagName('ShortMessage')->item(0)->nodeValue,
                    "long_message" => $error->getElementsByTagName('LongMessage')->item(0)->nodeValue,
                    "parameters" => $parameters,
                    "severity_code" => $severity
                );

                if($severity == 'Error' && ($errorCode == 3021 || $errorCode == 24 || $errorCode == 10007)) {
                    if($retryCall < $this->maximumRetries) {
                        sleep($this->delay);
                        return $this->call($requestXmlBody, $session, $retryCall + 1);
                    }
                }

            }
        }



        return $responseDoc;
    }

    public function callOpt($requestXmlBody, $prinaryTag, $session, $retryCall = 0) {
        if( !file_exists(DIR_DOWNLOAD . 'ebay_api/calls') ) mkdir(DIR_DOWNLOAD . 'ebay_api/calls', 0777, TRUE);
        $file = DIR_DOWNLOAD . 'ebay_api/calls/'.$session->getVerb(). '_' .$session->getSiteId() . '.xml';
        $fp = fopen ($file, 'w+');

        $session->sendHttpAndSave($requestXmlBody, $fp);
        fclose($fp);
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
            $endPos = $this->getPos($handle, $endNode, $startPos) + mb_strlen($endNode);
            // Jump back to the start position
            fseek($handle, $startPos);
            // Read the data
            $data = fread($handle, ($endPos-$startPos));
            // pass the $data into the callback
            $callback($data);
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
        $stringPos = mb_strpos($data, $string);
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

    public function getErrors() {
        return $this->errors;
    }

    public function getCurrencyXML($profile) {
        return '<Currency>'. $profile['list_profile']['currency']  ."</Currency>\n";


    }

    public function getPayPalEmailAddressXML($profile) {
        return '<PayPalEmailAddress>'. htmlspecialchars($profile['list_profile']['paypal_email'])."</PayPalEmailAddress>\n";
    }

    public function getDispatchTimeMaxXML($profile) {
        $xml = '';
        if(isset($profile['list_profile']['dispatch_time']) && !empty($profile['list_profile']['dispatch_time'])) {
            $xml = '<DispatchTimeMax>'. $profile['list_profile']['dispatch_time']."</DispatchTimeMax>\n";
        }
        return $xml;
    }

    public function getListingDurationXML($profile) {
        return '<ListingDuration>'. $profile['list_profile']['duration']."</ListingDuration>\n";
    }

    public function getListingTypeXML($profile) {
        return '<ListingType>'. $profile['list_profile']['listing_type']."</ListingType>\n";
    }

    public function getPostalCodeXML($profile) {
        if(!empty($profile['list_profile']['location'])) {
            return "<Location>". htmlspecialchars($profile['list_profile']['location']). "</Location>\n";
        } else {
            return "<PostalCode>". htmlspecialchars($profile['list_profile']['zip_postcode']). "</PostalCode>\n";
        }
    }

    public function getCountryXML($profile) {
        return '<Country>'.  htmlspecialchars($profile['list_profile']['country'])  ."</Country>\n";
    }

    public function getSkuXML($product) {
        return "<SKU>EC-" . $product['product']['product_id']  ."</SKU>\n";
    }

    public function getQuantityXML($product, $profile) {
        if(empty($product['variations'])) {
            return '<Quantity>'.  htmlspecialchars($product['product']['quantity'])  ."</Quantity>\n";
        }
    }

    public function getPrimaryCategoryXML($profile) {
        $requestXmlBody = '<PrimaryCategory>';
        $requestXmlBody .= '<CategoryID>'. $profile['list_profile']['ebay_category_id'] ."</CategoryID>\n";
        $requestXmlBody .= "</PrimaryCategory>\n";

        if(isset($profile['list_profile']['ebay_store_category_id']) && !empty($profile['list_profile']['ebay_store_category_id'])) {
            $requestXmlBody .= '<Storefront>';
            $requestXmlBody .= '<StoreCategoryID>'. $profile['list_profile']['ebay_store_category_id'] ."</StoreCategoryID>\n";
            $requestXmlBody .= "</Storefront>\n";
        }

        if($profile['list_profile']['private_listing']) {
            $requestXmlBody .= "<PrivateListing>true</PrivateListing>\n";
        }

        return $requestXmlBody;
    }

    public function getPaymentMethodsXML($profile) {
        $requestXmlBody = '';
        foreach ($profile['list_payment_method'] as $payment) {
            $requestXmlBody .= '<PaymentMethods>'. htmlspecialchars($payment['name']) ."</PaymentMethods>\n";
        }
        return $requestXmlBody;
    }

    public function getItemTitleAndDescriptionXML($product) {
        $requestXmlBody = $this->getItemTitleXML($product);
        $requestXmlBody .= $this->getItemDescriptionXML($product);
        return $requestXmlBody;
    }

    public function getItemTitleXML($product) {
        return '<Title><![CDATA['. $this->truncate(html_entity_decode($product['product']['title']), 80)  ."]]></Title>\n";
    }

    public function getItemDescriptionXML($product) {
        $requestXmlBody = '';
        if(!$product['has_template']) {
            $requestXmlBody .= "<Description><![CDATA[". html_entity_decode($product['product']['description']) ."]]></Description>\n";
        } else {
            $images = array();
            $image = '';
            if($product['product']['pictureUrl'] || isset($product['product']['images'])) {
                $img = preg_replace('/^https(?=:\/\/)/i','http', $product['product']['pictureUrl']);
                $image = str_replace(' ', '%20', $img);
                $images[] = $image;

                if(isset($product['product']['images'])) {
                    foreach ($product['product']['images'] as $imageUrl) {
                        $imageUrl = preg_replace('/^https(?=:\/\/)/i','http', $imageUrl);
                        $images[] = str_replace(' ', '%20', $imageUrl);
                    }
                }
            }


            $attributeGroups = array();
            if(isset($product['product']['attributes']) && !empty($product['product']['attributes'])) {
                foreach ($product['product']['attributes'] as $attributeGroup) {
                    if(!empty($attributeGroup['name'])) {
                        $group = array('name' => $attributeGroup['name'], 'attrbutes' => array());

                        foreach ($attributeGroup['attribute'] as $attribute) {
                            $group['attrbutes'][] = array('name'=>$attribute['name'], 'value' => $attribute['text']);
                        }

                        $attributeGroups[] = $group;
                    }
                }
            }

            $options = array(
                'title' => $product['product']['title'],
                'name' => $product['product']['title'],
                'description' => $product['product']['description'],
                'image' => $image,
                'images' => $images,
                'has_images' => count($images) > 0,
                'img_tpl' => $images,
                'attrbute_groups' => $attributeGroups);

            if(!empty($product['template']['options'])) {
                foreach($product['template']['options'] as $key=>$value) {
                    $options[$key] = $value;
                }
            }

            $template = new H2o($product['template']['html']);
            $product['product']['description'] = $template->render($options);
            $requestXmlBody .= "<Description><![CDATA[". html_entity_decode($product['product']['description'])  ."]]></Description>\n";

        }
        return $requestXmlBody;
    }

    private function truncate($string, $length, $dots = "") {
        return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
    }

    public function getPictureDetailsXML($product, $onlyCover = false) {
        $requestXmlBody = '';

        $pictures = '';//TODO REMOVE AFTER FIX VARIATIONS PICTURES
        if(!empty($product['variations'])) {
            foreach ($product['variations']['variations'] as $variation) {
                if (isset($variation['image']) && !empty($variation['image'])) {
                    $imageUrl = preg_replace("/^https:/i", "http:", $variation['image']);
                    $imageUrl = htmlspecialchars($imageUrl);
                    $imageUrl = str_replace(' ', '%20', $imageUrl);
                    $pictures .= '<PictureURL>'. $imageUrl ."</PictureURL>\n";
                }
            }
        }

        if($product['product']['pictureUrl'] || isset($product['product']['images'])) {
            $requestXmlBody .= '<PictureDetails>';
            $img = $product['product']['pictureUrl'];
            $img = preg_replace("/^https:/i", "http:", $img);
            $img = htmlspecialchars($img);
            $img = str_replace(' ', '%20', $img);

            $requestXmlBody .= '<PictureURL>'. $img ."</PictureURL>\n";
            if(!empty($pictures)) {
                $requestXmlBody .= $pictures . "\n";
            }

            if(!$onlyCover && isset($product['product']['images'])) {
                foreach ($product['product']['images'] as $i=>$imageUrl) {
                    if(!empty($imageUrl)) {
                        if($i < 11) {
                            $imageUrl = preg_replace("/^https:/i", "http:", $imageUrl);
                            $imageUrl = htmlspecialchars($imageUrl);
                            $imageUrl = str_replace(' ', '%20', $imageUrl);
                            $requestXmlBody .= '<PictureURL>'. $imageUrl ."</PictureURL>\n";
                        }
                    }
                }
            }
            $requestXmlBody .= "</PictureDetails>\n";
        }

        return $requestXmlBody;
    }

    public function getItemSpecificsXML($product, $profile) {
        $requestXmlBody = '';


        if(!empty($profile['list_profile']['subtitle'])) {
            $requestXmlBody = '<SubTitle><![CDATA['. html_entity_decode($profile['list_profile']['subtitle']) ."]]></SubTitle>\n";
        }

        if(isset($product['product']['item_specifics'])) {
            $specifics = $product['product']['item_specifics'];

            if(empty($product['product']['item_specifics'])) {//Old version
                if(isset($profile['item_specifics'])) {

                    $specifics = $profile['item_specifics'];
                    foreach ($profile['item_specifics'] as $name => $value) {
                        $exist = false;

                        if($profile['list_profile']['variations_enabled'] && isset($product['variations']['variation_specifics_set'])) {
                            foreach ($product['variations']['variation_specifics_set'] as $variation_specifics_set) {
                                if(strtolower($variation_specifics_set['name']) == strtolower($name) ) {
                                    $exist = true;
                                }
                            }
                        }

                        if(!$exist) {
                            $specifics[$name] = $value;
                        }
                    }

                    if($profile['list_profile']['attributes_enabled'] && isset($product['product']['attributes'])) {
                        foreach ($product['product']['attributes'] as $group) {
                            foreach ($group['attribute'] as $attribute) {
                                $exist = false;

                                if($profile['list_profile']['variations_enabled'] && isset($product['variations']['variation_specifics_set'])) {
                                    foreach ($product['variations']['variation_specifics_set'] as $variation_specifics_set) {
                                        if(strtolower($variation_specifics_set['name']) == strtolower($attribute['name']) ) {
                                            $exist = true;
                                        }
                                    }
                                }

                                if(!$exist) {
                                    $specifics[$attribute['name']] = $attribute['text'];
                                }
                            }
                        }
                    }

                } else {
                    if($profile['list_profile']['attributes_enabled'] && isset($product['product']['attributes'])) {
                        foreach ($product['product']['attributes'] as $group) {
                            foreach ($group['attribute'] as $attribute) {
                                $specifics[$attribute['name']] = $attribute['text'];
                            }
                        }
                    }
                }

                foreach ($specifics as $name => $value) {
                    if (in_array(strtolower($name), array('brand', 'marke', 'hersteller')) && !empty($product['product']['brand'])) {
                        $specifics[$name] = $product['product']['brand'];
                    }

                    if (in_array(strtolower($name), array('mpn', 'herstellernummer')) && !empty($product['product']['model'])) {
                        $specifics[$name] = $product['product']['model'];
                    }

                    if (in_array(strtolower($name), array('ean')) && !empty($product['product']['ean'])) {
                        $specifics[$name] = $product['product']['ean'];
                    }

                    if (in_array(strtolower($name), array('upc')) && !empty($product['product']['upc'])) {
                        $specifics[$name] = $product['product']['upc'];
                    }

                    if (in_array(strtolower($name), array('isbn')) && !empty($product['product']['isbn'])) {
                        $specifics[$name] = $product['product']['isbn'];
                    }
                }

                $pDetails = array();

                if(!(isset($specifics['Brand']) || isset($specifics['Marke']) || isset($specifics['Hersteller'])) &&  !empty($product['product']['brand'])) {
                    $specifics['Brand'] = $product['product']['brand'];
                }

                if(!(isset($specifics['MPN']) || isset($specifics['Herstellernummer'])) &&  !empty($product['product']['model'])) {
                    $specifics['MPN'] = $product['product']['model'];
                }

                if(!isset($specifics['EAN']) &&  !empty($product['product']['ean'])) {
                    $specifics['EAN'] = $product['product']['ean'];
                    $pDetails['EAN'] = $product['product']['ean'];
                }

                if(!isset($specifics['UPC']) &&  !empty($product['product']['upc'])) {
                    $specifics['UPC'] = $product['product']['upc'];
                    $pDetails['UPC'] = $product['product']['upc'];
                }

                if(!empty($profile['list_profile']['upc_enabled']) && strtolower($profile['list_profile']['upc_enabled']) == 'required' && !isset($pDetails['UPC'])) {
                    if(isset($profile['ebay_details']['ProductDetails']['ProductIdentifierUnavailableText'])) {
                        $pDetails['UPC'] = $profile['ebay_details']['ProductDetails']['ProductIdentifierUnavailableText'];
                        $specifics['UPC'] = $profile['ebay_details']['ProductDetails']['ProductIdentifierUnavailableText'];
                    }
                }

                if(!empty($profile['list_profile']['ean_enabled']) && strtolower($profile['list_profile']['ean_enabled']) == 'required' && !isset($pDetails['EAN'])) {
                    if(isset($profile['ebay_details']['ProductDetails']['ProductIdentifierUnavailableText'])) {
                        $pDetails['EAN'] = $profile['ebay_details']['ProductDetails']['ProductIdentifierUnavailableText'];
                        $specifics['EAN'] = $profile['ebay_details']['ProductDetails']['ProductIdentifierUnavailableText'];
                    }
                }

                if(!$profile['list_profile']['variations_enabled'] || empty($product['variations'])) {
                    if (!empty($pDetails) || (isset($specifics['Brand']) && isset($specifics['MPN']))) {

                        $requestXmlBody .= "<ProductListingDetails>";
                        foreach ($pDetails as $name => $value) {
                            $requestXmlBody .= "<" . $name . ">";
                            $requestXmlBody .= htmlspecialchars($value);
                            $requestXmlBody .= "</" . $name . ">" . "\n";
                        }


                        if (isset($specifics['Brand']) && isset($specifics['MPN'])) {
                            $requestXmlBody .= "<BrandMPN>";
                            $requestXmlBody .= "<Brand>" . htmlspecialchars($specifics['Brand']) . "</Brand>";
                            $requestXmlBody .= "<MPN>" . htmlspecialchars($specifics['MPN']) . "</MPN>";
                            $requestXmlBody .= "</BrandMPN>";
                        }
                        $requestXmlBody .= "<ListIfNoProduct>true</ListIfNoProduct>";
                        $requestXmlBody .= "</ProductListingDetails>";
                    }
                }

            } else {

                if(!$profile['list_profile']['variations_enabled'] || empty($product['variations'])) {
                    $productDetails = $product['product']['product_details'];
                    $productListingDetails = '';
                    if(!empty($productDetails)) {
                        $barnd = '';
                        $mpn = '';
                        foreach ($productDetails as $productDetail) {
                            if ($productDetail['name'] == 'EAN' || $productDetail['name'] == 'UPC') {
                                $productListingDetails .= "<" . $productDetail['name'] . ">";
                                $productListingDetails .= htmlspecialchars($productDetail['value']);
                                $productListingDetails .= "</" . $productDetail['name'] . ">" . "\n";
                            }
                            if ($productDetail['name'] == 'Brand') {
                                $barnd = $productDetail['value'];
                            }

                            if ($productDetail['name'] == 'MPN') {
                                $mpn = $productDetail['value'];
                            }
                        }

                        if (!empty($barnd) && !empty($mpn)) {
                            $productListingDetails .= "<BrandMPN>";
                            $productListingDetails .= "<Brand>" . htmlspecialchars($barnd) . "</Brand>";
                            $productListingDetails .= "<MPN>" . htmlspecialchars($mpn) . "</MPN>";
                            $productListingDetails .= "</BrandMPN>";
                        }
                    }

                    if(!empty($productListingDetails)) {
                        $requestXmlBody .= "<ProductListingDetails>" . $productListingDetails . "</ProductListingDetails>";
                    }
                }
            }




            if(!empty($specifics)) {
                $requestXmlBody .= "<ItemSpecifics>";
                foreach ($specifics as $name => $value) {
                    if(!empty($value)) {
                        $requestXmlBody .= "<NameValueList>";
                        $requestXmlBody .= "<Name>".htmlspecialchars($name)."</Name>\n";
                        $requestXmlBody .= "<Value>".htmlspecialchars($value)."</Value>\n";
                        $requestXmlBody .= "</NameValueList>\n";
                    }
                }
                $requestXmlBody .= "</ItemSpecifics>";
            }
        }

        return $requestXmlBody;
    }

    public function getConditionXML($profile) {
        $requestXmlBody = '';
        if(isset($profile['list_profile']['item_condition_id']) && $profile['list_profile']['item_condition_id'] && is_numeric($profile['list_profile']['item_condition_id'])) {
            $requestXmlBody .= '<ConditionID>'. $profile['list_profile']['item_condition_id']  ."</ConditionID>\n";
            if(isset($profile['list_profile']['item_condition_description']) && $profile['list_profile']['item_condition_description']) {
                $requestXmlBody .= '<ConditionDescription>'. htmlspecialchars($profile['list_profile']['item_condition_description'])  ."</ConditionDescription>\n";
            }
        }
        return $requestXmlBody;
    }

    public function getShippingPackageDetailsXML($profile) {
        $requestXmlBody = '';
        return $requestXmlBody;
    }

    public function getItemVinXML($profile) {
        $requestXmlBody = '';
        if(!empty($profile['list_profile']['vin'])) {
            $requestXmlBody .= '<VIN>'. $profile['list_profile']['vin']  ."</VIN>\n";
        }
        return $requestXmlBody;
    }

    public function getReturnPolicyXML($profile) {
        $requestXmlBody = '';
        if(isset($profile['list_profile']['return_policy_enabled'])) {
            $requestXmlBody .= '<ReturnPolicy>';
            $requestXmlBody .= '<ReturnsAcceptedOption>'. $profile['list_profile']['returns_accepted']  ."</ReturnsAcceptedOption>\n";
            if($profile['list_profile']['returns_accepted'] == 'ReturnsAccepted') {
                if($profile['list_profile']['return_policy_description']) {
                    $requestXmlBody .= '<Description>'. htmlspecialchars($profile['list_profile']['return_policy_description'])  ."</Description>\n";
                }

                if($profile['list_profile']['returns_within']) {
                    $requestXmlBody .= '<ReturnsWithinOption>'. htmlspecialchars($profile['list_profile']['returns_within'])  ."</ReturnsWithinOption>\n";
                }

                if($profile['list_profile']['shippingcost_paidby']) {
                    $requestXmlBody .= '<ShippingCostPaidByOption>'. htmlspecialchars($profile['list_profile']['shippingcost_paidby'])  ."</ShippingCostPaidByOption>\n";
                }
            }

            $requestXmlBody .= "</ReturnPolicy>\n";
        }
        return $requestXmlBody;
    }

    private function existsEbayVariation($ebay_variation, $product_variations) {
        foreach ($product_variations as $product_variation) {
            $ec = 0;
            $lTotal = count($product_variation['variation_specifics']);
            $eTotal = count($ebay_variation['variation_specifics']);
            if($lTotal <> $eTotal) {
                return false;
            }

            foreach($product_variation['variation_specifics'] as $variation_specific) {
                foreach($ebay_variation['variation_specifics'] as $ebay_variation_specific) {
                    $exists = strtolower($ebay_variation_specific['name']) == strtolower($variation_specific['name']) && strtolower($ebay_variation_specific['value']) == strtolower($variation_specific['value']);
                    if($exists) {
                        $ec++;
                    }
                }
            }

            if($ec != 0 && $ec == $lTotal) {
                return true;
            }
        }
        return false;
    }

    public function getVariationsXML($product, $profile) {
        $requestXmlBody = '';
        if($profile['list_profile']['variations_enabled'] && !empty($product['variations'])) {

            $requestXmlBody .= "<Variations>\n";
            $pictures = '';
            foreach ($product['variations']['variations'] as $variation) {
                $imageUrl = '';
                if(isset($variation['image']) && !empty($variation['image'])) {
                    $imageUrl = preg_replace("/^https:/i", "http:", $variation['image']);
                    $imageUrl = htmlspecialchars($imageUrl);
                    $imageUrl = str_replace(' ', '%20', $imageUrl);

                    $pictures .= "<Pictures>\n";
                    foreach ($variation['variation_specifics'] as $variation_specific) {

                        $pictures .= "<VariationSpecificName>" . htmlspecialchars($variation_specific['name']) ."</VariationSpecificName>\n";
                        $pictures .= "<VariationSpecificPictureSet>\n";
                        $pictures .="<VariationSpecificValue>". htmlspecialchars($variation_specific['value']) ."</VariationSpecificValue>\n";
                        $pictures .="<PictureURL>". $imageUrl ."</PictureURL>\n";
                        $pictures .= "</VariationSpecificPictureSet>\n";

                    }
                    $pictures .= "</Pictures>\n";
                }
            }

//            if(!empty($pictures)) {
//                $requestXmlBody .= $pictures;
//            }





            if(isset($product['p2e']['variations'])) {
                $ebay_variations = json_decode($product['p2e']['variations'], 1);
                $product_variations = $product['variations']['variations'];
                if ((is_array($ebay_variations)) or ($ebay_variations instanceof Traversable)) {
                    foreach($ebay_variations as $ebay_variation) {
                        $exists = $this->existsEbayVariation($ebay_variation, $product_variations);

                        if(!$exists) {
                            $requestXmlBody .= "<Variation>\n";
                            $requestXmlBody .= "<Delete>true</Delete>\n";
                            $requestXmlBody .= "<VariationSpecifics>\n";
                            foreach ($ebay_variation['variation_specifics']  as $variation_specific) {
                                $requestXmlBody .= "<NameValueList><Name>" . htmlspecialchars($variation_specific['name']) ."</Name><Value>". htmlspecialchars($variation_specific['value']) ."</Value></NameValueList>\n";
                            }
                            $requestXmlBody .= "</VariationSpecifics>\n";
                            $requestXmlBody .= "</Variation>\n";
                        }
                    }
                }

            }

            foreach ($product['variations']['variations'] as $variation) {
                $price = $variation['price'];
                $requestXmlBody .= "<Variation>\n";
                $requestXmlBody .= '<StartPrice>'. number_format($price, 2, '.', '')  ."</StartPrice>\n";
                $requestXmlBody .= '<Quantity>'. $variation['qty']  ."</Quantity>\n";
                if(isset($variation['sku']) && !empty($variation['sku']))  {
                    $requestXmlBody .= '<SKU>'. $variation['sku']  ."</SKU>\n";
                }

                if(
                    (!empty($profile['list_profile']['upc_enabled']) && strtolower($profile['list_profile']['upc_enabled']) == 'required')
                    ||
                    (!empty($profile['list_profile']['ean_enabled']) && strtolower($profile['list_profile']['ean_enabled']) == 'required')
                ) {
                    $requestXmlBody .= "<VariationProductListingDetails>\n";
                    if(!empty($profile['list_profile']['upc_enabled']) && strtolower($profile['list_profile']['upc_enabled']) == 'required') {
                        $requestXmlBody .= "<UPC>". $profile['ebay_details']['ProductDetails']['ProductIdentifierUnavailableText'] ."</UPC>\n";
                    }
                    if(!empty($profile['list_profile']['ean_enabled']) && strtolower($profile['list_profile']['ean_enabled']) == 'required') {
                        $requestXmlBody .= "<EAN>". $profile['ebay_details']['ProductDetails']['ProductIdentifierUnavailableText'] ."</EAN>\n";
                    }
                    $requestXmlBody .= "</VariationProductListingDetails>\n";
                }

                $requestXmlBody .= "<VariationSpecifics>\n";
                foreach ($variation['variation_specifics'] as $variation_specific) {
                    $name = $variation_specific['name'];
                    $requestXmlBody .= "<NameValueList><Name>" . htmlspecialchars($name) ."</Name><Value>". htmlspecialchars($variation_specific['value']) ."</Value></NameValueList>\n";
                }
                $requestXmlBody .= "</VariationSpecifics>\n";
                $requestXmlBody .= "</Variation>\n";
            }

            $requestXmlBody .= "<VariationSpecificsSet>\n";
            foreach ($product['variations']['variation_specifics_set'] as $variation_specifics_set) {
                $name = $variation_specifics_set['name'];
                $requestXmlBody .= "<NameValueList><Name>" . htmlspecialchars($name) ."</Name>\n";
                foreach ($variation_specifics_set['values'] as $value) {
                    $requestXmlBody .="<Value>". htmlspecialchars($value) ."</Value>\n";
                }
                $requestXmlBody .= "</NameValueList>\n";
            }
            $requestXmlBody .= "</VariationSpecificsSet>\n";

            $requestXmlBody .= "</Variations>\n";

        }

        //print_r($requestXmlBody);die();

        return $requestXmlBody;
    }

    public function getPriceXML($product, $profile) {
        $requestXmlBody = '';
        if(!($profile['list_profile']['variations_enabled'] && !empty($product['variations']))) {
            $price = $product['product']['price'];
            $requestXmlBody = '<StartPrice>'. number_format($price, 2, '.', '')  ."</StartPrice>\n";
        }
        return $requestXmlBody;
    }

    public function getAuctionPriceXML($product, $profile) {
        $requestXmlBody = '';
        if(isset($product['product']['bin_price'])) {
            $requestXmlBody = '<BuyItNowPrice>'. number_format($product['product']['bin_price'], 2, '.', '')  ."</BuyItNowPrice>\n";
        }
        return $requestXmlBody;
    }

    public function getShippingDetailsXML($product, $profile) {
        $requestXmlBody = '';

        if(isset($profile['list_shipping_service'])) {

            $flatShippingServices = array();
            $calculatedShippingServices = array();

            foreach ($profile['list_shipping_service'] as $shippingService) {
                if($shippingService['service_type'] == 'fd' || $shippingService['service_type'] == 'fi') {
                    $flatShippingServices[] = $shippingService;
                } else if($shippingService['service_type'] == 'ci' || $shippingService['service_type'] == 'cd') {
                    $calculatedShippingServices[] = $shippingService;
                }
            }

            $requestXmlBody .= "<ShippingDetails>\n";
            $requestXmlBody .= "<ShippingType>". $profile['list_profile']['shipping_type'] ."</ShippingType>\n";

            //Add flat
            $i = 1;
            foreach ($flatShippingServices as $shippingService) {
                if($shippingService['is_international']) {

                    $requestXmlBody .= "<InternationalShippingServiceOption>\n";
                    $requestXmlBody .= "<ShippingService>".htmlspecialchars($shippingService['service'])."</ShippingService>\n";
                    $requestXmlBody .= "<ShippingServicePriority>" . ($i++) ."</ShippingServicePriority>";
                    $requestXmlBody .= '<ShippingServiceAdditionalCost>'. number_format($shippingService['each_additional'], 2, '.', '')  ."</ShippingServiceAdditionalCost>\n";
                    $requestXmlBody .= '<ShippingServiceCost>'. number_format($shippingService['cost'], 2, '.', '')  ."</ShippingServiceCost>\n";

                    if(!empty($shippingService['locations'])) {
                        $locations = explode(",", $shippingService['locations']);
                        foreach ($locations as $location) {
                            $requestXmlBody .= "<ShipToLocation>" . $location . "</ShipToLocation>\n";
                        }
                    }
                    $requestXmlBody .= "</InternationalShippingServiceOption>\n";

                } else {
                    $requestXmlBody .= "<ShippingServiceOptions>\n";
                    $requestXmlBody .= "<ShippingService>".htmlspecialchars($shippingService['service'])."</ShippingService>\n";
                    $requestXmlBody .= "<ShippingServicePriority>" . ($i++) ."</ShippingServicePriority>";
                    if($shippingService['is_free_shipping']) {
                        $requestXmlBody .= "<FreeShipping>true</FreeShipping>\n";
                    } else {
                        $requestXmlBody .= '<ShippingServiceAdditionalCost>'. number_format($shippingService['each_additional'], 2, '.', '')  ."</ShippingServiceAdditionalCost>\n";
                        $requestXmlBody .= '<ShippingServiceCost>'. number_format($shippingService['cost'], 2, '.', '')  ."</ShippingServiceCost>\n";
                    }
                    $requestXmlBody .= "</ShippingServiceOptions>\n";
                }
            }

            //Add calcualted
            $i = 1;
            foreach ($calculatedShippingServices as $shippingService) {
                if($shippingService['is_international']) {
                    $requestXmlBody .= "<InternationalShippingServiceOption>\n";
                    $requestXmlBody .= "<ShippingService>".htmlspecialchars($shippingService['service'])."</ShippingService>\n";
                    if(!empty($shippingService['locations'])) {
                        $locations = explode(",", $shippingService['locations']);
                        foreach ($locations as $location) {
                            $requestXmlBody .= "<ShipToLocation>" . $location . "</ShipToLocation>\n";
                        }
                    }

                    $requestXmlBody .= "<ShippingServicePriority>" . ($i++) ."</ShippingServicePriority>";
                    $requestXmlBody .= "</InternationalShippingServiceOption>\n";
                } else {
                    $requestXmlBody .= "<ShippingServiceOptions>\n";
                    $requestXmlBody .= "<ShippingService>".htmlspecialchars($shippingService['service'])."</ShippingService>\n";
                    $requestXmlBody .= "<ShippingServicePriority>" . ($i++) ."</ShippingServicePriority>";
                    $requestXmlBody .= "</ShippingServiceOptions>\n";
                }
            }

            if(isset($profile['list_profile']['shipping_package']) && $profile['list_profile']['shipping_package']) {
                $requestXmlBody .= '<CalculatedShippingRate>';

                $requestXmlBody .= '<ShippingPackage>'. htmlspecialchars($profile['list_profile']['shipping_package'])  ."</ShippingPackage>";



//				if($product['product']['length'] > 0) {
//					$depth = $product['product']['length'];
//					if($product['product']['length_class_id'] == 1) {
//						$depth = $depth * 0.393700787401;
//					} else if($product['product']['length_class_id'] == 2) {
//						$depth = $depth * 0.0393700787;
//					}
//				}
//
//				if($product['product']['width'] > 0) {
//					$witdh = $product['product']['width'];
//					if($product['product']['length_class_id'] == 1) {
//						$witdh = $witdh * 0.393700787401;
//					} else if($product['product']['length_class_id'] == 2) {
//						$witdh = $witdh * 0.0393700787;
//					}
//				}
//
//				if($product['product']['height'] > 0) {
//					$length = $product['product']['height'];
//					if($product['product']['length_class_id'] == 1) {
//						$length = $length * 0.393700787401;
//					} else if($product['product']['length_class_id'] == 2) {
//						$length = $length * 0.0393700787;
//					}
//				}

                if(!empty($product['product']['package_length'])) {
                    $depth = $product['product']['package_length']['depth'];
                    $length = $product['product']['package_length']['length'];
                    $witdh = $product['product']['package_length']['width'];
                    $requestXmlBody .= '<PackageDepth>'. number_format($depth, 2, '.', '')  ."</PackageDepth>";
                    $requestXmlBody .= '<PackageLength>'. number_format($length, 2, '.', '')  ."</PackageLength>";
                    $requestXmlBody .= '<PackageWidth>'. number_format($witdh, 2, '.', '')  ."</PackageWidth>";
                } else {
                    $depth = $profile['list_profile']['dimension_depth'];
                    $length = $profile['list_profile']['dimension_length'];
                    $witdh = $profile['list_profile']['dimension_width'];
                    $requestXmlBody .= '<PackageDepth>'. number_format($depth, 2, '.', '')  ."</PackageDepth>";
                    $requestXmlBody .= '<PackageLength>'. number_format($length, 2, '.', '')  ."</PackageLength>";
                    $requestXmlBody .= '<PackageWidth>'. number_format($witdh, 2, '.', '')  ."</PackageWidth>";
                }

                if(!empty($product['product']['package_weight'])) {
                    $requestXmlBody .= '<WeightMajor unit="lbs" measurementSystem="English">' . number_format($product['product']['package_weight']['major'], 4, '.', '') . "</WeightMajor>";
                    $requestXmlBody .= '<WeightMinor unit="oz" measurementSystem="English">' . number_format($product['product']['package_weight']['minor'], 4, '.', '') . "</WeightMinor>";
                } else {
                    $requestXmlBody .= '<WeightMajor unit="lbs" measurementSystem="English">' . number_format($profile['list_profile']['weight_major'], 4, '.', '') . "</WeightMajor>";
                    $requestXmlBody .= '<WeightMinor unit="oz" measurementSystem="English">' . number_format($profile['list_profile']['weight_minor'], 4, '.', '') . "</WeightMinor>";
                }
                $requestXmlBody .= '<MeasurementUnit>English</MeasurementUnit> ';

                if($profile['list_profile']['is_irregular_package']) {
                    $requestXmlBody .= "<ShippingIrregular>true</ShippingIrregular>";
                }
                $requestXmlBody .= "<OriginatingPostalCode>" . $profile['list_profile']['zip_postcode'] . "</OriginatingPostalCode>";
                if($profile['list_profile']['shipping_type'] == 'Calculated') {
                    $requestXmlBody .= "<PackagingHandlingCosts>" . number_format($profile['list_profile']['package_handling_fee'], 2, '.', '') . "</PackagingHandlingCosts>";
                }

                $requestXmlBody .= "</CalculatedShippingRate>";
            }



            $requestXmlBody .= "</ShippingDetails>\n";

        }
        return $requestXmlBody;
    }

}
?>