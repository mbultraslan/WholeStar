<?php require DIR_TEMPLATE . 'module/seo_mega_pack-header.tpl'; ?>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-js.tpl'; ?>

<script type="text/javascript">
	var seoMegaPack_variableTags = {};
</script>


				<?php if( $is_installed ) { ?>				
					<a href="<?php echo $action_clear_all; ?>" class="btn btn-danger btn-sm pull-right" style="margin-top: 10px;">
						<i class="glyphicon glyphicon-remove"></i> <?php echo $button_clear_all; ?>
					</a>
					<br /><br />
					<div class="tab-content">
						<div class="tab-pane active">
							<br />

							<?php if( ! empty( $items ) ) { ?>
								<form action="<?php echo $tab_action_redirects; ?>" method="post" id="filter">
									<input type="hidden" name="clear_filter" vlaue="" id="clear_filter" />
									<table class="table">
										<thead>
											<tr>
												<td style="padding:5px; width: auto;"><?php echo $column_broken_link; ?></td>
												<td class="text-center" style="padding:5px; width: 100px;"><?php echo $column_frequency; ?></td>
												<td class="text-center" style="padding:5px; width: 100px;"><?php echo $column_date; ?></td>
												<td class="text-center" style="width:100px;"></td>
											</tr>
										</thead>
										<tbody>
											<?php foreach( $items as $item ) { ?>
												<tr data-id="<?php echo $item['failed_url_id']; ?>">
													<td style="padding:5px; width: auto;"><input type="text" class="form-control" value="<?php echo $item['url']; ?>" readonly="readonly" /></td>
													<td class="text-center" style="padding:5px"><?php echo $item['frequency']; ?></td>
													<td class="text-center" style="padding:5px"><?php echo $item['created_at']; ?></td>
													<td class="text-center">
														<a data-id="<?php echo $item['failed_url_id']; ?>" class="btn btn-danger btn-sm remove-item">
															<i class="glyphicon glyphicon-trash"></i>
														</a>
														<a data-url="<?php echo $item['url']; ?>" class="btn btn-primary btn-sm">
															<i class="glyphicon glyphicon-random"></i>
														</a>
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</form>

								<div class="pagination"><?php echo $pagination; ?></div>
							<?php } else { ?>
								<center><?php echo $text_list_is_empty; ?></center>
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
		$.get('index.php?route=module/seo_mega_pack/invalid_urls_remove&token=<?php echo $token; ?>&id=' + id);
	});
	
	$('a[data-url]').click(function(){
		$('<form>')
			.appendTo($('body'))
			.attr({
				'action'	: '<?php echo $tab_action_redirects; ?>'.replace('&amp;','&'),
				'method'	: 'post'
			})
			.append($('<input type="text" name="post_broken_link">').val($(this).attr('data-url')))
			.hide()
			.submit();
		
		return false;
	});
</script>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-footer.tpl'; ?>