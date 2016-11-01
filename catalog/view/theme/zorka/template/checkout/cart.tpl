<?php echo $header; ?>

<div class="container">

    <ul class="breadcrumb">

        <?php foreach ($breadcrumbs as $breadcrumb) { ?>

            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>

        <?php } ?>

    </ul>

    <?php if ($attention) { ?>

        <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $attention; ?>

            <button type="button" class="close" data-dismiss="alert">&times;</button>

        </div>

    <?php } ?>

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

        <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>

            <h1><?php echo $heading_title; ?>

                <?php if ($weight) { ?>

                    &nbsp;(<?php echo $weight; ?>)

                <?php } ?>

            </h1>

            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead>

                        <tr>

                            <td class="text-center"><?php echo $column_image; ?></td>

                            <td class="text-left"><?php echo $column_name; ?></td>

                            <td class="text-left"><?php echo $column_model; ?></td>

                            <td class="text-left"><?php echo $column_quantity; ?></td>

                            <td class="text-left"><?php echo $column_price; ?></td>

                            <td class="text-right"><?php echo $column_total; ?></td>
                            <td></td>
                        </tr>

                        </thead>

                        <tbody>

                        <?php foreach ($products as $product) {
                            ?>

                            <tr>

                                <td class="text-center"><?php if ($product['thumb']) { ?>

                                        <a href="<?php echo $product['href']; ?>"><img
                                                src="<?php echo $product['thumb']; ?>"
                                                alt="<?php echo $product['name']; ?>"
                                                title="<?php echo $product['name']; ?>" class="img-thumbnail"/></a>

                                    <?php } ?></td>

                                <td class="text-left"><a style="font-weight: bold;"
                                                         href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                                    <?php
                                    if ($product['packQty'] !== 0) {
                                        ?>
                                        <br/>
                                        <span>Ratio : <?php echo $product['productRatio']; ?></span> /
                                        <span>Ratio Scale : <?php echo $product['productRatioScale']; ?></span>
                                    <?php } ?>
                                    <?php if (!$product['stock']) { ?>

                                        <span class="text-danger">***</span>

                                    <?php } ?>

                                    <?php if ($product['option']) { ?>

                                        <?php foreach ($product['option'] as $option) {
                                            if ($option['type'] != 'text') {
                                                ?>

                                                <br/>

                                                <small><?php echo $option['name']; ?>
                                                    : <?php echo $option['value']; ?></small>

                                            <?php   }

                                        }?>

                                    <?php } ?>

                                    <?php if ($product['reward']) { ?>

                                        <br/>

                                        <small><?php echo $product['reward']; ?></small>

                                    <?php } ?>

                                    <?php if ($product['recurring']) { ?>

                                        <br/>

                                        <span class="label label-info"><?php echo $text_recurring_item; ?></span>
                                        <small><?php echo $product['recurring']; ?></small>

                                    <?php }
                                    ?>

                                </td>

                                <td class="text-left"><?php echo $product['model']; ?></td>

                                <td class="text-left">
                                    <div class="input-group btn-block" style="max-width: 200px;">


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
                                                            <br/><span style="margin-left: 10px;"><?php echo $rs; ?>Size-
                                                                : <?php echo explode('-', $single['ratio'])[$i]; ?>pcs</span>
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

                    <span class="input-group-btn">

<!--                    <button type="submit" data-toggle="tooltip" title="-->
                        <?php //echo $button_update; ?><!--" class="btn btn-primary"><i class="fa fa-refresh"></i></button>-->

                   </span></div>
                                </td>

                                <td class="text-left">
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
                                <td><a data-toggle="tooltip" title="<?php echo $button_remove; ?>"
                                       class="add-btn"
                                       onclick="cart.remove('<?php echo $product['key']; ?>');">Remove</a></td>
                            </tr>

                        <?php } ?>

                        <?php foreach ($vouchers as $vouchers) { ?>

                            <tr>

                                <td></td>

                                <td class="text-left"><?php echo $vouchers['description']; ?></td>

                                <td class="text-left"></td>

                                <td class="text-left">
                                    <div class="input-group btn-block" style="max-width: 200px;">

                                        <input type="text" name="" value="1" size="1" disabled="disabled"
                                               class="form-control"/>

                                        <span class="input-group-btn"><button type="button" data-toggle="tooltip"
                                                                              title="<?php echo $button_remove; ?>"
                                                                              class="btn btn-danger"
                                                                              onclick="voucher.remove('<?php echo $vouchers['key']; ?>');">
                                                <i class="fa fa-times-circle"></i></button></span></div>
                                </td>

                                <td class="text-right"><?php echo $vouchers['amount']; ?></td>

                                <td class="text-right"><?php echo $vouchers['amount']; ?></td>

                            </tr>

                        <?php } ?>

                        </tbody>

                    </table>

                </div>

            </form>

            <?php if ($coupon || $voucher || $reward || $shipping) { ?>

                <h2><?php echo $text_next; ?></h2>

                <p><?php echo $text_next_choice; ?></p>

                <div class="panel-group"
                     id="accordion"><?php echo $coupon; ?><?php echo $voucher; ?><?php echo $reward; ?><?php echo $shipping; ?></div>

            <?php } ?>

            <br/>

            <div class="row">

                <div class="col-sm-4 col-sm-offset-8">

                    <table class="table table-bordered">

                        <?php foreach ($totals as $total) { ?>

                            <tr>

                                <td class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>

                                <td class="text-right"><?php echo $total['text']; ?></td>

                            </tr>

                        <?php } ?>

                    </table>

                </div>

            </div>

            <div class="buttons">

                <div class="pull-left"><a href="<?php echo $continue; ?>"
                                          class="btn btn-default"><?php echo $button_shopping; ?></a></div>

                <div class="pull-right"><a href="<?php echo $checkout; ?>"
                                           class="btn btn-primary"><?php echo $button_checkout; ?></a></div>

            </div>

            <?php echo $content_bottom; ?></div>

        <?php echo $column_right; ?></div>

</div>
<style>
    .parallax-banner {
        margin: 50px -100% -30px;
    }
</style>
<?php echo $footer; ?> 