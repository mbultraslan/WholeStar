<?php

/*--------------------------------------------------------------------------/
* @Author		KulerThemes.com http://www.kulerthemes.com
* @Copyright	Copyright (C) 2012 - 2013 KulerThemes.com. All rights reserved.
* @License		KulerThemes.com Proprietary License
/---------------------------------------------------------------------------*/

class ControllerModuleKulerAdvancedHtml extends Controller
{
	protected $common;

	protected $data = array();

	public function __construct($registry)
	{
		parent::__construct($registry);

		$this->load->model('kuler/common');
		$this->common = $this->model_kuler_common;
	}

	public function index($setting)
    {
	    if (empty($setting) || !$this->common->isKulerTheme($this->config->get('config_template'))) {
		    return false;
	    }

        static $module = 0;

        if (empty($setting['description_text']))
        {
            $setting['description_text'] = 100;
        }

	    $this->data['show_title'] = isset($setting['showtitle']) ? $setting['showtitle'] : 1;

	    if (isset($setting['kuler_advanced_html_module'][$this->config->get('config_language_id')])) {
		    $this->data['heading_title'] = html_entity_decode($setting['kuler_advanced_html_module'][$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8');
		    $this->data['html'] = html_entity_decode($setting['kuler_advanced_html_module'][$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');
		    $this->data['html'] = $this->helperProcessShortCodes($this->data['html']);
	    }

        $this->data['module'] = ++$module;
        $this->data['setting'] = $setting;


	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/kuler_advanced_html.tpl')) {
		    return $this->load->view($this->config->get('config_template') . '/template/module/kuler_advanced_html.tpl', $this->data);
	    } else {
		    return $this->load->view('default/template/module/kuler_advanced_html.tpl', $this->data);
	    }
	}

	private function helperProcessShortCodes($message)
	{
    	if (preg_match_all('#\[(kuler|kbm).+?\]#', $message, $matches))
    	{
    		// Get short codes
    		foreach ($matches[0] as $rawCode) {
	    		$shortcodeParts = $this->helperParseShortcode($rawCode);

	    		$this->load->model('extension/module');

				if (isset($shortcodeParts[0]) && $this->config->get($shortcodeParts[0] . '_status')) {
					$module_html = $this->load->controller('module/' . $shortcodeParts[0]);
				}
				
				if (isset($shortcodeParts[1])) {
					$setting_info = $this->model_extension_module->getModule($shortcodeParts[1]);
					
					if ($setting_info && $setting_info['status']) {
						$module_html = $this->load->controller('module/' . $shortcodeParts[0], $setting_info);
					}
				}

				if (!empty($module_html)) {
					// Remove the margin of module
					if (preg_match('#<div(.+)?>#', $module_html, $matches) && strpos($matches[0], 'kuler-module') !== false)
					{
					    $matches[1] .= ' style="margin:0;"';
					    $div = '<div'. $matches[1] .'>';

					    $module_html = str_replace($matches[0], $div, $module_html);
					}

					$message = str_replace($rawCode, $module_html, $message);
				}
    		}
    	}

    	return $message;
	}

	private function helperParseShortcode($shortcode)
	{
		$shortcode = str_replace('&nbsp;', ' ', $shortcode);
		return explode('.', trim($shortcode, '[]'));
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
?>