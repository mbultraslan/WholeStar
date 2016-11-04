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
        <div class="row">
            <?php echo $column_left; ?>
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
							<meta itemprop="price" content="<?php if( ! empty( $special ) ) { echo preg_replace( '/[^0-9.]/', '', str_replace( ',', '.', $special ) ); } else { echo preg_replace( '/[^0-9.]/', '', str_replace( ',', '.', $price ) ); } ?>">
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
                                                <a title="<?php echo $heading_title; ?>" class="product-image-link"
                                                   href="<?php echo $image['popup']; ?>"
                                                   data-image="<?php echo $image['popup']; ?>"
                                                   data-zoom-image="<?php echo $image['popup']; ?>">
                                                    <img src="<?php echo $image['thumb']; ?>"
                                                         title="<?php echo $heading_title; ?>"
                                                         alt="<?php echo $heading_title; ?>"/>
                                                </a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <div class="ProductThumbImage">
                                    <ul class="thumbnails">
                                        <?php if ($thumb) { ?>
                                            <div class="thumbnails__big-image"
                                                 style="max-width: <?php echo $config->get('config_image_thumb_width'); ?>px; max-height: <?php echo $config->get('config_image_thumb_height'); ?>px;">
                                                <a href="<?php echo $popup; ?>" class="product-image-link">
                                                    <img id="main-image" src="<?php echo $thumb; ?>"
                                                         data-zoom-image="<?php echo $popup; ?>"/>
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
                        <h3><?php echo $text_model; ?><?php echo $model; ?></h3>
                        <?php if ($review_status) { ?>
                            <div class="product-rating">
                                <p>
                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                        <?php if ($rating < $i) { ?>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                                        <?php } else { ?>
                                            <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i
                                                    class="fa fa-star-o fa-stack-2x"></i></span>
                                        <?php } ?>
                                    <?php } ?>
                                    <a href=""
                                       onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $reviews . "  " . "/"; ?></a>
                                    <a href=""
                                       onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $text_write; ?></a>
                                </p>
                            </div>
                        <?php } ?>
                        <div id="product">
                            <div class="product-tabs" style="margin-bottom: 20px">
                                <!-- tab start -->
                                <ul class="nav nav-tabs" style="height: 38px;">
                                    <li class="active"><a href="#tab-prices" data-toggle="tab"
                                                          style="border: 1px solid #ddd; border-bottom: none;">Prices</a>
                                    </li>
                                    <li><a href="#tab-details" data-toggle="tab"
                                           style="border: 1px solid #ddd; border-bottom: none;">Details</a></li>
                                    <li><a href="#tab-description" data-toggle="tab"
                                           style="border: 1px solid #ddd; border-bottom: none;">Description</a></li>
                                </ul>
                                <div class="tab-content" style="border: 1px solid #ddd; padding: 20px 20px 0 20px ;">
                                    <div class="tab-pane active" id="tab-prices">
                                        <!-- prices content -->
                                        <?php if ($price) { ?>
                                            <?php if ($special) { ?>
                                                <div class="product-sale">
                                                    -<?php echo $kuler->calculateSalePercent($special, $price); ?>%
                                                </div>
                                            <?php } ?>
                                            <?php if ($price) { ?>
                                                <ul class="list-unstyled ">
                                                    <?php if (!$special) { ?>
                                                        <li>
                                                            <table class="table" style="margin-bottom: 0;">
                                                                <tr>
                                                                    <td style="text-align: center;">
                                                                        PRICE PER PACK <br/> <span
                                                                            class="price-new price-tag"><?php echo $price; ?></span>
                                                                        <br/>(<?php echo $packPrice; ?> Each)
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        PRICE PER PIECE <br/> <span
                                                                            class="price-new price-tag"><?php echo $eachPrice; ?></span>
                                                                        <br/>
                                                                        SAVE UP
                                                                        TO <?php echo 100 - round(preg_replace("/[^0-9\.]/", '', $packPrice) / preg_replace("/[^0-9\.]/", '', $eachPrice) * 100); ?>
                                                                        % WHEN YOU BUY PACKS
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" style="text-align: center;">
                                                                        1 PACK = <?php echo $packQty; ?> UNITS
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </li>
                                                    <?php } else { ?>
                                                        <li>

                                                            <table class="table" style="margin-bottom: 0;">
                                                                <tr>
                                                                    <td style="text-align: center;">
                                                                        PRICE PER PACK <br/> <span
                                                                            class="product-price--old"><?php echo $price; ?></span>
                                                                        <!--<span><?php echo $kuler->calculateSalePercent($special, $price); ?>% off</span>-->
                                                                        <span
                                                                            class="price-new price-tag"><?php echo $special; ?></span>
                                                                        <br/>(<span
                                                                            class="product-price--old"><?php echo $packPrice; ?></span>
                                                                        <!--<span><?php echo $kuler->calculateSalePercent($special, $price); ?>% off</span>-->
                                                                        <span
                                                                            class="price-new price-tag"><?php echo $specialPack; ?></span>
                                                                        Each)
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        PRICE PER PIECE <br/> <span
                                                                            class="product-price--old"><?php echo $eachPrice; ?></span>
                                                                        <!--<span><?php echo $kuler->calculateSalePercent($special, $price); ?>% off</span>-->
                                                                        <span
                                                                            class="price-new price-tag"><?php echo $specialEach; ?></span>
                                                                        <br/>
                                                                        SAVE UP
                                                                        TO <?php echo 100 - round(preg_replace("/[^0-9\.]/", '', $specialPack) / preg_replace("/[^0-9\.]/", '', $specialEach) * 100); ?>
                                                                        % WHEN YOU BUY PACKS

                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" style="text-align: center;">
                                                                        1 PACK = <?php echo $packQty; ?> UNITS
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if ($points) { ?>
                                                        <li><?php echo $text_points; ?><?php echo $points; ?></li>
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
                                            <?php
                                        } else {
                                            ?>
                                            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Attention:
                                                You must <a class="loginModal" href="index.php?route=account/login">login</a>
                                                or <a
                                                    href="index.php?route=account/register">create an account</a> to
                                                view prices!
                                                <button type="button" class="close" data-dismiss="alert">×</button>

                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <!-- prices content -->
                                    <div class="tab-pane" id="tab-details">
                                        <!-- details content -->
                                        <ul class="list-unstyled product-options" style="list-style-type: circle;">
                                            <?php if ($manufacturer) { ?>
                                                <li>
                                                    <?php if ($kuler->getSkinOption('show_brand_logo')) { ?>
                                                        <a href="<?php echo $manufacturers; ?>">
                                                            <img
                                                                src="<?php echo $kuler->getManufacturerImage($product_id); ?>"
                                                                alt="<?php echo $manufacturer; ?>"/>
                                                        </a>
                                                    <?php } else { ?>
                                                        <?php echo $text_manufacturer; ?>
                                                        <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a>
                                                    <?php } ?>
                                                </li>
                                            <?php } ?>
                                            <li><?php echo $text_model; ?><?php echo $model; ?></li>
                                            <?php if ($material) { ?>
                                                <li>Material: <?php echo $material; ?></li>
                                            <?php } ?>
                                            <?php if ($madeIn) { ?>
                                                <li>Made In: <?php echo $madeIn; ?></li>
                                            <?php } ?>
                                            <?php if ($reward) { ?>
                                                <li><?php echo $text_reward; ?><?php echo $reward; ?></li>
                                            <?php } ?>
                                            <li><?php echo $text_stock; ?><?php echo $stock; ?></li>
                                            <li>Size Scale: <?php echo $ratioScale; ?></li>
                                            <li>Bundle/Lot Ratio: <?php echo $ratio; ?></li>
                                            <li>Bundle/Lot Size: <?php echo $packQty; ?>pcs</li>
                                        </ul>
                                    </div>
                                    <!-- details content -->
                                    <div class="tab-pane" id="tab-description">
                                        <!-- description content -->
                                        <?php echo $description; ?>
                                        <?php if ($kuler->getSkinOption('show_custom_block')) { ?>
                                            <div
                                                class="custom-block"><?php echo $kuler->translate($kuler->getSkinOption('custom_block_content')); ?></div>
                                        <?php } ?>
                                    </div>
                                    <!-- description content -->
                                </div>
                            </div>
                            <!-- tab finish -->

                            <div class="product-tabs">
                                <!-- tab start -->
                                <ul class="nav nav-tabs" style="height: 54px; text-align: center">
                                    <li class="active"><a href="#tab-pack" data-toggle="tab"
                                                          style="border: 1px solid #ddd; border-bottom: none;">Buy in
                                            Pack/s <br/>
                                            <?php if ($price) {
                                                if (!$special) {
                                                    echo $price . '/pack';
                                                } else {
                                                    echo $special . '/pack';
                                                }
                                            } ?>&nbsp;</a></li>
                                    <li><a href="#tab-single" data-toggle="tab"
                                           style="border: 1px solid #ddd; border-bottom: none;">Buy in Single
                                            Piece/s<br/>
                                            <?php if ($price) {
                                                if (!$special) {
                                                    echo $eachPrice . '/each';
                                                } else {
                                                    echo $specialEach . '/each';
                                                }
                                            } ?>&nbsp;</a></li>
                                    <li>
                                        <div id="packAlert" style="height: 50px; width: 180px;"></div>
                                    </li>
                                </ul>
                                <div class="tab-content" style="border: 1px solid #ddd; padding: 5px;">
                                    <!-- tab content -->
                                    <div class="tab-pane active" id="tab-pack">
                                        <!-- pack content -->
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Colour</th>
                                                <th style="text-align:center;"><label>Qty</label></th>
                                                <?php
                                                foreach (explode('-', $ratioScale) as $rs) {
                                                    echo '<th style="text-align:center;">' . $rs . '</th>';
                                                }
                                                ?>
                                                <th style="text-align:center;">Pcs</th>
                                                <th style="width:5px;"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            //json_decode($json_string, TRUE)

                                            $productKey = '';
                                            foreach ($_SESSION['cart'] as $key => $quantity) {
                                                $cart_product[] = unserialize(base64_decode($key));
                                                //$cart_product_id = $cart_product['product_id'];
                                                $isInCart = 0;

                                            }

                                            foreach ($cart_product as $cp) {
                                                if ($cp['product_id'] === $product_id) {
                                                    $isInCart = 1;
                                                    $sizeOptions = array_shift(array_values($cp['option']));
                                                    $sizeOptions = stripslashes(html_entity_decode($sizeOptions));
                                                    $sizeOptions = json_decode($sizeOptions, true);
                                                    $productKey = base64_encode(serialize($cp));
                                                }

                                            }

                                            //print_r( $_SESSION['cart']) ;

                                            //print_r( unserialize(base64_decode($key)));
                                            foreach ($productColours as $pc) {
                                                $packAvailability = '';
                                                $availablePack = '';
                                                if (in_array('0', explode('-', $pc['quantity']))) {
                                                    $availablePack = 0;
                                                    $packAvailability = 'disabled';
                                                } else {
                                                    $availablePack = floor(explode('-', $pc['quantity'])[0] / explode('-', $ratio)[0]);
                                                }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <span
                                                            style="border: 1px solid #000000; min-width:40px; min-height:40px; background-color: <?php echo '#' . $pc['colour']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                        &nbsp;<span class="pack-colourName"
                                                                    colour="<?php echo $pc['colour']; ?>"
                                                                    colourId="<?php echo $pc['id']; ?>"><?php echo $pc['name']; ?></span>
                                                    </td>
                                                    <td style="text-align:center;">
                                                        <input class="option-text" <?php echo $packAvailability; ?>
                                                               value="<?php echo $sizeOptions['pack'][$pc['colour']]['qty']; ?>"
                                                               onchange="fillRatio('<?php echo $pc["colour"]; ?>')"
                                                               type="number" id="qty<?php echo $pc['colour']; ?>"
                                                               max="<?php echo $availablePack; ?>"
                                                               style="width: 40px; text-align:center;"/>
                                                    </td>
                                                    <?php
                                                    if ($availablePack !== 0) {
                                                        $i = 0;
                                                        foreach (explode('-', $ratioScale) as $rs) {
                                                            ?>
                                                            <td class="<?php echo $pc['colour']; ?> option-text"
                                                                type="text"
                                                                rate="<?php echo explode('-', $ratio)[$i] ?>"
                                                                id="<?php echo $pc['colour'] . $i; ?>"
                                                                style="text-align: center;">
                                                                <?php
                                                                $q = explode('-', $sizeOptions['pack'][$pc['colour']]['ratio'])[$i];
                                                                if ($q != 0) {
                                                                    echo $q;
                                                                }
                                                                ?>
                                                            </td>
                                                            <?php
                                                            $i++;
                                                        }
                                                    } else {
                                                        $colspan = count(explode('-', $ratioScale));
                                                        ?>
                                                        <td colspan="<?php echo $colspan; ?>">
                                                            <a onclick="notifyMe(<?php echo $pc['id']; ?>)">Notify me
                                                                when available again</a>
                                                        </td>
                                                        <?php
                                                    }
                                                    ?>
                                                    <td class="totalProduct totalPack"
                                                        value="<?php echo array_sum(explode('-', $sizeOptions['pack'][$pc['colour']]['ratio'])); ?>"
                                                        id="totalProduct<?php echo $pc['colour']; ?>"
                                                        style=" text-align: center;">
                                                        <?php
                                                        $q = array_sum(explode('-', $sizeOptions['pack'][$pc['colour']]['ratio']));
                                                        if ($q != 0){
                                                        echo $q;
                                                        ?>
                                                    </td>
                                                    <td id="removePack<?php echo $pc['colour']; ?>">
                                                        <button type="button"
                                                                onclick="removePack('<?php echo $pc['colour']; ?>');"
                                                                title="Remove" class="btn-xs"><i
                                                                class="fa fa-times"></i></button>
                                                    </td>
                                                <?php
                                                }
                                                else {
                                                    ?>
                                                    <td id="removePack<?php echo $pc['colour']; ?>"></td>
                                                    <?php
                                                }
                                                ?>

                                                </tr>
                                            <?php }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- pack tab finish -->
                                    <div class="tab-pane" id="tab-single">
                                        <!-- single content -->
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Colour</th>
                                                <?php
                                                foreach (explode('-', $ratioScale) as $rs) {
                                                    echo '<th style="text-align:center;">' . $rs . '</th>';
                                                }
                                                ?>
                                                <th style="text-align:center;">Pcs</th>
                                                <th style="width: 5px;"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($productColours as $pc) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <span
                                                            style="border: 1px solid #000000; min-width:40px; min-height:40px; background-color: <?php echo '#' . $pc['colour']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                        &nbsp;<span class="single-colourName"
                                                                    colour="<?php echo $pc['colour']; ?>"
                                                                    colourId="<?php echo $pc['id']; ?>"><?php echo $pc['name']; ?></span>
                                                    </td>
                                                    <?php
                                                    $i = 0;


                                                    foreach (explode('-', $pc['singleQuantity']) as $sq) {
                                                        $sizeAvalibility = '';
                                                        $max = $sq;
                                                        if ($max == 0) {
                                                            $sizeAvalibility = 'disabled';
                                                        }
                                                        ?>
                                                        <td style="text-align:center;">
                                                            <input type="text" value="<?php
                                                            if ($max != 0) {
                                                                $q = explode('-', $sizeOptions['single'][$pc['colour']]['ratio'])[$i];
                                                                if ($q != 0) {
                                                                    echo $q;
                                                                }
                                                            }
                                                            ?>"
                                                                   max="<?php echo $max; ?>" <?php echo $sizeAvalibility; ?>
                                                                   class="sng<?php echo $pc['colour']; ?>"
                                                                   id="sng<?php echo $pc['colour'] . $i; ?>"
                                                                   onchange="checkSize('<?php echo $pc["colour"] . "' , " . $i; ?>)"
                                                                   style="width: 40px; text-align:center;">
                                                        </td>
                                                        <?php
                                                        $i++;
                                                    }
                                                    ?>
                                                    <td class="totalProduct totalSingle"
                                                        id="sngTotalProduct<?php echo $pc['colour']; ?>"
                                                        style=" text-align: center;">
                                                        <?php
                                                        $q = array_sum(explode('-', $sizeOptions['single'][$pc['colour']]['ratio']));
                                                        if ($q != 0){
                                                        echo $q;
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                                onclick="removeSize('<?php echo $pc['colour']; ?>');"
                                                                title="Remove" class="btn-xs"><i
                                                                class="fa fa-times"></i></button>
                                                    </td>
                                                <?php
                                                }
                                                else {
                                                    ?>
                                                    <td></td>
                                                    <?php
                                                }
                                                ?>

                                                </tr>
                                            <?php }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- pack tab finish -->
                                    <table class="table" style="margin-bottom: 0">
                                        <tbody>
                                        <tr>
                                            <td>TOTAL QTY FOR THIS STYLE :</td>
                                            <td style="text-align: right;"><span id="totalQty"></span></td>
                                        </tr>
                                        <tr>
                                            <td>TOTAL AMOUNT FOR THIS STYLE :</td>
                                            <td style="text-align: right;"><span id="totalAmount"></span></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- tab content finish -->
                            </div>
                            <!-- tab  finish -->
                            <input type="hidden" value="<?php echo $product_id; ?>" name="product_id"/>
                            <input type="hidden" value="1" id="quantity" name="quantity"/>
                            <input type="hidden" value="0" id="totalProducts" name="totalProducts"/>
                            <?php foreach ($options as $option) {
                                ?>
                                <div class="form-group<?php echo($option['required'] ? ' required' : ''); ?>"
                                     style="display: none;">
                                    <label class="control-label"
                                           for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                                    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]"
                                           value="<?php echo $option['value']; ?>"
                                           placeholder="<?php echo $option['name']; ?>" id="options"
                                           class="form-control"/>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <script>
                            $(document).ready(function () {
                                totalQty();
                            });

                            function fillRatio(colour) {
                                var pack = parseInt($('#qty' + colour).val());
                                if (pack >= 0) {
                                    var availablePack = parseInt($('#qty' + colour).attr('max'));
                                    if (availablePack < pack) {
                                        $('#packAlert').append('<div  class="alert alert-danger">Stock availability ' + availablePack + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                                        pack = availablePack;
                                        $('#qty' + colour).val(pack);
                                    }
                                    var i = 0;
                                    var rate = 0;
                                    var totalProduct = 0;
                                    $('.' + colour).each(function () {
                                        rate = $(this).attr('rate');
                                        $('#' + colour + i).text(pack * rate);
                                        totalProduct += pack * rate;
                                        i++;
                                    });
                                    $('#totalProduct' + colour).text(totalProduct);

                                    totalQty();
                                }

                                $('#removePack' + colour).html('<button type="button" onclick="removePack(\'' + colour + '\');" title="Remove" class="btn-xs"><i class="fa fa-times"></i></button>')
                            }

                            function createOptionArray() {
                                if ($('#totalProducts').val() == '0') {
                                    $('#packAlert').append('<div class="alert alert-danger">No product selected <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                                    return false;
                                }
                                var totalSingle = 0;
                                var totalPack = 0;
                                $('.totalSingle').each(function () {
                                    var productCount = $(this).text();
                                    if ($.trim(productCount) == '') {
                                        productCount = 0;
                                    }
                                    totalSingle += parseFloat(productCount);
                                });

                                $('.totalPack').each(function () {
                                    var productCount = $(this).text();
                                    if ($.trim(productCount) == '') {
                                        productCount = 0;
                                    }
                                    totalPack += parseFloat(productCount);
                                });

                                if (totalPack != 0) {
                                    var array = '{ "pack": {';
                                    $('.pack-colourName').each(function () {
                                        var ratio = '';
                                        var colourName = $(this).text();

                                        var colourCode = $(this).attr('colour');
                                        var colourId = $(this).attr('colourId');
                                        var qty = $('#qty' + colourCode).val();
                                        if (qty == '' || qty < 1) {
                                            qty = 0;
                                        }
                                        if (qty !== 0) {
                                            $('.' + colourCode).each(function () {
                                                var size = $(this).text();
                                                if ($.trim(size) == '') {
                                                    size = 0;
                                                }
                                                ratio += size + '-';
                                            });
                                            ratio = ratio.slice(0, -1); //delete last -
                                            array += '"' + colourCode + '": { "colour": "' + colourName + '", "qty": "' + qty + '", "ratio" : "' + ratio + '", "id":"' + colourId + '" },'
                                        }
                                    });
                                    array = array.slice(0, -1); //delete last ,
                                    array += '}';
                                }
                                else {
                                    var array = '{ "pack": 0'
                                }

                                if (totalSingle != 0) {
                                    array += ', "single":{';
                                    $('.single-colourName').each(function () {
                                        var ratio = '';
                                        var colourName = $(this).text();

                                        var colourCode = $(this).attr('colour');
                                        var colourId = $(this).attr('colourId');
                                        var qty = $('#sngTotalProduct' + colourCode).text();
                                        if (qty == '' || qty < 1) {
                                            qty = 0;
                                        }
                                        if (qty !== 0) {
                                            $('.sng' + colourCode).each(function () {
                                                var size = $(this).val();
                                                if ($.trim(size) == '') {
                                                    size = 0;
                                                }
                                                ratio += size + '-';
                                            });
                                            ratio = ratio.slice(0, -1); //delete last -
                                            array += '"' + colourCode + '": { "colour": "' + colourName + '", "qty": "' + qty + '", "ratio" : "' + ratio + '", "id":"' + colourId + '" },'
                                        }
                                    });
                                    array = array.slice(0, -1); //delete last ,
                                    array += '}}';
                                }
                                else {
                                    array += ', "single": 0';
                                    array += '}';
                                }


                                $('#options').val(array);

                            }


                            function checkSize(colour, size) {
                                var count = parseInt($('#sng' + colour + size).val());
                                var availableSize = parseInt($('#sng' + colour + size).attr('max'));
                                if (availableSize < count) {
                                    $('#packAlert').append('<div class="alert alert-danger">Stock availability ' + availableSize + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                                    count = availableSize;
                                    $('#sng' + colour + size).val(count);
                                }
                                var totalProduct = 0;
                                $('.sng' + colour).each(function () {
                                    var sizeCount = $(this).val();
                                    if ($.trim(sizeCount) == '') {
                                        sizeCount = 0;
                                    }
                                    totalProduct += parseInt(sizeCount);
                                });
                                $('#sngTotalProduct' + colour).text(totalProduct);

                                totalQty();

                            }


                            function totalQty() {

                                var totalProduct = 0;
                                var totalSingle = 0;
                                var totalPack = 0;
                                $('.totalProduct').each(function () {
                                    var productCount = $(this).text();
                                    if ($.trim(productCount) == '') {
                                        productCount = 0;
                                    }
                                    totalProduct += parseInt(productCount);
                                });
                                // $('#quantity').val(totalProduct);
                                $('#totalProducts').val(totalProduct);
//


                                $('.totalSingle').each(function () {
                                    var productCount = $(this).text();
                                    if ($.trim(productCount) == '') {
                                        productCount = 0;
                                    }
                                    totalSingle += parseFloat(productCount);
                                });

                                $('.totalPack').each(function () {
                                    var productCount = $(this).text();
                                    if ($.trim(productCount) == '') {
                                        productCount = 0;
                                    }
                                    totalPack += parseFloat(productCount);
                                });
                                var totalPackText = '';
                                var totalPlus = '';
                                var totalQtyText = '';
                                var totalSingleText = '';
                                if (totalPack > 0) {
                                    totalPackText = totalPack + 'pcs in pack ';
                                }
                                if (totalSingle > 0) {
                                    totalSingleText = totalSingle + 'pcs in single ';
                                }
                                if (totalSingle > 0 && totalPack > 0) {
                                    totalPlus = ' + ';
                                    totalQtyText = ' = ' + totalProduct + ' pieces';
                                }
                                $('#totalQty').html(totalPackText + totalPlus + totalSingleText + totalQtyText);

                                <?php if ($price) {
                                if (!$special) { ?>
                                var packPrice = '<?php echo $packPrice; ?>';
                                packPrice = Number(packPrice.replace(/[^0-9\.]+/g, ""));
                                var eachPrice = '<?php echo $eachPrice; ?>';
                                eachPrice = Number(eachPrice.replace(/[^0-9\.]+/g, ""));

                                var totalAmount = totalPack * packPrice + totalSingle * eachPrice;

                                var totalPackPriceText = '';
                                var totalPricePlus = '';
                                var totalQtyPriceText = '';
                                var totalSinglePriceText = '';
                                if (totalPack > 0) {
                                    totalPackPriceText = '£' + (totalPack * packPrice).toFixed(2) + ' for pack ';
                                }
                                if (totalPack > 0 && totalSingle == 0) {
                                    totalPackPriceText = '£' + (totalPack * packPrice).toFixed(2);
                                }
                                if (totalSingle > 0) {
                                    totalSinglePriceText = '£' + (totalSingle * eachPrice).toFixed(2) + ' for single ';
                                }
                                if (totalSingle > 0 && totalPack ==0) {
                                    totalSinglePriceText = '£' + (totalSingle * eachPrice).toFixed(2);
                                }
                                if (totalSingle > 0 && totalPack > 0) {
                                    totalPricePlus = ' + ';
                                    totalQtyPriceText = ' = £' + totalAmount.toFixed(2);
                                }

                                var res = '<span class="price-new price-tag">'+ totalPackPriceText + totalPricePlus + totalSinglePriceText + totalQtyPriceText + '</span>';
                                $('#totalAmount').html(res);
                                <?php
                                }
                                else{
                                ?>
                                var oldPackPrice = '<?php echo $packPrice; ?>';
                                oldPackPrice = Number(oldPackPrice.replace(/[^0-9\.]+/g, ""));
                                var oldEachPrice = '<?php echo $eachPrice; ?>';
                                oldEachPrice = Number(oldEachPrice.replace(/[^0-9\.]+/g, ""));

                                var oldTotalAmount = totalPack * oldPackPrice + totalSingle * oldEachPrice;

                                var packPrice = '<?php echo $specialPack; ?>';
                                packPrice = Number(packPrice.replace(/[^0-9\.]+/g, ""));
                                var eachPrice = '<?php echo $specialEach; ?>';
                                eachPrice = Number(eachPrice.replace(/[^0-9\.]+/g, ""));

                                var totalAmount = totalPack * packPrice + totalSingle * eachPrice;

                                var res = '<span class="product-price--old"> £' + oldTotalAmount.toFixed(2) + '</span>' +
                                    '<span class="price-new price-tag"> £' + totalAmount.toFixed(2) + '</span>';
                                $('#totalAmount').html(res);

                                <?php
                                }
                                }
                                else{
                                ?>
                                $('#totalAmount').html('You must <a class= "loginModal" href="index.php?route=account/login">login</a> or <a href="index.php?route=account/register">create an account</a> to view prices!');
                                <?php
                                }
                                ?>

                                setTimeout(function () {
                                    $('.alert-danger').remove()
                                }, 3000);

                            }

                            function notifyMe(id) {
                                alert('OK ' + id);
                            }

                            function removePack(colour) {
                                $('#qty' + colour).val('');
                                $('.' + colour).text('');
                                $('#totalProduct' + colour).text('');

                                $('#removePack' + colour).html('');

                                totalQty();

                            }
                            function removeSize(colour) {
                                $('.sng' + colour).val('');
                                $('#sngTotalProduct' + colour).text('');

                                $('#removeSingle' + colour).html('');

                                totalQty();

                            }
                        </script>
                        <div class="product-detail button-group">
                            <div class="product-detail__group-buttons">
                                <?php if ($price) { ?>
                                    <a id="button-cart"
                                       class=" add-btn  col-sm-12 col-xs-12 col-md-4 col-lg-4 col-xlg-4"
                                       title="<?php echo $button_cart; ?>"
                                       data-loading-text="<?php echo $text_loading; ?>">
                                        <!--<i class="fa fa-shopping-cart"></i>--><span id="cartText">
                         <?php if ($isInCart === 1) {
                             echo "Update The Bag";
                         } else {
                             echo $button_cart;
                         }
                         ?></span>
                                    </a>
                                <?php } else {
                                    ?>
                                    <a href="index.php?route=account/login" style="width: 300px; color: #ffffff"
                                       class="loginModal add-btn  col-sm-12 col-xs-12 col-md-4 col-lg-4 col-xlg-4"
                                       title="Login to add bag"
                                       data-loading-text="<?php echo $text_loading; ?>">
                                        Please login to add cart!
                                    </a>
                                    <?php

                                }

                                ?>
                                <a class="share-icon" title="<?php echo $button_wishlist; ?>"
                                   onclick="wishlist.add('<?php echo $product_id; ?>');"><i class="fa fa-heart"></i></a>
                                <?php if ($kuler->getSkinOption('default_sharing')) { ?>
                                    <a class="share-icon"
                                       href="http://pinterest.com/pin/create/bookmarklet/?media=[MEDIA]&amp;url=[URL]&amp;is_video=false&amp;description=[TITLE]"><i
                                            class="fa fa-pinterest" style="color:#C8C8C8;"></i></a>
                                    <a class="share-icon" href="http://twitter.com/share"><i class="fa fa-twitter"
                                                                                             style="color:#C8C8C8;"></i></a>
                                    <script type="text/javascript"
                                            src="https://platform.twitter.com/widgets.js"></script>
                                    <a class="share-icon"
                                       href="http://www.facebook.com/sharer/sharer.php?u=[URL]&amp;title=[TITLE]"><i
                                            class="fa fa-facebook" style="color:#C8C8C8;"></i></a>
                                <?php } ?>
                            </div>
                        </div>
                        <div id="accordion-container" style="margin:10px 0;">
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
                                        <a class="tag__name"
                                           href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
                                    <?php } else { ?>
                                        <a class="tag__name"
                                           href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
                                    <?php } ?>
                                <?php } ?>
                                .
                            </p>
                        <?php } ?>
                        <?php if ($minimum > 1) { ?>
                            <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_minimum; ?>
                            </div>
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
            <?php echo $column_right; ?>
        </div>
        <hr/>
        <?php if ($products && $kuler->getSkinOption('show_related_products')) { ?>
            <div class="product-related">
                <div class="box-heading">
                    <span><?php echo $kuler->language->get('text_related_products'); ?></span>
                </div>
                <div id="product-related" class="row owl-carousel">
                    <?php foreach ($products as $product) { ?>
                        <div
                            class="product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12 <?php if (isset($product['date_end']) && $product['date_end']) echo ' has-deal'; ?>">
                            <?php if ($product['thumb']) { ?>
                                <div class="product-thumb product-thumb--no-image">
                                    <a href="<?php echo $product['href']; ?>">
                                        <img src="<?php echo $product['thumb']; ?>"/>
                                    </a>
                                </div>
                                <!--/.product-thumb--no-image-->
                            <?php } else { ?>
                                <div class="product-thumb product-thumb--no-image">
                                    <a href="<?php echo $product['href']; ?>">
                                        <img src="image/no_image.jpg" alt="<?php echo $product['name']; ?>"/>
                                    </a>
                                </div>
                                <!--/.product-thumb--no-image-->
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
                                    <button
                                        class="product-detail-button product-detail-button--cart hidden-xs hidden-sm hidden-md"
                                        type="button" data-toggle="tooltip" title="<?php echo $button_cart; ?>"
                                        onclick="cart.add('<?php echo $product['product_id']; ?>');">
                                        <span><?php echo $button_cart; ?></span>
                                        <i class="pe-7s-cart"></i>
                                    </button>
                                    <button
                                        class="product-detail-button product-detail-button--wishlist hidden-lg hidden-xlg "
                                        type="button" data-toggle="tooltip" title="<?php echo $button_cart; ?>"
                                        onclick="cart.add('<?php echo $product['product_id']; ?>');">
                                        <i class="pe-7s-cart"></i>
                                    </button>
                                    <button
                                        class="product-detail-button product-detail-button--wishlist hidden-xs hidden-sm "
                                        type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>"
                                        onclick="wishlist.add('<?php echo $product['product_id']; ?>');">
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
        <?php echo $content_bottom; ?>
    </div>
    </div>
    <script type="text/javascript"><!--
        $('select[name=\'recurring_id\'], input[name="quantity"]').change(function () {

            $.ajax({

                url: 'index.php?route=product/product/getRecurringDescription',

                type: 'post',

                data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),

                dataType: 'json',

                beforeSend: function () {

                    $('#recurring-description').html('');

                },

                success: function (json) {

                    $('.alert, .text-danger').remove();


                    if (json['success']) {

                        $('#recurring-description').html(json['success']);

                    }

                }

            });

        });

        //--></script>
    <script type="text/javascript"><!--
        $('#button-cart').on('click', function () {

            <?php if ($isInCart === 1) {
            echo "cart.remove('" . $productKey . "')";
        } ?>

            createOptionArray();
            $.ajax({

                url: 'index.php?route=checkout/cart/add',

                type: 'post',

                data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),

                dataType: 'json',

                beforeSend: function () {

                    $('#button-cart').button('loading');

                },

                complete: function () {

                    $('#button-cart').button('reset');

                },

                success: function (json) {

                    $('.alert-SUCCESS, .text-danger').remove();

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


                        $('html, body').animate({scrollTop: 0}, 'slow');


                        $('#cart > ul').load('index.php?route=common/cart/info ul li');

                        location.reload();
                    }

                    setTimeout(function () {
                        $('.alert-success').fadeOut(1000)
                    }, 3000)


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


        $('button[id^=\'button-upload\']').on('click', function () {

            var node = this;


            $('#form-upload').remove();


            $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');


            $('#form-upload input[name=\'file\']').trigger('click');


            $('#form-upload input[name=\'file\']').on('change', function () {

                $.ajax({

                    url: 'index.php?route=tool/upload',

                    type: 'post',

                    dataType: 'json',

                    data: new FormData($(this).parent()[0]),

                    cache: false,

                    contentType: false,

                    processData: false,

                    beforeSend: function () {

                        $(node).button('loading');

                    },

                    complete: function () {

                        $(node).button('reset');

                    },

                    success: function (json) {

                        $('.text-danger').remove();


                        if (json['error']) {

                            $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');

                        }


                        if (json['success']) {

                            alert(json['success']);


                            $(node).parent().find('input').attr('value', json['code']);

                        }

                    },

                    error: function (xhr, ajaxOptions, thrownError) {

                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

                    }

                });

            });

        });

        //--></script>
    <script type="text/javascript"><!--
        $('#review').delegate('.pagination a', 'click', function (e) {

            e.preventDefault();


            $('#review').fadeOut('slow');


            $('#review').load(this.href);


            $('#review').fadeIn('slow');

        });


        $('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');


        $('#button-review').on('click', function () {

            $.ajax({

                url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',

                type: 'post',

                dataType: 'json',

                data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),

                beforeSend: function () {

                    $('#button-review').button('loading');

                },

                complete: function () {

                    $('#button-review').button('reset');

                    $('#captcha').attr('src', 'index.php?route=tool/captcha#' + new Date().getTime());

                    $('input[name=\'captcha\']').val('');

                },

                success: function (json) {

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
        $(document).ready(function () {

            $('.size').click(function () {

                $('.size').each(function () {

                    $(this).attr("class", "option_box_enable size");

                });

                $(this).attr("class", "option_box_selected size");

            });

        });


    </script>
    <script>
        $(document).ready(function () {

            //Add Inactive Class To All Accordion Headers

            $('#accordion-container .accordion-header').toggleClass('inactive-header');


            // The Accordion Effect

            $('#accordion-container .accordion-header').click(function () {

                if ($(this).is('.inactive-header')) {

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

        function loginModal() {

        }

    </script>
<?php echo $footer; ?>