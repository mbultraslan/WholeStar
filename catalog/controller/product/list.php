<?php
//==============================================================================
// Extra Product Pages v220.1
//
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
//
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================

class ControllerProductList extends Controller {
	private $type = 'product';
	private $name = 'list';
	private $copy = 'category';

    public function all()			{ $this->index('all'); }
    public function bestseller()	{ $this->index('bestseller'); }
    public function coming()		{ $this->index('coming'); }
    public function featured()		{ $this->index('featured'); }
    public function latest()		{ $this->index('latest'); }
    public function popular()		{ $this->index('popular'); }

	public function index($page_type = '') {
		if (empty($page_type)) {
			unset($this->request->get['route']);
			$this->response->redirect(str_replace('&amp;', '&', $this->url->link('product/list/all', http_build_query($this->request->get))));
		}

		$data = $this->load->language('product/' . $this->copy);
		$data = array_merge($data, $this->load->language('product/extra_product_pages'));
		$data['heading_title'] = $data['heading_' . $page_type];

		// Set template variables
		$currency = $this->session->data['currency'];
		$theme = (version_compare(VERSION, '2.2', '<')) ? $this->config->get('config_template') : $this->config->get('config_theme');
		$image_width = (version_compare(VERSION, '2.2', '<')) ? $this->config->get('config_image_product_width') : $this->config->get($theme . '_image_product_width');
		$image_height = (version_compare(VERSION, '2.2', '<')) ? $this->config->get('config_image_product_height') : $this->config->get($theme . '_image_product_height');
		$placeholder = (version_compare(VERSION, '2.0', '<')) ? 'no_image.jpg' : 'placeholder.png';

		if (version_compare(VERSION, '2.0', '<')) {
			$description_limit = 100;
		} elseif (version_compare(VERSION, '2.2', '<')) {
			$description_limit = $this->config->get('config_product_description_length');
		} else {
			$description_limit = $this->config->get($theme . '_product_description_length');
		}

		if (version_compare(VERSION, '2.0', '<')) {
			$pagination_limit = $this->config->get('config_catalog_limit');
		} elseif (version_compare(VERSION, '2.2', '<')) {
			$pagination_limit = $this->config->get('config_product_limit');
		} else {
			$pagination_limit = $this->config->get($theme . '_product_limit');
		}

		// Build $url
		$url = array();
		$filters = array(
			'sort'	=> 'default',
			'order'	=> 'desc',
			'limit' => $pagination_limit,
			'page'	=> 1
		);
		foreach ($filters as $key => $value) {
			if (isset($this->request->get[$key])) {
				$url[$key] = '&' . $key . '=' . $this->request->get[$key];
				$filters[$key] = $this->request->get[$key];
			} else {
				$url[$key] = '';
			}
			$data[$key] = $filters[$key];
		}

		// Breadcrumbs
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'href'		=> $this->url->link('common/home'),
			'text'		=> $data['text_home'],
			'separator' => false,
		);
		$data['breadcrumbs'][] = array(
			'href'		=> $this->url->link($this->type . '/' . $this->name . '/' . $page_type, $url['sort'] . $url['order'] . $url['limit'] . $url['page']),
			'text'      => $data['heading_title'],
			'separator' => (version_compare(VERSION, '2.0', '<')) ? $data['text_separator'] : false,
		);

		// Get products
		$this->load->model('tool/image');
		$this->load->model('catalog/product');

		$total_limit = $pagination_limit * 10;

