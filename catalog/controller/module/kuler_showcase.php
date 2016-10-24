<?php



/**

 * Class ControllerModuleKulerShowcase

 * @property $common ModelKulerCommon

 */

class ControllerModuleKulerShowcase extends Controller

{

	protected $common;



	public function __construct($registry)

	{

		parent::__construct($registry);



		$this->load->model('kuler/common');

		$this->common = $this->model_kuler_common;

	}



	public function index($settings)

	{

		if (empty($settings) || !$this->common->isKulerTheme($this->config->get('config_template')))

		{

			return false;

		}



		static $module = 0;



		$data['direction'] = strtolower($this->language->get('direction'));

		$data['__'] = $this->language->load('module/kuler_showcase');



		$data['has_deal_date'] = false;



		// Prepare showcases

		$showcases = array();

		if (!empty($settings['showcases']))

		{

			foreach ($settings['showcases'] as $showcase)

			{

				if (!empty($showcase['status']))

				{

					// Prepare items

					$items = array();

					if (!empty($showcase['items']))

					{

						foreach ($showcase['items'] as $item)

						{

							if (!empty($item['status']))

							{

								// Prepare products

								if ($item['type'] == 'product') {

									$item = $this->common->mapProductDisplayOptions($item);



									if (!empty($item['deal_date'])) {

										$data['has_deal_date'] = true;

									}



									$search_data = array(

										'type' => $item['product_type'],

										'limit' => $item['product_limit']

									);



									if ($item['product_type'] == ModelKulerCommon::TYPE_LATEST || $item['product_type'] == ModelKulerCommon::TYPE_POPULAR) {

										$search_data['category_id'] = $item['product_category'];

									} else if ($item['product_type'] == ModelKulerCommon::TYPE_FEATURED) {

										$search_data['product_ids'] = $item['featured_products'];

									}



									$products = $this->common->getProducts($search_data);



									foreach ($products as &$product) {

										$product = $this->common->prepareProduct($product, $item);



										if (!empty($product['date_end']) && $product['date_end'] == '0000-00-00') {

											$product['date_end'] = '2020-01-01';

										}

									}



									$item['products'] = $products;

								}



								$items[] = $item;

							}

						}



						$items = $this->common->sortByField($items);

					}



					$showcase['items'] = $items;

					$showcases[] = $showcase;

				}

			}



			$showcases = $this->common->sortByField($showcases);

		}



		$data['showcases'] = $showcases;

		$data['settings'] = $settings;

		$data['module']   = $module++;



		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/kuler_showcase.tpl')) {

			return $this->load->view($this->config->get('config_template') . '/template/module/kuler_showcase.tpl', $data);

		} else {

			return $this->load->view('default/template/module/kuler_showcase.tpl', $data);

		}

	}

}