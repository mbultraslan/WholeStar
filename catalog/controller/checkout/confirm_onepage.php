<?php

class ControllerCheckoutConfirmOnepage extends Controller {

    public function index($render_content = false) {

        $redirect = '';
        $view_data = array();
        $error = array();
        $this->load->model('checkout/checkout_onepage');
        $this->load->language('checkout/checkout');

        $mmos_checkout_extra = $this->model_checkout_checkout_onepage->getsetting();
        $view_data['mmos_checkout'] = $mmos_checkout_extra['mmos_checkout'];

        $view_data['text_alert_finish'] = $this->language->get('text_alert_finish');

        $redirect = $this->model_checkout_checkout_onepage->validate_checkout();
        if (!$redirect) {
            $this->validate_order(true, false, $redirect, $error);
        }
        $view_data['error'] = $error;

        if (!$redirect) {
            $order_data = array();
            $totals = array();
            $total = 0;
            $order_data['totals'] = $this->get_total($totals, $total);

            $this->initialize_viewdata($order_data, $view_data);

            if (!$this->customer->isLogged()) {
                $view_data['account'] = $this->session->data['account'];
            }
            if ($this->customer->isLogged()) {
                $view_data['logged'] = 1;
            } else {
                $view_data['logged'] = 0;
            }
        } else {
            $view_data['redirect'] = $redirect;
        }

        if (version_compare(VERSION, '2.2.0.0') < 0) {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/order_summary.tpl')) {
                $template = $this->config->get('config_template') . '/template/checkout/order_summary.tpl';
            } else {
                $template = 'default/template/checkout/order_summary.tpl';
            }
        } else {
            $template = 'checkout/order_summary';
        }

