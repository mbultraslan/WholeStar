<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_generator.php' );

class SeoMegaPack_AbstractManufacturerGenerator extends SeoMegaPack_AbstractGenerator {
	
	/**
	 * @var array 
	 */
	protected $_tags			= array(
		'{name}'			=> 'name',
		'{sample_products}'	=> 'sample_products',
		'{total_products}'	=> 'total_products',
		'{site_name}'		=> 'site_name',
		'{site_title}'		=> 'site_title'
	);
	
	/**
	 * @var int
	 */
	protected $_sort		= 1;
	
	/**
	 * @var string 
	 */
	protected $_group		= 'manufacturers';
	
	/**
	 * @var string 
	 */
	protected $_icon		= 'list-alt';
	
	/**
	 * @var string 
	 */
	protected $_tableName	= 'manufacturer_smp';
	
	public function _generateInfoByLang( $language_id ) {
		$query = '
			SELECT
				COUNT(*) AS mo
			FROM
				' . DB_PREFIX . 'manufacturer m
			LEFT JOIN
				' . DB_PREFIX . $this->_tableName . ' md
			ON
				md.manufacturer_id = m.manufacturer_id AND md.language_id = ' . $language_id . '
			WHERE
				( md.' . $this->_fieldName . ' IS NULL OR md.' . $this->_fieldName . ' = "" )
		';
		
		return $this->db->query( $query )->row['mo'];
	}
	
	/**
	 * @param int $language_id
	 * @param bool $preview
	 * @return string
	 */
	public function _generateByLang( $language_id, $preview, $categoryIds = array(), $limit = NULL ) {
		$results	= array();
		$sql		= '';
		
		if( $this->_fieldName == 'description' ) {
			$sql .= " OR md." . $this->_fieldName . " = '" . $this->db->escape( '<p><br></p>' ) . "' ";
			$sql .= " OR md." . $this->_fieldName . " = '" . $this->db->escape( '&lt;p&gt;&lt;br&gt;&lt;/p&gt;' ) . "' ";
		}
		
		$query		= '
			SELECT
				m.manufacturer_id,
				IF( md.name IS NOT NULL AND md.name != "", md.name, m.name ) AS name,
				md.description
			FROM
				' . DB_PREFIX . 'manufacturer m
			LEFT JOIN
				' . DB_PREFIX . $this->_tableName . ' md
			ON
				md.manufacturer_id = m.manufacturer_id AND md.language_id = ' . $language_id . '
			WHERE
				( md.' . $this->_fieldName . ' IS NULL OR md.' . $this->_fieldName . ' = "" ' . $sql . ' )
		';
		
		if( $categoryIds )
			$query .= ' AND m.manufacturer_id IN(' . implode(',', $categoryIds) . ')';
		
		if( $preview ) {
			$query .= ' ORDER BY RAND() LIMIT 20';
		} else if( $limit !== NULL ) {
			$query .= ' LIMIT ' . (int) $limit;
		}
		
		$params	= $this->convertParamsToConfig( $language_id );
		
		foreach( $this->db->query( $query )->rows as $item ) {
			$generate = $this->getParams( $language_id );
			
			if( is_array( $generate ) )
				$generate = isset( $generate[$language_id] ) ? $generate[$language_id] : '';
			
			foreach( $params as $search => $param ) {
				if( ! isset( $this->_tags['{'.$param['tag'].'}'] ) ) continue;
				
				$field	= $this->_tags['{'.$param['tag'].'}'];
				$value	= isset( $item[$field] ) ? $item[$field] : $param['value'];
				
				switch( $param['tag'] ) {
					case 'sample_products' : {
						$value = $this->sampleProducts( 'manufacturer', $item['manufacturer_id'], $language_id, $param['config'] );
						
						break;
					}
					case 'total_products' : {
						$value = $this->totalProducts( 'manufacturer', $item['manufacturer_id'] );
						
						break;
					}
					case 'description' : {
						$value = $this->getSentences( $item['description'], $param['config']['sentences'] );
						
						break;
					}
				}
				
				$generate = str_replace( $search, $value, $generate );
			}
			
			$generate = $this->parseGenerated( $generate );
			
			if( ! $preview ) {
				if( ! $this->db->query( "SELECT * FROM " . DB_PREFIX . $this->_tableName . " WHERE manufacturer_id = " . $item['manufacturer_id'] . " AND language_id = " . $language_id )->num_rows ) {
					$this->db->query("
						INSERT INTO
							" . DB_PREFIX . $this->_tableName . "
						SET
							manufacturer_id = " . (int) $item['manufacturer_id'] . ",
							language_id = " . (int) $language_id . "
					");
				}
				
				$this->db->query("
					UPDATE
						" . DB_PREFIX . $this->_tableName . "
					SET
						`" . $this->_fieldName . "` = '" . $this->db->escape( $generate ) . "',
						`" . $this->_fieldName . "_ag` = '1'
					WHERE
						manufacturer_id = " . (int) $item['manufacturer_id'] . " AND
						language_id = " . (int) $language_id . "
				");
			}
			
			$results[] = array( $item['name'], htmlspecialchars_decode( $generate ) );
		}
		
		return $results;
	}
	
	/**
	 * @return void
	 */
	public function install() {		
		// utwórz tablę do przechowywania SEO tytułu i SEO nagłówka dla producentów
		$this->_createTableManufacturerSMP();
		
		parent::install();
	}
	
	/**
	 * @return void 
	 */
	public function uninstall() {
		// usuń tablę do przechowywania informacji SEO dla producentów
		$this->db->query('DROP TABLE IF EXISTS `' . DB_PREFIX . 'manufacturer_smp`');
		
		parent::uninstall();
	}
}