<?php
class ModelEbayChannelEbayDetailsDao extends Model {

    public function getDetails($names, $siteId) {
        $names = implode($names, "','");
        $details = $this->db->query("SELECT * from `" . DB_PREFIX . "channel_ebay_details` where `site_id`=" . (int)$siteId . " and `name` in ('" . $names . "')")->rows;

        $return = array();
        foreach($details as $detail) {
            $return[$detail['name']] = json_decode($detail['value'], true);
        }

        return $return;
    }




}
?>