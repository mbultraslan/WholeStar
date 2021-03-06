<?php
/**
 * Class ControllerModuleKBMModCategory
 * @property ModelModuleKBMModCategory $model
 * @property ModelModuleKbm $kbm_model
 */
class ControllerModuleKBMModCategory extends Controller
{
    protected $model;
	protected $kbm_model;
	protected $common;
	protected $data = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

	    $this->load->model('kuler/common');
	    $this->common = $this->model_kuler_common;

	    $this->load->model('module/kbm');
	    $this->kbm_model = $this->model_module_kbm;
    }

    public function index($setting)
    {
	    if (!$this->common->isKulerTheme($this->config->get('config_template')))
	    {
		    return false;
	    }

        static $module = 0;

		// Prepare conditions
	    if (!isset($setting['exclude_categories']))
	    {
		    $setting['exclude_categories'] = array();
	    }

	    // Prepare path
	    $paths = isset($this->request->get['path']) ? explode('_', $this->request->get['path']) : array();

	    $this->data['category_id'] = isset($paths[0]) ? $paths[0] : 0;
	    $this->data['child_id'] = isset($paths[1]) ? $paths[1] : 0;

	    // Prepare Categories
		$categories = $this->kbm_model->getCategories(array(
			'exclude_category' => $setting['exclude_categories'],
			'parent_id'      => 0
		));

	    foreach ($categories as &$category)
	    {
		    $subcategories = $this->kbm_model->getCategories(array(
			    'exclude_category'  => $setting['exclude_categories'],
			    'parent_id'         => $category['category_id']
		    ));

		    $children = array();
		    foreach ($subcategories as $subcategory)
		    {
			    $subcategory = $this->kbm_model->prepareCategory($subcategory);

			    $article_count = $this->kbm_model->countArticles(array(
				    'category_id'       => $subcategory['category_id'],
				    'exclude_category'  => $setting['exclude_categories'],
			    ), array('subcategory' => true));

			    $children[] = array(
				    'category_id'   => $subcategory['category_id'],
				    'name'          => $subcategory['name'] . ($setting['article_count'] ? ' (' . $article_count . ')' : ''),
				    'article_count' => $article_count,
				    'link'          => $this->kbm_model->link('module/kbm/category', array('path' => "{$category['category_id']}_{$subcategory['category_id']}"))
			    );
		    }

		    $category = $this->kbm_model->prepareCategory($category);

		    $article_count = $this->kbm_model->countArticles(array(
			    'category_id' => $category['category_id'],
			    'exclude_category'  => $setting['exclude_categories']
		    ), array('subcategory' => true));

		    $category = array(
			    'category_id'   => $category['category_id'],
			    'name'          => $category['name'] . ($setting['article_count'] ? ' (' . $article_count . ')' : ''),
			    'article_count' => $article_count,
		        'link'          => $category['link'],
			    'children'      => $children
		    );
	    }

	    $this->data['categories'] = $categories;

	    // Module Index
	    $this->data['module']   = $module++;

	    // Module title
	    $this->data['show_title']   = $setting['show_title'];
	    if (isset($setting['kbm_mod_category'][$this->config->get('config_language_id')])) {
		    $this->data['title'] = html_entity_decode($setting['kbm_mod_category'][$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8');
	    }

	    // Languages
	    $this->language->load('module/kbm_mod_category');

	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/kbm_mod_category.tpl')) {
		    return $this->load->view($this->config->get('config_template') . '/template/module/kbm_mod_category.tpl', $this->data);
	    } else {
		    return $this->load->view('default/template/module/kbm_mod_category.tpl', $this->data);
	    }
    }

	private function translate($texts, $language_id)
	{
		if (is_array($texts))
		{
			$first = current($texts);

			if (is_string($first))
			{
				$texts = empty($texts[$language_id]) ? $first : $texts[$language_id];
			}
			else if (is_array($texts))
			{
				if (!isset($texts[$language_id]))
				{
					$texts[$language_id] = array();
				}

				foreach ($first as $key => $value)
				{
					if (empty($texts[$language_id][$key]))
					{
						$texts[$language_id][$key] = $value;
					}
				}
			}
		}

		return $texts;
	}
}