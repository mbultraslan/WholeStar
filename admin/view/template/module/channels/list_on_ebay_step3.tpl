<div class="form-container" >
    <form class="form-horizontal">
        <fieldset>
            <?php if(!empty($specifics)) { ?>
            <p>To list these <?php echo $productsCount;?> product(s) on eBay, you will need to select the following required fields.</p>
            <p>These fields are determined by eBay as required, because of the category you've selected in your eBay template.</p>
            <p><strong>Default item specifics values</strong></p>

            <?php foreach ($specifics as $key=>$category_specific) { ?>
            <div class="form-group specific-row">
                <label class="col-sm-3 control-label">
                    <span title="" data-toggle="tooltip" data-original-title=""><?php echo $category_specific['name']; ?>:</span>
                </label>
                <div class="col-sm-9">
                    <select class="specific_values form-control" name="item_specific[<?php echo $category_specific['name']; ?>]">
                        <option value="">Other</option>
                        <?php foreach ($category_specific['values'] as $value) { ?>
                        <option value="<?php echo htmlspecialchars($value['value']); ?>"><?php echo $value['value']; ?></option>
                        <?php } ?>
                    </select>
                    <input type="text" class="specific_value form-control" name="item_specific[<?php echo $category_specific['name']; ?>]" value="<?php echo $category_specific['selected']; ?>"/>
                </div>
            </div>
            <?php } ?>
            <?php } else { ?>
            <p>Click next to retrieve the estimated listing costs...</p>
            <?php }  ?>
        </fieldset>
    </form>
</div>
