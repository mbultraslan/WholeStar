<h3><?php echo $heading_title; ?></h3>
<div class="row product-layout">
  <?php foreach ($products as $product) { ?>


    <div class="product-wrapper">
    <div class="product-thumb__primary">

      <a href="<?php echo $product['href']; ?>">

        <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" />
        <div class="category-product-info" style="position: absolute; margin-top: -60px; display: none;">Style : <?php echo $product['model']; ?><br>Size : <?php echo $product['ratioScale']; ?><br>Ratio : <?php echo $product['ratio']; ?></div>
      </a>

    </div>

    <script>
      $(function() {
        $('.product-wrapper').hover(function() {
          $(this).find($('.category-product-info')).show();
        }, function() {
          // on mouseout, reset the background colour
          $(this).find($('.category-product-info')).hide();
        });
      });
    </script>
    <div>

          <h4 class="product-name" style="margin-bottom: 0px; border: none;    width: 100%; text-align: center;">

            <a href="<?php echo $product['href']; ?>">

              <?php echo $product['name']; ?>

            </a>

      <div style="padding-top: 5px;">
        <?php

        foreach ($product['colours'] as $colour) {
          ?>

          <span style="border: 1px solid #000000; min-width:40px; min-height:40px; background-color: #<?php echo $colour['colour'];?>" data-toggle="tooltip" title="<?php echo $colour['name']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>

          <?php
        }
        ?>
      </div>
          </h4>
        <p><?php echo $product['description']; ?></p>
        <?php if ($product['rating']) { ?>
        <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($product['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
        <?php } ?>


      <div class="button-group">
        <?php if ($product['price']) { ?>
        <h4 class="price product-name" style="width: 100%">
          <?php

          if (!$product['special']) { ?>

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

          <?php } ?>

        </h4>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
