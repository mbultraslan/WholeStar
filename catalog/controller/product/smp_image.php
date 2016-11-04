<?php  
class ControllerProductSmpImage extends Controller {
	
	private $_tryed = false;
	
	/**
	 * Grupowanie rekordów
	 * 
	 * @param string $name
	 * @return string
	 */
	private function gn( $name ) {
		return substr( md5( $name ), 0, 2 );
	}
	
	public function index() {
		$name = NULL;
		
		if( isset( $this->request->get['name'] ) )
			$name = $this->request->get['name'];
		
		$name		= explode( '_', $name );
		$ctype		= NULL;
		$file		= NULL;
		$ext		= NULL;
		$image_id	= NULL;
		$size		= NULL;
		
		if( count( $name ) >= 3 ) {			
			$ext = array_pop( $name );
			
			if( $this->config->get('watermark_status') ) {
				list( $st_id, $ext ) = explode( '.', $ext );
				$ext = array_pop( $name ) . '.' . $ext;
			}
			
			$image_id = array_pop( $name );
			
			$tmp	= explode( '.', $ext );
			$ext	= array_pop( $tmp );
			$size	= implode( '.', $tmp );
			$gn		= $this->gn( $image_id );
		
			$cache = $this->cache->get('smp_seo_image_id_' . $gn);
			
			if( $cache && is_array( $cache ) && isset( $cache[$image_id] ) ) {
				$image = $cache[$image_id];
			} else {
				if( ! $cache || ! is_array( $cache ) ) {
					$cache = array();
				}
				
				$insert = false;
				
				foreach( $this->db->query("
					SELECT 
						* 
					FROM 
						" . DB_PREFIX . "smp_image 
					WHERE 
						gi='" . $this->db->escape( $gn ) . "'")->rows as $item ) {
					
					$cache[$item['smp_image_id']] = array( 'file' => $item['file'], 'ext' => $item['ext'] );
					$insert = true;
				}
				
				if( $insert && $cache ) {
					$this->cache->set('smp_seo_image_id_' . $gn, $cache);
				}
				
				$image = isset( $cache[$image_id] ) ? $cache[$image_id] : NULL;
			}
		
			if( ! empty( $image ) ) {				
				$file = DIR_IMAGE . 'cache/' . $image['file'] . '-' . $size;
				
				if( $this->config->get('watermark_status') || ( NULL != ( $iWatermark = $this->config->get( 'iWatermark' ) ) && ! empty( $iWatermark[$this->config->get('config_store_id')]['Enabled'] ) ) ) {
					$file .= '_' . $this->config->get('config_store_id');
				}
				
				$file .= '.' . $ext;
				
				if( file_exists( $file ) ) {		
					switch( $image['ext'] ) {
						case 'gif'	: $ctype='image/gif'; break;
						case 'png'	: $ctype='image/png'; break;
						default		: $ctype='image/jpg';
					}
				} else if( file_exists( DIR_IMAGE . $image['file'] . '.' . $image['ext'] ) ) {
					list( $width, $height ) = $this->_size( $size );
				
					$this->load->model('tool/image');
					
					$this->model_tool_image->resize( $image['file'] . '.' . $image['ext'], $width, $height );
				}
			}
		}
		
		if( $file === NULL || $ctype === NULL ) {
			if( $this->_tryed === false ) {
				$this->_tryed = true;
				sleep(1);
				
				return $this->index();
			}
			
			$this->load->model('tool/image');
			
			list( $width, $height ) = $this->_size( $size );
			
			$this->model_tool_image->resize('placeholder.png', $width, $height);
			
			$file	= DIR_IMAGE . 'cache/no_image-' . $width . 'x' . $height;
			
			// iWatermark
			if( $this->config->get('watermark_status') || ( NULL != ( $iWatermark = $this->config->get( 'iWatermark' ) ) && ! empty( $iWatermark[$this->config->get('config_store_id')]['Enabled'] ) ) ) {
				$file .= '_' . $this->config->get('config_store_id');
			}
			
			$file	.= '.jpg';
			$ctype	= 'image/jpg';
		}
		
		//if( function_exists( 'header_remove' ) )
		//	header_remove();
		
		header('Pragma: public');
		header('Cache-Control: max-age=1209600');
		header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 1209600));
		header('Content-type: ' . $ctype);
		
		//if( function_exists( 'ob_clean' ) )
		//	ob_clean();
		
		//if( function_exists( 'flush' ) )
		//	flush();
		
		if( file_exists( $file ) ) {
			readfile($file);
		}
		
		exit;
	}
	
	private function _size( $size ) {
		$width = $height = 0;
			
		if( $size ) {
			@ list( $width, $height ) = explode( 'x', $size );
				
			$width	= (int) $width;
			$height	= (int) $height;
		}
			
		if( $width < 1 || $width > 500 || $height < 1 || $height > 500 ) {
			$width = $height = 100;
		}
		
		return array( $width, $height );
	}
}
?>