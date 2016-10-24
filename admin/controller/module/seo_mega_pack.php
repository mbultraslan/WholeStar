<?php
class ControllerModuleSeoMegaPack extends Controller {
	
	/**
	 * @var string
	 */
	private $extensions_dir	= NULL;
	
	private $cache_dir		= NULL;
	
	private $sitemaps_dir	= NULL;
	
	private $_url_alias		= NULL;
	
	public $data = array();
	
	/**
	 * Version
	 * 
	 * @var string 
	 */
	private $_version		= '2.0.2.5.5';
	
	/**
	 * Type of version
	 * Typ wersji (plus/lite)
	 * 
	 * @var string
	 */
	private $_versionType	= 'plus';
	
	/**
	 * Minimum version of supported extensions
	 * Minimalna wersja obsługiwanych rozszerzeń
	 * 
	 * @var string 
	 */
	private $_minExtVerion	= '2';
	
	////////////////////////////////////////////////////////////////////////////
	
	const TYPE_EXTENSION	= 'extension';
	
	const TYPE_MANAGER		= 'manager';
	
	////////////////////////////////////////////////////////////////////////////
	
	private static $_adminDir = NULL;
	
	public static function adminDir() {
		if( self::$_adminDir === NULL ) {
			self::$_adminDir = defined( 'SMP_ADMIN_DIR' ) ? SMP_ADMIN_DIR : 'admin';
		}
		
		return self::$_adminDir;
	}
	
	////////////////////////////////////////////////////////////////////////////
	
	/**
	 * Generate messages
	 * Wygeneruj wiadomości
	 */
	private function _messages() {
		/**
		 * Warnings
		 * Ostrzeżenia
		 */
		if( isset( $this->session->data['error'] ) ) {
			$this->data['smk_error_warning'] = $this->session->data['error'];

			unset( $this->session->data['error'] );
		} else if( empty( $this->data['smk_error_warning'] ) ) {
			$this->data['smk_error_warning'] = '';
		}

		/**
		 * Messages
		 * Wiadomości
		 */
		if( isset( $this->session->data['success'] ) ) {
			$this->data['success'] = $this->session->data['success'];

			unset( $this->session->data['success'] );
		} else if( empty( $this->data['success'] ) ) {
			$this->data['success'] = '';
		}
	}
	
	/**
	 * __construct()
	 * 
	 * @param type $registry
	 */
	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->extensions_dir	= DIR_SYSTEM . 'library/smk/extensions/';
		$this->sitemaps_dir		= substr_replace(DIR_SYSTEM, '/sitemaps/', -8);
		$this->cache_dir		= DIR_SYSTEM . '/cache/smp';
		
		if( defined( 'SMP_CRON' ) )
			return;
		
		// style
		$this->document->addStyle('view/smp/css/bootstrap.css');
		$this->document->addStyle('view/smp/css/style.css?v2');
		
		// set language package
		// ustaw paczkę językową
		$this->data = array_merge($this->data, $this->language->load('module/seo_mega_pack'));
		$this->data['smp_version'] = $this->_version;

		$this->document->setTitle($this->language->get('heading_title'));

		// Breadcrumbs
		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_module'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('module/seo_mega_pack', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		// check if the module is installed
		// sprawdź czy moduł został zainstalowany
		$this->data['is_installed'] = $this->installation_check();
		
