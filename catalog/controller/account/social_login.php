<?php 
class ControllerAccountSocialLogin extends Controller {
	private $error = array();
	

   public function facebook_login(){
     $this->load->model('tool/social_login');
	 $this->load->model('account/customer');
	 $this->load->model('account/social_login');
	
	 $facebook_user_data =$this->model_tool_social_login->checkfacebookLogin();
	
	 if(count($facebook_user_data)>0) {
		   $customer_data=$this->model_account_social_login->checksocialcustomer($facebook_user_data['id'],'facebook');
		   
		   if(count($customer_data)>0) {
			$this->customer->login($customer_data['email'], $facebook_user_data['id']);
			$this->response->redirect($this->url->link('account/account'));
		   } else {
		    if(isset($facebook_user_data['email']) && $facebook_user_data['email']!='') {
			  
			  $this->request->post['firstname']=$facebook_user_data['first_name'];
			  $this->request->post['lastname']=$facebook_user_data['last_name'];
			  $this->request->post['password']=$facebook_user_data['id'];
			  $this->request->post['provider_id']=$facebook_user_data['id'];
			  $this->request->post['email']=$facebook_user_data['email'];
			  $this->request->post['provider']='facebook';
			  $this->request->post['status']='1';
			  $this->request->post['approved']='1';
			  $this->request->post['telephone']='';
			  $this->request->post['fax']='';
			  $this->request->post['customer_group_id']=$this->config->get('config_customer_group_id');
			  
			  $this->model_account_social_login->addProviderCustomer($this->request->post);
			  
			  $this->customer->login($facebook_user_data['email'], $facebook_user_data['id']);
			  $this->response->redirect($this->url->link('account/account'));
			}else {
				$this->session->data['facebook_user_data'] = $facebook_user_data;
				$this->response->redirect($this->url->link('account/social_login/social_email', '', 'SSL'));
			}
		   }
		   
	 } else {
		  $this->session->data['error'] = $this->language->get('text_twitter_error');
		  $this->response->redirect($this->url->link('account/login', '', 'SSL'));
	 }
	 
	 
   }
   
    public function social_email(){
	 $this->language->load('account/social_login');
	 $this->load->model('account/customer');
	 $this->load->model('account/social_login');

     $this->document->setTitle($this->language->get('heading_title'));
	 
	 if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateemail()) {
			
			if(isset($this->session->data['facebook_user_data'])){
			 $fb_data=$this->session->data['facebook_user_data'];
			  $this->request->post['firstname']=$fb_data['first_name'];
			  $this->request->post['lastname']=$fb_data['last_name'];
			  $this->request->post['password']=$fb_data['id'];
			  $this->request->post['provider_id']=$fb_data['id'];
			  $this->request->post['provider']='facebook';
			  $this->request->post['status']='1';
			  $this->request->post['approved']='1';
			  $this->request->post['telephone']='';
			  $this->request->post['fax']='';
			  $this->request->post['customer_group_id']=$this->config->get('config_customer_group_id');
			
			  unset($this->session->data['facebook_user_data']);
			}
			
		
			$this->model_account_social_login->addProviderCustomer($this->request->post);
         	$this->customer->login($this->request->post['email'], $this->request->post['password']);
			
			$this->response->redirect($this->url->link('account/success'));
     } 
		
	 $data['breadcrumbs'] = array();

	 $data['breadcrumbs'][] = array(
		'text'      => $this->language->get('text_home'),
		'href'      => $this->url->link('common/home'),       	
		'separator' => false
	 );

	 $data['breadcrumbs'][] = array(
		'text'      => $this->language->get('text_account'),
		'href'      => $this->url->link('account/account', '', 'SSL'),
		'separator' => $this->language->get('text_separator')
	 );
	
	 $data['breadcrumbs'][] = array(
		'text'      => $this->language->get('text_email'),
		'href'      => $this->url->link('account/login/social_email', '', 'SSL'),      	
		'separator' => $this->language->get('text_separator')
	 );
			
	 $data['heading_title'] = $this->language->get('heading_title');


	 $data['entry_email'] = $this->language->get('entry_email');
     
     $data['button_email'] = $this->language->get('button_email');

	 if (isset($this->error['warning'])) {
		$data['error_warning'] = $this->error['warning'];
	 } else {
		$data['error_warning'] = '';
	 }
	 
			
	  $data['action'] = $this->url->link('account/social_login/social_email', '', 'SSL');
	  
	  if (isset($this->request->post['email'])) {
    		$data['email'] = $this->request->post['email'];
	  } else {
			$data['email'] = '';
	  }
		
	 		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/social_email.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/social_email.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/social_email.tpl', $data));
		}
		
	}
  	
	protected function validateemail() {
    	if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
      		$this->error['warning'] = $this->language->get('error_email');
    	}
		if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
      		$this->error['warning'] = $this->language->get('error_exists');
    	}
		
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}
}
?>