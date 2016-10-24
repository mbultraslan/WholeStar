<?php
class ControllerModuleKulerFilter extends Controller {
	private $error = array();

	/* @var ModelKulerCommon $common */
	protected $common;

    protected $data = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

	    $this->load->model('kuler/common');
	    $this->common = $this->model_kuler_common;

	    ModelKulerCommon::loadTexts($this->language->load('module/kuler_filter'));

        $this->data['__'] = $this->language->load('module/kuler_filter');
    }

    /**
     * Add route module/kuler_filter_result under each route product/category
     */
    public function install() {
		$this->load->model('design/layout');

        /* @var ModelDesignLayout $layout_model */
        $layout_model = $this->model_design_layout;
		
        $layouts = $layout_model->getLayouts();

        if (is_array($layouts))
        {
            foreach ($layouts as $layout)
            {
                $routes = $layout_model->getLayoutRoutes($layout['layout_id']);

                if (is_array($routes))
                {
                    $need_edit = false;
                    $extra_routes = $routes;

                    foreach ($routes as $route)
                    {
                        if ($route['route'] == 'product/category')
                        {
                            $extra_routes[] = array(
                                'route' => 'module/kuler_filter_result',
                                'store_id' => $route['store_id']
                            );

                            $need_edit = true;
                        }
                    }

                    if ($need_edit)
                    {
                        $layout['layout_route'] = $extra_routes;
                        $layout_model->editLayout($layout['layout_id'], $layout);
                    }
                }
            }
        }
	}

    /**
     * Remove route module/kuler_filter_result
     */
    public function uninstall() {
        $this->load->model('design/layout');

        /* @var ModelDesignLayout $layout_model */
        $layout_model = $this->model_design_layout;

        $layouts = $layout_model->getLayouts();

        if (is_array($layouts))
        {
            foreach ($layouts as $layout)
            {
                $routes = $layout_model->getLayoutRoutes($layout['layout_id']);

                if (is_array($routes))
                {
                    $need_edit = false;

                    foreach ($routes as $route_index => $route)
                    {
                        if ($route['route'] == 'module/kuler_filter_result')
                        {
                            unset($routes[$route_index]);

                            $need_edit = true;
                        }
                    }

                    if ($need_edit)
                    {
                        $layout['layout_route'] = $routes;
                        $layout_model->editLayout($layout['layout_id'], $layout);
                    }
                }
            }
        }
	}
	
	public function index() {
        $this->load->model('setting/setting');
		$this->load->model('extension/module');
		$this->getLanguages();
		$this->getPathways();

        $this->data['languages'] = $this->getLanguageOptions();

		$this->load->model('localisation/currency');
		
		$results = $this->model_localisation_currency->getCurrencies();	

        $this->data['currency_options'] = array();

		foreach ($results as $result) {
			if ($result['status']) {
                $this->data['currency_options'][$result['code']] = $result['title'];
			}
		}

		$this->data['moduleName'] = 'kuler_filter';
		$this->data['config_admin_language_id'] = $this->config->get('config_language_id');

		// Attributes
		$this->load->model('catalog/attribute_group');
		$this->load->model('catalog/attribute');

		$attr_groups = $this->model_catalog_attribute_group->getAttributeGroups();

		foreach ($attr_groups as &$attr_group)
		{
			$attr_group['attrs'] = $this->model_catalog_attribute->getAttributes(array('filter_attribute_group_id' => $attr_group['attribute_group_id']));
		}

		$this->data['attr_groups'] = $attr_groups;

		// Options
		$this->load->model('catalog/option');

		$options = $this->model_catalog_option->getOptions();

		foreach ($options as &$option)
		{
			$option['option_values'] = $this->model_catalog_option->getOptionValues($option['option_id']);
		}

		$this->data['options'] = $options;

		$this->data['token'] = $this->session->data['token'];

        $this->data['store_id']             = isset($this->request->get['store_id']) ? $this->request->get['store_id']: 0;
        $this->data['token']                = $this->session->data['token'];
        $this->data['extension_code']       = 'kuler_filter';
        $this->data['default_module']       = $this->getDefaultModule();
        $this->data['config_language']      = $this->config->get('config_language');

        $this->data['stores']               = $this->common->getStores();
        $this->data['languages']            = $this->common->getLanguages();

        $this->data['front_base']       = $this->common->getFrontBase();

        $this->data['messages']         = ModelKulerCommon::getTexts();

        $this->data['action_url']   = $this->common->createLink('module/kuler_filter/save');
        $this->data['cancel_url']   = $this->common->createLink('extension/module');
        $this->data['store_url']    = $this->common->createLink('module/kuler_filter');

		if (!empty($this->request->get['module_id'])) {
			$module = $this->model_extension_module->getModule($this->request->get['module_id']);
			$module['module_id'] = $this->request->get['module_id'];

			$this->data['modules'] = array(
				$module
			);
		} else {
			$this->data['modules'] = array(
				array(
					'module_id' => 0,
					'name' => 'Filter',
					'status' => '1'
				)
			);
		}

        $this->common->insertCommonResources();
        $this->document->addScript($this->common->createLink('module/kuler_filter/jsMessages'));
        $this->document->addScript('view/kuler/js/filter.js');

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['column_left'] = $this->load->controller('common/column_left');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/kuler_filter.tpl', $this->data));
	}

    public function jsMessages()
    {
        $js = 'var _tMessages = ' . json_encode(ModelKulerCommon::getTexts());

        $this->response->setOutput($js);
    }

    public function save()
    {
        try
        {
            if ($this->request->server['REQUEST_METHOD'] != 'POST')
            {
                throw new Exception(_t('error_permission'));
            }

            $this->validate();

	        $this->load->model('extension/module');

	        $module = $this->request->post['modules'][0];

	        if (empty($module['module_id'])) {
		        unset($module['module_id']);

		        $this->model_extension_module->addModule('kuler_filter', $module);
	        } else {
		        $this->model_extension_module->editModule($module['module_id'], $module);
	        }
            $result = array(
                'status' => 1,
                'message' => _t('text_success')
            );
        }
        catch (Exception $e)
        {
            $result = array(
                'status' => 0,
                'message' => $e->getMessage()
            );
        }

        $this->response->setOutput(json_encode($result));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'module/kuler_showcase'))
        {
            throw new Exception(_t('error_permission'));
        }
    }

    protected function getDefaultModule()
    {
        return array(
            'show_title'                => '1',
            'category'                  => '1',
            'manufacture'               => '1',
            'attribute'                 => '1',
            'option'                    => '1',
            'price'                     => '1',
            'exclude_attr_group_id'     => array(),
            'exclude_attr_id'           => array(),
            'price_min'                 => 0,
            'price_max'                 => 1000,
            'currency'                  => $this->config->get('config_currency')
        );
    }
	
	protected function getLanguages() {
		$this->data['__'] = $this->language->load('module/kuler_filter');
		
		$this->document->setTitle($this->language->get('heading_module'));

		$this->data['heading_title'] = $this->language->get('heading_module');

		$this->data['text_module'] = $this->language->get('text_module');
		$this->data['text_success'] = $this->language->get('text_success');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
        $this->data['text_select_box'] = $this->language->get('text_select_box');
        $this->data['text_option_list'] = $this->language->get('text_option_list');
        $this->data['text_price_hint'] = $this->language->get('text_price_hint');

		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_shortcode'] = $this->language->get('entry_shortcode');
		$this->data['entry_showtitle'] = $this->language->get('entry_showtitle');
		
		$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_price'] = $this->language->get('entry_price');
		$this->data['entry_currency'] = $this->language->get('entry_currency');
		$this->data['entry_manufacture'] = $this->language->get('entry_manufacture');
		$this->data['entry_attribute'] = $this->language->get('entry_attribute');
		$this->data['entry_auto'] = $this->language->get('entry_auto');
        $this->data['entry_filter_type'] = $this->language->get('entry_filter_type');
        $this->data['entry_price_filter'] = $this->language->get('entry_price_filter');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_close'] = $this->language->get('button_close');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');		
	}
	
	/**
	 * @todo : Set pathway
	 */
	protected function getPathways() {
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
			'href'      => $this->url->link('module/kuler_filter', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
	}

    private function translate($texts)
    {
        $languages = $this->getLanguageOptions();

        if (is_string($texts))
        {
            $text = $texts;
            $texts = array();

            foreach ($languages as $language)
            {
                $texts[$language['language_id']] = $text;
            }
        }
        else if (is_array($texts))
        {
            $first = current($texts);

            foreach ($languages as $language)
            {
                if (is_string($first))
                {
                    if (empty($texts[$language['language_id']]))
                    {
                        $texts[$language['language_id']] = $first;
                    }
                }
                else if (is_array($first))
                {
                    if (!isset($texts[$language['language_id']]))
                    {
                        $texts[$language['language_id']] = array();
                    }

                    foreach ($first as $key => $val)
                    {
                        if (empty($texts[$language['language_id']][$key]))
                        {
                            $texts[$language['language_id']][$key] = $val;
                        }
                    }
                }
            }
        }

        return $texts;
    }

    private function getLanguageOptions()
    {
        $this->load->model('localisation/language');
        $languages = $this->model_localisation_language->getLanguages();
        $config_language = $this->config->get('config_language');

	    $keyed_languages = array();
	    foreach ($languages as $language)
	    {
		    $keyed_languages[$language['code']] = $language;
	    }

	    $languages = $keyed_languages;

        $results = array();
        $default_language = $languages[$config_language];
        unset($languages[$config_language]);

        $results[$config_language] = $default_language;
        $results = array_merge($results, $languages);

        return $results;
    }

	protected function jsEncode($obj)
	{
		$js_obj = json_encode($obj);

		if (is_string($obj))
		{
			$js_obj = json_encode(addslashes($obj));
		}

		return $js_obj;
	}
}
?>