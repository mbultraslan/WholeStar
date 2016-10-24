<?php

/**
 * SEO Mega Pack
 * 
 * @author marsilea15 <marsilea15@gmail.com>
 */

require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/abstract_generator.php' );

class SeoMegaPack_AutoGenerator extends SeoMegaPack_AbstractGenerator {
	
	/**
	 * @var string
	 */
	protected $_name		= 'auto_generator';
	protected $_shortName	= 'at';
	
	/**
	 * @var string
	 */
	protected $_title		= 'Auto Generator';
	
	/**
	 * @var bool
	 */
	protected $_clearUrl	= false;
	
	/**
	 * @var bool
	 */
	protected $_previewUrl	= false;
	
	/**
	 * @var bool
	 */
	protected $_generateUrl	= false;
	
	/**
	 * @var int
	 */
	protected $_sort		= 4;
	
	/**
	 * @var string 
	 */
	protected $_group		= 'auto';
	
	/**
	 * @var string 
	 */
	protected $_icon		= 'asterisk';
	
	/**
	 * @var string 
	 */
	protected $_version		= '2';
	
	/**
	 * @var array
	 */
	protected $_info		= array(
		'seo_url_product'				=> 'Automatically create SEO URLs when adding or editing product',
		'seo_url_category'				=> 'Automatically create SEO URLs when adding or editing category',
		'seo_url_manufacturer'			=> 'Automatically create SEO URLs when adding or editing manufacturer',
		'seo_url_information'			=> 'Automatically create SEO URLs when adding or editing information',
		
		'seo_titles'					=> 'Automatically create SEO Titles when adding or editing product',
		'seo_titles_category'			=> 'Automatically create SEO Titles when adding or editing category',
		'seo_titles_manufacturer'		=> 'Automatically create SEO Titles when adding or editing manufacturer',
		'seo_titles_information'		=> 'Automatically create SEO Titles when adding or editing information',
		
		'seo_h1_titles'					=> 'Automatically create SEO Header Titles when adding or editing product',
		'seo_h1_titles_category'		=> 'Automatically create SEO Header Titles when adding or editing category',
		'seo_h1_titles_manufacturer'	=> 'Automatically create SEO Header Titles when adding or editing manufacturer',
		'seo_h1_titles_information'		=> 'Automatically create SEO Header Titles when adding or editing information',
		
		'meta_keywords'					=> 'Automatically create Meta Keywords when adding or editing product',
		'meta_keywords_category'		=> 'Automatically create Meta Keywords when adding or editing category',
		'meta_keywords_manufacturer'	=> 'Automatically create Meta Keywords when adding or editing manufacturer',
		'meta_keywords_information'		=> 'Automatically create Meta Keywords when adding or editing information',
		
		'meta_description'				=> 'Automatically create Meta Description when adding or editing product',
		'meta_description_category'		=> 'Automatically create Meta Description when adding or editing category',
		'meta_description_manufacturer'	=> 'Automatically create Meta Description when adding or editing manufacturer',
		'meta_description_information'	=> 'Automatically create Meta Description when adding or editing information',
		
		'tags'							=> 'Automatically create Tags when adding or editing product',
		'tags_category'					=> 'Automatically create Tags when adding or editing category',
		'tags_manufacturer'				=> 'Automatically create Tags when adding or editing manufacturer',
		'tags_information'				=> 'Automatically create Tags when adding or editing information',
		
		'description'					=> 'Automatically create Description when adding or editing product',
		'description_category'			=> 'Automatically create Description when adding or editing category',
		'description_manufacturer'		=> 'Automatically create Description when adding or editing manufacturer',
		
		'seo_alt_images'				=> 'Automatically create SEO ALT Images when adding or editing product',
		'seo_title_images'				=> 'Automatically create SEO Title Images when adding or editing product',
		
		'related_products'				=> 'Automatically create Related Products when adding or editing product',
	);
	
	/**
	 * @param type $controller
	 */
	static public function newInstance( $controller ) {
		return new self( $controller );
	}
	
	/**
	 * __construct()
	 */
	public function __construct($controller) {
		parent::__construct($controller);
	}
	
	// URL //////////////////////////////////////////////////////////////
	
