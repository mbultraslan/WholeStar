<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

abstract class SeoMegaPack_AbstractGenerator {
	
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
	 * @var ControllerModuleSeoMegaPack
	 */
	protected $_controller			= NULL;
	
	/**
	 * @var array 
	 */
	protected $_tags				= array();
	
	/**
	 * @var bool|array
	 */
	protected $_clearUrl			= true;
	
	/**
	 * @var bool|array
	 */
	protected $_previewUrl			= true;
	
	/**
	 * @var bool|array
	 */
	protected $_generateUrl			= true;
	
	/**
	 * @var string
	 */
	protected $_name				= NULL;
	
	/**
	 * @var string
	 */
	protected $_shortName			= NULL;
	
	/**
	 * @var string
	 */
	protected $_title				= NULL;
	
	/**
	 * @var string
	 */
	protected $_description			= NULL;
	
	/**
	 * @var int
	 */
	protected $_sort				= 100;
	
	/**
	 * @var array
	 */
	protected $_categories			= NULL;
	
	/**
	 * @var bool
	 */
	protected $_hide				= false;
	
	/**
	 * @var bool 
	 */
	protected $_multiLanguages		= true;
	
	/**
	 * @var string 
	 */
	protected $_group				= 'other';
	
	/**
	 * @var string 
	 */
	protected $_version				= '2';
	
	/**
	 * @var string 
	 */
	protected $_icon				= NULL;
	
	/**
	 * @var string 
	 */
	protected $_fieldName			= NULL;
	
	/**
	 * @var string 
	 */
	protected $_tableName			= NULL;
	
	/**
	 * @var bool 
	 */
	protected $_defaultSetParams	= true;
	
	/**
	 * @param type $controller
	 */
	public function __construct( $controller ) {
		$this->_controller = $controller;
	}
	
	/**
	 * @return mixed
	 */
	public function getParams() {
		return $this->config->get( 'smp_' . $this->shortName() . '_params' );
	}
	
	/**
	 * @return bool 
	 */
	public function hasDefaultSetParams() {
		return $this->_defaultSetParams;
	}
	
	/**
	 * @param mixed $parameters
	 * @return mixed
	 */
	public function parseParameters( $parameters ) {
		return $parameters;
	}
	
	public function resetCache() {
		//
	}
	
	public function getSentences( $desc, $sentences, $strip_tags = true ) {
		if( $strip_tags ) {
			$desc = htmlspecialchars_decode( $desc );
			$desc = preg_replace( '/<[^>]+>/', ' ', $desc );
			$desc = strip_tags( $desc );
			$desc = preg_replace( '/ +/', ' ', $desc );
		}
		
		$desc = explode( '.', $desc );
		
		$desc = implode( '.', array_slice( $desc, 0, $sentences ) );
		
		return $desc ? trim( $desc ) . '.' : '';
	}
	
	protected function sampleProducts( $type, $id, $language_id, $config ) {
		$value	= array();
		$query	= array(
			'category'	=> "
				SELECT
					DISTINCT p.product_id,
					pd.name,
					GROUP_CONCAT( ptc.category_id ) AS gc
				FROM
					" . DB_PREFIX . "product p
				LEFT JOIN
					" . DB_PREFIX . "product_description pd
				ON
					pd.product_id = p.product_id AND pd.language_id = " . $language_id . "
				LEFT JOIN
					" . DB_PREFIX . "product_to_category ptc
				ON
					ptc.product_id = p.product_id
				WHERE
					p.status = 1
				GROUP BY
					p.product_id
				HAVING
					FIND_IN_SET(" . $id . ", gc)
				ORDER BY
					RAND()
				LIMIT
					" . $config['total'] . "
			",
			'manufacturer' => "
				SELECT
					p.product_id,
					pd.name
				FROM
					" . DB_PREFIX . "product p
				LEFT JOIN
					" . DB_PREFIX . "product_description pd
				ON
					pd.product_id = p.product_id AND pd.language_id = " . $language_id . "
				WHERE
					p.status = 1 AND p.manufacturer_id = " . $id . "
				ORDER BY
					RAND()
				LIMIT
					" . $config['total'] . "
			"
		);
		
		foreach( $this->db->query( $query[$type] )->rows as $item ) {
			$value[] = $item['name'];
		}
		
		return implode( $config['sep'], $value );
	}
	
