<?php
class ControllerCommonLanguage extends Controller {

				protected function __smpRedirectByLang() {
					if( empty( $this->request->post['redirect'] ) )
						return;
				
					$path_info	= parse_url( $this->request->post['redirect'] );
				
					if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
						$connection = 'SSL';
					} else {
						$connection = 'NONSSL';
					}

					if( isset( $path_info['path'] ) ) {
						$languages	= array();
						
						$this->load->model('localisation/language');

						foreach( $this->model_localisation_language->getLanguages() as $lang ) {
							$languages[$lang['language_id']] = $lang['code'];
						}

						if( $languages ) {
							$url_info = parse_url( defined( HTTPS_SERVER ) ? HTTPS_SERVER : HTTP_SERVER );

							if( empty( $url_info['path'] ) ) {
								$url_info['path'] = '';
							}

							$language = $this->db->query("
								SELECT 
									* 
								FROM 
									`" . DB_PREFIX . "setting`
								WHERE 
									`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND 
									`" . ( version_compare( VERSION, '2.0.1.0', '<' ) ? 'group' : 'code' ) . "` = 'config' AND 
									`key` = 'config_language'"
							);
							
							if( $language->num_rows ) {
								$language = $language->row['value'];
							} else {
								$language = $this->config->get( 'config_language' );
							}
				
							foreach( $languages as $lang ) {
								$path = explode( '/', trim( $path_info['path'], '/' ) );
				
								if( $lang == $path[0] ) {
									unset( $path[0] );
									
									$path_info['path'] = $url_info['path'] . implode( '/', $path );

									break;
								}
							}
				
							$this->request->post['redirect']	= '';

							if( isset( $path_info['scheme'] ) && isset( $path_info['host'] ) ) {
								$this->request->post['redirect'] .= $path_info['scheme'] . '://' . $path_info['host'] . ( isset( $path_info['port'] ) ? ':' . $path_info['port'] : '' );
							}

							if( $language != $this->session->data['language'] ) {
								$path_info['path'] = $url_info['path'] . $this->session->data['language'] . '/' .
									mb_substr( $path_info['path'], mb_strlen( $url_info['path'], 'utf-8' ), mb_strlen( $path_info['path'], 'utf-8' ), 'utf-8' );
							}

							$this->request->post['redirect'] .= $path_info['path'];

							if( $this->config->get( 'smp_trans_urls_when_change_langs' ) ) {
								foreach( $this->url->getRewrites() as $rewrite ) {
									if( $rewrite instanceof ControllerCommonSeoMegaPackProUrl ) {
										$url_info	= parse_url( str_replace( '&amp;', '&', $this->request->post['redirect'] ) );
										$path		= parse_url( defined( HTTPS_SERVER ) ? HTTPS_SERVER : HTTP_SERVER );

										if( empty( $path['path'] ) )
											$path['path'] = '/';

										if( ! isset( $url_info['path'] ) )
											$url_info['path'] = '';

										if( strpos( $url_info['path'], $path['path'] ) === 0 )
											$url_info['path'] = substr( $url_info['path'], mb_strlen( $path['path'], 'utf-8' ) );

										$this->request->get['_route_'] = trim( $url_info['path'], '/' );

										$this->request->get['route'] = '';

										$this->config->set( 'config_language', $this->session->data['language'] );
										$this->config->set( 'config_language_id', array_search( $this->session->data['language'], $languages ) );
										$rewrite->__resetCache();
										$this->url->resetRewrites();
										$rewrite->_setConfigLang( $this->session->data['language'], array_search( $this->session->data['language'], $languages ) );
										//$rewrite->_setDetectLanguageByURL( false );
										$rewrite->index();

										$get = $this->request->get;

										if( ! empty( $get['route'] ) ) {										
											$params = array();

											foreach( $get as $k => $v )
												if( ! in_array( $k, array( 'route', '_route_' ) ) )
													$params[] = $k . '=' . urlencode( $v );

											$this->request->post['redirect'] = $this->url->link( $get['route'], implode( '&', $params ), $connection );
										} else {
											$this->request->post['redirect'] = $this->url->link( '', '', $connection );
										}

										break;
									}
								}
							}
						}
					}
					
					$this->response->redirect( $this->request->post[ 'redirect' ] );
				}
			
	public function index() {
		$this->load->language('common/language');

		$data['text_language'] = $this->language->get('text_language');

		$data['action'] = $this->url->link('common/language/language', '', $this->request->server['HTTPS']);

		$data['code'] = $this->session->data['language'];

		$this->load->model('localisation/language');

		$data['languages'] = array();

		$results = $this->model_localisation_language->getLanguages();

		foreach ($results as $result) {
			if ($result['status']) {
				$data['languages'][] = array(
					'name'  => $result['name'],
					'code'  => $result['code'],
					'image' => $result['image']
				);
			}
		}

		if (!isset($this->request->get['route'])) {
			$data['redirect'] = $this->url->link('common/home', '', isset( $connection ) ? $connection : 'SSL');
		} else {
			$url_data = $this->request->get;

			unset($url_data['_route_']);

			$route = $url_data['route'];

			unset($url_data['route']);

			$url = '';

			if ($url_data) {
				$url = '&' . urldecode(http_build_query($url_data, '', '&'));
			}

			$data['redirect'] = $this->url->link($route, $url, $this->request->server['HTTPS']);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/language.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/language.tpl', $data);
		} else {
			return $this->load->view('default/template/common/language.tpl', $data);
		}
	}

	public function language() {
		if (isset($this->request->post['code'])) {
			$this->session->data['language'] = $this->request->post['code'];

				$this->__smpRedirectByLang();
			
		}

		if (isset($this->request->post['redirect'])) {
			$this->response->redirect($this->request->post['redirect']);
		} else {
			$this->response->redirect($this->url->link('common/home'));
		}
	}
}