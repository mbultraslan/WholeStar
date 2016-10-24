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