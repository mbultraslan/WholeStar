<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_generator.php' );

class SeoMegaPack_AbstractCategoryGenerator extends SeoMegaPack_AbstractGenerator {
	
	/**
	 * @var array 
	 */
	protected $_tags			= array(
		'{name}'			=> 'name',
		'{parent_name}'		=> 'parent_name',
		'{sample_products}'	=> 'sample_products',
		'{description}'		=> 'description',
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
	protected $_group		= 'categories';
	
	/**
	 * @var string 
	 */
	protected $_icon		= 'list-alt';
	
	/**
	 * @var string 
	 */
	protected $_tableName	= 'category_description';
	
	public function _generateInfoByLang( $language_id ) {
		$query = '
			SELECT
				COUNT(*) AS co
			FROM
				' . DB_PREFIX . 'category c
			LEFT JOIN
				' . DB_PREFIX . $this->_tableName . ' cd
			ON
				cd.category_id = c.category_id AND cd.language_id = ' . $language_id . '
			WHERE
				( cd.' . $this->_fieldName . ' IS NULL OR cd.' . $this->_fieldName . ' = "" )
		';
		
		return $this->db->query( $query )->row['co'];
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
			$sql .= " OR cd." . $this->_fieldName . " = '" . $this->db->escape( '<p><br></p>' ) . "' ";
			$sql .= " OR cd." . $this->_fieldName . " = '" . $this->db->escape( '&lt;p&gt;&lt;br&gt;&lt;/p&gt;' ) . "' ";
		}
		
		$query		= '
			SELECT
				c.category_id,
				cd.name,
				cd.description,
				( 
					SELECT 
						name
					FROM 
						' . DB_PREFIX . $this->_tableName . '
					WHERE 
						category_id = c.parent_id AND language_id = ' . $language_id . '
					LIMIT 
						1 
				) AS parent_name
			FROM
				' . DB_PREFIX . 'category c
			LEFT JOIN
				' . DB_PREFIX . $this->_tableName . ' cd
			ON
				cd.category_id = c.category_id AND cd.language_id = ' . $language_id . '
			WHERE
				( cd.' . $this->_fieldName . ' IS NULL OR cd.' . $this->_fieldName . ' = "" ' . $sql . ' )
		';
		
		if( $categoryIds )
			$query .= ' AND c.category_id IN(' . implode(',', $categoryIds) . ')';
		
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
						$value = $this->sampleProducts( 'category', $item['category_id'], $language_id, $param['config'] );
						
						break;
					}
					case 'total_products' : {
						$value = $this->totalProducts( 'category', $item['category_id'] );
						
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
				$this->db->query("
					UPDATE
						" . DB_PREFIX . $this->_tableName . "
					SET
						`" . $this->_fieldName . "` = '" . $this->db->escape( $generate ) . "',
						`" . $this->_fieldName . "_ag` = '1'
					WHERE
						category_id = " . (int) $item['category_id'] . " AND
						language_id = " . (int) $language_id . "
				");
			}
			
			$results[] = array( $item['name'], htmlspecialchars_decode( $generate ) );
		}
		
		return $results;
	}
}