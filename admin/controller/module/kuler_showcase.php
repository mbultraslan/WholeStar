<?php
class ControllerModuleKulerShowcase extends Controller
{
	/* @var ModelKulerCommon $common */
	protected $common;

	public function __construct($registry)
	{
		parent::__construct($registry);

		$this->load->model('kuler/common');
		$this->common = $this->model_kuler_common;

		ModelKulerCommon::loadTexts($this->language->load('module/kuler_showcase'));
	}

	public function index()
	{
		$this->load->model('setting/setting');
		$this->load->model('extension/module');

		$data['store_id']             = isset($this->request->get['store_id']) ? $this->request->get['store_id']: 0;
		$data['token']                = $this->session->data['token'];
		$data['extension_code']       = 'kuler_showcase';
		$data['default_module']       = $this->getDefaultModule();
		$data['config_language']      = $this->config->get('config_language');

		$data['stores']               = $this->common->getStores();
		$data['languages']            = $this->common->getLanguages();
		$data['layouts']              = $this->common->getLayoutOptions();
		$data['positions']            = $this->common->getPositions();

		$data['front_base'] = $this->common->getFrontBase();

		$data['category_options'] = $this->common->getCategoryOptions(array(
		'all' => true,
		'store_id' => $data['store_id']
	));

		$data['moduleName'] = 'kuler_showcase';

		if (!empty($this->request->get['module_id'])) {
			$module = $this->model_extension_module->getModule($this->request->get['module_id']);
			$module['module_id'] = $this->request->get['module_id'];
			$module['shortcode'] = '[' . $data['moduleName'] . '.' . $module['module_id'] . ']';

			$data['modules'] = array(
				$module
			);
		} else {
			$data['modules'] = array(
				array(
					'module_id' => 0,
					'name' => 'Showcase',
					'status' => '1'
				)
			);
		}

		// Prepare modules
		foreach ($data['modules'] as &$module)
		{
			if (!empty($module['showcases']))
			{
				foreach ($module['showcases'] as &$showcase)
				{
					if (!empty($showcase['items']))
					{
						foreach ($showcase['items'] as &$item)
						{
							if ($item['type'] == 'html')
							{
								$item['html_content'] = $this->common->decodeMultilingualText($item['html_content']);
							}
						}
					}
				}
			}
		}

		$data['messages']     = ModelKulerCommon::getTexts();

		$data['action_url']   = $this->common->createLink('module/kuler_showcase/save');
		$data['cancel_url']   = $this->common->createLink('extension/module');
		$data['store_url']    = $this->common->createLink('module/kuler_showcase');

		// Breadcrumbs
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/kuler_showcase', 'token=' . $this->session->data['token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/kuler_showcase', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}

        $this->document->setTitle(_t('heading_module'));

		$this->common->insertCommonResources();

		$this->document->addScript($this->common->createLink('module/kuler_showcase/jsMessages'));
		$this->document->addScript('view/kuler/js/showcase.js');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/kuler_showcase.tpl', $data));
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

				$this->model_extension_module->addModule('kuler_showcase', $module);

				$is_redirect = true;
				$module_id = $this->db->getLastId();
			} else {
				$this->model_extension_module->editModule($module['module_id'], $module);

				$is_redirect = false;
				$module_id = $module['module_id'];
			}

			$result = array(
				'status' => 1,
				'message' => _t('text_success'),
				'redirect' => $is_redirect ? $this->url->link('module/kuler_showcase', 'module_id=' . $module_id . '&token=' . $this->session->data['token']) : ''
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

	public function jsMessages()
	{
		$js = 'var _tMessages = ' . json_encode(ModelKulerCommon::getTexts());

		$this->response->setOutput($js);
	}

	protected function getDefaultModule()
	{
		return array(
			'layout_id'               => '1',
			'position'                => 'content_top',
			'sort_order'              => '3',
			'show_title'              => '1',
			'status'                  => '1'
		);
	}

	protected function getDefaultShowcase() {
		return array(
			'display_order' => 1
		);
	}

	protected function validate()
	{
		if (!$this->user->hasPermission('modify', 'module/kuler_showcase'))
		{
			throw new Exception(_t('error_permission'));
		}
	}
}