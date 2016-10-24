<?php
/**
 * Class ControllerModuleKBMModarchive
 * @property ModelModuleKBMModArchive $model
 * @property ModelModuleKulerBlogManager $kbm_model
 */
class ControllerModuleKBMModarchive extends Controller
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

	    $articles = $this->kbm_model->getArticles(array(
		    'exclude_category' => $setting['exclude_categories']
	    ));

	    $articles = $this->kbm_model->prepareArticles($articles);

	    // Group article by years, moths
	    $year_articles = array();
	    $year_counter = array();
	    $month_counter = array();

	    foreach ($articles as $article)
	    {
		    $year = date('Y', $article['date_added']);
		    $month = date('F', $article['date_added']);

		    if (!isset($year_articles[$year]))
		    {
			    $year_articles[$year] = array();
			    $month_counter[$year] = array();
			    $year_counter[$year] = 0;
		    }

		    if (!isset($year_articles[$year][$month]))
		    {
			    $year_articles[$year][$month] = array();
			    $month_counter[$year][$month] = 0;
		    }

		    if ($setting['article_count'])
		    {
			    $year_counter[$year]++;
			    $month_counter[$year][$month]++;
		    }

		    $year_articles[$year][$month][] = array(
			    'name' => $article['name'],
			    'link' => $article['link']
		    );
	    }

	    $this->data['year_articles'] = $year_articles;
	    $this->data['year_counter'] = $year_counter;
	    $this->data['month_counter'] = $month_counter;

	    // Module Index
	    $this->data['module']   = $module++;

	    // Module title
	    $this->data['show_title']   = $setting['show_title'];
	    if (isset($setting['kbm_mod_archive'][$this->config->get('config_language_id')])) {
		    $this->data['title'] = html_entity_decode($setting['kbm_mod_archive'][$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8');
	    }

	    // Languages
	    $this->language->load('module/kbm_mod_archive');

	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/kbm_mod_archive.tpl')) {
		    return $this->load->view($this->config->get('config_template') . '/template/module/kbm_mod_archive.tpl', $this->data);
	    } else {
		    return $this->load->view('default/template/module/kbm_mod_archive.tpl', $this->data);
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