		if ($page_type == 'all') {
			$results = $this->model_catalog_product->getProducts(array('start' => 0, 'limit' => $total_limit));
		} elseif ($page_type == 'bestseller') {
			$results = $this->model_catalog_product->getBestSellerProducts($total_limit);
		} elseif ($page_type == 'coming') {
			$coming_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE date_available > NOW() LIMIT 0," . $total_limit);

			$results = array();
			foreach ($coming_product_query->rows as $coming_product) {
				$date_available = $coming_product['date_available'];
				$this->db->query("UPDATE " . DB_PREFIX . "product SET date_available = NOW() WHERE product_id = " . (int)$coming_product['product_id']);

				$product = $this->model_catalog_product->getProduct($coming_product['product_id']);
				if ($product) $results[] = $product;




                $this->db->query("UPDATE " . DB_PREFIX . "product SET date_available = '" . $date_available . "' WHERE product_id = " . (int)$coming_product['product_id']);
			}
		} elseif ($page_type == 'featured') {
			$product_ids = array();

			if (version_compare(VERSION, '2.0', '<') && $this->config->get('featured_product')) {
				$product_ids = explode(',', $this->config->get('featured_product'));
			} elseif (version_compare(VERSION, '2.0', '>=')) {
				$featured_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `code` = 'featured'" . (isset($this->request->get['module_id']) ? " AND module_id = " . (int)$this->request->get['module_id'] : ""));
				foreach ($featured_query->rows as $row) {
					$module_settings = (version_compare(VERSION, '2.1', '<')) ? unserialize($row['setting']) : json_decode($row['setting'], true);
					$product_ids = array_unique(array_merge($product_ids, $module_settings['product']));
				}
			}

			$results = array();
			foreach ($product_ids as $product_id) {
				$product = $this->model_catalog_product->getProduct($product_id);
				if ($product) $results[] = $product;
			}
		} elseif ($page_type == 'latest') {
			$results = $this->model_catalog_product->getLatestProducts($total_limit);
		} elseif ($page_type == 'popular') {
			$results = $this->model_catalog_product->getPopularProducts($total_limit);
		}

		// Set product data
		$product_total = min(count($results), $total_limit);

