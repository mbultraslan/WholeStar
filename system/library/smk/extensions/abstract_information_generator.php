<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_generator.php' );

class SeoMegaPack_AbstractInformationGenerator extends SeoMegaPack_AbstractGenerator {
	
	/**
	 * @var array 
	 */
	protected $_tags			= array(
		'{title}'			=> 'title',
		'{description}'		=> 'description',
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
	protected $_group		= 'informations';
	
	/**
	 * @var string 
	 */
	protected $_icon		= 'list-alt';
	
	/**
	 * @var string 
	 */
	protected $_tableName	= 'information_description';
	
	public function _generateInfoByLang( $language_id ) {
		$query = '
			SELECT
				COUNT(*) AS io
			FROM
				' . DB_PREFIX . 'information i
			LEFT JOIN
				' . DB_PREFIX . 'information_description id
			ON
				id.information_id = i.information_id AND id.language_id = ' . $language_id . '
			WHERE
				( id.' . $this->_fieldName . ' IS NULL OR id.' . $this->_fieldName . ' = "" )
		';
		
		return $this->db->query( $query )->row['io'];
	}
	
	/**
	 * @param int $language_id
	 * @param bool $preview
	 * @return string
	 */
	public function _generateByLang( $language_id, $preview, $informationIds = array(), $limit = NULL ) {
		$results	= array();
		$query		= '
			SELECT
				i.information_id,
				id.title,
				id.description
			FROM
				' . DB_PREFIX . 'information i
			LEFT JOIN
				' . DB_PREFIX . $this->_tableName . ' id
			ON
				id.information_id = i.information_id AND id.language_id = ' . $language_id . '
			WHERE
				( id.' . $this->_fieldName . ' IS NULL OR id.' . $this->_fieldName . ' = "" )
		';
		
		if( $informationIds )
			$query .= ' AND i.information_id IN(' . implode(',', $informationIds) . ')';
		
		if( $preview ) {
			$query .= ' ORDER BY RAND() LIMIT 20';
		} else if( $limit !== NULL ) {
			$query .= ' LIMIT ' . (int) $limit;
		}
		
		$params	= $this->convertParamsToConfig( $language_id );
		
		foreach( $this->db->query( $query )->rows as $item ) {
			$generate = $this->getParams( $language_id );
			
			foreach( $params as $search => $param ) {
				if( ! isset( $this->_tags['{'.$param['tag'].'}'] ) ) continue;
				
				$field	= $this->_tags['{'.$param['tag'].'}'];
				$value	= isset( $item[$field] ) ? $item[$field] : $param['value'];
				
				switch( $param['tag'] ) {
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
						information_id = " . (int) $item['information_id'] . " AND
						language_id = " . (int) $language_id . "
				");
			}
			
			$results[] = array( $item['title'], $generate );
		}
		
		return $results;
	}
}