<?php
class ControllerModuleSpecial extends Controller {
	public function index($setting) {
		$this->load->language('module/special');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		$filter_data = array(
			'sort'  => 'pd.name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		$results = $this->model_catalog_product->getProductSpecials($filter_data);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}


                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                    $eachPrice = $this->currency->format($this->tax->calculate($result['eachPrice'], $result['tax_class_id'], $this->config->get('config_tax')));
                    $packPrice = $this->currency->format($this->tax->calculate($result['packPrice'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                    $eachPrice = false;
                    $packPrice = false;
                }

                if ((float)$result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                    $specialEach = $this->currency->format($this->tax->calculate($result['specialEach'], $result['tax_class_id'], $this->config->get('config_tax')));
                    $specialPack = $this->currency->format($this->tax->calculate($result['specialPack'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                    $specialEach = false;
                    $specialPack = false;
                }

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}

                $data['products'][] = array(
                    'product_id' => $result['product_id'],
                    'thumb' => $image,
                    'name' => $result['name'],
                    'ratio' => $result['ratio'],
                    'model' => $result['model'],
                    'ratioScale' => $result['ratioScale'],
                    'colours' => $this->model_catalog_product->getProductColours($result['product_id']),
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                    'price' => $price,
                    'eachPrice' => $eachPrice,
                    'packPrice' => $packPrice,
                    'special' => $special,
                    'specialEach' => $specialEach,
                    'specialPack' => $specialPack,
                    'tax' => $tax,
                    'rating' => $rating,
                    'href' => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url),
                    'packQty' => array_sum(explode('-', $result['ratio']))
                );
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/special.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/special.tpl', $data);
			} else {
				return $this->load->view('default/template/module/special.tpl', $data);
			}
		}
	}
}