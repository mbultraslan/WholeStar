<?php 
class ControllerModuleSocialLogin extends Controller {
	private $error = array(); 
	 
	public function index() { 
		$this->language->load('module/social_login');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('social', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_edit'] = $this->language->get('text_edit');
		
		
		$data['text_content_top'] = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_column_right'] = $this->language->get('text_column_right');
	
		$data['text_facebook_api_key'] = $this->language->get('text_facebook_api_key');		
		$data['text_facebook_secret_key'] = $this->language->get('text_facebook_secret_key');	
		$data['text_enable_facebook_login'] = $this->language->get('text_enable_facebook_login');
		$data['text_facebook_redirect_uri'] = $this->language->get('text_facebook_redirect_uri');
		
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
	
		$data['button_add_module'] = $this->language->get('button_add_module');
		$data['button_remove'] = $this->language->get('button_remove');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

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
			'href'      => $this->url->link('module/social_login', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('module/social_login', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');	
		
		if (isset($this->request->post['social_facebook_key'])) {
			$data['social_facebook_key'] = $this->request->post['social_facebook_key'];
		} else {
			$data['social_facebook_key'] = $this->config->get('social_facebook_key'); 
		}
				
		if (isset($this->request->post['social_facebook_secret'])) {
			$data['social_facebook_secret'] = $this->request->post['social_facebook_secret'];
		} else {
			$data['social_facebook_secret'] = $this->config->get('social_facebook_secret'); 
		} 
	
		if (isset($this->request->post['social_facebook_enable'])) {
			$data['social_facebook_enable'] = $this->request->post['social_facebook_enable'];
		} else {
			$data['social_facebook_enable'] = $this->config->get('social_facebook_enable'); 
		} 
		
		if (isset($this->request->post['social_facebook_redirect_uri'])) {
			$data['social_facebook_redirect_uri'] = $this->request->post['social_facebook_redirect_uri'];
		} else {
			$data['social_facebook_redirect_uri'] = $this->config->get('social_facebook_redirect_uri'); 
		} 
		if(empty($data['social_facebook_redirect_uri'])) {
		  $data['social_facebook_redirect_uri'] = HTTP_CATALOG . 'index.php?route=account/social_login/facebook_login';
		}
		
		
		if (isset($this->request->post['social_login_status'])) {
			$data['social_login_status'] = $this->request->post['social_login_status'];
		} else {
			$data['social_login_status'] = $this->config->get('social_login_status');
		}
		
		
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/social_login.tpl', $data));
		
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/social_login')) {
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