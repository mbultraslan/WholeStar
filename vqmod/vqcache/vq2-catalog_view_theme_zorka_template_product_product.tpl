<?php

$kuler = Kuler::getInstance();

$theme = $kuler->getTheme();

$kuler->addScript(array(

  "catalog/view/theme/$theme/js/lib/jquery.elevatezoom.js",

  "catalog/view/theme/$theme/js/product.js"

), true);

$kuler->language->load('kuler/zorka');

global $config;

?>

<?php echo $header; ?>

  <div class="container">

    <ul class="breadcrumb hidden-xs hidden-sm">

      <?php foreach ($breadcrumbs as $breadcrumb) { ?>

        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>

      <?php } ?>

    </ul>

    <div class="row"><?php echo $column_left; ?>

      <?php if ($column_left && $column_right) { ?>

        <?php $class = 'col-lg-6 col-sm-6'; ?>

      <?php } elseif ($column_left || $column_right) { ?>

        <?php $class = 'col-lg-10 col-sm-10'; ?>

      <?php } else { ?>

        <?php $class = 'col-lg-12 col-sm-12'; ?>

      <?php } ?>

      <div id="content" class="<?php echo $class; ?>">
				
				<?php echo $content_top; ?>
				
				<?php if( ! empty( $breadcrumbs ) && is_array( $breadcrumbs ) ) { ?>
					<ul style="display:none;">
						<?php foreach( $breadcrumbs as $breadcrumb ) { ?>
							<?php if( NULL != ( $smk_title = strip_tags( $breadcrumb['text'] ) ) ) { ?>
								<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
									<a href="<?php echo $breadcrumb['href']; ?>" itemprop="url"><span itemprop="title"><?php echo strip_tags( $breadcrumb['text'] ); ?></span></a>
								</li>
							<?php } ?>
						<?php } ?>
					</ul>
				<?php } ?>
			
			
				
				<?php if( ! empty( $smp_is_product ) ) { ?>
					<span itemscope itemtype="http://schema.org/Product">
						<meta itemprop="name" content="<?php echo htmlspecialchars( str_replace( '&amp;', '&', $heading_title ), ENT_QUOTES ); ?>">

						<?php if( ! empty( $breadcrumb ) ) { ?>
							<meta itemprop="url" content="<?php echo htmlspecialchars( $breadcrumb['href'], ENT_QUOTES ); ?>">
						<?php } ?>

						<?php if( ! empty( $model ) ) { ?>
							<meta itemprop="model" content="<?php echo htmlspecialchars( $model, ENT_QUOTES ); ?>">
						<?php } ?>

						<?php if( ! empty( $manufacturer ) ) { ?>
							<meta itemprop="manufacturer" content="<?php echo htmlspecialchars( $manufacturer, ENT_QUOTES ); ?>">
						<?php } ?>

						<span itemscope itemprop="offers" itemtype="http://schema.org/Offer">
							<meta itemprop="price" content="<?php if( ! empty( $special ) ) { echo preg_replace( '/[^0-9.]/', '', $special ); } else { echo preg_replace( '/[^0-9.]/', '', $price ); } ?>">
							<meta itemprop="priceCurrency" content="<?php echo $smp_currency; ?>">
							<link itemprop="availability" href="http://schema.org/<?php if( $smp_in_stock ) { ?>InStock<?php } else { ?>OutOfStock<?php } ?>">
						</span>

						<?php if( $review_status && $smp_reviews ) { ?>
							<span itemscope itemprop="aggregateRating" itemtype="http://schema.org/AggregateRating">
								<meta itemprop="reviewCount" content="<?php echo $smp_reviews; ?>">
								<meta itemprop="ratingValue" content="<?php echo $rating; ?>">
								<meta itemprop="bestRating" content="5">
								<meta itemprop="worstRating" content="1">
							</span>
						<?php } ?>

						<?php if( $thumb ) { ?>
							<meta itemprop="image" content="<?php echo $thumb; ?>">
						<?php } ?>

						<?php foreach( $images as $image ) { ?>
							<meta itemprop="image" content="<?php echo $image['popup']; ?>">
						<?php } ?>
					</span>
				<?php } ?>
			
			

        <div class="row">

          <?php if ($column_left && $column_right) { ?>

            <?php $class = 'col-lg-6 col-sm-6'; ?>

          <?php } elseif ($column_left || $column_right) { ?>

            <?php $class = 'col-lg-5 col-sm-5'; ?>

          <?php } else { ?>

            <?php $class = 'col-lg-5 col-sm-6'; ?>

          <?php } ?>

 <div class="<?php echo $class; ?>">

            <?php if ($thumb || $images) { ?>

            <div class="productimagescontainer">

 <?php if ($images) { ?>

				<div class="ProdSideThumbs " id="image-additional">

			<?php foreach ($images as $image) { ?>

                      <div>

                        <a title="<?php echo empty( $smp_title_images ) ? $heading_title : $smp_title_images; ?>" class="product-image-link" href="<?php echo $image['popup']; ?>" data-image="<?php echo $image['popup']; ?>" data-zoom-image="<?php echo $image['popup']; ?>">

                          <img src="<?php echo $image['thumb']; ?>" title="<?php echo empty( $smp_title_images ) ? $heading_title : $smp_title_images; ?>" alt="<?php echo empty( $smp_alt_images ) ? $heading_title : $smp_alt_images; ?>" />

                        </a>

                      </div>

                    <?php } ?>



				</div>

			 <?php } ?>

				<div class="ProductThumbImage">

				 <ul class="thumbnails">

<?php if ($thumb) { ?>

                  <div class="thumbnails__big-image" style="max-width: <?php echo $config->get('config_image_thumb_width'); ?>px; max-height: <?php echo $config->get('config_image_thumb_height'); ?>px;">

                    <a href="<?php echo $popup; ?>" class="product-image-link">

                      <img id="main-image" src="<?php echo $thumb; ?>" data-zoom-image="<?php echo $popup; ?>"/>

                    </a>

                  </div>

                <?php } ?>

</ul>



				</div>



		</div>





            <?php } ?>

          </div>



          <?php if ($column_left && $column_right) { ?>

            <?php $class = 'col-lg-6 col-sm-6'; ?>

          <?php } elseif ($column_left || $column_right) { ?>

            <?php $class = 'col-lg-7 col-sm-7'; ?>

          <?php } else { ?>

            <?php $class = 'col-lg-7 col-sm-6'; ?>

          <?php } ?>

          <div class="<?php echo $class; ?> product-info">

            <h1><?php echo $heading_title; ?></h1>

            <?php if ($review_status) { ?>

              <div class="product-rating">

                <p>

                  <?php for ($i = 1; $i <= 5; $i++) { ?>

                    <?php if ($rating < $i) { ?>

                      <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>

                    <?php } else { ?>

                      <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>

                    <?php } ?>

                  <?php } ?>

                  <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $reviews ."  " . "/"; ?></a>

                  <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $text_write; ?></a>

                </p>

              </div>

            <?php } ?>

            <?php if ($special) { ?>

              <div class="product-sale">-<?php echo $kuler->calculateSalePercent($special, $price); ?>%</div>

            <?php } ?>

            <?php if ($price) { ?>

              <ul class="list-unstyled product-price">

                <?php if (!$special) { ?>

                  <li>

                    <span class="price-new price-tag"><?php echo $price; ?></span>

                  </li>

                <?php } else { ?>

                  <li>



                  <span class="product-price--old"><?php echo $price; ?></span>

                  <!--<span><?php echo $kuler->calculateSalePercent($special, $price); ?>% off</span>-->

                  <span class="price-new price-tag"><?php echo $special; ?></span>

                  </li>

                <?php } ?>

                <?php if ($points) { ?>

                  <li><?php echo $text_points; ?> <?php echo $points; ?></li>

                <?php } ?>

                <?php if ($discounts) { ?>

                  <li>

                    <hr>

                  </li>

                  <?php foreach ($discounts as $discount) { ?>

                    <li><?php echo $discount['quantity']; ?><?php echo $text_discount; ?><?php echo $discount['price']; ?></li>

                  <?php } ?>

                <?php } ?>

              </ul>



            <?php } ?>

            <!--<h3><?php echo $kuler->language->get('text_product_details'); ?></h3>-->

            <?php if (count(@$samecolortype) > 0) { ?>

              <label class="control-label">Colour options</label>

              <p style="width:100%; float:left; margin-bottom:10px;">

               <?php foreach($samecolortype as $samestyleproduct){ ?>

              	<span class="samestyleproduct">

                <a href="<?php echo $samestyleproduct['href']; ?>" title="<?php echo $samestyleproduct['name']; ?>" style="background-color:#<?php echo @$samestyleproduct['jan']; ?>;"/>

                &nbsp;

                </a></span>

              <?php }

              echo '</p><br /><br />';

               } ?>

            <ul class="list-unstyled product-options">

              <?php if ($manufacturer) { ?>

                <li>

                  <?php if ($kuler->getSkinOption('show_brand_logo')) { ?>

                    <a href="<?php echo $manufacturers; ?>">

                      <img src="<?php echo $kuler->getManufacturerImage($product_id); ?>" alt="<?php echo $manufacturer; ?>" />

                    </a>

                  <?php } else { ?>

                    <?php echo $text_manufacturer; ?>

                    <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a>

                  <?php } ?>

                </li>

              <?php } ?>

              <li><?php echo $text_model; ?> <?php echo $model; ?></li>

              <?php if ($reward) { ?>

                <li><?php echo $text_reward; ?> <?php echo $reward; ?></li>

              <?php } ?>

              <li><?php echo $text_stock; ?> <?php echo $stock; ?></li>

            </ul>

            <div id="product">



              <?php if ($options) { ?>



                <!--<h3><?php echo $text_option; ?></h3>-->

                <?php foreach ($options as $option) { ?>

                  <?php if ($option['type'] == 'select') { ?>

                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">

                      <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label><br/>

                      <select name="option[<?php echo $option['product_option_id']; ?>]" id="input-option<?php echo $option['product_option_id']; ?>" class="dropdownbox form-control">

                        <option value=""><?php echo $text_select; ?></option>

                        <?php foreach ($option['product_option_value'] as $option_value) { ?>

                          <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>

                            <?php if ($option_value['price']) { ?>

                              (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)

                            <?php } ?>

                          </option>

                        <?php } ?>

                      </select>

                    </div>

                  <?php } ?>

                  <?php if ($option['type'] == 'radio') { ?>

                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">

                      <label class="control-label"><?php // echo $option['name']; ?>Select a Size</label>

                      <div id="input-option<?php echo $option['product_option_id']; ?>">

                        <?php foreach ($option['product_option_value'] as $option_value) { ?>







                            <label class="<?php if ($option_value['quantity'] <= 0) {

							echo 'option_box_disable';}

							else{

							echo 'option_box_enable size';

							}

							?>

							">

                              <input  <?php if (@$option_value['quantity'] <= 0) echo 'disabled'; ?> style="display:none;" type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />

                              <?php echo $option_value['name']; ?>

                              <?php if ($option_value['price']) { ?>

                                (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)

                              <?php } ?>

                            </label>



                        <?php } ?>

                      </div>

                    </div>

                  <?php } ?>



                  <?php if ($option['type'] == 'checkbox') { ?>

                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">

                      <label class="control-label"><?php echo $option['name']; ?></label>

                      <div id="input-option<?php echo $option['product_option_id']; ?>">

                        <?php foreach ($option['product_option_value'] as $option_value) { ?>

                          <div class="checkbox">

                            <label>

                              <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" />

                              <?php echo $option_value['name']; ?>

                              <?php if ($option_value['price']) { ?>

                                (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)

                              <?php } ?>

                            </label>

                          </div>

                        <?php } ?>

                      </div>

                    </div>

                  <?php } ?>

                  <?php if ($option['type'] == 'image') { ?>

                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">

                      <label class="control-label"><?php echo $option['name']; ?></label>

                      <div id="input-option<?php echo $option['product_option_id']; ?>">

                        <?php foreach ($option['product_option_value'] as $option_value) { ?>

                          <div class="radio">

                            <label>

                              <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />

                              <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /> <?php echo $option_value['name']; ?>

                              <?php if ($option_value['price']) { ?>

                                (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)

                              <?php } ?>

                            </label>

                          </div>

                        <?php } ?>

                      </div>

                    </div>

                  <?php } ?>

                  <?php if ($option['type'] == 'text') { ?>

                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">

                      <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>

                      <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />

                    </div>

                  <?php } ?>

                  <?php if ($option['type'] == 'textarea') { ?>

                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">

                      <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>

                      <textarea name="option[<?php echo $option['product_option_id']; ?>]" rows="5" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control"><?php echo $option['value']; ?></textarea>

                    </div>

                  <?php } ?>

                  <?php if ($option['type'] == 'file') { ?>

                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">

                      <label class="control-label"><?php echo $option['name']; ?></label>

                      <button type="button" id="button-upload<?php echo $option['product_option_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>

                      <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" id="input-option<?php echo $option['product_option_id']; ?>" />

                    </div>

                  <?php } ?>

                  <?php if ($option['type'] == 'date') { ?>

                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">

                      <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>

                      <div class="input-group date">

                        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-format="YYYY-MM-DD" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />

                <span class="input-group-btn">

                <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>

                </span></div>

                    </div>

                  <?php } ?>

                  <?php if ($option['type'] == 'datetime') { ?>

                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">

                      <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>

                      <div class="input-group datetime">

                        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-format="YYYY-MM-DD HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />

                <span class="input-group-btn">

                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>

                </span></div>

                    </div>

                  <?php } ?>

                  <?php if ($option['type'] == 'time') { ?>

                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">

                      <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>

                      <div class="input-group time">

                        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-format="HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />

                <span class="input-group-btn">

                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>

                </span></div>

                    </div>

                  <?php } ?>

                <?php } ?>

              <?php } ?>





              <style>

			  span.samestyleproduct {

					  width: 32px;

					  height: 32px;

					  float: left;

					  margin: 5px;

					  border: 1px solid #FFF;

					  border-radius:50%;

					  overflow:hidden;

				}

				span.samestyleproduct:first-child {

					margin-left:0;

				}

				span.samestyleproduct a {

				  width: 30px;

				  float: left;

				  height: 30px;

				  overflow: hidden;

				  background-position:center;

				}

			  </style>

              <?php if ($recurrings) { ?>

                <hr>

                <h3><?php echo $text_payment_recurring ?></h3>

                <div class="form-group required">

                  <select name="recurring_id" class="form-control">

                    <option value=""><?php echo $text_select; ?></option>

                    <?php foreach ($recurrings as $recurring) { ?>

                      <option value="<?php echo $recurring['recurring_id'] ?>"><?php echo $recurring['name'] ?></option>

                    <?php } ?>

                  </select>

                  <div class="help-block" id="recurring-description"></div>

                </div>

              <?php } ?>

              <?php if ($kuler->getSkinOption('show_custom_block')) { ?>

                <div class="custom-block"><?php echo $kuler->translate($kuler->getSkinOption('custom_block_content')); ?></div>

              <?php } ?>

              <!--<div class="quantity-form form-group">

                <?php if (!$kuler->getSkinOption('show_number_quantity')) { ?>

                  <button type="button" id="qty-dec" class="quantity__button" style="color:black;">-</button>

                  <input type="text" name="quantity" size="2" value="<?php echo $minimum; ?>" />

                  <button type="button" id="qty-inc" class="quantity__button" style="color:black;">+</button>

                <?php } else { ?>

                  <button type="button" id="qty-dec" class="quantity__button">-</button>

                  <input type="text" name="quantity" size="2" class="dynamic-number" value="<?php echo $minimum; ?>" data-min="<?php echo $minimum; ?>"/>

                  <button type="button" id="qty-inc" class="quantity__button">+</button>

                <?php } ?>

                <input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />

              </div>-->

              	<br/><br/><label>Qty</label><br/>

              	 <input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />



                                           <select  name="quantity" id="qty" class="dropdownbox">



	<option selected="selected" value="1">1</option>

	<option value="2">2</option>

	<option value="3">3</option>

	<option value="4">4</option>

	<option value="5">5</option>



