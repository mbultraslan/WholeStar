<?php  
class ControllerModuleSocialLogin extends Controller {
	public function index() {
		$this->language->load('module/social_login');
		$this->load->model('tool/social_login');
		
		//echo 'dasdadadadasd'; die;
		
    	$data['heading_title'] = $this->language->get('heading_title_social');
    	
		 //Social Media login code
		 if($this->config->get('social_facebook_enable')==1){
		   $data['facebook_login_url'] =$this->model_tool_social_login->getFacebookLoginUrl();
		 } else {
		   $data['facebook_login_url']='';
		 }
		 
		 
		$data['text_account'] = $this->language->get('text_account');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_password'] = $this->language->get('text_password');
		$data['text_address'] = $this->language->get('text_address');
				
		$data['logged'] = $this->customer->isLogged();
		$data['first_name'] = $this->customer->getFirstName();
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['edit'] = $this->url->link('account/edit', '', 'SSL');
		$data['password'] = $this->url->link('account/password', '', 'SSL');
		$data['address'] = $this->url->link('account/address', '', 'SSL');
		
		
		//echo '<pre>'; print_r($data); die;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/social_login.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/social_login.tpl', $data);
		} else {
			return $this->load->view('default/template/module/social_login.tpl', $data);
		}
		
		
	
	}
}
?>