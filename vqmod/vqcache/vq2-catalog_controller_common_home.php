<?php

class ControllerCommonHome extends Controller {

    public function index() {
        $this->document->setTitle($this->config->get('config_meta_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));

				if( NULL != ( $smk_settings = $this->config->get('smp_meta_stores' ) ) ) {
					if( ! empty( $smk_settings[$this->config->get('config_store_id')]['title'][$this->config->get('config_language_id')] ) ) {
						$this->document->setTitle( $smk_settings[$this->config->get('config_store_id')]['title'][$this->config->get('config_language_id')] );
					}
				
					if( ! empty( $smk_settings[$this->config->get('config_store_id')]['description'][$this->config->get('config_language_id')] ) ) {
						$this->document->setDescription( $smk_settings[$this->config->get('config_store_id')]['description'][$this->config->get('config_language_id')] );
					}
				
					if( ! empty( $smk_settings[$this->config->get('config_store_id')]['keywords'][$this->config->get('config_language_id')] ) ) {
						$this->document->setKeywords( $smk_settings[$this->config->get('config_store_id')]['keywords'][$this->config->get('config_language_id')] );
					}
				}
			

        if (isset($this->request->get['route'])) {
            $this->document->addLink(HTTP_SERVER, 'canonical');
        }

        //echo '<pre>'; print_r($data['stnimages']); die;

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        //$data['stn_popup'] = $this->load->controller('information/stn');
		
		if (!isset($this->request->cookie['homepopup']) || $this->request->cookie['homepopup'] != 'homepopup') {
            $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `code` = 'stnimage'");
            if ($result->num_rows) {
                $this->load->model('module/stnimage');
                $data['stn_popup'] = $this->model_module_stnimage->getstnimage();
			ini_set('session.homepopup', substr($_SERVER['SERVER_NAME'],strpos($_SERVER['SERVER_NAME'],"."),100));
				  setcookie('homepopup', 'homepopup', time() + 60 * 60 * 24 * 30, '/');
            } else {
                $data['stn_popup'] = 'N\A';
            }
        } else {
            $data['stn_popup'] = 'N\A';
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/home.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/home.tpl', $data));
        }
    }

}
