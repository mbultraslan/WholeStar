<?php
/**
 * Created by PhpStorm.
 * User: Ion
 * Date: 5/20/2015
 * Time: 11:26 AM
 */
class DefaultEbayChannelTheme extends EbayChannelTheme {

    public function getName() {
        return "EbayChannel Default";
    }

    public function getVersion() {
        return "1.0";
    }

    public function getAuthor() {
        return "EbayChannel";
    }

    public function getCoverName() {
        return "default.png";
    }

    public function getProductExtraData($productId, $languageId) {
        $data = array();
//        $extra_tabs = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_tab WHERE language_id = '" . (int)$languageId . "' and   product_id = '" . (int)$productId . "'")->rows;
//        foreach($extra_tabs as $extra_tab){
//            $data['extra_tab_' . $extra_tab['tab_id']] = $extra_tab['text'];
//        }

        return $data;
    }

}