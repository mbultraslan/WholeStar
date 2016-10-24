<?php
class ModelEbayChannelLogDao extends Model {

    public function addLog($data, $clearDays) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_log` SET "
            . "   `type` = '" . $this->db->escape($data['type'])
            . "', `ebay_product_id` = '" . $this->db->escape($data['ebay_product_id'])
            . "', `message` = '" . $this->db->escape($data['message'])
            . "', `severity_code` = '" . $this->db->escape($data['severity_code'])
            . "', `code` = '" . $this->db->escape($data['code'])
            . "', `create_time` = now()");
        $this->clearLog($clearDays);
    }

    public function clearLog($daysCount) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_log` WHERE create_time < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL " . $this->db->escape($daysCount) . " DAY))");
    }

    public function clearAll() {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_log`");
    }

    public function addEbayErrorLog($type, $p2eId, $error,  $clearDays = 7) {
        $data = array('type' => $type, 'ebay_product_id'=>$p2eId, 'severity_code'=> $error['severity_code'], 'code' => $error['code']);
        $data['message'] = $error['long_message'];
        foreach ($error['parameters'] as $parameter) {
            $data['message'] .= "\n" . $parameter['value'];
        }
        $this->addLog($data,  $clearDays);
    }

    public function addEbaySuccessLog($type, $message, $clearDays = 7) {
        $data = array('type' => $type, 'ebay_product_id'=>0, 'severity_code'=> 'Success', 'code' => 200, 'message' => $message);
        $this->addLog($data,  $clearDays);
    }

    public function addWarningLog($type, $message) {
        $data = array('type' => $type, 'ebay_product_id'=>0, 'severity_code'=> 'Warning', 'code' => 300, 'message' => $message);
        $this->addLog($data,  7);
    }

    public function addSuccessLog($type, $message) {
        $data = array('type' => $type, 'ebay_product_id'=>0, 'severity_code'=> 'Success', 'code' => 200, 'message' => $message);
        $this->addLog($data,  7);
    }

    public function addErrorLog($type, $message) {
        $data = array('type' => $type, 'ebay_product_id'=>0, 'severity_code'=> 'Error', 'code' => 500, 'message' => $message);
        $this->addLog($data,  7);
    }

    public function debug($object, $settings) {
        if($settings['general_debug_enable']) {
            $this->addSuccessLog('DEBUG', json_encode($object, JSON_PRETTY_PRINT));
        }
    }


    public function getColumns() {
        return array('el.type', 'el.code', 'el.severity_code', 'el.message', 'el.create_time', 'ep.ebay_id');
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
        $sOrder = "";
        if (isset($data['iSortCol_0'])) {
            $sOrder = " ORDER BY  ";
            for ($i = 0; $i < intval($data['iSortingCols']); $i++) {
                if ($data['bSortable_' . intval($data['iSortCol_' . $i])] == "true") {
                    $sOrder .= " " . $aColumns[intval($data['iSortCol_' . $i])] . " " .
                        ($data['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == " ORDER BY") {
                $sOrder = "";
            }
        }

        $sQuery = "SELECT el.type, el.code, el.severity_code, el.message, el.create_time, ep.ebay_id
						FROM ". DB_PREFIX ."channel_ebay_log el
						LEFT JOIN " . DB_PREFIX . "channel_ebay_product ep on ep.id = el.ebay_product_id"
            . " " . $sOrder . " " . $sLimit . " ";

        $result = $this->db->query($sQuery)->rows;


        $sQuery =  "SELECT count(*) as c FROM " . DB_PREFIX . "channel_ebay_log";
        $resultTotal = $this->db->query($sQuery)->row['c'];

        $r = new stdClass();
        $r->result = $result;
        $r->count = $resultTotal;
        $r->filterCount = $resultTotal;

        return $r;
    }


}
?>