<?php

class ControllerCheckoutShippingMethodOnepage extends Controller {

    public function index($render_content = false) {
        $this->load->model('checkout/checkout_onepage');
        $mmos_checkout_extra = $this->model_checkout_checkout_onepage->getsetting();
        $view_data['mmos_checkout'] = $mmos_checkout_extra['mmos_checkout'];
        $mmos_checkout = $mmos_checkout_extra['mmos_checkout'];
        $view_data['css'] = $mmos_checkout_extra['mmos_checkout_css'];
        $view_data['themes'] = $this->config->get('mmos_checkout_themes');

        $this->load->language('checkout/checkout');

        $this->load->model('account/address');


        $view_data['warning_non_address_shipping'] = '';

        if ($this->model_checkout_checkout_onepage->calculate_shipping()) {
            $this->session->data['shipping_methods'] = array();
            $view_data['warning_non_address_shipping'] = $this->model_checkout_checkout_onepage->getcheckoutLangugues('warning_non_address_shipping');
        } else {

            if (isset($this->session->data['shipping_address'])) {
                // Shipping Methods
                $method_data = array();
                $this->load->model('extension/extension');

                $results = $this->model_extension_extension->getExtensions('shipping');

                foreach ($results as $result) {
                    if ($this->config->get($result['code'] . '_status')) {
                        $this->load->model('shipping/' . $result['code']);

                        $quote = $this->{'model_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

                        if ($quote) {
                            $method_data[$result['code']] = array(
                                'title' => $quote['title'],
                                'quote' => $quote['quote'],
                                'sort_order' => $quote['sort_order'],
                                'error' => $quote['error']
                            );
                        }
                    }
                }

                $sort_order = array();

                foreach ($method_data as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $method_data);

                $this->session->data['shipping_methods'] = $method_data;
            }
        }


        $view_data['text_shipping_method'] = $this->language->get('text_shipping_method');

        $view_data['button_continue'] = $this->language->get('button_continue');




        if (isset($this->session->data['shipping_methods'])) {
            $view_data['shipping_methods'] = $this->session->data['shipping_methods'];
            $view_data['error_warning'] = '';
        } else {
            $view_data['shipping_methods'] = array();
            $view_data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
        }

        //store shipping_methods session for later validate post shipping_method
        if (!$this->customer->isLogged()) {
            if ($this->session->data['account'] == 'guest') {

                $this->session->data['checkout_guest']['shipping_methods'] = $this->session->data['shipping_methods'];
            } else if ($this->session->data['account'] == 'register') {
                $this->session->data['checkout_register']['shipping_methods'] = $this->session->data['shipping_methods'];
            }
        } else {
            $this->session->data['checkout_customer']['shipping_methods'] = $this->session->data['shipping_methods'];
        }

        $shipping_method_code = '';
        if (isset($this->session->data['shipping_method']['code'])) {
            $shipping_method_code = $this->session->data['shipping_method']['code'];
        } else {
            $shipping_method_code = '';
        }


        $is_set = false;
        foreach ($this->session->data['shipping_methods'] as $shipping_method) {
            foreach ($shipping_method['quote'] as $quote) {
                if ($shipping_method_code == $quote['code']) {
                    $this->session->data['shipping_method'] = $quote;
                    $is_set = true;
                    break;
                }
            }
        }
        if ($is_set) {
            $view_data['code'] = $shipping_method_code;
        } else {
            if (!empty($this->session->data['shipping_methods'])) {
                $first_method = (array) reset($this->session->data['shipping_methods']);
                $this->session->data['shipping_method'] = reset($first_method['quote']);
                $view_data['code'] = !empty($this->session->data['shipping_method']['code']) ? $this->session->data['shipping_method']['code'] : '';
            } else {
                $view_data['code'] = '';
            }
        }


        if (version_compare(VERSION, '2.2.0.0') < 0) {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/shipping_method_onepage.tpl')) {
                $template = $this->config->get('config_template') . '/template/checkout/shipping_method_onepage.tpl';
            } else {
                $template = 'default/template/checkout/shipping_method_onepage.tpl';
            }
        } else {
            $template = 'checkout/shipping_method_onepage';
        }

        if (!$render_content) {
            $this->response->setOutput($this->load->view($template, $view_data));
        } else {
            return $this->load->view($template, $view_data);
        }
    }

