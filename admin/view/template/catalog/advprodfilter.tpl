<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="container-fluid"> <!-- continut -->
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
    
    <div class="panel panel-default"> <!-- filters div -->
      
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $apftxt_p_filters_h; ?></h3>
      </div>
      
      <div class="panel-body">
      <div class="table-responsive">
    	<table class="table table-bordered table-hover"> <!-- filters table -->
    	<tbody>

    	<tr>
    	  <td class="text-left" style="width:236px;">
    	  <strong><?php echo $apftxt_name; ?></strong>
    	  </td>
    	  <td colspan="4" class="text-left">
    	  <input size="42" type="text" value="<?php echo $filter_name; ?>" name="filter_name">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_name_help; ?>"></span></label>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_tag; ?></strong>
    	  </td>
    	  <td colspan="4" class="text-left">
    	  <input size="42" type="text" value="<?php echo $filter_tag; ?>" name="filter_tag">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_tag_help; ?>"></span></label>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_model; ?></strong>
    	  </td>
    	  <td colspan="4" class="text-left">
    	  <input size="42" type="text" value="<?php echo $filter_model; ?>" name="filter_model">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_model_help; ?>"></span></label>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_categories; ?></strong>
    	  </td>
    	  <td colspan="4" class="text-left">
              <div class="well well-sm scroll1">
                <?php foreach ($categories as $category) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($category['category_id'], $product_category)) { ?>
                    <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                    <?php echo $category['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" />
                    <?php echo $category['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $apftxt_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $apftxt_unselect_all; ?></a>
              <label class="control-label tooltip1"><span data-toggle="tooltip" title="<?php echo $apftxt_unselect_all_to_ignore; ?>"></span></label>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_manufacturers; ?></strong>
    	  </td>
    	  <td colspan="4" class="text-left">
              <div class="well well-sm scroll1">
                <div class="checkbox">
                  <label>
                    <?php if (in_array(0, $manufacturer_ids)) { ?>
                    <input type="checkbox" name="manufacturer_ids[]" value="0" checked="checked" /><?php echo $apftxt_none; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="manufacturer_ids[]" value="0" /><?php echo $apftxt_none; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php foreach ($manufacturers as $manufacturer) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($manufacturer['manufacturer_id'], $manufacturer_ids)) { ?>
                    <input type="checkbox" name="manufacturer_ids[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
                    <?php echo $manufacturer['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="manufacturer_ids[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
                    <?php echo $manufacturer['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $apftxt_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $apftxt_unselect_all; ?></a>
              <label class="control-label tooltip1"><span data-toggle="tooltip" title="<?php echo $apftxt_unselect_all_to_ignore; ?>"></span></label>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_p_filters; ?></strong>
    	  </td>
    	  <td colspan="4" class="text-left">
    	  <?php if($p_filters) { ?>
              <div class="well well-sm scroll1">
                <?php foreach ($p_filters as $p_filter) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($p_filter['filter_id'], $filters_ids)) { ?>
                    <input type="checkbox" name="filters_ids[]" value="<?php echo $p_filter['filter_id']; ?>" checked="checked" />
                    <?php echo $p_filter['group'].' &gt; '.$p_filter['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="filters_ids[]" value="<?php echo $p_filter['filter_id']; ?>" />
                    <?php echo $p_filter['group'].' &gt; '.$p_filter['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $apftxt_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $apftxt_unselect_all; ?></a>
              <label class="control-label tooltip1"><span data-toggle="tooltip" title="<?php echo $apftxt_unselect_all_to_ignore; ?>"></span></label>
    	  <?php } else { echo $apftxt_p_filters_none; } ?>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_price; ?></strong>
    	  <span class="help"> <?php echo $apftxt_price_help; ?></span>
    	  </td>
    	  <td class="text-right">
    	  <?php echo $apftxt_greater_than_or_equal; ?>
    	  </td>
    	  <td class="text-left">
    	  <input size="10" type="text" value="<?php echo $price_mmarese; ?>" name="price_mmarese">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_leave_empty_to_ignore; ?>"></span></label>
    	  </td>
    	  <td class="text-right">
    	  <?php echo $apftxt_less_than_or_equal; ?>
    	  </td>
    	  <td class="text-left">
    	  <input size="10" type="text" value="<?php echo $price_mmicse; ?>" name="price_mmicse">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_leave_empty_to_ignore; ?>"></span></label>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_discount; ?></strong>
    	  </td>
    	  <td class="text-right">
    	  
    	  <div style="float:left;border-right:1px solid #DDDDDD;margin: -7px;padding: 7px;">
    	  <?php echo $apftxt_customer_group; ?> 
    	  <select name="d_cust_group_filter">
    	  <option value="any"<?php if ($d_cust_group_filter=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_all; ?></option>
    	  <?php foreach ($customer_groups as $customer_group) { ?>
    	  <option value="<?php echo $customer_group['customer_group_id']; ?>"<?php if ($customer_group['customer_group_id']==$d_cust_group_filter) { echo ' selected="selected"'; } ?>><?php echo $customer_group['name']; ?></option>
    	  <?php } ?>
    	  </select>
    	  </div>
    	  
    	  <?php echo $apftxt_greater_than_or_equal; ?>
    	  </td>
    	  <td class="text-left">
    	  <input size="10" type="text" value="<?php echo $d_price_mmarese; ?>" name="d_price_mmarese">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_leave_empty_to_ignore; ?>"></span></label>
    	  </td>
    	  <td class="text-right">
    	  <?php echo $apftxt_less_than_or_equal; ?>
    	  </td>
    	  <td class="text-left">
    	  <input size="10" type="text" value="<?php echo $d_price_mmicse; ?>" name="d_price_mmicse">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_leave_empty_to_ignore; ?>"></span></label>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_special; ?></strong>
    	  </td>
    	  <td class="text-right">
    	  
    	  <div style="float:left;border-right:1px solid #DDDDDD;margin: -7px;padding: 7px;">
    	  <?php echo $apftxt_customer_group; ?> 
    	  <select name="s_cust_group_filter">
    	  <option value="any"<?php if ($s_cust_group_filter=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_all; ?></option>
    	  <?php foreach ($customer_groups as $customer_group) { ?>
    	  <option value="<?php echo $customer_group['customer_group_id']; ?>"<?php if ($customer_group['customer_group_id']==$s_cust_group_filter) { echo ' selected="selected"'; } ?>><?php echo $customer_group['name']; ?></option>
    	  <?php } ?>
    	  </select>
    	  </div>
    	  
    	  <?php echo $apftxt_greater_than_or_equal; ?>
    	  </td>
    	  <td class="text-left">
    	  <input size="10" type="text" value="<?php echo $s_price_mmarese; ?>" name="s_price_mmarese">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_leave_empty_to_ignore; ?>"></span></label>
    	  </td>
    	  <td class="text-right">
    	  <?php echo $apftxt_less_than_or_equal; ?>
    	  </td>
    	  <td class="text-left">
    	  <input size="10" type="text" value="<?php echo $s_price_mmicse; ?>" name="s_price_mmicse">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_leave_empty_to_ignore; ?>"></span></label>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_tax_class; ?></strong>
    	  </td>
    	  <td colspan="4" class="text-left">
    	  <select name="tax_class_filter">
    	  <option value="any"<?php if ($tax_class_filter=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_ignore_this; ?></option>
    	  <option value="0"<?php if ($tax_class_filter=='0') { echo ' selected="selected"'; } ?>> <?php echo $apftxt_none; ?> </option>
    	  <?php foreach ($tax_classes as $tax_class) { ?>
    	  <option value="<?php echo $tax_class['tax_class_id']; ?>"<?php if ($tax_class['tax_class_id']==$tax_class_filter) { echo ' selected="selected"'; } ?>><?php echo $tax_class['title']; ?></option>
    	  <?php } ?>
    	  </select>
    	  </td>
    	</tr>
    	
    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_quantity; ?></strong>
    	  </td>
    	  <td class="text-right">
    	  <?php echo $apftxt_greater_than_or_equal; ?>
    	  </td>
    	  <td class="text-left">
    	  <input size="10" type="text" value="<?php echo $stock_mmarese; ?>" name="stock_mmarese">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_leave_empty_to_ignore; ?>"></span></label>
    	  </td>
    	  <td class="text-right">
    	  <?php echo $apftxt_less_than_or_equal; ?>
    	  </td>
    	  <td class="text-left">
    	  <input size="10" type="text" value="<?php echo $stock_mmicse; ?>" name="stock_mmicse">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_leave_empty_to_ignore; ?>"></span></label></td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_minimum_quantity; ?></strong>
    	  </td>
    	  <td class="text-right">
    	  <?php echo $apftxt_greater_than_or_equal; ?>
    	  </td>
    	  <td class="text-left">
    	  <input size="10" type="text" value="<?php echo $min_q_mmarese; ?>" name="min_q_mmarese">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_leave_empty_to_ignore; ?>"></span></label>
    	  </td>
    	  <td class="text-right">
    	  <?php echo $apftxt_less_than_or_equal; ?>
    	  </td>
    	  <td class="text-left">
    	  <input size="10" type="text" value="<?php echo $min_q_mmicse; ?>" name="min_q_mmicse">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_leave_empty_to_ignore; ?>"></span></label>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_subtract_stock; ?></strong>
    	  </td>
    	  <td colspan="4" class="text-left">
    	  <select name="subtract_filter">
    	  <option value="any"<?php if ($subtract_filter=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_ignore_this; ?></option>
    	  <option value="1"<?php if ($subtract_filter=='1') { echo ' selected="selected"'; } ?>><?php echo $apftxt_yes; ?></option>
    	  <option value="0"<?php if ($subtract_filter=='0') { echo ' selected="selected"'; } ?>><?php echo $apftxt_no; ?></option>
    	  </select>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_out_of_stock_status; ?></strong>
    	  </td>
    	  <td colspan="4" class="text-left">
    	  <select name="stock_status_filter">
    	  <option value="any"<?php if ($stock_status_filter=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_ignore_this; ?></option>
    	  <?php foreach ($stock_statuses as $stock_status) { ?>
    	  <option value="<?php echo $stock_status['stock_status_id']; ?>"<?php if ($stock_status['stock_status_id']==$stock_status_filter) { echo ' selected="selected"'; } ?>><?php echo $stock_status['name']; ?></option>
    	  <?php } ?>
    	  </select>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_requires_shipping; ?></strong>
    	  </td>
    	  <td colspan="4" class="text-left">
    	  <select name="shipping_filter">
    	  <option value="any"<?php if ($shipping_filter=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_ignore_this; ?></option>
    	  <option value="1"<?php if ($shipping_filter=='1') { echo ' selected="selected"'; } ?>><?php echo $apftxt_yes; ?></option>
    	  <option value="0"<?php if ($shipping_filter=='0') { echo ' selected="selected"'; } ?>><?php echo $apftxt_no; ?></option>
    	  </select>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_date_available; ?></strong>
    	  </td>
    	  <td class="text-right">
    	  <?php echo $apftxt_greater_than_or_equal; ?>
    	  </td>
    	  <td class="text-left">
    	  <input class="date" size="14" type="text" value="<?php echo $date_mmarese; ?>" name="date_mmarese" data-date-format="YYYY-MM-DD">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_leave_empty_to_ignore; ?>"></span></label>
    	  </td>
    	  <td class="text-right">
    	  <?php echo $apftxt_less_than_or_equal; ?>
    	  </td>
    	  <td class="text-left">
    	  <input class="date" size="14" type="text" value="<?php echo $date_mmicse; ?>" name="date_mmicse" data-date-format="YYYY-MM-DD">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_leave_empty_to_ignore; ?>"></span></label>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_date_added; ?></strong>
    	  </td>
    	  <td class="text-right">
    	  <?php echo $apftxt_greater_than_or_equal; ?>
    	  </td>
    	  <td class="text-left">
    	  <input class="datetime" size="14" type="text" value="<?php echo $date_added_mmarese; ?>" name="date_added_mmarese" data-date-format="YYYY-MM-DD HH:mm">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_leave_empty_to_ignore; ?>"></span></label>
    	  </td>
    	  <td class="text-right">
    	  <?php echo $apftxt_less_than_or_equal; ?>
    	  </td>
    	  <td class="text-left">
    	  <input class="datetime" size="14" type="text" value="<?php echo $date_added_mmicse; ?>" name="date_added_mmicse" data-date-format="YYYY-MM-DD HH:mm">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_leave_empty_to_ignore; ?>"></span></label>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_date_modified; ?></strong>
    	  </td>
    	  <td class="text-right">
    	  <?php echo $apftxt_greater_than_or_equal; ?>
    	  </td>
    	  <td class="text-left">
    	  <input class="datetime" size="14" type="text" value="<?php echo $date_modified_mmarese; ?>" name="date_modified_mmarese" data-date-format="YYYY-MM-DD HH:mm">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_leave_empty_to_ignore; ?>"></span></label>
    	  </td>
    	  <td class="text-right">
    	  <?php echo $apftxt_less_than_or_equal; ?>
    	  </td>
    	  <td class="text-left">
    	  <input class="datetime" size="14" type="text" value="<?php echo $date_modified_mmicse; ?>" name="date_modified_mmicse" data-date-format="YYYY-MM-DD HH:mm">
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_leave_empty_to_ignore; ?>"></span></label>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_status; ?></strong>
    	  </td>
    	  <td colspan="4" class="text-left">
    	  <select name="prod_status">
    	  <option value="any"<?php if ($prod_status=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_ignore_this; ?></option>
    	  <option value="1"<?php if ($prod_status=='1') { echo ' selected="selected"'; } ?>><?php echo $apftxt_enabled; ?></option>
    	  <option value="0"<?php if ($prod_status=='0') { echo ' selected="selected"'; } ?>><?php echo $apftxt_disabled; ?></option>
    	  </select>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_store; ?></strong>
    	  </td>
    	  <td colspan="4" class="text-left">
    	  <select name="store_filter">
    	  <option value="any"<?php if ($store_filter=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_ignore_this; ?></option>
    	  <option value="0"<?php if ($store_filter=='0') { echo ' selected="selected"'; } ?>><?php echo $apftxt_default; ?></option>
    	  <?php foreach ($stores as $store) { ?>
    	  <option value="<?php echo $store['store_id']; ?>"<?php if ($store['store_id']==$store_filter) { echo ' selected="selected"'; } ?>><?php echo $store['name']; ?></option>
    	  <?php } ?>
    	  </select>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_with_attribute; ?></strong>
    	  </td>
    	  <td colspan="4" class="text-left">
    	  <select name="filter_attr">
    	  <option value="any"<?php if ($filter_attr=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_ignore_this; ?></option>
    	  <?php foreach ($all_attributes as $attrib) { ?>
    	  <option value="<?php echo $attrib['attribute_id']; ?>"<?php if ($attrib['attribute_id']==$filter_attr) { echo ' selected="selected"'; } ?>><?php echo $attrib['attribute_group']." > ".$attrib['name']; ?></option>
    	  <?php } ?>
    	  </select>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_with_attribute_value; ?></strong>
    	  </td>
    	  <td colspan="4" class="text-left">
    	  <textarea name="filter_attr_val" cols="40" rows="2"><?php echo $filter_attr_val; ?></textarea>
    	  <label class="control-label"><span data-toggle="tooltip" title="<?php echo $apftxt_with_attribute_value_help; ?><br /><br /><?php echo $apftxt_leave_empty_to_ignore; ?>"></span></label>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_with_this_option; ?></strong>
    	  </td>
    	  <td colspan="4" class="text-left">
    	  <select name="filter_opti">
    	  <option value="any"<?php if ($filter_opti=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_ignore_this; ?></option>
    	  <?php foreach ($all_options as $option) { ?>
    	  <option value="<?php echo $option['option_id']; ?>"<?php if ($option['option_id']==$filter_opti) { echo ' selected="selected"'; } ?>><?php echo $option['name']; ?></option>
    	  <?php } ?>
    	  </select>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="text-left">
    	  <strong><?php echo $apftxt_with_this_option_value; ?></strong>
    	  </td>
    	  <td colspan="4" class="text-left">
    	  <select name="filter_opti_val">
    	  <option value="any"<?php if ($filter_opti_val=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_ignore_this; ?></option>
    	  <?php foreach ($all_optval as $optval) { ?>
    	  <option value="<?php echo $optval['option_value_id']; ?>"<?php if ($optval['option_value_id']==$filter_opti_val) { echo ' selected="selected"'; } ?>><?php echo $optval['o_name']." > ".$optval['ov_name']; ?></option>
    	  <?php } ?>
    	  </select>
    	  </td>
    	</tr>

    	<tr>
    	  <td colspan="5" class="text-right" style="padding-top:15px !important;padding-bottom:15px !important;">
    	  <?php echo $apftxt_show_max_prod_per_pag1; ?>
    	  <input size="3" type="text" value="<?php echo $max_prod_per_pag; ?>" name="max_prod_per_pag">
    	  <?php echo $apftxt_show_max_prod_per_pag2; ?>
    	  &nbsp;&nbsp;&nbsp;
    	  <button type="submit" value="lista_prod" name="lista_prod" class="btn btn-primary" style="font-weight:bold;padding-left:43px;padding-right:43px;"><i class="fa fa-search"></i> <?php echo $apftxt_button_filter_products; ?></button>
    	  &nbsp;&nbsp;&nbsp;
          <button value="reset_filters" type="submit" name="reset_filters" class="btn btn-primary"><?php echo $apftxt_button_reset_filters; ?></button>
    	  </td>
    	</tr>

        </tbody>
        </table> <!-- filters table -->
      </div>
      </div>
    
    </div> <!-- filters div -->
    
    
    
    <div class="panel panel-default"> <!-- products div -->
      
      <div class="panel-heading prod_h1">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $apftxt_results_products; ?></h3>
        <div class="pull-right">
          <a href="<?php echo $insert; ?>" data-toggle="tooltip" title="<?php echo $apftxt_results_insert; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
    	  <button type="submit" value="copy" name="copy" data-toggle="tooltip" title="<?php echo $apftxt_results_copy; ?>" class="btn btn-default"><i class="fa fa-copy"></i></button>
    	  <input type="submit" value="delete" name="delete" style="display:none;" />
    	  <button type="button" data-toggle="tooltip" title="<?php echo $apftxt_results_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $apftxt_results_text_confirm; ?>') ? $('input[name=\'delete\']').click() : false;"><i class="fa fa-trash-o"></i></button>
        </div>
      </div>
      
      <div class="panel-body">
      <div class="table-responsive">
    	<table class="table table-bordered table-hover prod_table1"> <!-- products table -->

          <thead>
            <tr>
              <td width="1" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
              <td class="text-center"><?php echo $apftxt_results_image; ?></td>
              <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $apftxt_results_product_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $apftxt_results_product_name; ?></a>
                <?php } ?></td>
              <td class="text-left"><?php if ($sort == 'p.model') { ?>
                <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $apftxt_results_model; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_model; ?>"><?php echo $apftxt_results_model; ?></a>
                <?php } ?></td>
              <td class="text-right"><?php if ($sort == 'p.price') { ?>
                <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $apftxt_results_base_price; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_price; ?>"><?php echo $apftxt_results_base_price; ?></a>
                <?php } ?></td>
              <td class="text-right"><?php if ($sort == 'p.quantity') { ?>
                <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $apftxt_results_quantity; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_quantity; ?>"><?php echo $apftxt_results_quantity; ?></a>
                <?php } ?></td>
              <td class="text-left"><?php if ($sort == 'p.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $apftxt_results_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $apftxt_results_status; ?></a>
                <?php } ?></td>
              <td class="text-right"><?php if ($sort == 'p.product_id') { ?>
                <a href="<?php echo $sort_product_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $apftxt_results_product_id; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_product_id; ?>"><?php echo $apftxt_results_product_id; ?></a>
                <?php } ?></td>
              <td class="text-left"><?php if ($sort == 'p.date_added') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $apftxt_results_date_added; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>"><?php echo $apftxt_results_date_added; ?></a>
                <?php } ?></td>
              <td class="text-left"><?php if ($sort == 'p.date_modified') { ?>
                <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $apftxt_results_date_modified; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_modified; ?>"><?php echo $apftxt_results_date_modified; ?></a>
                <?php } ?></td>
              <td class="text-right"><?php if ($sort == 'p.viewed') { ?>
                <a href="<?php echo $sort_viewed; ?>" class="<?php echo strtolower($order); ?>"><?php echo $apftxt_results_viewed; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_viewed; ?>"><?php echo $apftxt_results_viewed; ?></a>
                <?php } ?></td>
              <td class="text-right"><?php echo $apftxt_results_action; ?></td>
            </tr>
          </thead>
          <tbody>
          <?php if ($arr_lista_prod) { ?>
            <?php foreach ($arr_lista_prod as $product) { ?>
            <tr>
              <td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" /></td>
              <td class="text-center"><?php if ($product['image']) { ?><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" />
              <?php } else { ?>
              <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
              <?php } ?></td>
              <td class="text-left"><?php echo $product['name']; ?></td>
              <td class="text-left"><?php echo $product['model']; ?></td>
              <td class="text-right"><?php echo $product['price']; ?></td>
              <td class="text-right"><?php if ($product['quantity'] <= 0) { ?>
                <span class="label label-warning"><?php echo $product['quantity']; ?></span>
                <?php } elseif ($product['quantity'] <= 5) { ?>
                <span class="label label-danger"><?php echo $product['quantity']; ?></span>
                <?php } else { ?>
                <span class="label label-success"><?php echo $product['quantity']; ?></span>
                <?php } ?></td>
              <td class="text-left"><?php if ($product['status']==1) { ?>
                <span style="color: #008000;"><?php echo $apftxt_enabled; ?></span>
                <?php } else { ?>
                <span style="color: #FF0000;"><?php echo $apftxt_disabled; ?></span>
                <?php } ?></td>
              <td class="text-right"><?php echo $product['product_id']; ?></td>
              <td class="text-left"><?php echo $product['date_added']; ?></td>
              <td class="text-left"><?php echo $product['date_modified']; ?></td>
              <td class="text-right"><?php echo $product['viewed']; ?></td>
              <td class="text-right"><a href="<?php echo $product['href_edit']; ?>" data-toggle="tooltip" title="<?php echo $apftxt_results_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
            </tr>

            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="12"><?php echo $apftxt_results_no_results; ?></td>
            </tr>
            <?php } ?>
        </tbody>

    	</table> <!-- products table -->


        <div>
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>


      </div>
      </div>

    </div> <!-- products div -->
    
    <div style="width:100%;text-align:right">
    <a href="http://opencart-market.com" target="_blank">www.opencart-market.com</a>
    </div>    
    
    </form>
    
  </div> <!-- continut -->

<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script> 


</div> <!-- id content -->
<?php echo $footer; ?>
