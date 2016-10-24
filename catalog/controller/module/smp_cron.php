<?php  

define( 'SMP_CRON', true );

class ControllerModuleSmpCron extends Controller {
	
	////////////////////////////////////////////////////////////////////////////
	
	private static $_adminDir = NULL;
	
	private $data = array();
	
	public static function adminDir() {
		if( self::$_adminDir === NULL ) {
			self::$_adminDir = defined( 'SMP_ADMIN_DIR' ) ? SMP_ADMIN_DIR : 'admin';
		}
		
		return self::$_adminDir;
	}
	
	////////////////////////////////////////////////////////////////////////////
	
	public function index() {		
		$settings	= $this->config->get( 'smp_cron' );
		
		if( ! $settings || empty( $settings['enabled'] ) ) {
			echo 'Cron is disabled';
			return;
		}
				
		if( ! isset( $this->request->get['code'] ) || $this->request->get['code'] != $settings['secret_code'] ) {
			echo 'Secret Code is invalid';
			return;
		}
		
		$lock	= DIR_CACHE . 'smp_cron_lock';
		
		if( file_exists( $lock ) ) {
			$time	= (int) file_get_contents( $lock );
			$left	= ( $time + 60 * 60 * 24 ) - time();
			
			if( $left > 0 ) {
				echo 'Locked - left ' . $left . ' seconds';
				return;
			}
		}
		
		file_put_contents( $lock, time() );
		
		$log	= array();
		$file	= DIR_CACHE . 'smp_cron.db';
		$limit	= abs( (int) $settings['limit'] );
		
		if( ! $limit ) {
			$limit = NULL;
		}
		
		$state	= file_exists( $file ) ? unserialize( file_get_contents( $file ) ) : array(
			'sitemap'	=> array(
				'last'	=> NULL,
				'data'	=> array()
			),
			'auto_generators' => array(
				'last'	=> NULL,
				'data'	=> array()
			)
		);
		
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
		
		foreach( $query->rows as $item ) {
			$languages[$item['language_id']] = $item['name'];
		}
		
		if( ! empty( $settings['sitemap'] ) && ( ! $state['sitemap']['last'] || $state['sitemap']['last'] + $settings['sitemap_frequency'] * 60 * 60 < time() ) ) {
			require_once DIR_SYSTEM . '../' . self::adminDir() . '/controller/module/seo_mega_pack.php';
			
			$smp = new ControllerModuleSeoMegaPack( $this->registry );
			
			ob_start();
			
			$this->request->get['limit'] = $limit;
			
			$smp->createSitemap();
			
			$smp = ob_get_clean();
			$smp = explode( '||', $smp );
			
			$log[] = 'SiteMap generation: <b>' . ( $smp[0] == '1' ? 'completed' : $smp[5] . ' - ' . $smp[1] . ' - ' . $smp[3] . ': ' . $smp[4] . '%' ) . '</b>';
			
			if( $smp[0] == '1' )
				$state['sitemap']['last'] = time();
		} else if( ! empty( $settings['sitemap'] ) ) {
			$log[] = 'SiteMap re-generate for: ' . ( ( $state['sitemap']['last'] + $settings['sitemap_frequency'] * 60 * 60 ) - time() ) . ' seconds';
		} else {
			$log[] = 'Generate SiteMap: disabled';
		}
		
		if( ! empty( $settings['auto_generators'] ) && ( ! $state['auto_generators']['last'] || $state['auto_generators']['last'] + $settings['auto_generators_frequency'] * 60 * 60 < time() ) ) {
			require_once DIR_SYSTEM . '../' . self::adminDir() . '/controller/module/seo_mega_pack.php';
			
			$smp		= new ControllerModuleSeoMegaPack( $this->registry );
			$extensions = $smp->getExtensions();
			
			$smp->data['extensions']			= $extensions;
			
			$this->request->get['extensions']	= array();
			
			foreach( $extensions as $key => $extension ) {
				if( in_array( $key, array( 'SeoImagesGenerator' ) ) ) continue;
				
				if( ! in_array( $key, $settings['auto_generators'] ) ) continue;
				
				$this->request->get['extensions'][] = strtolower( preg_replace( '/(.)([A-Z])/', '$1_$2', $key ) );
			}
			
			if( empty( $state['auto_generators']['data']['todo'] ) ) {
				$this->request->get['extensions']	= implode( ',', $this->request->get['extensions'] );
				$this->request->get['info']			= '1';
				$this->request->get['limit']		= $limit;

				$smp->generate();
				
				$todo = array();
				
				foreach( $smp->data['info'] as $key => $val ) {
					if( ! $val['items'] )
						continue;
					
					$val['page']	= 1;
					$todo[$key]		= $val;
				}
				
				$state['auto_generators']['data']['todo'] = $todo;
			}
			
			if( $state['auto_generators']['data']['todo'] ) {
				$current	= array_shift( $state['auto_generators']['data']['todo'] );

				$this->request->get['extensions']	= $current['extension'];
				$this->request->get['mode']			= $current['mode'];
				$this->request->get['info']			= '0';
				$this->request->get['limit']		= $limit;
				$this->request->get['language_id']	= $current['language_id'];

				$smp->generate();
				
				$pages = $this->request->get['limit'] ? ceil( $current['items'] / $this->request->get['limit'] ) : 1;
				
				$log[] = 'Generate ' . ( $current['mode'] ? '(' . $current['mode'] . ') ' : '' ) . round( $pages ? $current['page'] / $pages * 100 : 100, 2 ) . '% - ' . ( $current['language_id'] ? $languages[$current['language_id']] . ' - ' : '' ) . $current['title'] . ' (remaining steps: ' . count($state['auto_generators']['data']['todo']) . ')';
			
				$current['page']++;
				
				if( $current['page'] <= $pages ) {
					array_unshift( $state['auto_generators']['data']['todo'], $current );
				}
			} else {
				$log[] = 'Generators: no data to generate';
			}
			
			if( ! $state['auto_generators']['data']['todo'] ) {
				$state['auto_generators']['last'] = time();
				$state['auto_generators']['data'] = array();
			}
		} else if( ! empty( $settings['auto_generators'] ) ) {
			$log[] = 'Generators re-generate for: ' . ( ( $state['auto_generators']['last'] + $settings['auto_generators_frequency'] * 60 * 60 ) - time() ) . ' seconds';
		} else {
			$log[] = 'Generators: disabled';
		}
		
		file_put_contents( $file, serialize( $state ) );
		
		unlink( $lock );
		
		echo implode( "\n", $log );
	}
}
?>