<?php

/*--------------------------------------------------------------------------/
* @Author		KulerThemes.com http://www.kulerthemes.com
* @Copyright	Copyright (C) 2012 - 2013 KulerThemes.com. All rights reserved.
* @License		KulerThemes.com Proprietary License
/---------------------------------------------------------------------------*/

class ControllerModuleKulerAdvancedHtml extends Controller {
	private $error = array();

	/* @var ModelKulerCommon $common */
	protected $common;
	protected $data = array();

	public function __construct($registry)
	{
		parent::__construct($registry);

		$this->load->model('kuler/common');
		$this->common = $this->model_kuler_common;

		ModelKulerCommon::loadTexts($this->language->load('module/kuler_advanced_html'));
	}
	 
	public function index() {
		$this->getLanguages();
		$this->getPathways();
        $this->getStores();
		$this->getResources();
        $this->beforeBuildingMode();
		$this->getTabActive();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->saveAction();
		}

		$this->getErrors();
		if (!empty($this->request->get['module_id'])) {
			$this->data['action'] = $this->url->link('module/kuler_advanced_html', 'token=' . $this->session->data['token'] . '&module_id='. $this->request->get['module_id']  , 'SSL');

		} else {
			$this->data['action'] = $this->url->link('module/kuler_advanced_html', 'token=' . $this->session->data['token'], 'SSL');
		}

		if (!empty($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] ='';
		}

		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'];

        $this->data['base'] = $this->getCatalogUrl();

		$this->data['modules'] = array();

