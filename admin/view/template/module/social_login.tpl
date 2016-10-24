<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-social-login" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-social-login" class="form-horizontal">
		 <div class="panel-heading">
		 Facebook Configuration
		 </div>
		 
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $text_facebook_api_key; ?></label>
            <div class="col-sm-10">
			 <input type="text" name="social_facebook_key" placeholder="<?php echo $text_facebook_api_key;?>" value="<?php echo $social_facebook_key;?>" class="form-control" />
		    </div>
          </div>
		  
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $text_facebook_secret_key; ?></label>
            <div class="col-sm-10">
			 <input type="text" name="social_facebook_secret" placeholder="<?php echo $text_facebook_secret_key;?>" value="<?php echo $social_facebook_key;?>" class="form-control" />
		    </div>
          </div>
		  
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $text_facebook_redirect_uri; ?></label>
            <div class="col-sm-10">
			 <input type="text" name="social_facebook_redirect_uri"  value="<?php echo $social_facebook_redirect_uri;?>" class="form-control" />
		    </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $text_enable_facebook_login; ?></label>
            <div class="col-sm-10">
			 <select name="social_facebook_enable" id="input-status" class="form-control">
                <?php if ($social_facebook_enable) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
		    </div>
          </div>
		  
		 
		  
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="social_login_status" id="input-status" class="form-control">
                <?php if ($social_login_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>