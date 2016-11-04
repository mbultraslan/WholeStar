<?php

class Controllermodulestnimage extends Controller {

    private $error = array();

    public function index() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "stnimage_image` (
  `stn_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `banner_width` varchar(255) NOT NULL,
  PRIMARY KEY (`stn_image_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;");
        $this->load->language('module/stnimage');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('setting/setting');
        
        $this->load->model('extension/module');

        $this->load->model('module/stnimage');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            //echo '<pre>'; print_r($this->request->post); die;
            $this->model_module_stnimage->addBanner($this->request->post['stnimage']);
            /* if (!isset($this->request->get['module_id'])) {
              $this->model_extension_module->addModule('stnimage', $this->request->post);
              } else {
              $this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
              } */

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_link'] = $this->language->get('entry_link');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['button_banner_add'] = $this->language->get('button_banner_add');
        $data['entry_width'] = $this->language->get('entry_width');
        $this->load->model('tool/image');
        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['width'])) {
            $data['error_width'] = $this->error['width'];
        } else {
            $data['error_width'] = '';
        }

        if (isset($this->error['height'])) {
            $data['error_height'] = $this->error['height'];
        } else {
            $data['error_height'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
        );

        if (!isset($this->request->get['module_id'])) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('module/stnimage', 'token=' . $this->session->data['token'], 'SSL')
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('module/stnimage', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
            );
        }

        if (!isset($this->request->get['module_id'])) {
            $data['action'] = $this->url->link('module/stnimage', 'token=' . $this->session->data['token'], 'SSL');
        } else {
            $data['action'] = $this->url->link('module/stnimage', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
        }

        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
        }

        //$this->load->model('design/stnimage');
        //$data['stnimages'] = $this->model_design_stnimage->getstnimages();



        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($module_info)) {
            $data['status'] = $module_info['status'];
        } else {
            $data['status'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        //$this->load->model('design/banner');
        $data['stnimages'] = $this->model_module_stnimage->getstnimage();

        $this->response->setOutput($this->load->view('module/stnimage.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'module/stnimage')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

}
