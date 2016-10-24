<?php require DIR_TEMPLATE . 'module/seo_mega_pack-header.tpl'; ?>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-js.tpl'; ?>

<script type="text/javascript">
	var seoMegaPack_variableTags = {};
</script>


				<?php if( $is_installed ) { ?>				
					<div class="pull-right" style="padding-top: 5px; font-weight: bold">
						<?php echo $text_click_to_edit; ?>
					</div>
					
					<div class="tab-content">
						<div class="tab-pane active">
							<br />
							<ul class="nav nav-tabs">
								<?php foreach( $managers as $name => $manager ) { ?>
									<li<?php if( $type == $name ) { ?> class="active"<?php } ?>>
										<a 
											<?php echo $manager->installed() ? 'href="' . $tab_action_manager . '&type=' . $name . '"' : ''; ?>>
												<?php echo $manager->getTabLabel(); ?>
										</a>
									</li>
								<?php } ?>
							</ul>
							
							<div class="tab-content">
								<div class="tab-pane active">
									<br />
									<div style="clear: both; overflow: hidden; padding: 0 10px 10px 10px;">
										<?php if( $managers[$type]->isMultilanguage() ) { ?>
											<?php foreach( $languages as $lang ) { ?>
												<a class="btn btn-sm btn-primary" style="<?php if( $language_id != $lang['language_id'] ) { ?>opacity: 0.5;<?php } ?>" href="<?php echo $tab_action_manager; ?>&type=<?php echo $type; ?>&lang=<?php echo $lang['language_id']; ?>">
													<img src="<?php echo version_compare( VERSION, '2.2.0.0', '>=' ) ? 'language/' . $lang['code'] . '/' . $lang['code'] . '.png' : 'view/image/flags/' . $lang['image']; ?>" /> <?php echo $lang['name']; ?>
												</a>
											<?php } ?>
										<?php } ?>

										<a onclick="$('#filter').submit();" class="btn btn-sm btn-primary pull-right">
											<i class="glyphicon glyphicon-search"></i>
											<?php echo $button_filter; ?>
										</a>

										<?php if( ! empty( $has_filter ) ) { ?>
											<a onclick="$('#clear_filter').val('1');$('#filter').submit();" style="margin-right:10px" class="btn btn-sm btn-danger pull-right">
												<i class="glyphicon glyphicon-remove"></i>
												<?php echo $button_clear_filter; ?>
											</a>
										<?php } ?>
									</div>

									<form action="<?php echo $tab_action_manager; ?>&type=<?php echo $type; ?>&lang=<?php echo $language_id; ?>" method="post" id="filter">
										<input type="hidden" name="clear_filter" vlaue="" id="clear_filter" />
										<table class="table list">
											<thead>
												<tr>
													<?php if( $managers[$type]->hasColumnName() ) { ?>
														<td style="padding:5px" width="200"><?php echo $column_name; ?></td>
													<?php } ?>

													<?php if( $managers[$type]->hasColumnUrl() ) { ?>
														<td style="padding:5px">
															<?php echo $column_seo_url; ?>
														</td>
													<?php } ?>

													<?php if( $managers[$type]->hasColumnTitle() ) { ?>
														<td style="padding:5px"><?php echo $column_seo_title; ?></td>
													<?php } ?>

													<?php if( $managers[$type]->hasColumnHeadingTitle() ) { ?>
														<td style="padding:5px"><?php echo $column_seo_h1_title; ?></td>
													<?php } ?>

													<?php if( $managers[$type]->hasColumnMetaKeywords() ) { ?>
														<td style="padding:5px"><?php echo $column_meta_keywords; ?></td>
													<?php } ?>

													<?php if( $managers[$type]->hasColumnMetaDescription() ) { ?>
														<td style="padding:5px"><?php echo $column_meta_description; ?></td>
													<?php } ?>

													<?php if( $managers[$type]->hasColumnTags() ) { ?>
														<td style="padding:5px"><?php echo $column_tags; ?></td>
													<?php } ?>
												</tr>
												<tr>
													<?php if( $managers[$type]->hasColumnName() ) { ?>
														<td><input class="form-control" type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
													<?php } ?>

													<?php if( $managers[$type]->hasColumnUrl() ) { ?>
														<td><input class="form-control" type="text" name="filter_seo_url" value="<?php echo $filter_seo_url; ?>" /></td>
													<?php } ?>

													<?php if( $managers[$type]->hasColumnTitle() ) { ?>
														<td><input class="form-control" type="text" name="filter_seo_title" value="<?php echo $filter_seo_title; ?>" /></td>
													<?php } ?>

													<?php if( $managers[$type]->hasColumnHeadingTitle() ) { ?>
														<td><input class="form-control" type="text" name="filter_seo_h1_title" value="<?php echo $filter_seo_h1_title; ?>" /></td>
													<?php } ?>

													<?php if( $managers[$type]->hasColumnMetaKeywords() ) { ?>
														<td><input class="form-control" type="text" name="filter_meta_keyword" value="<?php echo $filter_meta_keyword; ?>" /></td>
													<?php } ?>

													<?php if( $managers[$type]->hasColumnMetaDescription() ) { ?>
														<td><input class="form-control" type="text" name="filter_meta_description" value="<?php echo $filter_meta_description; ?>" /></td>
													<?php } ?>

													<?php if( $managers[$type]->hasColumnTags() ) { ?>
														<td><input class="form-control" type="text" name="filter_tag" value="<?php echo $filter_tag; ?>" /></td>
													<?php } ?>
												</tr>
											</thead>
											<tbody id="manager-values">
												<?php foreach( $items as $item ) { ?>
													<tr data-id="<?php echo $item['id']; ?>">
														<?php if( $managers[$type]->hasColumnName() ) { ?>
															<td style="padding:5px"><?php echo $item['name']; ?></td>
														<?php } ?>

														<?php if( $managers[$type]->hasColumnUrl() ) { ?>
															<td style="padding:5px" data-name="seo_url"><?php echo $item['seo_url']; ?></td>
														<?php } ?>

														<?php if( $managers[$type]->hasColumnTitle() ) { ?>
															<td style="padding:5px" data-name="seo_title"><?php echo $item['seo_title']; ?></td>
														<?php } ?>

														<?php if( $managers[$type]->hasColumnHeadingTitle() ) { ?>
															<td style="padding:5px" data-name="seo_h1_title"><?php echo $item['seo_h1_title']; ?></td>
														<?php } ?>

														<?php if( $managers[$type]->hasColumnMetaKeywords() ) { ?>
															<td style="padding:5px" data-name="meta_keywords"><?php echo $item['meta_keyword']; ?></td>
														<?php } ?>

														<?php if( $managers[$type]->hasColumnMetaDescription() ) { ?>
															<td style="padding:5px" data-name="meta_description"><?php echo $item['meta_description']; ?></td>
														<?php } ?>

														<?php if( $managers[$type]->hasColumnTags() ) { ?>
															<td style="padding:5px" data-name="tags"><?php echo $item['tag']; ?></td>
														<?php } ?>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</form>

									<div class="pagination"><?php echo $pagination; ?></div>
								</div>
							</div>
						</div>
					</div>
				<?php } else { ?>
					<br /><br /><center><?php echo $smk_error_warning; ?></center>
				<?php } ?>
					
<script type="text/javascript">	
	$('input[name^="filter_"]').each(function(){
		var name = $(this).attr('name').replace('filter_', '');
		$(this).autocomplete({
			delay: 500,
			source: function(request, response) {
				$.ajax({
					url: 'index.php?route=module/seo_mega_pack/autocomplete&token=<?php echo $token; ?>&lang=<?php echo $language_id; ?>&type=<?php echo $type; ?>&name=' + name + '&filter=' +  encodeURIComponent(request.term),
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
	
	function manager_values_unset() {
		$('#manager-values tr.is_edit').each(function(){
			var data = {},
				id = $(this).attr('data-id');
			
			$(this).removeClass('is_edit').find('[data-name]').each(function(){
				var name	= $(this).attr('data-name'),
					value	= $(this).find('input,textarea').val();
				
				data[name] = value;
				
				$(this).html(value);
			});
			
			$.post('index.php?route=module/seo_mega_pack/manager_save&token=<?php echo $token; ?>&lang=<?php echo $language_id; ?>&type=<?php echo $type; ?>&id=' + id, data);
		});
	}
	
	$('#manager-values tr').bind('click', function(e){
		if( $(this).hasClass( 'is_edit' ) ) return false;
		
		manager_values_unset();
	
		$(this).addClass('is_edit').find('[data-name]').each(function(){
			var name	= $(this).attr('data-name'),
				value	= $(this).text(),
				input;
			
			if( name == 'seo_url' || name == 'seo_title' || name == 'seo_h1_title' )
				input = $('<input type="text" class="form-control" style="font-size:12px; padding:1px;">').bind('keydown',function(e){
					if( e.keyCode == 13 )
						manager_values_unset();
				});
			else
				input = $('<textarea style="height:40px; font-size:12px; padding:1px;" class="form-control">');
			
			$(this).html('').append( input.css('width', '99%').attr('name', name).val( value ) );
		});
		
		return false;
	});
	
	$('body').bind('click', function(){
		manager_values_unset();
	});
</script>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-footer.tpl'; ?>