<form id="category_mapping_form" class="form-horizontal" action="<?php echo $save_category_mapping_url; ?>" method="post">
    <div class="form-group">
        <label class="col-sm-2 control-label">
            <span title="" data-toggle="tooltip" data-original-title="Map your Site Categories to the eBay Store Categories">Category mapping</span>
        </label>
        <div class="col-sm-10">
            <table class="table">
                <?php if(empty($ebay_store_categories)) {?>

                <tr>
                <td colspan="2"><a class="btn btn-primary" id="import_store_categories" href="<?php echo $import_ebay_store_categories; ?>"><i class="fa fa-download fa-fw"></i> Add store categories from eBay</a> </td>
                </tr>
                <?php } else {?>
                <tr>
                <td><b>eBay Store Categories</b></td>
                <td><b>Store Categories</b></td>
                </tr>
                <?php foreach($ebay_store_categories as $ebay_store_category) {?>
                <tr>
                <td><b><?php echo $ebay_store_category['path']; ?>:</b></td>
                <td>
                <select name="category_mapping[<?php echo $ebay_store_category['category_id']; ?>]">
                <option value="0">None</option>
                <?php foreach($local_categories as $local_category) {?>
                <?php $isSelected = false; foreach($mappings as $mapping) {?>
                <?php if($mapping['category_id'] == $local_category['category_id'] && $mapping['ebay_store_category_id'] == $ebay_store_category['category_id']) {$isSelected=true;}?>
                <?php }?>
                <option value="<?php echo $local_category['category_id']; ?>" <?php if($isSelected) {?> selected="selected" <?php }?> ><?php echo $local_category['path']; ?></option>
                <?php }?>
                </select>
                </td>
                </tr>
                <?php }?>

                <?php }?>

            </table>
            <?php if(!empty($ebay_store_categories)) {?>
            <div class="well">
                <div class="row">
                    <button class="btn btn-primary" id="save_category_mapping">Save</button>
                </div>
            </div>
            <?php }?>
        </div>
    </div>
</form>

<script>

    $('#category_mapping_form').ajaxForm({
        beforeSubmit: function(){
            $("#mapping_categories .success").html('');
            $('#save_category_mapping').attr('title', $('#save_category_mapping').html()).html('Loading...');
        },
        success: function(resp){
            $('#save_category_mapping').html($('#save_category_mapping').attr('title'));
            $("#mapping_categories .success").html(resp.message).removeClass('hide');
        }
    });

    $('#save_category_mapping').click(function(){
        var label = $(this).html();
        $('#category_mapping_form').submit();
        return false;
    });

    $("#import_store_categories").click(function(){
        var btn = $(this);
        var label = btn.html();
        btn.html('Loading...');
        $.get(btn.attr('href'), function(data){
            btn.html(label);
            if(data.status) {
                $('#category-mapping').load($('#category-mapping').attr('data-url'));
            } else if(data.hasOwnProperty('error')) {
                alert(data.error);
            }
        });
        return false;
    });

</script>