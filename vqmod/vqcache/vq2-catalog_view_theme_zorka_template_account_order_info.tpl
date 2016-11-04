<?php echo $header; ?>
    <div class="container">
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ul>
        <?php if ($success) { ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <?php if ($error_warning) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
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
			
			
                <h2><?php echo $heading_title; ?></h2>
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <td class="text-left" colspan="2"><?php echo $text_order_detail; ?></td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-left"><?php if ($invoice_no) { ?>
                                <b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_no; ?><br/>
                            <?php } ?>
                            <b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br/>
                            <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?></td>
                        <td class="text-left"><?php if ($payment_method) { ?>
                                <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br/>
                            <?php } ?>
                            <?php if ($shipping_method) { ?>
                                <b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; ?>
                            <?php } ?></td>
                    </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <td class="text-left"><?php echo $text_payment_address; ?></td>
                        <?php if ($shipping_address) { ?>
                            <td class="text-left"><?php echo $text_shipping_address; ?></td>
                        <?php } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-left"><?php echo $payment_address; ?></td>
                        <?php if ($shipping_address) { ?>
                            <td class="text-left"><?php echo $shipping_address; ?></td>
                        <?php } ?>
                    </tr>
                    </tbody>
                </table>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <td class="text-left"><?php echo $column_name; ?></td>
                            <td class="text-left"><?php echo $column_model; ?></td>
                            <td class="text-right"><?php echo $column_quantity; ?></td>
                            <td class="text-right"><?php echo $column_price; ?></td>
                            <td class="text-right"><?php echo $column_total; ?></td>
                            <?php if ($products) { ?>
                                <td style="width: 20px;"></td>
                            <?php } ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($products as $product) { ?>
                            <tr>
                                <td class="text-left"><?php echo $product['name']; ?>
                                    <?php
                                    if ($product['packQty'] !== 0) {
                                        ?>
                                        <br/>
                                        <span>Ratio : <?php echo $product['productRatio']; ?></span> /
                                        <span>Ratio Scale : <?php echo $product['productRatioScale']; ?></span>
                                    <?php } ?>
                                    <?php foreach ($product['option'] as $option) { ?>
                                        <br/>


                                        <?php foreach ($product['option'] as $option) {
                                            if ($option['type'] != 'text') {
                                                ?>

                                                <br/>

                                                <small><?php echo $option['name']; ?>
                                                    : <?php echo $option['value']; ?></small>

                                            <?php }

                                        } ?>


                                    <?php } ?></td>
                                <td class="text-left"><?php echo $product['model']; ?></td>
                                <td class="text-right">

                                    <?php
                                    $totalPack = 0;
                                    foreach ($product['packData'] as $pack) {
                                        $totalPack += $pack['qty'];
                                    }

                                    if ($product['packQty'] !== 0) {
                                        ?>
                                        <span
                                            style="font-weight: bold;">Pack : </span> (<?php echo $totalPack; ?> Pack)
                                        <br/>
                                        <?php
                                        $totalPack = 0;
                                        foreach ($product['packData'] as $pack) {
                                            ?>
                                            <small style="margin-left: 5px;"><?php echo $pack['qty']; ?>
                                                X <?php echo $pack['colour']; ?>,
                                                (Total <?php echo array_sum(explode('-', $pack['ratio'])); ?>
                                                pieces)
                                            </small><br>
                                            <?php
                                            $totalPack += $pack['qty'];
                                        }
                                        ?>
                                        <br/>
                                        <?php
                                    }


                                    if ($product['singleQty'] !== 0) {
                                        ?>
                                        <span
                                            style="font-weight: bold;">Single Pieces : </span>(<?php echo $product['singleQty']; ?> pieces)
                                        <br/>
                                        <?php
                                        foreach ($product['singleData'] as $single) {
                                            ?>
                                            <small style="margin-left: 5px;"><?php echo $single['qty']; ?>
                                                X <?php echo $single['colour']; ?>

                                                <?php
                                                $i = 0;

                                                foreach (explode('-', $product['productRatioScale']) as $rs) {
                                                    if (explode('-', $single['ratio'])[$i] != 0) {
                                                        ?>
                                                        <br/><span style="margin-left: 10px;">Size-<?php echo $rs; ?>
                                                            : <?php echo explode('-', $single['ratio'])[$i]; ?>
                                                            pcs</span>
                                                        <?php
                                                    }

                                                    $i++;
                                                }
                                                ?>
                                            </small><br/>
                                            <?php
                                        }
                                    }


                                    ?>


                                    <hr style="margin:0"/>
                                    <span style="font-weight: bold">Total :</span>
                                    <?php echo $product['packQty'] + $product['singleQty']; ?> Pieces
                                    <!--                    <input type="text" name="quantity[-->
                                    <?php //echo $product['key']; ?><!--]" value="-->
                                    <?php //echo $product['quantity']; ?><!--" size="1" class="form-control" />-->

                                </td>
                                <td class="text-right">
                                    <?php
                                    if ($product['packQty'] !== 0) {
                                        ?>
                                        <?php echo $product['packPrice']; ?>/each for pack<br/>
                                        <?php
                                    }

                                    if ($product['sinleQty'] !== 0) {
                                        ?>
                                        <?php echo $product['eachPrice']; ?>/each for single
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td class="text-right"><?php echo $product['price']; ?></td>
                                <td class="text-right" style="white-space: nowrap;"><?php if ($product['reorder']) { ?>
                                        <a href="<?php echo $product['href']; ?>" data-toggle="tooltip"
                                           title="<?php echo $button_reorder; ?>" class="btn btn-primary"><i
                                                class="fa fa-shopping-cart"></i></a>
                                    <?php } ?>
                                    <a href="<?php echo $product['return']; ?>" data-toggle="tooltip"
                                       title="<?php echo $button_return; ?>" class="btn btn-danger"><i
                                            class="fa fa-reply"></i></a></td>
                            </tr>
                        <?php } ?>
                        <?php foreach ($vouchers as $voucher) { ?>
                            <tr>
                                <td class="text-left"><?php echo $voucher['description']; ?></td>
                                <td class="text-left"></td>
                                <td class="text-right">1</td>
                                <td class="text-right"><?php echo $voucher['amount']; ?></td>
                                <td class="text-right"><?php echo $voucher['amount']; ?></td>
                                <?php if ($products) { ?>
                                    <td></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                        <?php foreach ($totals as $total) { ?>
                            <tr>
                                <td colspan="3"></td>
                                <td class="text-right"><b><?php echo $total['title']; ?></b></td>
                                <td class="text-right"><?php echo $total['text']; ?></td>
                                <?php if ($products) { ?>
                                    <td></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        </tfoot>
                    </table>
                </div>
                <?php if ($comment) { ?>
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <td class="text-left"><?php echo $text_comment; ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-left"><?php echo $comment; ?></td>
                        </tr>
                        </tbody>
                    </table>
                <?php } ?>
                <?php if ($histories) { ?>
                    <h3><?php echo $text_history; ?></h3>
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <td class="text-left"><?php echo $column_date_added; ?></td>
                            <td class="text-left"><?php echo $column_status; ?></td>
                            <td class="text-left"><?php echo $column_comment; ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($histories as $history) { ?>
                            <tr>
                                <td class="text-left"><?php echo $history['date_added']; ?></td>
                                <td class="text-left"><?php echo $history['status']; ?></td>
                                <td class="text-left"><?php echo $history['comment']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
                <div class="buttons clearfix">
                    <div class="pull-right"><a href="<?php echo $continue; ?>"
                                               class="btn btn-primary"><?php echo $button_continue; ?></a></div>
                </div>
                <?php echo $content_bottom; ?></div>
            <?php echo $column_right; ?></div>
    </div>
<?php echo $footer; ?>