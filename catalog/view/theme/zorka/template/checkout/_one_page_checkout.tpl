<?php
$kuler->addScript('catalog/view/theme/' . $kuler->getTheme() . '/js/one_page_checkout.js', true);
?>
<script>
  Kuler.one_page_checkout_methods_url = <?php echo json_encode($one_page_checkout_methods_url); ?>;
  Kuler.shipping_required = <?php echo json_encode($shipping_required); ?>;
</script>
<div id="one-page-checkout">
<?php if (!$is_logged) { ?>
  <a id="login"><?php echo _t('text_already_registered_click_here_to_login', 'Already registered? Click here to login'); ?></a>
<?php } ?>
<div class="checkout-info row">
<form id="checkout-form">
  <input type="hidden" name="address_id" value="<?php echo $address_id; ?>" />
  <div id="order-account" class="customer-info col-md-5">
    <div class="row">
      <div class="col-sm-6">
        <fieldset>
          <legend><?php echo _t('text_your_details'); ?></legend>
          <div class="form-group" style="display: <?php echo (count($customer_groups) > 1 ? 'block' : 'none'); ?>;">
            <label class="control-label"><?php echo _t('entry_customer_group'); ?></label>
            <?php foreach ($customer_groups as $customer_group) { ?>
              <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                <div class="radio">
                  <label>
                    <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                    <?php echo $customer_group['name']; ?></label>
                </div>
              <?php } else { ?>
                <div class="radio">
                  <label>
                    <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" />
                    <?php echo $customer_group['name']; ?></label>
                </div>
              <?php } ?>
            <?php } ?>
          </div>
          <div class="form-group required">
            <label class="control-label" for="input-order-firstname"><?php echo _t('entry_firstname'); ?></label>
            <input type="text" name="firstname" value="<?php echo $first_name; ?>" placeholder="<?php echo _t('entry_firstname'); ?>" id="input-order-firstname" class="form-control" />
          </div>
          <div class="form-group required">
            <label class="control-label" for="input-order-lastname"><?php echo _t('entry_lastname'); ?></label>
            <input type="text" name="lastname" value="<?php echo $last_name; ?>" placeholder="<?php echo _t('entry_lastname'); ?>" id="input-order-lastname" class="form-control" />
          </div>
          <div class="form-group required">
            <label class="control-label" for="input-order-email"><?php echo _t('entry_email'); ?></label>
            <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo _t('entry_email'); ?>" id="input-order-email" class="form-control" />
          </div>
          <div class="form-group required">
            <label class="control-label" for="input-order-telephone"><?php echo _t('entry_telephone'); ?></label>
            <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo _t('entry_telephone'); ?>" id="input-order-telephone" class="form-control" />
          </div>
          <div class="form-group">
            <label class="control-label" for="input-order-fax"><?php echo _t('entry_fax'); ?></label>
            <input type="text" name="fax" value="<?php echo $fax; ?>" placeholder="<?php echo _t('entry_fax'); ?>" id="input-order-fax" class="form-control" />
          </div>
          <?php foreach ($custom_fields as $custom_field) { ?>
            <?php if ($custom_field['location'] == 'account') { ?>
              <?php if ($custom_field['type'] == 'select') { ?>
                <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                  <label class="control-label" for="input-order-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                  <select name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" id="input-order-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
                    <option value=""><?php echo _t('text_select'); ?></option>
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                      <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'radio') { ?>
                <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                  <label class="control-label"><?php echo $custom_field['name']; ?></label>
                  <div id="input-order-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                      <div class="radio">
                        <label>
                          <input type="radio" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                          <?php echo $custom_field_value['name']; ?></label>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'checkbox') { ?>
                <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                  <label class="control-label"><?php echo $custom_field['name']; ?></label>
                  <div id="input-order-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                          <?php echo $custom_field_value['name']; ?></label>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'text') { ?>
                <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                  <label class="control-label" for="input-order-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                  <input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-order-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'textarea') { ?>
                <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                  <label class="control-label" for="input-order-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                  <textarea name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-order-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo $custom_field['value']; ?></textarea>
                </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'file') { ?>
                <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                  <label class="control-label"><?php echo $custom_field['name']; ?></label>
                  <br />
                  <button type="button" id="button-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo _t('text_loading'); ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                  <input type="hidden" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="" />
                </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'date') { ?>
                <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                  <label class="control-label" for="input-order-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                  <div class="input-group date">
                    <input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-format="YYYY-MM-DD" id="input-order-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                        <span class="input-group-btn">
                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                        </span>
                  </div>
                </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'time') { ?>
                <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                  <label class="control-label" for="input-order-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                  <div class="input-group time">
                    <input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-format="HH:mm" id="input-order-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                        <span class="input-group-btn">
                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                        </span>
                  </div>
                </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'datetime') { ?>
                <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                  <label class="control-label" for="input-order-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                  <div class="input-group datetime">
                    <input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-format="YYYY-MM-DD HH:mm" id="input-order-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                        <span class="input-group-btn">
                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                        </span>
                  </div>
                </div>
              <?php } ?>
            <?php } ?>
          <?php } ?>
        </fieldset>
      </div>
      <div class="col-sm-6">
        <fieldset id="address">
          <legend><?php echo _t('text_your_address'); ?></legend>
          <div class="form-group">
            <label class="control-label" for="input-order-company"><?php echo _t('entry_company'); ?></label>
            <input type="text" name="company" value="<?php echo $company; ?>" placeholder="<?php echo _t('entry_company'); ?>" id="input-order-company" class="form-control" />
          </div>
          <div class="form-group required">
            <label class="control-label" for="input-order-address-1"><?php echo _t('entry_address_1'); ?></label>
            <input type="text" name="address_1" value="<?php echo $address_1; ?>" placeholder="<?php echo _t('entry_address_1'); ?>" id="input-order-address-1" class="form-control" />
          </div>
          <div class="form-group">
            <label class="control-label" for="input-order-address-2"><?php echo _t('entry_address_2'); ?></label>
            <input type="text" name="address_2" value="<?php echo $address_2; ?>" placeholder="<?php echo _t('entry_address_2'); ?>" id="input-order-address-2" class="form-control" />
          </div>
          <div class="form-group required">
            <label class="control-label" for="input-order-city"><?php echo _t('entry_city'); ?></label>
            <input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo _t('entry_city'); ?>" id="input-order-city" class="form-control" />
          </div>
          <div class="form-group required">
            <label class="control-label" for="input-order-postcode"><?php echo _t('entry_postcode'); ?></label>
            <input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo _t('entry_postcode'); ?>" id="input-order-postcode" class="form-control" />
          </div>
          <div class="form-group required">
            <label class="control-label" for="input-order-country"><?php echo _t('entry_country'); ?></label>
            <select name="country_id" id="input-order-country" class="form-control country-selector" data-post-code-required="#input-order-postcode" data-zone="#input-order-zone">
              <option value=""><?php echo _t('text_select'); ?></option>
              <?php foreach ($countries as $country) { ?>
                <?php if ($country['country_id'] == $country_id) { ?>
                  <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                <?php } else { ?>
                  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                <?php } ?>
              <?php } ?>
            </select>
          </div>
          <div class="form-group required">
            <label class="control-label" for="input-order-zone"><?php echo _t('entry_zone');; ?></label>
            <select name="zone_id" id="input-order-zone" class="form-control" data-value="<?php echo $zone_id; ?>">
            </select>
          </div>
        </fieldset>
      </div>
      <fieldset class="checkbox" style="font-size: 14px;">
        <?php if ($shipping_required) { ?>
        <label><input type="checkbox" name="shipping_address_same" value="1" class="toggle-option" data-no-order-update  data-target="#shipping-address-selector" data-reserve="true" checked="checked" /> <?php echo _t('text_my_delivery_address_and_personal_details_are_the_same'); ?></label>
        <div id="shipping-address-selector" style="margin-top: 20px;">
          <select name="shipping_address_id" id="input-order-shipping-address-id" class="form-control address-selector" data-type="shipping">
            <option value="0"><?php echo _t('text_please_select'); ?></option>
            <?php foreach ($addresses as $address) { ?>
              <option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
            <?php } ?>
            <option value="new"><?php echo _t('text_i_want_to_use_a_new_address'); ?></option>
          </select>
        </div>
      </fieldset>
      <?php } ?>
      <fieldset class="checkbox" style="font-size: 14px;">
        <label><input type="checkbox" name="payment_address_same" value="1" class="toggle-option" data-no-order-update data-target="#payment-address-selector" data-reserve="true" checked="checked" /> <?php echo _t('text_my_payment_address_and_personal_details_are_the_same'); ?></label>
        <div id="payment-address-selector" style="margin-top: 20px;">
          <select name="payment_address_id" id="input-order-payment-address-id" class="form-control address-selector" data-type="payment">
            <option value="0"><?php echo _t('text_please_select'); ?></option>
            <?php foreach ($addresses as $address) { ?>
              <option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
            <?php } ?>
            <option value="new"><?php echo _t('text_i_want_to_use_a_new_address'); ?></option>
          </select>
        </div>
      </fieldset>
      <?php if (!$is_logged) { ?>
      <p style="font-size: 14px;">
        <label><input type="checkbox" name="create_new_account" class="toggle-option" data-target="#password-container" value="1" /> <?php echo _t('text_create_new_account'); ?></label>
        <fieldset id="password-container">
          <div class="form-group required">
            <label class="control-label" for="input-order-password"><?php echo _t('entry_password'); ?></label>
            <input type="password" name="password" id="input-order-password" placeholder="<?php echo _t('entry_password'); ?>" class="form-control" />
          </div>
          <div class="form-group required">
            <label class="control-label" for="input-order-confirm"><?php echo _t('entry_confirm'); ?></label>
            <input type="password" name="confirm" id="input-order-confirm" placeholder="<?php echo _t('entry_confirm'); ?>" class="form-control" />
          </div>
          <div class="checkbox" style="font-size: 14px;">
            <label>
              <input type="checkbox" name="newsletter" value="1" /> <?php echo _t('text_subscribe_newsletter'); ?>
            </label>
          </div>
          <div class="checkbox" style="font-size: 14px;">
            <label>
              <input type="checkbox" name="register_agree" id="input-order-register-agree" value="1" /> <?php echo $text_agree_register; ?>
            </label>
          </div>
        </fieldset>
      </p>
      <?php } ?>
    </div>
  </div>
  <div class="order-info col-md-7">
    <div class="method row">
      <?php if ($shipping_required) { ?>
      <div class="shipping-method col-md-6">
        <h2 id="input-order-shipping-method"><?php echo _t('text_opcheckout_shipping_method'); ?></h2>
        <div id="shipping-method-content"></div>
      </div>
      <?php } ?>
      <div class="payment-method col-md-6">
        <h2><?php echo _t('text_opcheckout_payment_method'); ?></h2>
        <div id="payment-method-content"></div>
      </div>
    </div>
    <div class="order-total">
      <h2 id="input-order-payment-method"><?php echo _t('text_opcheckout_confirm'); ?></h2>
      <div id="order-total-content"></div>
    </div>

    <p>
      <label><input type="checkbox" name="add_comment" value="1" class="toggle-option" data-target="#comment" /> <?php echo _t('text_comments'); ?></label>
      <textarea name="comment" cols="50" rows="10" id="comment"></textarea>
    </p>
    <p>
      <label><input type="checkbox" value="1" class="toggle-option" data-target="#coupon-container" /> <?php echo _t('text_use_coupon'); ?></label>
      <div id="coupon-container">
        <label><?php echo _t('entry_coupon'); ?></label>
        <input type="text" id="coupon" value="<?php echo $coupon; ?>" />
        <input type="button" id="apply-coupon" class="button" value="<?php echo _t('button_coupon'); ?>" />
      </div>
    </p>
    <p>
      <label><input type="checkbox" value="1" class="toggle-option" data-target="#voucher-container" /> <?php echo _t('text_use_voucher'); ?></label>
      <div id="voucher-container">
        <label><?php echo _t('entry_voucher'); ?></label>
        <input type="text" id="voucher" value="<?php echo $voucher; ?>" />
        <input type="button" id="apply-voucher" class="button" value="<?php echo _t('button_voucher'); ?>" />
      </div>
    </p>
    <p>
      <label id="input-order-order-agree"><input type="checkbox" name="order_agree" value="1" /> <?php echo $text_agree; ?></label>
    </p>
    <p style="text-align: right">
      <input type="submit" id="confirm-order" class="button" value="<?php echo _t('button_confirm'); ?>" />
    </p>
  </div>
