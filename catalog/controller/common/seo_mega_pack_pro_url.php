<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */
class ControllerCommonSeoMegaPackProUrl extends Controller {
	
	////////////////////////////////////////////////////////////////////////////
	
	private static $_adminDir = NULL;
	
	private static $_notFound = false;
	
	public static function adminDir() {
		if( self::$_adminDir === NULL ) {
			self::$_adminDir = defined( 'SMP_ADMIN_DIR' ) ? SMP_ADMIN_DIR : 'admin';
		}
		
		return self::$_adminDir;
	}
	
	////////////////////////////////////////////////////////////////////////////
	
	/**
	 * Max to all cache
	 * 
	 * @var int
	 */
	public $_maxToAllCache			= 10000; // for produsts/categories/manufacturers/informations
	public $_maxToAllCacheUrlAlias	= 20000; // only for urls alias
	
	/**
	 * Counts
	 *
	 * @var array
	 */
	private $_counts		= array();
	
	/**
	 * Products list
	 *
	 * @var array
	 */
	private $_products		= NULL;
	
	/**
	 * Categories list
	 *
	 * @var array
	 */
	private $_categories	= NULL;
	
	/**
	 * Manufacturers list
	 *
	 * @var array
	 */
	private $_manufacturers	= NULL;
	
	/**
	 * Informations list
	 * 
	 * @var array
	 */
	private $_informations	= NULL;
	
	/**
	 * Urls alias list
	 * 
	 * @var array
	 */
	private $_url_alias		= NULL;
	
	/**
	 * Languages list
	 * 
	 * @var array
	 */
	private $_languages		= NULL;
	
	private $_languages_data = NULL;
	
	/**
	 * @var array
	 */	
	private $_parse_url_extensions	= NULL;
	
	/**
	 * Default language
	 * 
	 * @var string
	 */
	private $_language		= NULL;
	
	public $_config_language	= NULL;
	
	public $_config_language_id = NULL;
	
	private $_detect_language_by_url = true;
	
	private $_dir_cache = NULL;
	
	private $_vc_cache = array();
	
	private $_initHreflang = false;
	
	private $_initChangeLang = false;
	
	private $_disRedirects = false;
	
	private $_toSave = array();
	
	/**
	 * Alias
	 *
	 * @var array
	 */
	private $_alias			= array(
		'product'		=> 'p',
		'category'		=> 'c',
		'manufacturer'	=> 'm',
		'information'	=> 'i',
	);

    public function __construct($registry) {
		parent::__construct( $registry );
		
		if( isset( $this->session->data['language'] ) ) {
			$query = $this->db->query( "SELECT * FROM `" . DB_PREFIX . "language` WHERE code ='" . $this->db->escape( $this->session->data['language'] ) . "' LIMIT 1" );
			
			if( $query->num_rows ) {
				$this->_config_language = $this->session->data['language'];
				$this->_config_language_id = $query->row['language_id'];
			}
		}
		
		if( ! $this->_config_language ) {
			$this->_config_language		= $this->config->get('config_language');
			$this->_config_language_id	= $this->config->get('config_language_id');
		}
		
		$this->_dir_cache = DIR_SYSTEM . '/cache/smp/';
    }
	
	public function getConfigLanguage() {
		return $this->_config_language;
	}
	
	public function getConfigLanguageId() {
		return $this->_config_language_id;
	}
	
	public function _setConfigLang( $language, $language_id ) {
		$this->_config_language = $language;
		$this->_config_language_id = $language_id;
	}
	
	public function _setDetectLanguageByURL( $detect_language ) {
		$this->_detect_language_by_url = $detect_language;
	}
	
	/**
	 * @param string $word
	 * @return string
	 */
    public static function classify( $word ) {
		$word = preg_replace('/[$]/', '', $word);
		
		return preg_replace_callback('~(_?)([-_])([\w])~', array( 'ControllerCommonSeoMegaPackProUrl', 'classifyCallback' ), ucfirst(strtolower($word)));
    }
	
	/**
	 * @param array $matches
	 * @return string
	 */
    public static function classifyCallback($matches) {
        return $matches[1] . strtoupper($matches[3]);
    }
	
	/**
	 * Pobierz listę dostępnych rozsrzeń
	 * Get list of available extensions
	 * 
	 * @return array
	 */
	private function getParseUrlExtensions() {
		/**
		 * Sprawdź czy lista nie została już wcześniej pobrana 
		 * Check if the list is earlier created
		 */
		if( $this->_parse_url_extensions === NULL ) {
			$dir			= DIR_SYSTEM . 'library/smk/extensions/';
			$extension_dir	= @ opendir( $dir );
			
			$this->_parse_url_extensions = array();

			while( false !== ( $extension = readdir( $extension_dir ) ) ) {
				/**
				 * Znajdź tylko pliki rozpoczynające się od "parse_url_" czyli klasy odpowiedzialne za interpretację adresów URL
				 * Find only files beginning with "parse_url_" that is responsible for the interpretation of URLs
				 */
				if( strpos( $extension, 'parse_url_' ) !== 0 ) continue;

				require_once VQmod::modCheck(realpath($dir . '/' . $extension));

				$extension	= str_replace( '.php', '', $extension );
				$classify	= self::classify( $extension );
				$class		= 'SeoMegaPack_' . $classify;
				$obj		= new $class( $this );

				/**
				 * Sprawdź czy moduł związany z tą klasą jest zainstalowany 
				 * Check whether the module associated with this class is installed
				 */
				if( $obj->installed() ) {
					$this->_parse_url_extensions[] = $obj;
				}
			}

			closedir( $extension_dir );
		}
		
		return $this->_parse_url_extensions;
	}
	
	private function parseUrlExt_part_1( $part, $parts, $params ) {
		foreach( $this->getParseUrlExtensions() as $ext ) {
			if( $ext->_part_1( $part, $parts, $params ) )
				return true;
		}
		
		return false;
	}
	
	private function parseUrlExt_part_2( $part, $parts, $params, $url ) {
		foreach( $this->getParseUrlExtensions() as $ext ) {
			if( $ext->_part_2( $part, $parts, $params, $url ) )
				return true;
		}
		
		return false;
	}
	
	private function parseUrlExt_part_3( $part, $parts, $params ) {
		foreach( $this->getParseUrlExtensions() as $ext ) {
			if( $ext->_part_3( $part, $parts, $params ) )
				return true;
		}
		
		return false;
	}
	
	private function vc( $string ) {
		if( isset( $this->_vc_cache[$string] ) )
			return $this->_vc_cache[$string];
		
		switch( $string ) {
			case 'product/manufacturer/info' : {
				if( version_compare( VERSION, '1.5.3.1', '<=' ) ) {
					$this->_vc_cache[$string] = 'product/manufacturer/product';
				}
				
				break;
			}
		}
		
		return isset( $this->_vc_cache[$string] ) ? $this->_vc_cache[$string] : $string;
	}
	