	/**
	 * Pobierz ilość wszystkich produktów wg typu
	 * 
	 * @param string $type - category
	 * @param id $id
	 * @return int 
	 */
	protected function totalProducts( $type, $id ) {
		$query = array(
			'category'	=> "
				SELECT
					COUNT(*) AS c
				FROM
					" . DB_PREFIX . "product_to_category ptc
				INNER JOIN
					" . DB_PREFIX . "product p
				ON
					ptc.product_id = p.product_id
				WHERE
					p.status = 1 AND ptc.category_id = " . $id . "
			",
			'manufacturer' => "
				SELECT
					COUNT(*) AS c
				FROM
					" . DB_PREFIX . "product p
				WHERE
					p.status = 1 AND manufacturer_id = " . $id . "
			"
		);
		
		return $this->db->query( $query[$type] )->row['c'];
	}
	
	protected function parseGenerated( $generate ) {
		return $generate;
	}
	
	protected function parseTagGenerated( $generate ) {
		$generate	= explode( ',', preg_replace( '/ +/', ',', preg_replace( '/,+/', ' ', $generate ) ) );
			
		foreach( $generate as $k => $v ) {
			$v = htmlspecialchars_decode( trim( $v ) );
				
			if( mb_strlen( $v, 'utf-8' ) < 3 ) {
				unset( $generate[$k] );
			}
		}
		
		return implode( ', ', array_unique( $generate ) );
	}
	
	/**
	 * Sprawdź czy tag ma synonimy
	 * 
	 * @param string $tag
	 * @return string 
	 */
	protected function tag( $tag ) {
		switch( $tag ) {
			case 'first_sentence' : {
				$tag = 'description';
				
				break;
			}
		}
		
		return $tag;
	}
	
	/**
	 * Zmiana parametrów podanych w polach konfiguracyjnych
	 * 
	 * @param int $language_id
	 * @return array
	 */
	protected function convertParamsToConfig( $language_id = NULL ) {
		$config		= $this->getParams( $language_id );
		
		if( is_array( $config ) )
			$config = isset( $config[$language_id] ) ? $config[$language_id] : '';
		
		$params		= array();
		
		$this->load->model('setting/setting');
		
		$global		= $this->model_setting_setting->getSetting( 'config' );
		$languages	= $this->_languages( true );
		$language	= $languages[$language_id];
		
		preg_match_all( '/\{[^}]+\}/', $config, $matches );
		
		if( empty( $matches[0] ) )
			return array();
		
		foreach( $matches[0] as $param ) {
			$conf	= explode( '#', trim( $param, '{}' ) );
			$name	= array_shift( $conf );
			
			$params[$param] = array(
				'tag'		=> $this->tag( $name ),
				'value'		=> NULL,
				'config'	=> array()
			);
			
			$key = NULL;
			foreach( $conf as $value ) {
				if( $key === NULL ) {
					$key = $value;
				} else {
					$params[$param]['config'][$key] = $value;
					$key = NULL;
				}
			}
		}
		
		foreach( $params as $search => $item ) {
			$value = $item['value'];
			
			switch( $item['tag'] ) {
				case 'sample_procuts' : {
					$item['config']['total']	= isset( $item['config']['total'] ) ? (int) $item['config']['total'] : 3;
					$item['config']['sep']		= isset( $item['config']['sep'] ) ? str_replace( '#', ' ', $item['config']['sep'] ) : ', ';
					
					if( $item['config']['total'] < 1 )
						$item['config']['total'] = 1;
					
					if( $item['config']['total'] > 50 )
						$item['config']['total'] = 50;
					
					break;
				}
				case 'description' : {
					$item['config']['sentences'] = isset( $item['config']['sentences'] ) ? (int) $item['config']['sentences'] : 1;
					
					if( $item['config']['sentences'] < 1 )
						$item['config']['sentences'] = 1;
					
					break;
				}
				case 'site_name' : {
					$value = $global['config_name'];
					
					break;
				}
				case 'site_title' : {
					if( is_array( $global['config_meta_title'] ) ) {
						$value = isset( $global['config_meta_title'][$language['code']] ) ? $global['config_meta_title'][$language['code']] : current( $global['config_meta_title'] );
					} else {
						$value = $global['config_meta_title'];
					}
					
					break;
				}
			}
			
			$item['value'] = $value;
			
			$params[$search] = $item;
		}
		
		return $params;
	}
	
