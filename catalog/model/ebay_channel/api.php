<?php require_once('GeteBayOfficialTimeCall.php') ?>
<?php require_once('GetTokenStatusCall.php') ?>
<?php require_once('GetUserCall.php') ?>
<?php require_once('GetStoreCall.php') ?>
<?php require_once('GetCategoriesCall.php') ?>
<?php require_once('GeteBayDetailsCall.php') ?>
<?php require_once('GetCategoryFeaturesCall.php') ?>
<?php require_once('GetCategorySpecificsCall.php') ?>
<?php require_once('VerifyAddFixedPriceItemCall.php') ?>
<?php require_once('VerifyAddItemCall.php') ?>
<?php require_once('AddItemCall.php') ?>
<?php require_once('ReviseItemCall.php') ?>
<?php require_once('RelistItemCall.php') ?>
<?php require_once('AddFixedPriceItemCall.php') ?>
<?php require_once('GetFeedbackCall.php') ?>
<?php require_once('GetSellerDashboardCall.php') ?>
<?php require_once('GetMyeBaySellingCall.php') ?>
<?php require_once('EndItemCall.php') ?>
<?php require_once('RelistFixedPriceItemCall.php') ?>
<?php require_once('ReviseFixedPriceItemCall.php') ?>
<?php require_once('ReviseInventoryStatusCall.php') ?>
<?php require_once('GetItemCall.php') ?>
<?php require_once('GetOrdersCall.php') ?>
<?php require_once('GetSellerListCall.php') ?>
<?php require_once('SetNotificationPreferencesCall.php') ?>
<?php require_once('GetNotificationPreferencesCall.php') ?>
<?php require_once('EditFixedPriceItemVariationCall.php') ?>
<?php require_once('GetDescriptionTemplatesCall.php') ?>
<?php require_once('CompleteSaleCall.php') ?>
<?php require_once('GetApiAccessRulesCall.php') ?>
<?php
class ModelEbayChannelApi extends Model {
	
	private $compatabilityLevel = 825;
	
	private function getServerUrl($data) {
		if($data['listing_mode'] == 'sandbox') {
			return "https://api.sandbox.ebay.com/ws/api.dll";
		} else {
			return "https://api.ebay.com/ws/api.dll";
		}
	}
	
	
	public function getEbayOfficialTime($data) {
		$call = new GeteBayOfficialTimeCall($data['token'], 
											$data['dev_id'], 
											$data['app_id'], 
											$data['cert_id'],	
											$this->getServerUrl($data), 
											$this->compatabilityLevel, 
											$data['default_site']);
		return $call->getTime();
	}
	
	public function getTokenStatus($data) {
		$call = new GetTokenStatusCall($data['token'], 
											$data['dev_id'], 
											$data['app_id'], 
											$data['cert_id'],	
											$this->getServerUrl($data), 
											$this->compatabilityLevel, 
											$data['default_site']);
		return $call->getTokenStatus();
	}
	
	public function getUser($data) {
		$call = new GetUserCall($data['token'], 
											$data['dev_id'], 
											$data['app_id'], 
											$data['cert_id'],	
											$this->getServerUrl($data), 
											$this->compatabilityLevel, 
											$data['default_site']);
		return $call->getUser();
	}
	
	public function getStore($account, $query) {
		$call = new GetStoreCall($account['token'], 
											$account['dev_id'], 
											$account['app_id'], 
											$account['cert_id'],	
											$this->getServerUrl($account), 
											$this->compatabilityLevel, 
											$account['default_site']);
		return $call->getStore($query);
	}
	
	public function getCategoriesCall($account, $site) {
		return new GetCategoriesCall($account['token'], 
											$account['dev_id'], 
											$account['app_id'], 
											$account['cert_id'],	
											$this->getServerUrl($account), 
											$this->compatabilityLevel, 
											$site);
	}
	
	public function geteBayDetailsCall($account, $site) {
		return new GeteBayDetailsCall($account['token'], 
											$account['dev_id'], 
											$account['app_id'], 
											$account['cert_id'],	
											$this->getServerUrl($account), 
											$this->compatabilityLevel, 
											$site);
	}
	
	public function getCategoryFeaturesCall($account, $site) {
		return new GetCategoryFeaturesCall($account['token'], 
											$account['dev_id'], 
											$account['app_id'], 
											$account['cert_id'],	
											$this->getServerUrl($account), 
											$this->compatabilityLevel, 
											$site);
	}
	
	public function getCategorySpecificsCall($account, $site) {
		return new GetCategorySpecificsCall($account['token'], 
											$account['dev_id'], 
											$account['app_id'], 
											$account['cert_id'],	
											$this->getServerUrl($account), 
											$this->compatabilityLevel, 
											$site);
	}
	
	public function getVerifyAddFixedPriceItemCall($account, $site) {
		return new VerifyAddFixedPriceItemCall($account['token'], 
											$account['dev_id'], 
											$account['app_id'], 
											$account['cert_id'],	
											$this->getServerUrl($account), 
											$this->compatabilityLevel, 
											$site);
	}
	
	public function getVerifyAddItemCall($account, $site) {
		return new VerifyAddItemCall($account['token'], 
											$account['dev_id'], 
											$account['app_id'], 
											$account['cert_id'],	
											$this->getServerUrl($account), 
											$this->compatabilityLevel, 
											$site);
	}
	
	public function getAddFixedPriceItemCall($account, $site) {
		return new AddFixedPriceItemCall($account['token'], 
											$account['dev_id'], 
											$account['app_id'], 
											$account['cert_id'],	
											$this->getServerUrl($account), 
											$this->compatabilityLevel, 
											$site);
	}
	
