<?php $kuler = Kuler::getInstance(); ?>

<?php echo $header; ?>

<div class="container">

  <ul class="breadcrumb">

    <?php foreach ($breadcrumbs as $breadcrumb) { ?>

      <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>

    <?php } ?>

  </ul>

  <div class="row"><?php echo $column_left; ?>

    <?php if ($column_left && $column_right) { ?>

      <?php $class = 'col-lg-6 col-sm-6'; ?>

    <?php } elseif ($column_left || $column_right) { ?>

      <?php $class = 'col-lg-9 col-sm-9'; ?>

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
			
			

      <h1><?php echo $heading_title; ?></h1>

      <label class="control-label" for="input-search"><?php echo $entry_search; ?></label>

      <div class="row">

        <div class="col-sm-4">

          <input type="text" name="search" value="<?php echo $search; ?>" placeholder="<?php echo $text_keyword; ?>" id="input-search" class="form-control" />

        </div>

        <div class="col-sm-3">

          <select name="category_id" class="form-control">

            <option value="0"><?php echo $text_category; ?></option>

            <?php foreach ($categories as $category_1) { ?>

              <?php if ($category_1['category_id'] == $category_id) { ?>

                <option value="<?php echo $category_1['category_id']; ?>" selected="selected"><?php echo $category_1['name']; ?></option>

              <?php } else { ?>

                <option value="<?php echo $category_1['category_id']; ?>"><?php echo $category_1['name']; ?></option>

              <?php } ?>

              <?php foreach ($category_1['children'] as $category_2) { ?>

                <?php if ($category_2['category_id'] == $category_id) { ?>

                  <option value="<?php echo $category_2['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>

                <?php } else { ?>

                  <option value="<?php echo $category_2['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>

                <?php } ?>

                <?php foreach ($category_2['children'] as $category_3) { ?>

                  <?php if ($category_3['category_id'] == $category_id) { ?>

                    <option value="<?php echo $category_3['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>

                  <?php } else { ?>

                    <option value="<?php echo $category_3['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>

                  <?php } ?>

                <?php } ?>

              <?php } ?>

            <?php } ?>

          </select>

        </div>

        <div class="col-sm-3">

          <label class="checkbox-inline">

            <?php if ($sub_category) { ?>

              <input type="checkbox" name="sub_category" value="1" checked="checked" />

            <?php } else { ?>

              <input type="checkbox" name="sub_category" value="1" />

            <?php } ?>

            <?php echo $text_sub_category; ?></label>

        </div>

      </div>

      <p>

        <label class="checkbox-inline">

          <?php if ($description) { ?>

            <input type="checkbox" name="description" value="1" id="description" checked="checked" />

          <?php } else { ?>

            <input type="checkbox" name="description" value="1" id="description" />

          <?php } ?>

          <?php echo $entry_description; ?></label>

      </p>

      <input type="button" value="<?php echo $button_search; ?>" id="button-search" class="btn btn-primary" />

      <h2><?php echo $text_search; ?></h2>

      <?php if ($products) { ?>

        <div class="categories-filter">

          <div class="row">

            <div class="col-lg-2 col-md-2">

              <div class="btn-group">

                <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>

                <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>

              </div>

            </div>

            <!--<div class="col-lg-3 col-md-3">

              <a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a>

            </div>-->

            <div class="col-lg-2 col-md-2 text-right">

              <label class="control-label" for="input-sort"><?php echo $text_sort; ?></label>

            </div>

            <div class="col-lg-2 col-md-2 text-right">

              <select id="input-sort" class="form-control" onchange="location = this.value;">

                <?php foreach ($sorts as $sorts) { ?>

                  <?php if ($sorts['value'] == $sort . '-' . $order) { ?>

                    <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>

                  <?php } else { ?>

                    <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>

                  <?php } ?>

                <?php } ?>

              </select>

            </div>

            <div class="col-lg-1 col-md-1 text-right">

              <label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>

            </div>

            <div class="col-lg-2 col-md-2 text-right">

              <select id="input-limit" class="form-control" onchange="location = this.value;">

                <?php foreach ($limits as $limits) { ?>

                  <?php if ($limits['value'] == $limit) { ?>

                    <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>

                  <?php } else { ?>

                    <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>

                  <?php } ?>

                <?php } ?>

              </select>

            </div>

          </div>

        </div>

        <br />

        <div class="row">

          <?php foreach ($products as $product) { ?>

            <div class="product-layout product-list col-lg-12 col-xs-12">

              <div class="product-wrapper">

                <?php if ($product['thumb']) { ?>

                  <div class="product-thumb">

                    <div class="product-thumb__primary">

                      <a href="<?php echo $product['href']; ?>">

                        <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" />

                      </a>

                    </div>

                    <?php if ($images = $kuler->getProductImages($product['product_id'])) { ?>

                      <?php if(!$kuler->mobile->isMobile() && $kuler->getSkinOption('enable_swap_image')){ ?>

                        <?php $size = $kuler->getImageSizeByPath($product['thumb']); ?>

                        <div class="product-thumb__secondary hidden-xs hidden-sm hidden-md">

                          <a href="<?php echo $product['href']; ?>">

                            <img src="<?php echo $kuler->resizeImage($images[0], $size['width'], $size['height']); ?>" alt="<?php echo $product['name']; ?>"/>

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

                      <div class="product-sale">

                        <span>-<?php echo $kuler->calculateSalePercent($product['special'], $product['price']); ?>%</span>

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

                <?php } //end product thumb ?>

                <h4 class="product-name">

                  <a href="<?php echo $product['href']; ?>">

                    <?php echo $product['name']; ?>

                  </a>

                </h4>

                <div class="product-rating">

                  <?php for ($i = 1; $i <= 5; $i++) { ?>

                    <?php if ($product['rating'] < $i) { ?>

                      <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>

                    <?php } else { ?>

                      <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>

                    <?php } ?>

                  <?php } ?>

                </div>

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

                    <button class="product-detail-button product-detail-button--cart" type="button" data-toggle="tooltip" title="<?php echo $button_cart; ?>" onclick="cart.add('<?php echo $product['product_id']; ?>');">

                      <span><?php echo $button_cart; ?></span>

                      <i class="pe-7s-cart"></i>

                    </button>

                    <button class="product-detail-button product-detail-button--wishlist" type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');">

                      <i class="pe-7s-like"></i>

                    </button>

                    <!--<button class="product-detail-button product-detail-button--compare" type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');">

                      <!--<i class="pe-7s-repeat"></i>

                    </button>-->

                  </div>

                </div>

              </div>

            </div>

          <?php } ?>

        </div>

        <div class="row">

          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>

          <div class="col-sm-6 text-right"><?php echo $results; ?></div>

        </div>

      <?php } else { ?>

        <p><?php echo $text_empty; ?></p>

      <?php } ?>

      <?php echo $content_bottom; ?>

    </div>

    <?php echo $column_right; ?></div>

</div>

<?php echo $footer; ?>

<script type="text/javascript"><!--

  $('#button-search').bind('click', function() {

    url = 'index.php?route=product/search';



    var search = $('#content input[name=\'search\']').prop('value');



    if (search) {

      url += '&search=' + encodeURIComponent(search);

    }



    var category_id = $('#content select[name=\'category_id\']').prop('value');



    if (category_id > 0) {

      url += '&category_id=' + encodeURIComponent(category_id);

    }



    var sub_category = $('#content input[name=\'sub_category\']:checked').prop('value');



    if (sub_category) {

      url += '&sub_category=true';

    }



    var filter_description = $('#content input[name=\'description\']:checked').prop('value');



    if (filter_description) {

      url += '&description=true';

    }



    location = url;

  });



  $('#content input[name=\'search\']').bind('keydown', function(e) {

    if (e.keyCode == 13) {

      $('#button-search').trigger('click');

    }

  });



  $('select[name=\'category_id\']').on('change', function() {

    if (this.value == '0') {

      $('input[name=\'sub_category\']').prop('disabled', true);

    } else {

      $('input[name=\'sub_category\']').prop('disabled', false);

    }

  });



  $('select[name=\'category_id\']').trigger('change');

  --></script>