<?php
class ModelEbayChannelCronDao extends Model {

    public function updateProduct($productId) {
        $this->callJob('update_product', 'product_id=' . $productId, 2);
    }

    public function deleteProduct($productId) {
        $this->callJob('delete_product', 'product_id=' . $productId, 2);
    }

    public function updateStockAndPrice($startPage = false, $totalUpdated = false, $successUpdated = false, $processId = false) {
        $params = 'a=1';
        if($startPage) {
            $params.='&startPage=' . $startPage;
        }

        if($processId) {
            $params.='&processId=' . $processId;
        }

        if($totalUpdated) {
            $params.='&totalUpdated=' . $totalUpdated;
        }

        if($successUpdated) {
            $params.='&successUpdated=' . $successUpdated;
        }
        return $this->callJob('updateStockAndPrice', $params, 5);
    }

    public function updateProductsStock($itemsIds = false) {
        $params = 'v=1';
        if($itemsIds) {
            $params.='&itemsIds=' . $itemsIds;
        }
        $url = $this->getServiceUrl('update_products_stock', $params);
        $response = $this->callJob('update_products_stock', $params, 5);
        return array(
            "response" => $response,
            "url" => $url
        );
    }


    public function reviseAllProductsByProfileId($profileId, $startPage = false, $totalUpdated = false, $successUpdated = false, $processId = false) {
        $params = 'profileId=' . $profileId;
        if($startPage) {
            $params.='&startPage=' . $startPage;
        }

        if($processId) {
            $params.='&processId=' . $processId;
        }

        if($totalUpdated) {
            $params.='&totalUpdated=' . $totalUpdated;
        }

        if($successUpdated) {
            $params.='&successUpdated=' . $successUpdated;
        }
        $this->callJob('reviseAllProductsByProfileId', $params, 5);
    }

    public function moveListingProfile($profileId, $toProfileId, $startPage = false, $totalUpdated = false, $successUpdated = false, $processId = false) {
        $params = 'profileId=' . $profileId;
        $params.='&toProfileId=' . $toProfileId;
        if($startPage) {
            $params.='&startPage=' . $startPage;
        }

        if($processId) {
            $params.='&processId=' . $processId;
        }

        if($totalUpdated) {
            $params.='&totalUpdated=' . $totalUpdated;
        }

        if($successUpdated) {
            $params.='&successUpdated=' . $successUpdated;
        }
        $this->callJob('moveListingProfile', $params, 5);
    }

    public function endAllProductsByProfileId($profileId, $startPage = false, $totalUpdated = false, $successUpdated = false, $processId = false) {
        $params = 'profileId=' . $profileId;
        if($startPage) {
            $params.='&startPage=' . $startPage;
        }

        if($processId) {
            $params.='&processId=' . $processId;
        }

        if($totalUpdated) {
            $params.='&totalUpdated=' . $totalUpdated;
        }

        if($successUpdated) {
            $params.='&successUpdated=' . $successUpdated;
        }
        $this->callJob('endAllProductsByProfileId', $params, 5);
    }

    public function relistAllProductsByProfileId($profileId, $startPage = false, $totalUpdated = false, $successUpdated = false, $processId = false) {
        $params = 'profileId=' . $profileId;
        if($startPage) {
            $params.='&startPage=' . $startPage;
        }

        if($processId) {
            $params.='&processId=' . $processId;
        }

        if($totalUpdated) {
            $params.='&totalUpdated=' . $totalUpdated;
        }

        if($successUpdated) {
            $params.='&successUpdated=' . $successUpdated;
        }
        $this->callJob('relistAllProductsByProfileId', $params, 5);
    }

    public function generateImagesCache() {
        $this->callJob('generateImagesCache', 'a=1', 5);
    }

    public function sendInstall() {
        $this->callJob('sendInstallEmail', 'a=1', 5);
    }

    public function syncronize($itemsIds = false, $startPage = false, $totalUpdated = false, $processId = false) {
        $params = 'v=1';
        if($startPage) {
            $params.='&startPage=' . $startPage;
        }

        if($processId) {
            $params.='&processId=' . $processId;
        }

        if($itemsIds) {
            $params.='&itemsIds=' . $itemsIds;
        }

        if($totalUpdated) {
            $params.='&totalUpdated=' . $totalUpdated;
        }

        $this->callJob('syncronize_job', $params, 5);
    }

    public function importItems($itemsIds = false, $startPage = false, $totalUpdated = false, $processId = false) {
        $params = 'v=1';

        if($itemsIds) {
            $params.='&itemsIds=' . $itemsIds;
        }

        if($startPage) {
            $params.='&startPage=' . $startPage;
        }

        if($processId) {
            $params.='&processId=' . $processId;
        }

        if($totalUpdated) {
            $params.='&totalUpdated=' . $totalUpdated;
        }



        $this->callJob('import_items_job', $params, 5);

    }

