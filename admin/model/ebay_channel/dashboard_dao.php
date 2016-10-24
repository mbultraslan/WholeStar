<?php
class ModelEbayChannelDashboardDao extends Model {
	
	public function addEbayFeedback($data) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_feedback_summary`");
		
		$a = array('positive', 'neutral', 'negative');
		foreach ($a as $v) {
			if(isset($data[$v])) {
				foreach ($data[$v] as $feedback) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_feedback_summary` SET "
						. "   `name` = '" . $this->db->escape($v) 
						. "', `period_in_days` = '" . $this->db->escape($feedback['period_in_days']) 
						. "', `count` = '" . $this->db->escape($feedback['count']) 
						. "'"
						);
				}
			}
		}
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_seller_rating_summary`");
		if(isset($data['seller_rating_summary'])) {
			foreach ($data['seller_rating_summary'] as $seller_rating_summary) {
				$period = $seller_rating_summary['period'];
				foreach ($seller_rating_summary['details'] as $details) {
					$v = $details['rating_detail'];
					if($v == 'Communication') {
						$v = 'Communication';
					} else if($v == 'ItemAsDescribed') {
						$v = 'Item as described';
					} else if($v == 'ShippingAndHandlingCharges') {
						$v = 'Shipping charges';
					} else if($v == 'ShippingTime') {
						$v = 'Shipping time';
					}
					$this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_seller_rating_summary` SET "
						. "   `period` = '" . $this->db->escape($period) 
						. "', `rating_detail` = '" . $this->db->escape($v)
						. "', `rating_count` = '" . $this->db->escape($details['rating_count'])
						. "', `rating` = '" . $this->db->escape($details['rating']) 
						. "'"
						);
				}
			}
		}
	}
	
	
	
	
	
	public function addSellingSummary($data) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_selling_summary`");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_selling_summary` SET "
						. "   `active_auction_count` = '" . $this->db->escape($data['active_auction_count']) 
						. "', `amount_limit_remaining` = '" . $this->db->escape($data['amount_limit_remaining'])
						. "', `amount_limit_remaining_currency` = '" . $this->db->escape($data['amount_limit_remaining_currency'])
						. "', `auction_bid_count` = '" . $this->db->escape($data['auction_bid_count'])
						. "', `auction_selling_count` = '" . $this->db->escape($data['auction_selling_count'])
						. "', `classified_ad_count` = '" . $this->db->escape($data['classified_ad_count'])
						. "', `classified_ad_offer_count` = '" . $this->db->escape($data['classified_ad_offer_count'])
						. "', `quantity_limit_remaining` = '" . $this->db->escape($data['quantity_limit_remaining'])
						. "', `sold_duration_in_days` = '" . $this->db->escape($data['sold_duration_in_days'])
						. "', `total_auction_selling_value` = '" . $this->db->escape($data['total_auction_selling_value'])
						. "', `total_auction_selling_value_currency` = '" . $this->db->escape($data['total_auction_selling_value_currency'])
						. "', `total_lead_count` = '" . $this->db->escape($data['total_lead_count'])
						. "', `total_listings_with_leads` = '" . $this->db->escape($data['total_listings_with_leads'])
						. "', `total_sold_count` = '" . $this->db->escape($data['total_sold_count'])
						. "', `total_sold_value` = '" . $this->db->escape($data['total_sold_value'])
						. "', `total_sold_value_currency` = '" . $this->db->escape($data['total_sold_value_currency'])
						. "'"
						);
		
	}
	
	public function addEbaySellerDashboard($data) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_seller_dashboard`");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_seller_dashboard_alert`");
		
		if(!empty($data['buyerSatisfaction'])){
			$this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_seller_dashboard` SET "
						. "   `name` = '" . $this->db->escape('buyerSatisfaction') 
						. "', `status` = '" . $this->db->escape($data['buyerSatisfaction']['status'])
						. "'"
						);
			
			$sdid = $this->db->getLastId();	
			if(!empty($data['buyerSatisfaction']['alerts'])) {
				foreach ($data['buyerSatisfaction']['alerts'] as $alert) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_seller_dashboard_alert` SET "
							. "   `seller_dashboard_id` = '" . $this->db->escape($sdid) 
							. "', `alert_severity` = '" . $this->db->escape($alert['severity'])
							. "', `alert_text` = '" . $this->db->escape($alert['text'])
							. "'"
						);
				}
			}
		}
		
		if(!empty($data['sellerAccount'])){
			$this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_seller_dashboard` SET "
						. "   `name` = '" . $this->db->escape('sellerAccount') 
						. "', `status` = '" . $this->db->escape($data['sellerAccount']['status'])
						. "'"
						);
			
			$sdid = $this->db->getLastId();	
			if(!empty($data['sellerAccount']['alerts'])) {
				foreach ($data['sellerAccount']['alerts'] as $alert) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_seller_dashboard_alert` SET "
							. "   `seller_dashboard_id` = '" . $this->db->escape($sdid) 
							. "', `alert_severity` = '" . $this->db->escape($alert['severity'])
							. "', `alert_text` = '" . $this->db->escape($alert['text'])
							. "'"
						);
				}
			}
		}
		
		if(!empty($data['powerSellerStatus'])){
			$this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_seller_dashboard` SET "
						. "   `name` = '" . $this->db->escape('powerSellerStatus') 
						. "', `level` = '" . $this->db->escape($data['powerSellerStatus']['level'])
						. "'"
						);
			
			$sdid = $this->db->getLastId();	
			if(!empty($data['powerSellerStatus']['alerts'])) {
				foreach ($data['powerSellerStatus']['alerts'] as $alert) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_seller_dashboard_alert` SET "
							. "   `seller_dashboard_id` = '" . $this->db->escape($sdid) 
							. "', `alert_severity` = '" . $this->db->escape($alert['severity'])
							. "', `alert_text` = '" . $this->db->escape($alert['text'])
							. "'"
						);
				}
			}
		}
		
		
		if(!empty($data['searchStanding'])){
			$this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_seller_dashboard` SET "
						. "   `name` = '" . $this->db->escape('searchStanding') 
						. "', `status` = '" . $this->db->escape($data['sellerFeeDiscount']['status'])
						. "'"
						);
		}
		
		if(!empty($data['sellerFeeDiscount'])){
			$this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_seller_dashboard` SET "
						. "   `name` = '" . $this->db->escape('sellerFeeDiscount') 
						. "', `percent` = '" . $this->db->escape($data['sellerFeeDiscount']['percent'])
						. "'"
						);
		}
		
		
	}
	
	
	public function getSellingInfo() {
		$orders = $this->db->query("SELECT DATE(date_added) as d, count(ebay_order_id) as cnt FROM `" . DB_PREFIX . "order` WHERE ebay_order_id is not null and date_added BETWEEN NOW() - INTERVAL 30 DAY AND NOW() group by d order by d asc")->rows;
		$r = array();
		$r[] = array("Last 30 Days", "Sales");
		foreach ($orders as $order) {
			$r[] = array($order['d'], (int)$order['cnt']);
		}
		
		if(empty($orders)) {
			$r[] = array("", 0);
		}
		
		return $r; 
	}
	
	
	public function getDashboard() {
		$feedback_summary = $this->db->query("select * from `" . DB_PREFIX . "channel_ebay_feedback_summary` ")->rows;
		
		$seller_dashboard = $this->db->query("select * from `" . DB_PREFIX . "channel_ebay_seller_dashboard` ")->rows;
		
		$channel_ebay_selling_summary = $this->db->query("select * from `" . DB_PREFIX . "channel_ebay_selling_summary` limit 1")->row;
		
		
		
		$seller_rating_summary = $this->db->query("select id, rating_detail,  GROUP_CONCAT(    
         '{\"',  period,'\" : { \"rating_count\":' , rating_count,', \"rating\" :', rating, '}', '}') as json from `" . DB_PREFIX . "channel_ebay_seller_rating_summary`  group by rating_detail order by rating_detail desc")->rows;
		
		foreach ($seller_rating_summary as $key => $summ) {
			$a = json_decode('[' . $summ['json'] . ']', true);
			foreach ($a as $v) {
				if(isset($v['FiftyTwoWeeks'])) {
					$seller_rating_summary[$key]['FiftyTwoWeeks'] = $v['FiftyTwoWeeks'];
				} else if(isset($v['ThirtyDays'])) {
					$seller_rating_summary[$key]['ThirtyDays'] = $v['ThirtyDays'];
				}
			}
		}
		
		return array(
			'feedback_summary'=>$feedback_summary,
			'seller_dashboard'=>$seller_dashboard,
			'seller_rating_summary'=>$seller_rating_summary,
			'selling_summary'=>$channel_ebay_selling_summary
		);
	}
}
?>