		$this->load->model('setting/setting');
		$this->load->model('extension/module');

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
					'title'     =>'Title',
					'status' => 1,
					'showtitle' => 0,
					'name' => 'Kuler Advanced HTML',
					'description' =>'',
					'status' => '1'
				)
			);
		}

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$this->data['name'] = $module_info['name'];
		} else {
			$this->data['name'] = '';
		}

		if (isset($this->request->post['kuler_advanced_html_module'])) {
			$this->data['kuler_advanced_html_module'] = $this->request->post['kuler_advanced_html_module'];
		} elseif (!empty($module_info)) {
			$this->data['kuler_advanced_html_module'] = $module_info['kuler_advanced_html_module'];
		} else {
			$this->data['kuler_advanced_html_module'] = '';
		}

        $this->data['languages'] = $this->getDataLanguages();

		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/kuler_advanced_html.tpl', $this->data));
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']))
		{
			$this->load->model('module/kuler_advanced_html');

			$filter_name = $this->request->get['filter_name'];

			$data = array(
				'filter_name'  => $filter_name,
				'store_id'     => $this->request->get['store_id'],
			);

			$results = $this->model_module_kuler_advanced_html->getProducts(array(
					'filter_name' => urldecode($filter_name),
					'store_id' => $this->request->get['store_id']
				), array(
					'start' => 0,
					'limit' => 20
				)
			);

			foreach ($results as $result)
			{
				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/kuler_advanced_html')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

        if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

    protected function beforeBuildingMode()
    {
        // Initialize building mode
        if (isset($this->request->get['ksb_module']))
        {
            $this->session->data['ksb_module'] = $this->request->request['ksb_module'];
            $this->session->data['ksb_building_mode'] = 1;
            $this->session->data['ksb_new'] = $this->request->request['ksb_new'];
            $this->session->data['ksb_token'] = $this->request->request['token'];

            if ($this->request->get['ksb_new'])
            {
                $this->data['ksb_trigger_creation'] = true;
            }
        }

        // Check building mode
        if (isset($this->session->data['ksb_building_mode']) && $this->session->data['ksb_token'] == $this->session->data['token'])
        {
            $this->data['ksb_building_mode'] = 1;

            $this->session->data['kuler_vtab'] = '#tab-module-' . $this->session->data['ksb_module'];
        }
        else
        {
            unset(
            $this->session->data['ksb_module'],
            $this->session->data['ksb_building_mode'],
            $this->session->data['ksb_new'],
            $this->session->data['ksb_token']
            );
        }

        // Get the updated module
        if (isset($this->session->data['ksb_updated_module']))
        {
            $this->data['ksb_updated_module'] = $this->session->data['ksb_updated_module'];
            unset($this->session->data['ksb_updated_module']);
        }
    }

    protected function postBuildingMode($modules)
    {
        if (!isset($this->session->data['ksb_building_mode']))
        {
            return;
        }

        $module = array();

        if (isset($this->session->data['ksb_new']) && $this->session->data['ksb_new'])
        {
            $module = end($modules);
            $indexes = array_keys($modules);
            $module['index'] = array_pop($indexes);
            $this->session->data['ksb_module'] = $module['index'];
        }
        else
        {
            $module = $modules[$this->session->data['ksb_module']];
        }

        if (isset($module['module_title']))
        {
            $module['title'] = $module['module_title'];
        }

        $this->session->data['ksb_updated_module'] = json_encode(array(
            'status' => '1',
            'module' => $module
        ));
    }
    
	protected function getTabActive() {
        // Remove last active tab
        if(isset($this->session->data['kuler_vtab'])) {
            $this->data['vtab'] = $this->session->data['kuler_vtab']; unset($this->session->data['kuler_vtab']);
        } else {
            $this->data['vtab'] = '';
        }
        if(isset($this->session->data['kuler_htab'])) {
            $this->data['htab'] = $this->session->data['kuler_htab']; unset($this->session->data['kuler_htab']);
        } else {
            $this->data['htab'] = '';
        }
        // Store current active tab
        if(isset($this->request->post['vtab'])) {
            $this->session->data['kuler_vtab'] = $this->request->post['vtab'];
        }
        
        if(isset($this->request->post['htab'])) {
            $this->session->data['kuler_htab'] = $this->request->post['htab'];
        }
    }
    
	protected function getResources() {
		$this->document->addStyle('view/kuler/css/main.css');
	}
	
	protected function getLayouts() {
		$this->load->model('design/layout');
		$result = $this->model_design_layout->getLayouts();
		return $result;
	}

    public function getDataLanguages()
    {
        $this->load->model('localisation/language');
        $languages = $this->model_localisation_language->getLanguages();
        $config_language = $this->config->get('config_language');

        $results = array();
        $default_language = $languages[$config_language];
        unset($languages[$config_language]);

        $results[$config_language] = $default_language;
        foreach ($languages as $code => $language)
        {
            $results[$code] = $language;
        }

        return $results;
    }
	
	protected function getLanguages() {
		$this->data['__'] = $this->language->load('module/kuler_advanced_html');
		$this->document->setTitle($this->language->get('heading_module'));

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_name']   = $this->language->get('entry_name');
		$this->data['entry_showtitle'] = $this->language->get('entry_showtitle');
		$this->data['entry_status']     = $this->language->get('entry_status');

		$this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_close'] = $this->language->get('button_close');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		$this->data['tab_module'] = $this->language->get('tab_module');		
	}
	
	protected function saveAction() {
		$this->load->model('extension/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$data = $this->request->post;

			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('kuler_advanced_html', $data);
				$module_id = $this->db->getLastId();
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $data);
				$module_id = $this->request->get['module_id'];
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('module/kuler_advanced_html', 'token=' . $this->session->data['token'] . '&module_id=' . $module_id, 'SSL'));

		}
	}
	
	protected function getErrors() {
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

        if (isset($this->error['dimension'])) {
            $this->data['error_dimension'] = $this->error['dimension'];
        } else {
            $this->data['error_dimension'] = array();
        }
	}
	
	protected function getPathways() {
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/kuler_advanced_html', 'token=' . $this->session->data['token'], 'SSL')
			);
		} else {
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/kuler_advanced_html', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}
	}

    private function getStores()
    {
        $this->load->model('setting/store');

        $rows = $this->model_setting_store->getStores();

        $stores = array(
            0 => $this->config->get('config_name') . $this->language->get('text_default')
        );

        foreach ($rows as $row)
        {
            $stores[$row['store_id']] = $row['name'];
        }

        $this->data['selected_store_id'] = 0;
        if (isset($this->request->post['store_id']))
        {
            $this->data['selected_store_id'] = $this->request->post['store_id'];
        }
        else if (isset($this->request->get['store_id']))
        {
            $this->data['selected_store_id'] = $this->request->get['store_id'];
        }

        $this->data['stores'] = $stores;

        return $stores;
    }

    private function getCatalogUrl()
    {
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')))
        {
            $base = $this->config->get('config_ssl') ? $this->config->get('config_ssl') : HTTPS_CATALOG;
        }
        else
        {
            $base = $this->config->get('config_url') ? $this->config->get('config_url') : HTTP_CATALOG;
        }

        return $base;
    }

    private function translate($texts)
    {
        $languages = $this->getDataLanguages();

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

    private function sort(array $items, $field = 'sort_order')
    {
        $sortOrder = array();
        foreach ($items as $key => $value)
        {
            $sortOrder[$key] = $value[$field];
        }
        array_multisort($sortOrder, SORT_ASC, $items);

        return $items;
    }

    public function uninstall()
    {
        $this->load->model('setting/setting');

        $stores = $this->getStores();

        foreach ($stores as $store_id => $store_name)
        {
            $this->model_setting_setting->deleteSetting('kuler_advanced_html', $store_id);
        }
    }
}
?>