        if (!$render_content) {
            $this->response->setOutput($this->load->view($template, $view_data));
        } else {
            return $this->load->view($template, $view_data);
        }
    }

    public function confirmOrder() {
        $redirect = '';
        $view_data = array();
        $error = array();
        $this->load->model('checkout/checkout_onepage');
        $this->load->language('checkout/checkout');
        $view_data['mmos_checkout'] = $this->model_checkout_checkout_onepage->getmainsetting();

        $redirect = $this->model_checkout_checkout_onepage->validate_checkout();
        if (!$redirect) {
            $this->validate_order(true, true, $redirect, $error);
        }

        $view_data['error'] = $error;

        if (!$redirect) {
            $order_data = array();
            $totals = array();
            $total = 0;
            $order_data['totals'] = $this->get_total($totals, $total);


            $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
            $order_data['store_id'] = $this->config->get('config_store_id');
            $order_data['store_name'] = $this->config->get('config_name');

            if ($order_data['store_id']) {
                $order_data['store_url'] = $this->config->get('config_url');
            } else {
                $order_data['store_url'] = HTTP_SERVER;
            }

            if ($this->customer->isLogged()) {
                $this->load->model('account/customer');

                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

                $order_data['customer_id'] = $this->customer->getId();
                $order_data['customer_group_id'] = $customer_info['customer_group_id'];
                $order_data['firstname'] = $customer_info['firstname'];
                $order_data['lastname'] = $customer_info['lastname'];
                $order_data['email'] = $customer_info['email'];
                $order_data['telephone'] = $customer_info['telephone'];
                $order_data['fax'] = $customer_info['fax'];
                if (version_compare(VERSION, '2.1.0.1') < 0) {
					$order_data['custom_field'] = unserialize($customer_info['custom_field']);
				} else {
					$order_data['custom_field'] = json_decode($customer_info['custom_field']);
				}  
            } elseif (isset($this->session->data['guest'])) {
                $order_data['customer_id'] = 0;
                $order_data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
                $order_data['firstname'] = $this->session->data['guest']['firstname'];
                $order_data['lastname'] = $this->session->data['guest']['lastname'];
                $order_data['email'] = $this->session->data['guest']['email'];
                $order_data['telephone'] = $this->session->data['guest']['telephone'];
                $order_data['fax'] = $this->session->data['guest']['fax'];
                $order_data['custom_field'] = $this->session->data['guest']['custom_field'];
            }

            $order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
            $order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
            $order_data['payment_company'] = $this->session->data['payment_address']['company'];
            $order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
            $order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
            $order_data['payment_city'] = $this->session->data['payment_address']['city'];
            $order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
            $order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
            $order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
            $order_data['payment_country'] = $this->session->data['payment_address']['country'];
            $order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
            $order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
            $order_data['payment_custom_field'] = (isset($this->session->data['payment_address']['custom_field']) ? $this->session->data['payment_address']['custom_field'] : array());

            if (isset($this->session->data['payment_method']['title'])) {
                $order_data['payment_method'] = $this->session->data['payment_method']['title'];
            } else {
                $order_data['payment_method'] = '';
            }

            if (isset($this->session->data['payment_method']['code'])) {
                $order_data['payment_code'] = $this->session->data['payment_method']['code'];
            } else {
                $order_data['payment_code'] = '';
            }

            if ($this->cart->hasShipping()) {
                $order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
                $order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
                $order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
                $order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
                $order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
                $order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
                $order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
                $order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
                $order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
                $order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
                $order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
                $order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
                $order_data['shipping_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : array());

                if (isset($this->session->data['shipping_method']['title'])) {
                    $order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
                } else {
                    $order_data['shipping_method'] = '';
                }

                if (isset($this->session->data['shipping_method']['code'])) {
                    $order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
                } else {
                    $order_data['shipping_code'] = '';
                }
            } else {
                $order_data['shipping_firstname'] = '';
                $order_data['shipping_lastname'] = '';
                $order_data['shipping_company'] = '';
                $order_data['shipping_address_1'] = '';
                $order_data['shipping_address_2'] = '';
                $order_data['shipping_city'] = '';
                $order_data['shipping_postcode'] = '';
                $order_data['shipping_zone'] = '';
                $order_data['shipping_zone_id'] = '';
                $order_data['shipping_country'] = '';
                $order_data['shipping_country_id'] = '';
                $order_data['shipping_address_format'] = '';
                $order_data['shipping_custom_field'] = array();
                $order_data['shipping_method'] = '';
                $order_data['shipping_code'] = '';
            }

            $order_data['products'] = array();

            foreach ($this->cart->getProducts() as $product) {
                $option_data = array();

                foreach ($product['option'] as $option) {
                    $option_data[] = array(
                        'product_option_id' => $option['product_option_id'],
                        'product_option_value_id' => $option['product_option_value_id'],
                        'option_id' => $option['option_id'],
                        'option_value_id' => $option['option_value_id'],
                        'name' => $option['name'],
                        'value' => $option['value'],
                        'type' => $option['type']
                    );
                }

                $order_data['products'][] = array(
                    'product_id' => $product['product_id'],
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'option' => $option_data,
                    'download' => $product['download'],
                    'quantity' => $product['quantity'],
                    'subtract' => $product['subtract'],
                    'price' => $product['price'],
                    'total' => $product['total'],
                    'tax' => $this->tax->getTax($product['price'], $product['tax_class_id']),
                    'reward' => $product['reward']
                );
            }

            // Gift Voucher
            $order_data['vouchers'] = array();

            if (!empty($this->session->data['vouchers'])) {
                foreach ($this->session->data['vouchers'] as $voucher) {
                    $order_data['vouchers'][] = array(
                        'description' => $voucher['description'],
                        'code' => substr(md5(mt_rand()), 0, 10),
                        'to_name' => $voucher['to_name'],
                        'to_email' => $voucher['to_email'],
                        'from_name' => $voucher['from_name'],
                        'from_email' => $voucher['from_email'],
                        'voucher_theme_id' => $voucher['voucher_theme_id'],
                        'message' => $voucher['message'],
                        'amount' => $voucher['amount']
                    );
                }
            }

            $order_data['comment'] = !empty($this->session->data['comment']) ? $this->session->data['comment'] : '';
            $order_data['total'] = $total;

            if (isset($this->request->cookie['tracking'])) {
                $order_data['tracking'] = $this->request->cookie['tracking'];

                $subtotal = $this->cart->getSubTotal();

                // Affiliate
                $this->load->model('affiliate/affiliate');

                $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);

                if ($affiliate_info) {
                    $order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
                    $order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
                } else {
                    $order_data['affiliate_id'] = 0;
                    $order_data['commission'] = 0;
                }

                // Marketing
                $this->load->model('checkout/marketing');

                $marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

                if ($marketing_info) {
                    $order_data['marketing_id'] = $marketing_info['marketing_id'];
                } else {
                    $order_data['marketing_id'] = 0;
                }
            } else {
                $order_data['affiliate_id'] = 0;
                $order_data['commission'] = 0;
                $order_data['marketing_id'] = 0;
                $order_data['tracking'] = '';
            }

            $order_data['language_id'] = $this->config->get('config_language_id');
            $order_data['ip'] = $this->request->server['REMOTE_ADDR'];

            if (version_compare(VERSION, '2.2.0.0') < 0) {
                $order_data['currency_id'] = $this->currency->getId();
                $order_data['currency_code'] = $this->currency->getCode();
                $order_data['currency_value'] = $this->currency->getValue($this->currency->getCode());
            } else {
                $order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
                $order_data['currency_code'] = $this->session->data['currency'];
                $order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
            }

            if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                $order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
            } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
                $order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
            } else {
                $order_data['forwarded_ip'] = '';
            }

            if (isset($this->request->server['HTTP_USER_AGENT'])) {
                $order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
            } else {
                $order_data['user_agent'] = '';
            }

            if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
                $order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
            } else {
                $order_data['accept_language'] = '';
            }


            $this->load->model('checkout/order');

            $this->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);

            $view_data['payment'] = $this->load->controller('payment/' . $this->session->data['payment_method']['code']);

            $this->initialize_viewdata($order_data, $view_data);
