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
            <li class="active"><a href="<?php echo $tab_products; ?>" ><i class="fa fa-tags bigger-130"></i> <span class="bigger-110"> Products</span></a></li>
            <li><a href="<?php echo $tab_selling; ?>" ><i class="fa fa-gavel bigger-130"></i> <span class="bigger-110"> My Selling</span></a></li>
            <li><a href="<?php echo $tab_listing_profiles; ?>" ><i class="fa fa-list bigger-130"></i> <span class="bigger-110"> Listings</span></a></li>
            <li><a href="<?php echo $tab_ebay_account; ?>" ><i class="fa fa-user bigger-130"></i> <span class="bigger-110"> Account</span></a></li>
            <li><a href="<?php echo $tab_ebay_syncronize; ?>" ><i class="fa fa-tasks bigger-130"></i> <span class="bigger-110"> Tasks</span></a></li>
            <li><a href="<?php echo $tab_ebay_templates; ?>" ><i class="fa fa-desktop bigger-130"></i> <span class="bigger-110"> Templates</span></a></li>
            <li><a href="<?php echo $tab_feedback; ?>" ><i class="fa fa-comments bigger-130"></i> <span class="bigger-110"> Feedback</span></a></li>
            <li><a href="<?php echo $tab_ebay_settings; ?>"><i class="fa fa-cogs bigger-130"></i> <span class="bigger-110"> Settings</span></a></li>
            <li><a href="<?php echo $tab_ebay_logs; ?>"><i class="fa fa-history bigger-130"></i> <span class="bigger-110"> Logs</span></a></li>
        </ul>

        <div  id="tab-products" class="tab-content pr">


            <div id="asb" class="well action-bar hide" style="margin-bottom: 1px;">
                <div class="row">
                <div class="filters">
                    <div class="section asf template hide">
                        <div class="col-md-4 fn">
                            <div class="col-md-3 fno">
                                    <select class="aso form-control" name="o_filter" >
                                        <option value="AND">AND</option>
                                        <option value="OR">OR</option>
                                        <option value="NOT">NOT</option>
                                    </select>
                            </div>
                            <div class="col-md-9 fnn">

                                    <select class="asfn form-control col-sm-10" name="asearch_filter">
                                        <option value="pn" selected="selected" data-filter="pn_filter" data-type="input">Product name</option>
                                        <option value="pm" data-filter="brands_filter" data-type="select">Manufacturer</option>
                                        <option value="pc" data-filter="category_filter" data-type="category">Category</option>
                                        <option value="elt" data-filter="elt_filter" data-type="select">Listing Type</option>
                                        <option value="em" data-filter="em_filter" data-type="select">Market</option>
                                        <option value="lp" data-filter="lp_filter" data-type="select">Listing Profile</option>
                                        <option value="m" data-filter="m_filter" data-type="input">Model</option>
                                        <option value="p" data-filter="price-range" data-type="range">Price</option>
                                        <option value="q" data-filter="qty" data-type="range">Quantity</option>
                                        <option value="eid" data-filter="eid_filter" data-type="input">eBay ID</option>
                                        <option value="s" data-filter="s_filter" data-type="select">Status</option>
                                    </select>

                            </div>
                        </div>

                        <div class="col-md-6 fv">
                            <div class="row">
                            <input type="text" class="aff pn_filter form-control">
                            <input type="text" class="aff m_filter  form-control hide">
                            <input type="text" class="aff eid_filter form-control hide">
                            <input type="text" class="aff eet_filter form-control date hide">

                             <div class="dtselect aff category_filter hide">

                                 <div class="input-group">
                                     <input type="text" class="form-control" placeholder="Browse for category"  readonly>
                                     <span class="input-group-btn">
                                        <button class="btn btn-success browse-category" type="button">Browse</button>
                                     </span>
                                 </div>
                                <input class="category_id" type="hidden" value="">
                              </div>

                            <div class="aff price-range hide">
                                <label class="col-md-6">
                                    <input type="text" class="from form-control" placeholder="From">
                                </label>
                                <label class="col-md-6">
                                    <input type="text" name="p_filter_to" class="to form-control" placeholder="To">
                                </label>
                            </div>

                            <div class="aff qty hide">
                                <label class="col-md-6">
                                    <input type="text" class="from form-control" placeholder="From">
                                </label>
                                <label class="col-md-6">
                                    <input type="text" class="to form-control" placeholder="To">
                                </label>
                            </div>

                            <select class="aff s_filter form-control hide">
                                <option value="1">Enable</option>
                                <option value="0">Disable</option>
                            </select>
                            <select class="aff elt_filter form-control hide">
                                <option value="FixedPriceItem">Fixed Price</option>
                                <option value="Chinese">Auction</option>
                            </select>
                            <select class="aff em_filter form-control hide">
                                <?php foreach ($ebay_sites as $ebay_site ) { ?>
                                <option value="<?php echo $ebay_site['id']; ?>" <?php if ($ebay_site['selected']) { ?> selected="selected"  <?php } ?> ><?php echo $ebay_site['name']; ?></option>
                                <?php } ?>
                            </select>
                            <select class="aff brands_filter form-control hide">
                                <?php foreach ($manufacturers as $manufacturer ) { ?>
                                <option value="<?php echo $manufacturer['manufacturer_id']; ?>" ><?php echo $manufacturer['name']; ?></option>
                                <?php } ?>
                            </select>
                            <select class="aff lp_filter form-control hide">
                                <?php foreach ($profiles as $profile ) { ?>
                                <option value="<?php echo $profile['id']; ?>" ><?php echo $profile['name']; ?>  (<?php echo $profile['site_name']?>)</option>
                                <?php } ?>
                            </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-danger rf-btn"><i class="fa fa-times"></i></button>
                            <button class="btn btn-primary nf-btn"><i class="fa fa-plus-circle"></i></button>
                        </div>

                    </div>
                </div>
                <div style="margin-top: 15px">
                    <div class="col-md-12">
                        <button class="btn btn-primary ase"><i class="fa fa-search"></i> Search</button>
                        <button class="btn btn-danger asc"><i class="fa fa-trash-o"></i> Clear</button>
                        <button id="ss-button" class="sw-mode-search btn btn-success"><i class="fa fa-retweet" aria-hidden="true"></i> Simple Search</button>
                    </div>
                </div>
                </div>


                <!-- Modal -->
                <div class="modal fade" id="category_modal" tabindex="-1" role="dialog" aria-labelledby="category_modal_label">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="category_modal_label">Select Category</h4>
                            </div>
                            <div class="modal-body" style="overflow: auto; height: 400px;">
                                <div id="category_tree" data-service="<?php echo $categories_service; ?>"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default choose-category" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="well action-bar action-bar" style="margin-bottom: 0;">
                <div class="row">
                <div class="col-sm-3 horizontal action-divider">
                    <div class="input-group">
                        <select id="action-s" class="form-control">
                            <option value="0">-- Choose an eBay Action --</option>
                            <option value="1">Add/Update/Relist</option>
                            <option value="2">Update Price and Stock</option>
                            <option value="3">End Items</option>
                            <option value="4">Syncronize</option>
                        </select>
                        <span class="input-group-btn">
                            <button id="confirm" class="btn btn-primary">Confirm</button>
                            <img id="process_wait" src="view/image/channels/ajax-loader.gif" width="16" height="16" />
                        </span>
                    </div>
                </div>

                <div  class="col-sm-7">

                    <form action="" class="form-inline">
                        <div id="ssb">
                        <div class="form-group">
                            <select name="search_filter" class="form-control">
                                <option value="fa">Find in all</option>
                                <option value="pn">Product name</option>
                                <option value="elt">Listing Type</option>
                                <option value="em">Market</option>
                                <option value="lp">Listing Profile</option>
                                <option value="m">Model</option>
                                <option value="p">Price</option>
                                <option value="q">Quantity</option>
                                <option value="eid">eBay ID</option>
                                <option value="s">Status</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" name="all_filter" class="form-control">
                            <input type="text" name="pn_filter" class="form-control hide">
                            <input type="text" name="m_filter" class="form-control hide">
                            <input type="text" name="eid_filter" class="form-control hide">
                            <input type="text" name="eet_filter" class="form-control date hide">
                            <span class="price-range hide">
                                From:<input type="text" name="p_filter_from" class="form-control"> To:<input type="text" name="p_filter_to" class="form-control">
                            </span>
                            <span class="qty hide">
                                From:<input type="text" name="q_filter_from" class="form-control"> To:<input type="text" name="q_filter_to" class="form-control">
                            </span>

                            <select name="s_filter" class="form-control hide">
                                <option value="1">Enable</option>
                                <option value="0">Disable</option>
                            </select>
                            <select name="elt_filter" class="form-control hide">
                                <option value="FixedPriceItem">Fixed Price</option>
                                <option value="Chinese">Auction</option>
                            </select>
                            <select name="em_filter" class="form-control hide">
                                <?php foreach ($ebay_sites as $ebay_site ) { ?>
                                <option value="<?php echo $ebay_site['id']; ?>" <?php if ($ebay_site['selected']) { ?> selected="selected"  <?php } ?> ><?php echo $ebay_site['name']; ?></option>
                                <?php } ?>
                            </select>
                            <select name="lp_filter" class="form-control hide">
                                <?php foreach ($profiles as $profile ) { ?>
                                <option value="<?php echo $profile['id']; ?>" ><?php echo $profile['name']; ?>  (<?php echo $profile['site_name']?>)</option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button id="search-btn" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                            <button id="clear-btn" class="btn btn-danger"><i class="fa fa-trash-o"></i> Clear</button>
                            <button id="as-button" class="sw-mode-search btn btn-success"><i class="fa fa-retweet" aria-hidden="true"></i> Advanced Search</button>
                        </div>
                        </div>

                    </form>

                </div>


                <div class="rpagination col-sm-2">
                    <div class="pull-right">
                        <form action="" class="form-inline">
                            <div class="form-group">
                                View
                            </div>
                            <div class="form-group">
                                <select name="pag" class="form-control"><option value="10">10</option></select>
                            </div>
                        </form>
                    </div>
                </div>



                </div>
            </div>


            <table border="0" id="products_table" class="table table-striped table-bordered no-footer list" data-url="<?php echo $search_url; ?>" data-ebay-action="<?php echo $ebay_action_url; ?>" width="100%">
                <thead>
                <tr class="header-t">
                    <th><input id="selectall" type="checkbox"></th>
                    <th>Image</th>
                    <th class="center">Product Name</th>
                    <th>Model</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>eBay ID</th>
                    <th>eBay Time Left</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>



        </div>


    </div>
</div>


<div class="modal fade" id="list_items_modal" action="<?php echo $ebay_list_action?>" tabindex="-1" role="dialog" aria-labelledby="test-list_items_modal_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="test-list_items_modal_label">List <span class="pcnt"></span> Product(s) on eBay (Step <span class="pstep">1</span> of 4)</h4>
            </div>
            <div class="modal-body">
                <div class="step" id="step1">
                    <div class="form-container" >
                        <form class="form-horizontal">
                            <fieldset>
                                <p>Choose options below to list your products on eBay.</p>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">
                                        <span title="" data-toggle="tooltip" data-original-title="">Listing Template:</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <select class="lps form-control">
                                            <option value="">--- Choose a Listing Template ---</option>
                                            <?php if(!empty($profiles)) { ?>
                                            <?php foreach($profiles as $profile) { ?>
                                            <option value="<?php echo $profile['id']?>"><?php echo $profile['name']?> (<?php echo $profile['site_name']?>)</option>
                                            <?php }?>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">
                                        <span title="" data-toggle="tooltip" data-original-title="">Language:</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <select class="ll form-control">
                                            <?php foreach($languages as $language) { ?>
                                            <option value="<?php echo $language['language_id']?>"><?php echo $language['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                <button type="button" class="btn btn-success back-step disabled"><i class="fa fa fa-arrow-left"></i> Back</button>
                <button type="button" class="btn btn-success next-step">Next <i class="fa fa-arrow-right"></i></button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="end_list_items_modal" action="<?php echo $ebay_list_action?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">End My Listing Early</h4>
            </div>
            <div class="modal-body">
                <div class="select_option">
                    <p>Select a reason for ending your listing early. The reason will appear on the Closed Item page. </p>
                    <div class="form-container" >
                        <table class="table">
                            <tr>
                                <td>
                                <input type="radio" id="endreason2" value="2" name="endreason"><label for="endreason2">The item is no longer available for sale.</label><br>
                                <input type="radio" id="endreason4" value="4" name="endreason"><label for="endreason4">There was an error in the listing.</label><br>
                                <input type="radio" id="endreason3" value="3" name="endreason"><label for="endreason3">There was an error in starting price, Buy It Now price, or reserve price.</label><br>
                                <input type="radio" id="endreason1" value="1" name="endreason"><label for="endreason1">The item was lost or broken.</label><br>
                                <input type="radio" id="endreason5" value="5" name="endreason"><label for="endreason5">The item is no longer available. Remove link from opencart.</label><br>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="progress_form hide">
                    <div>
                        <p>You can close this window and you will not interrupt the update process. You will receive an email with details as soon as the listing is finished</p>
                        <div id="end-progressbar" class="progress">
                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success end-item">End My Listing</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sync_list_items_modal" action="<?php echo $ebay_list_action?>" tabindex="-1" role="dialog" aria-labelledby="sync_items_modal_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="sync_items_modal_label">Syncronize with eBay</h4>
            </div>
            <div class="modal-body">
                <p>You can close this window and you will not interrupt the syncronize process. You will receive an email with details as soon as the listing is finished</p>
                <div id="sync-progressbar" class="progress">
                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update_inv_list_items_modal" action="<?php echo $ebay_list_action?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Revise Inventory Status</h4>
            </div>
            <div class="modal-body">
                <p>You can close this window and you will not interrupt the update process. You will receive an email with details as soon as the listing is finished</p>
                <div id="update-inventory-progressbar" class="progress">
                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>




<?php echo $footer; ?>

