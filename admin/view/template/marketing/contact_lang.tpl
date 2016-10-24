<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">

  <form  target="_blank" onsubmit="selectAllOptions();" class="container-fluid" action="index.php?route=marketing/contact/send&token=<?php echo $token; ?>" method="post">

  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <!--<button id="button-send" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_send; ?>" class="btn btn-primary" onclick="send('index.php?route=marketing/contact/send&token=<?php echo $token; ?>');"><i class="fa fa-envelope"></i></button>-->

		   <input type="submit" value="Send"  data-toggle="tooltip" title="Yardır Bakalım" class="btn btn-primary"/>

		<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>


    <div class="panel panel-default form-horizontal">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-envelope"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">


	    <div class="form-group">
            <label class="col-sm-2 control-label" for="input-store">Select Products</label>
            <div class="col-sm-3">
             	 <div class="table-responsive" style="width:100%; height:300px; overflow-y:scroll; float:left;">
            <table class="table table-bordered table-hover">



 <?php
  foreach ($products as $product) { ?>

    <tr onclick="AddItem('<?php echo $product['model']; ?>','<?php echo $product['product_id'];  ?>' ,'<?php echo $product['name'];  ?>')"  style="cursor:pointer;">



		<td style="width:55px;">

            <img src="<?php echo $product['image']; ?>" style="width:50px; height:60px;"/>
        </td>
        <td>
          <?php echo $product['name']; ?>    <br/> (<?php echo $product['model']; ?>)
        </td>
    </tr>

 <?php

 } ?>


    </table>
</div>
            </div>

			<div class="col-sm-1">
             <a class="btn btn-danger" style="position:relative; top:150px;" onclick="removeitem()"><i class="fa fa-reply"></i>Remove</a>
            </div>

			<div class="col-sm-3">

    <select multiple="multiple" id="selected_product" name="selected_product[]" style="width: 100%; height: 270px;">


</select><br /><br />
<span class="product_counter">0</span><span class="product_counter_text">&nbsp;Product Selected</span>


            </div>


			<div class="col-sm-3">
              	<div style="float: left; margin-left: 25px;">

	<label><input name="name" type="checkbox" checked />Product Name</label><br/>
	<label><input name="model" type="checkbox" checked/>Model</label><br/>
	<!-- <label><input name="material" type="checkbox" checked/>Material</label><br/>
	<label><input name="colour" type="checkbox" checked/>Colour</label><br/>
	<label><input name="ratio" type="checkbox" checked/>Ratio</label><br/>
	<label><input name="quantity" type="checkbox" checked/>Pack Quantity</label><br/>
	<label><input name="each" type="checkbox" checked/>Each Price</label><br/> -->
	<label><input name="pack" type="checkbox" checked/>Price</label><br/>
	<label><input name="special" type="checkbox" checked/>Special Price</label><br/>
	<label><input name="link" type="checkbox" checked/>Enable Linking</label><br/>


	</div>
            </div>

          </div>



    <script type="text/javascript">

	function selectAllOptions()
{
  var selObj = document.getElementById('selected_product');
  for (var i=0; i<selObj.options.length; i++) {
    selObj.options[i].selected = true;
  }
}


    function removeitem()
    {
	 $("#selected_product option:selected").remove();
	var ddloption =  document.getElementById('selected_product').options;
	  $(".product_counter").text(ddloption.length);
	}

    function AddItem(Model,Value, Name)
    {

var IsExists = false;
var ddloption =  document.getElementById('selected_product').options;
for(var i = 0; i < ddloption.length; i++)
{
 ddloption[i].selected=false;
   if( ddloption[i].value === Value )
    {
        IsExists = true;
	 ddloption[i].selected=true;
    }
}
	if(IsExists == false){
        // Create an Option object
        var opt = document.createElement("option");
        // Add an Option object to Drop Down/List Box
        document.getElementById("selected_product").options.add(opt);
        // Assign text and value to Option object
        opt.text = Model +"  " + Name;
        opt.value = Value;

		var ddloption =  document.getElementById('selected_product').options;
	  $(".product_counter").text(ddloption.length);

       }
    }