</select>









                                </div>

              <div class="product-detail button-group">

                <div class="product-detail__group-buttons">

                  <a id="button-cart" class=" add-btn  col-sm-12 col-xs-12 col-md-4 col-lg-3 col-xlg-3"  title="<?php echo $button_cart; ?>" data-loading-text="<?php echo $text_loading; ?>">

                    <!--<i class="fa fa-shopping-cart"></i>-->

                    <span><?php echo $button_cart; ?></span>





<a class="share-icon"  title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product_id; ?>');">

                    <i class="fa fa-heart"   ></i>

                  </a>



                      <?php if ($kuler->getSkinOption('default_sharing')) { ?>





                           <a class="share-icon" href="http://pinterest.com/pin/create/bookmarklet/?media=[MEDIA]&amp;url=[URL]&amp;is_video=false&amp;description=[TITLE]"><i class="fa fa-pinterest" style="color:#C8C8C8;"></i></a>



				<a class="share-icon"  href="http://twitter.com/share"><i class="fa fa-twitter" style="color:#C8C8C8;"></i></a><script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script>



            	<a class="share-icon"  href="http://www.facebook.com/sharer/sharer.php?u=[URL]&amp;title=[TITLE]"><i class="fa fa-facebook" style="color:#C8C8C8;"></i></a>

                <!--div style="width: 16px;

font-weight: 300;">| </div -->



              <?php } ?>











                  <!--<button class="product-detail-button product-detail-button--compare" type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product_id; ?>');">

                    <i class="fa fa-bar-chart-o"></i>

                  </button>-->

                </div>

              </div>



             <br/>

              <h3>PRODUCT DESCRIPTION</h3><hr style="margin:10px 0; width:80%;"/>

              <div><?php echo $description; ?></div>







             <div id="accordion-container" style="margin:10px 0; width:80%;" >



					<h3 class="accordion-header"><?php echo $kuler->translate($kuler->getSkinOption('custom_tab_1_title')); ?></h3>

					<div class="accordion-content" style="display: none;">

                   	<?php echo $kuler->translate($kuler->getSkinOption('custom_tab_1_content')); ?>

                    </div>



                <h3 class="accordion-header"><?php echo $kuler->translate($kuler->getSkinOption('custom_tab_2_title')); ?></h3>

					<div class="accordion-content" style="display: none;">

					<?php echo $kuler->translate($kuler->getSkinOption('custom_tab_2_content')); ?>

                       </div>





