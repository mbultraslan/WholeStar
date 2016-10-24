<?php require DIR_TEMPLATE . 'module/seo_mega_pack-header.tpl'; ?>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-js.tpl'; ?>

<script type="text/javascript">
	var seoMegaPack_variableTags = {};
</script>


				<?php if( $is_installed ) { ?>				
					<a href="<?php echo $action_invalid_urls; ?>" class="btn btn-info btn-sm pull-right" style="margin-top: 10px;">
						<i class="glyphicon glyphicon-link"></i> <?php echo $text_invalid_urls; ?>
					</a>
					
					<div class="tab-content">
						<div class="tab-pane active">
							<br />
							<div style="clear: both; overflow: hidden; padding: 0 10px 10px 10px;">
								<?php if( ! empty( $has_filter ) ) { ?><br />
									<a onclick="$('#clear_filter').val('1');$('#filter').submit();" class="btn btn-sm btn-danger pull-right">
										<i class="glyphicon glyphicon-remove"></i>
										<?php echo $button_clear_filter; ?>
									</a>
								<?php } ?>
							</div>

							<table class="table">
								<thead>
									<tr>
										<td width="45%" style="padding:5px"><?php echo $column_broken_link; ?></td>
										<td style="padding:5px"><?php echo $column_new_link; ?></td>
										<td width="100"></td>
									</tr>
								</thead>
								<tbody>
									<tr class="filter">
										<td style="font-size:10px">
											<input type="text" class="form-control" id="insert_broken_link" value="<?php echo $post_broken_link; ?>" />
											e.g.: <i>http://your-shop.com/category-name/product-name</i>
										</td>
										<td style="font-size:10px">
											<input type="text" class="form-control" id="insert_new_link" value="" />
											e.g.: <i>http://your-shop.com/category-new-name/product-new-name</i>
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

								<form action="<?php echo $tab_action_redirects; ?>" method="post" id="filter">
									<input type="hidden" name="clear_filter" vlaue="" id="clear_filter" />
									<table class="table">
										<thead>
											<tr>
												<td style="padding:5px" width="45%"><?php echo $column_broken_link; ?></td>
												<td style="padding:5px" width="45%"><?php echo $column_new_link; ?></td>
												<td class="text-center" width="10%"><?php echo $text_click_to_edit; ?></td>
											</tr>
											<tr class="filter">
												<td><input type="text" class="form-control" name="filter_broken_link" value="<?php echo $filter_broken_link; ?>" /></td>
												<td><input type="text" class="form-control" name="filter_new_link" value="<?php echo $filter_new_link; ?>" /></td>
												<td class="text-center">
													<a onclick="$('#filter').submit();" class="btn btn-sm btn-primary">
														<i class="glyphicon glyphicon-search"></i>
														<?php echo $button_filter; ?>
													</a>
												</td>
											</tr>
										</thead>
										<tbody id="redirects-values">
											<?php foreach( $items as $item ) { ?>
												<tr data-id="<?php echo $item['id']; ?>">
													<td style="padding:5px" data-name="broken_link"><?php echo $item['broken_link']; ?></td>
													<td style="padding:5px" data-name="new_link"><?php echo $item['new_link']; ?></td>
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
		$.get('index.php?route=module/seo_mega_pack/redirects_save&token=<?php echo $token; ?>&id=' + id);
	});
	
	$('#insert-button').click(function(){
		var broken_link	= $('#insert_broken_link').val();
		var new_link	= $('#insert_new_link').val();
		var _this		= $(this).hide();
		var loader		= $(SMP_LOADER);
		
		if( ! broken_link ) {
			alert('<?php echo $alert_broken_link_empty; ?>');
			_this.show();
		} else if( ! new_link ) {
			alert('<?php echo $alert_new_link_empty; ?>');
			_this.show();
		} else {
			_this.after( loader );
			
			$.post('index.php?route=module/seo_mega_pack/redirects_insert&token=<?php echo $token; ?>', {
				'broken_link'	: broken_link,
				'new_link'		: new_link
			}, function(response){				
				response = response.split('|');
				
				if( response[0] == '1' ) {
					window.location = 'index.php?route=module/seo_mega_pack/redirects&token=<?php echo $token; ?>';
				} else {
					loader.remove();
					_this.show();
					alert( response[1] );
				}
			});
		}
	});
	
	function redirects_values_unset() {
		$('#redirects-values tr.is_edit').each(function(){
			var data = {},
				id = $(this).attr('data-id');
			
			$(this).removeClass('is_edit').find('[data-name]').each(function(){
				var name	= $(this).attr('data-name'),
					value	= $(this).find('input,textarea').val();
				
				data[name] = value;
				
				$(this).html(value);
			});
			
			$.get('index.php?route=module/seo_mega_pack/redirects_save&token=<?php echo $token; ?>&id=' + id, data);
		});
	}
	
	$('#redirects-values tr').bind('click', function(e){
		if( $(this).hasClass( 'is_edit' ) ) return false;
		
		redirects_values_unset();
	
		$(this).addClass('is_edit').find('[data-name]').each(function(){
			var name	= $(this).attr('data-name'),
				value	= $(this).text(),
				input	= $('<input type="text" class="form-control">').bind('keydown',function(e){
					if( e.keyCode == 13 )
						redirects_values_unset();
				});
			
			$(this).html('').append( input.css('width', '99%').attr('name', name).val( value ) );
		});
		
		return false;
	});
	
	$('body').bind('click', function(){
		redirects_values_unset();
	});
</script>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-footer.tpl'; ?>