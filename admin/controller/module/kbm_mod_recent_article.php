<?php
/**
 * Class ControllerModuleKBMModRecentArticle
 * @property Config $config
 * @property Url $url
 * @property Request $request
 * @property Session $session
 * @property Document $document
 * @property ModelModuleKBMModRecentArticle $model
 * @property ModelModuleKbm $kbm_model
 */
class ControllerModuleKBMModRecentArticle extends Controller
{
	const MODE                  = 'PRODUCTION';

	public static $__           = array();
	public static $lost_texts   = array();

	/* @var  */
	private $model;
	private $kbm_model;
	private $errors = array();

	/* @var ModelKulerCommon $common */
	protected $common;
	protected $data = array();

	public function __construct($registry)
	{
		parent::__construct($registry);

		$this->load->model('kuler/common');
		$this->common = $this->model_kuler_common;

		ModelKulerCommon::loadTexts($this->language->load('module/kbm_mod_recent_article'));

		$this->load->model('module/kbm');
		$this->kbm_model = $this->model_module_kbm;

		$this->load->model('module/kbm_mod_recent_article');
		$this->model = $this->model_module_kbm_mod_recent_article;

		$this->data['token'] = $this->session->data['token'];
		self::$__ = $this->getLanguages();
		$this->data['__'] = self::$__;
	}

	public function index()
	{
		$this->data['breadcrumbs'] = $this->getPathways();
		$this->data['stores'] = $this->getStores();
		$this->data['selected_store_id'] = $this->getSelectedStore();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate())
		{
			$this->save();
		}

		$this->prepareAlerts();
		$this->getResources();

		if (!empty($this->request->get['module_id'])) {
			$this->data['action'] = $this->url->link('module/kbm_mod_recent_article', 'token=' . $this->session->data['token'] . '&module_id='. $this->request->get['module_id']  , 'SSL');

		} else {
			$this->data['action'] = $this->helperLink('module/kbm_mod_recent_article');

		}

		$this->data['cancel'] = $this->helperLink('extension/module');

		$this->data['config_language_id'] = $this->config->get('config_language_id');

		$this->data['languages']                = $this->getLanguageOptions();

		$this->data['layouts']      = $this->common->getLayouts();
		$this->data['positions']    = $this->common->getPositions();
		$this->data['default_module'] = $this->getDefaultModule();

		$this->data['category_options'] = $this->kbm_model->getCategoryOptions();

		$this->load->model('extension/module');

		$this->data['moduleName'] = 'kbm_mod_recent_article';