	/**
	 * 
	 * @param type $product_id
	 * @return \SeoMegaPack_AutoGenerator
	 */
	public function createSeoUrlProduct( $product_id ) {
		if( ! $this->isEnabled( 'seo_url_product' ) ) return $this;
		
		if( ! file_exists( DIR_SYSTEM . 'library/smk/extensions/seo_urls_generator.php' ) ) return $this;
		
		require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/seo_urls_generator.php' );
		
		$seo = new SeoMegaPack_SeoUrlsGenerator( $this->_controller );
		
		foreach( $this->db->query( 'SELECT * FROM ' . DB_PREFIX . 'language WHERE status=1 ORDER BY sort_order ASC' )->rows as $lang ) {
			if( ! empty( $this->_controller->request->post['keyword'][$lang['language_id']] ) ) continue;
			
			$seo->_generateProductsByLang( $lang, false, array( $product_id ) );
		}
		
		return $this;
	}
	
	public function createSeoUrlCategory( $category_id ) {
		if( ! $this->isEnabled( 'seo_url_category' ) ) return $this;
		
		if( ! file_exists( DIR_SYSTEM . 'library/smk/extensions/seo_urls_generator.php' ) ) return $this;
		
		require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/seo_urls_generator.php' );
		
		$seo = new SeoMegaPack_SeoUrlsGenerator( $this->_controller );
		
		foreach( $this->db->query( 'SELECT * FROM ' . DB_PREFIX . 'language WHERE status=1 ORDER BY sort_order ASC' )->rows as $lang ) {
			if( ! empty( $this->_controller->request->post['keyword'][$lang['language_id']] ) ) continue;
			
			$seo->_generateCategoriesByLang( $lang, false, array( $category_id ) );
		}
		
		return $this;
	}
	
	public function createSeoUrlManufacturer( $manufacturer_id ) {
		if( ! $this->isEnabled( 'seo_url_manufacturer' ) ) return $this;
		
		if( ! file_exists( DIR_SYSTEM . 'library/smk/extensions/seo_urls_generator.php' ) ) return $this;
		
		require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/seo_urls_generator.php' );
		
		$seo = new SeoMegaPack_SeoUrlsGenerator( $this->_controller );
		
		foreach( $this->db->query( 'SELECT * FROM ' . DB_PREFIX . 'language WHERE status=1 ORDER BY sort_order ASC' )->rows as $lang ) {
			if( ! empty( $this->_controller->request->post['keyword'][$lang['language_id']] ) ) continue;
			
			$seo->_generateManufacturersByLang( $lang, false, array( $manufacturer_id ) );
		}
		
		return $this;
	}
	
	public function createSeoUrlInformation( $information_id ) {
		if( ! $this->isEnabled( 'seo_url_information' ) ) return $this;
		
		if( ! file_exists( DIR_SYSTEM . 'library/smk/extensions/seo_urls_generator.php' ) ) return $this;
		
		require_once VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/seo_urls_generator.php' );
		
		$seo = new SeoMegaPack_SeoUrlsGenerator( $this->_controller );
		
		foreach( $this->db->query( 'SELECT * FROM ' . DB_PREFIX . 'language WHERE status=1 ORDER BY sort_order ASC' )->rows as $lang ) {
			if( ! empty( $this->_controller->request->post['keyword'][$lang['language_id']] ) ) continue;
			
			$seo->_generateInformationsByLang( $lang, false, array( $information_id ) );
		}
		
		return $this;
	}
	
	// Wspólne /////////////////////////////////////////////////////////////////
	