//            $view_data['shipping_required']=$this->cart->hasShipping();
            $view_data['payment_method'] = $order_data['payment_method'];

            $view_data['shipping_method'] = $order_data['shipping_method'];
            $view_data['payment_address'] = "{$order_data['payment_firstname']} {$order_data['payment_lastname']}, {$order_data['payment_address_1']}, {$order_data['payment_city']}, {$order_data['payment_zone']}, {$order_data['payment_postcode']}, {$order_data['payment_country']}";
            $view_data['shipping_address'] = "{$order_data['shipping_firstname']} {$order_data['shipping_lastname']}, {$order_data['shipping_address_1']}, {$order_data['shipping_city']}, {$order_data['shipping_zone']}, {$order_data['shipping_postcode']}, {$order_data['shipping_country']}";


            if (!$this->customer->isLogged()) {
                $view_data['account'] = $this->session->data['account'];
            }
            if ($this->customer->isLogged()) {
                $view_data['logged'] = 1;
            } else {
                $view_data['logged'] = 0;
            }
        } else {
            $view_data['redirect'] = $redirect;
        }

        if (version_compare(VERSION, '2.2.0.0') < 0) {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/order_confirm_onepage.tpl')) {
                $template = $this->config->get('config_template') . '/template/checkout/order_confirm_onepage.tpl';
            } else {
                $template = 'default/template/checkout/order_confirm_onepage.tpl';
            }
        } else {
            $template = 'checkout/order_confirm_onepage';
        }