		// configuration tabs
		// konfiguracja zakładek
		$this->data['tab_action_extensions']	= $this->url->link('module/seo_mega_pack', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['tab_action_manager']		= $this->url->link('module/seo_mega_pack/manager', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['tab_action_custom']		= $this->url->link('module/seo_mega_pack/custom', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['tab_action_redirects']		= $this->url->link('module/seo_mega_pack/redirects', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['tab_action_sitemap']		= $this->url->link('module/seo_mega_pack/sitemap', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['tab_action_extras']		= $this->url->link('module/seo_mega_pack/extras', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['tab_action_help']			= $this->url->link('module/seo_mega_pack/help', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['tab_action_settings']		= $this->url->link('module/seo_mega_pack/settings', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['tab_action_about']			= $this->url->link('module/seo_mega_pack/about', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['tab_action_cron']			= $this->url->link('module/seo_mega_pack/cron', 'token=' . $this->session->data['token'], 'SSL' );
		
		// link to 'back' button
		// link guzika 'cofnij'
		$this->data['back']		= $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token']	= $this->session->data['token'];
		$this->data['tab']		= explode( '/', $this->request->get['route'] );
		$this->data['tab']		= isset( $this->data['tab'][2] ) ? $this->data['tab'][2] : 'index';
	
		$this->data['token']	= $this->session->data['token'];
		
		// list of module extensions
		// lista rozszerzeń modułu
		$this->data['extensions']	= array();
		
		// check if the module is installed
		// sprawdź czy moduł jest zainstalowany
		if( $this->data['is_installed'] ) {
			// check if is newer version
			// sprawdź czy nowsza wersja
			if( version_compare( $this->_version, $this->config->get( 'smp_version' ), '>' ) || $this->_versionType != $this->config->get( 'smp_versionType' ) ) {
				// @since 1.2.1 - remove file 'seo_titles_cmi.php' and settings of it
				//				  usuń plik 'seo_titles_cmi.php' i ustawienia
				if( version_compare( $this->config->get( 'smp_version' ), '1.2.1', '<' ) && file_exists( $this->extensions_dir . 'seo_titles_cmi.php' ) ) {
					$this->load->model('setting/setting');

					$this->model_setting_setting->deleteSetting('smp_stcmi' );
					$this->model_setting_setting->deleteSetting('smp_stcmi_is_install' );

					unlink( $this->extensions_dir . 'seo_titles_cmi.php' );
				}

				// @since 1.3.1 - remove CRON cache
				//				  usuń cache CRONa
				if( version_compare( $this->config->get( 'smp_version' ), '1.3.1', '<' ) && file_exists( DIR_CACHE . 'smp_cron.db' ) ) {
					unlink( DIR_CACHE . 'smp_cron.db' );
				}

				// @since 1.2.2 - clear cache
				//				  wyczyść cache
				$this->_clearCache( false );

				// @since 1.2.8 - update SMK
				//				  aktualizuj SMK
				$this->__update();

				$this->load->model('setting/setting');

				// zaktualizuj nr wersji w bazie danych
				$this->model_setting_setting->editSetting('smp_version', array(
					'smp_version' => $this->_version
				));
				
				// @since 1.3.9 - typ wersji (plus/lite)
				$this->model_setting_setting->editSetting('smp_versionType', array(
					'smp_versionType' => $this->_versionType
				));
				
				// @since 2.0.0.4
				$this->model_setting_setting->deleteSetting( 'smp_conn_versions' );
				$this->model_setting_setting->deleteSetting( 'smp_conn_integrations' );
		
				/**
				 * @since 2.0.1.0
				 */
				if( version_compare( $this->config->get( 'smp_version' ), '2.0.1.0', '<' ) ) {
					/* @var $settings array */
					$settings = $this->model_setting_setting->getSetting( 'smp' );
					
					if( isset( $settings['smp_meta']['google-site-verification'] ) ) {
						/* @var $tmp array */
						$tmp = array();
						
						foreach( $this->stores() as $store ) {
							$tmp[$store['store_id']] = $settings['smp_meta']['google-site-verification'];
						}
						
						$settings['smp_meta'] = $tmp;
					}
					
					/* @var $tmp array */
					$tmp = array( 'smp_google_analytics', 'smp_facebook_open_graph', 'smp_twitter_cart', 'smp_twitter_creator', 'smp_twitter_site', 'smp_googleplus_metadata' );
					
					foreach( $tmp as $tmp2 ) {
						if( isset( $settings[$tmp2] ) ) {
							/* @var $tmp3 array */
							$tmp3 = array();

							foreach( $this->stores() as $store ) {
								$tmp3[$store['store_id']] = $settings['smp_twitter_cart'];
							}

							$settings[$tmp2] = $tmp3;
						}
					}
					
					if( isset( $settings['smp_extras'] ) ) {
						/* @var $tmp array */
						$tmp = array();
						
						foreach( $this->stores() as $store ) {
							$tmp[$store['store_id']] = $settings['smp_extras'];
						}
						
						$settings['smp_extras'] = $tmp;
					}
					
					$this->model_setting_setting->editSetting('smp', $settings);
				}
				
				// informacja dla metody installprogress() że trwa aktualizacja
				$this->session->data['smp_go_to_update'] = true;
				
				// przekieruj do aktualizacji rozszerzeń
				$this->response->redirect($this->url->link('module/seo_mega_pack/installprogress', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			$this->data['extensions'] = $this->getExtensions( false, self::TYPE_EXTENSION );

			// jeśli użytkownik zatwierdził formularz zapisz ustawienia
			if( $this->request->server['REQUEST_METHOD'] == 'POST' && isset( $this->request->post['save_parameters'] ) ) {
				$this->_saveParameters();
			}
		}
		
		$this->_connector();
		
		$this->_messages();
	}
	
	private function _connector() {
		$this->load->model('setting/setting');
		
		$url = sprintf( 'http://ocdemo.com/seo_mega_pack/connector.php?version=%s&version_type=%s&action=', $this->_version, $this->_versionType );
		
		$versions = (array) $this->config->get( 'smp_conn_versions' );
		$integrations = (array) $this->config->get( 'smp_conn_integrations' );
		
		if( ! $versions || $versions['time'] < time() - 60 * 60 * 23 ) {
			ob_start();			
			$data = json_decode( file_get_contents( $url . 'versions' ), true );			
			ob_end_clean();
			
			$versions['time']	= time();
			$versions['data']	= $data;
			$versions['readed'] = false;
			
			$this->model_setting_setting->editSetting('smp_conn_versions', array(
				'smp_conn_versions' => $versions
			));
			
			$this->config->set( 'smp_conn_versions', $versions );
		}
		
		if( ! $integrations || $integrations['time'] < time() - 60 * 60 * 25 ) {
			ob_start();			
			$data = json_decode( file_get_contents( $url . 'integrations' ), true );			
			ob_end_clean();
			
			$integrations['time'] = time();
			$integrations['data'] = $data;
			
			$this->model_setting_setting->editSetting('smp_conn_integrations', array(
				'smp_conn_integrations' => $integrations
			));
			
			$this->config->set( 'smp_conn_integrations', $integrations );
		}
		
		if( empty( $versions['readed'] ) && ! empty( $versions['data']['latest_version'] ) && version_compare( $this->_version, $versions['data']['latest_version'], '<' ) ) {
			$this->data['available_new_version_of_smk'] = sprintf( 
				$this->language->get( 'text_available_new_version_of_smk' ), 
				$versions['data']['latest_version'], 
				$this->url->link('module/seo_mega_pack/about', 'token=' . $this->session->data['token'], 'SSL') 
			);
			$this->data['available_new_version_of_smk_mark_as_readed'] = $this->url->link('module/seo_mega_pack/mark_as_readed', 'token=' . $this->session->data['token'] . '&key=smp_conn_versions', 'SSL') ;
		}
	}
	
	public function mark_as_readed() {
		if( isset( $this->request->get['key'] ) && NULL != ( $data = $this->config->get( $this->request->get['key'] ) ) ) {
			$data['readed'] = true;
			
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting($this->request->get['key'], array(
				$this->request->get['key'] => $data
			));
		}
	}
	
	/**
	 * Get a list of available extensions
	 * Pobierz listę dostępnych rozszerzeń
	 * 
	 * @param bool $hide
	 * @param string $type
	 * @param bool $install
	 * @return array
	 */
	public function getExtensions( $hide = false, $type = self::TYPE_EXTENSION, $install = false ) {
		$extension_dir	= @ opendir( $this->extensions_dir );
		$extensions		= array(
			'extension'		=> array(),
			'manager'		=> array()
		);
		$shortNames		= array();

		// find all available extensions
		// znajdź wszystkie dostępne rozszerzenia
		while( false !== ( $extension = readdir( $extension_dir ) ) ) {
			if( $extension == '.' || $extension == '..' || strpos( $extension, 'abstract_' ) === 0 || strpos( $extension, 'parse_url_' ) === 0 || ! preg_match( '/\.php$/', $extension ) ) continue;

			require_once VQMod::modCheck(realpath($this->extensions_dir . '/' . $extension));
			
			// check type
			// sprawdź typ
			switch( true ) {
				case strpos( $extension, 'manager_' ) === 0 : $t = 'manager'; break;
				default: $t = 'extension';
			}

			$extension	= str_replace( '.php', '', $extension );
			$classify	= self::classify( $extension );
			$class		= 'SeoMegaPack_' . $classify;
			
			$vars		= get_class_vars( $class );
			$version	= NULL;
			
			if( ! empty( $vars['_version'] ) ) {
				$version = $vars['_version'];
			} else {
				$extensions[$t][$classify] = new $class( $this );
				$version = $extensions[$t][$classify]->version();
			}
			
			// check if the extension has a minimum required version
			// sprawdź czy rozszerzenie ma minimalną wymaganą wersję
			if( version_compare( $this->_minExtVerion, $version, '>' ) ) {
				if( empty( $this->data['smk_error_warning'] ) )
					$this->data['smk_error_warning'] = 'Found incompatible extensions - you must download the latest versions.';
				
				if( isset( $extensions[$t][$classify] ) ) {
					unset( $extensions[$t][$classify] );
					
					$this->data['smk_error_warning'] .= '<br />' . $classify;
				}
					
				continue;
			}
			
			if( ! isset( $extensions[$t][$classify] ) )
				$extensions[$t][$classify] = new $class( $this );

			// check if is an extension
			// sprawdź czy to rozszerzenie
			if( $t == self::TYPE_EXTENSION ) {			
				if( isset( $shortNames[$extensions[$t][$classify]->shortName()] ) ) {
					throw new Exception( 'Name ' . $extensions[$t][$classify]->shortName() . ' is not unique' );
				}

				$shortNames[$extensions[$t][$classify]->shortName()] = true;
				
				if( ! $install ) {
					$insProgress = NULL;

					// check if extension has been installed
					// sprawdź czy rozszerzenie zostało zainstalowane
					if( ! $this->config->get( 'smp_' . $extensions[$t][$classify]->shortName() . '_is_install' ) ) {
						$insProgress = $extensions[$t][$classify]->install();
					} else
					// check version of extension
					// sprawdź wersję rozszerzenia
					if( version_compare( $extensions[$t][$classify]->version(), $this->config->get( 'smp_' . $extensions[$t][$classify]->shortName() . '_is_install' ), '>' ) ) {
						$insProgress = $extensions[$t][$classify]->update();
					}

					if( $insProgress === false ) {
						$idx = 0;

						do {
							$ret = $extensions[$t][$classify]->installprogress( $idx );
							$idx++;
						} while( $ret !== NULL );
					}
				}

				if( ! $hide && $extensions[$t][$classify]->hide() )
					unset( $extensions[$t][$classify] );
			}
		}

		closedir( $extension_dir );
		
		// get type
		// pobierz typ
		$extensions = $extensions[$type];

		// sorting extensions
		// sortowanie rozszerzeń
		uasort( $extensions, array( $this, '_sortExtensions' ) );
		
		return $extensions;
	}
	
	/**
	 * Internal function for sorting extensions
	 * Wewnętrzna funkcja do sortowania rozszerzeń
	 * 
	 * @param SeoMegaPack_AbstractGenerator $a
	 * @param SeoMegaPack_AbstractGenerator $b
	 * @return int
	 */
	public function _sortExtensions( $a, $b ) {
		if( $a->sort() == $b->sort() )
			return 0;
		else if( $a->sort() < $b->sort() )
			return -1;
		else
			return 1;
	}
	
	/**
	 * Internal function for sorting sitemaps
	 * Wewnętrzna funkcja do sortowania map stron
	 * 
	 * @param SeoMegaPack_AbstractGenerator $a
	 * @param SeoMegaPack_AbstractGenerator $b
	 * @return int
	 */
	public function _sortSitemaps( $a, $b ) {
		if( $a['name'] == $b['name'] )
			return 0;
		else if( $a['name'] < $b['name'] )
			return -1;
		else
			return 1;
	}
	
	/**
	 * Save the settings extensions
	 * Zapisz ustawienia rozszerzeń
	 */
	private function _saveParameters() {
		if( ! empty( $this->request->post['extensions'] ) ) {
			$this->load->model('setting/setting');
			
			foreach( $this->request->post['extensions'] as $extension => $parameters ) {
				$class = self::classify($extension);
				
				if( ! isset( $this->data['extensions'][$class] ) ) continue;
				
				$parameters = $this->data['extensions'][$class]->parseParameters( $parameters );
				
				if( is_array( $parameters ) || $this->config->get( 'smp_' . $this->data['extensions'][$class]->shortName() . '_params' ) != $parameters ) {
					$this->model_setting_setting->editSetting('smp_' . $this->data['extensions'][$class]->shortName(), array(
						'smp_' . $this->data['extensions'][$class]->shortName() . '_params' => $parameters
					));
					
					$this->config->set('smp_' . $this->data['extensions'][$class]->shortName() . '_params', $parameters);
				}
			}
			
			$this->session->data['success'] = $this->language->get('success_modified');
		}
	}
	
	/**
	 * @param string $word
	 * @return string
	 */
    public static function classify( $word ) {
		$word = preg_replace('/[$]/', '', $word);
		
		return preg_replace_callback('~(_?)([-_])([\w])~', array( 'ControllerModuleSeoMegaPack', 'classifyCallback' ), ucfirst(strtolower($word)));
    }
	
	/**
	 * @param array $matches
	 * @return string
	 */
    public static function classifyCallback($matches) {
        return $matches[1] . strtoupper($matches[3]);
    }

	/**
	 * Lista dostępnych rozszerzeń
	 */
	public function index() {
		// akcja dla formularza
		$this->data['action'] = $this->url->link('module/seo_mega_pack', 'token=' . $this->session->data['token'], 'SSL');
		
		// pogrupuj rozszerzenia
		$extensions	= array();
		
		foreach( $this->data['extensions'] as $key => $value ) {
			$extensions[$value->group()][$key] = $value;
		}
		
		$this->data['extensions'] = $extensions;
				
		// ustawienia szablonu
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		
		$this->data['url_generate'] = $this->url->link("module/seo_mega_pack/generate", "", "SSL") . "&token=" . $this->session->data['token'];
		$this->data['url_clear'] = $this->url->link("module/seo_mega_pack/clear", "", "SSL") . "&token=" . $this->session->data['token'];
			
		$this->response->setOutput( $this->load->view('module/seo_mega_pack.tpl', $this->data) );
	}
	
	/**
	 * Sprawdź czy słowo kluczowe w URLu jest unikalne
	 * 
	 * @param string $keyword
	 * @param int $skip_id
	 * @return [0,1,-1]
	 */
	private function _keywordIsUnique( $keyword, $skip_id = NULL ) {
		if( $this->_url_alias === NULL ) {			
			$this->_url_alias = array();
			
			foreach( $this->db->query( "SELECT * FROM " . DB_PREFIX . "url_alias" )->rows as $item ) {
				$this->_url_alias[$item['keyword']] = array(
					'query'				=> $item['query'],
					'smp_language_id'	=> $item['smp_language_id'],
					'url_alias_id'		=> $item['url_alias_id']
				);
			}
		}
		
		if( isset( $this->_url_alias[$keyword] ) ) {
			if( $skip_id === NULL || $this->_url_alias[$keyword]['url_alias_id'] != $skip_id )
				return false;
		}
		
		return true;
	}
	
	private function _clearIfOn( $str ) {
		require_once VQMod::modCheck(realpath(DIR_APPLICATION . '../catalog/controller/common/seo_mega_pack_pro_url.php'));
		
		return ControllerCommonSeoMegaPackProUrl::_clear( $str, $this->config->get( 'smp_clear_on' ) );
	}
	
	private function _createKeyword( $name ) {
		$keyword = new stdClass();
		
		$keyword->ext	= '';
		$keyword->name	= $name;
		
		if( mb_strpos($name, '.', 0, 'utf-8') !== false ) {
			$keyword->ext	= mb_substr($name, mb_strrpos($name, '.', 0, 'utf-8'), strlen($name), 'utf-8');
			$keyword->name	= mb_substr($name, 0, mb_strrpos($name, '.', 0, 'utf-8'), 'utf-8');
		}
		
		$keyword->name = $this->_clearIfOn( $keyword->name );
		
		return $keyword;
	}
	
	/**
	 * Create a unique URL based on the name
	 * Utwórz unikalny URL na podstawie nazwy
	 * 
	 * @param string $name
	 * @param int $skip_id
	 * @param string $query
	 * @return string
	 */
	public function _createUniqueKeyword( $name, $skip_id = NULL, $query = NULL ) {
		$nKeyword	= 0;
		
		$keyword = $this->_createKeyword( $name );
		
		if( $keyword->name != $name )
			$name = $keyword->name;
		
		$ext = $keyword->ext;
		
		do {
			$keyword	= $name . ( $nKeyword ? '-' . $nKeyword : '' ) . $ext;
			$isUnique	= $this->_keywordIsUnique( $keyword, $skip_id );
			
			if( ! $isUnique ) {
				if( $query !== NULL && $this->_url_alias[$keyword]['query'] == $query )
					$isUnique = true;
			}
				
			$nKeyword++;
		} while( ! $isUnique );
		
		return $keyword;
	}
	
	/**
	 * Save redirect
	 * Zapisz przekierowanie
	 */
	public function redirects_save() {
		$id				= isset( $this->request->get['id'] ) ? (int) $this->request->get['id'] : NULL;
		$broken_link	= isset( $this->request->get['broken_link'] ) ? $this->request->get['broken_link'] : NULL;
		$new_link		= isset( $this->request->get['new_link'] ) ? $this->request->get['new_link'] : NULL;
		
		if( $id && $broken_link && $new_link ) {
			$this->db->query("
				UPDATE
					" . DB_PREFIX . "redirects_smp
				SET
					broken_link='" . $this->db->escape( $broken_link ) . "',
					new_link='" . $this->db->escape( $new_link ) . "'
				WHERE
					redirects_id = " . $id . "
			");
		} else if( $id ) {
			$this->db->query('DELETE FROM ' . DB_PREFIX . 'redirects_smp WHERE redirects_id=' . $id );
		}
	}
	
	/**
	 * Add URL alias
	 * Dodaj alias URL
	 * 
	 * @return void
	 */
	public function redirects_insert() {
		$broken_link	= isset( $this->request->post['broken_link'] ) ? $this->request->post['broken_link'] : NULL;
		$new_link		= isset( $this->request->post['new_link'] ) ? $this->request->post['new_link'] : NULL;
		
		if( ! $broken_link || ! $new_link )
			return;
		
		$broken_link	= parse_url( $broken_link );
		$new_link		= parse_url( $new_link );
		
		if( empty( $broken_link['path'] ) ) {
			echo '0|Invalid broken url';
		} else if( empty( $new_link['path'] ) ) {
			echo '0|Invalid new url';
		} else {
			$broken_link = 
				( empty( $broken_link['scheme'] ) ? '' : $broken_link['scheme'] . '://' ) .
				( empty( $broken_link['host'] ) ? '' : $broken_link['host'] ) .
				( strpos( $broken_link['path'], '/' ) === 0 ? '' : '/' ) .
				$broken_link['path'];
			$new_link = 
				( empty( $new_link['scheme'] ) ? '' : $new_link['scheme'] . '://' ) .
				( empty( $new_link['host'] ) ? '' : $new_link['host'] ) .
				( strpos( $new_link['path'], '/' ) === 0 ? '' : '/' ) .
				$new_link['path'];
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "redirects_smp WHERE broken_link='" . $this->db->escape( $broken_link ) . "'" );
			
			$this->db->query("
				INSERT INTO
					" . DB_PREFIX . "redirects_smp
				SET
					broken_link='" . $this->db->escape( $broken_link ) . "',
					new_link='" . $this->db->escape( $new_link ) . "'
				");
			
			echo '1';
		}
	}
	
	public function invalid_urls_remove() {
		$id				= isset( $this->request->get['id'] ) ? (int) $this->request->get['id'] : NULL;
		
		if( $id ) {
			$this->db->query('DELETE FROM ' . DB_PREFIX . 'failed_url_smp WHERE failed_url_id=' . $id );
		}
	}
	
	public function invalid_urls_clear() {
		$this->db->query('DELETE FROM ' . DB_PREFIX . 'failed_url_smp' );
		
		$this->response->redirect($this->url->link('module/seo_mega_pack/invalid_urls', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	/**
	 * Save URL alias
	 * Zapisz alias URL
	 */
	public function custom_save() {
	//	$language_id	= isset( $this->request->get['lang'] ) ? (int) $this->request->get['lang'] : (int) $this->config->get('config_language_id');
		$id				= isset( $this->request->get['id'] ) ? (int) $this->request->get['id'] : NULL;
		$url			= isset( $this->request->get['url'] ) ? $this->request->get['url'] : NULL;
		$seo_url		= isset( $this->request->get['seo_url'] ) ? $this->request->get['seo_url'] : NULL;
		
		if( $id && $url && $seo_url ) {
			$this->db->query("
				UPDATE
					" . DB_PREFIX . "url_alias
				SET
					keyword='" . $this->db->escape( $this->_createUniqueKeyword( $seo_url, $id ) ) . "',
					query='" . $this->db->escape( $url ) . "'
				WHERE
					url_alias_id = " . $id . "
			");
		} else if( $id ) {
			$this->db->query('DELETE FROM ' . DB_PREFIX . 'url_alias WHERE url_alias_id=' . $id );
		}
	}
	
	/**
	 * Add URL alias
	 * Dodaj alias URL
	 * 
	 * @return void
	 */
	public function custom_insert() {
		$language_id	= isset( $this->request->get['lang'] ) ? (int) $this->request->get['lang'] : (int) $this->config->get('config_language_id');
		$url			= isset( $this->request->post['url'] ) ? $this->request->post['url'] : NULL;
		$seo_url		= isset( $this->request->post['seo_url'] ) ? $this->request->post['seo_url'] : NULL;
		
		if( ! $url || ! $seo_url )
			return;
		
		$url		= parse_url( trim( $url, ' /' ) );
		$seo_url	= parse_url( trim( $seo_url, ' /' ) );
		$regex		= '#^[a-z0-9/:\-\._=]+$#';
		
		if( empty( $url['path'] ) || ! preg_match( $regex, $url['path'] ) ) {
			echo '0|Invalid url';
		} else if( empty( $seo_url['path'] ) ) {
			echo '0|Invalid seo url';
		} else {
			$url		= trim( $url['path'], ' /' );
			$seo_url	= trim( $seo_url['path'], ' /' );
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query='" . $this->db->escape( $url ) . "' AND ( smp_language_id IS NULL OR smp_language_id=" . $language_id . ")" );
			
			$this->db->query("
				INSERT INTO
					" . DB_PREFIX . "url_alias
				SET
					keyword='" . $this->db->escape( $this->_createUniqueKeyword( $seo_url ) ) . "',
					query='" . $this->db->escape( $url ) . "',
					smp_language_id=" . $language_id . "
				");
			
			echo '1';
		}
	}
	
	/**
	 * Menadżer SEO - zapisz ustawienia
	 * 
	 * @return void
	 */
	public function manager_save() {
		$managers		= $this->getExtensions( true, self::TYPE_MANAGER );
		$types			= array_keys( $managers );
		
		$type			= isset( $this->request->get['type'] ) ? $this->request->get['type'] : NULL;
		$language_id	= isset( $this->request->get['lang'] ) ? $this->request->get['lang'] : (int) $this->config->get('config_language_id');
		$id				= isset( $this->request->get['id'] ) ? $this->request->get['id'] : NULL;
		
		if( ! $id ) return;
		
		// zapisz zmiany
		if( isset( $managers[$type] ) && $managers[$type]->installed() )
			$managers[$type]->save( $this->request->post, $id, $language_id );
	}
	
	/**
	 * Menadżer SEO
	 */
	public function manager() {
		$managers					= $this->getExtensions( true, self::TYPE_MANAGER );
		$types						= array_keys( $managers );
		
		$this->data['type']			= isset( $this->request->get['type'] ) ? $this->request->get['type'] : NULL;
		$this->data['language_id']	= isset( $this->request->get['lang'] ) ? $this->request->get['lang'] : (int) $this->config->get('config_language_id');
		$this->data['page']			= isset( $this->request->get['page'] ) ? $this->request->get['page'] : 1;
		$this->data['managers']		= $managers;
		
		if( $this->data['page'] < 1 )
			$this->data['page'] = 1;
		
		if( ! isset( $managers[$this->data['type']] ) || ! $managers[$this->data['type']]->installed() )
			$this->data['type'] = $types[0];
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if( $this->request->server['REQUEST_METHOD'] == 'POST' ) {
			if( ! empty( $this->request->post['clear_filter'] ) ) {
				unset( $this->session->data['smp_manager_filter'][$this->data['type']] );
			} else {
				foreach( $this->request->post as $key => $value ) {
					if( strpos( $key, 'filter_' ) !== 0 ) continue;

					$this->session->data['smp_manager_filter'][$this->data['type']][$key] = $value;
				}
			}
		}
		
		// zapytanie do bazy 
		$query	= $managers[$this->data['type']]->queryCount( false )->getQuery( $this->data, $this->session->data );
		$queryC = $managers[$this->data['type']]->queryCount( true )->getQuery( $this->data, $this->session->data );
		
		/**
		 * Lista dostępnych filtrów
		 */
		$this->data['filter_name']				= '';
		$this->data['filter_seo_url']			= '';
		$this->data['filter_seo_title']			= '';
		$this->data['filter_seo_h1_title']		= '';
		$this->data['filter_meta_keyword']		= '';
		$this->data['filter_meta_description']	= '';
		$this->data['filter_tag']				= '';
		$this->data['has_filter']				= false;
		
		// ustawienia filtrów
		if( isset( $this->session->data['smp_manager_filter'][$this->data['type']] ) ) {
			foreach( $this->session->data['smp_manager_filter'][$this->data['type']] as $key => $value ) {
				$this->data[$key] = $value;
				
				if( $value !== '' )
					$this->data['has_filter'] = true;
			}
		}
		
		/**
		 * Paginacja
		 */
		$pagination = new Pagination();
		$pagination->total	= $this->db->query( 'SELECT COUNT(*) AS num FROM(' . $queryC . ') AS tmp')->row['num'];
		$pagination->page	= $this->data['page'];
		$pagination->limit	= $this->config->get('config_limit_admin');
		$pagination->text	= $this->language->get('text_pagination');
		$pagination->url	= $this->url->link('module/seo_mega_pack/manager', 'token=' . $this->session->data['token'] . '&type=' . $this->data['type'] . '&lang=' . $this->data['language_id'] . '&page={page}', 'SSL');
		
		$query .= 'LIMIT ' . ( ( $this->data['page'] - 1 ) * $pagination->limit ) . ',' . $pagination->limit;
		
		$this->data['pagination']	= $pagination->render();
		$this->data['items']		= $managers[$this->data['type']]->addUrlAlias( $this->data, $this->db->query( $query )->rows );
		$this->data['token']		= $this->session->data['token'];
		
		// ustawienia szablonu
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		
		$this->response->setOutput( $this->load->view('module/seo_mega_pack_manager.tpl', $this->data) );
	}
	
	/**
	 * Autouzupełnianie
	 */
	public function autocomplete() {
		$type		= $this->request->get['type'];
		$name		= $this->request->get['name'];
		$filter		= $this->request->get['filter'];
		$lang_id	= $this->request->get['lang'];
		$json		= array();
		
		/**
		 * Sprawdź typ
		 */
		switch( $type ) {
			// produkt
			case 'ManagerProduct' :
			case 'product' : {
				$query = '
					SELECT
						pd.name AS name,
						pd.product_id AS id,
						pd.meta_title AS seo_title,
						pd.smp_h1_title AS seo_h1_title,
						pd.meta_keyword AS meta_keyword,
						pd.meta_description AS meta_description,
						pd.tag AS tag,
						ua.keyword AS seo_url
					FROM
						' . DB_PREFIX . 'product_description pd
					LEFT JOIN
						' . DB_PREFIX . 'url_alias ua
					ON
						ua.query = CONCAT( "product_id=", pd.product_id ) AND ( smp_language_id = ' . $lang_id . ' OR smp_language_id IS NULL )
					WHERE
						pd.language_id = ' . $lang_id . '
				';
				
				break;
			}
			// kategoria
			case 'ManagerCategory' :
			case 'category' : {
				$query = '
					SELECT
						cd.name AS name,
						cd.category_id AS id,
						cd.meta_title AS seo_title,
						cd.smp_h1_title AS seo_h1_title,
						cd.meta_keyword AS meta_keyword,
						cd.meta_description AS meta_description,
						cd.tag AS tag,
						ua.keyword AS seo_url
					FROM
						' . DB_PREFIX . 'category_description AS cd
					LEFT JOIN
						' . DB_PREFIX . 'url_alias ua
					ON
						ua.query = CONCAT( "category_id=", cd.category_id ) AND ( smp_language_id = ' . $lang_id . ' OR smp_language_id IS NULL )
					WHERE
						cd.language_id = ' . $lang_id . '
				';
				
				break;
			}
			// producent
			case 'ManagerManufacturer' :
			case 'manufacturer' : {
				$query = '
					SELECT
						m.name AS name,
						m.manufacturer_id AS id,
						ms.meta_title AS seo_title,
						ms.smp_h1_title AS seo_h1_title,
						ms.meta_keyword AS meta_keyword,
						ms.meta_description AS meta_description,
						ms.tag AS tag,
						ua.keyword AS seo_url
					FROM
						' . DB_PREFIX . 'manufacturer AS m
					LEFT JOIN
						' . DB_PREFIX . 'url_alias ua
					ON
						ua.query = CONCAT( "manufacturer_id=", m.manufacturer_id ) AND ( smp_language_id = ' . $lang_id . ' OR smp_language_id IS NULL )
					LEFT JOIN
						' . DB_PREFIX . 'manufacturer_smp AS ms
					ON
						ms.manufacturer_id = m.manufacturer_id AND ms.language_id = ' . $lang_id . '
				';
				
				break;
			}
			// CMS
			case 'ManagerInformation' :
			case 'information' : {
				$query = '
					SELECT
						id.title AS name,
						id.information_id AS id,
						id.meta_title AS seo_title,
						id.smp_h1_title AS seo_h1_title,
						id.meta_keyword AS meta_keyword,
						id.meta_description AS meta_description,
						id.tag AS tag,
						ua.keyword AS seo_url
					FROM
						' . DB_PREFIX . 'information_description AS id
					LEFT JOIN
						' . DB_PREFIX . 'url_alias ua
					ON
						ua.query = CONCAT( "information_id=", id.information_id ) AND ( smp_language_id = ' . $lang_id . ' OR smp_language_id IS NULL )
					WHERE
						id.language_id = ' . $lang_id . '
				';
				
				break;
			}
			// Alias URL
			case 'alias' : {
				$query = '
					SELECT
						ua.query AS url,
						ua.url_alias_id AS name,
						ua.url_alias_id AS id,
						ua.keyword AS seo_url
					FROM
						' . DB_PREFIX . 'url_alias AS ua
					WHERE
						( ua.smp_language_id = ' . $lang_id . ' OR ua.smp_language_id IS NULL ) AND
						ua.query NOT REGEXP "^(product_id|category_id|information_id|manufacturer_id)="
				';
				
				break;
			}
		}
		
		if( isset( $query ) ) {
			$query .= ' HAVING LOWER(' . $name . ") LIKE '%" . $this->db->escape( mb_strtolower( $filter, 'utf-8' ) ) . "%'";
			$query .= ' ORDER BY name';
			$query .= ' LIMIT 20';

			foreach( $this->db->query( $query )->rows as $item ) {
				$json[] = array(
					'id'	=> $item['id'],
					'name'	=> strip_tags(html_entity_decode($item[$name], ENT_QUOTES, 'UTF-8'))
				);
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	private function _setFilters( $key, $filters ) {
		if( $this->request->server['REQUEST_METHOD'] == 'POST' ) {
			if( ! empty( $this->request->post['clear_filter'] ) ) {
				unset( $this->session->data[$key] );
			} else {
				foreach( $this->request->post as $key2 => $value ) {
					if( strpos( $key2, 'filter_' ) !== 0 ) continue;

					$this->session->data[$key][$key2] = $value;
				}
			}
		}
		
		/**
		 * Filters
		 */
		foreach( $filters as $k ) {
			$this->data[$k] = '';
		}
		
		$this->data['has_filter']		= false;
		
		if( isset( $this->session->data[$key] ) ) {
			foreach( $this->session->data[$key] as $key => $value ) {
				$this->data[$key] = $value;
				
				if( $value !== '' )
					$this->data['has_filter'] = true;
			}
		}
	}
	
	/**
	 * Dostosuj przekierowania
	 */
	public function redirects() {
		$this->data['page']	= isset( $this->request->get['page'] ) ? $this->request->get['page'] : 1;
		
		if( $this->data['page'] < 1 )
			$this->data['page'] = 1;
		
		/**
		 * Set filters
		 */
		$this->_setFilters( 'smp_redirects_filter', array(
			'filter_broken_link', 'filter_new_link'
		));
		
		$this->data['post_broken_link'] = isset( $this->request->post['post_broken_link'] ) ? $this->request->post['post_broken_link'] : '';
		
		/**
		 * Query
		 */
		$query = '
			SELECT
				redirects_id AS id,
				broken_link,
				new_link
			FROM
				' . DB_PREFIX . 'redirects_smp
			WHERE
				1=1
		';
		
		if( ! empty( $this->data['filter_broken_link'] ) )
			$query .= " AND broken_link LIKE '%" . $this->db->escape( $this->data['filter_broken_link'] ) . "%'";
		
		if( ! empty( $this->data['filter_new_link'] ) )
			$query .= " AND new_link LIKE '%" . $this->db->escape( $this->data['filter_new_link'] ) . "%'";
		
		/**
		 * Paginacja
		 */
		$pagination = new Pagination();
		$pagination->total	= $this->db->query( 'SELECT COUNT(*) AS num FROM(' . $query . ') AS tmp')->row['num'];
		$pagination->page	= $this->data['page'];
		$pagination->limit	= $this->config->get('config_limit_admin');
		$pagination->text	= $this->language->get('text_pagination');
		$pagination->url	= $this->url->link('module/seo_mega_pack/redirects', 'token=' . $this->session->data['token']. '&page={page}', 'SSL');
		
		$query .= ' ORDER BY redirects_id DESC';
		$query .= ' LIMIT ' . ( ( $this->data['page'] - 1 ) * $pagination->limit ) . ',' . $pagination->limit;
		
		$this->data['pagination']	= $pagination->render();
		$this->data['items']		= $this->db->query( $query )->rows;
		$this->data['token']		= $this->session->data['token'];
		$this->data['action_invalid_urls'] = $this->url->link('module/seo_mega_pack/invalid_urls', 'token=' . $this->session->data['token'], 'SSL');
				
		// ustawienia szablonu
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['column_left'] = $this->load->controller('common/column_left');
			
		$this->response->setOutput( $this->load->view('module/seo_mega_pack_redirects.tpl', $this->data) );
	}
	
	/**
	 * 
	 */
	public function invalid_urls() {
		$this->data['page']	= isset( $this->request->get['page'] ) ? $this->request->get['page'] : 1;
		
		if( $this->data['page'] < 1 )
			$this->data['page'] = 1;
		
		/**
		 * Query
		 */
		$query = 'SELECT * FROM `' . DB_PREFIX . 'failed_url_smp`';
		
		/**
		 * Paginacja
		 */
		$pagination = new Pagination();
		$pagination->total	= $this->db->query( 'SELECT COUNT(*) AS num FROM(' . $query . ') AS tmp')->row['num'];
		$pagination->page	= $this->data['page'];
		$pagination->limit	= $this->config->get('config_limit_admin');
		$pagination->text	= $this->language->get('text_pagination');
		$pagination->url	= $this->url->link('module/seo_mega_pack/invalid_urls', 'token=' . $this->session->data['token']. '&page={page}', 'SSL');
		
		$query .= ' ORDER BY `frequency` DESC';
		$query .= ' LIMIT ' . ( ( $this->data['page'] - 1 ) * $pagination->limit ) . ',' . $pagination->limit;
		
		$this->data['pagination']	= $pagination->render();
		$this->data['items']		= $this->db->query( $query )->rows;
		$this->data['token']		= $this->session->data['token'];
		$this->data['action_clear_all'] = $this->url->link('module/seo_mega_pack/invalid_urls_clear', 'token=' . $this->session->data['token']. '&page={page}', 'SSL');
				
		// ustawienia szablonu
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['column_left'] = $this->load->controller('common/column_left');
			
		$this->response->setOutput( $this->load->view('module/seo_mega_pack_invalid_urls.tpl', $this->data) );
	}
	
	/**
	 * Dostosuj URLe
	 */
	public function custom() {
		$this->data['language_id']	= isset( $this->request->get['lang'] ) ? $this->request->get['lang'] : (int) $this->config->get('config_language_id');
		$this->data['page']			= isset( $this->request->get['page'] ) ? $this->request->get['page'] : 1;
		
		if( $this->data['page'] < 1 )
			$this->data['page'] = 1;
		
		/**
		 * Languages
		 */
		$this->load->model('localisation/language');		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		/**
		 * Set filters
		 */
		$this->_setFilters( 'smp_custom_filter', array(
			'filter_url', 'filter_seo_url'
		));
		
		/**
		 * Query
		 */
		$query = '
			SELECT
				url_alias_id AS id,
				query AS url,
				keyword AS seo_url,
				smp_language_id AS language_id
			FROM
				' . DB_PREFIX . 'url_alias
			WHERE
				query NOT REGEXP "^(product_id|category_id|information_id|manufacturer_id)=" AND
				smp_language_id = ' . $this->data['language_id'] . '
		';
		
		if( ! empty( $this->data['filter_url'] ) )
			$query .= " AND query='" . $this->db->escape( $this->data['filter_url'] ) . "'";
		
		if( ! empty( $this->data['filter_seo_url'] ) )
			$query .= " AND keyword='" . $this->db->escape( $this->data['filter_seo_url'] ) . "'";
		
		/**
		 * Paginacja
		 */
		$pagination = new Pagination();
		$pagination->total	= $this->db->query( 'SELECT COUNT(*) AS num FROM(' . $query . ') AS tmp')->row['num'];
		$pagination->page	= $this->data['page'];
		$pagination->limit	= $this->config->get('config_limit_admin');
		$pagination->text	= $this->language->get('text_pagination');
		$pagination->url	= $this->url->link('module/seo_mega_pack/custom', 'token=' . $this->session->data['token']. '&lang=' . $this->data['language_id'] . '&page={page}', 'SSL');
		
		$query .= ' ORDER BY url_alias_id DESC';
		$query .= ' LIMIT ' . ( ( $this->data['page'] - 1 ) * $pagination->limit ) . ',' . $pagination->limit;
		
		$this->data['pagination']	= $pagination->render();
		$this->data['items']		= $this->db->query( $query )->rows;
		$this->data['token']		= $this->session->data['token'];
				
		// ustawienia szablonu
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['column_left'] = $this->load->controller('common/column_left');
			
		$this->response->setOutput( $this->load->view('module/seo_mega_pack_custom.tpl', $this->data) );
	}
	
	private function _mainURL( $ssl ) {
		if( defined( 'HTTP_CATALOG' ) ) {
			if( $ssl && defined( 'HTTPS_CATALOG' ) )
				return HTTPS_CATALOG;
			
			return HTTP_CATALOG;
		}
		
		if( $ssl && defined( 'HTTPS_SERVER' ) )
			return HTTPS_SERVER;
	
		return HTTP_SERVER;
	}
	
	/**
	 * Pobierz listę dostępnych map strony
	 * 
	 * @return array
	 */
	private function getSitemaps( $skipindex = false ) {
		$sitemaps_dir	= @ opendir( $this->sitemaps_dir );
		$sitemaps		= array();
		$http_server	= $this->_mainURL( false );

		while( false !== ( $sitemap = readdir( $sitemaps_dir ) ) ) {
			if( $sitemap == '.' || $sitemap == '..' || strpos( $sitemap, '.sitemap.' ) === false || ( $skipindex && strpos( $sitemap, '.sitemap.index.xml' ) !== false ) || ! preg_match( '/\.xml$/', $sitemap ) ) continue;

			$sitemaps[] = array(
				'name'	=> $sitemap,
				'link'	=> $http_server . 'sitemaps/' . $sitemap
			);
		}

		closedir( $sitemaps_dir );

		// sortowanie rozszerzeń
		uasort( $sitemaps, array( $this, '_sortSitemaps' ) );
		
		return $sitemaps;
	}
	
	public function deleteSitemaps() {		
		// usuń wcześniejsze
		foreach( $this->getSitemaps() as $sitemap ) {
			@ unlink( $this->sitemaps_dir . $sitemap['name'] );
		}
	}
	
	/**
	 * Utwórz mapy stron
	 */
	public function createSitemap() {
		$languages	= array();
		$stores = array();
		
		foreach( $this->_languages( true ) as $language ) {
			$languages[] = $language;
		}
		
		foreach( $this->stores( '' ) as $store ) {
			$stores[] = $store;
		}
		
		if( ! file_exists( $this->sitemaps_dir . '.state' ) ) {
			if( NULL != ( $files = glob( $this->sitemaps_dir . '*sitemap.*.xml' ) ) ) {
				foreach( $files as $file ) {
					unlink( $file );
				}
			}
		}
		
		$state		= file_exists( $this->sitemaps_dir . '.state' ) ? unserialize( file_get_contents( $this->sitemaps_dir . '.state' ) ) : array(
			'todo'	=> array(
				'products'		=> 'Products', 
				'categories'	=> 'Categories', 
				'pages'			=> 'InformationPages'
			),
			'page'	=> 1,
			'files' => 1,
			'language_id' => 0,
			'store_id' => 0,
			'links' => 0
		);
		
		if( ! isset( $state['store_id'] ) ) {
			$state['store_id'] = 0;
		}
		
		list( $name, $fn ) = each( $state['todo'] );
		$count		= 'SELECT COUNT(*) AS c FROM ' . DB_PREFIX . '%s i WHERE i.status = 1';
		$language	= $languages[$state['language_id']];
		$store		= $stores[$state['store_id']];
		$limit		= isset( $this->request->get['limit'] ) ? (int) $this->request->get['limit'] : 200;
		$perFile	= 40000;
		
		switch( $name ) {
			case 'products'		: $count = sprintf( $count, 'product' ); break;
			case 'categories'	: $count = sprintf( $count, 'category' ); break;
			case 'pages'		: $count = sprintf( $count, 'information' ); break;
		}
		
		$count = $this->db->query( $count )->row['c'];
		
		require_once VQMod::modCheck(realpath(DIR_APPLICATION . '/../catalog/controller/common/seo_mega_pack_pro_url.php'));
		
		$smp = new ControllerCommonSeoMegaPackProUrl( $this->registry );
		$smp->_setConfigLang( $language['code'], $language['language_id'] );
			
		$this->url->resetRewrites();
		$this->url->addRewrite( $smp );
		
		$sitemap	= $this->{'_createSitemap' . $fn}( $store['store_id'], $state['page'], $limit );
		$file		= $this->sitemaps_dir . $language['code'] . ( $store['name'] ? '.' . $smp->_clear( $store['name'], true ) : '' ) . '.sitemap.' . $name . '.xml';
		$state['page']++;
		
		if( $limit && $state['page'] <= ceil( $count / $limit ) ) {
			$fp = fopen( $file . '.tmp.' . $state['files'], 'a' );
			
			fwrite( $fp, $sitemap );
			fclose( $fp );
			
			$state['links'] += $limit;
			
			if( $state['links'] + $limit > $perFile ) {
				$state['links'] = 0;
				$state['files']++;
			}
			
			$p = $count ? ( $limit * ( $state['page'] - 1 ) ) / $count * 100 : 100;
		} else {
			for( $i = 0; $i < $state['files']; $i++ ) {
				$data = file_exists( $file . '.tmp.' . ( $i+1 ) ) ? file_get_contents( $file . '.tmp.' . ( $i+1 ) ) : '';
				
				if( $i == $state['files']-1 ) {
					$data .= $sitemap;
				}

				if( $data ) {
					file_put_contents( 
						preg_replace( '/\.xml$/', ( $i+1 == 1 ? '' : $i+1 ) . '.xml', $file ), 
						sprintf("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n%s</urlset>", $data ), 
						LOCK_EX 
					);
				}

				if( file_exists( $file . '.tmp.' . ( $i+1 ) ) ) {
					unlink( $file . '.tmp.' . ( $i+1 ) );
				}
			}
			
			$state['store_id']++;
			$state['page'] = 1;
			$state['links'] = 0;
			$state['files'] = 1;
			
			if( ! isset( $stores[$state['store_id']] ) ) {
				$state['language_id']++;
				$state['store_id'] = 0;
			
				if( ! isset( $languages[$state['language_id']] ) ) {
					unset( $state['todo'][$name] );
					$state['language_id'] = 0;
				}
			}
			
			$p = 100;
		}
		
		if( ! $state['todo'] ) {
			foreach( $stores as $store ) {
				foreach( $languages as $language ) {
					$idx = '';

					foreach( $this->getSitemaps( true ) as $sitemap ) {
						list( $code, $st_name ) = explode( '.', $sitemap['name'] );

						if( $code != $language['code'] )
							continue;
						
						if( ( $st_name == 'sitemap' ? '' : $st_name ) != ( $store['name'] ? $smp->_clear( $store['name'], true ) : '' ) )
							continue;

						$idx .= sprintf("\t<sitemap>\r\n\t\t<loc>%s</loc>\r\n\t\t<lastmod>%s</lastmod>\r\n\t</sitemap>\r\n", $store['store_id'] ? $this->_replaceToStoreURL( $store['store_id'], $sitemap['link'] ) : $sitemap['link'], date( 'Y-m-d' ) );
					}

					file_put_contents( $this->sitemaps_dir . $language['code'] . ( $store['name'] ? '.' . $smp->_clear( $store['name'], true ) : '' ) . '.sitemap.index.xml', sprintf("<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n%s</sitemapindex>", $idx), LOCK_EX );
				}
			}
			
			unlink( $this->sitemaps_dir . '.state' );
		} else {
			file_put_contents( $this->sitemaps_dir . '.state', serialize($state), LOCK_EX );
		}
		
		$this->url->resetRewrites();
		
		echo $state['todo'] ? '0||' . $fn . '||' . ( isset( $language['image'] ) ? $language['image'] : '' ) . '||' . $language['name'] . '||' . round( $p > 100 ? 100 : $p, 2 ) . '||' . ( $store['name'] ? $store['name'] : 'Default' ) . '||' . $language['code'] : '1';
	}
	
	/**
	 * Utwórz mapy stron dla produktów
	 * 
	 * @return string
	 */
	private function _createSitemapProducts( $store_id = 0, $page = 1, $limit = 100 ) {
		$str = '';
		$sql = "
			SELECT 
				* 
			FROM 
				" . DB_PREFIX . "product p 
			INNER JOIN
				" . DB_PREFIX . "product_to_store p2s
			ON
				p.product_id = p2s.product_id AND p2s.store_id = " . (int) $store_id . "
			WHERE 
				p.status = 1" . ( $limit ? " LIMIT " . ( ( $page-1 ) * $limit  ) . ', ' . $limit : '' );
		
		$settings = (array) $this->config->get( 'smp_sitemap_changefreq' );
		
		foreach( $this->db->query( $sql )->rows as $product ) {
			$str .= $this->_createSitemapLink( $store_id, $this->url->link( 'product/product', 'product_id=' . $product['product_id'] ), empty( $settings[$store_id]['product'] ) ? 'weekly' : $settings[$store_id]['product'], '0.5' );
		}
		
		return $str;
	}
	
	/**
	 * Utwórz mapy stron dla stron
	 * 
	 * @return string 
	 */
	private function _createSitemapInformationPages( $store_id = 0, $page = 1, $limit = 100 ) {
		$str = '';
		$sql = "
			SELECT 
				* 
			FROM 
				" . DB_PREFIX . "information i 
			INNER JOIN
				" . DB_PREFIX . "information_to_store i2s
			ON
				i.information_id = i2s.information_id AND i2s.store_id = " . (int) $store_id . "
			WHERE 
				status = 1" . ( $limit ? " LIMIT " . ( ( $page-1 ) * $limit  ) . ', ' . $limit : '' );
		
		$settings = (array) $this->config->get( 'smp_sitemap_changefreq' );
		
		foreach( $this->db->query( $sql )->rows as $page ) {
			$str .= $this->_createSitemapLink( $store_id, $this->url->link( 'information/information', 'information_id=' . $page['information_id'] ), empty( $settings[$store_id]['information_page'] ) ? 'yearly' : $settings[$store_id]['information_page'], '1.0' );
		}
		
		// dodaj też kontakt
		$str .= $this->_createSitemapLink( $store_id, $this->url->link( 'information/contact' ), empty( $settings[$store_id]['information_page'] ) ? 'yearly' : $settings[$store_id]['information_page'], '1.0' );
		
		return $str;
	}
	
	private function _catPath( $category_id, $store_id ) {
		if( $category_id == '0' ) {
			return '';
		}
		
		$sql = "
			SELECT 
				* 
			FROM 
				" . DB_PREFIX . "category c 
			INNER JOIN
				" . DB_PREFIX . "category_to_store c2s
			ON
				c.category_id = c2s.category_id AND c2s.store_id = " . (int) $store_id . "
			WHERE 
				c.status = 1 AND c.category_id = " . $category_id;
		
		if( NULL != ( $row = $this->db->query( $sql )->row ) ) {
			$path = $this->_catPath( $row['parent_id'], $store_id );
			
			$path .= $path ? '_' : '';
			$path .= $row['category_id'];
			
			return $path;
		}
		
		return '';
	}
	
	/**
	 * Utwórz mapy stron kategorii
	 * 
	 * @return string 
	 */
	private function _createSitemapCategories( $store_id = 0, $page = 1, $limit = 100, $parent_id = 0, $path = '' ) {
		$str = '';
		$sql = "
			SELECT 
				* 
			FROM 
				" . DB_PREFIX . "category c 
			INNER JOIN
				" . DB_PREFIX . "category_to_store c2s
			ON
				c.category_id = c2s.category_id AND c2s.store_id = " . (int) $store_id . "
			WHERE 
				c.status = 1 " . ( $limit ? "
			LIMIT
				" . ( ( $page-1 ) * $limit  ) . ', ' . $limit : '' );
		
		$settings = (array) $this->config->get( 'smp_sitemap_changefreq' );
		
		foreach( $this->db->query( $sql )->rows as $category ) {
			$path = $this->_catPath( $category['category_id'], $store_id );
			
			$str .= $this->_createSitemapLink( $store_id, $this->url->link( 'product/category', 'path=' . $path ), empty( $settings[$store_id]['category'] ) ? 'yearly' : $settings[$store_id]['category'], '1.0' );
		}
		
		return $str;
	}
	
	private function _replaceToStoreURL( $store_id, $link ) {
		$store = $this->db->query( "SELECT * FROM `" . DB_PREFIX . "store` WHERE `store_id`=" . (int) $store_id)->row;
			
		if( $store ) {
			$source_url = parse_url( $link );
			$store_url = parse_url( $store['url'] );
			
			if( isset( $store_url['scheme'] ) ) {
				$link = $store_url['scheme'] . '://' . $store_url['host'] . ( empty( $store_url['port'] ) ? '' : ':' . $store_url['port'] ) .
					( empty( $store_url['path'] ) ? '' : rtrim( $store_url['path'], '/' ) ) .
					( empty( $source_url['path'] ) ? '' : $source_url['path'] ) .
					( empty( $source_url['query'] ) ? '' : '?' . $source_url['query'] );
			}
		}
		
		return $link;
	}
	
	/**
	 * Utwórz link mapy strony
	 * 
	 * @return string
	 */
	private function _createSitemapLink( $store_id, $link, $changefreq, $priority ) {
		$ssl	= strpos( $link, 'https://' ) === 0 ? true : false;
		$url	= $this->_mainURL( $ssl );
		$link	= str_replace( HTTP_SERVER, $url, $link );
		
		if( $store_id ) {
			$link = $this->_replaceToStoreURL( $store_id, $link );
		}
		
		return sprintf("\t<url>\r\n\t\t<loc>%s</loc>\r\n\t\t<lastmod>%s</lastmod>\r\n\t\t<changefreq>%s</changefreq>\r\n\t\t<priority>%s</priority>\r\n\t</url>\r\n",
			( $link ), date( 'Y-m-d' ), $changefreq, $priority
		);
	}
	
	/**
	 * Mapa strony 
	 */
	public function sitemap() {
		$this->data['createSitemap'] = $this->url->link( 'module/seo_mega_pack/createSitemap', '', 'SSL' ) . '&token=' . $this->session->data['token'];
		
		if( ! is_writable( $this->sitemaps_dir ) ) {
			$this->data['sitemaps'] = array();
			
			$this->data['smk_error_warning'] = $this->language->get('error_sitemaps_dir_writable') . ' ' . $this->sitemaps_dir;
		} else {
			// lista
			$this->data['sitemaps']	= $this->getSitemaps();

			// zapisz zmiany
//			if( $this->request->server['REQUEST_METHOD'] == 'POST' ) {
//				$this->data['success'] = $this->language->get('success_generated');
//
//				$this->createSitemaps();
//
//				$this->data['sitemaps']	= $this->getSitemaps();
//			}
		}
		
		// ustawienia szablonu
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['column_left'] = $this->load->controller('common/column_left');
			
		$this->response->setOutput( $this->load->view('module/seo_mega_pack_sitemap.tpl', $this->data) );
	}
	
	private function _cacheWritable() {
		return ! is_dir( $this->cache_dir ) || ! is_writable( $this->cache_dir ) ? false : true;
	}
	
	private function _clearCache( $messages ) {
		if( ! $this->_cacheWritable() ) {
			if( $messages ) {
				$this->session->data['error'] = $this->language->get('error_cache_dir');
			}
		} else {		
			$dir	= @ opendir( $this->cache_dir );
			$items	= 0;

			while( false !== ( $file = readdir( $dir ) ) ) {
				if( $file == '.' || $file == '..' || ( strlen( $file ) < 32 && strpos( $file, 'cache.' ) !== 0 ) ) continue;

				@ unlink( $this->cache_dir . '/' . $file );

				$items++;
			}

			closedir( $dir );

			if( $messages ) {
				$this->session->data['success'] = sprintf( $this->language->get('success_cache_clear'), $items );
			}
		}
	}
	
	public function clearcache() {		
		$this->_clearCache( true );
			
		$this->response->redirect($this->url->link('module/seo_mega_pack/settings', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	/**
	 * Extras
	 */
	public function extras() {
		// Action fro Form
		$this->data['action'] = $this->url->link('module/seo_mega_pack/extras', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->load->model('setting/setting');
		
		/* @var $stores array */
		$stores = $this->stores();
		
		// Save changes
		if( $this->request->server['REQUEST_METHOD'] == 'POST' ) {	
			$this->session->data['success'] = $this->language->get('success_modified');
			
			/**
			 * Save settings
			 */
			$this->model_setting_setting->editSetting('smp_extras', $this->request->post);
			
			$this->response->redirect($this->url->link('module/seo_mega_pack/extras', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		// Read current settings
		$this->data['extras'] = $this->config->get( 'smp_extras' );
		
		/**
		 * Images 
		 */
		$this->load->model('tool/image');
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		/**
		 * FB Image 
		 */
		if( ! empty( $this->data['extras']['facebook_widget']['image'] ) && file_exists( DIR_IMAGE . $this->data['extras']['facebook_widget']['image'] ) ) {
			$this->data['extras']['facebook_widget']['thumb'] = $this->model_tool_image->resize( $this->data['extras']['facebook_widget']['image'], 100, 100 );
		} else {
			$this->data['extras']['facebook_widget']['thumb']	= $this->model_tool_image->resize( 'no_image.jpg', 100, 100 );
			$this->data['extras']['facebook_widget']['image']	= '';
		}
		
		/**
		 * Google+ Image 
		 */
		if( ! empty( $this->data['extras']['g_plus_widget']['image'] ) && file_exists( DIR_IMAGE . $this->data['extras']['g_plus_widget']['image'] ) ) {
			$this->data['extras']['g_plus_widget']['thumb'] = $this->model_tool_image->resize( $this->data['extras']['g_plus_widget']['image'], 100, 100 );
		} else {
			$this->data['extras']['g_plus_widget']['thumb']	= $this->model_tool_image->resize( 'no_image.jpg', 100, 100 );
			$this->data['extras']['g_plus_widget']['image']	= '';
		}
		
		// ustawienia szablonu
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		
		$this->data['stores'] = $stores;
			
		$this->response->setOutput( $this->load->view('module/seo_mega_pack_extras.tpl', $this->data) );
	}
	
	/**
	 * Cron
	 */
	public function cron() {
		// akcja dla formularza
		$this->data['action'] = $this->url->link('module/seo_mega_pack/cron', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->load->model('setting/setting');
		
		// zapisz zmiany
		if( $this->request->server['REQUEST_METHOD'] == 'POST' ) {	
			$this->session->data['success'] = $this->language->get('success_modified');
			
			/**
			 * Zapisz ustawienia
			 */
			$this->model_setting_setting->editSetting('smp_cron', $this->request->post);
			
			if( file_exists( DIR_CACHE . 'smp_cron.db' ) ) {
				@ unlink( DIR_CACHE . 'smp_cron.db' );
			}
			
			$this->response->redirect($this->url->link('module/seo_mega_pack/cron', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		// odczytaj aktualne ustawienia
		$this->data['cron'] = $this->config->get( 'smp_cron' );
		
		// ustawienia szablonu
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['column_left'] = $this->load->controller('common/column_left');
			
		$this->response->setOutput( $this->load->view('module/seo_mega_pack_cron.tpl', $this->data) );
	}
	
	private function stores( $default = NULL ) {
		$this->load->model('setting/store');
		
		$stores = array( 0 => array( 'store_id' => 0, 'name' => $default !== NULL ? $default : $this->language->get( 'text_default' ) ) );
		
		foreach( $this->model_setting_store->getStores() as $v ) {
			$stores[] = $v;
		}
		
		return $stores;
	}
	
	/**
	 * Settings
	 * Ustawienia
	 */
	public function settings() {
		// akcja dla formularza
		$this->data['action'] = $this->url->link('module/seo_mega_pack/settings', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['action_clear_cache'] = $this->url->link('module/seo_mega_pack/clearcache', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->load->model('setting/setting');
		
		$stores = $this->stores();
		
		// zapisz zmiany
		if( $this->request->server['REQUEST_METHOD'] == 'POST' ) {	
			if( ! empty( $this->request->post['smp_cache'] ) && ! $this->_cacheWritable() ) {
				$this->language->get('error_cache_dir');
				$this->request->post['smp_cache'] = '0';
				
				$this->session->data['error'] = $this->language->get('error_cache_dir');
			} else {
				$this->session->data['success'] = $this->language->get('success_modified');
			}			
			
			/**
			 * Zapisz ustawienia
			 */
			$this->model_setting_setting->editSetting('smp', $this->request->post);
			
			$this->response->redirect($this->url->link('module/seo_mega_pack/settings', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		// odczytaj aktualne ustawienia
		$this->data['settings'] = $this->model_setting_setting->getSetting( 'smp' );
		
		$this->data['languages'] = $this->_languages( true );
		$this->data['stores'] = $stores;
		
		$this->data['val_default_meta_title'] = $this->config->get( 'config_meta_title' );
		$this->data['val_default_meta_description'] = $this->config->get( 'config_meta_description' );
		$this->data['val_default_meta_keywords'] = $this->config->get( 'config_meta_keywords' );
		
		// ustawienia szablonu
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['column_left'] = $this->load->controller('common/column_left');
			
		$this->response->setOutput( $this->load->view('module/seo_mega_pack_settings.tpl', $this->data) );
	}
	
	/**
	 * Pomoc
	 */
	public function help() {
		// ustawienia szablonu
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['column_left'] = $this->load->controller('common/column_left');
			
		$this->response->setOutput( $this->load->view('module/seo_mega_pack_help.tpl', $this->data) );
	}
	
	public function integration_request() {
		$url = sprintf( 'http://ocdemo.com/seo_mega_pack/connector.php?version=%s&version_type=%s&action=integration_request', $this->_version, $this->_versionType );
		
		$url .= '&email=' . urlencode( $this->request->post['email'] );
		$url .= '&order_id=' . urlencode( $this->request->post['order_id'] );
		$url .= '&integrations=' . urlencode( implode( ',', $this->request->post['integrations'] ) );
		
		ob_start();			
		$data = json_decode( file_get_contents( $url ), true );
		ob_end_clean();
		
		$html = '';
		
		if( ! empty( $data['message'] ) ) {
			$html .= $data['message'];
		} else {
			$html .= $this->language->get( 'text_connection_error' );
		}
		
		$this->response->setOutput( $html );
	}
	
	/**
	 * Informacje o module
	 */
	public function about() {
		// ustawienia szablonu
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		
		$this->data['config_email'] = $this->config->get('config_email');
		
		$this->data['versions'] = (array) $this->config->get( 'smp_conn_versions' );
		$this->data['integrations'] = (array) $this->config->get( 'smp_conn_integrations' );
		$this->data['integrations_installed'] = array();
		
		$this->data['action_request'] = $this->url->link('module/seo_mega_pack/integration_request', 'token=' . $this->session->data['token'], 'SSL');
		
		if( false != ( $integrations = glob(DIR_SYSTEM.'library/smk/integrations/*.xml') ) ) {
			foreach( $integrations as $tmp ) {
				$this->data['integrations_installed'][] = trim( basename( $tmp ), '.xml' );
			}
		}
		
		$this->data['action_need_install'] = $this->url->link('module/seo_mega_pack/check_if_need_install_ext', 'token=' . $this->session->data['token'], 'SSL');
		
		if( ! empty( $this->data['versions']['data']['latest_version'] ) && version_compare( $this->_version, $this->data['versions']['data']['latest_version'], '<' ) ) {
			$this->data['download_new_version_of_smk'] = sprintf(
				$this->language->get('text_download_new_version_of_smk'),
				$this->data['versions']['data']['latest_version'],
				'http://www.opencart.com/index.php?route=extension/extension/info&extension_id=12971'
			);
		}
		
		$this->response->setOutput( $this->load->view('module/seo_mega_pack_about.tpl', $this->data) );
	}
	
	public function check_if_need_install_ext() {
		$response = array(
			'success' => false,
			'alert' => 'Error - try again'
		);
		
		if( isset( $this->request->get['codename'] ) ) {
			$codename = $this->request->get['codename'];
			$integrations = (array) $this->config->get( 'smp_conn_integrations' );
			
			if( isset( $integrations['data']['integrations'][$codename] ) ) {
				$code = $integrations['data']['integrations'][$codename]['code'];
				$file = DIR_CACHE . 'smk_test_integration.php';
				
				$code = sprintf('
					<?php
						
						class SMK_TestIntegration {
							private $_ctrl;

							public function run() {
								%s
							}
							
							public function __construct( & $ctrl ) {
								$this->_ctrl = & $ctrl;
							}
							
							static public function create( & $ctrl ) {
								return new self( $ctrl );
							}
							
							public function __get( $name ) {
								return $this->_ctrl->{$name};
							}
						}
				', $code);
				
				file_put_contents( $file, $code, LOCK_EX );
				
				require_once $file;
				
				$response['should_install'] = SMK_TestIntegration::create( $this )->run();
				$response['success'] = true;
				
				unlink( $file );
			} else {
				$response['alert'] = 'Integrations not found';
			}
		}		
		
		$this->response->setOutput( json_encode( $response ) );
	}
	
	/**
	 * Uruchom generator
	 */
	public function generate() {
		$extensions	= explode( ',', $this->request->get['extensions'] );
		$results	= '';
		
		//if( function_exists( 'set_time_limit' ) )
		//	@ set_time_limit( 60 * 60 );
		
		$preview		= isset( $this->request->get['preview'] ) ? $this->request->get['preview'] : '0';
		$info			= isset( $this->request->get['info'] ) ? $this->request->get['info'] : '0';
		$limit			= isset( $this->request->get['limit'] ) ? $this->request->get['limit'] : NULL;
		$mode			= isset( $this->request->get['mode'] ) ? $this->request->get['mode'] : NULL;
		$language_id	= isset( $this->request->get['language_id'] ) ? $this->request->get['language_id'] : NULL;
		$languages		= $this->_languages( true );
		$resetCache		= isset( $this->request->get['reset_cache'] ) ? true : false;
		
		foreach( $extensions as $extension ) {
			$extension	= self::classify( $extension );

			if( isset( $this->data['extensions'][$extension] ) ) {
				if( $resetCache )
					$this->data['extensions'][$extension]->resetCache();
				
				if( $info ) {
					$generateInfo = $this->data['extensions'][$extension]->generateInfo( $mode );
					
					$results .= $results ? '<br />' : '';
					$results .= '<table class="table table-bordered">';
					$results .= '<tbody><tr><td class="vertical-middle" width="350" rowspan="' . count( $generateInfo ) . '">';
					$results .= '<strong>' . $this->data['extensions'][$extension]->title() . '</strong>:';
					$results .= '</td>';
					
					foreach( $generateInfo as $index => $info ) {
						if( $index > 0 )
							$results .= '<tr>';
						
						$results .= '<td>';
						$results .= '<span
							data-language-id="' . ( isset( $info['language_id'] ) ? $info['language_id'] : '' ) . '"
							data-extension="' . $this->data['extensions'][$extension]->name() . '"
							data-title="' . $this->data['extensions'][$extension]->title() . '"
							data-mode="' . ( empty( $info['mode'] ) ? '' : $info['mode'] ) . '"
							data-items="' . $info['items'] . '">';
						
						$this->data['info'][] = array(
							'language_id'	=> isset( $info['language_id'] ) ? $info['language_id'] : '',
							'extension'		=> $this->data['extensions'][$extension]->name(),
							'title'			=> $this->data['extensions'][$extension]->title(),
							'mode'			=> empty( $info['mode'] ) ? '' : $info['mode'],
							'items'			=> $info['items']
						);
						
						if( $this->data['extensions'][$extension]->multiLanguages() ) {
							$flag = version_compare( VERSION, '2.2.0.0', '>=' ) ? 
								( empty( $info['code'] ) ? '' : 'language/' . $info['code'] . '/' . $info['code'] . '.png' ) : 'view/image/flags/' . $info['image'];
							
							$results .= $flag ? '<img class="vertical-align" style="margin-top:-3px" src="' . $flag . '" /> ' : '';
							$results .= $info['name'];
							$results .= '</span> - ';
						} else {
							$results .= '</span>';
						}
						$results .= $info['items'] . ' new item(s)';
						
						$results .= '</td>';
						$results .= '</tr>';
					}
					
					$results .= '</tbody></table>';
					
					if( $extension == 'RelatedProductsGenerator' ) {
						$results .= '<span class="help">';
						$results .= sprintf( $this->language->get( 'text_related_products_generator_info' ), $this->config->get( 'smp_' . $this->data['extensions'][$extension]->shortName() . '_params' ) );
						$results .= '</span>';
					}
				} else {
					foreach( $this->data['extensions'][$extension]->generate( (bool) $preview, $limit, $language_id, $mode ) as $lang_id => $rows ) {
						$results .= '<table class="table table-thead-bg">';
						$results .= '<thead>';
						
						$results .= '<tr><td colspan=2" class="text-center btn-info">' . $this->data['extensions'][$extension]->title() . '</td></tr>';
						
						if( $lang_id !== '' && $this->data['extensions'][$extension]->multiLanguages() ) {
							$flag = version_compare( VERSION, '2.2.0.0', '>=' ) ? 
								( empty( $languages[$lang_id]['code'] ) ? '' : 'language/' . $languages[$lang_id]['code'] . '/' . $languages[$lang_id]['code'] . '.png' ) : 'view/image/flags/' . $languages[$lang_id]['image'];
							
							$results .= '<tr><td colspan="2" class="text-right">
								' . ( $flag ? '<img src="' . $flag . '" /> ' : '' ) . $languages[$lang_id]['name'] . '
							</td></tr>';
						}
						
						$results .= '</thead>';
						$results .= '<tbody><tr><td><b>Item</b></td><td><b>Preview value</b></td></tr></tbody><tbody>';
						
						if( ! $rows ) {
							$results .= '<tr><td colspan="2" class="text-center">' . $this->language->get( 'text_list_is_empty' ) . '</td></tr>';
						} else {
							foreach( $rows as $cols ) {
								if( is_array( $cols[0] ) && is_array( $cols[0][0] ) ) {
									foreach( $cols as $cols2 ) {
										if( is_array( $cols2 ) ) {
											foreach( $cols2 as $cols3 ) {
												if( count( $cols3 ) == 1 ) {
													$results .= '</tbody><thead>';
													$results .= '<td colspan="2" class="text-right">' . $cols3[0] . '</td>';
													$results .= '</thead><tbody>';
													
													if( count( $cols2 ) == 1 ) {
														$results .= '<tr><td colspan="2" class="text-center">' . $this->language->get( 'text_list_is_empty' ) . '</td></tr>';
													}
												} else {
													$results .= '<tr>';
													$results .= '<td>' . implode( '</td><td>', $cols3 ) . '</td>';
													$results .= '</tr>';
												}
											}
										} else {
											$results .= '<tr>';
											$results .= '<td>' . implode( '</td><td>', $cols2 ) . '</td>';
											$results .= '</tr>';

											break;
										}
									}
								} else {
									$results .= '<tr>';
									$results .= '<td>' . implode( '</td><td>', $cols ) . '</td>';
									$results .= '</tr>';
								}
							}

							$results .= '</tbody>';
							$results .= '</table>';
						}
					}
				}
			}
		}
		
		unset( $this->session->data['success'] );
		$this->data['success'] = trim( $results ) ? '' : $this->language->get( 'text_nothing_to_generate' );
		$this->data['results'] = $results;
		
		if( defined( 'SMP_CRON' ) )
			return;
		
		// Template
		$this->response->setOutput( $this->load->view('module/seo_mega_pack_generate.tpl', $this->data) );
	}
	
	/**
	 * Wyczyść
	 */
	public function clear() {
		$extensions			= explode( ',', $this->request->get['extensions'] );
		$mode				= isset( $this->request->get['mode'] ) ? $this->request->get['mode'] : NULL;
		$onlyAutoGenerated	= (bool) isset( $this->request->get['onlyAutoGenerated'] ) ? $this->request->get['onlyAutoGenerated'] : false;
				
		//if( function_exists( 'set_time_limit' ) )
		//	@ set_time_limit( 60 * 60 );
		
		foreach( $extensions as $extension ) {
			$extension	= self::classify( $extension );

			if( isset( $this->data['extensions'][$extension] ) ) {
				$this->data['results']		= $this->data['extensions'][$extension]->clear( $mode, $onlyAutoGenerated );
			}
		}
		
		$this->session->data['success'] = $this->language->get('success_clear');
		
		$this->_messages();
		
		// Template			
		$this->response->setOutput( $this->load->view('module/seo_mega_pack-messages.tpl', $this->data) );
	}
	
	/**
	 * Sprawdź czy moduł został zainstalowany
	 * 
	 * @return boolean
	 */
	private function installation_check() {
		// sprawdź czy folder rozszerzeń istnieje
		if( ! is_dir( $this->extensions_dir ) ) {
			$this->session->data['error'] = $this->language->get('error_extensions_dir');
			return false;
		}
		
		// sprawdź czy folder dla map strony istnieje
		if( ! is_dir( $this->sitemaps_dir ) ) {
			$this->session->data['error'] = $this->language->get('error_sitemaps_dir');
			return false;
		}

		// sprawdź wersję systemu - min 1.5.0
		if( ! defined('VERSION') || version_compare(VERSION, '1.5.0', '<') ) {
			$this->session->data['error'] = $this->language->get('error_opencart_version');
			return false;
		}
		
		// sprawdź czy moduł zainstalowany
		if( ! $this->config->get( 'smp_is_install' ) ) {
			$this->session->data['error'] = $this->language->get('error_install');
			return false;
		}
		
		// sprawdź uprawnienia
		if( ! $this->userPermission() ) {
			$this->session->data['error'] = $this->language->get('error_permission');
			return false;
		}

		return true;
	}

	/**
	 * Sprawdź uprawnienia użytkownika
	 * 
	 * @param string $permission
	 * @return boolean
	 */
	private function userPermission($permission = 'modify') {
		$this->language->load('module/seo_mega_pack');
		
		if( ! $this->user->hasPermission($permission, 'module/seo_mega_pack') ) {
			$this->session->data['error'] = $this->language->get('error_permission');
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Pobierz listę dostępnych języków
	 * 
	 * @return array
	 */
	public function _languages( $full = false ) {
		$languages	= array();
		$query		= $this->db->query('
			SELECT
				*
			FROM
				' . DB_PREFIX . 'language
			WHERE
				status = 1
			ORDER BY
				sort_order ASC
		');
		
		foreach( $query->rows as $item )
			$languages[$item['language_id']] = $full ? $item : $item['name'];
		
		return $languages;
	}
	
	/**
	 * Automatyczna modyfikacja pliku .htaccess przy deinstalacji
	 * @todo
	 */
	private function _htaccessUninstall() {
		$history = $this->config->get( 'smp_htaccess' );
		
		if( ! $history )
			return NULL;
		
		// katalog główny aplikacji
		$directory	= DIR_SYSTEM . '../';
		$history	= unserialize( $history );
		
		// sprawdź czy utworzono plik
		if( in_array( 'create', $history['info'] ) )
			@ unlink( $directory . '.htaccess' );
		// sprawdź czy była zmieniana nazwa
		else if( in_array( 'rename', $history['info'] ) ) {
			if( is_writable( $directory . '.htaccess' ) && NULL != ( $cnt = @ file_get_contents( $directory . '.htaccess' ) ) ) {
				$cnt = str_replace( 'RewriteRule ^image-smp/(.*) index.php?route=product/smp_image&name=$1 [L]', '', $cnt );
				file_put_contents( $directory . '.htaccess', $cnt, LOCK_EX );
			}
			
			@ rename( $directory . '.htaccess', $directory . '.htaccess.txt' );
		}
	}
	
	/**
	 * Automatyczna modyfikacja pliku .htaccess przy instalacji
	 * 
	 * @return bool|array
	 * @todo
	 */
	private function _htaccessInstall() {
		// katalog główny aplikacji
		$directory	= DIR_SYSTEM . '../';
		$history	= array(
			'.htaccess'	=> NULL,
			'info'		=> array()
		);
		$htaccess	= $directory . '.htaccess';
		
		// sprawdź czy istnieje plik .htaccess.txt
		if( ! file_exists( $htaccess ) && file_exists( $directory . '.htaccess.txt' ) ) {
			if( ! @ rename( $directory . '.htaccess.txt', $htaccess ) ) {
				return false;
			}
			
			$history['info'][] = 'rename';
		}
		
		// sprawdź czy istnieje plik .htaccess
		if( ! file_exists( $htaccess ) ) {			
			if( ! is_writable( $directory ) )
				return false;
			
			// standardowy plik .htaccess
			if( @ file_put_contents( $htaccess, implode( "\n", array(
				'Options +FollowSymlinks',
				'Options -Indexes',
				'<FilesMatch "(?i)((\.tpl|\.ini|\.log|(?<!robots)\.txt))">',
					'Order deny,allow',
					'Deny from all',
				'</FilesMatch>',
				'RewriteEngine On',
				'RewriteBase /',
				'RewriteRule ^sitemap.xml$ index.php?route=feed/google_sitemap [L]',
				'RewriteRule ^googlebase.xml$ index.php?route=feed/google_base [L]',
				'RewriteRule ^download/(.*) /index.php?route=error/not_found [L]',
				'RewriteCond %{REQUEST_FILENAME} !-f',
				'RewriteCond %{REQUEST_FILENAME} !-d',
				'RewriteCond %{REQUEST_URI} !.*\.(ico|gif|jpg|jpeg|png|js|css)',
				'RewriteRule ^([^?]*) index.php?_route_=$1 [L,QSA]'
			)), LOCK_EX) === false ) {
				return false;
			}
			
			$history['info'][] = 'create';
		}
		
		$fileperms	= NULL;
		
		// sprawdź czy jest dostęp do pliku
		if( ! is_readable( $htaccess ) || ! is_writable( $htaccess ) ) {
			$fileperms = fileperms( $htaccess );
			
			if( ! @ chmod( $htaccess, 777 ) )
				return false;
		}
		
		$contents				= file( $htaccess );
		$history['.htaccess']	= file_get_contents( $htaccess );
		
		// RewriteBase
		$path	= parse_url( HTTP_CATALOG );
		$l1		= 'RewriteBase ' . $path['path'];
		$l2		= 'RewriteRule ^image-smp/(.*) index.php?route=product/smp_image&name=$1 [L]';
		$l3		= 'RewriteEngine On';
		$ins1	= strpos( $history['.htaccess'], $l1 ) === false;
		$ins2	= strpos( $history['.htaccess'], $l2 ) === false;
		$ins3	= strpos( $history['.htaccess'], $l3 ) === false;
		
		foreach( $contents as $key => $line ) {
			$line = trim( $line );
			$contents[$key] = $line;
			
			if( $ins1 && strpos( $line, 'RewriteBase' ) !== false ) {
				$contents[$key] = $l1;
				$ins1 = false;
				
				$history['info'][] = 'RewriteBase';
			} else if( $ins2 && strpos( $line, 'RewriteCond %{REQUEST_FILENAME} !-f' ) !== false ) {
				array_splice( $contents, $key, 0, $l2 . "\n" . 'RewriteCond %{REQUEST_FILENAME} !-f' );
				$ins2 = false;
				
				$history['info'][] = 'RewriteRule';
			} else if( $ins3 && strpos( $line, 'RewriteEngine Off' ) !== false ) {
				$contents[$key] = $l3;
				$ins3 = false;
				
				$history['info'][] = 'RewriteEngine';
			}
		}
		
		// zapisz zmiany
		file_put_contents( $htaccess, implode( "\n", $contents ), LOCK_EX );
		
		// przywróć uprawnienia
		if( $fileperms ) {
			@ chmod( $htaccess, $fileperms );
		}
		
		return $history;
	}
	
	/**
	 * Aktualizacja pakietu SMP
	 */
	private function __update( $install = false ) {
		/**
		 * @since 2.0.2.1
		 */
		if( version_compare( $this->config->get( 'smp_version' ), '2.0.2.1', '<' ) ) {
			if( file_exists( $this->sitemaps_dir . '.state' ) ) {
				unlink( $this->sitemaps_dir . '.state' );
			}
		}
		
		/**
		 * @since 1.2.8 
		 */
		if( $install || version_compare( $this->config->get( 'smp_version' ), '1.2.8', '<' ) ) {
			$this->load->model('setting/setting');
			
			$settings		= (array) $this->model_setting_setting->getSetting( 'smp' );
			
			if( ! $settings )
				$settings = array();
			
			if( empty( $settings['smp_skip_urls'] ) )
				$settings['smp_skip_urls'] = array();
			
			$settings['smp_skip_urls'][]	= 'payment/';
			$settings['smp_skip_urls'][]	= 'product/product/captcha';
			$settings['smp_skip_urls'][]	= 'information/contact/captcha';
			$settings['smp_skip_urls'][]	= 'api/';
			$settings['smp_skip_urls'][]	= 'information/information/agree'; // @since 2.0.1.4.1
			$settings['smp_skip_urls']		= array_unique( $settings['smp_skip_urls'] );
			
			/**
			 * @since 1.3.9 
			 */
			if( $install ) {
				$settings['smp_trans_urls_when_change_langs']	= '1';
				$settings['smp_disable_convert_urls']			= '1';
				
				// @since 2.0.2.2
				$settings['smp_auto_redirect_to_canonical']		= '1';
			}
			
			$this->model_setting_setting->editSetting( 'smp', $settings );
		}
		
		/**
		 * @since 1.3.9.9
		 */
		if( $install || version_compare( $this->config->get( 'smp_version' ), '1.3.9.9', '<' ) ) {
			$this->db->query('
				CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'redirects_smp` (
					`redirects_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					`broken_link` TEXT NOT NULL,
					`new_link` TEXT NOT NULL,
					PRIMARY KEY (`redirects_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
			
			$this->db->query('
				CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'failed_url_smp` (
					`failed_url_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					`url` TEXT NOT NULL,
					`created_at` DATE NOT NULL,
					`frequency` INT(11) NOT NULL,
					PRIMARY KEY (`failed_url_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
		}		

		/**
		 * @since 1.3.9.9.5
		 */
		if( version_compare( $this->config->get( 'smp_version' ), '1.3.9.9.5', '<' ) ) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "smp_image");
			$this->cache->delete('smp_seo_image');
			$this->cache->delete('smp_seo_image_id');
		}
	}
	
	public function installprogress() {
		if( $this->data['is_installed'] && ! isset( $this->session->data['smp_go_to_update'] ) ) {
			$this->response->redirect($this->url->link('module/seo_mega_pack', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		if( ! empty( $this->data['available_new_version_of_smk'] ) ) {
			unset( $this->data['available_new_version_of_smk'] );
		}
		
		$this->language->load('module/seo_mega_pack');
		
		$extensions	= $this->getExtensions( true, self::TYPE_EXTENSION, true );
		
		if( isset( $this->request->post['extension'] ) ) {
			$extension	= $this->request->post['extension'];
			$index		= $this->request->post['index'];
			
			if( $extension == '__final_step__' ) {
				$this->load->model('setting/setting');
			
				/**
				* Automatyczna weryfikacja pliku .htaccess
				* 
				* @todo
				*/
				if( false != ( $htaccess = $this->_htaccessInstall() ) ) {
					$this->session->data['success'] = $this->language->get('success_install_with_htaccess');
					
					$this->model_setting_setting->editSetting('smp_htaccess', array(
						'smp_htaccess' => serialize( $htaccess )
					));
				} else {
					$this->session->data['success'] = isset( $this->session->data['smp_go_to_update'] ) ? $this->language->get('success_updated') : $this->language->get('success_install');
				}
					
				if( isset( $this->session->data['smp_go_to_update'] ) ) {
					unset( $this->session->data['smp_go_to_update'] );
				}
				
				if( isset( $this->session->data['smp_was_installed'] ) ) {
					unset( $this->session->data['smp_was_installed'] );
				}

				/**
				* Zapisz ustawienia
				*/
				$this->model_setting_setting->editSetting('smp_is_install', array(
					'smp_is_install' => '1'
				));

				$this->db->query("
					UPDATE
						`" . DB_PREFIX . "setting`
					SET
						`value` = '1'
					WHERE
						`code` = 'config' AND 
						`key` = 'config_seo_url' AND 
						`store_id` = '0'
				");

				unset( $this->session->data['error'] );

				// aktualizuj SMP
				$this->__update( true );

				// zaktualizuj informację o aktualnej wersji
				$this->model_setting_setting->editSetting('smp_version', array(
					'smp_version' => $this->_version
				));
				
				// @since 1.3.9 typ wersji (plus/lite)
				$this->model_setting_setting->editSetting('smp_versionType', array(
					'smp_versionType' => $this->_versionType
				));

				/**
				* Sprawdź czy wtyczka jest na liście 
				*/
				$this->load->model('extension/extension');

				if( ! in_array( 'seo_mega_pack', $this->model_extension_extension->getInstalled('module') ) )
					$this->model_extension_extension->install('module', 'seo_mega_pack');
				
				$this->response->setOutput( '1' );
			} else {
				$response = isset( $this->session->data['smp_was_installed'][$extension] ) ? $this->session->data['smp_was_installed'][$extension]['res'] : $extensions[$extension]->install();
				
				if( ! isset( $this->session->data['smp_was_installed'][$extension] ) ) {
					$this->session->data['smp_was_installed'][$extension] = array( 'res' => $response );
				}
				
				if( $response === false ) {
					foreach( $extensions as $ext => $obj ) {
						if( ! isset( $this->session->data['smp_was_installed'][$ext] ) ) {
							$this->session->data['smp_was_installed'][$ext] = array( 'res' => $extensions[$ext]->install() );
						}
					}
					
					$response = $extensions[$extension]->installprogress( $index );
				}
				
				$this->response->setOutput( $response === NULL ? '1' : '0|' . (int) $response );
			}
		} else {		
			$this->data['extensions']		= array_keys( $extensions );
			$this->data['action']			= $this->url->link('module/seo_mega_pack/installprogress', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['action_ready']		= $this->url->link('module/seo_mega_pack', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['text_smp_info']	= $this->language->get( empty( $this->session->data['smp_go_to_update'] ) ? 'text_installing_smp' : 'text_updating_smp' );

			if( $this->data['smk_error_warning'] == $this->language->get( 'error_install' ) )
				$this->data['smk_error_warning'] = '';
			
			$this->data['success'] = '';

			// Template
			$this->data['header'] = $this->load->controller('common/header');
			$this->data['footer'] = $this->load->controller('common/footer');
			$this->data['column_left'] = $this->load->controller('common/column_left');
			
			$this->response->setOutput( $this->load->view('module/seo_mega_pack_installprogress.tpl', $this->data) );
		}
	}

	/**
	 * Instalacja modułu
	 */
	public function install() {
		/**
		 * Sprawdź czy użytkownik ma uprawnienia
		 */
		if( $this->userPermission() ) {
			$this->response->redirect($this->url->link('module/seo_mega_pack/installprogress', 'token=' . $this->session->data['token'], 'SSL'));
		} else {
			$this->session->data['smp_error_install'] = true;
			
			$this->response->redirect($this->url->link('extension/module/uninstall', 'token=' . $this->session->data['token'] . '&extension=seo_mega_pack', 'SSL'));
		}
		
		// przekieruj do modułu
		$this->response->redirect($this->url->link('module/seo_mega_pack', 'token=' . $this->session->data['token'], 'SSL'));
	}

	/**
	 * Deinstalacja rozszerzenia
	 */
	public function uninstall() {
		/**
		 * Sprawdź czy użytkownik ma uprawnienia
		 */
		if( $this->userPermission() ) {
			// pobierz listę dostępnych rozszerzeń
			$extensions = $this->getExtensions( true, self::TYPE_EXTENSION );
			
			// usuń wszystkie rozszerzenia
			foreach( $extensions as $extension ) {
				$extension->uninstall();
			}
			
			$this->db->query('DROP TABLE IF EXISTS `' . DB_PREFIX . 'redirects_smp`');
			$this->db->query('DROP TABLE IF EXISTS `' . DB_PREFIX . 'failed_url_smp`');
			
			$this->_htaccessUninstall();
			
			$this->model_setting_setting->deleteSetting( 'smp_is_install' );
			$this->model_setting_setting->deleteSetting( 'smp_version' );
			$this->model_setting_setting->deleteSetting( 'smp_versionType' );
			$this->model_setting_setting->deleteSetting( 'smp' );
			$this->model_setting_setting->deleteSetting( 'smp_mp_check' );
			
			// sprawdź czy deinstalacja się powiodła
			if( isset( $this->session->data['smp_error_install'] ) ) {
				unset( $this->session->data['smp_error_install'] );
				$this->session->data['error'] = $this->language->get('error_permission');
			} else {
				$this->session->data['success'] = $this->language->get('success_uninstall');
			}
			
			/**
			 * Sprawdź czy wtyczka jest na liście 
			 */
			$this->load->model('extension/extension');
			
			if( in_array( 'seo_mega_pack', $this->model_extension_extension->getInstalled('module') ) )
				$this->model_extension_extension->uninstall('module', 'seo_mega_pack');
		}

		// przekieruj do listy modułów
		$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
	}
}
?>