</div>











                 <?php if ($tags) { ?>

                <p class="tag"><span class="tag__title"><?php echo $text_tags; ?></span>

                  <?php for ($i = 0; $i < count($tags); $i++) { ?>

                    <?php if ($i < (count($tags) - 1)) { ?>

                      <a class="tag__name" href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,

                    <?php } else { ?>

                      <a class="tag__name" href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>

                    <?php } ?>

                  <?php } ?>

                  .

                </p>

              <?php } ?>



              <?php if ($minimum > 1) { ?>

                <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_minimum; ?></div>

              <?php } ?>

            </div>

          </div>

         <!-- <div class="col-lg-12">

            <div class="product-tabs">

              <ul class="nav nav-tabs">

                <li class="active"><a href="#tab-description" data-toggle="tab"><?php echo $tab_description; ?></a></li>

                <?php if ($attribute_groups) { ?>

                  <li><a href="#tab-specification" data-toggle="tab"><?php echo $tab_attribute; ?></a></li>

                <?php } ?>

                <?php if ($review_status) { ?>

                  <li><a href="#tab-review" data-toggle="tab"><?php echo $tab_review; ?></a></li>

                <?php } ?>

                <?php if ($kuler->getSkinOption('show_custom_tab_1')) { ?>

                  <li><a data-toggle="tab" href="#tab-custom-tab-1"><?php echo $kuler->translate($kuler->getSkinOption('custom_tab_1_title')); ?></a></li>

                <?php } ?>

                <?php if ($kuler->getSkinOption('show_custom_tab_2')) { ?>

                  <li><a data-toggle="tab" href="#tab-custom-tab-2"><?php echo $kuler->translate($kuler->getSkinOption('custom_tab_2_title')); ?></a></li>

                <?php } ?>

              </ul>

              <div class="tab-content">

                <div class="tab-pane active" id="tab-description"><?php echo $description; ?></div>

                <?php if ($attribute_groups) { ?>

                  <div class="tab-pane" id="tab-specification">

                    <table class="table table-bordered">

                      <?php foreach ($attribute_groups as $attribute_group) { ?>

                        <thead>

                        <tr>

                          <td colspan="2"><strong><?php echo $attribute_group['name']; ?></strong></td>

                        </tr>

                        </thead>

                        <tbody>

                        <?php foreach ($attribute_group['attribute'] as $attribute) { ?>

                          <tr>

                            <td><?php echo $attribute['name']; ?></td>

                            <td><?php echo $attribute['text']; ?></td>

                          </tr>

                        <?php } ?>

                        </tbody>

                      <?php } ?>

                    </table>

                  </div>

                <?php } ?>

                <?php if ($review_status) { ?>

                  <div class="tab-pane" id="tab-review">

                    <form class="form-horizontal">

                      <div id="review"></div>

                      <h2><?php echo $text_write; ?></h2>

                      <div class="form-group required">

                        <div class="col-lg-12 col-sm-12">

                          <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>

                          <input type="text" name="name" value="" id="input-name" class="form-control" />

                        </div>

                      </div>

                      <div class="form-group required">

                        <div class="col-lg-12 col-sm-12">

                          <label class="control-label" for="input-review"><?php echo $entry_review; ?></label>

                          <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>

                          <div class="help-block"><?php echo $text_note; ?></div>

                        </div>

                      </div>

                      <div class="form-group required">

                        <div class="col-lg-12 col-sm-12">

                          <label class="control-label"><?php echo $entry_rating; ?></label>

                          &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;

                          <input type="radio" name="rating" value="1" />

                          &nbsp;

                          <input type="radio" name="rating" value="2" />

                          &nbsp;

                          <input type="radio" name="rating" value="3" />

                          &nbsp;

                          <input type="radio" name="rating" value="4" />

                          &nbsp;

                          <input type="radio" name="rating" value="5" />

                          &nbsp;<?php echo $entry_good; ?></div>

                      </div>

                      <div class="form-group required">

                        <div class="col-lg-12 col-sm-12">

                          <label class="control-label" for="input-captcha"><?php echo $entry_captcha; ?></label>

                          <input type="text" name="captcha" value="" id="input-captcha" class="form-control" />

                        </div>

                      </div>

                      <div class="form-group">

                        <div class="col-lg-12 col-sm-12"> <img src="index.php?route=tool/captcha" alt="" id="captcha" /> </div>

                      </div>

                      <div class="buttons">

                        <div class="pull-right">

                          <button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_continue; ?></button>

                        </div>

                      </div>

                    </form>

                  </div>

                <?php } ?>

                <?php if ($kuler->getSkinOption('show_custom_tab_1')) { ?>

                  <div id="tab-custom-tab-1" class="tab-pane">

                    <?php echo $kuler->translate($kuler->getSkinOption('custom_tab_1_content')); ?>

                  </div>

                <?php } ?>

                <?php if ($kuler->getSkinOption('show_custom_tab_2')) { ?>

                  <div id="tab-custom-tab-2" class="tab-pane">

                    <?php echo $kuler->translate($kuler->getSkinOption('custom_tab_2_content')); ?>

                  </div>

                <?php } ?>

              </div>

            </div>

          </div>-->

        </div>



         <?php echo $column_right; ?></div>

         <hr/>









        <?php if ($products && $kuler->getSkinOption('show_related_products')) { ?>

          <div class="product-related">

            <div class="box-heading">

              <span><?php echo $kuler->language->get('text_related_products'); ?></span>

            </div>

            <div id="product-related" class="row owl-carousel">

              <?php foreach ($products as $product) { ?>

                <div class="product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12 <?php if (isset($product['date_end']) && $product['date_end']) echo ' has-deal'; ?>">

                  <?php if ($product['thumb']) { ?>

					<div class="product-thumb product-thumb--no-image">

                      <a href="<?php echo $product['href']; ?>">

                         <img src="<?php echo $product['thumb']; ?>"/>

                      </a>

                    </div><!--/.product-thumb--no-image-->



                  <?php } else { ?>

                    <div class="product-thumb product-thumb--no-image">

                      <a href="<?php echo $product['href']; ?>">

                        <img src="image/no_image.jpg" alt="<?php echo empty( $product['smp_alt_images'] ) ? $product['name'] : $product['smp_alt_images']; ?>" />

                      </a>

                    </div><!--/.product-thumb--no-image-->

                  <?php } //end if product thumb ?>

                  <h4 class="product-name">

                    <a href="<?php echo $product['href']; ?>">

                      <?php echo $product['name']; ?>

                    </a>

                  </h4>

                  <p class="product-price">

                    <?php if (!$product['special']) { ?>

                      <?php echo $product['price']; ?>

                    <?php } else { ?>

                       <span class="product-price--old"><?php echo $product['price']; ?></span>

           			  <!--<span><?php echo $kuler->calculateSalePercent($product['special'], $product['price']); ?>% off</span>-->

            		  <span class="product-price--new"><?php echo $product['special']; ?></span>

                    <?php } ?>

                  </p>

                  <div class="product-description hidden">

                    <?php echo $product['description']; ?>

                  </div>

                  <div class="product-detail button-group">

                    <div class="product-detail__group-buttons">

                      <button class="product-detail-button product-detail-button--cart hidden-xs hidden-sm hidden-md" type="button" data-toggle="tooltip" title="<?php echo $button_cart; ?>" onclick="cart.add('<?php echo $product['product_id']; ?>');">

                        <span><?php echo $button_cart; ?></span>

                        <i class="pe-7s-cart"></i>

                      </button>

                      <button class="product-detail-button product-detail-button--wishlist hidden-lg hidden-xlg " type="button" data-toggle="tooltip" title="<?php echo $button_cart; ?>" onclick="cart.add('<?php echo $product['product_id']; ?>');">



                        <i class="pe-7s-cart"></i>

                      </button>

                      <button class="product-detail-button product-detail-button--wishlist hidden-xs hidden-sm " type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');">

                        <i class="pe-7s-like"></i>

                      </button>

                      <!--<button class="product-detail-button product-detail-button--compare" type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');">

                        <i class="pe-7s-repeat"></i>

                      </button>-->

                    </div>

                  </div>

                </div>

              <?php } ?>

            </div>

          </div>

        <?php } ?>

        <?php echo $content_bottom; ?></div>



  </div>

  <script type="text/javascript"><!--

    $('select[name=\'recurring_id\'], input[name="quantity"]').change(function(){

      $.ajax({

        url: 'index.php?route=product/product/getRecurringDescription',

        type: 'post',

        data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),

        dataType: 'json',

        beforeSend: function() {

          $('#recurring-description').html('');

        },

        success: function(json) {

          $('.alert, .text-danger').remove();



          if (json['success']) {

            $('#recurring-description').html(json['success']);

          }

        }

      });

    });

    //--></script>

  <script type="text/javascript"><!--

    $('#button-cart').on('click', function() {

      $.ajax({

        url: 'index.php?route=checkout/cart/add',

        type: 'post',

        data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),

        dataType: 'json',

        beforeSend: function() {

          $('#button-cart').button('loading');

        },

        complete: function() {

          $('#button-cart').button('reset');

        },

        success: function(json) {

          $('.alert, .text-danger').remove();

          $('.form-group').removeClass('has-error');



          if (json['error']) {

            if (json['error']['option']) {

              for (i in json['error']['option']) {

                var element = $('#input-option' + i.replace('_', '-'));



                if (element.parent().hasClass('input-group')) {

                  element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');

                } else {

                  element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');

                }

              }

            }



            if (json['error']['recurring']) {

              $('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');

            }



            // Highlight any found errors

            $('.text-danger').parent().addClass('has-error');

          }



          if (json['success']) {

            $('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');



            $('#cart-total').html(json['total']);



            $('html, body').animate({ scrollTop: 0 }, 'slow');



            $('#cart > ul').load('index.php?route=common/cart/info ul li');

			  }

			setTimeout(function() {$('.alert-success').fadeOut(1000)},3000)



        }

      });

    });

    //--></script>

  <script type="text/javascript"><!--

    $('.date').datetimepicker({

      pickTime: false

    });



    $('.datetime').datetimepicker({

      pickDate: true,

      pickTime: true

    });



    $('.time').datetimepicker({

      pickDate: false

    });



    $('button[id^=\'button-upload\']').on('click', function() {

      var node = this;



      $('#form-upload').remove();



      $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');



      $('#form-upload input[name=\'file\']').trigger('click');



      $('#form-upload input[name=\'file\']').on('change', function() {

        $.ajax({

          url: 'index.php?route=tool/upload',

          type: 'post',

          dataType: 'json',

          data: new FormData($(this).parent()[0]),

          cache: false,

          contentType: false,

          processData: false,

          beforeSend: function() {

            $(node).button('loading');

          },

          complete: function() {

            $(node).button('reset');

          },

          success: function(json) {

            $('.text-danger').remove();



            if (json['error']) {

              $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');

            }



            if (json['success']) {

              alert(json['success']);



              $(node).parent().find('input').attr('value', json['code']);

            }

          },

          error: function(xhr, ajaxOptions, thrownError) {

            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

          }

        });

      });

    });

    //--></script>

  <script type="text/javascript"><!--

    $('#review').delegate('.pagination a', 'click', function(e) {

      e.preventDefault();



      $('#review').fadeOut('slow');



      $('#review').load(this.href);



      $('#review').fadeIn('slow');

    });



    $('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');



    $('#button-review').on('click', function() {

      $.ajax({

        url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',

        type: 'post',

        dataType: 'json',

        data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),

        beforeSend: function() {

          $('#button-review').button('loading');

        },

        complete: function() {

          $('#button-review').button('reset');

          $('#captcha').attr('src', 'index.php?route=tool/captcha#'+new Date().getTime());

          $('input[name=\'captcha\']').val('');

        },

        success: function(json) {

          $('.alert-success, .alert-danger').remove();



          if (json['error']) {

            $('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');

          }



          if (json['success']) {

            $('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');



            $('input[name=\'name\']').val('');

            $('textarea[name=\'text\']').val('');

            $('input[name=\'rating\']:checked').prop('checked', false);

            $('input[name=\'captcha\']').val('');

          }

        }

      });

    });

    //--></script>

      <script>

				$( document ).ready(function() {

    $('.size').click(function (){

	$('.size').each(function (){

	$(this).attr("class", "option_box_enable size");

	});

	$(this).attr("class", "option_box_selected size");

	});

});

				  </script>



    <script>

				$(document).ready(function()

				{

					//Add Inactive Class To All Accordion Headers

					$('#accordion-container .accordion-header').toggleClass('inactive-header');



					// The Accordion Effect

					$('#accordion-container .accordion-header').click(function () {

						if($(this).is('.inactive-header')) {

							$('.active-header').toggleClass('active-header').toggleClass('inactive-header').next().slideToggle().toggleClass('open-content');

							$(this).toggleClass('active-header').toggleClass('inactive-header');

							$(this).next().slideToggle().toggleClass('open-content');

						}



						else {

							$(this).toggleClass('active-header').toggleClass('inactive-header');

							$(this).next().slideToggle().toggleClass('open-content');

						}

					});



					return false;

				});

				</script>

<?php echo $footer; ?>
