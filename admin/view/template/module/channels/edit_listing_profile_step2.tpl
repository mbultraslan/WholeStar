<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?> - <?php echo $version; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">

        <?php if ($ebay_global_message) { ?>
        <div class="alert alert-success" role="alert">
            <?php echo $ebay_global_message; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <?php if ($error_warning) { ?>
        <div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <?php if ($error_attention) { ?>
        <div class="alert alert-warning" role="alert">
            <?php echo $error_attention; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs tab-size-bigger">
            <li><a href="<?php echo $tab_dashboard; ?>" ><i class="fa fa-tachometer bigger-130"></i> <span class="bigger-110"> Dashboard</span></a></li>
            <li><a href="<?php echo $tab_products; ?>" ><i class="fa fa-gavel bigger-130"></i> <span class="bigger-110"> Products</span></a></li>
            <li><a href="<?php echo $tab_selling; ?>" ><i class="fa fa-gavel bigger-130"></i> <span class="bigger-110"> My Selling</span></a></li>
            <li class="active"><a href="<?php echo $tab_listing_profiles; ?>" ><i class="fa fa-list bigger-130"></i> <span class="bigger-110"> Listings</span></a></li>
            <li><a href="<?php echo $tab_ebay_account; ?>" ><i class="fa fa-user bigger-130"></i> <span class="bigger-110"> Account</span></a></li>
            <li><a href="<?php echo $tab_ebay_syncronize; ?>" ><i class="fa fa-tasks bigger-130"></i> <span class="bigger-110"> Tasks</span></a></li>
            <li><a href="<?php echo $tab_ebay_templates; ?>" ><i class="fa fa-desktop bigger-130"></i> <span class="bigger-110"> Templates</span></a></li>
            <li><a href="<?php echo $tab_feedback; ?>" ><i class="fa fa-comments bigger-130"></i> <span class="bigger-110"> Feedback</span></a></li>
            <li><a href="<?php echo $tab_ebay_settings; ?>"><i class="fa fa-cogs bigger-130"></i> <span class="bigger-110"> Settings</span></a></li>
            <li><a href="<?php echo $tab_ebay_logs; ?>"><i class="fa fa-history bigger-130"></i> <span class="bigger-110"> Logs</span></a></li>
        </ul>

        <div  id="tab-content" class="tab-content pr channel">
            <div class="wizard">
                <ul class="steps">
                    <li data-target="#step1">
                        <span class="badge badge-info">1</span>Step 1<span class="chevron"></span>
                    </li>
                    <li data-target="#step2" class="active">
                        <span class="badge">2</span>Step 2<span class="chevron"></span>
                    </li>
                </ul>

                <div class="pull-right step-button-group">
                    <a class="btn btn-primary save_profile" href="#">Save</a>
                    <a class="btn btn-primary save_profile" href="#" data-action="close">Save & Close</a>
                    <a href="<?php echo $cancel; ?>" class="btn btn-danger">Close</a>
                </div>
            </div>

            <div id="tab-listings" class="pr step-content">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> Create an eBay Listing Template</h3>
                    </div>
                    <div id="epz" class="panel-body">
                        <form id="ebay_add_list_profile" action="<?php echo $action; ?>" method="post" class="form-horizontal channel-form" >
                            <fieldset>
                            <legend>General</legend>
                            <?php if (!$is_item_conditions_disabled) { ?>
                            <div class="form-group <?php ($is_item_conditions_required)? 'required' : ''  ?> <?php (isset($errors['error_condition_id']))? 'has-error' : ''  ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="Specifying Item Condition for the selected category.">Item Condition:</span>
                                </label>
                                <div class="col-sm-10">
                                    <select name="condition" class="form-control">
                                        <?php if (!$is_item_conditions_required) { ?>
                                        <option value="0">Select Condition</option>
                                        <?php } ?>
                                        <?php foreach ($item_conditions as $item_condition ) { ?>
                                        <option <?php if (isset($profile) && $profile['condition_id'] ==  $item_condition['ID']) { ?> selected="selected" <?php } ?>  value="<?php echo $item_condition['ID']; ?>"><?php echo $item_condition['DisplayName']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php if (isset($errors['error_condition_id'])) { ?>
                                    <div class="text-danger"><?php echo $errors['error_condition_id']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="(Maximum characters: 1000)">Condition Description:</span>
                                </label>
                                <div class="col-sm-10">
                                    <textarea rows="2" cols="20" class="form-control" name="condition_description"><?php if (isset($profile)) { ?><?php echo $profile['condition_description']; ?><?php } ?></textarea>
                                </div>
                            </div>

                            <?php } ?>


                            <div class="form-group required <?php (isset($errors['error_listing_type']))? 'has-error' : ''  ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">Selling Format:</span>
                                </label>
                                <div class="col-sm-10">
                                    <select name="listing_type" class="form-control">
                                        <option value="Chinese" <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese') { ?> selected="selected" <?php } ?>>Auction</option>
                                        <option value="FixedPriceItem" <?php if (isset($profile) && $profile['listing_type'] ==  'FixedPriceItem') { ?> selected="selected" <?php } ?>>Fixed Price</option>
                                    </select>
                                    <?php if (isset($errors['error_listing_type'])) { ?>
                                    <div class="text-danger"><?php echo $errors['error_listing_type']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group required <?php (isset($errors['error_duration']))? 'has-error' : ''  ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">Duration:</span>
                                </label>
                                <div class="col-sm-10">

                                    <?php if (!empty($listing_auction_durations)) { ?>
                                    <select name="auction_duration" class="form-control <?php if (isset($profile) && $profile['listing_type'] ==  'FixedPriceItem') { ?> hide <?php } ?>" >
                                    <?php foreach ($listing_auction_durations as $listing_duration) { ?>
                                    <option value="<?php echo $listing_duration['code']; ?>" <?php if (isset($profile) && $profile['duration'] ==  $listing_duration['code']) { ?> selected="selected" <?php } ?> ><?php echo $listing_duration['label']; ?></option>
                                    <?php } ?>
                                    </select>
                                    <?php } ?>

                                    <?php if (!empty($listing_buyitnow_durations)) { ?>
                                    <select name="fixed_duration" class="form-control <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' || !isset($profile)) { ?> hide <?php } ?>">
                                    <?php foreach ($listing_buyitnow_durations as $listing_duration) { ?>
                                    <option value="<?php echo $listing_duration['code']; ?>" <?php if (isset($profile) && $profile['duration'] ==  $listing_duration['code']) { ?> selected="selected" <?php } ?>><?php echo $listing_duration['label']; ?></option>
                                    <?php } ?>
                                    </select>
                                    <?php } ?>

                                    <?php if (empty($listing_auction_durations) && empty($listing_buyitnow_durations)) { ?>
                                    <select name="fixed_duration" class="form-control <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' || !isset($profile)) { ?> hide <?php } ?>">
                                    <option value="GTC">Good 'Til Cancelled</option>
                                    </select>


                                    <select name="auction_duration" class="form-control <?php if (isset($profile) && $profile['listing_type'] ==  'FixedPriceItem') { ?> hide <?php } ?>">
                                    <option value="GTC">Good 'Til Cancelled</option>
                                    </select>
                                    <?php } ?>

                                    <?php if (isset($errors['error_duration'])) { ?>
                                        <div class="text-danger"><?php echo $errors['error_duration']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>


                            <div id="auction-binp-group" class="form-group <?php if ((isset($profile) && $profile['listing_type'] ==  'FixedPriceItem')) { ?> hide <?php } ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">Starting Price:</span>
                                </label>
                                <div class="col-sm-10">

                                    <div>
                                        <div>
                                            <input type="radio" name="start_price_option" value="product_price" checked="checked">
                                            <label> Use the product price.</label>
                                        </div>

                                        <div>
                                            <input type="radio" name="start_price_option" value="price_extra" <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['price_option'] ==  'price_extra') { ?>checked="checked"<?php } ?>>
                                            <label> Modify the product price.</label>
                                            <div <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['price_option'] ==  'price_extra') { ?>class="group"<?php } else { ?> class="group hide" <?php } ?> id="use-price-extra">
                                            <select name="startprice_plus_minus">
                                                <option value="plus" <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['price_option'] ==  'price_extra' && $profile['price_extra']['price_plus_minus'] ==  'plus') { ?>selected="selected"<?php } ?>>Plus</option>
                                                <option value="minus" <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['price_option'] ==  'price_extra' && $profile['price_extra']['price_plus_minus'] ==  'minus') { ?>selected="selected"<?php } ?>>Minus</option>
                                            </select>
                                            $:<input type="text" name="startprice_modify_amount" <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['price_option'] ==  'price_extra' && !empty($profile['price_extra']['price_modify_amount'])) { ?>value="<?php echo $profile['price_extra']['price_modify_amount']; ?>"<?php } ?>>
                                            %:<input type="text" name="startprice_modify_percent" <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['price_option'] ==  'price_extra' && !empty($profile['price_extra']['price_modify_amount'])) { ?>value="<?php echo $profile['price_extra']['price_modify_percent']; ?>"<?php } ?>>
                                        </div>
                                    </div>

                                    <div>
                                        <input type="radio" name="start_price_option" value="custom_price" <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['price_option'] ==  'custom_price') { ?>checked="checked"<?php } ?>>
                                        <label> Use a custom price.</label>
                                        <div <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['price_option'] ==  'custom_price') { ?>class="group"<?php } else { ?> class="group hide" <?php } ?> id="use-custom-price">
                                        $:<input type="text" name="startprice_custom_amount" <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['price_option'] ==  'custom_price' && !empty($profile['custom_price']['price_custom_amount'])) { ?>value="<?php echo $profile['custom_price']['price_custom_amount']; ?>"<?php } ?>>
                                    </div>
                                </div>
                                <div class="space-6"></div>
                                <div>
                                    <input type="checkbox" name="bin_enabled" <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['bin_enabled']) { ?> checked="checked" <?php } ?>>
                                    <label> Yes, use a Buy It Now Price.</label>
                                    <div <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['bin_enabled']) { ?> class="group" <?php } else {?> class="group hide" <?php } ?> id="bin_options">
                                    <div>
                                        <input type="radio" name="bin_option" value="product_price" checked="checked" <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['bin_enabled'] && $profile['bin_option'] ==  'product_price') { ?>checked="checked"<?php } ?>>
                                        <label> Use the product price.</label>
                                    </div>

                                    <div>
                                        <input type="radio" name="bin_option" value="price_extra" <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['bin_enabled'] && $profile['bin_option'] ==  'price_extra') { ?>checked="checked"<?php } ?>>
                                        <label> Modify the product price.</label>
                                        <div <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['bin_enabled'] && $profile['bin_option'] ==  'price_extra') { ?>class="group"<?php } else { ?> class="group hide" <?php } ?> id="use-bin-price-extra">
                                        <select name="bin_plus_minus">
                                            <option value="plus" <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['bin_enabled'] && $profile['bin_option'] == 'price_extra' && $profile['bin_price_extra']['price_plus_minus'] == 'plus') { ?>selected="selected"<?php } ?>>Plus</option>
                                            <option value="minus" <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['bin_enabled'] && $profile['bin_option'] == 'price_extra' && $profile['bin_price_extra']['price_plus_minus'] == 'minus') { ?>selected="selected"<?php } ?>>Minus</option>
                                        </select>
                                        $:<input type="text" name="bin_modify_amount" <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['bin_enabled'] && $profile['bin_option'] ==  'price_extra' && !empty($profile['bin_price_extra']['price_modify_amount'])) { ?>value="<?php echo $profile['bin_price_extra']['price_modify_amount']; ?>"<?php } ?>>
                                        %:<input type="text" name="bin_modify_percent" <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['bin_enabled'] && $profile['bin_option'] ==  'price_extra' && !empty($profile['bin_price_extra']['price_modify_percent'])) { ?>value="<?php echo $profile['bin_price_extra']['price_modify_percent']; ?>"<?php } ?>>
                                    </div>
                                </div>

                                <div>
                                    <input type="radio" name="bin_option" value="custom_price" <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['bin_enabled'] && $profile['bin_option'] ==  'custom_price') { ?>checked="checked"<?php } ?>>
                                    <label> Use a custom price.</label>
                                    <div <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['bin_enabled'] && $profile['bin_option'] == 'custom_price') { ?>class="group"<?php } else { ?> class="group hide" <?php } ?> id="use-bin-custom-price">
                                    $:<input type="text" name="bin_custom_amount" <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' && $profile['bin_enabled'] && $profile['bin_option'] ==  'custom_price' && !empty($profile['bin_custom_price']['price_custom_amount'])) { ?>value="<?php echo $profile['bin_custom_price']['price_custom_amount']; ?>"<?php } ?>>
                                </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                                </div>
                            </div>


                            <div id="fixedprice-binp-group" class="form-group <?php if (isset($profile) && $profile['listing_type'] ==  'Chinese' || !isset($profile)) { ?> hide <?php } ?> <?php (isset($errors['error_fixed_price_option']))? 'has-error' : ''  ?>"">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">Buy It Now Price:</span>
                                </label>
                                <div class="col-sm-10">
                                    <div>
                                        <input type="radio" name="fixed_price_option" value="product_price" <?php if (isset($profile) && $profile['listing_type'] ==  'FixedPriceItem' && $profile['price_option'] ==  'product_price') { ?>checked="checked"<?php } ?>>
                                        <label> Use the product price.</label>
                                    </div>

                                    <div>
                                        <input type="radio" name="fixed_price_option" value="price_extra" <?php if (isset($profile) && $profile['listing_type'] ==  'FixedPriceItem' && $profile['price_option'] ==  'price_extra') { ?>checked="checked"<?php } ?>>
                                        <label> Modify the product price.</label>
                                        <div <?php if (isset($profile) && $profile['listing_type'] ==  'FixedPriceItem' && $profile['price_option'] ==  'price_extra') { ?>class="group"<?php } else { ?> class="group hide" <?php } ?> id="use-fixedprice-extra">
                                        <select name="fixedprice_plus_minus">
                                            <option value="plus" <?php if (isset($profile) && $profile['listing_type'] ==  'FixedPriceItem' && $profile['price_option'] ==  'price_extra' && $profile['price_extra']['price_plus_minus'] ==  'plus') { ?>selected="selected"<?php } ?>>Plus</option>
                                            <option value="minus" <?php if (isset($profile) && $profile['listing_type'] ==  'FixedPriceItem' && $profile['price_option'] ==  'price_extra' && $profile['price_extra']['price_plus_minus'] ==  'minus') { ?>selected="selected"<?php } ?>>Minus</option>
                                        </select>
                                        $:<input type="text" name="fixedprice_modify_amount" <?php if (isset($profile) && $profile['listing_type'] ==  'FixedPriceItem' && $profile['price_option'] ==  'price_extra' && !empty($profile['price_extra']['price_modify_amount'])) { ?>value="<?php echo $profile['price_extra']['price_modify_amount']; ?>"<?php } ?>>
                                        %:<input type="text" name="fixedprice_modify_percent" <?php if (isset($profile) && $profile['listing_type'] ==  'FixedPriceItem' && $profile['price_option'] ==  'price_extra' && !empty($profile['price_extra']['price_modify_percent'])) { ?>value="<?php echo $profile['price_extra']['price_modify_percent']; ?>"<?php } ?>>
                                    </div>
                                    </div>
                                        <div>
                                            <input type="radio" name="fixed_price_option" value="custom_price" <?php if (isset($profile) && $profile['listing_type'] ==  'FixedPriceItem' && $profile['price_option'] ==  'custom_price') { ?>checked="checked"<?php } ?>>
                                            <label> Use a custom price.</label>
                                            <div <?php if (isset($profile) && $profile['listing_type'] == 'FixedPriceItem' && $profile['price_option'] == 'custom_price') { ?>class="group"<?php } else { ?> class="group hide" <?php } ?> id="use-custom-fixedprice">
                                            $:<input type="text" name="fixedprice_custom_amount" <?php if (isset($profile) && $profile['listing_type'] ==  'FixedPriceItem' && $profile['price_option'] ==  'custom_price' && !empty($profile['custom_price']['price_custom_amount'])) { ?>value="<?php echo $profile['custom_price']['price_custom_amount']; ?>"<?php } ?>>
                                        </div>
                                    </div>

                                <?php if (isset($errors['error_fixed_price_option'])) { ?>
                                    <div class="text-danger"><?php echo $errors['error_fixed_price_option']; ?></div>
                                <?php } ?>
                            </div>

                        </div>

                        <div class="form-group <?php (isset($errors['error_qty_to_sell']))? 'has-error' : ''  ?>">
                            <label class="col-sm-2 control-label">
                                <span title="" data-toggle="tooltip" data-original-title="">Quantity to sell:</span>
                            </label>
                            <div class="col-sm-10">

                                <div>
                                    <input type="radio" name="qty_to_sell" value="1" <?php if(isset($profile) && isset($profile['qty_to_sell']) && $profile['qty_to_sell'] > 0){ ?> checked="checked" <?php } ?>>
                                    <label>Product quantity</label>
                                </div>


                                <div>
                                    <input type="radio" name="qty_to_sell" value="0" <?php if(isset($profile) && isset($profile['qty_to_sell']) && $profile['qty_to_sell'] < 1){ ?> checked="checked" <?php } ?>>
                                    <label>Max Quantity</label>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <input type="text" name="max_qty_to_sell" value="<?php echo (isset($profile) && isset($profile['max_qty_to_sell']))? $profile['max_qty_to_sell'] : '' ?>" class="form-control eint"><br><span class="help">the quantity value will be less or equal with this value.</span>
                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($errors['error_qty_to_sell'])) { ?>
                                <div class="text-danger"><?php echo $errors['error_qty_to_sell']; ?></div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                <span title="" data-toggle="tooltip" data-original-title="">Private Listing:</span>
                            </label>
                            <div class="col-sm-10">
                                <div>
                                    <input type="checkbox" name="private_listing" value="1" <?php if(isset($profile) && isset($profile['private_listing']) && $profile['private_listing'] > 0){ ?> checked="checked" <?php } ?>>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                <span title="" data-toggle="tooltip" data-original-title="List items with attributes (Item specifics max name length: 40 and max value length: 50)">Enable Attributes:</span>
                            </label>
                            <div class="col-sm-10">
                                <div>
                                    <input type="checkbox" name="attributes_enabled" value="1" <?php if(isset($profile) && isset($profile['attributes_enabled']) && $profile['attributes_enabled'] > 0){ ?> checked="checked" <?php } ?>>
                                </div>
                            </div>
                        </div>

                        <div class="form-group <?php (isset($errors['error_subtitle']))? 'has-error' : ''  ?>">
                            <label class="col-sm-2 control-label">
                                <span title="" data-toggle="tooltip" data-original-title="Subtitle to use in addition to the title max: 55 chars">SubTitle:</span>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="subtitle" <?php if (isset($profile) && isset($profile['subtitle'])) { ?> value="<?php echo $profile['subtitle']; ?>" <?php } ?>>
                                <?php if (isset($errors['error_subtitle'])) { ?>
                                <div class="text-danger"><?php echo $errors['error_subtitle']; ?></div>
                                <?php } ?>
                            </div>
                        </div>

                        <?php if (isset($templates) && !empty($templates)) { ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                <span title="" data-toggle="tooltip" data-original-title="In order to use these options you must first select a template.">Template:</span>
                            </label>
                            <div class="col-sm-10">
                                <select name="template_id" class="form-control">
                                    <option id="">Select template</option>
                                    <?php foreach ($templates as $template ) { ?>
                                    <option <?php if (isset($profile) && $profile['template_id'] ==  $template['id']) { ?> selected="selected" <?php } ?> value="<?php echo $template['id']; ?>"><?php echo $template['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <?php } ?>
                        </fieldset>




<?php if(!empty($category_specifics)) { ?>

<fieldset>
    <legend>Item Specifics</legend>

    <?php if($ean_required) { ?>
    <div class="form-group required">
        <label class="col-sm-2 control-label">
            <span title="" data-toggle="tooltip" data-original-title="">EAN</span>
        </label>
        <div class="col-sm-10">
            <select name="item_specific[ean_required]" class="form-control i-s-s">
                <?php if(!empty($unavailable_value)) { ?>
                <option
                <?php if(isset($profile['item_specifics']['ean_required']) && $profile['item_specifics']['ean_required'] == 'v.' . $unavailable_value['value'] ) { ?>selected="selected"<?php } ?>
                value="v.<?php echo $unavailable_value['value'];?>"><?php echo $unavailable_value['label'];?></option>
                <?php } else { ?>
                <option value="0">None</option>
                <?php } ?>
                <optgroup label="Product">
                    <?php foreach($product_fields as $product_field) { ?>
                    <option
                    <?php if(isset($profile['item_specifics']['ean_required']) && $profile['item_specifics']['ean_required'] == 'p.' . $product_field['id']) { ?>selected="selected"<?php } ?>
                    value="p.<?php echo $product_field['id'];?>">
                    <?php echo $product_field['name'];?>
                    </option>
                    <?php } ?>
                </optgroup>
                <?php if(!empty($attributes)) { ?>
                <optgroup label="Attributes">
                    <?php foreach($attributes as $attribute) { ?>
                    <option
                    <?php if(isset($profile['item_specifics']['ean_required']) && $profile['item_specifics']['ean_required'] == 'a.' . $attribute['attribute_id']) { ?>selected="selected"<?php } ?>
                    value="a.<?php echo $attribute['attribute_id'];?>"><?php echo $attribute['name'];?></option>
                    <?php } ?>
                </optgroup>
                <?php } ?>
            </select>
        </div>
    </div>
    <?php } ?>

    <?php if($upc_required) { ?>
    <div class="form-group required">
        <label class="col-sm-2 control-label">
            <span title="" data-toggle="tooltip" data-original-title="">UPC</span>
        </label>
        <div class="col-sm-10">
            <select name="item_specific[upc_required]" class="form-control i-s-s">
                <?php if(!empty($unavailable_value)) { ?>
                <option
                <?php if(isset($profile['item_specifics']['upc_required']) && $profile['item_specifics']['upc_required'] == 'v.' . $unavailable_value['value'] ) { ?>selected="selected"<?php } ?>
                value="v.<?php echo $unavailable_value['value'];?>"><?php echo $unavailable_value['label'];?></option>
                <?php } else { ?>
                <option value="0">None</option>
                <?php } ?>
                <optgroup label="Product">
                    <?php foreach($product_fields as $product_field) { ?>
                    <option
                    <?php if(isset($profile['item_specifics']['upc_required']) && $profile['item_specifics']['upc_required'] == 'p.' . $product_field['id']) { ?>selected="selected"<?php } ?>
                    value="p.<?php echo $product_field['id'];?>">
                    <?php echo $product_field['name'];?>
                    </option>
                    <?php } ?>
                </optgroup>
                <?php if(!empty($attributes)) { ?>
                <optgroup label="Attributes">
                    <?php foreach($attributes as $attribute) { ?>
                    <option
                    <?php if(isset($profile['item_specifics']['upc_required']) && $profile['item_specifics']['upc_required'] == 'a.' . $attribute['attribute_id']) { ?>selected="selected"<?php } ?>
                    value="a.<?php echo $attribute['attribute_id'];?>"><?php echo $attribute['name'];?></option>
                    <?php } ?>
                </optgroup>
                <?php } ?>
            </select>
        </div>
    </div>
    <?php } ?>


    <?php foreach($category_specifics as $key => $category_specific) { ?>
    <?php if($category_specific['req']) { ?>
    <div class="form-group required <?php (isset($errors['error_item_specific'][$category_specific['name']]))? 'has-error' : ''  ?>">
        <label class="col-sm-2 control-label">
            <span title="" data-toggle="tooltip" data-original-title=""><?php echo $category_specific['name']?></span>
        </label>
        <div class="col-sm-10">
            <select name="item_specific[<?php echo $category_specific['name']?>]" class="form-control i-s-s">
                <?php if(!empty($unavailable_value)) { ?>
                <option
                <?php if(isset($profile['item_specifics'][$category_specific['name']]) && $profile['item_specifics'][$category_specific['name']] == 'v.' . $unavailable_value['value'] ) { ?>selected="selected"<?php } ?>
                value="v.<?php echo $unavailable_value['value'];?>"><?php echo $unavailable_value['label'];?></option>
                <?php } else { ?>
                <option value="0">None</option>
                <?php } ?>
                <option
                <?php if(isset($profile['item_specifics'][$category_specific['name']]) && $profile['item_specifics'][$category_specific['name']] == '00') { ?>selected="selected"<?php } ?>
                value="00">Custom value</option>
                <optgroup label="Product">
                    <?php foreach($product_fields as $product_field) { ?>
                    <option
                    <?php if(isset($profile['item_specifics'][$category_specific['name']]) && $profile['item_specifics'][$category_specific['name']] == 'p.'.$product_field['id']) { ?>selected="selected"<?php } ?>
                    value="p.<?php echo $product_field['id'];?>">
                    <?php echo $product_field['name'];?>
                    </option>
                    <?php } ?>
                </optgroup>
                <?php if(!empty($options)) { ?>
                <optgroup label="Options">
                    <?php foreach($options as $option) { ?>
                    <option
                    <?php if(isset($profile['item_specifics'][$category_specific['name']]) && $profile['item_specifics'][$category_specific['name']] == 'o.'.$option['option_id']) { ?>selected="selected"<?php } ?>
                    value="o.<?php echo $option['option_id'];?>">
                    <?php echo $option['name'];?>
                    </option>
                    <?php } ?>
                </optgroup>
                <?php } ?>
                <?php if(!empty($attributes)) { ?>
                <optgroup label="Attributes">
                    <?php foreach($attributes as $attribute) { ?>
                    <option
                    <?php if(isset($profile['item_specifics'][$category_specific['name']]) && $profile['item_specifics'][$category_specific['name']] == 'a.'.$attribute['attribute_id']) { ?>selected="selected"<?php } ?>
                    value="a.<?php echo $attribute['attribute_id'];?>"><?php echo $attribute['name'];?></option>
                    <?php } ?>
                </optgroup>
                <?php } ?>
            </select>
            <input type="text" class="form-control c-v" name="item_specific[<?php echo $category_specific['name']?>_custom_value]"
            <?php if(isset($profile['item_specifics'][$category_specific['name']]) && isset($profile['item_specifics'][$category_specific['name'] . '_custom_value'])) { ?>value="<?php echo $profile['item_specifics'][$category_specific['name'] . '_custom_value'];?>"<?php } ?>
            <?php if(isset($profile['item_specifics'][$category_specific['name']]) && $profile['item_specifics'][$category_specific['name']] == '00') { ?>style=""<?php } else { ?>
            style="display: none;"
            <?php } ?> />

            <?php if (isset($errors['error_item_specific'][$category_specific['name']])) { ?>
            <div class="text-danger"><?php echo $errors['error_item_specific'][$category_specific['name']]; ?></div>
            <?php } ?>


        </div>
    </div>
    <?php } else { ?>
    <div class="form-group" id="is-<?php echo $key;?>" <?php if(!isset($profile['item_specifics'][$category_specific['name']])) { ?> style="display: none;" <?php } ?> >
        <label class="col-sm-2 control-label">
            <span title="" data-toggle="tooltip" data-original-title=""><?php echo $category_specific['name']?> (<a href="#is-btn-<?php echo $key;?>" class="item-specifics-remove-button">Remove</a>)</span>
        </label>
        <div class="col-sm-10">
            <select name="item_specific[<?php echo $category_specific['name']?>]" class="form-control i-s-s">
                <option value="0">None</option>
                <option
                <?php if(isset($profile['item_specifics'][$category_specific['name']]) && $profile['item_specifics'][$category_specific['name']] == '00') { ?>selected="selected"<?php } ?>
                value="00">Custom value</option>
                <optgroup label="Product">
                    <?php foreach($product_fields as $product_field) { ?>
                    <option
                    <?php if(isset($profile['item_specifics'][$category_specific['name']]) && $profile['item_specifics'][$category_specific['name']] == 'p.'.$product_field['id']) { ?>selected="selected"<?php } ?>
                    value="p.<?php echo $product_field['id'];?>">
                    <?php echo $product_field['name'];?>
                    </option>
                    <?php } ?>
                </optgroup>
                <?php if(!empty($options)) { ?>
                <optgroup label="Options">
                    <?php foreach($options as $option) { ?>
                    <option
                    <?php if(isset($profile['item_specifics'][$category_specific['name']]) && $profile['item_specifics'][$category_specific['name']] == 'o.'.$option['option_id']) { ?>selected="selected"<?php } ?>
                    value="o.<?php echo $option['option_id'];?>">
                    <?php echo $option['name'];?>
                    </option>
                    <?php } ?>
                </optgroup>
                <?php } ?>
                <?php if(!empty($attributes)) { ?>
                <optgroup label="Attributes">
                    <?php foreach($attributes as $attribute) { ?>
                    <option
                    <?php if(isset($profile['item_specifics'][$category_specific['name']]) && $profile['item_specifics'][$category_specific['name']] == 'a.'.$attribute['attribute_id']) { ?>selected="selected"<?php } ?>
                    value="a.<?php echo $attribute['attribute_id'];?>"><?php echo $attribute['name'];?></option>
                    <?php } ?>
                </optgroup>
                <?php } ?>
            </select>

            <input type="text" class="form-control c-v" name="item_specific[<?php echo $category_specific['name']?>_custom_value]"
            <?php if(isset($profile['item_specifics'][$category_specific['name']]) && isset($profile['item_specifics'][$category_specific['name'] . '_custom_value'])) { ?>value="<?php echo $profile['item_specifics'][$category_specific['name'] . '_custom_value'];?>"<?php } ?>
            <?php if(isset($profile['item_specifics'][$category_specific['name']]) && $profile['item_specifics'][$category_specific['name']] == '00') { ?>style=""<?php } else { ?>
            style="display: none;"
            <?php } ?> />
        </div>
    </div>
    <?php } ?>
    <?php } ?>

    <?php if(isset($profile['item_specifics'])) { ?>
    <?php foreach($profile['item_specifics'] as $key => $value) { ?>
    <?php if(isset($profile['item_specifics'][$key . '_type'])) { ?>
    <div class="form-group" id="is-<?php echo $key;?>">
        <label class="col-sm-2 control-label">
            <span title="" data-toggle="tooltip" data-original-title=""><?php echo $key ?> (<a href="#is-btn-<?php echo $key;?>" class="item-specifics-remove-button">Remove</a>)</span>
        </label>
        <div class="col-sm-10">
            <select name="item_specific[<?php echo $key ?>]" class="form-control i-s-s">
                <option value="0">None</option>
                <option <?php if($profile['item_specifics'][$key] == '00') { ?>selected="selected"<?php } ?> value="00">Custom value</option>
                <optgroup label="Product">
                    <?php foreach($product_fields as $product_field) { ?>
                    <option
                            <?php if($profile['item_specifics'][$key] == 'p.'.$product_field['id']) { ?>selected="selected"<?php } ?>
                            value="p.<?php echo $product_field['id'];?>">
                        <?php echo $product_field['name'];?>
                    </option>
                    <?php } ?>
                </optgroup>
                <?php if(!empty($options)) { ?>
                <optgroup label="Options">
                    <?php foreach($options as $option) { ?>
                    <option
                            <?php if($profile['item_specifics'][$key] == 'o.'.$option['option_id']) { ?>selected="selected"<?php } ?>
                            value="o.<?php echo $option['option_id'];?>">
                        <?php echo $option['name'];?>
                    </option>
                    <?php } ?>
                </optgroup>
                <?php } ?>
                <?php if(!empty($attributes)) { ?>
                <optgroup label="Attributes">
                    <?php foreach($attributes as $attribute) { ?>
                    <option
                            <?php if($profile['item_specifics'][$key] == 'a.'.$attribute['attribute_id']) { ?>selected="selected"<?php } ?>
                            value="a.<?php echo $attribute['attribute_id'];?>"><?php echo $attribute['name'];?></option>
                    <?php } ?>
                </optgroup>
                <?php } ?>
            </select>
            <input type="text" class="form-control c-v"
            <?php if(isset($profile['item_specifics'][$key. '_custom_value'])) { ?>value="<?php echo $profile['item_specifics'][$key. '_custom_value'] ?>"<?php } ?>
            name="item_specific[<?php echo $key ?>_custom_value]" <?php if($profile['item_specifics'][$key] == '00') { ?>style=""<?php } else { ?> style="display: none;" <?php } ?>/>
            <input type="hidden" name="item_specific[<?php echo $key ?>_type]" value="1"/>
        </div>
    </div>
    <?php } ?>
    <?php } ?>
    <?php } ?>

    <script type="text/html" id="custom-attribute-template">
        <div class="form-group">
            <label class="col-sm-2 control-label">
                <span title="" data-toggle="tooltip" data-original-title="">${name} (<a href="#" class="item-specifics-remove-button">Remove</a>)</span>
            </label>
            <div class="col-sm-10">
                <select name="item_specific[${name}]" class="form-control i-s-s">
                    <option value="0">None</option>
                    <option value="00">Custom value</option>
                    <optgroup label="Product">
                        <?php foreach($product_fields as $product_field) { ?>
                        <option
                        value="p.<?php echo $product_field['id'];?>">
                        <?php echo $product_field['name'];?>
                        </option>
                        <?php } ?>
                    </optgroup>
                    <?php if(!empty($options)) { ?>
                    <optgroup label="Options">
                        <?php foreach($options as $option) { ?>
                        <option
                        value="o.<?php echo $option['option_id'];?>">
                        <?php echo $option['name'];?>
                        </option>
                        <?php } ?>
                    </optgroup>
                    <?php } ?>
                    <?php if(!empty($attributes)) { ?>
                    <optgroup label="Attributes">
                        <?php foreach($attributes as $attribute) { ?>
                        <option
                        value="a.<?php echo $attribute['attribute_id'];?>"><?php echo $attribute['name'];?></option>
                        <?php } ?>
                    </optgroup>
                    <?php } ?>
                </select>
                <input type="text" class="form-control c-v" name="item_specific[${name}_custom_value]" style="display: none;"/>
                <input type="text" name="item_specific[${name}_type]" value="1" style="display: none;"/>
            </div>
        </div>
    </script>


    <div class="form-group">
        <div class="col-sm-2">
            <a class="btn btn-primary pull-right" id="add-custom-attribute-btn" href="#"><i class="fa fa-plus"></i> Custom Attribute</a>
        </div>
        <div class="col-sm-10 item-specifics-plus">
            <?php foreach($category_specifics as  $key => $category_specific) { ?>
            <?php if(!$category_specific['req']) { ?><a id="is-btn-<?php echo $key;?>"
            <?php if(isset($profile['item_specifics'][$category_specific['name']])) { ?> style="display: none;" <?php } ?>
            href="#is-<?php echo $key;?>" class="item-specifics-button btn btn-primary"> <i class="fa fa-plus"></i> <?php echo $category_specific['name']?></a><?php } ?>
            <?php } ?>
        </div>
    </div>
    </fieldset>
<?php } ?>







                        <fieldset>
                            <legend>Item Location</legend>
                            <div class="form-group required <?php (isset($errors['error_country']))? 'has-error' : ''  ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">Country:</span>
                                </label>
                                <div class="col-sm-10">
                                    <select name="country" class="form-control">
                                        <?php foreach ($ebay_countries as $ebay_country ) { ?>
                                        <option <?php if (isset($profile) && $profile['country'] ==  $ebay_country['code']) { ?> selected="selected" <?php } ?> value="<?php echo $ebay_country['code']; ?>"><?php echo $ebay_country['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php if (isset($errors['error_country'])) { ?>
                                    <div class="text-danger"><?php echo $errors['error_country']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group required <?php (isset($errors['error_city_state']))? 'has-error' : ''  ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">City, State:</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="city_state" <?php if (isset($profile) && isset($profile['city_state'])) { ?> value="<?php echo $profile['city_state']; ?>" <?php } ?>>
                                    <?php if (isset($errors['error_city_state'])) { ?>
                                    <div class="text-danger"><?php echo $errors['error_city_state']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group required <?php (isset($errors['error_zip_postcode']))? 'has-error' : ''  ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">Zip/Postcode:</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="zip_postcode" <?php if (isset($profile) && isset($profile['zip_postcode'])) { ?> value="<?php echo $profile['zip_postcode']; ?>" <?php } ?>>
                                    <?php if (isset($errors['error_zip_postcode'])) { ?>
                                    <div class="text-danger"><?php echo $errors['error_zip_postcode']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group required <?php (isset($errors['error_location']))? 'has-error' : ''  ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">Location:</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="location" <?php if (isset($profile) && isset($profile['location'])) { ?> value="<?php echo $profile['location']; ?>" <?php } ?>>
                                    <?php if (isset($errors['error_location'])) { ?>
                                    <div class="text-danger"><?php echo $errors['error_location']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>Payment</legend>
                            <div class="form-group required <?php (isset($errors['error_payment_method']))? 'has-error' : ''  ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">Payment Methods:</span>
                                </label>
                                <div class="col-sm-10">
                                    <?php foreach ($payment_methods as $payment_method ) { ?>
                                    <div>
                                        <input <?php if (isset($profile) && isset($profile['payment_method'][$payment_method])) { ?> checked="checked" <?php } ?> id="<?php echo $payment_method; ?>" type="checkbox" name="payment_method[]" value="<?php echo $payment_method; ?>">
                                        <label for="<?php echo $payment_method; ?>" ><?php echo $payment_method; ?></label>
                                    </div>
                                    <?php } ?>
                                    <?php if (isset($errors['error_payment_method'])) { ?>
                                    <div class="text-danger"><?php echo $errors['error_payment_method']; ?></div>
                                    <?php } ?>
                                    <?php if (isset($errors['error_paypal_payment_method'])) { ?>
                                    <div class="text-danger"><?php echo $errors['error_paypal_payment_method']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group required <?php (isset($errors['error_paypal_email']))? 'has-error' : ''  ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">PayPal Email Address:</span>
                                </label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-envelope-o"></i> </span>
                                        <input type="text" class="form-control" name="paypal_email" <?php if (isset($profile) && isset($profile['paypal_email'])) { ?> value="<?php echo $profile['paypal_email']; ?>" <?php } ?>>
                                    </div>
                                    <?php if (isset($errors['error_paypal_email'])) { ?>
                                    <div class="text-danger"><?php echo $errors['error_paypal_email']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">Checkout Instructions:</span>
                                </label>
                                <div class="col-sm-10">
                                    <textarea name="payment_instructions" class="form-control" rows="2" cols="20"><?php if (isset($profile) && isset($profile['payment_instructions'])) { ?><?php echo $profile['payment_instructions']; ?><?php } ?></textarea>
                                </div>
                            </div>

                        </fieldset>
                        <fieldset>
                            <legend>Returns</legend>

                            <div class="form-group required <?php (isset($errors['error_returns_accepted']))? 'has-error' : ''  ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">Do You Accept Returns?</span>
                                </label>
                                <div class="col-sm-10">
                                    <select name="returns_accepted" class="form-control">
                                        <?php foreach ($returns_accepted as $return_accepted) { ?>
                                        <option value="<?php echo $return_accepted['option']; ?>"  <?php if ((isset($profile['returns_accepted']) && $profile['returns_accepted'] == $return_accepted['option']) || (!isset($profile['returns_accepted']) && $return_accepted['option'] == 'ReturnsNotAccepted')) { ?> selected="selected"  <?php } ?>><?php echo $return_accepted['description']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php if (isset($errors['error_returns_accepted'])) { ?>
                                    <div class="text-danger"><?php echo $errors['error_returns_accepted']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div id="returns_within" class="form-group required <?php (isset($errors['error_returns_within']))? 'has-error' : ''  ?> <?php if (!isset($profile['returns_accepted']) || (isset($profile['returns_accepted']) && $profile['returns_accepted'] == 'ReturnsNotAccepted')) { ?> hide  <?php } ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">Item must be returned within:</span>
                                </label>
                                <div class="col-sm-10">
                                    <select name="returns_within" class="form-control">
                                        <?php foreach ($returns_within as $return_within) { ?>
                                        <option <?php if(isset($profile['returns_within']) && $profile['returns_within'] == $return_within['option']) { ?>selected="selected"<?php } ?> value="<?php echo $return_within['option']; ?>" ><?php echo $return_within['description']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php if (isset($errors['error_returns_within'])) { ?>
                                    <div class="text-danger"><?php echo $errors['error_returns_within']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php if(isset($refunds) && !empty($refunds)) { ?>
                            <div id="refunds" class="form-group required <?php if (!isset($profile['returns_accepted']) || (isset($profile['returns_accepted']) && $profile['returns_accepted'] == 'ReturnsNotAccepted')) { ?> hide  <?php } ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">Refund will be given as:</span>
                                </label>
                                <div class="col-sm-10">
                                    <select name="refunds" class="form-control">
                                        <?php foreach ($refunds as $refund) { ?>
                                        <option <?php if(isset($profile['refunds']) && $profile['refunds'] == $refund['option']) { ?>selected="selected"<?php } ?> value="<?php echo $refund['option']; ?>" ><?php echo $refund['description']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <?php }?>

                            <div id="shippingcost_paidby" class="form-group required <?php if (!isset($profile['returns_accepted']) || (isset($profile['returns_accepted']) && $profile['returns_accepted'] == 'ReturnsNotAccepted')) { ?> hide <?php } ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">Return shipping will be paid by:</span>
                                </label>
                                <div class="col-sm-10">
                                    <select name="shippingcost_paidby" class="form-control">
                                        <?php foreach ($shippingcost_paidby as $paidby) { ?>
                                        <option <?php if(isset($profile['shippingcost_paidby']) && $profile['shippingcost_paidby'] == $paidby['option']) { ?>selected="selected"<?php } ?> value="<?php echo $paidby['option']; ?>"><?php echo $paidby['description']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div id="return_policy_details" class="form-group required <?php if (!isset($profile['returns_accepted']) || (isset($profile['returns_accepted']) && $profile['returns_accepted'] == 'ReturnsNotAccepted')) { ?> hide <?php } ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">Return Policy Details:</span>
                                </label>
                                <div class="col-sm-10">
                                    <textarea name="return_policy_description" class="form-control" rows="2" cols="20"><?php if (isset($profile) && isset($profile['return_policy_description'])) { ?><?php echo $profile['return_policy_description']; ?><?php } ?></textarea>
                                </div>
                            </div>

                        </fieldset>
                        <fieldset>
                            <legend>Shipping</legend>

                            <div class="form-group required">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">Shipping Type:</span>
                                </label>
                                <div class="col-sm-10">
                                    <select name="shipping_type" class="form-control">
                                        <?php foreach ($shipping_service_types as $shipping_service_type) { ?>
                                        <option <?php if(isset($profile['shipping_type']) && $profile['shipping_type'] == $shipping_service_type['name']) { ?>selected="selected"<?php } ?> value="<?php echo $shipping_service_type['name']; ?>"><?php echo $shipping_service_type['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <input type="checkbox" name="has_international_shipping" value="1" <?php if (isset($profile['has_international_shipping']) && $profile['has_international_shipping']) { ?>checked="checked"<?php } ?> > <span> Offer International Shipping? </span>
                                </div>
                            </div>

                            <div id="package_type" class="form-group required <?php if ((isset($profile) && $profile['shipping_type'] ==  'Flat') || !isset($profile)) { ?> hide <?php } ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">Package Type:</span>
                                </label>
                                <div class="col-sm-10">
                                    <select name="shipping_package" class="form-control">
                                        <?php foreach ($shipping_packages as $shipping_package) { ?>
                                        <option <?php if(isset($profile['shipping_package']) && $profile['shipping_package'] == $shipping_package['code']) { ?>selected="selected"<?php } ?> value="<?php echo $shipping_package['code']; ?>"><?php echo $shipping_package['label']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div id="dimensions" class="form-group required <?php if ((isset($profile) && $profile['shipping_type'] ==  'Flat') || !isset($profile)) { ?> hide <?php } ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="enter dimensions in inches">Package Dimensions:</span>
                                </label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="col-md-3" style="padding: 0">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-addon">Depth</span>
                                                    <input type="text" name="dimension_depth" class="enumeric form-control" <?php if(isset($profile['dimension_depth'])) { ?>value="<?php echo $profile['dimension_depth']; ?>"<?php } ?>>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding: 1px">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-addon">Length</span>
                                                    <input type="text" name="dimension_length" class="enumeric form-control" <?php if(isset($profile['dimension_length'])) { ?>value="<?php echo $profile['dimension_length']; ?>"<?php } ?>>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding: 1px">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-addon">Width</span>
                                                    <input type="text" name="dimension_width" class="enumeric form-control" <?php if(isset($profile['dimension_width'])) { ?>value="<?php echo $profile['dimension_width']; ?>"<?php } ?>>
                                                </div>
                                            </div>
                                            <input type="checkbox" value="1" name="is_irregular_package" id="IsIrregularPackage" <?php if (isset($profile['is_irregular_package']) && $profile['is_irregular_package']) { ?>checked="checked"<?php } ?>> <span> Irregular package size </span>
                                        </div>
                                    </div>
                                    <?php if (isset($errors['error_dimension_depth'])) { ?>
                                        <div class="text-danger"><?php echo $errors['error_dimension_depth']; ?></div>
                                    <?php } ?>
                                    <?php if (isset($errors['error_dimension_width'])) { ?>
                                        <div class="text-danger"><?php echo $errors['error_dimension_width']; ?></div>
                                    <?php } ?>
                                    <?php if (isset($errors['error_dimension_length'])) { ?>
                                        <div class="text-danger"><?php echo $errors['error_dimension_length']; ?></div>
                                    <?php } ?>

                                </div>
                            </div>

                            <div id="package_weight" class="form-group required <?php if ((isset($profile) && $profile['shipping_type'] ==  'Flat') || !isset($profile)) { ?> hide <?php } ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">Package Weight:</span>
                                </label>
                                <div class="col-sm-10">
                                    <div class="col-md-3" style="padding: 0">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-addon">Major(lbs):</span>
                                            <input type="text" name="weight_major" class="enumeric form-control" <?php if(isset($profile['weight_major'])) { ?>value="<?php echo $profile['weight_major']; ?>"<?php } ?>>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-addon">Minor(oz):</span>
                                            <input type="text" name="weight_minor" class="enumeric form-control" <?php if(isset($profile['weight_minor'])) { ?>value="<?php echo $profile['weight_minor']; ?>"<?php } ?>>
                                        </div>
                                    </div>
                                    <?php if (isset($errors['error_weight_minor'])) { ?>
                                        <div class="text-danger"><?php echo $errors['error_weight_minor']; ?></div>
                                    <?php } ?>
                                    <?php if (isset($errors['error_weight_major'])) { ?>
                                        <div class="text-danger"><?php echo $errors['error_weight_major']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div id="package_costs" class="form-group <?php if ((isset($profile) && $profile['shipping_type'] ==  'Flat') || !isset($profile)) { ?> hide <?php } ?>">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="Fees a seller might assess for the shipping of the item.">Package handling fees:</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="eprice form-control" name="package_handling_fee" <?php if(isset($profile['package_handling_fee'])) { ?>value="<?php echo $profile['package_handling_fee']; ?>"<?php } ?>>
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="col-sm-2 control-label">
                                    <span title="" data-toggle="tooltip" data-original-title="">Handling Time:</span>
                                </label>
                                <div class="col-sm-10">
                                    <select name="dispatch_time" class="form-control">
                                        <?php foreach ($dispatch_times as $time) { ?>
                                        <option <?php if(isset($profile['dispatch_time']) && $profile['dispatch_time'] == $time['code']) { ?>selected="selected"<?php } ?> value="<?php echo $time['code']; ?>"><?php echo $time['label']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-10">
                                    <ul id="shipping-tabs" role="tablist" class="nav nav-tabs">
                                        <li class="active" role="presentation"><a data-toggle="tab" role="tab" href="#shipping-domestic">Domestic</a></li>
                                        <li role="presentation"><a data-toggle="tab" role="tab" href="#shipping-international" <?php if(!(isset($profile['shipping_type']) && ($profile['has_international_shipping'] || $profile['shipping_type'] == 'FlatDomesticCalculatedInternational' || $profile['shipping_type'] == 'CalculatedDomesticFlatInternational'))) { ?> style="display: none;" <?php } ?>>International</a></li>
                                    </ul>

                                    <div class="tab-content">

                                        <div id="shipping-domestic" class="tab-pane active">
                                            <table id="domestic_flat_shipping" class="table table-striped table-bordered no-footer list <?php if(!isset($profile['shipping_services']['fd']) && isset($profile['shipping_services']['cd'])) { ?>hide<?php } ?>">
                                                <thead>
                                                <tr>
                                                    <td class="center" style="width:50%">
                                                        Service
                                                    </td>
                                                    <td class="center" style="width:25%">
                                                        Cost
                                                    </td>
                                                    <td class="center" style="width:25%">
                                                        Each Additional
                                                    </td>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                <?php for($i=1; $i<=3; $i++) { ?>
                                                <tr>
                                                    <td>
                                                        <select name="ss[<?php echo $i; ?>][fd][service]">
                                                            <option value="">-- Choose a service --</option>
                                                            <?php foreach ($domestic_flat_shipping_services as $shipping_service) { ?>
                                                            <option <?php if(isset($profile['shipping_services']['fd'][$i-1]) && $profile['shipping_services']['fd'][$i-1]['service'] == $shipping_service['shipping_service']) { ?>selected="selected"<?php } ?> value="<?php echo $shipping_service['shipping_service']; ?>"><?php echo $shipping_service['description']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input class="eprice" name="ss[<?php echo $i; ?>][fd][cost]" <?php if(isset($profile['shipping_services']['fd'][$i-1]['cost'])) { ?> value="<?php echo $profile['shipping_services']['fd'][$i-1]['cost']; ?>" <?php } ?>>
                                                    </td>
                                                    <td>
                                                        <input class="eprice" name="ss[<?php echo $i; ?>][fd][each_additional]" <?php if(isset($profile['shipping_services']['fd'][$i-1]['cost'])) { ?> value="<?php echo $profile['shipping_services']['fd'][$i-1]['each_additional']; ?>" <?php } ?>>
                                                        <?php if($i == 1){ ?>
                                                        <input type="checkbox" name="ss[<?php echo $i; ?>][fd][free_shipping]" value="1" <?php if(isset($profile['shipping_services']['fd'][$i-1]['free_shipping']) && $profile['shipping_services']['fd'][$i-1]['free_shipping']) { ?> checked="checked"  <?php } ?>>
                                                        <label>Free Shipping?</label>
                                                        <?php }?>


                                                    </td>
                                                </tr>
                                                <?php }?>

                                                </tbody>
                                            </table>

                                            <table id="domestic_calculated_shipping" class="table table-striped table-bordered no-footer list <?php if(!isset($profile['shipping_services']['cd'])) { ?>hide<?php } ?>">
                                                <thead>
                                                <tr>
                                                    <td class="center" style="width:50%">
                                                        Service
                                                    </td>
                                                    <td class="center" style="width:25%">
                                                        Cost
                                                    </td>
                                                    <td class="center" style="width:25%">
                                                        Each Additional
                                                    </td>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                <?php for($i=1; $i<=3; $i++) { ?>
                                                <tr>
                                                    <td>
                                                        <select name="ss[<?php echo $i; ?>][cd][service]">
                                                            <option value="">-- Choose a service --</option>
                                                            <?php foreach ($domestic_calculated_shipping_services as $shipping_service) { ?>
                                                            <option <?php if(isset($profile['shipping_services']['cd'][$i-1]) && $profile['shipping_services']['cd'][$i-1]['service'] == $shipping_service['shipping_service']) { ?>selected="selected"<?php } ?> value="<?php echo $shipping_service['shipping_service']; ?>"><?php echo $shipping_service['description']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input class="eprice" name="ss[<?php echo $i; ?>][cd][cost]" <?php if(isset($profile['shipping_services']['cd'][$i-1]['cost'])) { ?> value="<?php echo $profile['shipping_services']['cd'][$i-1]['cost']; ?>" <?php } ?>>
                                                    </td>
                                                    <td>
                                                        <input class="eprice" name="ss[<?php echo $i; ?>][cd][each_additional]" <?php if(isset($profile['shipping_services']['cd'][$i-1]['cost'])) { ?> value="<?php echo $profile['shipping_services']['cd'][$i-1]['each_additional']; ?>" <?php } ?>>
                                                        <?php if($i == 1){ ?>
                                                        <input type="checkbox" name="ss[<?php echo $i; ?>][cd][free_shipping]" value="1" <?php if(isset($profile['shipping_services']['cd'][$i-1]['free_shipping']) && $profile['shipping_services']['cd'][$i-1]['free_shipping']) { ?> checked="checked"  <?php } ?>>
                                                        <label>Free Shipping?</label>
                                                        <?php }?>


                                                    </td>
                                                </tr>
                                                <?php }?>
                                                </tbody>
                                            </table>
                                        </div>


                                        <div id="shipping-international" class="tab-pane">
                                            <table id="international_flat_shipping" class="table table-striped table-bordered no-footer list <?php if(!isset($profile['shipping_services']['fi']) && isset($profile['shipping_services']['ci'])) { ?>hide<?php } ?>">
                                                <thead>
                                                <tr>
                                                    <td class="center" style="width:30%">
                                                        Service
                                                    </td>
                                                    <td class="center" style="width:40%">
                                                        Shipping locations
                                                    </td>
                                                    <td class="center" style="width:15%">
                                                        Cost
                                                    </td>
                                                    <td class="center" style="width:15%">
                                                        Each Additional
                                                    </td>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                <?php for($i=1; $i<=3; $i++) { ?>
                                                <tr>
                                                    <td>
                                                        <select name="ss[<?php echo $i; ?>][fi][service]">
                                                            <option value="">-- Choose a service --</option>
                                                            <?php foreach ($international_flat_shipping_services as $shipping_service) { ?>
                                                            <option <?php if(isset($profile['shipping_services']['fi'][$i-1]) && $profile['shipping_services']['fi'][$i-1]['service'] == $shipping_service['shipping_service']) { ?>selected="selected"<?php } ?> value="<?php echo $shipping_service['shipping_service']; ?>"><?php echo $shipping_service['description']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <?php foreach ($ebay_shipping_locations as $ebay_shipping_location ) { ?>
				            		<span class="sippl">
				            			<input type="checkbox" value="1" <?php if(isset($profile['shipping_services']['fi'][$i-1]['alocations']) && in_array($ebay_shipping_location['code'], $profile['shipping_services']['fi'][$i-1]['alocations'])) { ?> checked="checked" <?php } ?>  name="ss[<?php echo $i; ?>][fi][shipping_location][<?php echo $ebay_shipping_location['code']; ?>]"><label><?php echo $ebay_shipping_location['name']; ?></label>
				            		</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <input class="eprice" name="ss[<?php echo $i; ?>][fi][cost]" <?php if(isset($profile['shipping_services']['fi'][$i-1]['cost'])) { ?> value="<?php echo $profile['shipping_services']['fi'][$i-1]['cost']; ?>" <?php } ?>>
                                                    </td>
                                                    <td>
                                                        <input class="eprice" name="ss[<?php echo $i; ?>][fi][each_additional]" <?php if(isset($profile['shipping_services']['fi'][$i-1]['cost'])) { ?> value="<?php echo $profile['shipping_services']['fi'][$i-1]['each_additional']; ?>" <?php } ?>>
                                                        <?php if($i == 1){ ?>
                                                        <input type="checkbox" name="ss[<?php echo $i; ?>][fi][free_shipping]" value="1" <?php if(isset($profile['shipping_services']['fi'][$i-1]['free_shipping']) && $profile['shipping_services']['fi'][$i-1]['free_shipping']) { ?> checked="checked"  <?php } ?>>
                                                        <label>Free Shipping?</label>
                                                        <?php }?>
                                                    </td>
                                                </tr>
                                                <?php }?>
                                                </tbody>
                                            </table>

                                            <table id="international_calculated_shipping" class="table table-striped table-bordered no-footer list <?php if(!isset($profile['shipping_services']['ci'])) { ?>hide<?php } ?>">
                                                <thead>
                                                <tr>
                                                    <td class="center" style="width:30%">
                                                        Service
                                                    </td>
                                                    <td class="center" style="width:70%">
                                                        Shipping locations
                                                    </td>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                <?php for($i=1; $i<=3; $i++) { ?>
                                                <tr>
                                                    <td>
                                                        <select name="ss[<?php echo $i; ?>][ci][service]">
                                                            <option value="">-- Choose a service --</option>
                                                            <?php foreach ($international_calculated_shipping_services as $shipping_service) { ?>
                                                            <option <?php if(isset($profile['shipping_services']['ci'][$i-1]) && $profile['shipping_services']['ci'][$i-1]['service'] == $shipping_service['shipping_service']) { ?>selected="selected"<?php } ?> value="<?php echo $shipping_service['shipping_service']; ?>"><?php echo $shipping_service['description']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <input type="hidden" class="eprice" name="ss[<?php echo $i; ?>][ci][cost]" value="0">
                                                        <input type="hidden" class="eprice" name="ss[<?php echo $i; ?>][ci][each_additional]" value="0">
                                                    </td>
                                                    <td>
                                                        <?php foreach ($ebay_shipping_locations as $ebay_shipping_location ) { ?>
				            		<span class="sippl">
				            			<input type="checkbox" value="1" <?php if(isset($profile['shipping_services']['ci'][$i-1]['alocations']) && in_array($ebay_shipping_location['code'], $profile['shipping_services']['ci'][$i-1]['alocations'])) { ?> checked="checked"  <?php } ?> name="ss[<?php echo $i; ?>][ci][shipping_location][<?php echo $ebay_shipping_location['code']; ?>]"><label><?php echo $ebay_shipping_location['name']; ?></label>
				            		</span>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php }?>
                                                </tbody>
                                            </table>
                                        </div>


                                    </div>


                                </div>
                            </div>
                        </fieldset>

                        </form>
                    </div>
                </div>
            </div>


        </div>


    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="add_custom_attribute_modal" tabindex="-1" role="dialog" aria-labelledby="add_custom_attribute_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="add_custom_attribute_label">Add custom attribute</h4>
            </div>
            <div class="modal-body">
                <p>Write your attribute name</p>
                <div class="row">
                <div class="form-group">
                    <div class="col-sm-10">
                        <input  id="custom-attribute-name" type="text" class="form-control">
                        <div class="text-danger"></div>
                    </div>
                </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="save_custom_attribute" type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-plus"></i> Add</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>



<?php echo $footer; ?>


