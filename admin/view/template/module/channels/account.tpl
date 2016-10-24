<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a class="btn btn-success save" title="" data-original-title="Save Account"><i class="fa fa-save"></i> Save eBay Account</a>
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

        <?php if ($success_account_save) { ?>
        <div class="alert alert-success" role="alert">
            <?php echo $success_account_save; ?>
        </div>
        <?php } ?>



        <!-- Nav tabs -->
        <ul class="nav nav-tabs tab-size-bigger">
            <li><a href="<?php echo $tab_dashboard; ?>" ><i class="fa fa-tachometer bigger-130"></i> <span class="bigger-110"> Dashboard</span></a></li>
            <li><a href="<?php echo $tab_products; ?>" ><i class="fa fa-gavel bigger-130"></i> <span class="bigger-110"> Products</span></a></li>
            <li><a href="<?php echo $tab_selling; ?>" ><i class="fa fa-gavel bigger-130"></i> <span class="bigger-110"> My Selling</span></a></li>
            <li><a href="<?php echo $tab_listing_profiles; ?>" ><i class="fa fa-list bigger-130"></i> <span class="bigger-110"> Listings</span></a></li>
            <li class="active"><a href="<?php echo $tab_ebay_account; ?>" ><i class="fa fa-user bigger-130"></i> <span class="bigger-110"> Account</span></a></li>
            <li><a href="<?php echo $tab_ebay_syncronize; ?>" ><i class="fa fa-tasks bigger-130"></i> <span class="bigger-110"> Tasks</span></a></li>
            <li><a href="<?php echo $tab_ebay_templates; ?>" ><i class="fa fa-desktop bigger-130"></i> <span class="bigger-110"> Templates</span></a></li>
            <li><a href="<?php echo $tab_feedback; ?>" ><i class="fa fa-comments bigger-130"></i> <span class="bigger-110"> Feedback</span></a></li>
            <li><a href="<?php echo $tab_ebay_settings; ?>"><i class="fa fa-cogs bigger-130"></i> <span class="bigger-110"> Settings</span></a></li>
            <li><a href="<?php echo $tab_ebay_logs; ?>"><i class="fa fa-history bigger-130"></i> <span class="bigger-110"> Logs</span></a></li>
        </ul>

        <div  id="tab-content" class="tab-content pr">
            <form id="ebay_account" action="<?php echo $action; ?>" method="post" class="form-horizontal">
                <div class="form-group required <?php ($error_dev_id)? 'has-error' : ''  ?>">
                    <label for="dev_id" class="col-sm-2 control-label">eBay DevID:</label>
                    <div class="col-sm-10">
                        <input class="ai form-control" placeholder="eBay DevID" name="dev_id" type="text" value="<?php echo $dev_id; ?>">
                        <?php if ($error_dev_id) { ?>
                        <div class="text-danger"><?php echo $error_dev_id; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group required <?php ($error_app_id)? 'has-error' : ''  ?>">
                    <label for="app_id" class="col-sm-2 control-label">eBay AppID:</label>
                    <div class="col-sm-10">
                        <input class="ai form-control" placeholder="eBay AppID" name="app_id" type="text" value="<?php echo $app_id; ?>">
                        <?php if ($error_app_id) { ?>
                        <div class="text-danger"><?php echo $error_app_id; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group required <?php ($error_cert_id)? 'has-error' : ''  ?>">
                    <label for="cert_id" class="col-sm-2 control-label">eBay CertID:</label>
                    <div class="col-sm-10">
                        <input class="ai form-control" placeholder="eBay CertID" name="cert_id" type="text" value="<?php echo $cert_id; ?>">
                        <?php if ($error_cert_id) { ?>
                        <div class="text-danger"><?php echo $error_cert_id; ?></div>
                        <?php } ?>
                        <?php if ($status) { ?>
                        <span class="help">Status: <?php echo $status; ?></span>
                        <?php } ?>
                        <?php if ($expirationTime) { ?>
                        <span class="help">Expiration: <?php echo $expirationTime; ?></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group required <?php ($error_token)? 'has-error' : ''  ?>">
                    <label for="token" class="col-sm-2 control-label">eBay User Token:</label>
                    <div class="col-sm-10">
                        <input class="ai form-control" placeholder="eBay User Token" name="token" type="text" value="<?php echo $token; ?>">
                        <?php if ($error_token) { ?>
                        <div class="text-danger"><?php echo $error_token; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group <?php ($error_default_site)? 'has-error' : ''  ?>">
                    <label for="default_site" class="col-sm-2 control-label">Default eBay Site:</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="default_site" name="default_site">
                            <?php foreach($ebay_sites as $ebay_site) { ?>
                            <option <?php if ($ebay_site['selected']) { ?> selected="selected"  <?php } ?>	value="<?php echo $ebay_site['id']; ?>"><?php echo $ebay_site['name']; ?></option>
                            <?php } ?>
                        </select>
                        <?php if ($error_default_site) { ?>
                        <div class="text-danger"><?php echo $error_default_site; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group <?php ($error_listing_mode)? 'has-error' : ''  ?>">
                    <label for="listing_mode" class="col-sm-2 control-label">Listing Mode:</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="listing_mode" name="listing_mode">
                            <option <?php if ($listing_mode == 'production') { ?> selected="selected"  <?php } ?> value="production">Production Site</option>
                            <option <?php if ($listing_mode == 'sandbox') { ?> selected="selected"  <?php } ?> value="sandbox">Sandbox (Test) Site</option>
                        </select>
                        <?php if ($error_listing_mode) { ?>
                        <div class="text-danger"><?php echo $error_listing_mode; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <?php if ($store_name) { ?>
                <div class="form-group">
                    <label for="listing_mode" class="col-sm-2 control-label">eBay Store:</label>
                    <div class="col-sm-10">
                        <a href="<?php echo $store_url; ?>" target="_blank"><?php echo $store_name; ?></a>
                    </div>
                </div>
                <?php } ?>
            </form>

        </div>


    </div>

    <script>
        $('a.save').click(function(){
            if($('#ebay_loading').length) {
                console.log('now processing');
            } else {
                $('#tab-content').append('<div id="ebay_loading" style="position: absolute; width: 100%; height: 100%; opacity: 0.3; left: 0px; top: 0px; z-index: 1000000; background: url(http://localhost/ebay/admin/view/image/channels/ajax-loader.gif) 50% 50% no-repeat black;"></div>');
                setTimeout(function(){
                    $('#ebay_account').submit();
                }, 1000);
            }
            return false;
        });
    </script>

</div>
<?php echo $footer; ?>


