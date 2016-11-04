<!--<h1>confirm order onepage</h1>-->
<?php if (!isset($redirect)) { ?>
<style type="text/css">
    #confirm-checkout tr td{
        vertical-align: middle;
    }
    #confirm-checkout table{
        background-color: #fff;
    }
  @media (min-width: 768px){
        #confirm-checkout .dl-horizontal dt { width: auto; }

        }
</style>
<div class="row">
    <div class="table-responsive">
    
</div>
</div>

  <?php if (!empty($comment)) { ?>
    <div>
        
            <span class="glyphicon glyphicon-comment"></span> <?php echo $text_comment; ?>
            <div class="form-group">
                <p class="text-info"><?php echo $comment; ?><p>
            </div>
        
    </div>
    <hr>
    <?php } ?>
    
    
    
    <?php if (!$shipping_required && !empty($no_shipping_required)) { ?>
        <div class="alert alert-warning" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign"></span> <span><?php echo $no_shipping_required; ?></span>
        </div>
    <?php } ?>
    
    
   
      <?php if ($shipping_required) { ?>
      <hr>
 <dl class="dl-horizontal">
  <dt><span class="glyphicon glyphicon-send"></span></span> <?php echo $text_checkout_shipping_address; ?>:</dt>
  <dd><?php echo $shipping_address; ?></dd>
</dl>
     <hr>   
 <dl class="dl-horizontal">
  <dt><span class="glyphicon glyphicon-send"></span> <?php echo $text_checkout_shipping_method; ?>:</dt>
  <dd><?php echo $shipping_method ?></dd>
</dl>
      <hr>
      
  <?php } ?>    
    
    
 <dl class="dl-horizontal">
  <dt><span class="glyphicon glyphicon-credit-card"></span> <?php echo $text_checkout_payment_address; ?>:</dt>
  <dd><?php echo $payment_address; ?></dd>
</dl>
    <hr>
 <dl class="dl-horizontal">
  <dt><span class="glyphicon glyphicon-credit-card"></span> <?php echo $text_checkout_payment_method; ?>:</dt>
  <dd><?php echo $payment_method; ?></dd>
</dl>

<div class="clearfix">
    <div class="payment">
        <?php echo $payment; ?>
    </div>
</div>




<div class="clearfix"> 
    <button id="btn-edit-order" class="btn btn-<?php
            if ($css['checkout_theme'] == 'standar') {
                echo 'primary';
            } else {
                echo $css['checkout_theme'];
            }
            ?>" style="<?php
            if (!empty($css['edit_order_btn_color'])) {
                echo "background-color:{$css['edit_order_btn_color']}!important; background-image:none;";
                }
                ?>">
                <i class="fa fa-pencil"></i>&nbsp;<span><?php echo $edit_order_button; ?></span>
            </button>
    </div>

    <script type="text/javascript"><!--
        $('#cart_order_title').html('<i class="fa fa-list" style="font-size: 1.5em;"></i>&nbsp;<strong><?php echo $text_order_detail; ?></strong>');
        $('#confirm-checkout').show();
        $('#billing-shipping-details').hide();
        $('#billing-shipping-methods').hide();
        $('#voucher-coupon').hide();
        $('body').animate({
            scrollTop: $("#checkout-container").parent().offset().top
        }, 500);


        $('#methods-summary-panel').removeClass('col-sm-8').addClass('col-sm-12');
        $('#btn-edit-order').click(function () {
            $('#cart_order_title').html('<i class="fa fa-shopping-cart" style="font-size: 1.5em;"></i>&nbsp;<strong><?php echo $text_cart; ?></strong>');
            $('#confirm-checkout').hide();
            $('#confirm').show();
            $('#methods-summary-panel').removeClass('col-sm-12').addClass('col-sm-8');
            $.when($('#billing-shipping-details').slideDown().promise(), $('#billing-shipping-methods').slideDown().promise(), $('#voucher-coupon').slideDown().promise()).done(function () {

                $('body').animate({
                    scrollTop: $("#checkout-container").parent().offset().top
                }, 500);
            });

        });
		<?php if ($mmos_checkout['enable_qtip'] == '1'){ ?>
        $('#confirm-checkout img.product-image').each(function () {
            var product_name = $(this).siblings('.product-name').val();
            var urlproduct = $(this).siblings('.product-image-popup').val();
            $(this).qtip({
                content: {
                    text: '<div>'
                            + '<div><h3 class="text-center">' + product_name + '</h3></div>'
                            + '<div style="text-align: center!important;"><img src="' + urlproduct + '" />' + '</div>'

                            + '</div>'
                },
                position: {
                    my: 'right center',
                    target: 'mouse',
                    adjust: {
                        screen: true
                    },
                    viewport: $(window) // Keep it on-screen at all times if possible
                },
                show: {
                    ready: true,
                },
                hide: {
                    when: 'mouseout', fixed: true
                },
                style: 'qtip-wiki'
            });
        });
		  <?php } ?>
        //--></script>
    <?php } else { ?>
    <script type="text/javascript"><!--

        var redirect = '<?php echo $redirect; ?>';
                <?php if ($mmos_checkout['debug']) { ?>
                var messages = [];
                <?php foreach ($error as $err) { ?>
                messages.push('<?php echo $err; ?>');
                <?php } ?>
                var message = messages.join("\r\n");
        console.log(messages);
        console.log("Redirect: " + redirect);
        if (confirm(message + "\r\nRedirect: " + redirect)) {
        location = redirect;
        }
        <?php } else { ?>
                location = redirect;
                <?php } ?>
                //--></script> 
    <?php } ?>