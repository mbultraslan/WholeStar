<style>
table.form {
	width: 100%;
	border-collapse: collapse;
	margin-bottom: 20px;
}
table.form > tbody > tr > td:first-child {
	width: 200px;
}
table.form > tbody > tr > td {
	padding: 10px;
	color: #000000;
	border-bottom: 1px dotted #CCCCCC;
}
</style><div class="modal-dialog">
  <div class="warning"<?php if (!$error_warning) echo ' style="display:none;"';?>>
  <?php if ($error_warning) echo $error_warning;?>
  </div>
  <div class="modal-content">
<?php if (isset($html) && $html) { ?>
    <div class="modal-header" style="padding:10px;"><h3><?php echo $button_quick_settings;?></h3></div>
    <?php echo $html;?>
    <div class="modal-footer">
	  <center>
        <a onclick="$('#quick-edit').modal('hide');return false;" href="#" class="btn btn-primary"><span><?php echo $button_cancel; ?></span></a> &nbsp;
        <a onclick="saveQuickSettings();return false;" href="#" class="btn btn-primary"><span><?php echo $button_save; ?></span></a>
      </center>
	</div>
<?php } else { ?>
	<?php if ($name) { ?><div class="modal-header" style="padding:10px;"><h3><?php echo $name;?></h3></div><?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="qe-form">
        <table class="form">
          <?php if (QE_QUICK_MODEL) { ?>
          <tr>
            <td class="required"><?php echo $entry_model; ?></td>
            <td><input type="text" name="model" value="<?php echo $model; ?>" />
              <?php if ($error_model) { ?>
              <span class="error"><?php echo $error_model; ?></span>
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php if (QE_QUICK_MANUFACTURER) { ?>
          <tr>
            <td><span class="required"></span> <?php echo $entry_manufacturer; ?></td>
            <td><select name="manufacturer_id">
                <option value="0" selected="selected"><?php echo $text_none; ?></option>
                <?php foreach ($manufacturers as $manufacturer) { ?>
                <option value="<?php echo $manufacturer['manufacturer_id']; ?>"<?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) echo ' selected="selected"';?>><?php echo $manufacturer['name']; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <?php } ?>
<?php // BOF - Zappo - Product Types - Added Product Types ?>
          <?php if (defined('QE_QUICK_TYPE') && QE_QUICK_TYPE) { ?>
          <tr>
            <td><?php echo $entry_type_id; ?></td>
            <td><select name="type_id">
            <?php echo $type_ids;?>
            </select></td>
          </tr>
          <?php } ?>
<?php // EOF - Zappo - Product Types - Added Product Types ?>
          <?php if (QE_QUICK_SKU) { ?>
          <tr>
            <td><?php echo $entry_sku; ?></td>
            <td><input type="text" name="sku" value="<?php echo $sku; ?>" /></td>
          </tr>
          <?php } ?>
          <?php if (QE_QUICK_UPC) { ?>
          <tr>
            <td><?php echo $entry_upc; ?></td>
            <td><input type="text" name="upc" value="<?php echo $upc; ?>" /></td>
          </tr>
          <?php } ?>
          <?php if (QE_QUICK_PRICE) { ?>
          <tr>
            <td><?php echo $entry_price; ?></td>
            <td><input type="text" name="price" value="<?php echo $price; ?>" /></td>
          </tr>
          <?php } ?>
          <?php if (QE_QUICK_TAX) { ?>
          <tr>
            <td><?php echo $entry_tax_class; ?></td>
            <td><select name="tax_class_id">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($tax_classes as $tax_class) { ?>
                <?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <?php } ?>
          <?php if (QE_QUICK_QTY) { ?>
          <tr>
            <td><?php echo $entry_quantity; ?></td>
            <td><input type="text" name="quantity" value="<?php echo $quantity; ?>" size="2" /></td>
          </tr>
          <?php } ?>
          <?php if (QE_QUICK_SEO) { ?>
          <tr>
            <td><?php echo $entry_keyword; ?></td>
            <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" /></td>
          </tr>
          <?php } ?>
          <?php if (QE_QUICK_DATE) { ?>
          <tr>
            <td><?php echo $entry_date_available; ?></td>
            <td><input type="text" name="date_available" value="<?php echo $date_available; ?>" size="12" class="date" /></td>
          </tr>
          <?php } ?>
          <?php if (QE_QUICK_SIZE) { ?>
          <tr>
            <td><?php echo $entry_dimension; ?></td>
            <td><input type="text" name="length" value="<?php echo $length; ?>" size="5" />
              <input type="text" name="width" value="<?php echo $width; ?>" size="5" />
              <input type="text" name="height" value="<?php echo $height; ?>" size="5" /></td>
          </tr>
          <?php } ?>
          <?php if (QE_QUICK_LENGTH) { ?>
          <tr>
            <td><?php echo $entry_length; ?></td>
            <td><select name="length_class_id">
                <?php foreach ($length_classes as $length_class) { ?>
                <?php if ($length_class['length_class_id'] == $length_class_id) { ?>
                <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <?php } ?>
          <?php if (QE_QUICK_WEIGHT) { ?>
          <tr>
            <td><?php echo $entry_weight; ?></td>
            <td><input type="text" name="weight" value="<?php echo $weight; ?>" /></td>
          </tr>
          <?php } ?>
          <?php if (QE_QUICK_WEIGHTCLASS) { ?>
          <tr>
            <td><?php echo $entry_weight_class; ?></td>
            <td><select name="weight_class_id">
                <?php foreach ($weight_classes as $weight_class) { ?>
                <?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
                <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
            </select></td>
          </tr>
          <?php } ?>
          <?php if (QE_QUICK_STATUS) { ?>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
            </select></td>
          </tr>
          <?php } ?>
          <?php if (QE_QUICK_SORT) { ?>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="2" /></td>
          </tr>
          <?php } ?>
          <?php if (QE_QUICK_POINTS) { ?>
          <tr>
            <td><?php echo $entry_points; ?></td>
            <td><input type="text" name="points" value="<?php echo $points; ?>" /></td>
          </tr>
          <?php } ?>
          <?php if (QE_QUICK_REWARD) { ?>
          <tr style="background-color:#FFF;">
            <td class="left"><?php echo $entry_customer_group; ?></td>
            <td class="right"><?php echo $entry_reward; ?></td>
          </tr>
          <?php foreach ($customer_groups as $customer_group) { ?>
          <tr>
            <td class="left"><?php echo $customer_group['name']; ?></td>
            <td class="right"><input type="text" name="product_reward[<?php echo $customer_group['customer_group_id']; ?>][points]" value="<?php echo isset($product_reward[$customer_group['customer_group_id']]) ? $product_reward[$customer_group['customer_group_id']]['points'] : ''; ?>" /></td>
          </tr>
          <?php } ?>
          <?php } ?>
          <tr>
            <td colspan="2"><center>
              <a onclick="$('#quick-edit').modal('hide');return false;" href="#" class="btn btn-primary"><span><?php echo $button_cancel; ?></span></a>
              <?php if ($button_save_load) { ?>&nbsp; <a onclick="saveProduct(true);return false;" href="#" class="btn btn-primary"><span><?php echo $button_save_load; ?></span></a><?php } ?>
              &nbsp; <a onclick="saveProduct();return false;" href="#" class="btn btn-primary"><span><?php echo $button_save; ?></span></a>
            </center></td>
          </tr>
        </table>
    </form>
<?php } ?>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js"></script> 
<script type="text/javascript"><!--
function saveProduct(reload) {
	$.ajax({
		url: '<?php echo str_replace('&amp;','&',$action);?>',
		type: 'post',
		data: $('input[type="text"], input[type="radio"]:checked, input[type="checkbox"]:checked, select, textarea', '#qe-form'),
		dataType: 'json',
		success: function(json) {
			$('.warning:visible').hide();

			if (json['error']) {
				if (json['error']['warning']) {
					$('.alert-danger').html(json['error']['warning']).fadeIn('slow');
				}

				for (var i in json['error']) {
					$('[name="' + i + '"]').after('<span class="alert-danger">' + json['error'][i] + '</span>');
				}
			}

			if (json['success']) {
				$('#quick-edit').find('.modal-content').html('<div class="alert alert-success" role="alert">' + json['success'] + '</div>');
				setTimeout((reload ? "location = '<?php echo $reload;?>';" : "$('#quick-edit').modal('hide');"),<?php echo (QE_QUICK_TIME) ? QE_QUICK_TIME : '2000';?>);
			}
		}
	});
}
function saveQuickSettings() {
	$.ajax({
		url: '<?php echo str_replace('&amp;','&',$action);?>',
		type: 'post',
		data: $('input[type="text"], input[type="radio"]:checked, input[type="checkbox"]:checked, select, textarea', '#quick-edit'),
		success: function(message) {
			var time = (message.indexOf('alert-danger') !== -1) ? 5000 : <?php echo (QE_QUICK_TIME) ? QE_QUICK_TIME : '2000';?>;
			$("#quick-edit").find('.modal-content').html(message);
			setTimeout("location = '<?php echo $reload;?>';",time);
		}
	});
}
$('.date').datetimepicker({
	pickTime: false,
	format: 'YYYY-MM-DD'
});
//--></script>