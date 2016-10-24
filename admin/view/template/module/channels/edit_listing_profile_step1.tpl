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
                    <li data-target="#step1" class="active">
                        <span class="badge badge-info">1</span>Step 1<span class="chevron"></span>
                    </li>
                    <li data-target="#step2">
                        <span class="badge">2</span>Step 2<span class="chevron"></span>
                    </li>
                </ul>
            </div>

            <div class="step-content">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> General Listing Details</h3>
                    </div>
                    <div id="epz" class="panel-body">
                        <form id="ebay_add_list_profile" action="<?php echo $action; ?>" method="post"  class="form-horizontal channel-form" >

                            <div class="form-group required <?php ($error_list_name)? 'has-error' : ''  ?>">
                                <label for="list_name" class="col-sm-2 control-label">Template Name:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" placeholder="Such as 'Phone template' - for your reference only" name="list_name" type="text" <?php if (isset($list_name)) { ?> value="<?php echo $list_name; ?>" <?php } ?>>
                                    <?php if ($error_list_name) { ?>
                                        <div class="text-danger"><?php echo $error_list_name; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="col-sm-2 control-label">Use as Default?</label>
                                <div class="col-sm-10">
                                    <input type="checkbox" name="default_list" value="1" <?php if (isset($is_default) && $is_default) { ?> checked="checked"  <?php } ?>>
                                </div>
                            </div>

                            <?php if (!$is_edit) { ?>
                            <div class="form-group required">
                                <label for="language" class="col-sm-2 control-label">List Products On:</label>
                                <div class="col-sm-10">
                                    <?php if(isset($site_id)) { ?>
                                    <select name="site_id" class="form-control">
                                        <?php foreach ($ebay_sites as $ebay_site ) { ?>
                                        <option value="<?php echo $ebay_site['id']; ?>"  <?php if($site_id == $ebay_site['id']) { ?> selected="selected" <?php } ?>><?php echo $ebay_site['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php } else { ?>
                                    <select name="site_id" class="form-control">
                                        <?php foreach ($ebay_sites as $ebay_site ) { ?>
                                        <option value="<?php echo $ebay_site['id']; ?>"   <?php if ($ebay_site['selected']) { ?> selected="selected"  <?php } ?>><?php echo $ebay_site['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php }?>
                                    <?php if ($error_site_id) { ?>
                                    <div class="text-danger"><?php echo $error_site_id; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } else { ?>
                                <input type="hidden" name="site_id" value="<?php echo $site_id; ?>"/>
                            <?php } ?>

                            <div id="pec" class="form-group required">
                                <label for="site_id" class="col-sm-2 control-label">Primary eBay Category:</label>
                                <div class="col-sm-10">
                                    <?php foreach ($ebay_sites as $ebay_site ) { ?>
                                        <div id="browse_category_<?php echo $ebay_site['id']; ?>" class="<?php if (!$ebay_site['selected']) { ?>hide<?php } ?> bcs" >
                                            <div class="input-group browse_group <?php if (!$ebay_site['has_categories']) { ?>hide<?php } ?>">
                                                <input name="ebay_category_id_<?php echo $ebay_site['id']; ?>" type="text" class="form-control" readonly="readonly" <?php if (isset($ebay_category_id)) { ?> value="<?php echo $ebay_category_id; ?>" <?php } ?>>
                                                <div class="input-group-btn">
                                                    <button id="bpec" class="btn btn-primary browse_btn" type="button">Browse</button>
                                                    <button tabindex="-1" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" type="button">
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                        <li><a href="<?php echo $import_ebay_categories; ?>" class="update_categories">Update</a></li>
                                                    </ul>
                                                </div><!-- /btn-group -->
                                            </div>
                                            <?php if ($error_ebay_category_id) { ?>
                                            <span class="label  label-danger error"><?php echo $error_ebay_category_id; ?></span>
                                            <?php } ?>
                                            <span class="label label-info hinfo"><?php if (isset($ebay_category_path)) { ?><?php echo $ebay_category_path; ?><?php } ?></span>

                                            <a href="<?php echo $import_ebay_categories; ?>" class="btn btn-primary import_categories <?php if ($ebay_site['has_categories']) { ?>hide<?php } ?>"><i class="fa fa-plus"></i> Add categories from eBay</a>

                                        </div>

                                    <?php } ?>

                                </div>


                            </div>

                            <?php if ($has_store) { ?>
                                <div id="sec" class="form-group">
                                    <label class="col-sm-2 control-label">Store eBay Category:</label>
                                    <div class="col-sm-10">
                                        <div class="input-group store_browse_group <?php if (!$has_store_categories) { ?>hide<?php } ?>">
                                            <input name="ebay_store_category_id" type="text" readonly="readonly" <?php if (isset($ebay_store_category_id)) { ?> value="<?php echo $ebay_store_category_id; ?>" <?php } ?> class="form-control">
                                            <div class="input-group-btn">
                                                <button id="bsec" class="btn browse_store_btn btn-primary" type="button">Browse</button>
                                                <button tabindex="-1" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" type="button">
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                    <li><a class="update_categories" href="<?php echo $import_ebay_store_categories; ?>">Update</a></li>
                                                </ul>
                                            </div><!-- /btn-group -->
                                        </div><!-- /input-group -->

                                        <span id="store_help" class="label label-info"></span>
                                        <span id="erroresc" class="label  label-danger"></span>

                                        <a href="<?php echo $import_ebay_store_categories; ?>" class="btn btn-primary import_store_categories <?php if ($has_store_categories) { ?>hide<?php } ?>"><i class="fa fa-plus"></i> Add store categories from eBay</a>

                                    </div>
                                </div>

                            <?php } ?>






                            <div class="well">
                                <div class="row">
                                    <button id="save_profile_step1" type="submit" class="btn btn-success pull-right">Next <i class="fa fa-arrow-right"></i></button>
                                    <a href="<?php echo $cancel; ?>" class="btn btn-danger pull-right" style="margin-right: 10px;">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>


    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="select_category_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Choose your category.</h4>
            </div>
            <div id="tree_panel" class="modal-body" data-url="<?php echo $get_ebay_categories; ?>" data-path="<?php echo $get_ebay_category_path; ?>">

            </div>
            <div class="modal-footer">
                <button id="choose_category_btn" type="button" class="add_btn btn btn-primary hide">Choose category</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="select_store_category_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Choose your store category.</h4>
            </div>
            <div id="store_tree_panel" class="modal-body" data-url="<?php echo $get_ebay_store_categories; ?>" data-path="<?php echo $get_ebay_store_category_path; ?>">

            </div>
            <div class="modal-footer">
                <button id="choose_store_category_btn" type="button" class="add_btn btn btn-primary hide">Choose category</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




<?php echo $footer; ?>


