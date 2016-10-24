<?php
class ControllerModuleNewslettersubscribe extends Controller {
	private $error = array(); 
	
	public function index() {   
	
		$this->load->language('module/newslettersubscribe');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			
			$this->model_setting_setting->editSetting('newslettersubscribe', $this->request->post);		
					 
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_edit'] = $this->language->get('text_edit');
		
		$data['text_no'] = $this->language->get('text_no');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_content_top'] = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_column_right'] = $this->language->get('text_column_right');
		
		$data['entry_admin'] = $this->language->get('entry_admin');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_unsubscribe'] = $this->language->get('entry_unsubscribe');
		$data['entry_thickbox'] = $this->language->get('entry_thickbox');
		$data['entry_registered'] = $this->language->get('entry_registered');	
		$data['entry_mail'] = $this->language->get('entry_mail');
		$data['entry_options'] = $this->language->get('entry_options');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_module'] = $this->language->get('button_add_module');
		$data['button_remove'] = $this->language->get('button_remove');
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/newslettersubscribe', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('module/newslettersubscribe', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		
		if (isset($this->request->post['newslettersubscribe_unsubscribe'])) {
			$data['newslettersubscribe_unsubscribe'] = $this->request->post['newslettersubscribe_unsubscribe'];
		} else {
			$data['newslettersubscribe_unsubscribe'] = $this->config->get('newslettersubscribe_unsubscribe');
		}
		
		if (isset($this->request->post['newslettersubscribe_registered'])) {
			$data['newslettersubscribe_registered'] = $this->request->post['newslettersubscribe_registered'];
		} else {
			$data['newslettersubscribe_registered'] = $this->config->get('newslettersubscribe_registered');
		}
		
		if (isset($this->request->post['newslettersubscribe_mail_status'])) {
			$data['newslettersubscribe_mail_status'] = $this->request->post['newslettersubscribe_mail_status'];
		} else {
			$data['newslettersubscribe_mail_status'] = $this->config->get('newslettersubscribe_mail_status');
		}
		
		if (isset($this->request->post['newslettersubscribe_thickbox'])) {
			$data['newslettersubscribe_thickbox'] = $this->request->post['newslettersubscribe_thickbox'];
		} else {
			$data['newslettersubscribe_thickbox'] = $this->config->get('newslettersubscribe_thickbox');
		}
		if (isset($this->request->post['newslettersubscribe_status'])) {
			$data['newslettersubscribe_status'] = $this->request->post['newslettersubscribe_status'];
		} else {
			$data['newslettersubscribe_status'] = $this->config->get('newslettersubscribe_status');
		}
		
				
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/newslettersubscribe.tpl', $data));
		
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/newslettersubscribe')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>