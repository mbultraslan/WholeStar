<?php
/**
 * Created by PhpStorm.
 * User: Ion
 * Date: 6/3/2015
 * Time: 5:00 PM
 */

class ModelEbayChannelScheduleListProfileDao extends Model {

    public function add($data) {
        $this->removeByProdutId($data['product_id']);
        $this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_schedule_list_profile` SET "
            . "   `list_profile_id` = '" . $this->db->escape($data['list_profile_id'])
            . "', `schedule_datetime` = '" . $this->db->escape($data['schedule_datetime'])
            . "', `product_id` = '" . $this->db->escape($data['product_id'])
            . "'");

    }

    public function removeByProdutId($productId) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_schedule_list_profile` WHERE product_id = '" . $this->db->escape($productId) . "'");
    }

    public function removeByListingProfileId($listProfileId) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_schedule_list_profile` WHERE list_profile_id = '" . $this->db->escape($listProfileId) . "'");
    }

    public function listByListingProfileId($listProfileId) {
        $this->db->query("SELECT * FROM `" . DB_PREFIX . "channel_ebay_schedule_list_profile` WHERE list_profile_id = '" . $this->db->escape($listProfileId) . "'");
    }

    public function getAllLessNowTotal() {
        return $this->db->query("SELECT count(*) as cnt FROM `" . DB_PREFIX . "channel_ebay_schedule_list_profile` WHERE schedule_datetime < now()")->row['cnt'];
    }

    public function getAllLessNow($offset, $itemsPerPage) {
        return $this->db->query("SELECT * FROM `" . DB_PREFIX . "channel_ebay_schedule_list_profile` WHERE schedule_datetime < now() LIMIT " . $offset . ", " . $itemsPerPage)->rows;
    }


}