	public function getFeedbackCall($account, $site) {
		return new GetFeedbackCall($account['token'], 
											$account['dev_id'], 
											$account['app_id'], 
											$account['cert_id'],	
											$this->getServerUrl($account), 
											$this->compatabilityLevel, 
											$site);
	}
	
	public function getSellerDashboardCall($account, $site) {
		return new GetSellerDashboardCall($account['token'], 
											$account['dev_id'], 
											$account['app_id'], 
											$account['cert_id'],	
											$this->getServerUrl($account), 
											$this->compatabilityLevel, 
											$site);
	}
	
	
	public function getMyeBaySellingCall($account, $site) {
		return new GetMyeBaySellingCall($account['token'], 
											$account['dev_id'], 
											$account['app_id'], 
											$account['cert_id'],	
											$this->getServerUrl($account), 
											$this->compatabilityLevel, 
											$site);
	}
	
	public function getEndItemCall($account, $site) {
		return new EndItemCall($account['token'],
				$account['dev_id'],
				$account['app_id'],
				$account['cert_id'],
				$this->getServerUrl($account),
				$this->compatabilityLevel,
				$site);
	}
	
	public function getRelistFixedPriceItemCall($account, $site) {
		return new RelistFixedPriceItemCall($account['token'],
				$account['dev_id'],
				$account['app_id'],
				$account['cert_id'],
				$this->getServerUrl($account),
				$this->compatabilityLevel,
				$site);
	}
	
	public function getReviseFixedPriceItemCall($account, $site) {
		return new ReviseFixedPriceItemCall($account['token'],
				$account['dev_id'],
				$account['app_id'],
				$account['cert_id'],
				$this->getServerUrl($account),
				$this->compatabilityLevel,
				$site);
	}
	
	public function getReviseInventoryStatusCall($account, $site) {
		return new ReviseInventoryStatusCall($account['token'],
				$account['dev_id'],
				$account['app_id'],
				$account['cert_id'],
				$this->getServerUrl($account),
				$this->compatabilityLevel,
				$site);
	}
	
	public function getGetItemCall($account, $site) {
		return new GetItemCall($account['token'],
				$account['dev_id'],
				$account['app_id'],
				$account['cert_id'],
				$this->getServerUrl($account),
				$this->compatabilityLevel,
				$site);
	}
	
	public function getGetOrdersCall($account, $site) {
		return new GetOrdersCall($account['token'],
				$account['dev_id'],
				$account['app_id'],
				$account['cert_id'],
				$this->getServerUrl($account),
				$this->compatabilityLevel,
				$site);
	}
	
	public function getGetSellerListCall($account, $site) {
		return new GetSellerListCall($account['token'],
				$account['dev_id'],
				$account['app_id'],
				$account['cert_id'],
				$this->getServerUrl($account),
				$this->compatabilityLevel,
				$site);
	}
	
	public function getAddItemCall($account, $site) {
		return new AddItemCall($account['token'],
				$account['dev_id'],
				$account['app_id'],
				$account['cert_id'],
				$this->getServerUrl($account),
				$this->compatabilityLevel,
				$site);
	}
	
	public function getReviseItemCall($account, $site) {
		return new ReviseItemCall($account['token'],
				$account['dev_id'],
				$account['app_id'],
				$account['cert_id'],
				$this->getServerUrl($account),
				$this->compatabilityLevel,
				$site);
	}
	
	public function getRelistItemCall($account, $site) {
		return new RelistItemCall($account['token'],
				$account['dev_id'],
				$account['app_id'],
				$account['cert_id'],
				$this->getServerUrl($account),
				$this->compatabilityLevel,
				$site);
	}
	
	public function getEditFixedPriceItemVariationCall($account, $site) {
		return new EditFixedPriceItemVariationCall($account['token'],
				$account['dev_id'],
				$account['app_id'],
				$account['cert_id'],
				$this->getServerUrl($account),
				$this->compatabilityLevel,
				$site);
	}
	
	public function getSetNotificationPreferencesCall($account, $site) {
		return new SetNotificationPreferencesCall($account['token'],
				$account['dev_id'],
				$account['app_id'],
				$account['cert_id'],
				$this->getServerUrl($account),
				$this->compatabilityLevel,
				$site);
	}
	
	public function getGetNotificationPreferencesCall($account, $site) {
		return new GetNotificationPreferencesCall($account['token'],
				$account['dev_id'],
				$account['app_id'],
				$account['cert_id'],
				$this->getServerUrl($account),
				$this->compatabilityLevel,
				$site);
	}
	
	public function getGetStoreCall($account, $site) {
		return new GetStoreCall($account['token'],
							$account['dev_id'],
							$account['app_id'],
							$account['cert_id'],
							$this->getServerUrl($account),
							$this->compatabilityLevel,
							$site);
	}

    public function getDescriptionTemplatesCall($account, $site) {
        return new GetDescriptionTemplatesCall($account['token'],
            $account['dev_id'],
            $account['app_id'],
            $account['cert_id'],
            $this->getServerUrl($account),
            $this->compatabilityLevel,
            $site);
    }

    public function getCompleteSaleCall($account, $site) {
        return new CompleteSaleCall($account['token'],
            $account['dev_id'],
            $account['app_id'],
            $account['cert_id'],
            $this->getServerUrl($account),
            $this->compatabilityLevel,
            $site);
    }

    public function getApiAccessRulesCall($account, $site) {
        return new GetApiAccessRulesCall($account['token'],
            $account['dev_id'],
            $account['app_id'],
            $account['cert_id'],
            $this->getServerUrl($account),
            $this->compatabilityLevel,
            $site);
    }
	
	
}
?>