<?php

class ControllerCheckoutCheckoutOnepageContent extends Controller {

    public function index($render_content = false) {
        $this->load->model('checkout/checkout_onepage');

        $redirect = $this->model_checkout_checkout_onepage->validate_checkout();

        if (!$redirect) {
            $this->model_checkout_checkout_onepage->unset_login_sessions();

            // <editor-fold defaultstate="collapsed" desc="ADD SUB-SECTION-PAGES">
            $view_data['left_content'] = $this->load->controller('checkout/checkout_onepage_left_content', true);

            $view_data['right_content'] = $this->load->controller('checkout/checkout_onepage_right_content', true);
            // </editor-fold>
        } else {
            $view_data['redirect'] = $redirect;
        }

        if (version_compare(VERSION, '2.2.0.0') < 0) {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/checkout_onepage_content.tpl')) {
                $template = $this->config->get('config_template') . '/template/checkout/checkout_onepage_content.tpl';
            } else {
                $template = 'default/template/checkout/checkout_onepage_content.tpl';
            }
        } else {
            $template = 'checkout/checkout_onepage_content';
        }

        //OUTPUT
        if (!$render_content) {
            $this->response->setOutput($this->load->view($template, $view_data));
        } else {
            return $this->load->view($template, $view_data);
        }
    }

    public function render_index() {
        return $this->index(true); //true: render content, not output
    }

}

?>