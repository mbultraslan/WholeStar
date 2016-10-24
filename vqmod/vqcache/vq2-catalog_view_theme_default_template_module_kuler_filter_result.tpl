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
			
			
        <h2><?php echo $heading_title; ?></h2>
        <?php if ($thumb || $description) { ?>
          <div class="row">
            <?php if ($thumb && !$kuler->getSkinOption('hide_category_image')) { ?>
              <div class="image"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" /></div>
            <?php } ?>
            <?php if ($description) { ?>
              <div class="col-lg-10 col-sm-10"><?php echo $description; ?></div>
            <?php } ?>
          </div>
          <hr>
        <?php } ?>
        <?php if ($categories) { ?>
          <div class="refine-search">
            <h3><?php echo $text_refine; ?></h3>
            <div class="owl-carousel">
              <?php foreach ($categories as $category) { ?>
                <div>
                  <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?>
                  </a>
                </div>
              <?php } ?>
            </div>
          </div>
          <script type="text/javascript">
            $('.owl-carousel').owlCarousel({
              loop:true,
              margin:10,
              responsive:{
                0:{
                  items:2
                },
                600:{
                  items:4
                },
                1000:{
                  items:6
                }
              }
            })
          </script>
        <?php } //end refine search ?>
        <?php if ($products) { ?>
          <div class="categories-filter">
            <div class="row">
              <div class="col-lg-2 col-md-2">
                <div class="btn-group">
                  <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="List"><i class="fa fa-th-list"></i></button>
                  <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="Grid"><i class="fa fa-th"></i></button>
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
                    </div>
                    
                  <?php } //end product thumb ?>
                  <h4 class="product-name">
                    <a href="<?php echo $product['href']; ?>">
                      <?php echo $product['name']; ?>
                    </a>
                  </h4>
                 <!-- <div class="product-rating">
                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                      <?php if ($product['rating'] < $i) { ?>
                        <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                      <?php } else { ?>
                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                      <?php } ?>
                    <?php } ?>
                  </div>-->
                  <p class="product-price">
                    <?php if (!$product['special']) { ?>
                      <?php echo $product['price']; ?>
                    <?php } else { ?>
                      <span class="product-price--new"><?php echo $product['special']; ?></span>
                      <span class="product-price--old"><?php echo $product['price']; ?></span>
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
                       <!-- <i class="pe-7s-repeat"></i>
                      </button>-->
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
          <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
      </div>
      <?php } ?>
      <?php if (!$categories && !$products) { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>