</script>


          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-store"><?php echo $entry_store; ?></label>
            <div class="col-sm-10">
              <select name="store_id" id="input-store" class="form-control">
                <option value="0"><?php echo $text_default; ?></option>
                <?php foreach ($stores as $store) { ?>
                <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-to"><?php echo $entry_to; ?></label>
            <div class="col-sm-10">
              <select name="to" id="input-to" class="form-control">
                <option value="newsletter"><?php echo $text_newsletter; ?></option>
				 <option value="subs">Subsricbed</option>
                <option value="customer_all"><?php echo $text_customer_all; ?></option>
                <option value="customer_group"><?php echo $text_customer_group; ?></option>
                <option value="customer"><?php echo $text_customer; ?></option>
                <option value="affiliate_all"><?php echo $text_affiliate_all; ?></option>
                <option value="affiliate"><?php echo $text_affiliate; ?></option>
                <option value="product"><?php echo $text_product; ?></option>
              </select>
            </div>

			<script>
			$(document).ready(function(){
     $("#input-to").change(function(){
		 if($(this).val()=="subs"){
			alert("Please Select Correct Template");

		 }

     });
   });

			</script>
          </div>
          <div class="form-group to" id="to-customer-group">
            <label class="col-sm-2 control-label" for="input-customer-group"><?php echo $entry_customer_group; ?></label>
            <div class="col-sm-10">
              <select name="customer_group_id" id="input-customer-group" class="form-control">
                <?php foreach ($customer_groups as $customer_group) { ?>
                <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group to" id="to-customer">
            <label class="col-sm-2 control-label" for="input-customer"><span data-toggle="tooltip" title="<?php echo $help_customer; ?>"><?php echo $entry_customer; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="customers" value="" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
              <div class="well well-sm" style="height: 150px; overflow: auto;"></div>
            </div>
          </div>
          <div class="form-group to" id="to-affiliate">
            <label class="col-sm-2 control-label" for="input-affiliate"><span data-toggle="tooltip" title="<?php echo $help_affiliate; ?>"><?php echo $entry_affiliate; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="affiliates" value="" placeholder="<?php echo $entry_affiliate; ?>" id="input-affiliate" class="form-control" />
              <div class="well well-sm" style="height: 150px; overflow: auto;"></div>
            </div>
          </div>
          <div class="form-group to" id="to-product">
            <label class="col-sm-2 control-label" for="input-product"><span data-toggle="tooltip" title="<?php echo $help_product; ?>"><?php echo $entry_product; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="products" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
              <div class="well well-sm" style="height: 150px; overflow: auto;"></div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-template"><?php echo $entry_template; ?></label>
            <div class="col-sm-10">
              <select name="email_template" id="input-template" class="form-control">
                <option value=''><?php echo $text_select; ?></option>
				<?php foreach($email_templates as $item) { ?>
				<option value="<?php echo $item['value']; ?>"><?php echo $item['label']; ?></option>
				<?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-campaign-name"><?php echo $entry_campaign_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="campaign_name" value="" id="input-campaign-name" class="form-control" />
            </div>
          </div>
      </div>
    </div>

    <div class="panel form-horizontal" id="language-panel">
      <div class="panel-body">
        <ul class="nav nav-tabs" id="language">
          <?php $i = 1; foreach ($languages as $language) { ?>
          <li<?php if($i == 1){ ?> class="active"<?php } ?>><a href="javascript:void(0)" data-target="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
          <?php $i++; } ?>
        </ul>
        <div class="tab-content">
          <?php $i = 1;  foreach ($languages as $language) { ?>
            <div class="tab-pane <?php if($i == 1){ ?> active<?php } ?>" id="language<?php echo $language['language_id']; ?>">
              <div class="form-group required">
	            <label class="col-sm-2 control-label" for="input-subject-<?php echo $language['language_id']; ?>"><?php echo $entry_subject; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="subject[<?php echo $language['language_id']; ?>]" value="" id="input-subject-<?php echo $language['language_id']; ?>" class="form-control" />
                </div>
              </div>

              <div class="form-group">
	            <label class="col-sm-2 control-label" for="input-preview-<?php echo $language['language_id']; ?>"><?php echo $entry_preheader; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="preview[<?php echo $language['language_id']; ?>]" value="" id="input-preview-<?php echo $language['language_id']; ?>" class="form-control" />
                </div>
              </div>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-message-<?php echo $language['language_id']; ?>"><?php echo $entry_message; ?></label>
                <div class="col-sm-10">
                  <textarea name="message[<?php echo $language['language_id']; ?>]" id="input-message-<?php echo $language['language_id']; ?>" class="language_editor form-control"></textarea>
		        </div>
		      </div>
        	</div>
          <?php $i++; } ?>
      	</div>
      </div>
    </div>
  </form>
<script type="text/javascript"><!--
(function($) {

	$('#language-panel > .panel-body > .tab-content > .tab-pane').each(function(){
		var $el = $(this);

		if(!$el.is(':hidden')){
			$el.find('.language_editor').summernote({
				height: ($el.data('height') ? $el.data('height') : 'auto')
			});
		} else {
			$('a[data-toggle="tab"][data-target="#' + $(this).attr('id') + '"]').on('shown.bs.tab', function(){
				$el.find('.language_editor').summernote({
					height: ($el.data('height') ? $el.data('height') : 'auto')
				});
			});
		}
	});

	$(document).ready(function() {

		$('#input-template').change(function(){
			var val = $(this).val(),
				language_id,
				store_id = $('select[name=store_id]').val();

			if (!val || !confirm("<?php echo $warning_template_content; ?>")) return;

			$.ajax({
				url: '<?php echo html_entity_decode($templates_action); ?>',
				type: 'get',
				data: 'id=' + val + '&store_id=' + store_id + '&parse=0',
				dataType: 'json',
				success: function(json) {
					for(i in json) {
 						language_id = json[i]['language_id'];

 						if (json[i]['emailtemplate']['subject']) {
							$("#input-subject-" + language_id).val(json[i]['emailtemplate']['subject']);
						}

 						if (json[i]['emailtemplate']['preview']) {
							$("#input-preview-" + language_id).val(json[i]['emailtemplate']['preview']);
						}

						$("#input-message-" + language_id).code(json[i]['emailtemplate']['comment']);
					}
				}
			});
		});

 });	// doc.ready
})(jQuery);
//--></script>
  <script type="text/javascript"><!--
$('select[name=\'to\']').on('change', function() {
	$('.to').hide();

	$('#to-' + this.value.replace('_', '-')).show();
});

$('select[name=\'to\']').trigger('change');
//--></script>
  <script type="text/javascript"><!--
// Customers
$('input[name=\'customers\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'customers\']').val('');

		$('#input-customer' + item['value']).remove();

		$('#input-customer').parent().find('.well').append('<div id="customer' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="customer[]" value="' + item['value'] + '" /></div>');
	}
});

$('#input-customer').parent().find('.well').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

// Affiliates
$('input[name=\'affiliates\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'affiliates\']').val('');

		$('#input-affiliate' + item['value']).remove();

		$('#input-affiliate').parent().find('.well').append('<div id="affiliate' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="affiliate[]" value="' + item['value'] + '" /></div>');
	}
});

