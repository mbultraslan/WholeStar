<?php  

/******************************************************

 * @package Pav Sliders Layers module for Opencart 1.5.x

 * @version 1.0

 * @author http://www.pavothemes.com

 * @copyright	Copyright (C) 2013 PavoThemes.com <@emai:pavothemes@gmail.com>.All rights reserved.

 * @license		GNU General Public License version 2

*******************************************************/



class ControllerModuleKulerLayerSlider extends Controller {

	protected $common;



	protected $data = array();



	public function __construct($registry)

	{

		parent::__construct($registry);



		$this->load->model('kuler/common');

		$this->common = $this->model_kuler_common;

	}



	public function index($setting) {

		if (empty($setting['slider_id']) || !$this->common->isKulerTheme($this->config->get('config_template')))

		{

			return false;

		}



		static $module = 0;

		

		$this->load->model('kuler_layer_slider/slider');

		$this->load->model('tool/image');	

		

		$model = $this->model_kuler_layer_slider_slider;

		$group_id = (int)$setting['slider_id'];



		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/layerslider/css/typo.css')) {

			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/layerslider/css/typo.css');

		} else {

			$this->document->addStyle('catalog/view/theme/default/stylesheet/layerslider/css/typo.css');

		}

		

		$this->document->addScript('catalog/view/javascript/layerslider/jquery.themepunch.plugins.min.js');	 

		$this->document->addScript('catalog/view/javascript/layerslider/jquery.themepunch.revolution.min.js');	 



 	 	$url =   $this->config->get('config_secure') ? $this->config->get('config_ssl') : $this->config->get('config_ssl'); 

 		

 		$this->data['url'] = $url;



 		$sliderGroup = $model->getSliderGroupById( $group_id );

		$sliders = $model->getSlidersByGroupId( $group_id );

		$this->data['sliderParams'] = $sliderGroup['params'];

	 

		if(isset($sliderGroup['params']['fullwidth']) && (!empty($sliderGroup['params']['fullwidth']) || $sliderGroup['params']['fullwidth'] == 'boxed')){

			$sliderGroup['params']['image_cropping'] = false; 

		}



		foreach( $sliders as $key => $slider ){

			$slider["layers"] = array();

			$slider['params'] = unserialize( $slider["params"] ); 

			$slider['layersparams'] = unserialize( $slider["layersparams"] );

			$slider['main_image'] = $url."image/".$slider['image'];



			$sliders[$key] = $slider;

		} 



		$this->data['sliders'] = $sliders;



		$this->data['module'] = ++$module;



		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/kuler_layer_slider.tpl')) {

			return $this->load->view($this->config->get('config_template') . '/template/module/kuler_layer_slider.tpl', $this->data);

		} else {

			return $this->load->view('default/template/module/kuler_layer_slider.tpl', $this->data);

		}

	}

}

?>