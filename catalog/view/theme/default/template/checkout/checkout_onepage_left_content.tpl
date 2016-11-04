<!-- CHECKOUT OPTIONS & BILLING ADDRESS -->
<?php $classtheme = ($css['checkout_theme'] == 'standar') ?  'warning' :  $css['checkout_theme']; ?>
<?php if (!$logged) { ?>
<style>
    #checkout-options  .list-group .list-group-item{
        cursor: pointer;
        padding: 5px 10px;
    }
    #checkout-options  .list-group .list-group-item input[type=radio]{
         display: none;
    }
   #checkout-options  .list-group .list-group-item  .radio label, #checkout-options  .list-group .list-group-item  .checkbox label { 
    padding-left: 5px;
   }
</style>

<!-- CHECKOUT OPTIONS -->
<div  id="checkout-options">
    <div class="list-group">
        <li href="#" class="list-group-item ">
            <span class="text-<?php echo $classtheme ; ?>"  style="font-size: 1.5em;">
                <i class="fa fa-certificate"  style="font-size: 1em;"></i> <?php echo $text_checkout; ?>
            </span>
        </li>
        <?php if ($guest_checkout) { ?>
        <li class="list-group-item  <?php echo ($account_view == 'guest') ? 'active' :'' ; ?>">
            <div class="radio">
                <label>
                    <input type="radio" name="account" value="guest" id="guest" <?php echo ($account_view == 'guest') ?  'checked="checked"' : '' ; ?> />
                           <strong><i class="fa fa-fire fa-2x"></i> <?php echo $text_guest; ?></strong>
                </label>
            </div>
        </li>
        <?php } ?>
        <li class="list-group-item <?php echo ($account_view == 'register') ? 'active' : '';?>">
            <div class="radio">
                <label>
                    <input type="radio" name="account" value="register" id="register" <?php echo ($account_view == 'register') ?  'checked="checked"' : '' ; ?> />
                           <strong><i class="fa fa-user-plus"></i> <?php echo $text_register; ?> </strong>
                </label>
            </div>
        </li>
        <li class="list-group-item <?php echo ($account_view == 'returning-customer') ? 'active' : '';?> ">
            <div class="radio">
                <label>
                    <input type="radio" name="account" value="returning-customer" id="returning-customer" <?php echo ($account_view == 'returning-customer') ?  'checked="checked"' : '' ; ?> >
                           <strong><i class="fa fa-sign-in"></i> <?php echo $text_i_am_returning_customer; ?> </strong>
                </label>
            </div>
        </li>
    </div>
</div>
<?php } ?>




<div class="panel panel-<?php echo $classtheme ; ?>" style="<?php echo (!empty($css['billing_panel_color'])) ? "border-color:{$css['billing_panel_color']}!important;" : '';?>">
     <div class="checkout-overlay checkout-overlay-dark hidden"></div>
    <div class="panel-heading" style="<?php echo  (!empty($css['billing_panel_color'])) ? "background-color:{$css['billing_panel_color']}!important;border-color:{$css['billing_panel_color']}!important;" : '';?>">
         <h3 class="panel-title">
            <i class="fa fa-paper-plane-o" style="font-size: 1.5em;"></i> 
            <?php if (!$logged) { ?>
            <strong id="checkout-option-title"> <?php echo $account_view_title; ?></strong>
            <?php } else { ?>
            <strong><?php echo $text_checkout_payment_address; ?></strong>
            <?php } ?>
            <span id="billing-wait" class="glyphicon glyphicon-refresh glyphicon-spin-animate hidden"></span>
        </h3>
    </div>
    <div class="panel-body">

        <?php if (!$logged) { ?>
        <!-- LOGIN FORM -->
        <div id="login-form" style="<?php echo ($account_view != 'returning-customer') ? 'display:none;' : ''; ?>">
            <?php echo $login; ?>
        </div>
        <?php } ?>



        <!-- BILLING TIPS -->
        <?php if (!empty($tips['payment_address_tip'][$config_language_id])) { ?>
        <i class="fa fa-lightbulb-o"></i> <small class="text-warning"><i><?php echo $tips['payment_address_tip'][$config_language_id]; ?></i></small>
        <?php } ?>

        <!-- BILLING ADDRESS -->
        <?php if (!$logged) { ?>
        <div id="payment-address">
            <div class="checkout-content-onepage">
                <?php if ($guest_checkout) { ?>
                <div id="guest-form-detail" style="overflow: visible!important; <?php echo ($account_view != 'guest') ? 'display:none;' : ''; ?>">
                    <?php echo $guest; ?>
                </div>
                <?php } ?>
                <div id="register-form-detail" style="overflow: visible!important; <?php echo ($account_view != 'register') ? 'display:none;' : ''; ?>">
                    <?php echo $register; ?>
                </div>
            </div>
        </div>
        <?php } else { ?>
        <div id="payment-address">
            <div class="checkout-content-onepage">
                <?php echo $paymentAddress; ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<!-- Shipping Address-->
<?php if ($shipping_required && ($logged || (!$logged && $guest_checkout))) { ?>
<div id="shipping-address-panel" class="panel panel-<?php echo $classtheme ; ?>" style="<?php echo (!empty($css['shipping_panel_color']))  ?  "border-color:{$css['shipping_panel_color']}!important;" : ''; ?> <?php echo (!empty($shipping_address_same) || (!$logged && $account_view != 'guest')) ? 'display:none;' : ''; ?>"> 
     <div class="checkout-overlay checkout-overlay-dark hidden"></div>
    <div class="panel-heading" style="<?php echo (!empty($css['shipping_panel_color'])) ? "background-color:{$css['shipping_panel_color']}!important;border-color:{$css['shipping_panel_color']}!important;" : ''; ?>">
         <h3 class="panel-title">
            <i class="fa fa-paper-plane-o" style="font-size: 1.5em;"></i> 
            <strong><?php echo $text_checkout_shipping_address; ?></strong>
            <span id="shipping-wait" class="glyphicon glyphicon-refresh glyphicon-spin-animate hidden"></span>
        </h3>
    </div>
    <div class="panel-body">
        <?php if (!empty($tips['shipping_address_tip'][$config_language_id])) { ?>
        <i class="fa fa-lightbulb-o"></i> <small class="text-warning"><i><?php echo $tips['shipping_address_tip'][$config_language_id]; ?></i></small>
        <?php } ?>
        <div id="shipping-address">
            <div class="checkout-content-onepage">
                <?php if (!$logged && $guest_checkout) { ?>
                <?php echo $guestShipping; ?>
                <?php } else if ($logged) { ?>
                <?php echo $shippingAddress; ?>
                <?php } ?>
            </div>
        </div>
    </div>

</div>
<?php } else { ?>
<?php // echo $text_no_shipping_required; ?>
<?php } ?>
<?php if (isset($account_view) && $account_view == 'returning-customer') { ?>
<script type="text/javascript"><!--
    $(function () {
        $('#login-form').show();
        $('#shipping-address-panel').hide();
        $('#register-form-detail').hide();
        $('#guest-form-detail').hide();
        //overlay
        $('#shipping-method-panel div.checkout-overlay').removeClass('hidden');
        $('#payment-method-panel div.checkout-overlay').removeClass('hidden');
        $('#voucher-coupon-panel div.checkout-overlay').removeClass('hidden');
        $('#confirm-footer-panel div.checkout-overlay').removeClass('hidden');
        $('#confirm-panel div.checkout-overlay').addClass('hidden');
        $('#confirm-footer-panel .checkout-overlay').removeClass('hidden');
    });
    //--></script>
<?php } ?>