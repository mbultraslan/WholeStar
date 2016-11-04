<?php

class ModelCheckoutCheckoutOnepage extends Model {

    private $checkoutsetting = array();

    function __construct() {
        global $registry;
        parent::__construct($registry);
        $this->load->model('setting/setting');
        $this->checkoutsetting = $this->model_setting_setting->getSetting('mmos_checkout', $this->config->get('config_store_id'));

        $this->mmos_checkout = $this->checkoutsetting['mmos_checkout'];

// ALL CSS and style
        $this->mmos_checkout_css = $this->checkoutsetting['mmos_checkout_css'];

// ALL languages
        $this->mmos_checkout_langs = $this->checkoutsetting['mmos_checkout_langs'];

// All tips and languages

        $this->mmos_checkout_tips = $this->checkoutsetting['mmos_checkout_tips'];
    }

    public function getsetting() {

        return $this->checkoutsetting;
    }

    public function getmainsetting() {

        return $this->mmos_checkout;
    }

    public function getcheckoutCss() {

        return $this->mmos_checkout_css;
    }

    public function getcheckoutLangugues($key = '') {

        if ($key == '') {
// all languages setting
            $languuages = array();
            foreach ($this->mmos_checkout_langs as $langkey => $lang) {
                $languuages[$langkey] = $lang[$this->config->get('config_language_id')];
            }
            return $languuages;
        } else {
            if (isset($this->mmos_checkout_langs[$key][$this->config->get('config_language_id')])) {
// true language
                return $this->mmos_checkout_langs[$key][$this->config->get('config_language_id')];
            } else
            if (isset($this->mmos_checkout_langs[$key])) {
// no current language set (get default)
                return $this->mmos_checkout_langs[$key][reset($this->mmos_checkout_langs[$key])];
            } else {
                return '';
            }
        }
    }

    public function getcheckoutTips() {

        return $this->checkoutsetting['mmos_checkout_tips'];
    }

    public function unset_login_sessions() {
//ensure unset all not logined sessions when have already logined
        if ($this->customer->isLogged()) {
            unset($this->session->data['account']);
            unset($this->session->data['guest']);
            unset($this->session->data['register']);
            unset($this->session->data['checkout_guest']);
            unset($this->session->data['checkout_register']);
//            unset();
        } else {
            unset($this->session->data['checkout_customer']);
        }
    }

    public function rebuid_content_text($text = '') {
        $texts = explode(':', $text);
        if (is_array($texts) && count($texts) > 1) {
            array_shift($texts);
            return trim(implode(' ', $texts));
        } else {
            return $text;
        }
    }