		if (!$product_total) {
			$data['text_error'] = $data['text_empty'];
			$data['continue'] = $this->url->link('common/home');

			$template = (file_exists(DIR_TEMPLATE . $theme . '/template/error/not_found.tpl')) ? $theme : 'default';
			$template_file = (version_compare(VERSION, '2.2', '<')) ? $template . '/template/error/not_found.tpl' : 'error/not_found';
		} else {
			$data['products'] = array();

			$name = array();
			$price = array();
			$rating = array();
			$model = array();

			foreach ($results as $key => $result) {
				$name[$key] = strtolower($result['name']);
				$price[$key] = $result['special'] ? $result['special'] : $result['price'];
				$rating[$key] = $result['rating'];
				$model[$key] = $result['model'];
				$viewed[$key] = $result['viewed'];
			}

			if ($filters['sort'] != 'default') {
				array_multisort(${$filters['sort']}, $filters['order'] == 'asc' ? SORT_ASC : SORT_DESC, $results);
			} elseif ($page_type == 'popular') {
				array_multisort($viewed, SORT_DESC, $results);
			}

			$results = array_slice($results, ($filters['page'] - 1) * $filters['limit'], $filters['limit']);

			foreach ($results as $result) {
				$options = $this->model_catalog_product->getProductOptions($result['product_id']);

                $otherImages = array();

                $images = $this->model_catalog_product->getProductOtherImages( $result['product_id']);

                foreach ($images as $i) {

                    $otherImages[] = array(

                        'thumb'=> $this->model_tool_image->resize($i['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'))
                    );

                }

				$product = $result;
				$product['add']			= $this->url->link(($options ? 'product/product' : 'checkout/cart'), 'product_id=' . $result['product_id']);
				$product['description']	= substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $description_limit) . '...';
				$product['href']		= $this->url->link('product/product', 'product_id=' . $result['product_id']);
				$product['options']		= $options;
				$product['price']		= (!$this->config->get('config_customer_price') || $this->customer->isLogged()) ? $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $currency) : false;
				$product['rating']		= ($this->config->get('config_review_status')) ? (int)$result['rating'] : false;
				$product['reviews']		= (version_compare(VERSION, '2.0', '<')) ? sprintf($data['text_reviews'], (int)$result['reviews']) : '';
				$product['special']		= ((float)$result['special']) ? $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $currency) : false;
				$product['tax']			= ($this->config->get('config_tax')) ? $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $currency) : false;
				$product['thumb']		= $this->model_tool_image->resize($result['image'] ? $result['image'] : $placeholder, $image_width, $image_height);
                $product['otherImages'] = $otherImages;

                $product['ratio'] = $result['ratio'];
                   $product['model'] = $result['model'];
                    $product['ratioScale'] = $result['ratioScale'];
                    $product['colours'] = $this->model_catalog_product->getProductColours($result['product_id']);
                    $product['eachPrice'] =  (!$this->config->get('config_customer_price') || $this->customer->isLogged()) ? $this->currency->format($this->tax->calculate($result['eachPrice'], $result['tax_class_id'], $this->config->get('config_tax')), $currency) : false;
                    $product['packPrice'] =  (!$this->config->get('config_customer_price') || $this->customer->isLogged()) ? $this->currency->format($this->tax->calculate($result['packPrice'], $result['tax_class_id'], $this->config->get('config_tax')), $currency) : false;
                    $product['specialEach'] =  (!$this->config->get('config_customer_price') || $this->customer->isLogged()) ? $this->currency->format($this->tax->calculate($result['specialEach'], $result['tax_class_id'], $this->config->get('config_tax')), $currency) : false;
                    $product['specialPack'] =  (!$this->config->get('config_customer_price') || $this->customer->isLogged()) ? $this->currency->format($this->tax->calculate($result['specialPack'], $result['tax_class_id'], $this->config->get('config_tax')), $currency) : false;
                $product[ 'packQty'] = array_sum(explode('-', $result['ratio']));

                $data['products'][] = $product;
			}

			// Set sort and order
			$data['sorts'] = array();
			$data['sorts'][] = array(
				'text'	=> $data['text_default'],
				'value' => '',
				'href'	=> $this->url->link($this->type . '/' . $this->name . '/' . $page_type, $url['limit']),
			);

			$sort_array = array(
				'name',
				'price',
				'rating',
				'model',
			);
			$order_array = array(
				'asc',
				'desc',
			);

			foreach ($sort_array as $sort) {
				if ($sort == 'rating' && !$this->config->get('config_review_status')) continue;
				foreach ($order_array as $order) {
					$data['sorts'][] = array(
						'text'	=> $data['text_' . $sort . '_' . $order],
						'value' => $sort . '-' . $order,
						'href'	=> $this->url->link($this->type . '/' . $this->name . '/' . $page_type, '&sort=' . $sort . '&order=' . $order . $url['limit']),
					);
				}
			}

			// Generate pagination
			$limit_array = array(
				$pagination_limit,
				$pagination_limit * 2,
				$pagination_limit * 3,
				$pagination_limit * 4,
				$pagination_limit * 5,
			);

			foreach ($limit_array as $limit) {
				$data['limits'][] = array(
					'text'  => $limit,
					'value' => $limit,
					'href'  => $this->url->link($this->type . '/' . $this->name . '/' . $page_type, $url['sort'] . $url['order'] . '&limit=' . $limit),
				);
			}

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $filters['page'];
			$pagination->limit = $filters['limit'];
			$pagination->text = $data['text_pagination'];
			$pagination->url = $this->url->link($this->type . '/' . $this->name . '/' . $page_type, $url['sort'] . $url['order'] . $url['limit'] . '&page={page}');

			$data['pagination'] = $pagination->render();
			$data['results'] = sprintf($data['text_pagination'], ($product_total) ? (($filters['page'] - 1) * $filters['limit']) + 1 : 0, ((($filters['page'] - 1) * $filters['limit']) > ($product_total - $filters['limit'])) ? $product_total : ((($filters['page'] - 1) * $filters['limit']) + $filters['limit']), $product_total, ceil($product_total / $filters['limit']));

			$data['display_price'] = (!$this->config->get('config_customer_price') || $this->customer->isLogged());
			$data['text_compare'] = sprintf($data['text_compare'], (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			$data['compare'] = $this->url->link('product/compare');

			$template = (file_exists(DIR_TEMPLATE . $theme . '/template/' . $this->type . '/' . $this->copy . '.tpl')) ? $theme : 'default';
			$template_file = (version_compare(VERSION, '2.2', '<')) ? $template . '/template/' . $this->type . '/' . $this->copy . '.tpl' : $this->type . '/' . $this->copy;
		}

		// Render
		$this->document->setTitle($data['heading_title']);

		if (version_compare(VERSION, '2.0', '<')) {
			if (version_compare(VERSION, '1.5.5', '>=')) {
				$this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');
			}
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header',
			);
			$this->data = $data;
			$this->template = $template_file;
			$this->response->setOutput($this->render());
		} else {
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view($template_file, $data));
		}
	}
}
?>