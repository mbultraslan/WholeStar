<?php
class ModelEbayChannelApiAccessRuleDao extends Model {


    public function add($account) {
        $this->load->model('ebay_channel/api');
        $this->load->model('ebay_channel/log_dao');
        $r = $this->model_ebay_channel_api->getApiAccessRulesCall($account, $account['default_site'])->getApiAccessRules();
        if(isset($r['errors'])) {
            foreach ($r['errors'] as $error) {
                $this->model_ebay_channel_log_dao->addEbayErrorLog('ApiAccessRulesCall', 0, $error, 7);
            }
        } else {
            $this->clear();
            foreach($r['api_access_rules'] as $api_access_rule){
                $this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_api_access_rule` SET "
                    . "   `call_name` = '" . $this->db->escape($api_access_rule['call_name'])
                    . "', `counts_toward_aggregate` = '" . $this->db->escape($api_access_rule['counts_toward_aggregate'])
                    . "', `daily_hard_limit` = '" . $this->db->escape($api_access_rule['daily_hard_limit'])
                    . "', `daily_soft_limit` = '" . $this->db->escape($api_access_rule['daily_soft_limit'])
                    . "', `daily_usage` = '" . $this->db->escape($api_access_rule['daily_usage'])
                    . "', `mod_time` = '" . $this->db->escape($api_access_rule['mod_time'])
                    . "', `period` = '" . $this->db->escape($api_access_rule['period'])
                    . "', `rule_current_status` = '" . $this->db->escape($api_access_rule['rule_current_status']) . "'");
            }
        }
    }

    public function clear() {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_api_access_rule`");
    }
}