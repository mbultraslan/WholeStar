<?php
class ModelToolImage extends Model {

				private $_smp_cache = array();
				
				private $_smp_dynamic_cache = array();
				
				private function _smp_gn( $name ) {
					return substr( md5( $name ), 0, 2 );
				}
				
				public function setSeoName( $product_id ) {
					if( ! isset( $this->_smp_dynamic_cache[$product_id] ) ) {
						foreach( $this->getSeoImagesInfo( NULL, NULL, $product_id ) as $item ) {
							$this->_smp_dynamic_cache[$product_id] = $item['slug'];
							break;
						}
					}
				
					return $this;
				}
				
				private function getSeoImagesInfo( $new_image, $extension, $product_id ) {
					if( ( $new_image === NULL || $extension === NULL ) && $product_id === NULL ) {
						return array();
					}
				
					if( NULL == ( $smp_si_params = $this->config->get( 'smp_si_params' ) ) ) {
						return array();
					}
				
					$conditions = array();
				
					if( $product_id !== NULL ) {
						$conditions[] = "p.product_id = " . (int) $product_id;
					}
				
					if( $new_image !== NULL && $extension !== NULL ) {
						$conditions[] = "(
							p.image='" . $this->db->escape( $new_image . '_' . $this->config->get('config_store_id') . '.' . $extension ) . "' OR 
							pi.image='" . $this->db->escape( $new_image . '_' . $this->config->get('config_store_id') . '.' . $extension ) . "' OR
							p.image='" . $this->db->escape( $new_image . '.' . $extension ) . "' OR 
							pi.image='" . $this->db->escape( $new_image . '.' . $extension ) . "'
						)";
					}
				
					$images = $this->db->query("
						SELECT
							p.image AS p_image,
							pi.image AS pi_image,
							p.model,
							p.sku,
							p.upc,
							pd.name AS pd_name,
							(
								SELECT
									name
								FROM
									" . DB_PREFIX . "category_description
								WHERE
									language_id = " . (int) $this->config->get('config_language_id') . " AND
									category_id = (
										SELECT
											category_id
										FROM
											" . DB_PREFIX . "product_to_category
										WHERE
											product_id = p.product_id
										LIMIT
											1
									)
								LIMIT
									1
							) AS c_name,
							(
								SELECT
									name
								FROM
									" . DB_PREFIX . "manufacturer
								WHERE
									manufacturer_id = p.manufacturer_id
							) AS m_name
						FROM
							" . DB_PREFIX . "product p
						LEFT JOIN
							" . DB_PREFIX . "product_image pi
						ON
							p.product_id = pi.product_id
						INNER JOIN
							" . DB_PREFIX . "product_description pd
						ON
							p.product_id = pd.product_id AND pd.language_id = " . (int) $this->config->get('config_language_id') . "
						WHERE
							" . implode( ' AND ', $conditions ) . "
						GROUP BY
							p.product_id
					")->rows;
				
					require_once VQMod::modCheck(realpath( DIR_SYSTEM . '../catalog/controller/common/seo_mega_pack_pro_url.php' ));

					foreach( $images as $key => $item ) {
						$images[$key]['slug'] = ControllerCommonSeoMegaPackProUrl::_clear( str_replace( array(
							'{product_name}',
							'{category}',
							'{model}',
							'{brand}',
							'{sku}',
							'{upc}'
						), array(
							$item['pd_name'],
							$item['c_name'],
							$item['model'],
							$item['m_name'],
							$item['sku'],
							$item['upc']
						), $smp_si_params ), $this->config->get( 'smp_clear_on' ) );
					}
				
					return $images;
				}