	protected function __create( $type, $id ) {
		if( ! $this->isEnabled( $type ) ) return $this;
		
		$target = explode( '_', $type );
		$target = array_pop( $target );
		$target = in_array( $target, array( 'category', 'manufacturer', 'information' ) ) ? $target : 'product';
		
		$type	= in_array( $type, array( 'related_products' ) ) ? $type : str_replace( '_' . $target, '', $type );
		$info	= array(
			'seo_titles'		=> array(
				'f'	=> 'seo_titles',
				'c'	=> 'SeoTitles',
				'n' => 'smp_title'
			),
			'seo_h1_titles'		=> array(
				'f' => 'seo_titles_h1',
				'c' => 'SeoTitlesH1',
				'n' => 'smp_h1_title'
			),
			'meta_keywords'		=> array(
				'f' => 'keywords',
				'c' => 'Keywords',
				'n'	=> 'meta_keyword'
			),
			'meta_description'	=> array(
				'f' => $target == 'product' ? 'meta_description' : 'meta_desc',
				'c' => $target == 'product' ? 'MetaDescription' : 'MetaDesc',
				'n' => 'meta_description'
			),
			'tags'				=> array(
				'f' => 'tags',
				'c' => 'Tags',
				'n' => 'tag'
			),
			'description'		=> array(
				'f' => 'description',
				'c' => 'Description',
				'n' => 'description'
			),
			'seo_alt_images'	=> array(
				'f' => 'seo_alt_images',
				'c' => 'SeoAltImages',
				'n' => 'seo_alt_images'
			),
			'seo_title_images'	=> array(
				'f'	=> 'seo_title_images',
				'c' => 'SeoTitleImages',
				'n' => 'seo_title_images'
			),
			'related_products'	=> array(
				'f'		=> 'related_products',
				'c'		=> 'RelatedProducts',
				'nn'	=> 'product_related'
			)
		);
		
		if( ! isset( $info[$type] ) ) 
			return $this;
		
		$file = VQMod::modCheck( DIR_SYSTEM . 'library/smk/extensions/' . ( $target == 'product' ? '' : $target . '_' ) . $info[$type]['f'] . '_generator.php' );
		
		if( ! file_exists( $file ) )
			return $this;
		
		require_once $file;
		
		$class	= 'SeoMegaPack_' . ( $target == 'product' ? '' : ucfirst( $target ) ) . $info[$type]['c'] . 'Generator';
		$seo	= new $class( $this->_controller );
		
		foreach( $this->db->query( 'SELECT * FROM ' . DB_PREFIX . 'language WHERE status=1 ORDER BY sort_order ASC' )->rows as $lang ) {
			if( ! empty( $info[$type]['n'] ) ) {
				if( $info[$type]['n'] == 'description' ) {
					if( ! empty( $this->_controller->request->post[$target . '_description'][$lang['language_id']][$info[$type]['n']] ) ) {
						if( ! in_array( $this->_controller->request->post[$target . '_description'][$lang['language_id']][$info[$type]['n']], array( '<p><br></p>', '&lt;p&gt;&lt;br&gt;&lt;/p&gt;' ) ) ) {
							continue;
						}
					}
				} else if( ! empty( $this->_controller->request->post[$target . '_description'][$lang['language_id']][$info[$type]['n']] ) ) {
					continue;
				}
			}
			
			if( ! empty( $info[$type]['nn'] ) && ! empty( $this->_controller->request->post[$info[$type]['nn']] ) ) continue;
			
			$seo->_generateByLang( $lang['language_id'], false, array( $id ) );
		}
		
		return $this;
	}
	
	// Produkt /////////////////////////////////////////////////////////////////
	
	public function createMetaKeywords( $product_id ) {
		return $this->__create( 'meta_keywords', $product_id );
	}
	
	public function createSeoTitles( $product_id ) {
		return $this->__create( 'seo_titles', $product_id );
	}
	
	public function createSeoHeaderTitles( $product_id ) {
		return $this->__create( 'seo_h1_titles', $product_id );
	}
	
	public function createMetaDescription( $product_id ) {
		return $this->__create( 'meta_description', $product_id );
	}
	
	public function createTags( $product_id ) {
		return $this->__create( 'tags', $product_id );
	}
	
	public function createDescription( $product_id ) {
		return $this->__create( 'description', $product_id );
	}
	
	public function createSeoAltImages( $product_id ) {
		return $this->__create( 'seo_alt_images', $product_id );
	}
	
	public function createSeoTitleImages( $product_id ) {
		return $this->__create( 'seo_title_images', $product_id );
	}
	
	public function createRelatedProducts( $product_id ) {
		return $this->__create( 'related_products', $product_id );
	}
	
	// Kategoria ///////////////////////////////////////////////////////////////
	
	public function createMetaKeywordsCategory( $category_id ) {
		return $this->__create( 'meta_keywords_category', $category_id );
	}
	
