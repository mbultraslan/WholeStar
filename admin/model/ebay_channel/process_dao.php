<?php
class ModelEbayChannelProcessDao extends Model {
	
	
	public function addProcess($name, $total) {
        $this->clearProcessByName($name);
		$this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_process` SET "
						. "   `name` = '" . $this->db->escape($name) 
						. "', `total` = '" . $this->db->escape($total)
						. "', `processed` = '0'"
				        . ", `start_time` = now()");
		$process_id = $this->db->getLastId();
		return $process_id;
	}

    public function addProcessByName($name) {
        $this->clearProcessByName($name);
        $this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_process` SET "
            . "   `name` = '" . $this->db->escape($name)
            . "', `total` = '0"
            . "', `processed` = '0'"
            . ", `start_time` = now()");
        $process_id = $this->db->getLastId();
        return $process_id;
    }

    public function updateProcessTotal($process_id, $total) {
        $this->db->query("UPDATE `" . DB_PREFIX . "channel_ebay_process` SET `total` = '" . $this->db->escape($total) . "' WHERE id = " . (int)$process_id);
    }
	
	public function updateProcess($process_id, $processed, $killUnfoundProcess=true) {
        $this->db->query("UPDATE `" . DB_PREFIX . "channel_ebay_process` SET `processed` = '" . $this->db->escape($processed) . "' WHERE id = " . (int)$process_id);
        if($killUnfoundProcess && $this->db->countAffected() < 1) {
            $this->load->model('ebay_channel/log_dao');
            $this->model_ebay_channel_log_dao->addWarningLog('DEBUG', "ABORT PROCESS: " . $process_id, 7);
            die();
        }
	}
	
	public function updateProcessed($process_id, $killUnfoundProcess=true) {
        $this->db->query("UPDATE `" . DB_PREFIX . "channel_ebay_process` SET `processed` = `processed` + 1  WHERE id = " . (int)$process_id);
        if($killUnfoundProcess && $this->db->countAffected() < 1) {
            die();
        }
	}
	
	public function deleteProcess($process_id, $abortProcess = false) {
        if($abortProcess) {
            $this->load->model('ebay_channel/log_dao');
            $process = $this->getProcessById($process_id);
            if(!empty($process)) {
                $this->model_ebay_channel_log_dao->addWarningLog($process['name'], "Job Aborted by User!", 7);
            }
        }
		$this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_process` WHERE id = " . (int)$process_id);
	}

    public function getProcesses($limit = 0) {
        $sql = "SELECT * FROM " . DB_PREFIX . "channel_ebay_process order by id desc ";
        if($limit > 0) {
            $sql .= " limit " . $this->db->escape($limit);
        }
        return $this->db->query($sql)->rows;
    }

    public function getProcessById($process_id) {
       return $this->db->query("SELECT * FROM `" . DB_PREFIX . "channel_ebay_process` WHERE id = " . (int)$process_id)->row;
    }

    public function clearProcessByName($name) {
        $rows = $this->db->query("SELECT * FROM `" . DB_PREFIX . "channel_ebay_process` WHERE lower(name) = lower('" . $this->db->escape($name) . "')")->rows;
        foreach($rows as $row) {
            $this->model_ebay_channel_log_dao->addWarningLog($name, "Job already exists or timeout!" , 7);
            $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_process` WHERE id = " . (int)$row['id']);
        }

    }

    public function getProcessesCount() {
        $sql = "SELECT count(*) as cnt FROM " . DB_PREFIX . "channel_ebay_process";
        return $this->db->query($sql)->row['cnt'];
    }

    public function deleteAll() {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_process` ");
    }


	
	
}
?>