    public function hasCheckoutOnepage() {

        if (!(isset($this->mmos_checkout['status']) && $this->mmos_checkout['status'] == 1)) {
            return 0;
        }
        /* Validate cart has products and has stock. */
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            return -1;
        }
        return 1;
    }

    public function hasValidMinimumQtyRequirement() {
        /* Validate minimum quantity requirments. */
        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                /* $this->response->redirect($this->url->link('checkout/cart')); */
                return false;
            }
        }
        return true;
    }

    public function validate_checkout1() {
        if ($this->hasCheckoutOnepage() == 0) {
            $this->response->redirect($this->url->link('checkout/checkout'));
        } else if ($this->hasCheckoutOnepage() == -1) {
            $this->response->redirect($this->url->link('checkout/cart'));
        }

        if (!$this->hasValidMinimumQtyRequirement()) {
            $this->response->redirect($this->url->link('checkout/cart'));
        }
        return true;
    }

    public function validate_checkout() {
        $redirect = '';
        if ($this->hasCheckoutOnepage() == 0) {
            return $redirect = $this->url->link('checkout/checkout', '', 'SSL');
        } else if ($this->hasCheckoutOnepage() == -1) {
            return $redirect = $this->url->link('checkout/cart', '', 'SSL');
        }

        if (!$this->hasValidMinimumQtyRequirement()) {
            return $redirect = $this->url->link('checkout/cart', '', 'SSL');
        }
        return '';
    }

    public function calculate_shipping() {



        if ($this->mmos_checkout['calculate_non_address_shipping'] == '1' && $this->cart->hasShipping()) {
            if ($this->customer->isLogged()) {
                $shipping = $this->session->data["shipping_address"];
            } else {
                if ($this->session->data['account'] == 'guest') {
                    $shipping = $this->session->data['guest']['shipping'];
                } else if ($this->session->data['account'] == 'register') {
                    $shipping = $this->session->data['register'];
                }
            }
            if (isset($this->mmos_checkout['calculate_shipping_country_id']) && empty($shipping['country_id'])) {
                return true;
            }
            if (isset($this->mmos_checkout['calculate_shipping_postcode']) && (empty($shipping['postcode']) || $shipping['postcode'] == '' )) {

                return true;
            }
            if (isset($this->mmos_checkout['calculate_shipping_zone_id']) && empty($shipping['zone_id'])) {

                return true;
            }
            if (isset($this->mmos_checkout['calculate_shipping_city']) && empty($shipping['city'])) {

                return true;
            }
        }
        return FALSE;
    }

    public function calculate_payment() {

        if ($this->mmos_checkout['calculate_non_address_payment'] == '1') {

            if ($this->customer->isLogged()) {
                $payment = $this->session->data["payment_address"];
            } else {
                if ($this->session->data['account'] == 'guest') {

                    $payment = $this->session->data['guest']['payment'];
                } else if ($this->session->data['account'] == 'register') {

                    $payment = $this->session->data['register'];
                }
            }

            if (isset($this->mmos_checkout['calculate_payment_country_id']) && empty($payment['country_id'])) {
                return true;
            }
            if (isset($this->mmos_checkout['calculate_payment_postcode']) && empty($payment['postcode'])) {
                return true;
            }
            if (isset($this->mmos_checkout['calculate_payment_zone_id']) && empty($payment['zone_id'])) {
                return true;
            }
            if (isset($this->mmos_checkout['calculate_payment_city']) && empty($payment['city'])) {
                return true;
            }
        }
        return FALSE;
    }

    public function getcountries_zone($accont_type) {
        /*
          $accont_type
         * guest
         * register
         * logged => returning-customer
         */

        $view_data = array();



        if (!$this->customer->isLogged()) {
            if (isset($this->session->data[$accont_type]['payment']['country_id'])) {
                $view_data['country_id'] = $this->session->data[$accont_type]['payment']['country_id'];
            } else {
                $view_data['country_id'] = '';
            }

            if (isset($this->session->data[$accont_type]['payment']['zone_id'])) {
                $view_data['zone_id'] = $this->session->data[$accont_type]['payment']['zone_id'];
            } else {
                $view_data['zone_id'] = '';
            }
        } else {

            if (isset($this->session->data['shipping_address']['country_id'])) {
                $view_data['country_id'] = $this->session->data['shipping_address']['country_id'];
            } else {
                $view_data['country_id'] = '';
            }

            if (isset($this->session->data['shipping_address']['zone_id'])) {
                $view_data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
            } else {
                $view_data['zone_id'] = '';
            }
        }

        // when emtype 
        if ($view_data['country_id'] == '') {

            $view_data['country_id'] = $this->config->get('config_zone_id');
        }
        if ($view_data['zone_id'] == '') {

            $view_data['zone_id'] = $this->config->get('config_zone_id');
        }

        $this->load->model('localisation/country');
        $view_data['countries'] = $this->model_localisation_country->getCountries();

        $country_info = $this->model_localisation_country->getCountry($view_data['country_id']);
        if ($country_info) {
            $this->load->model('localisation/zone');
            $view_data['zones'] = $this->model_localisation_zone->getZonesByCountryId($view_data['country_id']);
        } else {
            $view_data['zones'] = array();
        }

        return $view_data;
    }

}

?>
