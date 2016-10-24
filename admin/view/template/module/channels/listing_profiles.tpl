<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?php echo $edit_listing_profile; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i> Create eBay Listing</a>
            </div>
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

        <div  id="tab-content" class="tab-content pr">

            <div class="tab-content pr" id="tab-listing-profile">

                <?php if($profiles_count > 0) { ?>
                    <table id="profile_list_table" class="table table-striped table-bordered no-footer list" data-url="<?php echo $profile_list_url; ?>"
                        delete-url="<?php echo $delete_listing_profile; ?>"
                        action-url="<?php echo $action_listing_profile; ?>"
                        mark-url="<?php echo $markdef_listing_profile; ?>"
                        edit-url="<?php echo $edit_listing_profile; ?>" width="100%">
                        <thead>
                            <tr>
                            <td class="center">Profile Name</td>
                            <td class="left">Summary</td>
                            <td class="left">Products</td>
                            <td class="left">Default</td>
                            <td class="left"></td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                <?php } else { ?>
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <h2>No eBay listing templates have been created.</h2>
                        <a href="<?php echo $edit_listing_profile; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i> Create an eBay Listing Template</a>
                    </div>
                </div>
                <?php } ?>
            </div>

        </div>


    </div>
</div>

<div class="modal fade" id="delete-dialog" action="<?php echo $delete_listing_profile?>" tabindex="-1" role="dialog" aria-labelledby="delete-dialog-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="delete-dialog-label">Are you sure?</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this listing profile?</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="delete-listing-profile-btn" class="btn btn-danger">Yes, remove</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rename-dialog" action="<?php echo $rename_listing_profile?>" tabindex="-1" role="dialog" aria-labelledby="rename-dialog-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="rename-dialog-label">Rename</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="listing-profile-name" class="form-control-label">Rename listing profile to:</label>
                        <input type="text" class="form-control" id="listing-profile-name">
                        <div class="text-danger"></div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" id="rename-listing-profile-btn" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="move-dialog" action="<?php echo $move_listing_profile?>" tabindex="-1" role="dialog" aria-labelledby="move-dialog-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="move-dialog-label">Move</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="listing-profiles-list" class="form-control-label">Move <strong class="text-success">listing profile</strong> to:</label>
                        <select id="listing-profiles-list" type="text" class="form-control" action="<?php echo $listing_profile_list?>">
                            <option>Loading...</option>
                        </select>
                        <div class="text-danger"></div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" id="move-listing-profile-btn" class="btn btn-success">Move</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>


<?php echo $footer; ?>


