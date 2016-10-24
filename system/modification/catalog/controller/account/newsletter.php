<?php
class ControllerAccountNewsletter extends Controller {
	public function unsubscribe() {
 		$this->load->language('account/newsletter');

 		$this->document->setTitle($this->language->get('heading_title'));

 		if ($this->request->get['code']) {
 			$result = $this->db->query("SELECT customer_id, email FROM ".DB_PREFIX."customer WHERE MD5(email) = '" . $this->db->escape($this->request->get['code']) . "'");

			if ($result->num_rows) {
				$this->db->query("UPDATE ".DB_PREFIX."customer SET newsletter = '0' WHERE customer_id = " . (int)$result->row['customer_id'] . "");

	 			$data['text_message'] = sprintf($this->language->get('success_unsubscribe'), $result->row['email']);
 			} else {
 				$data['text_message'] = $this->language->get('error_unsubscribe');
 			}
 		}

 		$data['breadcrumbs'] = array();
 		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
 		);
 		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_newsletter'),
			'href' => $this->url->link('account/newsletter', '', 'SSL')
 		);

 		$data['heading_title'] = $this->language->get('heading_unsubscribe');

	   	$data['button_continue'] = $this->language->get('button_continue');

	   	$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
		}
 	}
 	  
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/newsletter', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/newsletter');

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->load->model('account/customer');

			$this->model_account_customer->editNewsletter($this->request->post['newsletter']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('account/account', '', 'SSL'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_newsletter'),
			'href' => $this->url->link('account/newsletter', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_newsletter'] = $this->language->get('entry_newsletter');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		$data['action'] = $this->url->link('account/newsletter', '', 'SSL');

		$data['newsletter'] = $this->customer->getNewsletter();

		$data['back'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/newsletter.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/newsletter.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/newsletter.tpl', $data));
		}
	}
}