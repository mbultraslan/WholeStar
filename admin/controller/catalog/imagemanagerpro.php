<?php
class ControllerCatalogImageManagerPro extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/imagemanagerpro');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addStyle('view/javascript/imagemanagerpro/jquery-ui.css');
		$this->document->addScript('view/javascript/imagemanagerpro/jquery.min.js');
		$this->document->addScript('view/javascript/imagemanagerpro/jquery-ui.min.js');
		$this->document->addStyle('view/javascript/imagemanagerpro/elfinder.min.css');
		$this->document->addStyle('view/javascript/imagemanagerpro/theme.css');
		$this->document->addScript('view/javascript/imagemanagerpro/elfinder.min.js');
		$this->document->addScript('view/javascript/imagemanagerpro/elfinder.ru.js');

		$this->getList();
	}

	protected function getList() {

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_title'] = $this->language->get('column_title');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/imagemanagerpro', 'token=' . $this->session->data['token'] , 'SSL')
		);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/imagemanagerpro.tpl', $data));
	}

}