<div id="cart" class="btn-group btn-block mini-cart">
  <button style="background:none;" type="button" data-toggle="dropdown" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-inverse btn-block btn-lg dropdown-toggle">
    <i class="cart__icon pe-7s-cart"></i>
    <span class="cart-product-total-number" id="cart-total" style="color:white; font-weight:bold;"><?php echo Kuler::getInstance()->cart->countProducts(); ?></span>
  </button>
  <?php if ($products || $vouchers) { ?>
    <ul class="dropdown-menu pull-right">
      <li>
        <table class="table table-striped">
          <?php foreach ($products as $product) { ?>
          <tr>
            <td class="text-center"><?php if ($product['thumb']) { ?>
              <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail"  style="width:70px;"/></a>
              <?php } ?></td>
            <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
              <?php if ($product['option']) { ?>
              <?php foreach ($product['option'] as $option) { ?>
              <br />



                  <!--                    <input type="text" name="quantity[-->
<!--              - <small>--><?php //// echo  $option['name']; ?><!-- --><?php //// echo $option['value']; ?><!--</small>-->
              <?php } ?>
              <?php } ?>
              <?php if ($product['recurring']) { ?>
              <br />
              - <small><?php echo $text_recurring; ?> <?php echo $product['recurring']; ?></small>
              <?php } ?></td>
            <td class="text-right">x   <?php echo $product['packQty'] + $product['singleQty']; ?> </td>
            <td class="text-right"><?php echo $product['price']; ?></td>
            <td class="text-center"><button type="button" onclick="cart.remove('<?php echo $product['key']; ?>');" title="<?php echo $button_remove; ?>" class="btn-xs"><i class="fa fa-times"></i></button></td>
          </tr>
          <?php } ?>
          <?php foreach ($vouchers as $voucher) { ?>
          <tr>
            <td class="text-center"></td>
            <td class="text-left"><?php echo $voucher['description']; ?></td>
            <td class="text-right">x&nbsp;1</td>
            <td class="text-right"><?php echo $voucher['amount']; ?></td>
            <td class="text-center text-danger"><button type="button" onclick="voucher.remove('<?php echo $voucher['key']; ?>');" title="<?php echo $button_remove; ?>"class="btn-xs"><i class="fa fa-times"></i></button></td>
          </tr>
          <?php } ?>
        </table>
      </li>
      <li>
        <div>
          <table class="table table-bordered">
            <?php foreach ($totals as $total) { ?>
            <tr>
              <td class="text-right"><strong><?php echo $total['title']; ?></strong></td>
              <td class="text-right"><?php echo $total['text']; ?></td>
            </tr>
            <?php } ?>
          </table>
          <p>
            <a class="mini-cart__view-cart" href="<?php echo $cart; ?>" style="color:#fff !important;">
              <?php echo $text_cart; ?> <i class="fa fa-shopping-cart"></i>
            </a>
            <a class="mini-cart__checkout" href="<?php echo $checkout; ?>"  style="color:#fff !important;">
              <?php echo $text_checkout; ?> <i class="fa fa-share"></i>
            </a>
          </p>
        </div>
      </li>
    </ul>
  <?php } else { ?>
  <ul class="dropdown-menu pull-right mini-cart__no-product">
    <li>
      <p class="text-center"><?php echo $text_empty; ?></p>
    </li>
  </ul>
  <?php } ?>
</div>
