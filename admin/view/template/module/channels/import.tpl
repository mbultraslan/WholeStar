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
            <li><a href="<?php echo $tab_selling; ?>" ><i class="fa fa-gavel bigger-130"></i> <span class="bigger-110"> My Selling</span></a></li>
            <li><a href="<?php echo $tab_listing_profiles; ?>" ><i class="fa fa-list bigger-130"></i> <span class="bigger-110"> Listings</span></a></li>
            <li><a href="<?php echo $tab_ebay_account; ?>" ><i class="fa fa-user bigger-130"></i> <span class="bigger-110"> Account</span></a></li>
            <li class="active"><a href="<?php echo $tab_ebay_syncronize; ?>" ><i class="fa fa-tasks bigger-130"></i> <span class="bigger-110"> Tasks</span></a></li>
            <li><a href="<?php echo $tab_ebay_templates; ?>" ><i class="fa fa-desktop bigger-130"></i> <span class="bigger-110"> Templates</span></a></li>
            <li><a href="<?php echo $tab_feedback; ?>" ><i class="fa fa-comments bigger-130"></i> <span class="bigger-110"> Feedback</span></a></li>
            <li><a href="<?php echo $tab_ebay_settings; ?>"><i class="fa fa-cogs bigger-130"></i> <span class="bigger-110"> Settings</span></a></li>
            <li><a href="<?php echo $tab_ebay_logs; ?>"><i class="fa fa-history bigger-130"></i> <span class="bigger-110"> Logs</span></a></li>
        </ul>

        <div  id="tab-content" class="tab-content pr">

            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                <i class="fa fa-download fa-fw"></i> Orders Import
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <button data-action="<?php echo $ebay_action_url; ?>" id="import_orders_btn" class="btn btn-primary">Import Orders</button>
                                    </label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="numberOfDays">
                                            <?php for($i=1; $i<=30; $i++) { ?>
                                            <option value="<?php echo $i;?>"><?php echo $i;?> Day(s)</option>
                                            <?php } ?>
                                        </select>
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
                                <i class="fa fa-download fa-fw"></i> Import Products
                            </a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <button data-action="<?php echo $ebay_action_url; ?>" id="import_products_btn" class="btn btn-primary">Import Products</button>
                                    </label>
                                    <div class="col-sm-10">
                                        <div class="alert alert-info" role="alert">This will import products.</div>
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
                                <i class="fa fa-download fa-fw"></i> Bayer Feedback Import
                            </a>
                        </h4>
                    </div>
                    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                        <div class="panel-body">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <button data-action="<?php echo $ebay_action_url; ?>" id="import_feedback_btn" class="btn btn-primary">Import Feedback</button>
                                    </label>
                                    <div class="col-sm-10">
                                        <div class="alert alert-info" role="alert">This will import feedback from buyer.</div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="syncronize_items_tab">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSyncronize_items" aria-expanded="false" aria-controls="collapseThree">
                                <i class="fa fa-refresh fa-fw"></i> Syncronize eBay items with OC products
                            </a>
                        </h4>
                    </div>
                    <div id="collapseSyncronize_items" class="panel-collapse collapse" role="tabpanel" aria-labelledby="syncronize_items_tab">
                        <div class="panel-body">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <button data-action="<?php echo $ebay_action_url; ?>" id="sync_items_btn" class="btn btn-primary">Start Syncronize</button>
                                    </label>
                                    <div class="col-sm-10">
                                        <div class="alert alert-info" role="alert">This will syncronize eBay items with store products.</div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="update_inventory_tab">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseUpdate_inventory" aria-expanded="false" aria-controls="collapseThree">
                                <i class="fa fa-upload fa-fw"></i> Update eBay items inventory
                            </a>
                        </h4>
                    </div>
                    <div id="collapseUpdate_inventory" class="panel-collapse collapse" role="tabpanel" aria-labelledby="update_inventory_tab">
                        <div class="panel-body">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <button data-action="<?php echo $ebay_action_url; ?>" id="update_inventory_btn" class="btn btn-primary">Update Inventory</button>
                                    </label>
                                    <div class="col-sm-10">
                                        <div class="alert alert-info" role="alert">This will update eBay item price and quantity from OC product.</div>
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
                                <i class="fa fa-download fa-fw"></i> Import Links
                            </a>
                        </h4>
                    </div>
                    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                        <div class="panel-body">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <button data-action="<?php echo $ebay_action_url; ?>" id="import_links_btn" class="btn btn-primary">Import Links</button>
                                    </label>
                                    <div class="col-sm-10">
                                        <div class="alert alert-info" role="alert">This will import links eBay->OC based on (title, sku, openbay).</div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
</div>
<?php echo $footer; ?>


