<?php

class ControllerCommonSeoUrl extends Controller {


				private $_seoMegaPackProUrl = NULL;
				
				private function _initSeoMegaPackProUrl() {
					if( $this->config->get( 'smp_is_install' ) ) {
						require_once VQMod::modCheck(realpath(DIR_SYSTEM . '../catalog/controller/common/seo_mega_pack_pro_url.php'));
					
						$this->_seoMegaPackProUrl = new ControllerCommonSeoMegaPackProUrl( $this->registry );
					}
					
					$config_language = $this->_seoMegaPackProUrl != NULL ? 
						$this->_seoMegaPackProUrl->getConfigLanguage() : $this->config->get('config_language');
				}
			
	public function index() {

				$this->_initSeoMegaPackProUrl();

				if( $this->_seoMegaPackProUrl !== NULL )
					return $this->_seoMegaPackProUrl->index();
			

		// Add rewrite to url class

		if ($this->config->get('config_seo_url')) {

			$this->url->addRewrite($this);

		}



		// Decode URL

		if (isset($this->request->get['_route_'])) {

			$parts = explode('/', $this->request->get['_route_']);



			// remove any empty arrays from trailing

			if (utf8_strlen(end($parts)) == 0) {

				array_pop($parts);

			}



			foreach ($parts as $part) {

				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");



				if ($query->num_rows) {

					$url = explode('=', $query->row['query']);



					if ($url[0] == 'product_id') {

						$this->request->get['product_id'] = $url[1];

					}



					if ($url[0] == 'category_id') {

						if (!isset($this->request->get['path'])) {

							$this->request->get['path'] = $url[1];

						} else {

							$this->request->get['path'] .= '_' . $url[1];

						}

					}



					if ($url[0] == 'manufacturer_id') {

						$this->request->get['manufacturer_id'] = $url[1];

					}



					if ($url[0] == 'information_id') {

						$this->request->get['information_id'] = $url[1];

					}

					

					if ($query->row['query'] && $url[0] != 'information_id' && $url[0] != 'manufacturer_id' && $url[0] != 'category_id' && $url[0] != 'product_id') {

						$this->request->get['route'] = $query->row['query'];

					}

				} else {

					$this->request->get['route'] = 'error/not_found';



					break;

				}

			}



			if (!isset($this->request->get['route'])) {

				if (isset($this->request->get['product_id'])) {

					$this->request->get['route'] = 'product/product';

				} elseif (isset($this->request->get['path'])) {

					$this->request->get['route'] = 'product/category';

				} elseif (isset($this->request->get['manufacturer_id'])) {

					$this->request->get['route'] = 'product/manufacturer/info';

				} elseif (isset($this->request->get['information_id'])) {

					$this->request->get['route'] = 'information/information';

				}

			}



			if (isset($this->request->get['route'])) {

				return new Action($this->request->get['route']);

			}

		}

	}



	public function rewrite($link) {

				$this->_initSeoMegaPackProUrl();
				
				if( $this->_seoMegaPackProUrl !== NULL )
					return $this->_seoMegaPackProUrl->rewrite( $link );
			

		$url_info = parse_url(str_replace('&amp;', '&', $link));

/*echo $link;

exit;

*/

		$url = '';



		$data = array();



		parse_str($url_info['query'], $data);



		foreach ($data as $key => $value) {

			if (isset($data['route'])) {

				if (($data['route'] == 'product/product' && $key == 'product_id') || (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id')) {

					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");



					if ($query->num_rows && $query->row['keyword']) {

						$url .= '/' . $query->row['keyword'];



						unset($data[$key]);

					}

				} elseif ($key == 'path') {

					$categories = explode('_', $value);



					foreach ($categories as $category) {

						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");



						if ($query->num_rows && $query->row['keyword']) {

							$url .= '/' . $query->row['keyword'];

						} else {

							$url = '';



							break;

						}

					}



					unset($data[$key]);

				}
				elseif(strpos($data['route'],'account') !== false || strpos($data['route'],'information') !== false || strpos($data['route'],'module') !== false){
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $data['route'] . "'");
                   if ($query->num_rows && $query->row['keyword']) {
						$url = $query->row['keyword'];
				   }
				}

			}

		}



		if ($url) {

			unset($data['route']);



			$query = '';



			if ($data) {

				foreach ($data as $key => $value) {

					$query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((string)$value);

				}



				if ($query) {

					$query = '?' . str_replace('&', '&amp;', trim($query, '&'));

				}

			}


			$url_final = $url_info['scheme'] . '' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
			//$url_final = strstr($url_final, 'index.php');
			$url_final = str_replace('index.php', '', $url_final);
			$url_final = str_replace('httpsdressdirect.co.uk', '', $url_final);
			return $url_final;

		} else {

			return $link;

		}

	}

}

