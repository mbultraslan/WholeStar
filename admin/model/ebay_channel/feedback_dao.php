<?php
/**
 * Created by PhpStorm.
 * User: Ion
 * Date: 5/27/2015
 * Time: 1:52 PM
 */

class ModelEbayChannelFeedbackDao extends Model {

    public function addFeedBack($feedback) {
        if(!empty($feedback['feedback_id'])) {
            $count = $this->db->query("SELECT count(*) as cnt from " . DB_PREFIX . "channel_ebay_feedback where feedback_id=" . $this->db->escape($feedback['feedback_id']))->row['cnt'];
            if($count < 1) {
                $result = $this->db->query("SELECT order_id from `" . DB_PREFIX . "order` WHERE ebay_order_id='". $this->db->escape($feedback['transaction_id']) ."'")->row;
                $order_id = 0;
                if($result) {
                    $order_id = $result['order_id'];
                }
                $this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_feedback` SET "
                    . "   `feedback_id` = '" . $this->db->escape($feedback['feedback_id'])
                    . "', `user` = '" . $this->db->escape($feedback['user'])
                    . "', `role` = '" . $this->db->escape($feedback['role'])
                    . "', `score` = '" . $this->db->escape($feedback['score'])
                    . "', `comment_text` = '" . $this->db->escape($feedback['comment_text'])
                    . "', `comment_time` = '" . $this->db->escape($feedback['comment_time'])
                    . "', `comment_type` = '" . $this->db->escape($feedback['comment_type'])
                    . "', `item_id` = '" . $this->db->escape($feedback['item_id'])
                    . "', `item_price` = '" . $this->db->escape($feedback['item_price'])
                    . "', `item_price_currency` = '" . $this->db->escape($feedback['item_price_currency'])
                    . "', `item_title` = '" . $this->db->escape($feedback['item_title'])
                    . "', `order_line_item_id` = '" . $this->db->escape($feedback['order_line_item_id'])
                    . "', `transaction_id` = '" . $this->db->escape($feedback['transaction_id'])
                    . "', `order_id` = '" . $this->db->escape($order_id)
                    . "'");

                return true;
            }
        }
       return false;
    }

    public function deleteAll() {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_feedback`");
    }

    public function getColumns() {
        return array('el.comment_type', 'el.comment_text', 'el.item_title', 'el.item_id', 'el.user', 'el.score', 'el.item_price', 'el.item_price_currency', 'el.comment_time');
    }

    public function getList($data) {

        $aColumns = $this->getColumns();

        $sLimit = '';
        if (isset($data['iDisplayStart']) && $data['iDisplayLength'] != '-1') {
            $sLimit .= " LIMIT " . intval($data['iDisplayStart']) . ", " . intval($data['iDisplayLength']);
        }

        /*
         * Ordering
        */
        $sOrder = "  ORDER BY el.comment_time desc ";



        $sQuery = "SELECT el.user, el.score, el.comment_text, el.comment_time, el.comment_type, el.item_id,
                          el.item_price, el.item_price_currency, el.item_title, el.order_id
						FROM ". DB_PREFIX ."channel_ebay_feedback el"
            . " " . $sOrder . " " . $sLimit . " ";

        $result = $this->db->query($sQuery)->rows;


        $sQuery =  "SELECT count(*) as c FROM " . DB_PREFIX . "channel_ebay_feedback";
        $resultTotal = $this->db->query($sQuery)->row['c'];

        $r = new stdClass();
        $r->result = $result;
        $r->count = $resultTotal;
        $r->filterCount = $resultTotal;

        return $r;
    }
}
?>