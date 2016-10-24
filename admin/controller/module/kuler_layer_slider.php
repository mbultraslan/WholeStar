<?php
class ControllerModuleKulerLayerSlider extends Controller {
	private $error = array();

	/* @var ModelKulerCommon $common */
	protected $common;

	protected $data = array();

	public function __construct($registry)
	{
		parent::__construct($registry);

		$this->load->model('kuler/common');
		$this->common = $this->model_kuler_common;

		ModelKulerCommon::loadTexts($this->language->load('module/kuler_layer_slider'));

		$this->load->model('kuler_layer_slider/slider');

		$this->document->addStyle('view/kuler/css/main.css');
	}
	
	public function getModel( $model='slider' ){
		$model = "model_kuler_layer_slider_"+$model;
		return $this->{$model};
	}

	protected function preload(){
		$this->language->load('module/kuler_layer_slider');
		$this->load->model('tool/image');
		$this->load->model( 'kuler_layer_slider/slider' );
		$this->document->setTitle($this->language->get('heading_module'));
		
		$this->load->model('setting/setting');
				
		$this->data['heading_title'] = $this->language->get('heading_module');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
 		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');			
				
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_width'] = $this->language->get('entry_width');
		$this->data['entry_height'] = $this->language->get('entry_height');
		
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);		
		$this->data['entry_show_image'] = $this->language->get( 'entry_show_image' );
		$this->data['entry_module_title'] = $this->language->get( 'entry_module_title' );
		$this->data['tab_module'] = $this->language->get('tab_module');
		$this->data['entry_image_navigator'] = $this->language->get( 'entry_image_navigator' );
		$this->data['entry_navigator_width'] = $this->language->get( 'entry_navigator_width' );
		$this->data['entry_navigator_height'] = $this->language->get( 'entry_navigator_height' );
		
	}
	public function index() {   
		$this->preload();
		$model = $this->model_kuler_layer_slider_slider;

		if (!empty($this->request->get['module_id'])) {
			$this->load->model('extension/module');

			$module = $this->model_extension_module->getModule($this->request->get['module_id']);

			$_GET['id'] = $module['slider_id'];
			$this->request->get['id'] = $module['slider_id'];
		}

		$model->checkInstall();
		// process input post to insert or update 
		if ( ($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			if ($this->validateSliderGroup()) {
				$data = array();  
				$data['title']  = $this->request->post['slider']['title'] ;
				$data['params'] = serialize( $this->request->post['slider'] );
				$data['id']     = $this->request->post['id'];
				$data['id'] = $model->saveSliderGroupData( $data , $this->request->post['id'] );
				$id = 'id='.$data['id']."&";

				// Add slider to module
				$this->rebuildModules();

				$this->response->redirect($this->url->link('module/kuler_layer_slider', $id.'token=' . $this->session->data['token'], 'SSL'));
			}
		}



 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['success_msg'] = array();
		if( isset($this->request->get['msg_idone'])  ){
			if($this->request->get['msg_idone']){
				$this->data['success_msg'] =  $this->language->get('import_data_done');
			}else{
				$this->data['error_warning'] = $this->language->get('import_data_error');
			}
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => '<i class="fa fa-home"></i> ' . $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ''
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_module'),
			'href'      => $this->url->link('module/kuler_layer_slider', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ''
   		);
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');

		$this->data['entry_banner'] = $this->language->get('entry_banner');
		$this->data['entry_limit'] = $this->language->get('entry_limit');
		$this->data['entry_scroll'] = $this->language->get('entry_scroll');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['action'] = $this->url->link('module/kuler_layer_slider', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['actionImport'] = $this->url->link('module/kuler_layer_slider/import', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];

		$this->data['modules'] = array();
		$this->data['positions'] = array(
										  'content_top',
										  'column_left',
										  'column_right',
										  'content_bottom'
		);
		$this->data['yesno'] = array( 1=> $this->language->get('text_yes'), 0=>$this->language->get('text_no') );

		$this->data['shadow_types'] = array(
			0  	=> $this->language->get('text_no_shadow'),
			1  => 1,
			2  => 2,
			3  => 3
		);
		$this->data['linepostions'] = array(
			'bottom'  => $this->language->get('text_bottom'),
			'top'     => $this->language->get('text_top')
		);
		$this->data['navigator_types'] = array(
			'none'  => $this->language->get('text_none'),
			'bullet'     => $this->language->get('text_bullet'),
//			'thumb'     => $this->language->get('text_thumbnail'),
//			'both'     => $this->language->get('text_both')
				
		);
		$this->data['navigation_arrows'] = array(
			'none'    			 => $this->language->get('text_none'),
			'nexttobullets' 	 => $this->language->get('text_nexttobullets'),
			'verticalcentered'   => $this->language->get('text_verticalcentered')
		);
		
		$this->data['navigation_style'] = array(
			'round' 	    => $this->language->get('text_round'),
			'navbar'        => $this->language->get('text_navbar'),
			'round-old'     => $this->language->get('text_round_old') ,
			'square-old'    => $this->language->get('text_square_old') ,
			'navbar-old'    => $this->language->get('text_navbar_old') 
		);

		$d = array('layout_id'=>'','position'=>'','status'=>'','sort_order'=>'1',
		'banner_image'=>array(),'width'=>940,'height'=>350,
		'image_navigator' => 0,
		'navimg_height'   =>97,
		'navimg_weight'   =>177
		);

		$this->data['store_options'] = $this->getStoreOptions();

		$id = isset($this->request->get['id']) ? $this->request->get['id']:0;
		$sliderGroup = $model->getSliderGroupById( $id );

		$params = $sliderGroup['params'] ;

		if ($id)
		{
			$this->load->model('extension/module');

			$layout_modules = $this->model_extension_module->getModulesByCode('kuler_layer_slider');
			$module_id = 0;

			foreach ($layout_modules as $layout_module) {
				$data = unserialize($layout_module['setting']);

				if (!empty($data['slider_id']) && $data['slider_id'] == $id) {
					$module_id = $layout_module['module_id'];
				}
			}

			$params['shortcode'] = "[kuler_layer_slider.$module_id]";
		}
		else
		{
			$params['shortcode'] = '';
		}

		$this->data['id'] = $id;
		if (isset($this->request->post['kuler_layer_slider_module'])) {
			$this->data['modules'] = $this->request->post['kuler_layer_slider_module'];
		} else {
			$this->data['modules'] = array();

			// Get modules form multi-store
			foreach ($this->data['store_options'] as $store_id => $store_name)
			{
				$store_settings = $this->model_setting_setting->getSetting('kuler_layer_slider', $store_id);

				if (isset($store_settings['kuler_layer_slider_module']) && is_array($store_settings['kuler_layer_slider_module']))
				{
					$this->data['modules'] = array_merge($this->data['modules'], $store_settings['kuler_layer_slider_module']);
				}
			}

			// Fill store_id for each module
			foreach ($this->data['modules'] as &$module)
			{
				if (!isset($module['store_id']))
				{
					$module['store_id'] = 0;
				}
			}
		}

		if( !empty($this->data['modules']) ){
			 $d = array_merge($d,$this->data['modules'][0]);			
		}
		$this->data['module'] = $d;
		if( $d['banner_image'] ){
			$tmp = array();$i=1;
			foreach( $d['banner_image'] as $key => $banner ){
				$banner['link'] = isset($banner['link'])?trim($banner['link']):"";
				$banner['thumb'] = $this->model_tool_image->resize($banner['image'], 100, 100);
				$tmp[$i++] = $banner;
			}
			 $d['banner_image'] = $tmp;
		}


		$this->data['slidergroups'] = $model->getListSliderGroups();

		$this->data['params'] = $params;
		$this->data['fullwidth'] = array( '' 		   => $this->language->get('Boxed'),
										  'fullwidth'  => $this->language->get('Fullwidth'),
										  'fullscreen' => $this->language->get('Fullscreen') );
		$this->data['banner_image'] = $d['banner_image'];
		$this->load->model('design/layout');

		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->common->insertCommonResources();

		$this->data['ctrl'] = $this;

		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/layerslider/sliders.tpl', $this->data));
	}

	protected function rebuildModules() {
		$model = $this->model_kuler_layer_slider_slider;
		$this->load->model('extension/module');

		$module_rows = $this->model_extension_module->getModulesByCode('kuler_layer_slider');

		$modules = array();
		$module_ids = array();
		$existing_ids = array();

		foreach ($module_rows as $module_row) {
			$module_settings = unserialize($module_row['setting']);

			$modules[$module_settings['slider_id']] = $module_row['module_id'];
			$module_ids[] = $module_row['module_id'];
		}

		$sliders = $model->getListSliderGroups();
		foreach ($sliders as $slider) {
			$slider_module = array(
				'slider_id' => $slider['id'],
				'name' => $slider['title'],
				'status' => 1
			);

			if (isset($modules[$slider['id']])) {
				$existing_ids[] = $modules[$slider['id']];

				$this->model_extension_module->editModule($modules[$slider['id']], $slider_module);
			} else {
				$this->model_extension_module->addModule('kuler_layer_slider', $slider_module);
			}
		}

		$removed_ids = array_diff($module_ids, $existing_ids);

		foreach ($removed_ids as $removed_id) {
			$this->model_extension_module->deleteModule($removed_id);
		}
	}

	public function layer() {
		$this->preload();

		$id = isset($this->request->get['id']) ? $this->request->get['id'] : 0;

		if (!isset($this->request->get['group_id'])  || !$this->request->get['group_id']) {
			$this->response->redirect( $this->url->link('module/kuler_layer_slider', 'token=' . $this->session->data['token'], 'SSL') );
		}

		$groupID = (int)$this->request->get['group_id'];

		$model = $this->model_kuler_layer_slider_slider;

	 	$this->data['success_msg'] = array();
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_module'),
			'href'      => $this->url->link('module/kuler_layer_slider', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);	
		$this->data['action'] = $this->url->link('module/kuler_layer_slider/savedata', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('module/kuler_layer_slider', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'];

		$sliderGroup = $model->getSliderGroupById( $groupID );
		if( !$sliderGroup ){
			$this->response->redirect( $this->url->link('module/kuler_layer_slider', 'token=' . $this->session->data['token'], 'SSL') );
		}

		$this->data['sliderGroup']  = $sliderGroup;
		$this->data['sliderHeight'] = (int) $sliderGroup['params']['height'];
		$this->data['sliderWidth']  = (int) $sliderGroup['params']['width'];

		//// get list  slider 
		$sliders = $model->getSlidersByGroupId( $groupID );
		$this->data['sliders'] = $sliders;
		$this->data['group_id'] = $groupID;
		$dslider = array(
			'status' => 1,
		);

		$default = array(
			'id'  => '',
			'title' => 'Slide',
			'group_id' => $groupID,
			'status' => '1',
			'params' => array(
				'slider_transition' => 'random',
				'slider_duration'    => '300',
				'slider_rotation'   => '0',
				'slider_link'  => '',
			)
		);

		$slider = $model->getSliderById($id);
		$times = array();
		$layers = array();

		$layers = array();
		if (!empty($slider['layersparams'])) {
			$std = unserialize( $slider['layersparams'] );
			$layers = $std->layers;
		}

		$slide_params = array();
		if (!empty($slider['params']) && is_string($slider['params'])) {
			$slide_params = unserialize($slider['params']);

		}

		$slide_params = array_merge($default['params'], $slide_params);

		$slider = array_merge($default, $slider);

		$this->data['slide_option'] = $slider;
		$this->data['slide_params'] = $slide_params;
		$this->data['layers'] = $layers;
		$this->data['slider_id']  = $id;

		$typoFile = 	HTTP_CATALOG."catalog/view/theme/default/stylesheet/layerslider/css/typo.css";
		if (file_exists(DIR_CATALOG ."view/theme/". $this->config->get('config_template')."/stylesheet/layerslider/css/typo.css")) {
			$typoFile = HTTP_CATALOG."catalog/view/theme/". $this->config->get('config_template')."/stylesheet/layerslider/css/typo.css";
		}
		$this->document->addStyle($typoFile);

		// Get layer style lists
		$this->data['layer_styles'] = array();

		$typo_path = DIR_CATALOG . 'view/theme/default/stylesheet/layerslider/css/typo.css';

		if (file_exists($typo_path)) {
			$type_contents = file_get_contents($typo_path);

			if (preg_match_all('/^\.tp-caption.(\w+)/m', $type_contents, $matches)) {
				$this->data['layer_styles'] = $matches[1];
			}
		}

		$this->data['front_base'] = $this->common->getFrontBase();
		$this->data['save_url']     = $this->common->createLink('module/kuler_layer_slider/saveSlide');

		$this->common->insertCommonResources();

		$this->document->addStyle('view/kuler/css/layer_slider.css');
		$this->document->addScript($this->common->createLink('module/kuler_layer_slider/jsMessages'));
		$this->document->addScript('view/kuler/js/layer_slider.js');

		$this->data['ctrl'] = $this;

		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/layerslider/layer.tpl', $this->data));
	}

	public function saveSlide() {
		$json = array();

		if (!$this->user->hasPermission('modify', 'module/kuler_layer_slider') && $this->request->server['REQUEST_METHOD'] == 'POST') {
			$json['status'] = 0;
			$json['message'] = _t('error_permission');
		} else {
			$slide_option = json_decode(html_entity_decode($this->request->post['slide_option_data'], ENT_QUOTES, 'UTF-8'), true);
			$slide = json_decode(html_entity_decode($this->request->post['slide_data'], ENT_QUOTES, 'UTF-8'), true);
			$layers = json_decode(html_entity_decode($this->request->post['layers_data'], ENT_QUOTES, 'UTF-8'), true);

			unset($slide['params']);

			$layers_obj = new stdClass();
			$layers_obj->layers = $layers;

			$data = array(
				'params'	   => serialize($slide),
				'layersparams' => serialize($layers_obj),
				'group_id'     => $slide_option['group_id'],
				'title'   	   => $slide_option['title'],
				'id'		   => $slide_option['id'],
				'image'        => $slide['slider_image'],
				'status'       => $slide_option['status']
			);

			$id = $this->model_kuler_layer_slider_slider->saveData($data);

			$json['status'] = 1;
			$json['message'] = _t('text_success');

			if ($id != $slide_option['id']) {
				$json['redirect'] = $this->common->createLink('module/kuler_layer_slider/layer', array('id' => $id, 'group_id' => $slide_option['group_id']));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function copythis(){
		if (!$this->user->hasPermission('modify', 'module/kuler_layer_slider')) {
			$this->error['warning'] = $this->language->get('error_permission');
			die( $this->language->get('error_permission') ); 
		}
	 	$this->preload();
	 	$model = $this->model_kuler_layer_slider_slider;
	 	if( isset($this->request->get['id']) ){
	 		$id = (int) $this->request->get['id'];
	 		$slider = $slider = $model->getSliderById( $id );
	 		$slider['title'] = 'Copy Of ' . $slider['title'];
	 		$slider['id'] = 0;
	 		$id = $model->saveData( $slider );

	 		$url = $this->url->link('module/kuler_layer_slider/layer', 'id='.$id.'&group_id='.$slider['group_id'].'&token=' . $this->session->data['token'], 'SSL');
	 		$this->response->redirect( $url );
	 	}
	 	die("Having Error");
	}
 	
 	public function import(){
 		if (!$this->user->hasPermission('modify', 'module/kuler_layer_slider')) {
			$this->error['warning'] = $this->language->get('error_permission');
			die( $this->language->get('error_permission') ); 
		}
 		$this->preload();
		$done = 0;
 		if( isset($_FILES['import_file']['name']) ){
 			$path = $_FILES['import_file']['tmp_name'];

 			$content = trim(file_get_contents( $path ));

 			$data = unserialize( $content );
 		 
 			if( is_object($data) && isset($data->group) && isset($data->sliders) ){
 		 
 				$id = $this->model_kuler_layer_slider_slider->saveSliderGroupData( $data->group );
 				if( $id ) {
 					foreach( $data->sliders as $slider ){
 						$slider['id'] = 0;
 						$slider['group_id'] = $id;
 						$this->model_kuler_layer_slider_slider->saveData( $slider );
 					}
 				} 
				$done = 1;	
 			}
 		}
 		 
 		
 		$url = $this->url->link('module/kuler_layer_slider', 'token=' . $this->session->data['token'].'&msg_idone='.$done, 'SSL');
	 	$this->response->redirect( $url );

 	}
 	public function export(){
 		if (!$this->user->hasPermission('modify', 'module/kuler_layer_slider')) {
			$this->error['warning'] = $this->language->get('error_permission');
			die( $this->language->get('error_permission') ); 
		}
 		$this->preload();

 		if( isset($this->request->get['id']) ){
 			$id = (int) $this->request->get['id'];
 			$sliderGroup = $this->model_kuler_layer_slider_slider->getSliderGroupById( $id );
 			$sliderGroup['id'] = 0;
 			$sliderGroup['params'] = serialize( $sliderGroup['params'] );
 			$export = new stdClass();
 			$export->group = $sliderGroup; 
 			$export->sliders = $this->model_kuler_layer_slider_slider->getSlidersByGroupId( $id );

 			header("Content-Type: plain/text");
			header("Content-Disposition: Attachment; filename=export_group_".time().".txt");
			header("Pragma: no-cache");

			echo  serialize($export); 
 		}
 	}
 	public function deleteslider(){
 		if (!$this->user->hasPermission('modify', 'module/kuler_layer_slider')) {
			$this->error['warning'] = $this->language->get('error_permission');
			$group_id = 0; 
		}else {
	 		$this->preload();
			if( isset($this->request->get['id']) ){ 
				$this->model_kuler_layer_slider_slider->deleteslider( (int)($this->request->get['id']) );
			}
			$group_id = $this->request->get['group_id'];

			$this->rebuildModules();
		}
		
		$url = $this->url->link('module/kuler_layer_slider/layer', 'group_id='.$group_id.'&token=' . $this->session->data['token'], 'SSL');
	 	$this->response->redirect( $url );
 	}


	public function savedata () {
		if (!$this->user->hasPermission('modify', 'module/kuler_layer_slider')) {
			$this->error['warning'] = $this->language->get('error_permission');
			die(  $this->language->get('error_permission') );
		}
		$this->preload();
	 	
	 	$output = new stdClass();
	 	$output->id =	0;
	 	$output->error = 1;
	 	$output->message = $this->language->get('text_could_not_save');
	 	$model = $this->model_kuler_layer_slider_slider;

	  	if( empty($this->request->post['slider_title']) ){
	  		$output->message = $this->language->get('error_missing_title');	
	  		echo json_encode( $output );exit();
	  	}
		
	  	if( $this->request->post ){

	  		$layersparams = new stdClass();
	  		$layersparams->layers = array();
	  		$params = serialize( $this->request->post );

			if( isset($this->request->post['layers'])  && !empty($this->request->post['layers']) ){
					
				$layersparams = new stdClass();
				$times 		 	= $this->request->post['layer_time'];
				$tmp 			= $this->request->post['layers'];	
				


				$layers = $this->request->post['layers'];

				foreach (  $layers as $key => $value ) {
						$value['time_start'] = $times[$value['layer_id']];
					 	$times[$value['layer_id']] = $value;
				}

				$k = 0;
				foreach( $times as $key => $value ) {
					if( is_array($times) ) {
						$value['layer_id'] = $k+1;
						$layersparams->layers[$k] = $value;
						$k++;
					}
				}
			
				unset( $this->request->post['layer_time'] );
				unset( $this->request->post['layers'] );


				$params = serialize( $this->request->post ); 
			}

			$data = array(
				'layersparams' => serialize($layersparams),
				'group_id'     => $this->request->post['slider_group_id'],
				'title'   	   => $this->request->post['slider_title'],
				'id'		   => $this->request->post['slider_id'],
				'image'        => $this->request->post['slider_image'],
				'params'	   =>  $params,	
				'status'       => $this->request->post['slider_status']
			);
		  
			$id = $model->saveData( $data );
		 	$output->id     = $id;
		 	$output->error  = 0;
		}
 		echo json_encode( $output );exit();
	}

	/**
	 * Delete 
	 */
	public function delete(){
		if (!$this->user->hasPermission('modify', 'module/kuler_layer_slider')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}else {
			$this->preload();
			if( isset($this->request->get['id']) ){ 
				$this->model_kuler_layer_slider_slider->delete( (int)($this->request->get['id']) );

				$this->rebuildModules();
			}
		}
		$this->response->redirect($this->url->link('module/kuler_layer_slider', 'token=' . $this->session->data['token'], 'SSL'));
	}

	public function preview () {	
		$this->preload();

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')))
		{
			$server = HTTPS_CATALOG;
		}
		else
		{
			$server = HTTP_CATALOG;
		}

		$this->data['url'] = $server;
		
		if( isset($this->request->get['id']) ){
			$this->template = 'module/layerslider/previewgroup.tpl';
			$sliderGroup = $this->model_kuler_layer_slider_slider->getSliderGroupById( (int)$this->request->get['id'] );
			$this->data['sliderParams'] = $sliderGroup['params'];
			$sliders =  $this->model_kuler_layer_slider_slider->getSlidersByGroupId( (int)$this->request->get['id'] );

			foreach( $sliders as $key=> $slider ){
				$slider["layers"] = array();
				$slider['params'] = unserialize( $slider["params"] ); 
				$slider['layersparams'] = unserialize( $slider["layersparams"] ); 
				
				if( $sliderGroup['params']['image_cropping']) { 
					 $slider['main_image'] = $this->model_kuler_layer_slider_slider->resize($slider['image'], $sliderGroup['params']['width'],
					 								$sliderGroup['params']['height'],'a');
				}else { 
					 $slider['main_image'] = HTTP_CATALOG."image/".$slider['image'];
				}	
				
				if( $slider['params']['slider_thumbnail'] ) {
					$slider['thumbnail'] = $this->model_kuler_layer_slider_slider->resize( $slider['params']['slider_thumbnail'], $sliderGroup['params']['thumbnail_width'],
					 								$sliderGroup['params']['thumbnail_height'],'a'); 
				}else {
					$slider['thumbnail'] = $this->model_kuler_layer_slider_slider->resize($slider['image'], $sliderGroup['params']['thumbnail_width'],
					 								$sliderGroup['params']['thumbnail_height'],'a'); 
				}
				// echo '<pre>'.print_r( $slider,1 ); die;

				$sliders[$key] = $slider;
			} 
 
			$this->data['sliders'] = $sliders;

			 $this->template = 'module/layerslider/previewgroup.tpl';
		}else { 
		  	if( !isset($this->request->post['slider_preview_data']) ){
		  		die( $this->language->get('text_could_not_show_preview') );
		  	}
			$a =  trim( html_entity_decode($this->request->post['slider_preview_data']) ) ;  
			$a= json_decode( $a, true);

			$sliderGroup = $this->model_kuler_layer_slider_slider->getSliderGroupById( $a['params']['slider_group_id']);

			$this->data['sliderParams'] = $sliderGroup['params'];
		 
			$this->data['sliders'] = array($a);

	 		$this->template = 'module/layerslider/preview.tpl';
 		}
		$this->response->setOutput($this->render());
	}


	public function typo(){

		if (isset($this->request->get['field'])) {
			$this->data['field'] = $this->request->get['field'];
		} else {
			$this->data['field'] = '';
		}
 
	 
	 	$typoFile = 	HTTP_CATALOG."catalog/view/theme/default/stylesheet/layerslider/css/typo.css";	
		if( file_exists( DIR_CATALOG ."view/theme/". $this->config->get('config_template')."/stylesheet/layerslider/css/typo.css" ) ){
			$typoFile = 	HTTP_CATALOG."catalog/view/theme/". $this->config->get('config_template')."/stylesheet/layerslider/css/typo.css";	
		}
		$content = file_get_contents(  $typoFile );

		$this->data['typoFile'] = $typoFile; 
		$data = preg_match_all("#\.tp-caption\.(\w+)\s*{\s*#", $content, $matches);
	
	
		$this->data['captions'] = array();

		if( isset($matches[1]) ){
			$this->data['captions']  = $matches[1];
		}  	

		$this->template = 'module/layerslider/typo.tpl';
		$this->response->setOutput($this->render());
	}

	public function jsMessages() {
		$js = 'var _tMessages = ' . json_encode(ModelKulerCommon::getTexts());

		$this->response->setOutput($js);
	}

	protected function validateSliderGroup() {

		if (!$this->user->hasPermission('modify', 'module/kuler_layer_slider')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		 
		if( !isset($this->request->post['slider']) ){
			$this->error['warning'] = $this->language->get('error_missing_slider_data');
		}elseif(  $this->request->post['slider'] && empty($this->request->post['slider']['title']) ){
			$this->error['warning'] = $this->language->get('error_missing_slider_title');
		}				

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	protected function getStoreOptions()
	{
		$this->load->model('setting/store');

		$stores = array(
			0 => $this->config->get('config_name') . $this->language->get('text_default')
		);

		$secondary_stores = $this->model_setting_store->getStores();;

		foreach ($secondary_stores as $secondary_store)
		{
			$stores[$secondary_store['store_id']] = $secondary_store['name'];
		}

		return $stores;
	}
}
?>