	/**
	 * Analize part
	 * 
	 * @param string $part
	 * @param array $parts
	 */
	private function _part( $part, $parts ) {
		$params = explode( ',', $part );
		
		switch( $params[0] ) {
			// product
			case $this->_alias['product'] : {
				if( isset( $params[1] ) ) {
					$this->request->get['route']		= 'product/product';
					$this->request->get['product_id']	= $params[1];
					
					if( isset( $parts[1] ) ) {
						$parts[1] = explode( ',', $parts[1] );
						
						if( isset( $parts[1][0] ) && isset( $parts[1][2] ) && $parts[1][0] == $this->_alias['category'] )
							$this->request->get['path']	= $parts[1][1];
					}
				} else {
					$this->request->get['route'] = 'error/not_found';
				}
				
				break;
			}
			// category
			case $this->_alias['category'] : {
				if( isset( $params[1] ) ) {
					$this->request->get['route']		= 'product/category';
					
					if( ! isset( $this->request->get['path'] ) )
						$this->request->get['path'] = $params[1];
					else
						$this->request->get['path']	.= '_' . $params[1];
				} else {
					$this->request->get['route'] = 'error/not_found';
				}
				
				break;
			}
			// manufacturer
			case $this->_alias['manufacturer'] : {
				if( isset( $params[1] ) ) {
					$this->request->get['route']			= $this->vc( 'product/manufacturer/info' );
					$this->request->get['manufacturer_id']	= $params[1];
				}
				
				break;
			}
			// information
			case $this->_alias['information'] : {
				if( isset( $params[1] ) ) {
					$this->request->get['route']			= 'information/information';
					$this->request->get['information_id']	= $params[1];
				} else {
					$this->request->get['route'] = 'error/not_found';
				}
				
				break;
			}
			// other
			default : {
				if( ! $this->parseUrlExt_part_1( $part, $parts, $params ) ) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");

					if( $query->num_rows ) {
						$url = explode('=', $query->row['query']);

						switch( $url[0] ) {
							case 'product_id' : {
								$this->request->get['route'] = 'product/product';
								$this->request->get['product_id'] = $url[1];

								break;
							}
							case 'category_id' : {
								$this->request->get['route'] = 'product/category';
								
								if( ! isset( $this->request->get['path'] ) ) {
									$this->request->get['path'] = $url[1];
								} else {
									$this->request->get['path'] .= '_' . $url[1];
								}

								break;
							}
							case 'manufacturer_id' : {
								$this->request->get['route'] = $this->vc( 'product/manufacturer/info' );
								$this->request->get['manufacturer_id'] = $url[1];

								break;
							}
							case 'information_id' : {
								$this->request->get['route'] = 'information/information';
								$this->request->get['information_id'] = $url[1];

								break;
							}
							default : {
								if( ! $this->parseUrlExt_part_2( $part, $parts, $params, $url ) ) {								
									if( isset( $url[1] ) ) {							
										unset( $parts[array_search( $part, $parts )] );

										$this->request->get['route'] = implode( '/', $parts );
										$this->request->get[$url[0]] = $url[1];
									} else {
										$this->request->get['route'] = $query->row['query'];
									}
								}

								break;
							}
						}
					} else {
						$this->request->get['route'] = 'error/not_found';
					}			

					/*if( isset( $this->request->get['product_id'] ) ) {
						$this->request->get['route'] = 'product/product';
					} else if( isset( $this->request->get['path'] ) ) {
						$this->request->get['route'] = 'product/category';
					} else if( isset( $this->request->get['manufacturer_id'] ) ) {
						$this->request->get['route'] = $this->vc( 'product/manufacturer/info' );
					} else if( isset($this->request->get['information_id'] ) ) {
						$this->request->get['route'] = 'information/information';
					} else*/ if( ! $this->parseUrlExt_part_3( $part, $parts, $params ) && isset( $this->request->get['route'] ) && $this->request->get['route'] == 'error/not_found' ) {
						$this->request->get['route'] = implode( '/', $parts );
					}
				}
			}
		}
	}
	
	private function isAjax() {
		return ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest';
	}
	
	private function isPost() {
		return isset( $_SERVER['REQUEST_METHOD'] ) && in_array( $_SERVER['REQUEST_METHOD'], array( 'post', 'POST' ) );
	}
	
	private function _redirect( $url ) {
		$url = $this->_parseLink( $url );
		
		if( ! $url ) {
			if( defined( 'HTTPS_SERVER' ) ) {
				$url = HTTPS_SERVER;
			} else if( defined( 'HTTP_SERVER' ) ) {
				$url = HTTP_SERVER;
			} else {
				$url = '/';
			}
		}
		
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: " . $url);
		exit();
	}
	
	public function __resetCache() {
		$this->_languages		= NULL;
		$this->_languages_data	= NULL;
		$this->_language		= NULL;
		$this->_categories		= NULL;
		$this->_products		= NULL;
		$this->_counts			= array();
		$this->_url_alias		= NULL;
		$this->_manufacturers	= NULL;
		$this->_informations	= NULL;
		
		return $this;
	}
	
	private function __initLang() {		
		if( $this->_languages === NULL ) {
			$this->load->model( 'localisation/language' );
			
			$this->_languages = array();
			$this->_languages_data = array();
			
			foreach( $this->model_localisation_language->getLanguages() as $lang ) {
				$this->_languages[$lang['language_id']] = $lang['code'];
				$this->_languages_data[$lang['code']] = $lang;
			}
			
			if( $this->_language === NULL ) {
				$this->_language = $this->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$this->config->get('config_store_id') . "' AND `code` = 'config' AND `key` = 'config_language'");

				if( $this->_language->num_rows ) {
					$this->_language = $this->_language->row['value'];
				} else {
					$this->_language = $this->_config_language;
				}
			}
		}
	}
	
	private function skipURLs( $route = NULL ) {
		if( $route === NULL ) {
			if( isset( $this->request->get['route'] ) ) {
				$route = $this->request->get['route'];
			} else if( isset( $this->request->get['_route_'] ) ) {
				$route = $this->request->get['_route_'];
			} else {
				$route = 'common/home';
			}
		}
		
		if( ! $route || ! $this->config->get( 'smp_skip_urls' ) )
			return false;
		
		$urls = (array) $this->config->get( 'smp_skip_urls' );
		
		if( $urls ) {
			foreach( $urls as $url ) {
				if( mb_strpos( $route, $url, 0, 'utf-8' ) === 0 )
					return true;
			}
		}
		
		return false;
	}
	
	public static function getFullPageUrl() {
		$url = 'http';
		
		if( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) {
			$url .= 's';
		}
		
		$url .= '://';
		
		if( ! empty( $_SERVER['SERVER_NAME'] ) ) {
			$url .= $_SERVER['SERVER_NAME'];
		}
		
		if( ! empty( $_SERVER['SERVER_PORT'] ) && $_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443' ) {
			$url .= ':' . $_SERVER['SERVER_PORT'];
		}
		
		if( ! empty( $_SERVER['REQUEST_URI'] ) ) {
			$url .= $_SERVER['REQUEST_URI'];
		}
		
		return $url;
	}
	
	private function __createPath( $url ) {
		$url	= parse_url( $url );
		$path	= '';
		
		if( ! empty( $url['scheme'] ) && ! empty( $url['host'] ) ) {
			$path .= $url['scheme'];
			$path .= '://';
			$path .= $url['host'];
			$path .= empty( $url['port'] ) ? '' : ':' . $url['port'];
		}
		
		$path .= empty( $url['path'] ) ? '/' : $url['path'];
		
		return $path;
	}
	
	public function index() {
		if( ! $this->isAjax() && ! $this->skipURLs() && ! $this->isPost() ) {
			if( ! isset( $this->request->get['_route_'] ) ) {			
				if( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], 'index.php?' ) ) {
					$this->_redirect( $this->rewrite( $_SERVER['REQUEST_URI'] ) );
				}
			} else if( isset( $_SERVER['REQUEST_URI'] ) && strpos( str_replace( '&amp;', '&', $_SERVER['REQUEST_URI'] ), '&filter=' ) ) {
				if( ! $this->config->get( 'smp_disable_convert_urls' ) ) {
					$this->_redirect( $this->rewrite( $_SERVER['REQUEST_URI'] ) );
				}
			}
			
			if( isset( $_SERVER['REQUEST_URI'] ) ) {
				if( trim( $_SERVER['REQUEST_URI'], '/' ) == 'index.php' ) {
					$this->_redirect( '/' );
				} else if( strpos( $_SERVER['REQUEST_URI'], 'index.php' ) !== false ) {
					$this->_redirect( str_replace( array( '/index.php/', '/index.php', 'index.php' ), '/', $_SERVER['REQUEST_URI'] ) );
				}
			}
		}
		
		if( isset( $_SERVER['REQUEST_URI'] ) ) {
			$fullPageUrl = self::getFullPageUrl();
			$pathOfFullPageUrl = $this->__createPath( $fullPageUrl );
			$pathOfRequestUri = $this->__createPath( $_SERVER['REQUEST_URI'] );
			
			$redirect = $this->db->query("
				SELECT
					new_link
				FROM
					" . DB_PREFIX . "redirects_smp
				WHERE
					broken_link LIKE '" . $this->db->escape( $pathOfFullPageUrl ) . "' OR
					broken_link LIKE '" . $this->db->escape( $pathOfRequestUri ) . "' OR
					broken_link LIKE '" . $this->db->escape( urldecode( $pathOfFullPageUrl ) ) . "' OR
					broken_link LIKE '" . $this->db->escape( urldecode( $pathOfRequestUri ) ) . "' OR
					broken_link LIKE '" . $this->db->escape( htmlentities( $pathOfFullPageUrl, ENT_QUOTES, 'UTF-8' ) ) . "' OR
					broken_link LIKE '" . $this->db->escape( htmlentities( $pathOfRequestUri, ENT_QUOTES, 'UTF-8' ) ) . "' OR
					broken_link LIKE '" . $this->db->escape( htmlentities( urldecode( $pathOfFullPageUrl ), ENT_QUOTES, 'UTF-8') ) . "' OR
					broken_link LIKE '" . $this->db->escape( htmlentities( urldecode( $pathOfRequestUri ), ENT_QUOTES, 'UTF-8') ) . "'
				LIMIT 1
			");
			
			if( $redirect->num_rows ) {
				$this->_redirect( $redirect->row['new_link'] );
			}
		}
		
		// Add rewrite to url class
		if( $this->config->get('config_seo_url') ) {
			$this->url->addRewrite($this);
		}
		
		// Languages
		$this->__initLang();
		
		// Decode URL
		if( isset( $this->request->get['_route_'] ) ) {
			$route		= trim( $this->request->get['_route_'], '/' );
			$params		= '';
			
			if( false !== ( $pos = mb_strpos( $route, ';', 0, 'utf-8' ) ) ) {
				$params = mb_substr( $route, $pos+1, mb_strlen( $route, 'utf-8' ), 'utf-8' );
				$route	= mb_substr( $route, 0, $pos, 'utf-8' );
			} else if( false !== ( $pos = mb_strpos( $route, '&', 0, 'utf-8' ) ) ) {
				$params = str_replace( array( '&', '=' ), ';', mb_substr( $route, $pos+1, mb_strlen( $route, 'utf-8' ), 'utf-8' ) );
				$route	= mb_substr( $route, 0, $pos, 'utf-8' );
			}
			
			$parts		= explode( '/', $route );
			
			if( $params ) {
				$params = explode( ';', $params );

				for( $i = 1; $i < count( $params ); $i+=2 ) {
					$this->request->get[$params[$i-1]] = str_replace( array( 'Ow==', 'Lw==' ), array( ';', '/' ), $params[$i] );
				}
			}
			
			if( isset( $parts[0] ) ) {
				// Remove language from parts
				if( in_array( $parts[0], $this->_languages ) ) {
					$language	= array_shift( $parts );
					
					$this->request->get['_route_'] = preg_replace( '#^' . $language . '/?#', '', $this->request->get['_route_'] );
					
					$this->_changeLang( $language );
					
					if( empty( $this->request->get['_route_'] ) ) {
						unset( $this->request->get['_route_'] );
						$parts = array();
					}
				}
				
				foreach( $parts as $part ) {
					$this->_part( $part, $parts );
				}
				
				// utwórz mapę kategorii (jeśli włączona jest opcja "krótkie adresy dla produktów" w URL nie ma listy kategorii na podstawie których tworzony jest breadcrumb)
				if( empty( $this->request->get['path'] ) && $this->config->get( 'smp_short_product_urls' ) && isset( $this->request->get['product_id'] ) ) {
					if( NULL !== ( $product = $this->_getProduct( $this->request->get['product_id'] ) ) ) {
						if( $product['category_id'] && NULL != ( $categoryMap = $this->_getCategoryMap( $product['category_id'] ) ) ) {
							$this->request->get['path'] = implode( '_', $categoryMap );
						}
					}
				} else
				// utwórz mapę kategorii (jeśli włączona jest opcja "krótkie adresy dla kategorii" w URL jest tylko ostatnia kategoria)
				if( ! empty( $this->request->get['path'] ) && $this->config->get( 'smp_short_category_urls' ) && strpos( $this->request->get['path'], '_' ) === false ) {
					if( NULL != ( $category = $this->_getCategory( $this->request->get['path'] ) ) ) {
						if( NULL != ( $categoryMap = $this->_getCategoryMap( $this->request->get['path'] ) ) ) {
							$this->request->get['path'] = implode( '_', $categoryMap );
						}
					}
				}
			} else if( $this->config->get( 'smp_set_default_language_in_main_url' ) ) {
				$this->_changeLang( $this->_language );
			}
			
			if( isset( $this->request->get['route'] ) ) {
				$_GET['route'] = $this->request->get['route'];
				
				if( ! $this->isPost() && $route != ( $alias = $this->_getUrlAliasByQuery( $this->request->get['route'] ) ) && $alias != NULL ) {
					if( $this->config->get( 'smp_add_default_language_code_to_url' ) || $this->_config_language != $this->_language ) {
						$alias = $this->_config_language . '/' . $alias;
					}
					
					if( $route != $alias ) {
						unset( $this->request->get['_route_'] );
						unset( $this->request->get['route'] );
						
						$path	= parse_url( HTTP_SERVER );
						$url	= $path['path'] . $alias . $this->_parseLink( $this->request->get );
						
						return $this->_disRedirects ? $url : $this->response->redirect( $url );
					}
				}
				
				if( $this->request->get['route'] == 'error/not_found' ) {
					self::notFound( $this );
				}
 				
				if( ! $this->_initHreflang ) {
					if( $this->config->get( 'smp_auto_redirect_to_canonical_urls' ) && ! $this->isPost() && ! $this->isAjax() && ! $this->skipURLs() && ! isset( $this->request->get['mfp_seo_alias'] ) ) {
						$canonical = '';
						$parameters = array(
							'product/product' => array( 'product_id', 'path' )
						);

						foreach( $this->request->get as $k => $v ) {
							if( in_array( $k, array( 'route', '_route_' ) ) ) continue;
							
							if( isset( $parameters[$this->request->get['route']] ) && ! in_array( $k, $parameters[$this->request->get['route']] ) ) continue;

							$canonical .= $canonical ? '&' : '';
							$canonical .= $k . '=' . urlencode( $v );
						}

						$canonical = $this->url->link( $this->request->get['route'], $canonical, empty( $_SERVER['HTTPS'] ) ? NULL : 'SSL' );
						
						$pCanonical = parse_url( urldecode( $canonical ) );
						$pCurrent = parse_url( urldecode( $_SERVER['REQUEST_URI'] ) );
						
						if( ! empty( $pCanonical['path'] ) && ! empty( $pCurrent['path'] ) && $pCanonical['path'] != $pCurrent['path'] ) {
							return $this->response->redirect( $canonical, 301 );
						}
					}
					
					$this->__initHreflang();
				}
				
				return new Action( $this->request->get['route'] );
			}
		} else {			
			if( $this->config->get( 'smp_set_default_language_in_main_url' ) ) {
				$this->_changeLang( $this->_language );
			}
			
			if( ! $this->_initHreflang ) {
				$this->__initHreflang();
			}
		}
	}
	
	private function _changeLang( $language ) {		
		if( $this->_initChangeLang ) {
			return;
		}
		
		$this->_initChangeLang = true;
		
		if( $this->_detect_language_by_url ) {
			if( ( isset( $this->session->data['language'] ) && $language != $this->session->data['language'] ) || ( isset( $this->request->cookie['language'] ) && $language != $this->request->cookie['language'] ) ) {
				$this->session->data['language']	= $language;
				$this->request->cookie['language']	= $language;

				setcookie('language', $language, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);

				$lang = new Language($this->_languages_data[$language]['directory']);
				$lang->load('default');
				$this->registry->set('language', $lang);

				if( version_compare( VERSION, '2.2.0.0', '<' ) ) {
					$this->config->set( 'config_language', $this->session->data['language'] );
					$this->config->set( 'config_language_id', array_search( $this->session->data['language'], $this->_languages ) );
				}

				$this->_config_language	= $this->session->data['language'];
				$this->_config_language_id = array_search( $this->session->data['language'], $this->_languages );
			}
		}
	}
	
	private function __initHreflang() {
		$this->_initHreflang = true;
		
		if( ! $this->config->get( 'smp_use_hreflang' ) ) {
			return;
		}
		
		if( $this->_languages === NULL ) {
			$this->__initLang();
		}
		
		if( count( $this->_languages ) < 2 ) {
			return;
		}
		
		$route	= isset( $this->request->get['route'] ) ? $this->request->get['route'] : 'common/home';
		$get	= $this->request->get;
		$params	= array();
		$html	= '';
		
		foreach( $get as $k => $v ) {
			if( ! in_array( $k, array( 'route', '_route_' ) ) ) {
				$params[] = $k . '=' . urlencode( $v );
			}
		}		
		
		$_langs		= $this->_languages;
		$_lang		= $this->_config_language;
		$_lang_id	= array_search( $_lang, $this->_languages );
		
		$this->_disRedirects = true;
		
		foreach( $_langs as $lang_id => $lang ) {
			if( $this->_config_language == $lang ) continue;
			
			$this->__resetCache();
			$this->url->resetRewrites();
			$this->_setConfigLang( $lang, $lang_id );
			$this->index();
			$this->request->get = $get;
			
			$html .= sprintf( '<link rel="alternate" href="%s" hreflang="%s" />', $this->url->link( $route, implode( '&', $params ) ), $lang );
		}
		
		$this->__resetCache();
		$this->url->resetRewrites();
		$this->_setConfigLang( $_lang, $_lang_id );
		$this->index();
		$this->request->get = $get;
		
		$this->_disRedirects = false;
		
		$this->config->set('__SMP_HREFLANG', $html);
	}
	
	public static function notFound( $ctrl ) {
		if( ! $ctrl->config->get( 'smp_store_incorrect_urls' ) || self::$_notFound ) return;
		
		$fullURL = self::getFullPageUrl();
		$query = $ctrl->db->query("SELECT * FROM " . DB_PREFIX . "failed_url_smp WHERE url LIKE '" . $ctrl->db->escape( $fullURL ) . "'");
		
		if( $query->num_rows ) {
			$ctrl->db->query("UPDATE " . DB_PREFIX . "failed_url_smp SET frequency = frequency + 1 WHERE failed_url_id='" . (int) $query->row['failed_url_id'] . "'");
		} else {
			$ctrl->db->query("INSERT INTO " . DB_PREFIX . "failed_url_smp SET url='" . $ctrl->db->escape( $fullURL ) . "', frequency=1, created_at=NOW()");
		}
		
		self::$_notFound = true;
	}
	
	private function _parseLink( $url ) {
		$args		= array();
		$convert	= ! $this->config->get( 'smp_disable_convert_urls' );
		
		if( ! is_array( $url ) ) {
			if( ! $convert || strpos( $url, '/' . self::adminDir() . '/index.php' ) !== false )
				return $url . '';
			
			$path = '';
			
			if( strpos( $url, '?' ) !== false ) {
				$url	= explode( '?', $url );
				$path	= isset( $url[1] ) ? $url[1] : '';
				$url	= $url[0];
			} else if( false !== ( $pos = mb_strpos( $url, '&', 0, 'utf-8' ) ) ) {
				$path	= mb_substr( $url, $pos+1, mb_strlen( $url, 'utf-8' ), 'utf-8' );
				$url	= mb_substr( $url, 0, $pos, 'utf-8' );
			}
			
			$path = rtrim( $path, '/' );
			
			parse_str( $path, $args );
		} else {
			$args	= $url;
			$url	= '';
		}
		
		$path	= '';
		
		foreach( $args as $name => $value ) {
			if( $value === '' ) continue;
						
			if( $convert ) {
				$path .= $path ? ';' : '';
				$path .= $name . ';' . ( $value == '{page}' ? $value : urlencode( str_replace( array( ';', '/' ), array( 'Ow==', 'Lw==' ), $value ) ) );
			} else {
				$path .= $path ? '&' : '';
				$path .= $name . '=' . ( $value == '{page}' ? $value : urlencode( (string) $value ) );
			}
		}
		
		$url = $convert ? $url . rtrim( $path ? ';' . $path : '', '/' ) : $url . rtrim( $path ? '?' . $path : '', '/' );
		
		if( ! $url && ! $this->config->get( 'smp_add_slash_at_the_end_urls' ) ) {
			$url .= '/';
		}
		
		return $url;
	}
	
	private function parseUrlExt_rewrite( & $url_data, & $url ) {		
		foreach( $this->getParseUrlExtensions() as $ext ) {
			if( $ext->_rewrite( $url_data, $url ) )
				return true;
		}
		
		return false;
	}
	
	/**
	 * Rewrite URL
	 * 
	 * @param string $link
	 * @return string
	 */
	public function rewrite( $link ) {
		$url_info	= parse_url( str_replace( '&amp;', '&', $link ) );
		
		if( ! isset( $url_info['query'] ) )
			return $this->__url( $url_info, '' );
		
		$url_data	= array();
		
		parse_str( $url_info['query'], $url_data );
		
		if( ! isset( $url_data['route'] ) )
			return $this->__url( $url_info, '' );
		
		/**
		 * Sprawdź czy pominąć URL (tj. czy powinien zostać bez zmian) 
		 * Check whether to skip URL (ie whether it should be no change)
		 */
		if( $this->skipURLs( $url_data['route'] ) )
			return $link;
		
		$url	= '';
		
		switch( $url_data['route'] ) {
			// home
			case 'common/home' : {
				if( count( $url_data ) > 1 ) {
					$url .= 'common/home';
				}
				
				break;
			}
			// product
			case 'product/product' : {				
				if( isset( $url_data['product_id'] ) ) {					
					if( ! $this->config->get( 'smp_short_product_urls' ) && NULL != ( $product = $this->_getProduct( $url_data['product_id'] ) ) ) {
						$p_cat_id = $product['category_id'];
						
						if( $this->config->get( 'smp_pr_url_by_cat_url' ) && isset( $url_data['path'] ) ) {
							$p_cat_id = explode( '_', $url_data['path'] );
							$p_cat_id = array_pop( $p_cat_id );
						}
						
						$url .= $this->_getCategoryUrl( $p_cat_id );
						$url .= '/';
					}
					
					if( isset( $url_data['path'] ) ) {
						unset( $url_data['path'] );
					}
					
					if( isset( $url_data['manufacturer_id'] ) ) {
						if( ( ! $this->config->get( 'smp_short_product_urls' ) || ! $this->config->get( 'smp_short_product_urls_manufacturers' ) ) && NULL != ( $manufacturer = $this->_getManufacturer( $url_data['manufacturer_id'] ) ) ) {
							if( NULL != ( $alias = $this->_getUrlAliasByQuery('manufacturer_id', $url_data['manufacturer_id']) ) ) {
								$url .= $alias . '/';
							} else {
								$url .= $this->_alias['manufacturer'] . ',' . $url_data['manufacturer_id'] . ',' . $manufacturer . '/';
							}
						}
						
						unset( $url_data['manufacturer_id'] );
					}
					
					if( NULL != ( $alias = $this->_getUrlAliasByQuery('product_id', $url_data['product_id']) ) ) {
						$url .= $alias;
					} else {					
						$url .= $this->_alias['product'] . ',' . $url_data['product_id'];
						
						if( ! isset( $product ) ) {
							$product = $this->_getProduct( $url_data['product_id'] );
						}

						if( isset( $product ) ) {
							if( $product != NULL )
								$url .= ',' . $product['name'];

							if( NULL != ( $manufacturer = $this->_getManufacturer( $product['manufacturer_id'] ) ) )
								$url .= ',' . $manufacturer;
						}

						if( ! preg_match( '/\.html$/', $url ) ) {
							$url .= '.html';
						}
					}
					
					unset( $url_data['product_id'] );
				}
				
				break;
			}
			// category
			case 'product/category' : {
				if( ! isset( $url_data['path'] ) )
					$url_data['path'] = 0;
				
				$url_data['path']	= array_reverse( explode( '_', $url_data['path'] ) );
				
				$url .= $this->_getCategoryUrl( $url_data['path'][0], '/', true );
				
				unset( $url_data['path'] );
				
				break;
			}
			// manufacturer/product
			case $this->vc( 'product/manufacturer/info' ) : {
				if( ! isset( $url_data['manufacturer_id'] ) )
					$url_data['manufacturer_id'] = 0;
				
				if( NULL != ( $alias = $this->_getUrlAliasByQuery('manufacturer_id', $url_data['manufacturer_id'] ) ) ) {
					$url .= $alias;
				} else {
					$url .= '/' . $this->_alias['manufacturer'] . ',' . $url_data['manufacturer_id'];

					if( NULL != ( $manufacturer = $this->_getManufacturer( $url_data['manufacturer_id'] ) ) )
						$url .= ',' . $manufacturer;
				}
				
				unset( $url_data['manufacturer_id'] );
				
				break;
			}
			// information
			case 'information/information' : {
				if( ! isset( $url_data['information_id'] ) )
					$url_data['information_id'] = 0;
				
				if( NULL != ( $alias = $this->_getUrlAliasByQuery('information_id', $url_data['information_id'] ) ) ) {
					$url .= $alias;
				} else {
					$url .= '/' . $this->_alias['information'] . ',' . $url_data['information_id'];

					if( NULL != ( $info = $this->_getInformation( $url_data['information_id'] ) ) )
						$url .= ',' . $info;
				}
				
				unset( $url_data['information_id'] );
				
				break;
			}
			// return
			case 'account/return/add' : {
				$url .= '/account/return/add';
				
				break;
			}
			// other
			default : {
				if( ! $this->parseUrlExt_rewrite( $url_data, $url ) ) {
					$alias = NULL;

					if( count( $url_data > 1 ) ) {
						//reset( $url_data );
						//next( $url_data );
						end( $url_data );

						list( $f_k, $f_v ) = each( $url_data );

						if( NULL != ( $alias = $this->_getUrlAliasByQuery( $f_k, $f_v ) ) ) {
							$url .= $url_data['route'] . '/' . $alias;

							unset( $url_data[$f_k] );
						}
					}

					if( $alias == NULL ) {
						if( NULL != ( $alias = $this->_getUrlAliasByQuery( $url_data['route'] ) ) ) {
							$url .= $alias;
						} else {
							$url .= $url_data['route'];
						}
					}
				}
			}
		}
		
		unset( $url_data['route'] );
		
		if( isset( $url_data['manufacturer_id'] ) && NULL != ( $m_alias = $this->_getUrlAliasByQuery( 'manufacturer_id', $url_data['manufacturer_id'] ) ) ) {
			$parts		= explode( '/', $url );
			$last		= array_pop( $parts );
			$parts[]	= $m_alias;
			$parts[]	= $last;
			$url		= implode( '/', $parts );
			
			unset( $url_data['manufacturer_id'] );
		}
		
		return $this->__url( $url_info, $url . $this->_parseLink( $url_data ) );
	}
	
	private function __url( $url_info, $path ) {
		$path_info = parse_url( $path );
		
		if( isset( $path_info['path'] ) )
			$path = $path_info['path'];
		
		$return = '';
		
		if( isset( $url_info['scheme'] ) && isset( $url_info['host'] ) )
			$return .= $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '');
		
		if( ! isset( $url_info['path'] ) )
			$url_info['path'] = '';
		
		$return .= str_replace( '/index.php', '', $url_info['path'] );
		
		// init langugaes
		if( $this->_language === NULL )
			$this->__initLang();
		
		// add language
		if( $this->config->get( 'smp_add_default_language_code_to_url' ) || $this->_language != $this->_config_language ) {
			$return .= '/' . $this->_config_language;
		}
		
		if( $path )
			$return .= '/' . trim( $path, '/' );
		
		if( $return && $this->config->get( 'smp_add_slash_at_the_end_urls' ) ) {
			$return .= '/';
		}
		
		if( ! empty( $path_info['query'] ) ) {
			$return .= '?' . $path_info['query'];
		}
		
		return $return;
	}
	
	public function __destruct() {
		foreach( $this->_toSave as $file => $data ) {
			file_put_contents( $file, $data, LOCK_EX );
		}
	}
	
	/**
	 * Pobierz alias URL
	 * 
	 * @param string $key
	 * @param string $value
	 * @return string|null 
	 */
	public function _getUrlAliasByQuery( $key, $value = NULL ) {
		$v	= $value === NULL ? $key : $key . '=' . $value;
		$gn	= $this->gn();
		$q	= '
			SELECT
				*
			FROM
				' . DB_PREFIX . 'url_alias
			WHERE
				smp_language_id IS NULL OR smp_language_id = ' . (int) $this->_config_language_id . '
		';
		
		// sprawdź czy zostały wcześniej pobrane aliasy
		if( $this->_url_alias === NULL ) {
			$this->_url_alias			= array();
			$this->_counts['url_alias']	= $this->query('SELECT COUNT(*) AS c FROM ' . DB_PREFIX . 'url_alias')->row['c'];
		}
		
		// sprawdź czy ilość aliasów nie przekracza limitu (tj. maksymalnej ilości rekordów przechowywanych w 1 pliku cache)
		if( $this->_counts['url_alias'] > $this->_maxToAllCacheUrlAlias ) {
			$q	.= " AND query = '" . $this->db->escape( $v ) . "'";
			$gn	= substr( md5( $v ), 0, 2 );
		}
		
		// sprawdź czy dane są zapisane w cache
		if( ! isset( $this->_url_alias[$gn] ) )
			$this->_url_alias[$gn] = $this->_getCache( __FUNCTION__, $gn );
		
		$q .= ' ORDER BY smp_language_id ASC';
		
		// pobierz dane jeśli nie ma ich jeszcze w cache
		if( ! isset( $this->_url_alias[$gn][$v] ) ) {			
			$query = $this->db->query( $q );
			
			foreach( $query->rows as $item ) {
				$this->_url_alias[$gn][$item['query']]	= $this->_addCache( $item['keyword'] );
			}
			
			if( ! isset( $this->_url_alias[$gn][$v] ) )
				$this->_url_alias[$gn][$v] = $this->_addCache( false );
			
			$this->_setCache( $this->_url_alias[$gn], __FUNCTION__, $gn );
		}
		
		// brak danych
		if( $this->_url_alias[$gn][$v]['d'] === false )
			return NULL;
		
		return $this->_url_alias[$gn][$v]['d'];
	}
	
	/**
	 * Pobierz informacje o produkcie
	 * 
	 * @param string $product_id
	 * @return null 
	 */
	private function _getProduct( $product_id ) {
		$gn	= $this->gn();
		$q	= "
			SELECT
				p.product_id,
				p.manufacturer_id,
				pd.name,
				IF( p.smp_url_category_id IS NULL OR (
					SELECT
						c2s.category_id
					FROM
						" . DB_PREFIX . "category AS c
					INNER JOIN
						" . DB_PREFIX . "category_to_store c2s
					ON
						c.category_id = c2s.category_id AND c2s.store_id = " . (int) $this->config->get('config_store_id') . "
					WHERE
						c.status = '1' AND c.category_id = p.smp_url_category_id
					LIMIT
						1
				) IS NULL, ( 
					SELECT
						pc.category_id
					FROM
						" . DB_PREFIX . "product_to_category pc
					INNER JOIN
						" . DB_PREFIX . "category AS c
					ON
						c.category_id = pc.category_id AND c.status = '1'
					INNER JOIN
						" . DB_PREFIX . "category_to_store c2s
					ON
						c2s.category_id = pc.category_id AND c2s.store_id = " . (int) $this->config->get('config_store_id') . "
					WHERE
						pc.product_id = p.product_id
					ORDER BY 
						pc.category_id DESC
					LIMIT
						1
				), p.smp_url_category_id ) AS category_id
			FROM
				" . DB_PREFIX . "product p
			LEFT JOIN
				" . DB_PREFIX . "product_description pd
			ON
				pd.product_id = p.product_id
			WHERE
				pd.language_id = " . (int) $this->_config_language_id . " AND
				p.status = 1
		";
		
		if( $this->_products === NULL ) {			
			$this->_products			= array();
			$this->_counts['product']	= $this->query('SELECT COUNT(*) AS c FROM ' . DB_PREFIX . 'product p WHERE p.status = 1')->row['c'];
		}
		
		if( $this->_counts['product'] > $this->_maxToAllCache ) {
			$q .= ' AND p.product_id = ' . (int) $product_id;
			$gn = $this->gn( $product_id );
		}
		
		if( ! isset( $this->_products[$gn] ) )
			$this->_products[$gn] = $this->_getCache( __FUNCTION__, $gn );
		
		if( ! isset( $this->_products[$gn][$product_id] ) ) {
			$query = $this->db->query( $q );
			
			foreach( $query->rows as $item ) {
				$this->_products[$gn][$item['product_id']] = $this->_addCache( array(
					'name'					=> $this->_clearIfOn( $item['name'] ),
					'category_id'			=> $item['category_id'],
					'manufacturer_id'		=> $item['manufacturer_id']
				) );
			}
			
			if( ! isset( $this->_products[$gn][$product_id] ) )
				$this->_products[$gn][$product_id] = $this->_addCache( false );
			
			$this->_setCache( $this->_products[$gn], __FUNCTION__, $gn );
		}
		
		if( $this->_products[$gn][$product_id]['d'] === false )
			return NULL;
		
		return $this->_products[$gn][$product_id]['d'];
	}
	
	/**
	 * Pobierz informacje o kategorii
	 * 
	 * @param int $category_id
	 * @return array|null 
	 */
	private function _getCategory( $category_id ) {
		$gn	= $this->gn();
		$q	= "
			SELECT
				c.category_id,
				c.parent_id,
				cd.name
			FROM
				" . DB_PREFIX . "category c
			LEFT JOIN
				" . DB_PREFIX . "category_description cd
			ON
				cd.category_id = c.category_id AND cd.language_id = " . (int) $this->_config_language_id . "
			WHERE
				c.status = 1
		";
		
		if( $this->_categories === NULL ) {
			$this->_categories			= array();
			$this->_counts['category']	= $this->query('SELECT COUNT(*) AS c FROM ' . DB_PREFIX . 'category c WHERE c.status = 1')->row['c'];
		}
		
		if( $this->_counts['category'] > $this->_maxToAllCache ) {
			$q .= ' AND c.category_id = ' . (int) $category_id;
			$gn = $this->gn( $category_id );
		}
		
		if( ! isset( $this->_categories[$gn] ) )
			$this->_categories[$gn] = $this->_getCache( __FUNCTION__, $gn );
		
		if( ! isset( $this->_categories[$gn][$category_id] ) ) {
			$query = $this->db->query( $q );
			
			foreach( $query->rows as $item ) {
				$this->_categories[$gn][$item['category_id']] = $this->_addCache( array(
					'name'		=> $this->_clearIfOn( $item['name'] ),
					'parent_id'	=> $item['parent_id']
				) );
			}
			
			if( ! isset( $this->_categories[$gn][$category_id] ) )
				$this->_categories[$gn][$category_id] = $this->_addCache( false );
			
			$this->_setCache( $this->_categories[$gn], __FUNCTION__, $gn );
		}
		
		if( $this->_categories[$gn][$category_id]['d'] === false )
			return NULL;
		
		return $this->_categories[$gn][$category_id]['d'];
	}
	
	/**
	 * Pobierz informacje o marce
	 * 
	 * @param int $manufacturer_id
	 * @return array|NULL
	 */
	private function _getManufacturer( $manufacturer_id ) {
		$gn	= $this->gn();
		$q	= '
			SELECT
				manufacturer_id,
				name
			FROM
				' . DB_PREFIX . 'manufacturer
		';
		
		if( $this->_manufacturers === NULL ) {
			$this->_manufacturers			= array();
			$this->_counts['manufacturer']	= $this->query('SELECT COUNT(*) AS c FROM ' . DB_PREFIX . 'manufacturer')->row['c'];
		}
		
		if( $this->_counts['manufacturer'] > $this->_maxToAllCache ) {
			$q .= ' WHERE manufacturer_id = ' . (int) $manufacturer_id;
			$gn = $this->gn( $manufacturer_id );
		}
		
		if( ! isset( $this->_manufacturers[$gn] ) )
			$this->_manufacturers[$gn] = $this->_getCache( __FUNCTION__, $gn );
		
		if( ! isset( $this->_manufacturers[$gn][$manufacturer_id] ) ) {
			$query = $this->db->query( $q );
			
			foreach( $query->rows as $item ) {
				$this->_manufacturers[$gn][$item['manufacturer_id']] = $this->_addCache( $this->_clearIfOn( $item['name'] ) );
			}
			
			if( ! isset( $this->_manufacturers[$gn][$manufacturer_id] ) )
				$this->_manufacturers[$gn][$manufacturer_id] = $this->_addCache( false );
			
			$this->_setCache( $this->_manufacturers[$gn], __FUNCTION__, $gn );
		}
		
		if( $this->_manufacturers[$gn][$manufacturer_id]['d'] === false )
			return NULL;
		
		return $this->_manufacturers[$gn][$manufacturer_id]['d'];
	}
	
	/**
	 * Zapisz cache
	 * 
	 * @param string $name
	 * @param mixed $data
	 * @return void 
	 */
	private function setCache( $name, $data ) {
		if( ! $this->config->get( 'smp_cache' ) )
			return;
		
		$time	= $name . '.time';
		$dir	= DIR_SYSTEM . '/cache/smp/';
		
		$this->_toSave[$dir . $name] = self::serializeCache( $data );
		$this->_toSave[$dir . $time] = time() + 60 * 60 * 24;
	}
	
	/**
	 * Generuj nazwę pliku cache
	 * 
	 * @param string $name
	 * @return string 
	 */
	private function cname( $name ) {
		return md5( $name );
	}
	
	private static function unserializeCache( $data ) {
		if( ! $data ) {
			return NULL;
		}
		
		if( mb_substr( $data, 0, 1, 'utf8' ) != '#' ) {
			return NULL;
		}
		
		if( mb_substr( $data, -1, 1, 'utf-8' ) != '#' ) {
			return NULL;
		}
		
		$data = trim( $data, '#' );
		
		return unserialize( $data );
	}
	
	private static function serializeCache( $data ) {
		return '#' . serialize( $data ) . '#';
	}
	
	/**
	 * Odczytaj cache z pliku
	 * 
	 * @param string $name
	 * @return mixed|null 
	 */
	private function getCache( $name ) {
		if( ! $this->config->get( 'smp_cache' ) )
			return NULL;
		
		$time	= $name . '.time';
		$dir	= DIR_SYSTEM . '/cache/smp/';
		$data	= NULL;
			
		if( file_exists( $dir . $name ) && file_exists( $dir . $time ) ) {
			$expr = (int) file_get_contents( $dir . $time );
				
			if( $expr > time() ) {
				$data = self::unserializeCache( file_get_contents( $dir . $name ) );
			}
		}
		
		return $data;
	}
	
	/**
	 * Cachowane zapytanie do bazy
	 * 
	 * @param string $q
	 * @return stdClass
	 */
	private function query( $q ) {		
		if( $this->config->get( 'smp_cache' ) ) {
			$cname	= $this->cname( $q );
			$data	= $this->getCache( $cname );
			
			if( $data === NULL ) {
				$data = $this->db->query( $q );
				
				$this->setCache( $cname, $data );
			}
			
			return $data;
		}
		
		return $this->db->query( $q );
	}
	
	private function _setCache( $data, $method, $gn ) {
		if( ! $this->config->get( 'smp_cache' ) )
			return false;
		
		$name = 'cache.' . $this->config->get('config_store_id') . '.' . $this->_config_language_id . '.' . $method . '.' . $gn;
		
		$this->_toSave[$this->_dir_cache . $name] = self::serializeCache( $data );
		
		return true;
	}
	
	private function _getCache( $method, $gn ) {
		if( ! $this->config->get( 'smp_cache' ) )
			return NULL;
		
		$name = 'cache.' . $this->config->get('config_store_id') . '.' . $this->_config_language_id . '.' . $method . '.' . $gn;
		
		if( ! file_exists( $this->_dir_cache . $name ) )
			return NULL;		
		
		try {
			$data = self::unserializeCache( file_get_contents( $this->_dir_cache . $name ) );
		} catch( Exception $e ) {
			return NULL;
		}
		
		if( ! is_array( $data ) ) {
			return NULL;
		}
		
		$removed	= false;
		$time		= time();
		
		foreach( $data as $key => $val ) {
			if( $val['t'] < $time ) {
				unset( $data[$key] );
				$removed = true;
			}
		}
		
		if( $removed )
			$this->_setCache( $data, $method, $gn );
		
		return $data;
	}
	
	private function _addCache( $data ) {
		return array(
			'd'	=> $data,
			't' => time() + 60 * 60 * 24 // 24 godzin
		);
	}
	
	/**
	 * Grupowanie rekordów
	 * 
	 * @param int $item_id
	 * @return int 
	 */
	private function gn( $item_id = NULL ) {
		if( ! $item_id )
			return 0;
		
		return (int) ( $item_id / 100 );
	}
	
	private function _getInformation( $information_id ) {
		$gn	= $this->gn();
		$q	= '
			SELECT
				i.information_id,
				id.title
			FROM
				' . DB_PREFIX . 'information i
			LEFT JOIN
				' . DB_PREFIX . 'information_description id
			ON
				id.information_id = i.information_id AND id.language_id = ' . (int) $this->_config_language_id . '
			WHERE
				i.status = 1
		';
		
		if( $this->_informations === NULL ) {
			$this->_informations			= array();
			$this->_counts['information']	= $this->query('SELECT COUNT(*) AS c FROM ' . DB_PREFIX . 'information i WHERE i.status=1')->row['c'];
		}
		
		if( $this->_counts['information'] > $this->_maxToAllCache ) {
			$q .= ' AND i.information_id = ' . (int) $information_id;
			$gn = $this->gn( $information_id );
		}
		
		if( ! isset( $this->_informations[$gn] ) )
			$this->_informations[$gn] = $this->_getCache( __FUNCTION__, $gn );
		
		if( ! isset( $this->_informations[$gn][$information_id] ) ) {
			$query = $this->db->query( $q );
			
			foreach( $query->rows as $item ) {
				$this->_informations[$gn][$item['information_id']] = $this->_addCache( $this->_clearIfOn( $item['title'] ) );
			}
			
			if( ! isset( $this->_informations[$gn][$information_id] ) )
				$this->_informations[$gn][$information_id] = $this->_addCache( false );
			
			$this->_setCache( $this->_informations[$gn], __FUNCTION__, $gn );
		}
		
		if( $this->_informations[$gn][$information_id]['d'] === false )
			return NULL;
		
		return $this->_informations[$gn][$information_id]['d'];
	}
	
	/**
	 * Sprawdź czy usuwanie znaków specjalnych jest wyłaczone
	 * 
	 * @param string $str
	 * @return bool
	 */
	public function _clearIfOn( $str ) {		
		return self::_clear( $str, $this->config->get( 'smp_clear_on' ) );
	}
	
	/**
	 * Clear URL
	 * Wyczyść adres URL
	 * 
	 * @param string $str
	 * @param bool $clearOn
	 * @return string 
	 */
	static public function _clear( $str, $clearOn ) {
		// replace always
		$str = str_replace(array(
			'`', '~', '!', '@', '#', '$', '%', '^', '*', '(', ')', '+', '=', '[', '{', ']', '}', '\\', '|', ';', ':', "'", '"', ',', '<', '.', '>', '/', '?'
		), ' ', str_replace(array(
			'&'
		), array(
			'and'
		), htmlspecialchars_decode( $str )) );		
		
		if( ! $clearOn ) {
			return mb_strtolower( trim( preg_replace( '/-+/', '-', preg_replace( '/ +/', '-', $str ) ), '-' ), 'utf-8' );
		}
		
		$unPretty = array(
			'À', 'à', 'Á', 'á', 'Â', 'â', 'Ã', 'ã', 'Ä', 'ä', 'Å', 'å', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ǟ', 'ǟ', 'Ǻ', 'ǻ', 'Α', 'α', 'ъ', 'ạ', 'ả', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ', 'Ạ', 'Ả', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ', 'ά', 'Ά',
			'Ḃ', 'ḃ', 'Б', 'б',
			'Ć', 'ć', 'Ç', 'ç', 'Č', 'č', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Ч', 'ч', 'Χ', 'χ',
			'Ḑ', 'ḑ', 'Ď', 'ď', 'Ḋ', 'ḋ', 'Đ', 'đ', 'Ð', 'ð', 'Д', 'д', 'Δ', 'δ',
			'Ǳ',  'ǲ', 'ǳ', 'Ǆ', 'ǅ', 'ǆ', 
			'È', 'è', 'É', 'é', 'Ě', 'ě', 'Ê', 'ê', 'Ë', 'ë', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ę', 'ę', 'Ė', 'ė', 'Ʒ', 'ʒ', 'Ǯ', 'ǯ', 'Е', 'е', 'Э', 'э', 'Ε', 'ε', 'ẹ', 'ẻ', 'ẽ', 'ề', 'ế', 'ệ', 'ể', 'ễ', 'Ẹ', 'Ẻ', 'Ẽ', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ', 'έ', 'Έ',
			'Ḟ', 'ḟ', 'ƒ', 'Ф', 'ф', 'Φ', 'φ',
			'ﬁ', 'ﬂ', 
			'Ǵ', 'ǵ', 'Ģ', 'ģ', 'Ǧ', 'ǧ', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ǥ', 'ǥ', 'Г', 'г', 'Γ', 'γ',
			'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ж', 'ж', 'Х', 'х',
			'Ì', 'ì', 'Í', 'í', 'Î', 'î', 'Ĩ', 'ĩ', 'Ï', 'ï', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'И', 'и', 'Η', 'η', 'Ι', 'ι', 'ị', 'ỉ', 'Ị', 'Ỉ', 'ί', 'ή', 'Ή', 'ϊ', 'ΐ', 'Ϊ',
			'Ĳ', 'ĳ', 
			'Ĵ', 'ĵ',
			'Ḱ', 'ḱ', 'Ķ', 'ķ', 'Ǩ', 'ǩ', 'К', 'к', 'Κ', 'κ',
			'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Л', 'л', 'Λ', 'λ',
			'Ǉ', 'ǈ', 'ǉ', 
			'Ṁ', 'ṁ', 'М', 'м', 'Μ', 'μ',
			'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'Ñ', 'ñ', 'ŉ', 'Ŋ', 'ŋ', 'Н', 'н', 'Ν', 'ν',
			'Ǌ', 'ǋ', 'ǌ', 
			'Ò', 'ò', 'Ó', 'ó', 'Ô', 'ô', 'Õ', 'õ', 'Ö', 'ö', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ø', 'ø', 'Ő', 'ő', 'Ǿ', 'ǿ', 'О', 'о', 'Ο', 'ο', 'Ω', 'ω', 'ọ', 'ỏ', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ', 'Ọ', 'Ỏ', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ', 'ό', 'Ό', 'ώ', 'Ώ',
			'Œ', 'œ', 
			'Ṗ', 'ṗ', 'П', 'п', 'Π', 'π',
			'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Р', 'р', 'Ρ', 'ρ', 'Ψ', 'ψ',
			'Ś', 'ś', 'Ş', 'ş', 'Š', 'š', 'Ŝ', 'ŝ', 'Ṡ', 'ṡ', 'ſ', 'ß', 'С', 'с', 'Ш', 'ш', 'Щ', 'щ', 'Σ', 'σ', 'ς',
			'Ţ', 'ţ', 'Ť', 'ť', 'Ṫ', 'ṫ', 'Ŧ', 'ŧ', 'Þ', 'þ', 'Т', 'т', 'Ц', 'ц', 'Θ', 'θ', 'Τ', 'τ',
			'Ù', 'ù', 'Ú', 'ú', 'Û', 'û', 'Ũ', 'ũ', 'Ü', 'ü', 'Ů', 'ů', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ų', 'ų', 'Ű', 'ű', 'У', 'у', 'ụ', 'ủ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ', 'Ụ', 'Ủ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ', 'ύ', 'Ύ', 'ϋ', 'Ϋ',
			'В', 'в', 'Β', 'β',
			'Ẁ', 'ẁ', 'Ẃ', 'ẃ', 'Ŵ', 'ŵ', 'Ẅ', 'ẅ',
			'Ξ', 'ξ',
			'Ỳ', 'ỳ', 'Ý', 'ý', 'Ŷ', 'ŷ', 'Ÿ', 'ÿ', 'Й', 'й', 'Ы', 'ы', 'Ю', 'ю', 'Я', 'я', 'Υ', 'υ', 'ỵ', 'ỷ', 'ỹ', 'Ỵ', 'Ỷ', 'Ỹ',
			'Ź', 'ź', 'Ž', 'ž', 'Ż', 'ż', 'З', 'з', 'Ζ', 'ζ',
			'Æ', 'æ', 'Ǽ', 'ǽ', 'а', 'А',
		);
		$pretty   = array(
			'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'a', 'A',
			'B', 'b', 'B', 'b',
			'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'CH', 'ch', 'CH', 'ch',
			'D', 'd', 'D', 'd', 'D', 'd', 'D', 'd', 'D', 'd', 'D', 'd', 'D', 'd',
			'DZ', 'Dz', 'dz', 'DZ', 'Dz', 'dz',
			'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'e', 'E',
			'F', 'f', 'f', 'F', 'f', 'F', 'f',
			'fi', 'fl',
			'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g',
			'H', 'h', 'H', 'h', 'ZH', 'zh', 'H', 'h',
			'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'i', 'i', 'I', 'I', 'i', 'i', 'I', 'i', 'i', 'I',
			'IJ', 'ij',
			'J', 'j',
			'K', 'k', 'K', 'k', 'K', 'k', 'K', 'k', 'K', 'k',
			'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l',
			'LJ', 'Lj', 'lj',
			'M', 'm', 'M', 'm', 'M', 'm',
			'N', 'n', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'N', 'n', 'N', 'n', 'N', 'n',
			'NJ', 'Nj', 'nj',
			'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'o', 'O', 'o', 'O',
			'OE', 'oe',
			'P', 'p', 'P', 'p', 'P', 'p', 'PS', 'ps',
			'R', 'r', 'R', 'r', 'R', 'r', 'R', 'r', 'R', 'r',
			'S', 's', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 's', 'ss', 'S', 's', 'SH', 'sh', 'SHCH', 'shch', 'S', 's', 's',
			'T', 't', 'T', 't', 'T', 't', 'T', 't', 'T', 't', 'T', 't', 'TS', 'ts', 'TH', 'th', 'T', 't',
			'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'u', 'U', 'u', 'U',
			'V', 'v', 'V', 'v',
			'W', 'w', 'W', 'w', 'W', 'w', 'W', 'w',
			'X', 'x',
			'Y', 'y', 'Y', 'y', 'Y', 'y', 'Y', 'y', 'Y', 'y', 'Y', 'y', 'YU', 'yu', 'YA', 'ya', 'Y', 'y', 'y', 'y', 'y', 'Y', 'Y', 'Y',
			'Z', 'z', 'Z', 'z', 'Z', 'z', 'Z', 'z', 'Z', 'z',
			'AE', 'ae', 'AE', 'ae', 'a', 'A',
		);
		
		$str = mb_strtolower( str_replace( $unPretty, $pretty, $str ), 'utf-8' );
		$str = trim( preg_replace('/[^A-Z^a-z^0-9]+/','-', /*preg_replace('/([a-z\d])([A-Z])/','\1_\2', preg_replace('/([A-Z]+)([A-Z][a-z])/','\1_\2',*/$str/*))*/), '-');
		return preg_replace( '/-+/', '-', $str );
	}
	
	private function _getCategoryMap( $category_id ) {
		$ids	= array();
		
		if( ! $category_id )
			return $ids;
		
		$i		= 0;
		
		do {
			if( NULL != ( $category = $this->_getCategory( $category_id ) ) ) {
				$ids[] = $category_id;
				
				$category_id = $category_id != $category['parent_id'] ? $category['parent_id'] : NULL;
			} else {
				$category_id = NULL;
			}
			
			$i++;
		} while( $category_id && $i < 100 );
		
		return array_reverse( $ids );
	}
	
	private function _getCategoryUrl( $category_id, $sep = '/', $for_category = false ) {
		$ids	= array();
		$names	= array();
		$alias	= array();
		$url	= '';
		$i		= 0;
		
		do {
			if( NULL != ( $category = $this->_getCategory( $category_id ) ) ) {
				$db_alias		= $this->_getUrlAliasByQuery( 'category_id', $category_id );
				
				if( $db_alias != NULL ) {
					$alias[]	= $db_alias;
				} else {
					$names[]	= $category['name'];
					$ids[]		= $category_id;
				}
				
				$category_id = $category_id != $category['parent_id'] ? $category['parent_id'] : NULL;
			} else {
				$category_id = NULL;
			}
			
			$i++;
			
			if( $for_category && $this->config->get( 'smp_short_category_urls' ) ) {
				break;
			}
		} while( $category_id && $i < 100 );
		
		if( $alias ) {
			$alias = array_reverse( $alias );
			
			$url .= $sep . implode( $sep, $alias );
		}
		
		if( $ids && $names ) {
			$ids	= array_reverse( $ids );
			$names	= array_reverse( $names );
			
			$url .= $sep . $this->_alias['category'] . ',' . implode( '_', $ids ) . ',' . implode( ',', $names );
		}
		
		return $url;
	}
	
}