    public function render_index() {
        return $this->index(true);
    }

    public function validate() {
        $this->load->language('checkout/checkout');

        $json = array();
        ob_start();
        // Validate if shipping is required. If not the customer should not have reached this page.
        if (!$this->cart->hasShipping()) {
            $json['redirect'] = $this->url->link('checkout/checkout_onepage', '', 'SSL');
        }

        // Validate if shipping address has been set.		
        $this->load->model('account/address');

        if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {
            $shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
        } elseif (isset($this->session->data['guest'])) {
            $shipping_address = $this->session->data['guest']['shipping'];
        }

        if (empty($shipping_address)) {
            $json['redirect'] = $this->url->link('checkout/checkout_onepage', '', 'SSL');
        }

        // Validate cart has products and has stock.	
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $json['redirect'] = $this->url->link('checkout/cart');
        }

        // Validate minimum quantity requirments.			
        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $json['redirect'] = $this->url->link('checkout/cart');

                break;
            }
        }
        if (!$this->customer->isLogged()) {
            if ($this->session->data['account'] == 'guest') {
                $this->session->data['shipping_methods'] = !empty($this->session->data['checkout_guest']['shipping_methods']) ? $this->session->data['checkout_guest']['shipping_methods'] : array();
            } else if ($this->session->data['account'] == 'register') {
//            $this->session->data['register']['shipping_method'] = $this->session->data['shipping_method'];
                $this->session->data['shipping_methods'] = !empty($this->session->data['checkout_register']['shipping_methods']) ? $this->session->data['checkout_register']['shipping_methods'] : array();
            }
        } else {
            $this->session->data['shipping_methods'] = !empty($this->session->data['checkout_customer']['shipping_methods']) ? $this->session->data['checkout_customer']['shipping_methods'] : array();
        }


        if (!$json) {
            if (!isset($this->request->post['shipping_method'])) {
                $json['error']['warning'] = $this->language->get('error_shipping');
            } else {
                $shipping = explode('.', $this->request->post['shipping_method']);

                if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                    $json['error']['warning'] = $this->language->get('error_shipping');
                }
            }

            if (!$json) {
                $shipping = explode('.', $this->request->post['shipping_method']);

                $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
            }
        }
        ob_clean();


        $this->response->setOutput(json_encode($json));
    }

    public function quick_validate() {
        $this->load->language('checkout/checkout');

        $json = array();
        ob_start();
        if (!isset($this->request->post['shipping_method'])) {
            $json['error']['warning'] = $this->language->get('error_shipping');
        } else {
            $shipping = explode('.', $this->request->post['shipping_method']);

            if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                $json['error']['warning'] = $this->language->get('error_shipping');
            }
        }

        if (!$json) {
            $shipping = explode('.', $this->request->post['shipping_method']);

            $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

            if (!$this->customer->isLogged()) {
                ##store shipping method to current account(guest or post)
                if ($this->session->data['account'] == 'guest') {
                    $this->session->data['checkout_guest']['shipping_method'] = $this->session->data['shipping_method'];
                } else if ($this->session->data['account'] == 'register') {
                    $this->session->data['checkout_register']['shipping_method'] = $this->session->data['shipping_method'];
                }
            } else {
                //no need, because no need change between checkout states(there is only one state).
                // $this->session->data['checkout_customer']['shipping_method']=$this->session->data['shipping_method'];
            }
        }
        ob_end_clean();
        $this->response->setOutput(json_encode($json));
    }

}

?>