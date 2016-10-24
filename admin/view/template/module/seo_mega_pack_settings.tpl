<?php require DIR_TEMPLATE . 'module/seo_mega_pack-header.tpl'; ?>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-js.tpl'; ?>

<?php if( $is_installed ) { ?>
	<div class="tab-content">
		<div class="tab-pane active">
			<br />
			<div class="col-xs-2">
				<ul class="nav nav-tabs tabs-left">
					<li><a href="#group-main" data-toggle="tab"><?php echo $settings_main; ?></a></li>
					<li><a href="#group-stores" data-toggle="tab"><?php echo $settings_stores; ?></a></li>
					<li><a href="#group-webmaster-tools" data-toggle="tab"><?php echo $settings_webmaster_tools; ?></a></li>
					<li><a href="#group-google-analytics" data-toggle="tab"><?php echo $settings_google_analytics; ?></a></li>
					<li><a href="#group-social" data-toggle="tab"><?php echo $settings_social; ?></a></li>
					<li><a href="#group-sitemaps" data-toggle="tab"><?php echo $settings_sitemaps; ?></a></li>
					<li><a href="#group-url" data-toggle="tab"><?php echo $settings_url; ?></a></li>
					<li><a href="#group-language" data-toggle="tab"><?php echo $settings_language; ?></a></li>
					<li><a href="#group-skip-urls" data-toggle="tab"><?php echo $settings_skip_urls; ?></a></li>
				<ul>
			</div>
								
			<div class="col-xs-10">
				<div class="tab-content">
					<div class="tab-pane" id="group-main">
						<table class="table">
							<thead>
								<tr>
									<td width="350" style="padding:5px"><?php echo $column_parameter; ?></td>
									<td style="padding:5px"><?php echo $column_description; ?></td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<center>
											<?php echo $s_smp_cache; ?><br />
											<input type="checkbox" name="smp_cache" value="1" <?php if( ! empty( $settings['smp_cache'] ) ) { ?>checked="checked"<?php } ?> />
										</center>
									</td>
									<td>
										<?php echo $s_smp_cache_desc; ?>

										<a href="<?php echo $action_clear_cache ?>" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-trash"></i> <?php echo $text_clear_cache; ?></a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					
					<div class="tab-pane" id="group-stores">
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
										<table class="table table-tbody">
											<tr>
												<td width="150" class="vertical-middle text-center">
													<?php echo $entry_meta_title; ?>
												</td>
												<td>
													<table class="table table-tbody" style="margin: 0">
														<?php foreach( $languages as $language ) { ?>
															<tr>
																<td>
																	<input 
																		type="text" 
																		class="form-control" 
																		name="smp_meta_stores[<?php echo $store['store_id']; ?>][title][<?php echo $language['language_id']; ?>]" 
																		value="<?php echo ! empty( $settings['smp_meta_stores'][$store['store_id']]['title'][$language['language_id']] ) ? $settings['smp_meta_stores'][$store['store_id']]['title'][$language['language_id']] : $val_default_meta_title; ?>" 
																	/>
																</td>
																<td width="1" class="vertical-middle">
																	<img src="<?php echo version_compare( VERSION, '2.2.0.0', '>=' ) ? 'language/' . $language['code'] . '/' . $language['code'] . '.png' : 'view/image/flags/' . $language['image']; ?>" alt="<?php echo $language['name']; ?>" />
																</td>
															</tr>
														<?php } ?>
													</table>
												</td>
											</tr>
											<tr>
												<td class="vertical-middle text-center">
													<?php echo $entry_meta_description; ?>
												</td>
												<td>
													<table class="table table-tbody" style="margin: 0">
														<?php foreach( $languages as $language ) { ?>
															<tr>
																<td>
																	<textarea
																		class="form-control" 
																		name="smp_meta_stores[<?php echo $store['store_id']; ?>][description][<?php echo $language['language_id']; ?>]"><?php echo ! empty( $settings['smp_meta_stores'][$store['store_id']]['description'][$language['language_id']] ) ? $settings['smp_meta_stores'][$store['store_id']]['description'][$language['language_id']] : $val_default_meta_description; ?></textarea>
																</td>
																<td width="1" class="vertical-middle">
																	<img src="<?php echo version_compare( VERSION, '2.2.0.0', '>=' ) ? 'language/' . $language['code'] . '/' . $language['code'] . '.png' : 'view/image/flags/' . $language['image']; ?>" alt="<?php echo $language['name']; ?>" />
																</td>
															</tr>
														<?php } ?>
													</table>
												</td>
											</tr>
											<tr>
												<td class="vertical-middle text-center">
													<?php echo $entry_meta_keywords; ?>
												</td>
												<td>
													<table class="table table-tbody" style="margin: 0">
														<?php foreach( $languages as $language ) { ?>
															<tr>
																<td>
																	<textarea
																		class="form-control" 
																		name="smp_meta_stores[<?php echo $store['store_id']; ?>][keywords][<?php echo $language['language_id']; ?>]"><?php echo ! empty( $settings['smp_meta_stores'][$store['store_id']]['keywords'][$language['language_id']] ) ? $settings['smp_meta_stores'][$store['store_id']]['keywords'][$language['language_id']] : $val_default_meta_keywords; ?></textarea>
																</td>
																<td width="1" class="vertical-middle">
																	<img src="<?php echo version_compare( VERSION, '2.2.0.0', '>=' ) ? 'language/' . $language['code'] . '/' . $language['code'] . '.png' : 'view/image/flags/' . $language['image']; ?>" alt="<?php echo $language['name']; ?>" />
																</td>
															</tr>
														<?php } ?>
													</table>
												</td>
											</tr>
										</table>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
					
					<div class="tab-pane" id="group-webmaster-tools">
						<div class="col-xs-2">
							<ul class="nav nav-tabs tabs-left">
								<?php foreach( $stores as $store ) { ?>
									<li><a href="#gwt-stores-<?php echo $store['store_id']; ?>" data-toggle="tab"><?php echo $store['name']; ?></a></li>
								<?php } ?>
							</ul>
						</div>
						
						<div class="col-xs-10">
							<div class="tab-content">
								<?php foreach( $stores as $store ) { ?>
									<div class="tab-pane" id="gwt-stores-<?php echo $store['store_id']; ?>">
										<table class="table table-tbody">
											<tr>
												<td width="250" class="vertical-middle"><b><?php echo $s_smp_google_wt_entry; ?></b></td>
												<td>
													<input type="text" class="form-control" name="smp_meta[<?php echo $store['store_id']; ?>][google-site-verification]" value="<?php echo empty( $settings['smp_meta'][$store['store_id']]['google-site-verification'] ) ? '' : $settings['smp_meta'][$store['store_id']]['google-site-verification']; ?>" />
												</td>
											</tr>
										</table>
									</div>
								<?php } ?>
							</div>
						</div>
						
						<div class="clearfix"></div>
						<br />
						<div class="panel-group" id="help-webmaster-tools">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h6 class="panel-title">
										<a data-toggle="collapse" data-parent="#help-webmaster-tools" href="#help-group-webmaster-tools">
											Learn more
										</a>
									</h6>
								</div>
								<div id="help-group-webmaster-tools" class="panel-collapse collapse">
									<div class="panel-body">
										<table class="table">
											<tr>
												<td>
													<img src="view/smp/img/help/wt-info.jpg" />
												</td>
												<td class="text-right">
													<a target="_blank" href="https://support.google.com/webmasters/answer/35179">Learn more</a>
												</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
										
					<div class="tab-pane" id="group-google-analytics">
						<div class="col-xs-2">
							<ul class="nav nav-tabs tabs-left">
								<?php foreach( $stores as $store ) { ?>
									<li><a href="#gga-stores-<?php echo $store['store_id']; ?>" data-toggle="tab"><?php echo $store['name']; ?></a></li>
								<?php } ?>
							</ul>
						</div>
						
						<div class="col-xs-10">
							<div class="tab-content">
								<?php foreach( $stores as $store ) { ?>
									<div class="tab-pane" id="gga-stores-<?php echo $store['store_id']; ?>">
										<table class="table table-tbody">
											<tr>
												<td width="250"><b><?php echo $s_smp_google_a_entry; ?></b></td>
												<td>
													<textarea class="form-control" rows="5" name="smp_google_analytics[<?php echo $store['store_id']; ?>]"><?php echo empty( $settings['smp_google_analytics'][$store['store_id']] ) ? '' : $settings['smp_google_analytics'][$store['store_id']]; ?></textarea>
												</td>
											</tr>
										</table>
									</div>
								<?php } ?>
							</div>
						</div>
						
						<div class="clearfix"></div>
						
						<div class="panel-group" id="help-google-analytics">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h6 class="panel-title">
										<a data-toggle="collapse" data-parent="#help-webmaster-tools" href="#help-group-google-analytics">
											Learn more
										</a>
									</h6>
								</div>
								<div id="help-group-google-analytics" class="panel-collapse collapse">
									<div class="panel-body">
										<table class="table">
											<tr>
												<td>
													<img src="view/smp/img/help/ga-info.jpg" />
												</td>
												<td class="text-right">
													<a target="_blank" href="http://www.google.com/analytics/">Learn more</a>
												</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
										
					<div class="tab-pane" id="group-social">
						<div class="col-xs-2">
							<ul class="nav nav-tabs tabs-left">
								<?php foreach( $stores as $store ) { ?>
									<li><a href="#gs-stores-<?php echo $store['store_id']; ?>" data-toggle="tab"><?php echo $store['name']; ?></a></li>
								<?php } ?>
							</ul>
						</div>
						
						<div class="col-xs-10">
							<div class="tab-content">
								<?php foreach( $stores as $store ) { ?>
									<div class="tab-pane" id="gs-stores-<?php echo $store['store_id']; ?>">
										<table class="table">
											<thead>
												<tr>
													<td width="350" style="padding:5px"><?php echo $column_parameter; ?></td>
													<td style="padding:5px"><?php echo $column_description; ?></td>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<center>
															<?php echo $s_smp_facebook_open_graph; ?><br />
															<input type="checkbox" name="smp_facebook_open_graph[<?php echo $store['store_id']; ?>]" value="1" <?php if( ! empty( $settings['smp_facebook_open_graph'][$store['store_id']] ) ) { ?>checked="checked"<?php } ?> />
														</center>
													</td>
													<td><?php echo $s_smp_facebook_open_graph_desc; ?></td>
												</tr>
												<tr>
													<td>
														<center>
															<?php echo $s_smp_twitter_cart; ?><br />
															<input type="checkbox" name="smp_twitter_cart[<?php echo $store['store_id']; ?>]" value="1" <?php if( ! empty( $settings['smp_twitter_cart'][$store['store_id']] ) ) { ?>checked="checked"<?php } ?> />
														</center>
													</td>
													<td>
														<?php echo $s_smp_twitter_cart_desc; ?>
														<table class="table pull-right" style="width: auto;">
															<thead>
																<tr>
																	<th><?php echo $tab_settings; ?></th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td>
																		<input type="text" class="pull-right" name="smp_twitter_creator[<?php echo $store['store_id']; ?>]" placeholder="@setTwitterNickName" value="<?php echo empty( $settings['smp_twitter_creator'][$store['store_id']] ) ? '' : $settings['smp_twitter_creator'][$store['store_id']]; ?>" /><br />

																		<?php echo $s_smp_twitter_cart_creator; ?>
																	</td>
																</tr>
																<tr>
																	<td>
																		<input type="text" class="pull-right" name="smp_twitter_site[<?php echo $store['store_id']; ?>]" placeholder="@setTwitterSite" value="<?php echo empty( $settings['smp_twitter_site'][$store['store_id']] ) ? '' : $settings['smp_twitter_site'][$store['store_id']]; ?>" /><br />

																		<?php echo $s_smp_twitter_cart_site; ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td>
														<center>
															<?php echo $s_smp_googleplus_metadata; ?><br />
															<input type="checkbox" name="smp_googleplus_metadata[<?php echo $store['store_id']; ?>]" value="1" <?php if( ! empty( $settings['smp_googleplus_metadata'][$store['store_id']] ) ) { ?>checked="checked"<?php } ?> />
														</center>
													</td>
													<td><?php echo $s_smp_googleplus_metadata_desc; ?></td>
												</tr>
											</tbody>
										</table>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
										
					<div class="tab-pane" id="group-sitemaps">
						<div class="col-xs-2">
							<ul class="nav nav-tabs tabs-left">
								<?php foreach( $stores as $store ) { ?>
									<li><a href="#sitemaps-stores-<?php echo $store['store_id']; ?>" data-toggle="tab"><?php echo $store['name']; ?></a></li>
								<?php } ?>
							</ul>
						</div>
						
						<div class="col-xs-10">
							<div class="tab-content">
								<?php
								
									$changefreq = array(
										'always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'
									);
								
								?>
								<?php foreach( $stores as $store ) { ?>
									<?php
									
										$changefreqVals = empty( $settings['smp_sitemap_changefreq'][$store['store_id']] ) ? array(
											'product' => 'weekly',
											'information_page' => 'yearly',
											'category' => 'yearly'
										) : $settings['smp_sitemap_changefreq'][$store['store_id']];
									
									?>
									<div class="tab-pane" id="sitemaps-stores-<?php echo $store['store_id']; ?>">
										<table class="table">
											<thead>
												<tr>
													<td width="350" style="padding:5px"><?php echo $column_parameter; ?></td>
													<td style="padding:5px"><?php echo $column_value; ?></td>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<?php echo $entry_changefreq_product; ?>
													</td>
													<td>
														<select name="smp_sitemap_changefreq[<?php echo $store['store_id']; ?>][product]" class="form-control">
															<?php foreach( $changefreq as $val ) { ?>
																<option value="<?php echo $val; ?>"<?php echo $val == $changefreqVals['product'] ? ' selected="selected"' : ''; ?>><?php echo $val; ?></option>
															<?php } ?>
														</select>
													</td>
												</tr>
												<tr>
													<td>
														<?php echo $entry_changefreq_information_page; ?>
													</td>
													<td>
														<select name="smp_sitemap_changefreq[<?php echo $store['store_id']; ?>][information_page]" class="form-control">
															<?php foreach( $changefreq as $val ) { ?>
																<option value="<?php echo $val; ?>"<?php echo $val == $changefreqVals['information_page'] ? ' selected="selected"' : ''; ?>><?php echo $val; ?></option>
															<?php } ?>
														</select>
													</td>
												</tr>
												<tr>
													<td>
														<?php echo $entry_changefreq_category; ?>
													</td>
													<td>
														<select name="smp_sitemap_changefreq[<?php echo $store['store_id']; ?>][category]" class="form-control">
															<?php foreach( $changefreq as $val ) { ?>
																<option value="<?php echo $val; ?>"<?php echo $val == $changefreqVals['category'] ? ' selected="selected"' : ''; ?>><?php echo $val; ?></option>
															<?php } ?>
														</select>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
										
					<div class="tab-pane" id="group-url">
						<div class="row">
							<div class="col-md-1">
								<b><?php echo $settings_main; ?></b>
							</div>
							<div class="col-md-11">
								<blockquote>
									<p><input type="checkbox" name="smp_clear_on" value="1" <?php if( ! empty( $settings['smp_clear_on'] ) ) { ?>checked="checked"<?php } ?> /> <?php echo $s_smp_clear_on; ?></p>
									<footer><?php echo $s_smp_clear_on_desc; ?></footer>
								</blockquote>
								
								<blockquote>
									<p><input type="checkbox" name="smp_trans_urls_when_change_langs" value="1" <?php if( ! empty( $settings['smp_trans_urls_when_change_langs'] ) ) { ?>checked="checked"<?php } ?> /> <?php echo $s_smp_trans_urls_when_change_langs; ?></p>
									<footer><?php echo $s_smp_trans_urls_when_change_langs_desc; ?></footer>
								</blockquote>
													
								<blockquote>
									<p><input type="checkbox" name="smp_disable_convert_urls" value="1" <?php if( ! empty( $settings['smp_disable_convert_urls'] ) ) { ?>checked="checked"<?php } ?> /> <?php echo $s_smp_disable_convert_urls; ?></p>
									<footer><?php echo $s_smp_disable_convert_urls_desc; ?></footer>
								</blockquote>
													
								<blockquote>
									<p><input type="checkbox" name="smp_add_slash_at_the_end_urls" value="1" <?php if( ! empty( $settings['smp_add_slash_at_the_end_urls'] ) ) { ?>checked="checked"<?php } ?> /> <?php echo $s_smp_add_slash_at_the_end_urls; ?></p>
									<footer><?php echo $s_smp_add_slash_at_the_end_urls_desc; ?></footer>
								</blockquote>
													
								<blockquote>
									<p><input type="checkbox" name="smp_store_incorrect_urls" value="1" <?php if( ! empty( $settings['smp_store_incorrect_urls'] ) ) { ?>checked="checked"<?php } ?> /> <?php echo $s_smp_store_incorrect_urls; ?></p>
									<footer><?php echo $s_smp_store_incorrect_urls_desc; ?></footer>
								</blockquote>
													
								<blockquote>
									<p><input type="checkbox" name="smp_auto_redirect_to_canonical_urls" value="1" <?php if( ! empty( $settings['smp_auto_redirect_to_canonical_urls'] ) ) { ?>checked="checked"<?php } ?> /> <?php echo $s_smp_auto_redirect_to_canonical_urls; ?></p>
									<footer><?php echo $s_smp_auto_redirect_to_canonical_urls_desc; ?></footer>
								</blockquote>
							</div>
						</div>
											
						<hr style="border-color: #C8C8C8; margin-top: 0;" />
											
						<div class="row">
							<div class="col-md-1">
								<b><?php echo $settings_products; ?></b>
							</div>
							<div class="col-md-11">
								<blockquote>
									<p><input type="checkbox" name="smp_short_product_urls" value="1" <?php if( ! empty( $settings['smp_short_product_urls'] ) ) { ?>checked="checked"<?php } ?> /> <?php echo $s_smp_short_product_urls; ?></p>
									<footer><?php echo $s_smp_short_product_urls_desc; ?></footer>
								</blockquote>
								
								<blockquote>
									<p><input type="checkbox" name="smp_short_product_urls_manufacturers" value="1" <?php if( ! empty( $settings['smp_short_product_urls_manufacturers'] ) ) { ?>checked="checked"<?php } ?> /> <?php echo $s_smp_short_product_urls_manufacturers; ?></p>
									<footer><?php echo $s_smp_short_product_urls_manufacturers_desc; ?></footer>
								</blockquote>
										
								<blockquote>
									<p><input type="checkbox" name="smp_pr_url_by_cat_url" value="1" <?php if( ! empty( $settings['smp_pr_url_by_cat_url'] ) ) { ?>checked="checked"<?php } ?> /> <?php echo $s_smp_pr_url_by_cat_url; ?></p>
									<footer><?php echo $s_smp_pr_url_by_cat_url_desc; ?></footer>
								</blockquote>
											
								<blockquote>
									<p><input type="checkbox" name="smp_c_short_product_urls" value="1" <?php if( ! empty( $settings['smp_c_short_product_urls'] ) ) { ?>checked="checked"<?php } ?> /> <?php echo $s_smp_c_short_product_urls; ?></p>
									<footer><?php echo $s_smp_c_short_product_urls_desc; ?></footer>
								</blockquote>
													
								<script type="text/javascript">
									$('input[name=smp_short_product_urls]').change(function(){
										var st = $(this).is(':checked');
															
										$('input[name=smp_pr_url_by_cat_url],input[name=smp_c_short_product_urls]').parent().parent()[st?'hide':'show']();
										
										$('input[name=smp_short_product_urls_manufacturers]').parent().parent()[st?'show':'hide']();
									});
									
									$('input[name=smp_short_product_urls]:checked').trigger('change');
								</script>
							</div>
						</div>
											
						<hr style="border-color: #C8C8C8; margin-top: 0;" />
											
						<div class="row">
							<div class="col-md-1">
								<b><?php echo $settings_categories; ?></b>
							</div>
							<div class="col-md-11">
								<blockquote>
									<p><input type="checkbox" name="smp_short_category_urls" value="1" <?php if( ! empty( $settings['smp_short_category_urls'] ) ) { ?>checked="checked"<?php } ?> /> <?php echo $s_smp_short_category_urls; ?></p>
									<footer><?php echo $s_smp_short_category_urls_desc; ?></footer>
								</blockquote>
							</div>
						</div>
					</div>
							
					<div class="tab-pane" id="group-language">
						<div class="row">
							<div class="col-md-1">
								<b><?php echo $settings_main; ?></b>
							</div>
							<div class="col-md-11">
								<blockquote>
									<p><input type="checkbox" name="smp_use_hreflang" value="1" <?php if( ! empty( $settings['smp_use_hreflang'] ) ) { ?>checked="checked"<?php } ?> /> <?php echo $s_smp_use_hreflang; ?></p>
									<footer><?php echo $s_smp_use_hreflang_desc; ?></footer>
								</blockquote>
										
								<blockquote>
									<p><input type="checkbox" name="smp_add_default_language_code_to_url" value="1" <?php if( ! empty( $settings['smp_add_default_language_code_to_url'] ) ) { ?>checked="checked"<?php } ?> /> <?php echo $s_smp_add_default_language_code_to_url; ?></p>
									<footer><?php echo $s_smp_add_default_language_code_to_url_desc; ?></footer>
								</blockquote>
											
								<blockquote>
									<p><input type="checkbox" name="smp_set_default_language_in_main_url" value="1" <?php if( ! empty( $settings['smp_set_default_language_in_main_url'] ) ) { ?>checked="checked"<?php } ?> /> <?php echo $s_smp_set_default_language_in_main_url; ?></p>
									<footer><?php echo $s_smp_set_default_language_in_main_url_desc; ?></footer>
								</blockquote>
							</div>
						</div>
					</div>
										
					<div class="tab-pane" id="group-skip-urls">
						<?php echo $s_smp_skip_urls_guide; ?><br /><br />
									
						<table class="table">
							<thead>
								<tr>
									<td colspan="2"><?php echo $s_smp_skip_urls_add; ?></td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<input type="text" id="skip_url" class="form-control" />
									</td>
									<td>
										<button type="button" id="add_skip_url" class="btn btn-success"><i class="glyphicon glyphicon-plus-sign"></i></button>
									</td>
								</tr>
							</tbody>
						</table>
											
						<table class="table table-tbody" id="skip-urls-list">
							<?php if( ! empty( $settings['smp_skip_urls'] ) ) { ?>
								<?php foreach( $settings['smp_skip_urls'] as $url ) { ?>
									<tr>
										<td>
											<?php echo $url; ?>
											<input type="hidden" name="smp_skip_urls[]" value="<?php echo $url; ?>" />
										</td>
									</tr>
								<?php } ?>
							<?php } ?>
						</table>
											
						<script type="text/javascript">
							(function(){
								var $list	= $('#skip-urls-list');
									
								function initTR( $tr ) {
									$tr.append($('<td>')
										.attr( 'width', '50' )
										.addClass( 'text-center' )
										.append($('<button>')
											.attr({
												'type'	: 'button',
												'class'	: 'btn btn-danger'
											})
											.append($('<i>')
												.addClass( 'glyphicon glyphicon-minus-sign' )
											)
											.click(function(){
												$tr.remove();
												
												return false;
											})
										)
									);
								}
													
								$('#add_skip_url').click(function(){
									var url = $.trim( $('#skip_url').val() );
								
									if( url ) {
										var $tr = $('<tr>')
											.append($('<td>')
												.text( url )
												.append($('<input>')
													.attr({
														'type'	: 'hidden',
														'name'	: 'smp_skip_urls[]',
														'value'	: url
													})
												)
											)
											.appendTo( $list );
											
											initTR( $tr );
									}
										
									$('#skip_url').val('');
										
									return false;
								});
													
								$list.find('tr').each(function(){
									initTR( $(this) );
								});
							})();
						</script>
					</div>
				</div>
			</div>
								
			<div class="clearfix"></div>
		</div>
	</div>
<?php } else { ?>
	<br /><br /><center><?php echo $smk_error_warning; ?></center>
<?php } ?>
					
<?php require DIR_TEMPLATE . 'module/seo_mega_pack-footer.tpl'; ?>