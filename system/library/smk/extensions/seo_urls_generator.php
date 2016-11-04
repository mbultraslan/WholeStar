<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_generator.php' );

class SeoMegaPack_SeoUrlsGenerator extends SeoMegaPack_AbstractGenerator {
	
	/**
	 * @var string
	 */
	protected $_name		= 'seo_urls_generator';
	protected $_shortName	= 'sug';
	
	/**
	 * @var string
	 */
	protected $_title		= 'SEO Urls Generator';
	
	/**
	 * @var int
	 */
	protected $_sort		= 5;
	
	protected $_url_alias	= array();
	
	/**
	 * @var string 
	 */
	protected $_group		= 'url';
	
	/**
	 * @var string 
	 */
	protected $_icon		= 'link';
	
	/**
	 * @var string 
	 */
	protected $_version		= '2';
	
	/**
	 * @var bool|array
	 */
	protected $_clearUrl		= array(
		'product', 'category', 'manufacturer', 'information'
	);
	
	/**
	 * @var bool|array
	 */
	protected $_previewUrl		= array(
		'product', 'category', 'manufacturer', 'information'
	);
	
	/**
	 * @var bool|array
	 */
	protected $_generateUrl		= array(
		'product', 'category', 'manufacturer', 'information'
	);
	
	/**
	 * @var array 
	 */
	protected $_supportUrlAliasExists = array( 
		'product', 'manufacturer', 'information', 'category' 
	);
	
	protected function _getUrlAlias( $rows, $field, $type, array $types = array() ) {
		$keywords	= array();
		$query		= array();		
		
		foreach( $rows as $row ) {
			$keyword = $this->_clearIfOn( $row[$field] )/* . $ext*/;
			
			$keywords[] = $keyword;
			$query[]	= "'" . $this->db->escape( $keyword ) . "'";
			
			/////////////////////////////////////////////
			
			if( isset( $row['id'] ) ) {
				$keyword = $this->_clearIfOn( $row[$field] ) . '-' . $row['id']/* . $ext*/;

				$keywords[] = $keyword;
				$query[]	= "'" . $this->db->escape( $keyword ) . "'";
			}
		}
		
		if( $query ) {
			foreach( $this->db->query( "SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword IN(" . implode( ',', $query ) . ")" )->rows as $item ) {
				$this->_url_alias[$item['keyword']] = array(
					'query'				=> $item['query'],
					'smp_language_id'	=> $item['smp_language_id'],
					'url_alias_id'		=> $item['url_alias_id']
				);
			}
		}
		
		foreach( $keywords as $keyword ) {
			if( ! isset( $this->_url_alias[$keyword] ) )
				$this->_url_alias[$keyword] = false;
		}
	}
	
	/**
	 * @param string $keyword
	 * @return [0,1,-1]
	 */
	protected function _keywordIsUnique( $keyword, $key, $value ) {		
		if( isset( $this->_url_alias[$keyword] ) && $this->_url_alias[$keyword] !== false ) {
			if( $this->_url_alias[$keyword]['query'] == $key . '=' . $value ) {
				//if( $this->_url_alias[$keyword]['smp_language_id'] !== NULL ) {
					//$this->_url_alias[$keyword]['smp_language_id'] = NULL;
					
					//$this->db->query( 'UPDATE ' . DB_PREFIX . 'url_alias SET smp_language_id = NULL WHERE url_alias_id = ' . $this->_url_alias[$keyword]['url_alias_id'] );
				//}
				
				return 1;
			} else {
				return 0;
			}
		}
		
		return 1;
	}
	
	protected function _clearIfOn( $str ) {
		require_once VQMod::modCheck(modification(realpath( 
			DIR_APPLICATION . '../catalog/controller/common/seo_mega_pack_pro_url.php'
		)));
		
		return ControllerCommonSeoMegaPackProUrl::_clear( $str, $this->config->get( 'smp_clear_on' ) );
	}
	
