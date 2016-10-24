<?php
class ControllerInformationStn extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('information/stnimage');
		
		$this->load->model('module/stnimage');
		
		$data['stnimages'] = $this->model_module_stnimage->getstnimage();
		//echo '<pre>'; print_r($data['stnimages']); die;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/stn.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/stn.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/information/stn.tpl', $data));
		}
	}
}
