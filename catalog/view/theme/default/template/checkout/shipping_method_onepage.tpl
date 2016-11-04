<style type="text/css">
    #tbl_shipping_methods tr>td{border:none; }
    #tbl_shipping_methods {    margin-bottom: 0px !important;}
</style>
<?php if ($error_warning) { ?>
<div class=""><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($warning_non_address_shipping) { ?>
<div class=""><?php echo $warning_non_address_shipping; ?></div>
<?php } ?>
<?php if ($shipping_methods) { ?>
<?php
$is_matched_shipping_method = false;
$alt_shipping_method = null;
?>
<p class="text-info hidden-sm hidden-xs"><?php echo $text_shipping_method; ?></p>

<table class="table table-hover" id="tbl_shipping_methods">
    <?php foreach ($shipping_methods as $shipping_method) { ?>

    <?php if (!$shipping_method['error']) { ?>

    <?php foreach ($shipping_method['quote'] as $quote) { ?>
    <?php
    if (!$alt_shipping_method) {
    $alt_shipping_method = $quote;
    }
    ?>
    <tr class="">
        <td style="">
            <div class="radio">
                <label>
                    <?php if ($quote['code'] == $code) { ?>
                    <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" />
                    <?php } ?>
                    <?php echo $quote['title']; ?>
                </label>
            </div>

        </td>
        <td class="text-right"><label for="<?php echo $quote['code']; ?>"><?php echo $quote['text']; ?></label></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
        <td colspan="3"><div class="error"><?php echo $shipping_method['error']; ?></div></td>
    </tr>
    <?php } ?>
    <?php } ?>
</table>


<?php } ?>