		if (!empty($this->request->get['module_id'])) {
			$module = $this->model_extension_module->getModule($this->request->get['module_id']);
			$module['module_id'] = $this->request->get['module_id'];
			$module['shortcode'] = '[' . $this->data['moduleName'] . '.' . $module['module_id'] . ']';

			$this->data['modules'] = array(
				$module
			);
		} else {
			$this->data['modules'] = array(
				array(
					'module_id' => 0,
					'status' => 1,
					'name' => 'Blog Recent Article',
				)
			);
		}

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['kbm_mod_recent_article_module'])) {
			$this->data['kbm_mod_recent_article_module'] = $this->request->post['kbm_mod_recent_article_module'];
		} elseif (!empty($module_info)) {
			$this->data['kbm_mod_recent_article_module'] = $module_info['kbm_mod_recent_article_module'];
		} else {
			$this->data['kbm_mod_recent_article_module'] = '';
		}


		$this->document->setTitle(_t('heading_module_title'));

		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/kbm_mod_recent_article.tpl', $this->data));
	}

	private function prepareModules($modules)
	{
		if (is_array($modules))
		{
			foreach ($modules as &$module)
			{
				$module['title'] = $this->translate($module['title']);
				$module['main_title'] = $module['title'][$this->config->get('config_language_id')];
			}
		}

		return $modules;
	}

	private function getStores()
	{
		$this->load->model('setting/store');

		// Get stores
		$rows = $this->model_setting_store->getStores();

		$stores = array(
			0 => $this->config->get('config_name') . $this->language->get('text_default')
		);

		foreach ($rows as $row)
		{
			$stores[$row['store_id']] = $row['name'];
		}

		return $stores;
	}

	/**
	 * Get selected store id from post or get
	 */
	private function getSelectedStore()
	{
		$selected_store_id = 0;
		if (isset($this->request->post['store_id']))
		{
			$selected_store_id = $this->request->post['store_id'];
		}
		else if (isset($this->request->get['store_id']))
		{
			$selected_store_id = $this->request->get['store_id'];
		}

		return $selected_store_id;
	}

	private function getLanguages()
	{
		$__ = $this->language->load('module/kbm_mod_recent_article');

		return $__;
	}

	private function getPathways() {
		$breadcrumbs = array();

		$breadcrumbs[] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$breadcrumbs[] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		$breadcrumbs[] = array(
			'text'      => $this->language->get('heading_module_title'),
			'href'      => $this->url->link('module/kbm_mod_recent_article', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		return $breadcrumbs;
	}

	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'module/kbm_mod_recent_article'))
		{
			$this->errors['warning'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['modules']))
		{
			foreach ($this->request->post['modules'] as $module_index => $module)
			{
				if (empty($module['featured_image_width']) || empty($module['featured_image_height']))
				{
					if (empty($this->errors['featured_image_size']))
					{
						$this->errors['featured_image_size'] = array();
					}

					$this->errors['featured_image_size'][$module_index] = _t('error_featured_image_size');
				}
			}
		}

		return !$this->errors ? true : false;
	}

	private function prepareAlerts()
	{
		$this->data['error_warning'] = '';

		$this->data['error_featured_image_size'] = array();

		foreach ($this->errors as $error_key => $error_message)
		{
			$this->data['error_' . $error_key] = $error_message;
		}

		if ($this->errors && empty($this->data['error_warning']))
		{
			$this->data['error_warning'] = _t('error_warning');
		}

		// Success
		$this->data['success'] = isset($this->session->data['success']) ? $this->session->data['success'] : '';

		unset($this->session->data['success']);
	}

	private function getResources()
	{
		$this->document->addStyle('view/kulercore/css/kulercore.css');
		$this->document->addScript('view/kulercore/js/handlebars.js');
	}

	private function getDefaultModule()
	{
		return array(
			'title'                 => '',
			'show_title'            => 1,
			'layout_id'             => 1,
			'position'              => 'content_top',
			'status'                => 1,
			'sort_order'            => '',

			'product_featured_image'    => 1,
			'product_description'       => 1,
			'specific_categories'   => array(),
			'exclude_categories'    => array(),
			'article_limit'         => 5,
			'description_limit'     => 50,
			'featured_image_width'  => 45,
			'featured_image_height' => 45
		);
	}

	private function save()
	{
		$this->load->model('extension/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$data = $this->request->post;

			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('kbm_mod_recent_article', $data);
				$module_id = $this->db->getLastId();
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $data);
				$module_id = $this->request->get['module_id'];
			}
		}

			$this->session->data['success'] = $this->language->get('text_success');

		if (isset($this->request->post['op']) && $this->request->post['op'] == 'close')
		{
			$this->redirect($this->helperLink('extension/module'));
		}
		else
		{
			$this->response->redirect($this->url->link('module/kbm_mod_recent_article', 'token=' . $this->session->data['token'] . '&module_id=' . $module_id, 'SSL'));
		}
	}

	private function getLanguageOptions()
	{
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		$config_language = $this->config->get('config_language');

		$results = array();
		$default_language = $languages[$config_language];
		unset($languages[$config_language]);

		$results[$config_language] = $default_language;
		$results = array_merge($results, $languages);

		return $results;
	}

	private function getLayoutOptions()
	{
		$this->load->model('design/layout');
		$result = $this->model_design_layout->getLayouts();
		return $result;
	}

	private function getPositionOptions()
	{
		return array(
			'content_top'       => _t('text_content_top'),
			'content_bottom'    => _t('text_content_bottom'),
			'column_left'       => _t('text_column_left'),
			'column_right'      => _t('text_column_right')
		);
	}

	public function uninstall()
	{
		$this->load->model('setting/setting');

		$stores = $this->getStores();

		foreach ($stores as $store_id => $store_name)
		{
			$this->model_setting_setting->deleteSetting('kbm_mod_recent_article', $store_id);
		}
	}

	private function helperLink($route, array $params = array())
	{
		$params['token'] = $this->data['token'];

		return $this->url->link($route, http_build_query($params), 'SSL');
	}

	public static function __()
	{
		$args = func_get_args();
		$text = $args[0];
		array_shift($args);

		if (isset(self::$__[$text]))
		{
			array_unshift($args, self::$__[$text]);

			return call_user_func_array('sprintf', $args);
		}
		else
		{
			if (self::MODE == 'DEVELOPMENT')
			{
				if (!in_array($text, self::$lost_texts))
				{
					$cache[] = $text;

					// todo: remove logger
					Logger::log($text);
				}
			}

			return $text;
		}
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
}