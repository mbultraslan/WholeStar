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
            <li><a href="<?php echo $tab_selling; ?>" ><i class="fa fa-shopping-cart bigger-130"></i> <span class="bigger-110"> My Selling</span></a></li>
            <li><a href="<?php echo $tab_listing_profiles; ?>" ><i class="fa fa-list bigger-130"></i> <span class="bigger-110"> Listings</span></a></li>
            <li><a href="<?php echo $tab_ebay_account; ?>" ><i class="fa fa-user bigger-130"></i> <span class="bigger-110"> Account</span></a></li>
            <li><a href="<?php echo $tab_ebay_syncronize; ?>" ><i class="fa fa-tasks bigger-130"></i> <span class="bigger-110"> Tasks</span></a></li>
            <li><a href="<?php echo $tab_ebay_templates; ?>" ><i class="fa fa-desktop bigger-130"></i> <span class="bigger-110"> Templates</span></a></li>
            <li><a href="<?php echo $tab_feedback; ?>" ><i class="fa fa-comments bigger-130"></i> <span class="bigger-110"> Feedback</span></a></li>
            <li class="active"><a href="<?php echo $tab_ebay_settings; ?>"><i class="fa fa-cogs bigger-130"></i> <span class="bigger-110"> Settings</span></a></li>
            <li><a href="<?php echo $tab_ebay_logs; ?>"><i class="fa fa-history bigger-130"></i> <span class="bigger-110"> Logs</span></a></li>
        </ul>

        <div  id="tab-content" class="tab-content pr">

            <div class="tab-content pr">


                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    <i class="fa fa-puzzle-piece fa-fw"></i> General settings
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="if is longer 80 chars">Enable truncate title</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <label><input type="checkbox" id="truncate_title_enabled" <?php if($settings['truncate_title_enabled']) { ?> checked="checked" <?php } ?> /></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="end item action">End Item</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <label><input id="end_item_on_delete_enabled" <?php if($settings['end_item_on_delete_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox"> On delete product</label>
                                            </div>
                                            <div class="checkbox">
                                                <label><input id="end_item_out_stock_enabled" <?php if($settings['end_item_out_stock_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox"> On out of stock</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="will revise enabled attributes">Revise Item</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <label><input id="revise_title_enabled" <?php if($settings['revise_title_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox"> Title</label>
                                            </div>
                                            <div class="checkbox">
                                                <label><input id="revise_description_enabled" <?php if($settings['revise_description_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox"> Description</label>
                                            </div>

                                            <div class="checkbox">
                                                <label><input id="revise_sku_enabled" <?php if($settings['revise_sku_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox"> SKU</label>
                                            </div>

                                            <div class="checkbox">
                                                <label><input id="revise_price_enabled" <?php if($settings['revise_price_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox"> Price</label>
                                            </div>

                                            <div class="checkbox">
                                                <label><input id="revise_quantity_enabled" <?php if($settings['revise_quantity_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox">  Quantity</label>
                                            </div>

                                            <div class="checkbox">
                                                <label><input id="revise_paypalemail_enabled" <?php if($settings['revise_paypalemail_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox"> PayPal Email</label>
                                            </div>

                                            <div class="checkbox">
                                                <label><input id="revise_dispatch_time_max_enabled" <?php if($settings['revise_dispatch_time_max_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox">
                                             Time Max</label>
                                            </div>
                                            <div class="checkbox">
                                                <label><input id="revise_listing_duration_enabled" <?php if($settings['revise_listing_duration_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox">
                                            Listing Duration</label>
                                            </div>

                                            <div class="checkbox">
                                                <label><input id="revise_listing_type_enabled" <?php if($settings['revise_listing_type_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox">
                                            Listing Type</label>
                                            </div>

                                            <div class="checkbox">
                                                <label><input id="revise_postal_code_enabled" <?php if($settings['revise_postal_code_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox">
                                            Location</label>
                                            </div>

                                            <div class="checkbox">
                                                <label><input id="revise_primary_category_enabled" <?php if($settings['revise_primary_category_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox">
                                            Primary Category</label>
                                            </div>

                                            <div class="checkbox">
                                                <label><input id="revise_payment_methods_enabled" <?php if($settings['revise_payment_methods_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox">
                                            Payment Methods</label>
                                            </div>

                                            <div class="checkbox">
                                                <label><input id="revise_picture_details_enabled" <?php if($settings['revise_picture_details_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox">
                                            Picture Details</label>
                                            </div>

                                            <div class="checkbox">
                                                <label><input id="revise_item_specifics_enabled" <?php if($settings['revise_item_specifics_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox">
                                            Item Specifics</label>
                                            </div>

                                            <div class="checkbox">
                                                <label><input id="revise_condition_enabled" <?php if($settings['revise_condition_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox">
                                            Condition</label>
                                            </div>

                                            <div class="checkbox">
                                                <label><input id="revise_return_policy_enabled" <?php if($settings['revise_return_policy_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox">
                                            Return Policy</label>
                                            </div>

                                            <div class="checkbox">
                                                <label><input id="revise_shipping_details_enabled" <?php if($settings['revise_shipping_details_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox">
                                            Shipping Details</label>
                                            </div>

                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="Will be use special price in price calculation">Use special price</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <label><input id="special_price_enabled" <?php if($settings['special_price_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox"></label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="Will be use taxes in price calculation">Use Taxes</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <label><input id="general_use_taxes_enabled" <?php if($settings['general_use_taxes_enabled']) { ?> checked="checked" <?php } ?> value="1" type="checkbox"></label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="Will list product with one image">List only product cover image</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <label><input id="general_list_product_cover_image" <?php if($settings['general_list_product_cover_image']) { ?> checked="checked" <?php } ?> value="1" type="checkbox"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="image{_type}.jpg">Image Type</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <input type="text" id="image_type" class="form-control" value="<?php echo $settings['image_type']; ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="HTTP Basic Authentication credentials">Security</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <label>Username</label>
                                            <input type="text" id="http_basic_autentification_username" class="form-control" value="<?php echo $settings['http_basic_autentification_username']; ?>" />
                                            <label>Password</label>
                                            <input type="password" id="http_basic_autentification_password" class="form-control" value="<?php echo $settings['http_basic_autentification_password']; ?>" />
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="Enable for hosts with apache timeout">Import prevent timeout</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <label><input id="general_prevent_timeout" <?php if($settings['general_prevent_timeout']) { ?> checked="checked" <?php } ?> value="1" type="checkbox"></label>
                                            </div>
                                        </div>
                                    </div>




                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="will rebuild cache for all broken images">Rebuild Image Cache</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <a href="<?php echo $generate_images_cache; ?>" class="btn btn-success cache_rebuild">Rebuild Image Cache</a>
                                        </div>
                                    </div>

                                    <div class="well">
                                        <div class="row">
                                            <button data-action="<?php echo $save_general_settings ?>" data-callback="save_general_settings" data-loading="Saving..." data-title="Save" class="btn btn-primary pull-right save-settings-btn" type="button">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingTwo">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <i class="fa fa-clock-o fa-fw"></i> Cron Settings
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                            <div class="panel-body">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <button href="<?php echo $test_cron_url ?>" id="test_cron_btn" class="btn btn-success cache_rebuild">Test Cron</button>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="alert alert-info" role="alert">
                                                <p>
                                                    If you want to use the scheduling features, your server has to support <strong>Cron</strong>  functions.
                                                    <br><br>
                                                    The cron daemon is a long running process that executes commands at specific dates and times. By clicking on the button <strong>Test Cron</strong> you can check if your server supports <strong>Cron</strong>
                                                    commands. If your server <strong>does</strong> support Cron jobs, but this script shows that the feature is disabled, this means that the automatic creation of Cron commands is disabled.
                                                    In that case, you can use this URL string - <strong>{path_to_your_site}/?route=ebay_channel/notification/cron</strong> - in your hosting config panel every 5 minutes or add <strong>0,5,10,15,20,25,30,35,40,45,50,55 * * * * php /absolute/path/to/opencart/ecron/cron.php</strong>.
                                                    <br><br>
                                                    <a class="btn btn-primary" id="alternativ_cron_btn" href="#">My server does not support cron jobs</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="in minutes from 5 to 1440">OC->eBay Stock and Price Update</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="col-sm-1">
                                                <input id="stock_and_price_interval" class="form-control" type="number" min="5" max="1440" value="<?php echo $settings['stock_and_price_interval'] ?>">
                                                <div class="checkbox">
                                                    <label><input type="checkbox" id="stock_and_price_interval_enabled" <?php if($settings['stock_and_price_interval_enabled']) { ?> checked="checked" <?php } ?> /> Enabled</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="in minutes from 5 to 1440">Import/Update Orders</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="col-sm-1">
                                                <input class="form-control" id="orders_update_interval" min="5" max="1440" value="<?php echo $settings['orders_update_interval'] ?>">
                                                <div class="checkbox">
                                                    <label><input type="checkbox" id="orders_update_enabled" <?php if($settings['orders_update_enabled']) { ?> checked="checked" <?php } ?> /> Enabled</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="in hours">Syncronize eBay items with OC products</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="col-sm-1">
                                                <select class="form-control" id="syncronize_interval">
                                                    <option value="360" <?php if($settings['syncronize_interval'] == 360) { ?> selected="selected" <?php } ?>>6h</option>
                                                    <option value="720" <?php if($settings['syncronize_interval'] == 720) { ?> selected="selected" <?php } ?>>12h</option>
                                                    <option value="1440" <?php if($settings['syncronize_interval'] == 1440) { ?> selected="selected" <?php } ?>>24h</option>
                                                </select>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" id="syncronize_enabled" <?php if($settings['syncronize_enabled']) { ?> checked="checked" <?php } ?> /> Enabled</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="will import or update existing products in OC from eBay">Import/Update Products From eBay</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="col-sm-1">
                                                <select class="form-control" id="import_items_interval">
                                                    <option value="360" <?php if($settings['import_items_interval'] == 360) { ?> selected="selected" <?php } ?>>6h</option>
                                                    <option value="720" <?php if($settings['import_items_interval'] == 720) { ?> selected="selected" <?php } ?>>12h</option>
                                                    <option value="1440" <?php if($settings['import_items_interval'] == 1440) { ?> selected="selected" <?php } ?>>24h</option>
                                                </select>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" id="import_items_enabled" <?php if($settings['import_items_enabled']) { ?> checked="checked" <?php } ?> /> Enabled</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="will import buyers feedback in OC every 6h">Import buyers feedback</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="col-sm-1">
                                                <div class="checkbox">
                                                    <label><input type="checkbox" id="feedback_enabled" <?php if($settings['feedback_enabled']) { ?> checked="checked" <?php } ?> /> Enabled</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="well">
                                        <div class="row">
                                            <button data-action="<?php echo $save_cron_settings ?>" data-callback="save_cron_settings" data-loading="Saving..." data-title="Save" class="btn btn-primary pull-right save-settings-btn" type="button">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingThree">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    <i class="fa fa-download fa-fw"></i> Orders Import Settings
                                </a>
                            </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                            <div class="panel-body">
                                <form class="form-horizontal">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="">Store</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="os-store">
                                                <option value="0">Default</option>
                                                <?php foreach ($stores as $store) { ?>
                                                <?php if ($store['store_id'] == $settings['order_store_id']) { ?>
                                                <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                                                <?php } else { ?>
                                                <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="">Customer Group</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="os-customer-group">
                                                <?php foreach ($customer_groups as $customer_group) { ?>
                                                <?php if ($customer_group['customer_group_id'] == $settings['order_customer_group_id']) { ?>
                                                <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                                                <?php } else { ?>
                                                <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="Orders are new when younger than this limit (in days). Default is 1 day" data-toggle="tooltip" data-original-title="">New order age limit</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="os-import_older_orders">
                                                <option value="0">None</option>
                                                <?php for($i=1; $i<=7; $i++) { ?>
                                                <option value="<?php echo $i;?>" <?php echo ($settings['order_import_older'] == $i)? 'selected="selected"' : '' ?>><?php echo $i;?> Day(s)</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="Will subtract products stock for imported order items" data-toggle="tooltip" data-original-title="">Subtract Stock</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control"  id="os-import_subtract_stock">
                                                <option value="0" <?php echo ($settings['order_subtract_stock'] == 0)? 'selected="selected"' : '' ?>>Disable</option>
                                                <option value="1" <?php echo ($settings['order_subtract_stock'] == 1)? 'selected="selected"' : '' ?>>By Product Subtract Stock Option</option>
                                                <option value="2" <?php echo ($settings['order_subtract_stock'] == 2)? 'selected="selected"' : '' ?>>Get last stock from eBay</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="Will subtract products stock for imported order items" data-toggle="tooltip" data-original-title="">Tax Class</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control"  id="os-order_tax_class_id">
                                                <option value="0">None</option>
                                                <?php foreach ($tax_classes as $tax_class) { ?>
                                                <?php if ($tax_class['tax_class_id'] == $settings['order_tax_class_id']) { ?>
                                                <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                                                <?php } else { ?>
                                                <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="Will import customers">Import customers</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <div class="checkbox">
                                                <label><input type="checkbox" id="os-customer_import_enabled" value="1" <?php echo (!empty($settings['customer_import_enabled']))? 'checked="checked"' : '' ?> /></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="">Hook URL</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <input type="text" id="os-hook-url" class="form-control" value="<?php echo $settings['order_hook_url']; ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="Will import only orders with Completed status">Import only Completed orders</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <div class="checkbox">
                                                <label><input type="checkbox" id="os-import-compleded" value="1" <?php echo (!empty($settings['import_compleded']))? 'checked="checked"' : '' ?> /></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="">Default Status</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="os-default_status">
                                                <?php foreach($order_statuses as $order_status) { ?>
                                                <option value="<?php echo $order_status['order_status_id'] ?>"  <?php echo ($order_status['order_status_id'] == $settings['default_status'])? 'selected="selected"' : '' ?>  ><?php echo $order_status['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="">Completed Status</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="os-completed_status">
                                                <?php foreach($order_statuses as $order_status) { ?>
                                                <option value="<?php echo $order_status['order_status_id'] ?>" <?php echo ($order_status['order_status_id'] == $settings['completed_status'])? 'selected="selected"' : '' ?>><?php echo $order_status['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="">Shipped Status</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="os-shipped_status">
                                                <?php foreach($order_statuses as $order_status) { ?>
                                                <option value="<?php echo $order_status['order_status_id'] ?>" <?php echo ($order_status['order_status_id'] == $settings['shipped_status'])? 'selected="selected"' : '' ?>><?php echo $order_status['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="">InProcess Status</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="os-inprocess_status">
                                                <?php foreach($order_statuses as $order_status) { ?>
                                                <option value="<?php echo $order_status['order_status_id'] ?>" <?php echo ($order_status['order_status_id'] == $settings['inprocess_status'])? 'selected="selected"' : '' ?>><?php echo $order_status['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="">Active Status</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="os-active_status">
                                                <?php foreach($order_statuses as $order_status) { ?>
                                                <option value="<?php echo $order_status['order_status_id'] ?>" <?php echo ($order_status['order_status_id'] == $settings['active_status'])? 'selected="selected"' : '' ?>><?php echo $order_status['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="">Cancelled Status</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="os-cancelled_status">
                                                <?php foreach($order_statuses as $order_status) { ?>
                                                <option value="<?php echo $order_status['order_status_id'] ?>" <?php echo ($order_status['order_status_id'] == $settings['cancelled_status'])? 'selected="selected"' : '' ?>><?php echo $order_status['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="">Cancel Pending Status</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="os-cancelpending_status">
                                                <?php foreach($order_statuses as $order_status) { ?>
                                                <option value="<?php echo $order_status['order_status_id'] ?>" <?php echo ($order_status['order_status_id'] == $settings['cancelpending_status'])? 'selected="selected"' : '' ?> ><?php echo $order_status['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="">Inactive Status</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control"  id="os-inactive_status">
                                                <?php foreach($order_statuses as $order_status) { ?>
                                                <option value="<?php echo $order_status['order_status_id'] ?>" <?php echo ($order_status['order_status_id'] == $settings['inactive_status'])? 'selected="selected"' : '' ?> ><?php echo $order_status['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="">Invalid Status</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control"  id="os-invalid_status">
                                                <?php foreach($order_statuses as $order_status) { ?>
                                                <option value="<?php echo $order_status['order_status_id'] ?>" <?php echo ($order_status['order_status_id'] == $settings['invalid_status'])? 'selected="selected"' : '' ?> ><?php echo $order_status['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="well">
                                        <div class="row">
                                            <button data-action="<?php echo $save_orders_settings ?>" data-callback="save_orders_settings" data-loading="Saving..." data-title="Save" class="btn btn-primary pull-right save-settings-btn" type="button">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingFour">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    <i class="fa fa-download fa-fw"></i> Products Import Settings
                                </a>
                            </h4>
                        </div>
                        <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                            <div class="panel-body">
                                <form class="form-horizontal">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="For slow hosts set less entries per page">Import Entries per page</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="product_entries_per_page">
                                                <option value="5" <?php if($settings['product_entries_per_page'] == 5) { ?> selected="selected" <?php } ?>>5</option>
                                                <option value="10" <?php if($settings['product_entries_per_page'] == 10) { ?> selected="selected" <?php } ?>>10</option>
                                                <option value="20" <?php if($settings['product_entries_per_page'] == 20) { ?> selected="selected" <?php } ?>>20</option>
                                                <option value="50" <?php if($settings['product_entries_per_page'] == 50) { ?> selected="selected" <?php } ?>>50</option>
                                                <option value="100" <?php if($settings['product_entries_per_page'] == 100) { ?> selected="selected" <?php } ?>>100</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="">Import new products</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <label><input id="import_new_products" <?php if($settings['import_new_products']) { ?> checked="checked" <?php } ?> value="1" type="checkbox"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="">Update product fields</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="update_product_fields">
                                                <option value="all">All fields</option>
                                                <option value="stock_and_price" <?php if($settings['update_only_stock_and_price']) { ?> selected="selected" <?php } ?>>Stock and Price</option>
                                                <option value="price" <?php if($settings['update_only_price']) { ?> selected="selected" <?php } ?>>Price</option>
                                                <option value="stock" <?php if($settings['update_only_stock']) { ?> selected="selected" <?php } ?>>Stock</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="">Disable ended Items</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <label><input id="disable_ended_items" <?php if($settings['disable_ended_items']) { ?> checked="checked" <?php } ?> value="1" type="checkbox"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="">Import with categories</span>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="product_import_categories">
                                                <option value="0" <?php if($settings['product_import_categories'] == 0) { ?> selected="selected" <?php } ?>>None</option>
                                                <option value="1" <?php if($settings['product_import_categories'] == 1) { ?> selected="selected" <?php } ?>>eBay Categories</option>
                                                <option value="2" <?php if($settings['product_import_categories'] == 2) { ?> selected="selected" <?php } ?>>eBay Store Categories</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="">Import variations</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <label><input id="product_import_variations" <?php if($settings['product_import_variations']) { ?> checked="checked" <?php } ?> value="1" type="checkbox"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="">Import item Specifics</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <label><input id="product_import_specifics" <?php if($settings['product_import_specifics']) { ?> checked="checked" <?php } ?> value="1" type="checkbox"></label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="Filter items by ebay site">Import site filter</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <?php $i=0; foreach($sites as $site) { ?>
                                            <div class="col-sm-<?php echo ($i++ %2==0)? '3' : '9';  ?>">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="site-filter" value="<?php echo $site['id']; ?>" type="checkbox" <?php if(!empty($settings['product_filter_site']) && in_array($site['id'], $settings['product_filter_site'])) { ?> checked="checked" <?php } ?>>
                                                        <?php echo $site['name']; ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <span title="" data-toggle="tooltip" data-original-title="Filter item description html">Item description filter</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <table id="tag-filters" class="table">
                                                <tr>
                                                    <td>Tag(ex: h1, div)</td>
                                                    <td>Attribute(ex: class, id)</td>
                                                    <td>Value(ex: description, item-desc)</td>
                                                    <td><button class="btn btn-primary btn-xs add-tag"><i class="fa fa-plus-circle"></i></button></td>
                                                </tr>
                                                <?php if(!empty($settings['product_filter_tags'])) { ?>
                                                <?php foreach($settings['product_filter_tags'] as $filter) { ?>
                                                <tr class="tag-row"><td><input class="tag-name" type="text" value="<?php echo $filter['tag']  ?>"></td><td><input class="tag-attribute" type="text" value="<?php echo $filter['attribute']  ?>"></td><td><input class="tag-value" type="text" value="<?php echo $filter['value']  ?>"></td><td><button class="button remove-tag">X</button></td></tr>
                                                <?php } ?>
                                                <?php } else { ?>
                                                <tr class="tag-row"><td><input class="tag-name" type="text"></td><td><input class="tag-attribute" type="text"></td><td><input class="tag-value" type="text"></td><td><button class="btn btn-danger btn-xs remove-tag"><i class="fa fa-times"></i></button></td></tr>
                                                <?php } ?>
                                            </table>
                                        </div>
                                    </div>



                                    <div class="well">
                                        <div class="row">
                                            <button data-action="<?php echo $save_products_settings ?>" data-callback="save_products_settings" data-loading="Saving..." data-title="Save" class="btn btn-primary pull-right save-settings-btn" type="button">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="heading5">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                    <i class="fa fa-magnet fa-fw"></i> Category mapping
                                </a>
                            </h4>
                        </div>
                        <div id="collapse5" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading5">
                            <div id="category-mapping" class="panel-body" data-url="<?php echo $get_category_mapping ?>">
                                <h2>Please wait loading...</h2>
                            </div>
                        </div>
                    </div>



                </div>



            </div>

        </div>



        <script>

            $('#category-mapping').load($('#category-mapping').attr('data-url'));

            $('a.save').click(function(){
                $('div.pr').append('<div style="position: absolute; width: 100%; height: 100%; opacity: 0.3; left: 0px; top: 0px; z-index: 1000000; background: url(view/image/channels/ajax-loader.gif) 50% 50% no-repeat black;"></div>');
                setTimeout(function(){
                    $('#settings_form').submit();
                }, 1000);
                return false;
            });

            $('a.cache_rebuild').click(function(){
                $btn = $(this);
                $label = $btn.html();
                if(!$btn.hasClass('loading')) {
                    $btn.html('Loading...');
                    $.get($btn.attr('href'), function(data){
                        if(data.status) {
                            $btn.html($label);
                        }
                    });
                }
                return false;
            });
            $service = $('#test_cron_btn').attr('href');
            $('#test_cron_btn').click(function(){
                $('#test-dialog .modal-body').load($service,function(){
                    $('#test-dialog').modal(true);
                });
                return false;
            });

            $('#alternativ_cron_btn').click(function(){
                $('#info-dialog').modal(true);
                return false;
            });




        </script>

    </div>
</div>
<?php echo $footer; ?>
<div class="modal fade" id="test-dialog" tabindex="-1" role="dialog" aria-labelledby="test-dialogLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="test-dialogLabel">Schedule options & cron jobs</h4>
            </div>
            <div class="modal-body">
                Loading...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="info-dialog" tabindex="-1" role="dialog" aria-labelledby="info-dialogLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="info-dialogLabel">Alternative cron solutions</h4>
            </div>
            <div class="modal-body">
                <p>If your server does not support cron jobs, you can try using services such as <strong>easycron.com</strong>, <strong>setcronjob.com</strong> or others which can provide you this feature.<br><br>
                    In order to do that, you have to register in the selected service and use this URL for execution:
                </p>
                <ul>
                    <li><?php echo $base_url; ?>?route=ebay_channel/notification/cron</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

