<?php

$kuler = Kuler::getInstance();

$setting['products_per_row'] = !empty($setting['products_per_row']) ? intval($setting['products_per_row']) : 4;



$lg_col = 4;

if (12 % $setting['products_per_row']) {

  if ($setting['products_per_row'] == 5) {

    $lg_col = 20;

  }

} else {

  $lg_col = 12 / $setting['products_per_row'];

}

?>

<div class="product-layout product-grid col-md-4 col-sm-6 col-xs-12 col-lg-<?php echo $lg_col; ?><?php if (isset($product['date_end']) && $product['date_end']) echo ' has-deal'; ?>">

  <?php $size = $kuler->getImageSizeByPath($product['thumb']); ?>

  <?php if ($product['thumb']) { ?>

    <div class="product-thumb">

      <div class="product-thumb__primary">

        <a href="<?php echo $product['href']; ?>">

          <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" width="<?php echo $size['width']; ?>" height="<?php echo $size['height']; ?>" />

          <div class="category-product-info" style="position: absolute; margin-top: 0px; ">Style : <?php echo $product['model']; ?><br>Size : <?php echo $product['ratioScale']; ?><br>Ratio : <?php echo $product['ratio']; ?></div>
        </a>

      </div>

      <script>
        $(function() {
          $('.product-thumb').hover(function() {
            $(this).find($('.category-product-info')).css('bottom', '0');
          }, function() {
            // on mouseout, reset the background colour
            $(this).find($('.category-product-info')).css('bottom', '');
          });
        });
      </script>

      <?php if ($images = $kuler->getProductImages($product['product_id'])) { ?>

        <?php if(!$kuler->mobile->isMobile() && $kuler->getSkinOption('enable_swap_image')){ ?>

          <div class="product-thumb__secondary hidden-xs hidden-sm hidden-md">

            <a href="<?php echo $product['href']; ?>">

              <img src="<?php echo $kuler->resizeImage($images[0], $size['width'], $size['height']); ?>" alt="<?php echo $product['name']; ?>" width="<?php echo $size['width']; ?>" height="<?php echo $size['height']; ?>" />

            </a>

          </div>

        <?php } ?>

      <?php } //end swap image ?>

      <?php if (Kuler::getInstance()->getSkinOption('show_quick_view')) { ?>

        <button class="product-detail-button product-detail-button--quick-view">

          <a href="<?php echo Kuler::getInstance()->getQuickViewUrl($product); ?>" data-toggle="tooltip" title="<?php echo $kuler->translate($kuler->getSkinOption('view_button_text')) ?>">

            <?php echo ($kuler->translate($kuler->getSkinOption('view_button_text'))) ? $kuler->translate($kuler->getSkinOption('view_button_text')) : '<i class="pe-7s-search"></i>';?>

          </a>

        </button>

      <?php } ?>

      <?php if ($product['special']) { ?>

        <div class="product-sale" style="    min-width: 50px;">

<!--          <span>---><?php //echo $kuler->calculateSalePercent($product['special'], $product['price']); ?><!--%</span>-->
          <?php if ($product['price']) { ?>
          <br/>  <span style="background-color: #942a25; padding: 5px;"><?php echo $kuler->calculateSalePercent($product['special'], $product['price']); ?>% Off</span>
          <?php } ?>

        </div><!--/.product-sale-->

      <?php } //end special ?>

      <?php if(isset($setting['deal_date']) && $setting['deal_date']) { ?>

        <?php if(isset($product['date_end'])) { ?>

          <?php

          $parts = array('0000', '00', '00');



          if ($product['date_end']) {

            $parts = explode('-', $product['date_end']);

          }

          ?>

          <div class="product-deal-countdown" data-is-deal="<?php echo $product['date_end'] ? 'true' : 'false' ?>" data-product-id="<?php echo $product['product_id'] ?>" data-date-end="<?php echo $product['date_end'] ?>" data-year="<?php echo $parts[0] ?>" data-month="<?php echo $parts[1] ?>" data-day="<?php echo $parts[2] ?>"></div>

        <?php }  ?>

      <?php } //end deal date ?>

    </div><!--/.produc-thumb-->

  <?php } else { ?>

    <div class="product-thumb product-thumb--no-image">

      <a href="<?php echo $product['href']; ?>">

        <img src="image/no_image.jpg" alt="<?php echo $product['name']; ?>" />

      </a>

    </div><!--/.product-thumb--no-image-->

  <?php } //end if product thumb ?>

  <?php if(isset($setting['name']) && $setting['name']) { ?>

    <h4 class="product-name">

      <a href="<?php echo $product['href']; ?>">

        <?php echo $product['name']; ?>

      </a>

    </h4>

  <?php } //end product name ?>

  <?php if(isset($setting['rating']) && $setting['rating']) { ?>

    <div class="product-rating">

      <?php for ($i = 1; $i <= 5; $i++) { ?>

        <?php if ($setting['rating'] < $i) { ?>

          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>

        <?php } else { ?>

          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>

        <?php } ?>

      <?php } ?>

    </div>

  <?php } ?>

  <div style="margin-bottom: 5px;">
    <?php

    foreach ($product['colours'] as $colour) {
      ?>

      <span style="border: 1px solid #000000; min-width:40px; min-height:40px; background-color: #<?php echo $colour['colour'];?>" data-toggle="tooltip" title="<?php echo $colour['name']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>

      <?php
    }
    ?>
  </div>

  <?php if(isset($setting['price']) && $setting['price']) { ?>


    <?php if (!$product['special']) { ?>

      <table class="table" style="margin-bottom: 0;">
        <?php if ($product['price']) { ?>
        <tr>
          <td  style="text-align: center;">
            PACK<br>
            <span class="price-new price-tag"><?php echo $product['price']; ?></span>
          </td>
          <td  style="text-align: center;">
            PIECE <br>
            <span class="price-new price-tag"><?php echo $product['eachPrice']; ?></span>
          </td>
        </tr>
        <tr>
          <?php } ?>

          <td colspan="2"  style="text-align: center;">
            1 PACK = <?php echo $product['packQty']; ?> UNITS
          </td>
        </tr>
      </table>

    <?php } else { ?>


      <table class="table" style="margin-bottom: 0;">
        <?php if ($product['price']) { ?>
        <tr>
          <td  style="text-align: center;">
            PACK<br>
            <span class="product-price--old"><?php echo $product['price']; ?></span>
            <span class="price-new price-tag"><?php echo $product['special']; ?></span>
          </td>
          <td  style="text-align: center;">
            PIECE <br>
            <span class="product-price--old"><?php echo $product['eachPrice']; ?></span>
            <span class="price-new price-tag"><?php echo $product['specialEach']; ?></span>
          </td>
        </tr>
        <tr>
          <?php } ?>

          <td colspan="2"  style="text-align: center;">
            1 PACK = <?php echo $product['packQty']; ?> UNITS
          </td>
        </tr>
      </table>

      <!--                      <span class="product-price--old">--><?php //echo $product['price']; ?><!--</span>-->
      <!---->
      <!--            		  <!--<span>--><?php //echo $kuler->calculateSalePercent($product['special'], $product['price']); ?><!--% off</span>-->
      <!---->
      <!--            		  <span class="product-price--new">--><?php //echo $product['special']; ?><!--</span>-->

    <?php } ?>


  <?php } //end product price ?>

  <?php if(isset($setting['rating']) && $setting['rating']) { ?>

  <?php } //end product rating ?>

  <?php if (isset($setting['description']) && $setting['description']) { ?>

    <div class="product-description hidden">

      <?php echo $product['description']; ?>

    </div>

  <?php } //end product description ?>

  <?php
  /*
  if((isset($setting['add']) && $setting['add']) ||(isset($setting['wishlist']) && $setting['wishlist']) || (isset($setting['compare']) && $setting['compare'])) { ?>

    <div class="product-detail button-group">

      <div class="product-detail__group-buttons">

        <?php if(isset($setting['add']) && $setting['add']) { ?>

          <button class="product-detail-button product-detail-button--cart" type="button" data-toggle="tooltip" title="<?php echo $button_cart; ?>" onclick="cart.add('<?php echo $product['product_id']; ?>');">

            <span><?php echo $button_cart; ?></span>

            <i class="pe-7s-cart"></i>

          </button>

        <?php } ?>

        <?php if(isset($setting['wishlist']) && $setting['wishlist']) { ?>

          <button class="product-detail-button product-detail-button--wishlist" type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');">

            <i class="pe-7s-like"></i>

          </button>

        <?php } ?>

        <?php if(isset($setting['compare']) && $setting['compare']) { ?>

          <button class="product-detail-button product-detail-button--compare" type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');">

            <i class="pe-7s-repeat"></i>

          </button>

        <?php } ?>

      </div>

    </div>

  <?php }
   */
  //end product buttons ?>

</div>