</form>
</div>
</div>

<div style="display: none;">
  <?php if (!$is_logged) { ?>
    <div id="login-form" class="popup-container">
      <form>
        <div class="form-group required">
          <label class="control-label"><?php echo _t('entry_email'); ?></label>
          <input type="email" name="email" class="form-control" />
        </div>
        <div class="form-group required">
          <label class="control-label"><?php echo _t('entry_password'); ?></label>
          <input type="password" name="password" class="form-control" />
        </div>
        <p>
          <a href="<?php echo $forgotten; ?>"><?php echo _t('text_forgotten'); ?></a>
        </p>
        <p>
          <input type="submit" value="<?php echo _t('button_login'); ?>" class="button" />
        </p>
        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
      </form>
    </div>
  <?php } ?>

  <div id="address-form-container" class="popup-container">
    <h2 data-payment-title="<?php echo _t('text_payment_address'); ?>" data-shipping-title="<?php echo _t('text_shipping_title'); ?>"></h2>

    <form id="address-form">
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-new-firstname"><?php echo _t('entry_firstname'); ?></label>
        <div class="col-sm-10">
          <input type="text" name="firstname" value="" placeholder="<?php echo _t('entry_firstname'); ?>" id="input-new-firstname" class="form-control" />
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-new-lastname"><?php echo _t('entry_lastname'); ?></label>
        <div class="col-sm-10">
          <input type="text" name="lastname" value="" placeholder="<?php echo _t('entry_lastname'); ?>" id="input-new-lastname" class="form-control" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-new-company"><?php echo _t('entry_company'); ?></label>
        <div class="col-sm-10">
          <input type="text" name="company" value="" placeholder="<?php echo _t('entry_company'); ?>" id="input-new-company" class="form-control" />
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-new-address-1"><?php echo _t('entry_address_1'); ?></label>
        <div class="col-sm-10">
          <input type="text" name="address_1" value="" placeholder="<?php echo _t('entry_address_1'); ?>" id="input-new-address-1" class="form-control" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-new-address-2"><?php echo _t('entry_address_2'); ?></label>
        <div class="col-sm-10">
          <input type="text" name="address_2" value="" placeholder="<?php echo _t('entry_address_2'); ?>" id="input-new-address-2" class="form-control" />
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-new-city"><?php echo _t('entry_city'); ?></label>
        <div class="col-sm-10">
          <input type="text" name="city" value="" placeholder="<?php echo _t('entry_city'); ?>" id="input-new-city" class="form-control" />
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-new-postcode"><?php echo _t('entry_postcode'); ?></label>
        <div class="col-sm-10">
          <input type="text" name="postcode" value="" placeholder="<?php echo _t('entry_postcode'); ?>" id="input-new-postcode" class="form-control" />
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-new-country"><?php echo _t('entry_country'); ?></label>
        <div class="col-sm-10">
          <select name="country_id" id="input-new-country" class="form-control country-selector" data-post-code-required="#input-new-postcode" data-zone="#input-new-zone">
            <option value=""><?php echo _t('text_select'); ?></option>
            <?php foreach ($countries as $country) { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-new-zone"><?php echo _t('entry_zone'); ?></label>
        <div class="col-sm-10">
          <select name="zone_id" id="input-new-zone" class="form-control"></select>
        </div>
      </div>
      <?php foreach ($custom_fields as $custom_field) { ?>
        <?php if ($custom_field['location'] == 'address') { ?>
          <?php if ($custom_field['type'] == 'select') { ?>
            <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
              <label class="col-sm-2 control-label" for="input-new-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
              <div class="col-sm-10">
                <select name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" id="input-new-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
                  <option value=""><?php echo _t('text_select'); ?></option>
                  <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          <?php } ?>
          <?php if ($custom_field['type'] == 'radio') { ?>
            <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
              <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
              <div class="col-sm-10">
                <div id="input-new-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                  <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <div class="radio">
                      <label>
                        <input type="radio" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                        <?php echo $custom_field_value['name']; ?></label>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          <?php } ?>
          <?php if ($custom_field['type'] == 'checkbox') { ?>
            <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
              <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
              <div class="col-sm-10">
                <div id="input-new-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                  <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                        <?php echo $custom_field_value['name']; ?></label>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          <?php } ?>
          <?php if ($custom_field['type'] == 'text') { ?>
            <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
              <label class="col-sm-2 control-label" for="input-new-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
              <div class="col-sm-10">
                <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-new-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
              </div>
            </div>
          <?php } ?>
          <?php if ($custom_field['type'] == 'textarea') { ?>
            <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
              <label class="col-sm-2 control-label" for="input-new-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
              <div class="col-sm-10">
                <textarea name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-new-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo $custom_field['value']; ?></textarea>
              </div>
            </div>
          <?php } ?>
          <?php if ($custom_field['type'] == 'file') { ?>
            <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
              <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
              <div class="col-sm-10">
                <button type="button" id="button-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo _t('text_loading'); ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                <input type="hidden" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="" />
              </div>
            </div>
          <?php } ?>
          <?php if ($custom_field['type'] == 'date') { ?>
            <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
              <label class="col-sm-2 control-label" for="input-new-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
              <div class="col-sm-10">
                <div class="input-group date">
                  <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-format="YYYY-MM-DD" id="input-new-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
              </div>
            </div>
          <?php } ?>
          <?php if ($custom_field['type'] == 'time') { ?>
            <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
              <label class="col-sm-2 control-label" for="input-new-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
              <div class="col-sm-10">
                <div class="input-group time">
                  <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-format="HH:mm" id="input-new-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
              </div>
            </div>
          <?php } ?>
          <?php if ($custom_field['type'] == 'datetime') { ?>
            <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
              <label class="col-sm-2 control-label" for="input-new-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
              <div class="col-sm-10">
                <div class="input-group datetime">
                  <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-format="YYYY-MM-DD HH:mm" id="input-new-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
              </div>
            </div>
          <?php } ?>
        <?php } ?>
      <?php } ?>
      <button type="submit" class="btn btn-default"><?php echo _t('button_continue'); ?></button>
    </form>
  </div>
  <div id="payment-form" class="popup-container"></div>
</div>
<script>
  Kuler.one_page_checkout_login_url = <?php echo json_encode($login_url); ?>;
  Kuler.order_confirm_url = <?php echo json_encode($order_confirm_url); ?>;
</script>
