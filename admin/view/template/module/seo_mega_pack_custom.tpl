<?php require DIR_TEMPLATE . 'module/seo_mega_pack-header.tpl'; ?>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-js.tpl'; ?>

<script type="text/javascript">
	var seoMegaPack_variableTags = {};
</script>

				<?php if( $is_installed ) { ?>					
					<div class="tab-content">
						<div class="tab-pane active">
							<br />
							<div style="clear: both; overflow: hidden; padding: 0 10px 10px 10px;">
								<?php foreach( $languages as $lang ) { ?>
									<a class="btn btn-primary btn-sm" style="margin-left:5px;<?php if( $language_id != $lang['language_id'] ) { ?>opacity: 0.5;<?php } ?>" href="<?php echo $tab_action_custom; ?>&lang=<?php echo $lang['language_id']; ?>">
										<img src="<?php echo version_compare( VERSION, '2.2.0.0', '>=' ) ? 'language/' . $lang['code'] . '/' . $lang['code'] . '.png' : 'view/image/flags/' . $lang['image']; ?>" /> <?php echo $lang['name']; ?>
									</a>
								<?php } ?>

								<?php if( ! empty( $has_filter ) ) { ?>
									<a onclick="$('#clear_filter').val('1');$('#filter').submit();" class="btn btn-sm btn-danger pull-right">
										<i class="glyphicon glyphicon-remove"></i>
										<?php echo $button_clear_filter; ?>
									</a>
								<?php } ?>
							</div>

							<table class="table">
								<thead>
									<tr>
										<td width="45%" style="padding:5px"><?php echo $column_url; ?></td>
										<td style="padding:5px"><?php echo $column_seo_url; ?></td>
										<td width="100"></td>
									</tr>
								</thead>
								<tbody>
									<tr class="filter">
										<td style="font-size:10px">
											<input type="text" class="form-control" id="insert_url" value="<?php echo $filter_url; ?>" />
											e.g.: <i>http://your-shop.com/account/login</i> OR <i>/account/login</i>
										</td>
										<td style="font-size:10px">
											<input type="text" class="form-control" id="insert_seo_url" value="" />
											e.g.: <i>login</i>
										</td>
										<td style="text-align:center">
											<a id="insert-button" class="btn btn-sm btn-success">
												<i class="glyphicon glyphicon-plus-sign"></i>
												<?php echo $button_insert; ?>
											</a>
										</td>
									</tr>
								</tbody>
							</table>

							<?php if( ! empty( $has_filter ) || ! empty( $items ) ) { ?>
								<hr><br />

								<form action="<?php echo $tab_action_custom; ?>&lang=<?php echo $language_id; ?>" method="post" id="filter">
									<input type="hidden" name="clear_filter" vlaue="" id="clear_filter" />
									<table class="table">
										<thead>
											<tr>
												<td style="padding:5px"><?php echo $column_url; ?></td>
												<td style="padding:5px"><?php echo $column_seo_url; ?></td>
												<td class="text-center" width="150"><?php echo $text_click_to_edit; ?></td>
											</tr>
											<tr class="filter">
												<td><input type="text" class="form-control" name="filter_url" value="<?php echo $filter_url; ?>" /></td>
												<td><input type="text" class="form-control" name="filter_seo_url" value="<?php echo $filter_seo_url; ?>" /></td>
												<td class="text-center">
													<a onclick="$('#filter').submit();" class="btn btn-sm btn-primary">
														<i class="glyphicon glyphicon-search"></i>
														<?php echo $button_filter; ?>
													</a>
												</td>
											</tr>
										</thead>
										<tbody id="custom-values">
											<?php foreach( $items as $item ) { ?>
												<tr data-id="<?php echo $item['id']; ?>">
													<td style="padding:5px" data-name="url"><?php echo $item['url']; ?></td>
													<td style="padding:5px" data-name="seo_url"><?php echo $item['seo_url']; ?></td>
													<td class="text-center">
														<a data-id="<?php echo $item['id']; ?>" class="btn btn-danger btn-sm remove-item">
															<i class="glyphicon glyphicon-trash"></i> 
															<?php echo $button_remove; ?>
														</a>
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</form>

								<div class="pagination"><?php echo $pagination; ?></div>
							<?php } ?>
						</div>
					</div>
				<?php } else { ?>
					<br /><br /><center><?php echo $smk_error_warning; ?></center>
				<?php } ?>
					
<script type="text/javascript">	
	$('.remove-item').click(function(){
		if( ! confirm( '<?php echo $text_confirm_delete; ?>' ) ) return false;
	
		var $tr = $(this).parent().parent(),
			id	= $tr.attr('data-id');
		
		$tr.remove();
		$.get('index.php?route=module/seo_mega_pack/custom_save&token=<?php echo $token; ?>&lang=<?php echo $language_id; ?>&id=' + id);
	});
	
	$('#insert-button').click(function(){
		var url			= $('#insert_url').val();
		var seo_url		= $('#insert_seo_url').val();
		var _this		= $(this).hide();
		var loader		= $(SMP_LOADER);
		
		if( ! url ) {
			alert('<?php echo $alert_url_empty; ?>');
			_this.show();
		} else if( ! seo_url ) {
			alert('<?php echo $alert_seo_url_empty; ?>');
			_this.show();
		} else {
			_this.after( loader );
			
			$.post('index.php?route=module/seo_mega_pack/custom_insert&token=<?php echo $token; ?>&lang=<?php echo $language_id; ?>', {
				'url'		: url,
				'seo_url'	: seo_url
			}, function(response){				
				response = response.split('|');
				
				if( response[0] == '1' ) {
					window.location = 'index.php?route=module/seo_mega_pack/custom&token=<?php echo $token; ?>&lang=<?php echo $language_id; ?>';
				} else {
					loader.remove();
					_this.show();
					alert( response[1] );
				}
			});
		}
	});
	
	$('input[name^="filter_"]').each(function(){
		var name = $(this).attr('name').replace('filter_', '');
		$(this).autocomplete({
			delay: 500,
			source: function(request, response) {
				$.ajax({
					url: 'index.php?route=module/seo_mega_pack/autocomplete&token=<?php echo $token; ?>&lang=<?php echo $language_id; ?>&type=alias&name=' + name + '&filter=' +  encodeURIComponent(request.term),
					dataType: 'json',
					success: function(json) {		
						response($.map(json, function(item) {
							return {
								label: item.name,
								value: item.id
							}
						}));
					}
				});
			}, 
			select: function(event, ui) {
				$('input[name="filter_' + name + '"]').val(ui.item.label);

				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
	});
	
	function custom_values_unset() {
		$('#custom-values tr.is_edit').each(function(){
			var data = {},
				id = $(this).attr('data-id');
			
			$(this).removeClass('is_edit').find('[data-name]').each(function(){
				var name	= $(this).attr('data-name'),
					value	= $(this).find('input,textarea').val();
				
				data[name] = value;
				
				$(this).html(value);
			});
			
			$.get('index.php?route=module/seo_mega_pack/custom_save&token=<?php echo $token; ?>&lang=<?php echo $language_id; ?>&id=' + id, data);
		});
	}
	
	$('#custom-values tr').bind('click', function(e){
		if( $(this).hasClass( 'is_edit' ) ) return false;
		
		custom_values_unset();
	
		$(this).addClass('is_edit').find('[data-name]').each(function(){
			var name	= $(this).attr('data-name'),
				value	= $(this).text(),
				input	= $('<input type="text" class="form-control">').bind('keydown',function(e){
					if( e.keyCode == 13 )
						custom_values_unset();
				});
			
			$(this).html('').append( input.css('width', '99%').attr('name', name).val( value ) );
		});
		
		return false;
	});
	
	$('body').bind('click', function(){
		custom_values_unset();
	});
</script>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-footer.tpl'; ?>