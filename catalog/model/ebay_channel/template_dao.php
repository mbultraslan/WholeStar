<?php
require_once('EbayChannelTheme.php');
class ModelEbayChannelTemplateDao extends Model {

    public function insertOrUpdate($template) {
        $dbTemplate = array();
        if(isset($template['id']) && !empty($template['id'])) {
            $dbTemplate = $this->getTemplateById($template['id']);
        }
        if(!empty($dbTemplate)) {
            $this->db->query("UPDATE `" . DB_PREFIX . "channel_ebay_templates` "
                . " SET name='". $this->db->escape($template['name']) ."'"
                . ", html='". $this->db->escape($template['html']) ."' WHERE id = '" . $this->db->escape($dbTemplate['id']) . "'");
        } else {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_templates` SET "
                . "   `name` = '" . $this->db->escape($template['name'])
                . "', `html` = '" . $this->db->escape($template['html']) . "'");
        }
    }

    public function getTemplates() {
        return $this->db->query("SELECT id, name FROM `" . DB_PREFIX . "channel_ebay_templates`")->rows;
    }

    public function deleteTepmlateById($id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "channel_ebay_templates` WHERE id = '" . $this->db->escape($id) . "'");
    }

    public function getColumns() {
        return array('te.name', 'te.id');
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

        $sQuery = "SELECT te.name, te.id FROM ". DB_PREFIX ."channel_ebay_templates te " . $sOrder . " " . $sLimit . " ";

        $result = $this->db->query($sQuery)->rows;


        $sQuery =  "SELECT count(*) as c FROM " . DB_PREFIX . "channel_ebay_templates";
        $resultTotal = $this->db->query($sQuery)->row['c'];

        $r = new stdClass();
        $r->result = $result;
        $r->count = $resultTotal;
        $r->filterCount = $resultTotal;

        return $r;
    }

    public function getTemplateById($id) {
        $template = $this->db->query("SELECT * FROM `" . DB_PREFIX . "channel_ebay_templates` WHERE id = '" . $this->db->escape($id) . "'")->row;
        if(!empty($template)) {
            $themesUrl = HTTP_SERVER . 'catalog/view/channels/theme/';
            $template['template_base_path'] = HTTP_SERVER;
            if(!empty($template['template_id'])) {
                $template['options']['template_base_path'] = $themesUrl . $template['template_id'];
            }
        }
        return $template;
    }

    public function getProductExtraData($productId, $languageId, $template) {
        if(!empty($template) && !empty($template['template_id'])) {
            $themesPath = DIR_APPLICATION . 'view/channels/theme/';
            $themesUrl = HTTP_SERVER . 'catalog/view/channels/theme/';

            $themePath = $themesPath . $template['template_id'];
            if(file_exists($themePath . '/theme.php')) {
                require_once($themePath . '/theme.php');
                $className = ucfirst($template['template_id'] . 'EbayChannelTheme');
                $themeObject = new $className($this->db);
                $data = $themeObject->getProductExtraData($productId, $languageId);
                $data['template_base_path'] = $themesUrl . $template['template_id'];
                return $data;

            }
        }
        return array();
    }

    public function installTemplate($path_name) {
        $themesPath = DIR_APPLICATION . 'view/channels/theme/';
        $themePath = $themesPath . $path_name;
        $count = $this->db->query("SELECT count(*) as cnt FROM ". DB_PREFIX ."channel_ebay_templates where template_id = '" .  $this->db->escape($$path_name) ."'")->row['cnt'];
        if($count < 1) {
            if (file_exists($themePath . '/template.html')) {
                require_once($themePath . '/theme.php');
                $className = ucfirst($path_name . 'EbayChannelTheme');
                $themeObject = new $className();

                $html = file_get_contents($themePath . '/template.html');
                $this->db->query("INSERT INTO `" . DB_PREFIX . "channel_ebay_templates` SET "
                    . "   `name` = '" . $this->db->escape($themeObject->getName())
                    . "', `template_id` = '" . $this->db->escape($path_name)
                    . "', `html` = '" . $this->db->escape($html) . "'");
            }
        }

        return false;
    }


    public function getAllTemplates() {

        $installedTemplates = $this->db->query("SELECT * FROM ". DB_PREFIX ."channel_ebay_templates")->rows;

        $templates = array();
        $themesPath = DIR_APPLICATION . 'view/channels/theme/';
        $themesUrl = HTTP_SERVER . 'catalog/view/channels/theme/';
        $templatesDirs = scandir($themesPath);
        foreach($installedTemplates as $dbTemplate) {
            $template = array('id'=> $dbTemplate['id'], 'name' => $dbTemplate['name'], 'author'=> 'Unknow Author', 'installed' => true);
            $template['cover'] =  HTTP_SERVER . 'view/image/channels/noimage.png';
            $templates[$dbTemplate['name']] = $template;

        }

        foreach ($templatesDirs as $key => $name) {
            if (!in_array($name, array(".",".."))) {
                $themePath = $themesPath . $name;
                require_once($themePath . '/theme.php');
                $className = ucfirst($name . 'EbayChannelTheme');
                $themeObject = new $className($this->db);
                $template = array('name' => 'Unknow Name', 'author'=> 'Unknow Author', 'installed' => false);
                $template['cover'] =  HTTP_SERVER . 'view/image/channels/noimage.png';
                $template['path_name'] =  $name;
                if(method_exists ($themeObject, 'getName')) {
                    $template['name'] =  $themeObject->getName();
                }

                if(method_exists ($themeObject, 'getVersion')) {
                    $template['version'] =  $themeObject->getVersion();
                }

                if(method_exists ($themeObject, 'getAuthor')) {
                    $template['author'] =  $themeObject->getAuthor();
                }

                if(method_exists ($themeObject, 'getCoverName')) {
                    if (file_exists($themePath . '/' . $themeObject->getCoverName())) {
                        $template['cover'] =  $themesUrl . $name . '/' . $themeObject->getCoverName();
                    }
                }
                foreach($installedTemplates as $dbTemplate) {
                    if($dbTemplate['name'] == $template['name']) {
                        $template['installed'] = true;
                        $template['id'] = $dbTemplate['id'];
                    }
                }

                $templates[$template['name']] = $template;

            }
        }



        return $templates;
    }


}
?>