	protected function _createUniqueKeyword( $name, $type, $id, array $types = array() ) {
		$nKeyword = 0;
		$ext = '';
		
		if( false !== ( $pos = mb_strpos( $name, '.', 0, 'utf8' ) ) ) {
			$ext = mb_substr( $name, $pos, NULL, 'utf8' );
			$name = mb_substr( $name, 0, $pos, 'utf8' );
			
			if( ! in_array( mb_strtolower( $ext, 'utf8' ), array( '.php', '.php5', '.php4', '.php3', '.html', '.phtml', '.dhtml', '.xhtml', '.htm', '.asp', '.aspx' ) ) ) {
				$name .= $ext;
				$ext = '';
			}
		}
		
		do {
			$isUnique	= 0;
			
			if( $nKeyword > $id ) {
				$keywords	= array();
				$isUnique	= 0;				
				
				for( $i = $nKeyword; $i < $nKeyword + 100; $i++ ) {
					$keyword	= $this->_clearIfOn( $name ) . ( $i ? '-' . $i : '' ) . $ext;
					
					if( ! isset( $this->_url_alias[$keyword] ) )
						$keywords[]	= array( 'keyword' => $name . ( $i ? '-' . $i : '' ) );
				}
				
				$this->_getUrlAlias( $keywords, 'keyword', $type );
				
				for( $i = $nKeyword; $i < $nKeyword + 100; $i++ ) {
					$keyword	= $this->_clearIfOn( $name ) . ( $i ? '-' . $i : '' ) . $ext;
					$isUnique	= $this->_keywordIsUnique( $keyword, $type . '_id', $id );
					
					if( $isUnique )
						break;
				}
				
				$nKeyword+=100;
			} else {
				$keyword	= $this->_clearIfOn( $name ) . ( $nKeyword ? '-' . $nKeyword : '' ) . $ext;
			
				$isUnique	= $this->_keywordIsUnique( $keyword, $type . '_id', $id );
				
				$nKeyword	+= $nKeyword ? 1 : (int) $id;
			}
		} while( $isUnique === 0 );
		
		return array(
			'keyword'	=> $keyword,
			'isUnique'	=> $isUnique
		);
	}
	
	protected function _generateInfoProductsByLang( $language_id ) {
		return $this->__generateInfoByLang( 
			$language_id,
			'product'
		);
	}
	
	protected function _generateInfoCategoriesByLang( $language_id ) {
		return $this->__generateInfoByLang( 
			$language_id,
			'category'
		);
	}
	
	protected function _generateInfoInformationsByLang( $language_id ) {
		return $this->__generateInfoByLang( 
			$language_id,
			'information'
		);
	}
	
	protected function _generateInfoManufacturersByLang( $language_id ) {
		return $this->__generateInfoByLang( 
			$language_id,
			'manufacturer'
		);
	}
	
	public function __generateInfoByLang( $language_id, $table ) {
		$query = '
			SELECT
				COUNT(*) AS c
			FROM
				' . DB_PREFIX . $table . ' t
			LEFT JOIN
				' . DB_PREFIX . ( $table == 'manufacturer' ? $table . '_smp' : $table . '_description' ) . ' d
			ON
				t.' . $table . '_id = d.' . $table . '_id AND d.language_id = ' . $language_id . '
			WHERE
		';
		
		if( in_array( $table, $this->_supportUrlAliasExists ) ) {
			$query .= 'NOT FIND_IN_SET( ' . $language_id . ', d.url_alias_exists )';
		} else {
			$query .= 'NOT EXISTS(SELECT * FROM ' . DB_PREFIX . 'url_alias a WHERE a.query = CONCAT( "' . $table . '_id=", t.' . $table . '_id ) AND (a.smp_language_id IS NULL OR a.smp_language_id = ' . $language_id . ') )';
		}
		
		return $this->db->query( $query )->row['c'];
	}
	