	public function createSeoTitlesCategory( $category_id ) {
		return $this->__create( 'seo_titles_category', $category_id );
	}
	
	public function createSeoHeaderTitlesCategory( $category_id ) {
		return $this->__create( 'seo_h1_titles_category', $category_id );
	}
	
	public function createMetaDescriptionCategory( $category_id ) {
		return $this->__create( 'meta_description_category', $category_id );
	}
	
	public function createTagsCategory( $category_id ) {
		return $this->__create( 'tags_category', $category_id );
	}
	
	public function createDescriptionCategory( $category_id ) {
		return $this->__create( 'description_category', $category_id );
	}
	
	// Strona tekstowa /////////////////////////////////////////////////////////
	
	public function createMetaKeywordsInformation( $information_id ) {
		return $this->__create( 'meta_keywords_information', $information_id );
	}
	
	public function createSeoTitlesInformation( $information_id ) {
		return $this->__create( 'seo_titles_information', $information_id );
	}
	
	public function createSeoHeaderTitlesInformation( $information_id ) {
		return $this->__create( 'seo_h1_titles_information', $information_id );
	}
	
	public function createMetaDescriptionInformation( $information_id ) {
		return $this->__create( 'meta_description_information', $information_id );
	}
	
	public function createTagsInformation( $information_id ) {
		return $this->__create( 'tags_information', $information_id );
	}
	
	// Producenci //////////////////////////////////////////////////////////////
	
	public function createMetaKeywordsManufacturer( $manufacturer_id ) {
		return $this->__create( 'meta_keywords_manufacturer', $manufacturer_id );
	}
	
	public function createSeoTitlesManufacturer( $manufacturer_id ) {
		return $this->__create( 'seo_titles_manufacturer', $manufacturer_id );
	}
	
	public function createSeoHeaderTitlesManufacturer( $manufacturer_id ) {
		return $this->__create( 'seo_h1_titles_manufacturer', $manufacturer_id );
	}
	
	public function createMetaDescriptionManufacturer( $manufacturer_id ) {
		return $this->__create( 'meta_description_manufacturer', $manufacturer_id );
	}
	
	public function createTagsManufacturer( $manufacturer_id ) {
		return $this->__create( 'tags_manufacturer', $manufacturer_id );
	}
	
	public function createDescriptionManufacturer( $manufacturer_id ) {
		return $this->__create( 'description_manufacturer', $manufacturer_id );
	}
	
	////////////////////////////////////////////////////////////////////////////
	
	public function isEnabled( $name ) {
		$params = $this->getParams();
		
		return ! empty( $params[$name] );
	}
	
	/**
	 * @return string
	 */
	public function description() {
		$desc = '';
		
		foreach( $this->getParams() as $name => $value ) {
			if( ! isset( $this->_info[$name] ) ) continue;
			
			$id = 'extensions_' . $this->name() . '_' . $name;
			
			$desc .= $desc ? '<br />' : '';
			$desc .= '<label for="' . $id . '">';
			$desc .= '<input id="' . $id . '" type="checkbox" name="extensions[' . $this->name() . '][' . $name . ']" value="1"' . ( $value ? ' checked="checked"' : '' ) . ' /> ';
			$desc .= $this->_info[$name];
			$desc .= '</label>';
		}
		
		$desc .= '<input type="hidden" name="extensions[' . $this->name() . '][]" value="" />';
		
		return $desc;
	}
	
	/**
	 * @param mixed $parameters
	 * @return mixed
	 */
	public function parseParameters( $parameters ) {
		$keys	= array_keys( $this->_info );
		$params	= array();
		
		foreach( $keys as $key )
			$params[$key] = empty( $parameters[$key] ) ? '0' : '1';
		
		return $params;
	}
	
	/**
	 * @return array
	 */
	public function getParams() {
		$params = parent::getParams();
		
		if( $params === NULL )
			$params = array_combine(array_keys($this->_info), array_fill(0, count($this->_info), '1'));
		else if( count( $params ) != count( $this->_info ) )
			foreach( $this->_info as $name => $info )
				if( ! isset( $params[$name] ) )
					$params[$name] = '1';
		
		return $params;
	}
}