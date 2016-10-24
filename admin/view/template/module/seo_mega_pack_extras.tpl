<?php require DIR_TEMPLATE . 'module/seo_mega_pack-header.tpl'; ?>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-js.tpl'; ?>

<script type="text/javascript">
	var seoMegaPack_variableTags = {};
</script>

<?php if( $is_installed ) { ?>					
	<div class="tab-content">
		<div class="tab-pane active">
			<br />
			<div class="col-xs-2">
				<ul class="nav nav-tabs tabs-left">
					<?php foreach( $stores as $store ) { ?>
						<li><a href="#g-stores-<?php echo $store['store_id']; ?>" data-toggle="tab"><?php echo $store['name']; ?></a></li>
					<?php } ?>
				</ul>
			</div>
						
			<div class="col-xs-10">
				<div class="tab-content">
					<?php foreach( $stores as $store ) { ?>
						<div class="tab-pane" id="g-stores-<?php echo $store['store_id']; ?>">
							<div class="col-xs-2">
								<ul class="nav nav-tabs tabs-left">
									<li><a href="#group-facebook-widget-<?php echo $store['store_id']; ?>" data-toggle="tab"><?php echo $extras_facebook_widget; ?></a></li>
									<li><a href="#group-google-plus-widget-<?php echo $store['store_id']; ?>" data-toggle="tab"><?php echo $extras_google_plus_widget; ?></a></li>
								<ul>
							</div>
			
							<div class="col-xs-10">
								<div class="tab-content">
									<div class="tab-pane" id="group-facebook-widget-<?php echo $store['store_id']; ?>">
										<table class="table table-tbody">
											<tr>
												<td width="250" class="vertical-middle">
													<?php echo $entry_enabled; ?>
												</td>
												<td>
													<input type="checkbox" name="smp_extras[<?php echo $store['store_id']; ?>][facebook_widget][enabled]" value="1" <?php if( ! empty( $extras[$store['store_id']]['facebook_widget']['enabled'] ) ) { ?>checked="checked"<?php } ?> />
												</td>
											</tr>
											<tr>
												<td width="250" class="vertical-middle">
													<?php echo $entry_small_header; ?>
												</td>
												<td>
													<input type="checkbox" name="smp_extras[<?php echo $store['store_id']; ?>][facebook_widget][small_header]" value="1" <?php if( ! empty( $extras[$store['store_id']]['facebook_widget']['small_header'] ) ) { ?>checked="checked"<?php } ?> />
												</td>
											</tr>
											<tr>
												<td width="250" class="vertical-middle">
													<?php echo $entry_show_faces; ?>
												</td>
												<td>
													<input type="checkbox" name="smp_extras[<?php echo $store['store_id']; ?>][facebook_widget][show_faces]" value="1" <?php if( ! empty( $extras[$store['store_id']]['facebook_widget']['show_faces'] ) ) { ?>checked="checked"<?php } ?> />
												</td>
											</tr>
											<tr>
												<td width="250" class="vertical-middle">
													<?php echo $entry_show_posts; ?>
												</td>
												<td>
													<input type="checkbox" name="smp_extras[<?php echo $store['store_id']; ?>][facebook_widget][show_posts]" value="1" <?php if( ! empty( $extras[$store['store_id']]['facebook_widget']['show_posts'] ) ) { ?>checked="checked"<?php } ?> />
												</td>
											</tr>
											<tr>
												<td width="250" class="vertical-middle">
													<?php echo $entry_fb_url; ?>
												</td>
												<td>
													<input class="form-control" name="smp_extras[<?php echo $store['store_id']; ?>][facebook_widget][url]" value="<?php echo empty( $extras[$store['store_id']]['facebook_widget']['url'] ) ? '' : $extras[$store['store_id']]['facebook_widget']['url']; ?>" />
												</td>
											</tr>
											<tr>
												<td class="vertical-middle">
													<?php echo $entry_width; ?>
												</td>
												<td>
													<input class="form-control" name="smp_extras[<?php echo $store['store_id']; ?>][facebook_widget][width]" value="<?php echo empty( $extras[$store['store_id']]['facebook_widget']['width'] ) ? '300' : $extras[$store['store_id']]['facebook_widget']['width']; ?>" />
												</td>
											</tr>
											<tr>
												<td class="vertical-middle">
													<?php echo $entry_height; ?>
												</td>
												<td>
													<input class="form-control" name="smp_extras[<?php echo $store['store_id']; ?>][facebook_widget][height]" value="<?php echo empty( $extras[$store['store_id']]['facebook_widget']['height'] ) ? '300' : $extras[$store['store_id']]['facebook_widget']['height']; ?>" />
												</td>
											</tr>
											<tr>
												<td class="vertical-middle">
													<?php echo $entry_margin_top; ?>
												</td>
												<td>
													<input class="form-control" name="smp_extras[<?php echo $store['store_id']; ?>][facebook_widget][margin_top]" value="<?php echo empty( $extras[$store['store_id']]['facebook_widget']['margin_top'] ) ? '100' : $extras[$store['store_id']]['facebook_widget']['margin_top']; ?>" />
												</td>
											</tr>
											<tr>
												<td class="vertical-middle">
													<?php echo $entry_position; ?>
												</td>
												<td>
													<select class="form-control" name="smp_extras[<?php echo $store['store_id']; ?>][facebook_widget][position]">
														<option value="right"<?php if( ! empty( $extras[$store['store_id']]['facebook_widget']['position'] ) && $extras[$store['store_id']]['facebook_widget']['position'] == 'right' ) { ?> selected="selected"<?php } ?>><?php echo $text_position_right; ?></option>
														<option value="left"<?php if( ! empty( $extras[$store['store_id']]['facebook_widget']['position'] ) && $extras[$store['store_id']]['facebook_widget']['position'] == 'left' ) { ?> selected="selected"<?php } ?>><?php echo $text_position_left; ?></option>
													</select>
												</td>
											</tr>
											<tr>
												<td class="vertical-middle">
													<?php echo $entry_color_scheme; ?>
												</td>
												<td>
													<select class="form-control" name="smp_extras[<?php echo $store['store_id']; ?>][facebook_widget][colorscheme]">
														<option value="light"<?php if( ! empty( $extras[$store['store_id']]['facebook_widget']['colorscheme'] ) && $extras[$store['store_id']]['facebook_widget']['colorscheme'] == 'light' ) { ?> selected="selected"<?php } ?>><?php echo $text_colorscheme_light; ?></option>
														<option value="dark"<?php if( ! empty( $extras[$store['store_id']]['facebook_widget']['colorscheme'] ) && $extras[$store['store_id']]['facebook_widget']['colorscheme'] == 'dark' ) { ?> selected="selected"<?php } ?>><?php echo $text_colorscheme_dark; ?></option>
													</select>
												</td>
											</tr>
											<tr>
												<td>
													<?php echo $entry_image; ?>
												</td>
												<td>
													<table class="table">
														<thead>
															<tr>
																<td class="text-center"><?php echo $text_custom_image; ?></td>
																<td width="30%" class="text-center"><?php echo $text_if_position_is_left; ?></td>
																<td width="30%" class="text-center"><?php echo $text_if_position_is_right; ?></td>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td class="text-center">
																	<div class="image">
																		<a 
																			href="" 
																			id="thumb-image2-<?php echo $store['store_id']; ?>" 
																			data-toggle="image" 
																			class="img-thumbnail"
																		>
																			<img 
																				src="<?php echo empty( $extras[$store['store_id']]['facebook_widget']['thumb'] ) ? $no_image : $extras[$store['store_id']]['facebook_widget']['thumb']; ?>" 
																				alt="" 
																				title="" 
																				data-placeholder="<?php echo $no_image; ?>" 
																			/>
																		</a>
																		<input 
																			type="hidden" 
																			name="smp_extras[<?php echo $store['store_id']; ?>][facebook_widget][image]" 
																			value="<?php echo empty( $extras[$store['store_id']]['facebook_widget']['image'] ) ? '' : $extras[$store['store_id']]['facebook_widget']['image']; ?>" 
																			id="input-image2-<?php echo $store['store_id']; ?>" 
																		/>
																	</div>
																</td>
																<td class="text-center">
																	<img src="../catalog/view/theme/default/smp/img/fb-left.png" class="image-thumbnail" />
																</td>
																<td class="text-center">
																	<img src="../catalog/view/theme/default/smp/img/fb-right.png" class="image-thumbnail" />
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</table>
									</div>
										
									<div class="tab-pane" id="group-google-plus-widget-<?php echo $store['store_id']; ?>">
										<table class="table table-tbody">
											<tr>
												<td width="250" class="vertical-middle">
													<?php echo $entry_enabled; ?>
												</td>
												<td>
													<input type="checkbox" name="smp_extras[<?php echo $store['store_id']; ?>][g_plus_widget][enabled]" value="1" <?php if( ! empty( $extras[$store['store_id']]['g_plus_widget']['enabled'] ) ) { ?>checked="checked"<?php } ?> />
												</td>
											</tr>
											<tr>
												<td width="250" class="vertical-middle">
													<?php echo $entry_layout; ?>
												</td>
												<td>
													<input type="radio" name="smp_extras[<?php echo $store['store_id']; ?>][g_plus_widget][layout]" value="portrait" <?php if( ( ! empty( $extras[$store['store_id']]['g_plus_widget']['layout'] ) && $extras[$store['store_id']]['g_plus_widget']['layout'] == 'portrait' ) || ! isset( $extras[$store['store_id']]['g_plus_widget']['layout'] ) ) { ?>checked="checked"<?php } ?> /> <?php echo $text_layout_portrait; ?>
													<input type="radio" name="smp_extras[<?php echo $store['store_id']; ?>][g_plus_widget][layout]" value="landscape" <?php if( ! empty( $extras[$store['store_id']]['g_plus_widget']['layout'] ) && $extras[$store['store_id']]['g_plus_widget']['layout'] == 'landscape' ) { ?>checked="checked"<?php } ?> /> <?php echo $text_layout_landscape; ?>
												</td>
											</tr>
											<tr>
												<td width="250" class="vertical-middle">
													<?php echo $entry_type_account; ?>
												</td>
												<td>
													<input type="radio" name="smp_extras[<?php echo $store['store_id']; ?>][g_plus_widget][type_account]" value="person" <?php if( ( ! empty( $extras[$store['store_id']]['g_plus_widget']['type_account'] ) && $extras[$store['store_id']]['g_plus_widget']['type_account'] == 'person' ) || ! isset( $extras[$store['store_id']]['g_plus_widget']['type_account'] ) ) { ?>checked="checked"<?php } ?> /> <?php echo $text_type_account_person; ?>
													<input type="radio" name="smp_extras[<?php echo $store['store_id']; ?>][g_plus_widget][type_account]" value="page" <?php if( ! empty( $extras[$store['store_id']]['g_plus_widget']['type_account'] ) && $extras[$store['store_id']]['g_plus_widget']['type_account'] == 'page' ) { ?>checked="checked"<?php } ?> /> <?php echo $text_type_account_page; ?>
												</td>
											</tr>
											<tr>
												<td width="250" class="vertical-middle">
													<?php echo $entry_g_plus_url; ?>
												</td>
												<td>
													<input class="form-control" name="smp_extras[<?php echo $store['store_id']; ?>][g_plus_widget][url]" value="<?php echo empty( $extras[$store['store_id']]['g_plus_widget']['url'] ) ? '' : $extras[$store['store_id']]['g_plus_widget']['url']; ?>" />
												</td>
											</tr>
											<tr>
												<td class="vertical-middle">
													<?php echo $entry_width; ?>
												</td>
												<td>
													<input class="form-control" name="smp_extras[<?php echo $store['store_id']; ?>][g_plus_widget][width]" value="<?php echo empty( $extras[$store['store_id']]['g_plus_widget']['width'] ) ? '300' : $extras[$store['store_id']]['g_plus_widget']['width']; ?>" />
												</td>
											</tr>
											<tr>
												<td class="vertical-middle">
													<?php echo $entry_height; ?>
												</td>
												<td>
													<input class="form-control" name="smp_extras[<?php echo $store['store_id']; ?>][g_plus_widget][height]" value="<?php echo empty( $extras[$store['store_id']]['g_plus_widget']['height'] ) ? '300' : $extras[$store['store_id']]['g_plus_widget']['height']; ?>" />
												</td>
											</tr>
											<tr>
												<td class="vertical-middle">
													<?php echo $entry_margin_top; ?>
												</td>
												<td>
													<input class="form-control" name="smp_extras[<?php echo $store['store_id']; ?>][g_plus_widget][margin_top]" value="<?php echo empty( $extras[$store['store_id']]['g_plus_widget']['margin_top'] ) ? '155' : $extras[$store['store_id']]['g_plus_widget']['margin_top']; ?>" />
												</td>
											</tr>
											<tr>
												<td class="vertical-middle">
													<?php echo $entry_position; ?>
												</td>
												<td>
													<select class="form-control" name="smp_extras[<?php echo $store['store_id']; ?>][g_plus_widget][position]">
														<option value="right"<?php if( ! empty( $extras[$store['store_id']]['g_plus_widget']['position'] ) && $extras[$store['store_id']]['g_plus_widget']['position'] == 'right' ) { ?> selected="selected"<?php } ?>><?php echo $text_position_right; ?></option>
														<option value="left"<?php if( ! empty( $extras[$store['store_id']]['g_plus_widget']['position'] ) && $extras[$store['store_id']]['g_plus_widget']['position'] == 'left' ) { ?> selected="selected"<?php } ?>><?php echo $text_position_left; ?></option>
													</select>
												</td>
											</tr>
											<tr>
												<td class="vertical-middle">
													<?php echo $entry_color_scheme; ?>
												</td>
												<td>
													<select class="form-control" name="smp_extras[<?php echo $store['store_id']; ?>][g_plus_widget][colorscheme]">
														<option value="light"<?php if( ! empty( $extras[$store['store_id']]['g_plus_widget']['colorscheme'] ) && $extras[$store['store_id']]['g_plus_widget']['colorscheme'] == 'light' ) { ?> selected="selected"<?php } ?>><?php echo $text_colorscheme_light; ?></option>
														<option value="dark"<?php if( ! empty( $extras[$store['store_id']]['g_plus_widget']['colorscheme'] ) && $extras[$store['store_id']]['g_plus_widget']['colorscheme'] == 'dark' ) { ?> selected="selected"<?php } ?>><?php echo $text_colorscheme_dark; ?></option>
													</select>
												</td>
											</tr>
											<tr>
												<td>
													<?php echo $entry_image; ?>
												</td>
												<td>
													<table class="table">
														<thead>
															<tr>
																<td class="text-center"><?php echo $text_custom_image; ?></td>
																<td width="30%" class="text-center"><?php echo $text_if_position_is_left; ?></td>
																<td width="30%" class="text-center"><?php echo $text_if_position_is_right; ?></td>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td class="text-center">
																	<div class="image">
																		<a 
																			href="" 
																			id="thumb-image1-<?php echo $store['store_id']; ?>" 
																			data-toggle="image" 
																			class="img-thumbnail"
																		>
																			<img 
																				src="<?php echo empty( $extras[$store['store_id']]['g_plus_widget']['thumb'] ) ? $no_image : $extras[$store['store_id']]['g_plus_widget']['thumb']; ?>" 
																				alt="" 
																				title="" 
																				data-placeholder="<?php echo $no_image; ?>" 
																			/>
																		</a>
																		<input 
																			type="hidden" 
																			name="smp_extras[<?php echo $store['store_id']; ?>][g_plus_widget][image]" 
																			value="<?php echo empty( $extras[$store['store_id']]['g_plus_widget']['image'] ) ? '' : $extras[$store['store_id']]['g_plus_widget']['image']; ?>" 
																			id="input-image1-<?php echo $store['store_id']; ?>" 
																		/>
																	</div>
																</td>
																<td class="text-center">
																	<img src="../catalog/view/theme/default/smp/img/g_plus-left.png" class="image-thumbnail" />
																</td>
																<td class="text-center">
																	<img src="../catalog/view/theme/default/smp/img/g_plus-right.png" class="image-thumbnail" />
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							
							<div class="clearfix"></div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php } else { ?>
	<span><?php echo $error_warning; ?></span>
<?php } ?>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-footer.tpl'; ?>