$('#input-affiliate').parent().find('.well').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

// Products
$('input[name=\'products\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'products\']').val('');

		$('#input-product' + item['value']).remove();

		$('#input-product').parent().find('.well').append('<div id="product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product[]" value="' + item['value'] + '" /></div>');
	}
});

$('#input-product').parent().find('.well').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

function send(url) {

 var selObj = document.getElementById('selected_product');
  for (var i=0; i<selObj.options.length; i++) {
    selObj.options[i].selected = true;
  }

	// Summernote fix
	$('.language_editor').each(function(){
		$(this).val($(this).code())
	});

	$.ajax({
		url: url,
		type: 'post',
		data: $('#content select, #content input, #content textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-send').button('loading');
		},
		complete: function() {
			$('#button-send').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['error']) {
				if (json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');
				}

				if (json['error']['subject']) {
					$('input[name=\'subject\']').after('<div class="text-danger">' + json['error']['subject'] + '</div>');
				}

				if (json['error']['message']) {
					$('textarea[name=\'message\']').parent().append('<div class="text-danger">' + json['error']['message'] + '</div>');
				}
			}

			if (json['next']) {
				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i>  ' + json['success'] + '</div>');

					send(json['next']);
				}
			} else {
				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
				}
			}
		}
	});
}
//--></script></div>
<?php echo $footer; ?>