				private function _seoUrlImage( $product_id, $seoUrlImage, $filename, $extension, $width, $height, $isHTTPS = false ) {
					if( $this->config->get( 'smp_si_is_install' ) && NULL != $this->config->get( 'smp_si_params' ) ) {
						$gn = $this->_smp_gn( $product_id . '-' . $filename );
				
						if( ! isset( $this->_smp_cache[$gn] ) ) {
							if( NULL == ( $this->_smp_cache[$gn] = $this->cache->get('smp_seo_image.' . $this->config->get('config_language_id') . '_' . $gn) ) ) {
								$this->_smp_cache[$gn] = array();
								
								foreach( $this->db->query("
									SELECT 
										* 
									FROM 
										" . DB_PREFIX . "smp_image 
									WHERE 
										gn='" . $this->db->escape( $gn ) . "' AND 
										language_id=" . (int) $this->config->get('config_language_id')
									)->rows as $item ) 
								{
									$this->_smp_cache[$gn][$item['file'].'.'.$item['ext']] = $item['slug'] . '_' . $item['smp_image_id'];
								}

								$this->cache->set('smp_seo_image.' . $this->config->get('config_language_id') . '_' . $gn, $this->_smp_cache[$gn]);
							}
						}

						$new_image		= mb_substr($filename, 0, mb_strrpos($filename, '.', 0, 'utf-8'), 'utf-8');
				
						if( ! isset( $this->_smp_cache[$gn][$new_image.'.'.$extension] ) ) {
							foreach( $this->getSeoImagesInfo( $new_image, $extension, $product_id ) as $item ) {
								$files = array();
				
								if( in_array( $item['p_image'], array( $new_image . '.' . $extension, $new_image . '_' . $this->config->get('config_store_id') . '.' . $extension ) ) ) {
									$files[] = $item['p_image'];
								} else {
									$files[] = $item['pi_image'];
								}
								
								foreach( $files as $file ) {				
									$basename = basename( $file );
									$info = array( 
										'dirname'	=> dirname( $file ), 
										'basename'	=> $basename, 
										'filename'	=> mb_substr( $basename, 0, mb_strrpos( $basename, '.', 'utf-8' ), 'utf-8' ),
										'extension'	=> mb_substr( $basename, mb_strrpos( $basename, '.', 'utf-8' )+1, mb_strlen( $basename, 'utf-8' ), 'utf-8' )
									);

									$this->db->query("
										INSERT INTO 
											" . DB_PREFIX . "smp_image 
										SET 
											file='" . $this->db->escape( $info['dirname'] . '/' . $info['filename'] ) . "', 
											ext='" . $this->db->escape( $info['extension'] ) . "',
											slug='" . $this->db->escape( $item['slug'] ) . "',
											language_id='" . (int) $this->config->get('config_language_id') . "',
											gn='" . $this->db->escape( $gn ) . "'
									");
				
									$id = $this->db->getLastId();
				
									$this->db->query("
										UPDATE
											" . DB_PREFIX . "smp_image
										SET
											gi='" . $this->db->escape( $this->_smp_gn( $id ) ) . "'
										WHERE
											smp_image_id='" . $id . "'
									");

									$this->_smp_cache[$gn][$file] = $item['slug'] . '_' . $id;
								}
							}

							if( ! isset( $this->_smp_cache[$gn][$new_image.'.'.$extension] ) ) {
								$this->_smp_cache[$gn][$new_image.'.'.$extension] = false;
							}

							$this->cache->set('smp_seo_image.' . $this->config->get('config_language_id') . '_' . $gn, $this->_smp_cache[$gn]);
						}

						if( ! empty( $this->_smp_cache[$gn][$new_image.'.'.$extension] ) ) {
							$seoName = $this->_smp_cache[$gn][$new_image.'.'.$extension];
				
							if( isset( $this->_smp_dynamic_cache[$product_id] ) ) {
								$seoName = explode( '_', $seoName );
								$seoName = $this->_smp_dynamic_cache[$product_id] . '_' . $seoName[count($seoName)-1];
							}
				
							$seoUrlImage	= 'image-smp/' . $seoName . '_' . $width . 'x' . $height . '.' . $extension;
						}
					}

					return $seoUrlImage;
				}
			
	public function resize($filename, $width, $height) {

				$product_id = NULL;
				
				if( strpos( $filename, '::' ) !== false ) {
					$filename = explode( '::', $filename );
					$product_id = $filename[1];
					$filename = $filename[0];
				}
			
		if (!is_file(DIR_IMAGE . $filename)) {
			return;
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$old_image = $filename;
		$new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

		if (!is_file(DIR_IMAGE . $new_image) || (filectime(DIR_IMAGE . $old_image) > filectime(DIR_IMAGE . $new_image))) {
			$path = '';

			$directories = explode('/', dirname(str_replace('../', '', $new_image)));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

			list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_IMAGE . $old_image);
				$image->resize($width, $height);
				$image->save(DIR_IMAGE . $new_image);
			} else {
				copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
			}
		}


				if( __FUNCTION__ == 'resize' ) {
					$seoUrlImage = $this->_seoUrlImage( $product_id, 'image/' . $new_image, $filename, $extension, $width, $height );			
			
					if ($this->request->server['HTTPS']) {
						return ( $this->config->get('config_ssl') ? $this->config->get('config_ssl') : HTTPS_SERVER . 'image/' )  . $seoUrlImage;
					} else {
						return ( $this->config->get('config_url') ? $this->config->get('config_url') : HTTP_SERVER . 'image/' ) . $seoUrlImage;
					}
				}
			
			
		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl')  . ( isset( $seoUrlImage ) ? $seoUrlImage : 'image/' . $new_image );
		} else {
			return $this->config->get('config_url')  . ( isset( $seoUrlImage ) ? $seoUrlImage : 'image/' . $new_image );
		}
	}
}