<?php $kuler = Kuler::getInstance(); ?>

<div class="product-layout product-list col-xs-12">

  <div class="row">

    <div class="col-md-3 col-sm-6 col-xs-4 left">

      <?php if ($product['thumb']) { ?>

        <div class="product-thumb">

          <div class="product-thumb__primary">

            <a href="<?php echo $product['href']; ?>">

              <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" />

            </a>

          </div>

        </div><!--/.produc-thumb-->

      <?php } else { ?>

        <div class="product-thumb product-thumb--no-image">

          <img src="image/no_image.jpg" alt="<?php echo $product['name']; ?>" />

        </div><!--/.product-thumb--no-image-->

      <?php } //end product thumb ?>

    </div>

    <div class="col-md-9 col-sm-6 col-xs-8 right">

      <?php if(isset($setting['name']) && $setting['name']) { ?>

        <h4 class="product-name">

          <a href="<?php echo $product['href']; ?>">

            <?php echo $product['name']; ?>

          </a>

        </h4>

      <?php } //end product name ?>

      <?php if(isset($setting['price']) && $setting['price']) { ?>

        <p class="product-price">

          <?php if (!$product['special']) { ?>

            <?php echo $product['price']; ?>

          <?php } else { ?>

            <span class="product-price--old"><?php echo $product['price']; ?></span>

            <!--<span><?php echo $kuler->calculateSalePercent($product['special'], $product['price']); ?>% off</span>-->

            <span class="product-price--new"><?php echo $product['special']; ?></span>

          <?php } ?>

        </p>

      <?php } //end product price ?>

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

      <?php } //end product rating ?>

      <?php if (isset($setting['description']) && $setting['description']) { ?>

        <div class="product-description hidden">

          <?php echo $product['description']; ?>

        </div>

      <?php } //end product description ?>

    </div>

  </div>

</div>