    public function importLinks($startPage = false, $totalUpdated = false, $processId = false) {
        $params = 'v=1';
        if($startPage) {
            $params.='&startPage=' . $startPage;
        }

        if($processId) {
            $params.='&processId=' . $processId;
        }

        if($totalUpdated) {
            $params.='&totalUpdated=' . $totalUpdated;
        }

        $this->callJob('import_links_job', $params, 5);
    }

    public function importFeedback($startPage = false, $totalUpdated = false, $processId = false) {
        $params = 'v=1';
        if($startPage) {
            $params.='&startPage=' . $startPage;
        }

        if($processId) {
            $params.='&processId=' . $processId;
        }

        if($totalUpdated) {
            $params.='&totalUpdated=' . $totalUpdated;
        }

        $this->callJob('import_feedbacks_job', $params, 5);
    }

    public function scheduleListProfile($startPage = false, $totalUpdated = false, $processId = false) {
        $params = 'v=1';
        if($startPage) {
            $params.='&startPage=' . $startPage;
        }

        if($processId) {
            $params.='&processId=' . $processId;
        }

        if($totalUpdated) {
            $params.='&totalUpdated=' . $totalUpdated;
        }
        $this->callJob('schedule_list_profile_job', $params, 5);
    }

    public function importOrders($numberOfDays = 1) {
        $params ='numberOfDays=' . $numberOfDays;
        $this->callJob('import_orders_job', $params, 5);

    }




    public function completeSale($userId, $trackingNumber=0, $orderId=0, $shippingCarrierUsed=0) {

        $params = 'complete_sale';
        if(!empty($trackingNumber)) {
            $params.='&tracking_number=' . $trackingNumber;
        }

        if(!empty($orderId)) {
            $params.='&order_id=' . $orderId;
        }

        if(!empty($shippingCarrierUsed)) {
            $params.='&shipping_carrier_used=' . str_replace(' ', '%20', $shippingCarrierUsed);
        }

        $this->callJob($params, $userId, 5);
    }




    private function callJob($scope, $params, $delay=1, $url = null, $step = 0) {

        if($step > 2) {
            //die('Multiple redirects!!!!');
            return false;
        }

 		if($url == null) {
            $url = $this->getServiceUrl($scope, $params);
         }

        //die($url);

 		$ch = curl_init();
 		curl_setopt($ch, CURLOPT_URL, $url);
 		curl_setopt($ch, CURLOPT_TIMEOUT, $delay);
 		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
 		curl_setopt($ch, CURLOPT_HEADER, TRUE);
 		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
 		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 		curl_setopt($ch, CURLOPT_USERAGENT, 'eBay-channel-client');

		
 		$html = curl_exec($ch);
 		$status = curl_getinfo($ch);
 		curl_close($ch);
	
 		if($status['http_code']!=200){
 			if($status['http_code'] == 301 || $status['http_code'] == 302) {
 				list($header) = explode("\r\n\r\n", $html, 2);
 				$matches = array();
 				preg_match("/(Location:|URI:)[^(\n)]*/", $header, $matches);
 				$url = trim(str_replace($matches[1],"",$matches[0]));
 				$url_parsed = parse_url($url);

                return (isset($url_parsed))? $this->callJob($scope, $params, $delay, $url, $step + 1):'';
 			}
 		}
 		return $html;
 	}
	
	public function getCurrentUser($user_id) {
		return $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . $this->db->escape($user_id) . "'")->row;
	}
	
	public function getCurrentsJobs() {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "channel_ebay_cron` where TIME_TO_SEC(TIMEDIFF(now(), update_time))/60 >=`interval`")->rows;
	}
	
	public function updateCronJob($job_type) {
		$this->db->query("UPDATE `" . DB_PREFIX . "channel_ebay_cron` c SET `update_time` = now() WHERE  `job_type`='" . $this->db->escape($job_type) . "'");
	}

    public function getServiceUrl($scope, $params) {
        $this->load->model('ebay_channel/settings_dao');
        $settings = $this->model_ebay_channel_settings_dao->getSettingsByName(array("http_basic_autentification_username", "http_basic_autentification_password"));
        $url = HTTP_SERVER . '?route=ebay_channel/cron/' . $scope . '&' . $params;
        if(!empty($settings["http_basic_autentification_username"]) && !empty($settings["http_basic_autentification_password"])) {
            $url = str_replace("://", "://" . $settings["http_basic_autentification_username"] . ":" . $settings["http_basic_autentification_password"] . "@", HTTP_SERVER);
            $url .= '?route=ebay_channel/cron/' . $scope . '&' . $params;
        }
        return $url;
    }
	
	
}
?>