	protected function _generateByLang( $lang, $preview, $label, $table, $label_num, $label_name, $itemIds, $field = NULL, $alias = NULL, $limit = NULL ) {
		if( $field === NULL )
			$field = $table;
		
		if( $alias === NULL )
			$alias = $field;
		
		if( strpos( $label_name, '.' ) === false ) {
			$label_name = 'd.' . $label_name;
		}
		
		if( strpos( $label_name, 'AS name' ) === false ) {
			$label_name .= ' AS name';
		}
				
		$params = $this->getParams();
		$params = isset( $params[$table] ) ? $params[$table] : '';
		
		if( is_array( $params ) ) {
			$params = isset( $params[$lang['language_id']] ) ? $params[$lang['language_id']] : '';
		}
		
		if( $table == 'product' ) {
			$label_name .= ', i.model, i.sku, i.upc';
		}
		
		$results	= array(
			array( '<b>' . $label . '</b>' )
		);
		
		$query		= '
			SELECT
				i.' . $field . '_id AS id,
				' . $label_name . '
			FROM
				' . DB_PREFIX . $table . ' i
			LEFT JOIN
				' . DB_PREFIX . ( $table == 'manufacturer' ? $table . '_smp' : $table . '_description' ) . ' d
			ON
				d.' . $field . '_id = i.' . $field . '_id AND d.language_id = ' . $lang['language_id'] . '
			WHERE
		';
		
		if( in_array( $table, $this->_supportUrlAliasExists ) ) {
			$query .= 'NOT FIND_IN_SET( ' . $lang['language_id'] . ', d.url_alias_exists )';
		} else {
			$query .= 'NOT EXISTS(SELECT * FROM ' . DB_PREFIX . 'url_alias a WHERE a.query = CONCAT( "' . $alias . '_id=", i.' . $field . '_id ) AND (a.smp_language_id IS NULL OR a.smp_language_id = ' . $lang['language_id'] . ') )';
		}
		
		if( $itemIds )
			$query .= ' AND i.' . $field . '_id IN(' . implode( ',', $itemIds ) . ')';
		
		if( $preview ) {
			$query .= ' ORDER BY RAND() LIMIT 20';
		} else if( $limit !== NULL ) {
			$query .= ' LIMIT ' . (int) $limit;
		}
		
		$query	= $this->db->query( $query );
		
		$this->_getUrlAlias( $query->rows, 'name', $alias );
		
		$updates = array();
		
		foreach( $query->rows as $item ) {
			$name = $item['name'];
			
			if( $params ) {
				$name = str_replace(array(
					'{name}',
					'{product_name}',
					'{model}',
					'{sku}',
					'{upc}'
				), array(
					$item['name'],
					$item['name'],
					isset( $item['model'] ) ? $item['model'] : '',
					isset( $item['sku'] ) ? $item['sku'] : '',
					isset( $item['upc'] ) ? $item['upc'] : ''
				), $params);
			}
			
			$keyword = $this->_createUniqueKeyword( $name, $alias, $item['id'] );
			
			if( ! $preview && $keyword['isUnique'] === 1 ) {
				$this->db->query("
					INSERT INTO
						" . DB_PREFIX . "url_alias
					SET
						query = '" . $alias . "_id=" . $item['id'] . "',
						keyword = '" . $this->db->escape( $keyword['keyword'] ) . "',
						smp_language_id = '" . $lang['language_id'] . "'
				");
				
				$updates[] = $item['id'];
			}
				
			$this->_url_alias[$keyword['keyword']] = array(
				'query'				=> $alias . '_id=' . $item['id'],
				'smp_language_id'	=> $lang['language_id'],
				'url_alias_id'		=> $preview ? 0 : $this->db->getLastId()
			);
			
			$results[] = array( $item['name'], $keyword['keyword'] );
		}
		
		if( $updates && in_array( $table, $this->_supportUrlAliasExists ) ) {
			$this->db->query("
				UPDATE
					" . DB_PREFIX . ( $table == 'manufacturer' ? $table . '_smp' : $table . '_description' ) . "
				SET
					url_alias_exists = IF( url_alias_exists = '' OR url_alias_exists = '0', '" . $lang['language_id'] . "', CONCAT( url_alias_exists, ',', '" . $lang['language_id'] . "' ) )
				WHERE
					" . $field . "_id IN( " . implode( ',', $updates ) . " )
			");
		}
		
		return $results;
	}
	
	// Standardowe /////////////////////////////////////////////////////////////
	
	/**
	 * @param int $lang
	 * @param bool $preview
	 * @return string
	 */
	public function _generateProductsByLang( $lang, $preview, $productIds = array(), $limit = NULL ) {
		return $this->_generateByLang($lang, $preview, 
			'Product',
			'product',
			'products',
			'd.name AS name',
			$productIds,
			NULL,
			NULL,
			$limit
		);
	}
	
	/**
	 * @param int $lang
	 * @param bool $preview
	 * @return string
	 */
	public function _generateCategoriesByLang( $lang, $preview, $categoryIds = array(), $limit = NULL ) {
		return $this->_generateByLang($lang, $preview, 
			'Category',
			'category',
			'categories',
			'd.name AS name',
			$categoryIds,
			NULL,
			NULL,
			$limit
		);
	}
	
	/**
	 * @param int $lang
	 * @param bool $preview
	 * @return string
	 */
	public function _generateInformationsByLang( $lang, $preview, $informationIds = array(), $limit = NULL ) {
		return $this->_generateByLang($lang, $preview, 
			'Information',
			'information',
			'informations',
			'd.title AS name',
			$informationIds,
			NULL,
			NULL,
			$limit
		);
	}
	
	/**
	 * @param int $lang
	 * @param bool $preview
	 * @return string
	 */
	public function _generateManufacturersByLang( $lang, $preview, $manufacuterIds = array(), $limit = NULL ) {
		return $this->_generateByLang($lang, $preview, 
			'Manufacturer',
			'manufacturer',
			'manufacturers',
			'IF( d.name IS NULL OR d.name = "", i.name, d.name ) AS name',
			$manufacuterIds,
			NULL,
			NULL,
			$limit
		);
	}
	
	/**
	 * @return void
	 */
	public function clear( $mode = NULL, $onlyAutoGenerated = false ) {
		$query = "
			DELETE FROM
				" . DB_PREFIX . "url_alias
		";
		
		switch( $mode ) {
			case 'product'	: {
				$query .= ' WHERE query LIKE "product_id=%"';
				
				break;
			}
			case 'category' : {
				$query .= ' WHERE query LIKE "category_id=%"';
				
				break;
			}
			case 'information' : {
				$query .= ' WHERE query LIKE "information_id=%"';
				
				break;
			}
			case 'manufacturer' : {
				$query .= ' WHERE query LIKE "manufacturer_id=%"';
				
				break;
			}
			default : {
				$mode = NULL;
			}
		}
		
		if( $mode == 'product' || $mode === NULL )
			$this->db->query( "UPDATE " . DB_PREFIX . "product_description SET url_alias_exists = ''" );
		
		if( $mode == 'category' || $mode === NULL )
			$this->db->query( "UPDATE " . DB_PREFIX . "category_description SET url_alias_exists = ''" );
		
		if( $mode == 'information' || $mode === NULL )
			$this->db->query( "UPDATE " . DB_PREFIX . "information_description SET url_alias_exists = ''" );
		
		if( $mode == 'manufacturer' || $mode === NULL )
			$this->db->query( "UPDATE " . DB_PREFIX . "manufacturer_smp SET url_alias_exists = ''" );
		
		$this->db->query( $query );
	}
	
	public function generateInfo( $mode = NULL ) {
		$results	= array();
		$modes		= array(
			'product'		=> 'Products',
			'category'		=> 'Categories',
			'information'	=> 'Informations',
			'manufacturer'	=> 'Manufacturers'
		);
		
		foreach( $this->_controller->_languages( true ) as $language_id => $lang ) {
			foreach( $modes as $name => $title ) {
				if( $mode !== NULL && $mode != $name ) continue;
				
				$results[]	= array(
					'language_id'	=> $language_id,
					'image'			=> $lang['image'],
					'name'			=> $title . ' - ' . $lang['name'],
					'mode'			=> $name,
					'code'			=> $lang['code'],
					'items'			=> $this->{'_generateInfo' . $title . 'ByLang'}( $language_id )
				);
			}
		}
		
		return $results;
	}
	
	/**
	 * @param bool $preview
	 * @return string
	 */
	public function generate( $preview = false, $limit = NULL, $language_id = NULL, $mode = NULL ) {
		$results	= array();
		
		foreach( $this->_controller->_languages( true ) as $lang ) {
			if( $language_id !== NULL && $language_id != $lang['language_id'] ) continue;
			
			if( $mode === NULL || $mode == 'product' )
				$results[$lang['language_id']][] = array( $this->_generateProductsByLang( $lang, $preview, array(), $limit ) );
			
			if( $mode === NULL || $mode == 'category' )
				$results[$lang['language_id']][] = array( $this->_generateCategoriesByLang(  $lang, $preview, array(), $limit ) );
			
			if( $mode === NULL || $mode == 'information' )
				$results[$lang['language_id']][] = array( $this->_generateInformationsByLang( $lang, $preview, array(), $limit ) );
		
			if( $mode === NULL || $mode == 'manufacturer' )
				$results[$lang['language_id']][] = array( $this->_generateManufacturersByLang( $lang, $preview, array(), $limit ) );
		}
		
		return $results;
	}
	
	private function createInput( $name, $default = '' ) {
		/* @var $params array */
		$params = $this->getParams();
		
		/* @var $value string */
		$value = $default;
		
		if( strpos( $name, '][' ) !== false ) {
			$n = explode( '][', $name );
			
			if( isset( $params[$n[0]] ) ) {
				if( is_array( $params[$n[0]] ) ) {
					if( isset( $params[$n[0]][$n[1]] ) ) {
						$value = $params[$n[0]][$n[1]];
					}
				} else {
					$value = $params[$n[0]];
				}
			}
		} else if( isset( $params[$name] ) ) {
			$value = $params[$name];
		}
		
		return '<input 
			type="text" 
			class="form-control" 
			value="' . $value . '" 
			name="extensions[' . $this->name() . '][' . $name . ']" 
			data-name="' . $this->name() . '-' . str_replace( '][', '-', $name ) . '" />';
	}
	
	/**
	 * Pobierz opis rozszerzenia wyświetlany w panelu administracyjnym użytkownikowi
	 * 
	 * @return string
	 */
	public function description() {
		$languages	= $this->_languages( true );
		
		/* @var $desc string */
		$desc = '';
		
		$desc .= 'Use this extension to generate friendly URLs for products, categories, manufacturiers and information pages.';
		
		$desc .= '<br /><br />Set URL structure for <strong>products</strong>:<br />';
		
		foreach( $languages as $language ) {
			$img = 'view/image/flags/' . $language['image'];
			
			if( version_compare( VERSION, '2.2.0.0', '>=' ) ) {
				$img = 'language/' . $language['code'] . '/' . $language['code'] . '.png';
			}
			
			$desc .= '<br />';
			$desc .= $this->printTags('', '{product_name},{model},{sku},{upc}', '-product-' . $language['language_id']);
			$desc .= '<div class="input-group">';
			$desc .= $this->createInput( 'product][' . $language['language_id'], '{product_name}' );
			$desc .= '<div class="input-group-addon">';
			$desc .= '<img src="' . $img . '" />';
			$desc .= '</div>';
			$desc .= '</div>';
		}
		
		$desc .= '<hr />';
		$desc .= 'Set URL structure for <strong>categories</strong>:<br />';
		
		foreach( $languages as $language ) {
			$img = 'view/image/flags/' . $language['image'];
			
			if( version_compare( VERSION, '2.2.0.0', '>=' ) ) {
				$img = 'language/' . $language['code'] . '/' . $language['code'] . '.png';
			}
			
			$desc .= '<br />';
			$desc .= $this->printTags('', '{name}', '-category-' . $language['language_id']);
			$desc .= '<div class="input-group">';
			$desc .= $this->createInput( 'category][' . $language['language_id'], '{name}' );
			$desc .= '<div class="input-group-addon">';
			$desc .= '<img src="' . $img . '" />';
			$desc .= '</div>';
			$desc .= '</div>';
		}
		
		$desc .= '<hr />';
		$desc .= 'Set URL structure for <strong>manufacturers</strong>:<br />';
		
		foreach( $languages as $language ) {
			$img = 'view/image/flags/' . $language['image'];
			
			if( version_compare( VERSION, '2.2.0.0', '>=' ) ) {
				$img = 'language/' . $language['code'] . '/' . $language['code'] . '.png';
			}
			
			$desc .= '<br />';
			$desc .= $this->printTags('', '{name}', '-manufacturer-' . $language['language_id']);
			$desc .= '<div class="input-group">';
			$desc .= $this->createInput( 'manufacturer][' . $language['language_id'], '{name}' );
			$desc .= '<div class="input-group-addon">';
			$desc .= '<img src="' . $img . '" />';
			$desc .= '</div>';
			$desc .= '</div>';
		}
		
		$desc .= '<hr />';
		$desc .= 'Set URL structure for <strong>information pages</strong>:<br />';
		
		foreach( $languages as $language ) {
			$img = 'view/image/flags/' . $language['image'];
			
			if( version_compare( VERSION, '2.2.0.0', '>=' ) ) {
				$img = 'language/' . $language['code'] . '/' . $language['code'] . '.png';
			}
			
			$desc .= '<br />';
			$desc .= $this->printTags('', '{name}', '-information-' . $language['language_id']);
			$desc .= '<div class="input-group">';
			$desc .= $this->createInput( 'information][' . $language['language_id'], '{name}' );
			$desc .= '<div class="input-group-addon">';
			$desc .= '<img src="' . $img . '" />';
			$desc .= '</div>';
			$desc .= '</div>';
		}
		
		
		return $desc;
	}
	
	/**
	 * Pobierz listę parametrów rozszerzenia
	 * 
	 * @return int
	 */
	public function getParams() {
		$params = parent::getParams();
		
		if( $params === NULL ) {
			$params = array(
				'product' => '{product_name}.html',
				'category' => '{name}',
				'manufacturer' => '{name}',
				'information' => '{name}.html'
			);
		} else if( ! is_array( $params ) ) {
			$params = array(
				'product' => '{product_name}' . $params,
				'category' => '{name}',
				'manufacturer' => '{name}',
				'information' => '{name}' . $params
			);
		}
		
		return $params;
	}
	
	/**
	 * Jeśli funkcja install() zwróci false system wywoła poniższą metodę aby podzielić instalację na etapy
	 * 
	 * @param int $index
	 * @return int|NULL 
	 */
	public function installprogress( $index ) {		
		$ids	= array();
		$count	= $this->db->query( 'SELECT COUNT(*) AS c FROM ' . DB_PREFIX . 'url_alias' )->row['c'];
		$limit	= 5000;
			
		// poniższy kod odpowiedzialny jest za sprawdzenie dla których wpisów istnieją aliansy URL aby odpowiedinio te rekordy zostały oznaczone w bazie danych i nie było duplikatów
		foreach( $this->db->query( 'SELECT * FROM ' . DB_PREFIX . 'url_alias LIMIT ' . ( $limit * $index ) . ', ' . $limit )->rows as $row ) {
			$query	= explode( '=', $row['query'] );
			
			switch( $query[0] ) {
				case 'product_id'		: $ids['product_description'][] = $query[1]; break;
				case 'category_id'		: $ids['category_description'][] = $query[1]; break;
				case 'information_id'	: $ids['information_description'][] = $query[1]; break;
				case 'manufacturer_id'	: $ids['manufacturer_smp'][] = $query[1]; break;
			}
		}
		
		// @since 1.3.9 wstaw rekordy językowe dla marek
		if( ! $index ) {
			$languages = $this->db->query( 'SELECT * FROM ' . DB_PREFIX . 'language' )->rows;
			foreach( $this->db->query( 'SELECT * FROM ' . DB_PREFIX . 'manufacturer' )->rows as $row ) {
				foreach( $languages as $language ) {
					if( ! $this->db->query( 'SELECT * FROM ' . DB_PREFIX . 'manufacturer_smp WHERE manufacturer_id = ' . (int) $row['manufacturer_id'] . ' AND language_id = ' . (int) $language['language_id'] )->num_rows ) {
						$this->db->query("
							INSERT INTO
								" . DB_PREFIX . "manufacturer_smp
							SET
								manufacturer_id = '" . $row['manufacturer_id'] . "',
								language_id = '" . $language['language_id'] . "',
								description = '',
								description_ag = '0',
								meta_keyword = NULL,
								meta_keyword_ag = '0',
								meta_description = NULL,
								meta_title = NULL,
								meta_title_ag = '0',
								smp_h1_title = NULL,
								smp_h1_title_ag = '0',
								tag = '',
								tag_ag = '0',
								name = '" . $this->db->escape( $row['name'] ) . "',
								url_alias_exists = '0'
						");
					} 
				}
			}
		}
			
		// zaktualizuj wszystkie rekordy dla których odnaleziono aliansy URL
		foreach( $ids as $table => $items ) {
			$type = explode( '_', $table );
				
			$this->db->query("
				UPDATE
					" . DB_PREFIX . $table . "
				SET 
					url_alias_exists = '" . $this->config->get('config_language_id') . "'
				WHERE
					" . $type[0] . "_id IN(" . implode( ',', $items ) . ")
			");
		}
			
		// oblicz postęp instalacji
		$progress = $count < 1 ? 100 : ( $limit * $index ) / $count * 100;
			
		if( $progress < 100 )
			return round( $progress, 2 );
	}


	/**
	 * Funkcja wywoływana automatycznie podczas aktualizacji/instalacji modułu
	 * Jeśli zwróci false system dodatkowo wywoła metodę installprogress z tego obiektu
	 * 
	 * @return void
	 */
	public function install() {
		parent::install();
		
		$this->_createTableManufacturerSMP();

		$this->updateColumn( 'url_alias_exists', "VARCHAR(255) NOT NULL DEFAULT ''", 'product_description' );
		$this->updateColumn( 'url_alias_exists', "VARCHAR(255) NOT NULL DEFAULT ''", 'category_description' );
		$this->updateColumn( 'url_alias_exists', "VARCHAR(255) NOT NULL DEFAULT ''", 'information_description' );
		$this->updateColumn( 'url_alias_exists', "VARCHAR(255) NOT NULL DEFAULT ''", 'manufacturer_smp' );
		
		/**
		 * @since 1.1.3 
		 */
		if( version_compare( $this->config->get('smp_sug_is_install'), '1.1.3', '<' ) ) {
			$this->removeColumn( 'url_alias_exists', 'manufacturer' );
		}
		
		$query	= $this->db->query('SHOW COLUMNS FROM ' . DB_PREFIX . 'url_alias LIKE "smp_language_id"');

		if( ! $query->num_rows ) {
			$this->db->query('ALTER TABLE ' . DB_PREFIX . 'url_alias ADD `smp_language_id` INT(11) NULL DEFAULT NULL');
			$this->db->query('UPDATE ' . DB_PREFIX . 'url_alias SET smp_language_id = ' . (int) $this->config->get('config_language_id') );
		}
		
		// @since 1.3.5
		$this->addColumn( 'smp_url_category_id', 'INT(11) NULL DEFAULT NULL', 'product' ); // kolumna przechowuje informacje o wybranej kanonicznej kategorii (domyślnie jest to ostatnia dodana do listy)
		
		return false;
	}
	
	/**
	 * Funkcja wywoływana automatycznie podczas deinstalacji modułu
	 * 
	 * @return void
	 */
	public function uninstall() {
		parent::uninstall();
		
		$this->removeColumn( 'url_alias_exists', 'product_description' );
		$this->removeColumn( 'url_alias_exists', 'category_description' );
		$this->removeColumn( 'url_alias_exists', 'information_description' );
		
		// @since 1.3.5
		$this->removeColumn( 'smp_url_category_id', 'product' );
		
		$query = $this->db->query('SHOW COLUMNS FROM ' . DB_PREFIX . 'url_alias LIKE "smp_language_id"');
		
		if( $query->num_rows ) {
			$this->db->query('DELETE FROM ' . DB_PREFIX . 'url_alias WHERE smp_language_id IS NOT NULL AND smp_language_id != ' . (int) $this->config->get('config_language_id') );
			
			$this->db->query('ALTER TABLE ' . DB_PREFIX . 'url_alias DROP `smp_language_id`');
		}
	}
}