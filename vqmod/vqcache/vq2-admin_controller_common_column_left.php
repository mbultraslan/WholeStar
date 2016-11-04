<?php
class ControllerCommonColumnLeft extends Controller {
	public function index() {

				if( $this->config->get( 'smp_is_install' ) && isset( $this->session->data['token'] ) ) {
					$data['text_seo_mega_pack'] = $this->language->get('text_seo_mega_pack');
					$data['text_smp_extensions'] = $this->language->get('text_smp_extensions');
					$data['text_smp_manager'] = $this->language->get('text_smp_manager');
					
					if( $data['text_seo_mega_pack'] == 'text_seo_mega_pack' )
						$data['text_seo_mega_pack'] = 'SEO Mega KIT PLUS';
						
					if( $data['text_smp_extensions'] == 'text_smp_extensions' )
						$data['text_smp_extensions'] = 'Extensions';
						
					if( $data['text_smp_manager'] == 'text_smp_manager' )
						$data['text_smp_manager'] = 'Manager';

					$data['seo_mega_pack'] = $this->url->link('module/seo_mega_pack', 'token=' . $this->session->data['token'], 'SSL');
					$data['smp_manager'] = $this->url->link('module/seo_mega_pack/manager', 'token=' . $this->session->data['token'], 'SSL');
				}
			
		if (isset($this->request->get['token']) && isset($this->session->data['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$data['profile'] = $this->load->controller('common/profile');
			$data['menu'] = $this->load->controller('common/menu');
			$data['stats'] = $this->load->controller('common/stats');

			return $this->load->view('common/column_left.tpl', $data);
		}
	}
}