print_r($view_data);
        $this->response->setOutput($this->load->view($template, $view_data));
    }

    protected function get_total(&$totals = array(), &$total = 0) {

        $taxes = $this->cart->getTaxes();
        $this->load->model('checkout/checkout_onepage');

        $this->load->model('extension/extension');

        $sort_order = array();

        $results = $this->model_extension_extension->getExtensions('total');

        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        if (version_compare(VERSION, '2.2.0.0') >= 0) {
            $total_data = array(
                'totals' => &$totals,
                'taxes' => &$taxes,
                'total' => &$total
            );
        }

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                if ($this->model_checkout_checkout_onepage->calculate_shipping() != 0) {
                    if ($result['code'] != 'shipping') {
                        $this->load->model('total/' . $result['code']);

                        if (version_compare(VERSION, '2.2.0.0') < 0) {
                            $this->{'model_total_' . $result['code']}->getTotal($totals, $total, $taxes);
                        } else {
                            $this->{'model_total_' . $result['code']}->getTotal($total_data);
                        }
                    }
                } else {
                    $this->load->model('total/' . $result['code']);

                    if (version_compare(VERSION, '2.2.0.0') < 0) {
                        $this->{'model_total_' . $result['code']}->getTotal($totals, $total, $taxes);
                    } else {
                        $this->{'model_total_' . $result['code']}->getTotal($total_data);
                    }
                }
            }
        }

        $sort_order = array();

        foreach ($totals as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $totals);
        return $totals;
    }

    protected function initialize_viewdata($order_data = array(), &$view_data) {
        $this->load->model('checkout/checkout_onepage');

        //button update/remove cart
        $view_data['button_update'] = $this->language->get('button_update');
        $view_data['button_remove'] = $this->language->get('button_remove');

        $mmos_checkout_extra = $this->model_checkout_checkout_onepage->getsetting();

        $view_data['css'] = $mmos_checkout_extra['mmos_checkout_css'];

        $view_data['langs'] = $this->model_checkout_checkout_onepage->getcheckoutLangugues();


        $view_data['themes'] = $this->config->get('mmos_checkout_themes');
        if (empty($css['checkout_theme']) || !in_array($css['checkout_theme'], $themes)) {
            $css['checkout_theme'] = 'standar';
        }

        $view_data['make_order_button'] = $view_data['langs']['make_order_button'];
        $view_data['text_alert_register'] = $view_data['langs']['text_alert_register'];
        $view_data['edit_order_button'] = $view_data['langs']['edit_order_button'];


        $view_data['text_cart'] = $this->language->get('text_cart');
        $view_data['column_name'] = $this->language->get('column_name');
        $view_data['column_model'] = $this->language->get('column_model');
        $view_data['column_quantity'] = $this->language->get('column_quantity');
        $view_data['column_price'] = $this->language->get('column_price');
        $view_data['column_total'] = $this->language->get('column_total');

        $view_data['text_recurring_item'] = $this->language->get('text_recurring_item');
        $view_data['text_payment_profile'] = $this->language->get('text_payment_profile');

        $view_data['text_comments'] = $this->language->get('text_comments');
        $this->load->model('checkout/checkout_onepage');
        $view_data['text_button_checkout_confirm'] = $this->model_checkout_checkout_onepage->rebuid_content_text($this->language->get('text_checkout_confirm'));



        $this->load->model('tool/upload');
        $view_data['products'] = array();

        foreach ($this->cart->getProducts() as $product) {
            $option_data = array();

            foreach ($product['option'] as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['value'];
                } else {
                    $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                    if ($upload_info) {
                        $value = $upload_info['name'];
                    } else {
                        $value = '';
                    }
                }

                $option_data[] = array(
                    'name' => $option['name'],
                    'type'  => $option['type'],
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                );
            }

            $recurring = '';

            if ($product['recurring']) {
                $frequencies = array(
                    'day' => $this->language->get('text_day'),
                    'week' => $this->language->get('text_week'),
                    'semi_month' => $this->language->get('text_semi_month'),
                    'month' => $this->language->get('text_month'),
                    'year' => $this->language->get('text_year'),
                );

                if ($product['recurring_trial']) {
                    if (version_compare(VERSION, '2.2.0.0') < 0) {
                        $recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
                    } else {
                        $recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
                    }
                }

                if ($product['recurring_duration']) {
                    if (version_compare(VERSION, '2.2.0.0') < 0) {
                        $recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                    } else {
                        $recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                    }
                } else {
                    if (version_compare(VERSION, '2.2.0.0') < 0) {
                        $recurring .= sprintf($this->language->get('text_payment_until_canceled_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                    } else {
                        $recurring .= sprintf($this->language->get('text_payment_until_canceled_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                    }
                }
            }



            if ($product['image']) {
                $this->load->model('tool/image');
                if (version_compare(VERSION, '2.2.0.0') < 0) {
                    $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
                    $image_popup = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
                } else {
                    $image = $this->model_tool_image->resize($product['image'], $this->config->get($this->config->get('config_theme') . '_image_cart_width'), $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
                    $image_popup = $this->model_tool_image->resize($product['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));
                }
            } else {
                $image = '';
                $image_popup = '';
            }

            $view_data['config_image_popup_width'] = $this->config->get('config_image_thumb_width');
            $view_data['config_image_popup_height'] = $this->config->get('config_image_thumb_height');

            if (version_compare(VERSION, '2.1.0.0') < 0) {
                $cart_key = $product['key'];
            } else {
                $cart_key = $product['cart_id'];
            }
            if (version_compare(VERSION, '2.2.0.0') < 0) {
                $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
                $total_price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
            } else {
                $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                $total_price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'], $this->session->data['currency']);
            }
            $view_data['products'][] = array(
                'key' => $cart_key,
                'thumb' => $image,
                'image_popup' => $image_popup,
                'product_id' => $product['product_id'],
                'name' => $product['name'],
                'model' => $product['model'],
                'option' => $option_data,
                'recurring' => $recurring,
                'quantity' => $product['quantity'],
                'subtract' => $product['subtract'],
                
                
                //remove cart
                'remove' => $this->url->link('checkout/cart', 'remove=' . $cart_key),
                'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
                    'eachPrice'      => $this->currency->format($this->tax->calculate($product['eachPrice'], $product['tax_class_id'], $this->config->get('config_tax'))),
                    'packPrice'      => $this->currency->format($this->tax->calculate($product['packPrice'], $product['tax_class_id'], $this->config->get('config_tax'))),
                    'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
                    'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                    'packData' => $product['packData'],
                    'singleData' => $product['singleData'],
                    'packQty' => $product['packQty'],
                    'singleQty' => $product['singleQty'],
                    'productRatio' => $product['productRatio'],
                    'productRatioScale' => $product['productRatioScale'],
            );
        }

        // Gift Voucher
        $view_data['vouchers'] = array();

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $key => $voucher) {
                $view_data['vouchers'][] = array(
                    'key' => $key,
                    'description' => $voucher['description'],
                    'amount' => (version_compare(VERSION, '2.2.0.0') < 0) ? $this->currency->format($voucher['amount']) : $this->currency->format($voucher['amount'], $this->session->data['currency'])
                );
            }
        }

        $view_data['totals'] = array();
        foreach ($order_data['totals'] as $total) {
            $view_data['totals'][] = array(
                'title' => $total['title'],
                'text' => (version_compare(VERSION, '2.2.0.0') < 0) ? $this->currency->format($total['value']) : $this->currency->format($total['value'], $this->session->data['currency'])
            );
        }

        //term conditions
        if ($this->config->get('config_checkout_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

            if ($information_info) {
                $mmos_text_agree = str_replace('<a href="%s" class="agree">', '<a href="%s" dala-link="%s" class="mmos-agree">', $this->language->get('text_agree'));
                $view_data['text_agree'] = sprintf($mmos_text_agree, $this->url->link('information/information', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'), $information_info['title'], $information_info['title']);
            } else {
                $view_data['text_agree'] = '';
            }
        } else {
            $view_data['text_agree'] = '';
        }

        if (isset($this->session->data['agree'])) {
            $view_data['agree'] = $this->session->data['agree'];
        } else {
            $view_data['agree'] = '1';
        }
        if (isset($this->session->data['comment'])) {
            $view_data['comment'] = $this->session->data['comment'];
        } else {
            $view_data['comment'] = '';
        }
        $view_data['shipping_required'] = $this->cart->hasShipping();
        $view_data['no_shipping_required'] = $view_data['langs']['no_shipping_required'];
        $view_data['text_checkout_shipping_method'] = $this->model_checkout_checkout_onepage->rebuid_content_text($this->language->get('text_checkout_shipping_method'));
        $view_data['text_checkout_payment_method'] = $this->model_checkout_checkout_onepage->rebuid_content_text($this->language->get('text_checkout_payment_method'));
        $view_data['text_checkout_payment_address'] = $this->model_checkout_checkout_onepage->rebuid_content_text($this->language->get('text_checkout_payment_address'));
        $view_data['text_checkout_shipping_address'] = $this->model_checkout_checkout_onepage->rebuid_content_text($this->language->get('text_checkout_shipping_address'));
        $this->load->language('account/order');
        $view_data['text_comment'] = $this->language->get('text_comment');
        $view_data['text_order_detail'] = $this->language->get('text_order_detail');
    }

    public function validate_order($none_quick_validate = false, $process = false, &$redirect = '', &$error = array()) {
        //args=array() callback function
        //don't use $quick_validate=true
        //$none_quick_validate is only the argument which submit form client, is array(0)
        //all arguments submit from client is dispatch to the first parameter.
        $json = array();
        ob_start();
        $this->load->language('checkout/checkout');
        if ($process) {
            $guest_checkout = ($this->config->get('config_checkout_guest') && !$this->config->get('config_customer_price') && !$this->cart->hasDownload());
            if (!$this->customer->isLogged() && !$guest_checkout) {
                // $redirect = $this->url->link('checkout/checkout_onepage', '', 'SSL');
            }

            if ($this->config->get('config_checkout_id')) {
                $this->load->model('catalog/information');

                $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

                if ($information_info && empty($this->session->data['agree'])) {
                    //$redirect = $this->url->link('checkout/checkout_onepage', '', 'SSL');
                    $error['error_agree'] = sprintf($this->language->get('error_agree'), $information_info['title']);
                }
            }
        }
        if ($none_quick_validate) {
            if ($this->cart->hasShipping()) {
                // Validate if shipping address has been set.
                if (!isset($this->session->data['shipping_address'])) {
                    $redirect = $this->url->link('checkout/checkout_onepage', '', 'SSL');
                    $error['error_address'] = $this->language->get('error_address') . '(Shipping address)';
                }

                // Validate if shipping method has been set.
                /* not by Khoa
                  if (!isset($this->session->data['shipping_method'])) {
                  $redirect = $this->url->link('checkout/checkout_onepage', '', 'SSL');
                  $error['error_shipping'] = $this->language->get('error_shipping');
                  } */
            } else {
                unset($this->session->data['shipping_address']);
                unset($this->session->data['shipping_method']);
                unset($this->session->data['shipping_methods']);
            }

            // Validate if payment address has been set.
            if (!isset($this->session->data['payment_address'])) {
                $redirect = $this->url->link('checkout/checkout_onepage', '', 'SSL');
                $error['error_address'] = $this->language->get('error_address') . '(Payment address)';
            }

            // Validate if payment method has been set.
            /* not by Khoa
              if (!isset($this->session->data['payment_method'])) {
              $redirect = $this->url->link('checkout/checkout_onepage', '', 'SSL');
              $error['error_payment'] = $this->language->get('error_payment');
              } */
        } else {
            if (isset($this->request->post['comment'])) {
                $this->session->data['comment'] = $this->request->post['comment'];
            }
            if (isset($this->request->post['agree'])) {
                $this->session->data['agree'] = $this->request->post['agree'];
            }
            if ($this->config->get('config_checkout_id')) {
                $this->load->model('catalog/information');

                $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

                if ($information_info && !isset($this->request->post['agree'])) {
                    $json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
                }
            }
            ob_end_clean();
            $this->response->setOutput(json_encode($json));
        }
    }

    public function showConfirmAddress($order_data) {
        $returnData = array();


        if ($order_data) {
            $type_allow = array('payment', 'shipping');
            $this->load->model('localisation/country');
            $payment_address = '';
            $shipping_address = '';
            foreach ($type_allow as $type) {
                $country_info = $this->model_localisation_country->getCountry($order_data[$type . 'country_id']);
                $datareturn = array('firstname', 'lastname', 'address_1', 'address_2', 'city', 'zone', 'postcode', 'country');
                if (!empty($country_info['address_format'])) {
                    
                }
                $view_data['payment_address'] = "{$order_data['payment_firstname']} {$order_data['payment_lastname']}, {$order_data['payment_address_1']}, {$order_data['payment_city']}, {$order_data['payment_zone']}, {$order_data['payment_country']}";
                $view_data['shipping_address'] = "{$order_data['shipping_firstname']} {$order_data['shipping_lastname']}, {$order_data['shipping_address_1']}, {$order_data['shipping_city']}, {$order_data['shipping_zone']}, {$order_data['shipping_country']}";
            }
        }
    }

}
