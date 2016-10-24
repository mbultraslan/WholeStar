<?php

class ControllerCommonSearch extends Controller {


				private function _decodeSearchKeyword() {
					$name = 'search';

					if( ! isset( $this->request->get[$name] ) ) {
						$name = $name == 'filter_name' ? 'search' : 'filter_name';
						
						if( ! isset( $this->request->get[$name] ) )
							return '';
					}

					$search = urldecode( $this->request->get[$name] );

					return str_replace( 'Ow==', ';', $search );
				}
			
    public function index() {
        $this->load->language('common/search');

        $data['text_search'] = $this->language->get('text_search');

        if (isset($this->request->get['search'])) {
            
				$data['search'] = $this->_decodeSearchKeyword();
			
        } else {
            $data['search'] = '';
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/search.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/common/search.tpl', $data);
        } else {
            return $this->load->view('default/template/common/search.tpl', $data);
        }
    }

}