	/**
	 * @param bool $preview
	 * @param int $start
	 * @param int $limit
	 */
	public function generate( $preview = false, $limit = NULL, $language_id = NULL, $mode = NULL ) {
		$results	= array();
		
		foreach( $this->_languages( true ) as $lang ) {
			if( $language_id !== NULL && $language_id != $lang['language_id'] ) continue;
			
			$results[$lang['language_id']] = $this->_generateByLang( $lang['language_id'], $preview, array(), $limit );
		}
		
		return $results;
	}
	
	public function _generateInfoByLang( $language_id ) {
		return 0;
	}
	
	/**
	 * Pobierz listę dostępnych języków
	 * 
	 * @return array
	 */
	protected function _languages( $full = false ) {
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
	 * 
	 */	
	public function generateInfo( $mode = NULL ) {
		$results	= array();
		
		foreach( $this->_languages( true ) as $language_id => $lang ) {
			$results[]	= array(
				'language_id'	=> $language_id,
				'image'			=> $lang['image'],
				'code'			=> $lang['code'],
				'name'			=> $lang['name'],
				'items'			=> $this->_generateInfoByLang( $language_id )
			);
		}
		
		return $results;
	}
	
	/**
	 * @return string 
	 */
	public function version() {
		return $this->_version;
	}
	
	/**
	 * @return string 
	 */
	public function group() {
		return $this->_group;
	}
	
	/**
	 * @return string
	 */
	public function name() {
		return $this->_name;
	}
	
	/**
	 * @return int
	 */
	public function sort() {
		return $this->_sort;
	}
	
	/**
	 * @return string
	 */
	public function shortName() {
		return $this->_shortName;
	}
	
	/**
	 * @return string
	 */
	public function title() {
		return $this->_title;
	}
	
	/**
	 * @return string
	 */
	public function description() {
		return $this->_description;
	}
	
	/**
	 * @return bool
	 */
	public function hide() {
		return $this->_hide;
	}
	
	/**
	 * @return bool 
	 */
	public function multiLanguages() {
		return $this->_multiLanguages;
	}
	
	/**
	 * @return bool
	 */
	public function hasAction() {
		return $this->generateUrl() || $this->previewUrl() || $this->clearUrl();
	}
	
	/**
	 * @return bool|array
	 */
	public function generateUrl() {
		if( ! $this->_generateUrl ) return NULL;
		
		return $this->_generateUrl;
	}
	
	/**
	 * @return bool|array
	 */
	public function previewUrl() {
		if( ! $this->_previewUrl ) return NULL;
		
		return $this->_previewUrl;
	}
	
	/**
	 * @return bool|array
	 */
	public function clearUrl() {
		if( ! $this->_clearUrl ) return NULL;
		
		return $this->_clearUrl;
	}
	
	/**
	 * @return void
	 */
	public function install() {
		$this->load->model('setting/setting');
		
		$this->model_setting_setting->editSetting('smp_' . $this->shortName() . '_is_install', array(
			'smp_' . $this->shortName() . '_is_install' => $this->version()
		));
	}
	
	public function installprogress( $index ) {
		
	}
	
	/**
	 * @return bool
	 */
	public function clear( $mode = NULL, $onlyAutoGenerated = false ) {
		if( ! $this->_tableName || ! $this->_fieldName )
			return false;
		
		$q = "
			UPDATE
				" . DB_PREFIX . $this->_tableName . "
			SET
				`" . $this->_fieldName . "` = '',
				`" . $this->_fieldName . "_ag` = '0'
		";
		
		if( $onlyAutoGenerated )
			$q .= "
				WHERE 
					`" . $this->_fieldName . "_ag` = '1'
			";
		
		$this->db->query( $q );
		
		return true;
	}
	
	/**
	 * Dodaj kolumnę do tabeli
	 * 
	 * @param string $column
	 * @param string $type
	 * @return bool
	 */
	protected function addColumn( $column, $type, $table = NULL ) {
		if( $table === NULL )
			$table = $this->_tableName;
		
		if( ! $table )
			return false;
		
		$query = $this->db->query('SHOW COLUMNS FROM ' . DB_PREFIX . $table . ' LIKE "' . $column . '"');
		
		if( ! $query->num_rows ) {
			$this->db->query( 'ALTER TABLE ' . DB_PREFIX . $table . ' ADD `' . $column . '` ' . $type );
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Aktualizuj kolumnę w tabeli
	 * 
	 * @param string $column
	 * @param string $type
	 * @return bool
	 */
	protected function updateColumn( $column, $type, $table = NULL ) {
		if( $table === NULL )
			$table = $this->_tableName;
		
		if( ! $table )
			return false;
		
		$query = $this->db->query('SHOW COLUMNS FROM ' . DB_PREFIX . $table . ' LIKE "' . $column . '"');
		
		if( ! $query->num_rows ) {
			return $this->addColumn( $column, $type, $table );
		} else {
			$this->db->query( 'ALTER TABLE ' . DB_PREFIX . $table . ' CHANGE `' . $column . '` `' . $column . '` ' . $type );
		}
		
		return false;
	}
	
	protected function _createTableManufacturerSMP() {
		$this->db->query('
			CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'manufacturer_smp` (
				`manufacturer_id` int(11) NOT NULL,
				`language_id` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
	}
	
	/**
	 * Usuń kolumnę z tabeli
	 * 
	 * @param string $column 
	 */
	protected function removeColumn( $column, $table = NULL ) {
		if( $table === NULL )
			$table = $this->_tableName;
		
		if( ! $table )
			return false;
		
		$query = $this->db->query('SHOW COLUMNS FROM ' . DB_PREFIX . $table . ' LIKE "' . $column . '"');
		
		if( $query->num_rows ) {
			$this->db->query('ALTER TABLE ' . DB_PREFIX . $table . ' DROP `' . $column . '`');
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * @return void 
	 */
	public function update() {
		return $this->install();
	}
	
	/**
	 * @return void
	 */
	public function uninstall() {
		$this->load->model('setting/setting');
		
		$this->model_setting_setting->deleteSetting('smp_' . $this->shortName() );
		$this->model_setting_setting->deleteSetting('smp_' . $this->shortName() . '_is_install' );
	}
	
	/**
	 * @return string
	 */
	public function tags( $sep = ',' ) {
		return implode( $sep, array_keys( $this->_tags ) );
	}
	
	/**
	 * @return string 
	 */
	public function printTags( $attrs = '', $tags = array(), $name = '' ) {
		$str = 'Available parameters:';
		$str .= '<div style="padding:5px 0;">';
		
		$tags = explode( ',', $tags ? $tags : $this->tags() ); 
		
		foreach( $tags as $tag ) {
			$str .= '<button ' . $attrs . ' type="button" data-insert-tag="' . $tag . '" data-name="' . $this->name() . $name . '" class="btn btn-xs btn-default">';
			$str .= '<i class="glyphicon glyphicon-plus-sign"></i>';
			$str .= $tag;
			$str .= '</button> ';
		}
		
		$str .= '</div>';
		
		return $str;
	}
	
	/**
	 * @return string 
	 */
	protected function _descriptionByCKEDITOR( $defaultValue ) {
		$languages	= $this->_languages( true );
		$str		= '';
		$params		= $this->getParams();
		
		if( ! is_array( $params ) )
			$params = array();
		
		$str .= '<ul class="nav nav-tabs">';
		
		foreach( $languages as $language ) {
			$str .= sprintf( '<li><a href="#desc-' . $this->name() . '-lang-%s" data-toggle="tab">%s</a></li>', $language['language_id'], $language['name'] );
		}
		
		$str .= '</ul>';
		$str .= '<div class="tab-content">';
		$str .= '<script type="text/javascript" src="view/smp/ckeditor/ckeditor.js"></script>';
		
		foreach( $languages as $language ) {
			$value	= isset( $params[$language['language_id']] ) ? $params[$language['language_id']] : $defaultValue;
			$name	= sprintf( 'extensions[%s][%s]', $this->name(), $language['language_id'] );
			
			$str .= sprintf( '<div class="tab-pane" id="desc-' . $this->name() . '-lang-%s"><br />', $language['language_id'] );
			$str .= $this->printTags( 'data-language-id="' . $language['language_id'] . '"' );
			$str .= sprintf( '<textarea class="form-control" style="height:150px" data-name="%s" name="%s" data-language-id="%s">%s</textarea>',
				$this->name(), $name, $language['language_id'], $value
			);
			$str .= '</div>';
			
			$str .= '<script type="text/javascript">';
			$str .= 'CKEDITOR.replace("' . $name . '", {';
			$str .= 'enterMode: CKEDITOR.ENTER_BR,';
			$str .= 'shiftEnterMode: CKEDITOR.ENTER_BR,';
			$str .= 'filebrowserBrowseUrl: "index.php?route=common/filemanager&token=' . $this->_controller->session->data['token'] . '",';
			$str .= 'filebrowserImageBrowseUrl: "index.php?route=common/filemanager&token=' . $this->_controller->session->data['token'] . '",';
			$str .= 'filebrowserFlashBrowseUrl: "index.php?route=common/filemanager&token=' . $this->_controller->session->data['token'] . '",';
			$str .= 'filebrowserUploadUrl: "index.php?route=common/filemanager&token=' . $this->_controller->session->data['token'] . '",';
			$str .= 'filebrowserImageUploadUrl: "index.php?route=common/filemanager&token=' . $this->_controller->session->data['token'] . '",';
			$str .= 'filebrowserFlashUploadUrl: "index.php?route=common/filemanager&token=' . $this->_controller->session->data['token'] . '"';
			$str .= '})';
			$str .= '</script>';
		}
		
		$str .= '</div>';
		
		return $str;
	}
	
	/**
	 * @return string 
	 */
	public function icon() {
		if( ! $this->_icon )
			return '';
		
		return '<i class="glyphicon glyphicon-' . $this->_icon . '"></i> ';
	}
	
	/**
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name ) {
		return $this->_controller->{$name};
	}
	
	/**
	 * @param string $name
	 * @param array $arguments
	 * @return mixed
	 */
	public function __call($name, $arguments) {
		return call_user_func_array(array( $this->_controller, $name), $arguments);
	}
	
	/**
	 * @param int $category_id
	 * @param int $language_id
	 * @param bool $desc
	 * @return null|array
	 */
	protected function _getCategory( $category_id, $language_id, $desc = false ) {
		if( $this->_categories === NULL ) {
			$query = $this->db->query('
				SELECT
					c.category_id,
					c.parent_id,
					cd.name,
					cd.description,
					cd.language_id
				FROM
					' . DB_PREFIX . 'category c
				LEFT JOIN
					' . DB_PREFIX . 'category_description cd
				ON
					cd.category_id = c.category_id
				WHERE
					c.status = 1
			');
			
			$this->_categories = array();
			
			foreach( $query->rows as $item ) {
				$this->_categories[$item['language_id']][$item['category_id']] = array(
					'name'		=> $item['name'],
					'parent_id'	=> $item['parent_id']
				);
				
				if( $desc )
					$this->_categories[$item['language_id']][$item['category_id']]['description'] = $item['description'];
			}
		}
		
		if( isset( $this->_categories[$language_id][$category_id] ) )
			return $this->_categories[$language_id][$category_id];
		
		return NULL;
	}
	
	/**
	 * @param int $category_id
	 * @param int $language_id
	 * @return array
	 */
	protected function _getCategoryTree( $category_id, $language_id ) {
		$names	= array();
		$i		= 0;
		
		do {
			if( NULL != ( $category = $this->_getCategory( $category_id, $language_id ) ) ) {
				$names[] = $category['name'];
				
				$category_id = $category['parent_id'] && $category['parent_id'] != $category_id ? $category['parent_id'] : NULL;
			} else {
				$category_id = NULL;
			}
			
			$i++;
		} while( $category_id && $i < 100 );
		
		return array_reverse( $names );
	}
}