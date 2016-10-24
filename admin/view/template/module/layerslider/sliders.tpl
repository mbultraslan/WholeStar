<?php include_once(DIR_TEMPLATE . 'module/kuler_helper.tpl'); ?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content" ng-app class="items-sidebar">
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<section id="kuler-module-content" class="kuler-module kuler-module-content-v1">
			<section class="wrapper">
				<?php if ($error_warning) { ?>
					<div class="alert alert-danger"><?php echo $error_warning; ?></div>
				<?php } ?>

				<div class="row">
					<div class="col-md-12">
						<ul class="breadcrumb">
							<?php $breadcrumb_index = 0; ?>
							<?php foreach ($breadcrumbs as $breadcrumb) { ?>
								<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
									<?php $breadcrumb_index++; ?>
								</li>
							<?php } ?>
						</ul>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12">
						<div class="panel" id="kuler-panel-action">
							<div class="panel-body">
								<div class="row">
									<div style="float: left; display: none;">
										<label class="kuler-module-status-label"><?php echo _t('text_status', 'Status'); ?></label>
										<div style="margin: 0 15px; display: inline-block;">
											<div class="kuler-switch-btn">
												<input type="hidden" name="status" value="0" />
												<input type="checkbox" name="status" value="1"<?php if (!empty($status)) echo ' checked="checked"'; ?> />
												<span class="kuler-switch-btn-holder"></span>
											</div>
										</div>
									</div>

									<div class="pull-right main-actions">
										<a onclick="$('#form').submit();" class="btn-kuler btn btn-success"><?php echo $button_save; ?></a>
										<a href="<?php echo $cancel; ?>" class="btn-kuler btn btn-danger"><?php echo $button_cancel; ?></a>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-12">
						<section class="panel" id="kuler-panel-navigation">
							<nav class="navbar" role="navigation" id="kuler-navbar-container">
								<div class="kuler-navigation-left">
									<div class="navbar-header">
										<h2><img src="view/kuler/image/icon/kuler_logo.png" /> <?php echo _t('heading_module'); ?></h2>
									</div>
								</div>
								<div class="kuler-navigation-right">
									<ul id="kuler-main-tab-list" class="nav navbar-nav">
										<li<?php if ($ctrl->request->get['route'] == 'module/kuler_layer_slider') echo ' class="active"'; ?>>
											<a href="<?php echo $ctrl->url->link('module/kuler_layer_slider', 'id='. $id . '&token=' . $ctrl->session->data['token'], 'SSL'); ?>"><?php echo _t('text_slider_options', 'Slider Options'); ?></a>
										</li>
										<?php if ($id) { ?>
										<li<?php if ($ctrl->request->get['route'] == 'module/kuler_layer_slider/layer') echo ' class="active"'; ?>>
											<a href="<?php echo  $ctrl->url->link('module/kuler_layer_slider/layer', 'group_id=' . $id . '&token=' . $ctrl->session->data['token'], 'SSL');?>"><?php echo _t('text_footer', 'Slides'); ?></a>
										</li>
										<?php } ?>
									</ul>
								</div>
							</nav>
						</section>

						<section class="panel" id="kuler-panel-section">
							<div class="panel-body">
								<div id="kuler-module-container" class="clearfix">
									<ul class="nav nav-pills nav-stacked">
										<li<?php if (empty($id)) echo ' class="active"'; ?>><a href="<?php echo $ctrl->url->link('module/kuler_layer_slider', 'token=' . $ctrl->session->data['token'], 'SSL'); ?>"><?php echo _t('text_create_new_slider', 'Create new slider'); ?></a></li>
										<?php foreach ($slidergroups as $slider) { ?>
										<li<?php if ($slider['id'] == $id) echo ' class="active"'; ?>>
											<a href="<?php echo $ctrl->url->link('module/kuler_layer_slider', 'id='. $slider['id'] . '&token=' . $ctrl->session->data['token'], 'SSL'); ?>">
												<?php echo $slider['title']; ?>
												<span class="module-remover" onclick="if (confirm('Are you sure to delete this?')) {location = '<?php echo $ctrl->url->link('module/kuler_layer_slider/delete', 'id='. $slider['id'] .'&token=' . $ctrl->session->data['token'], 'SSL');?>';} return false;"><i class="fa fa-minus-circle"></i></span>
											</a>
										</li>
										<?php } ?>
									</ul>
									<div class="tab-content tab-content col-lg-9 col-sm-9">
										<div class="form-horizontal">
											<input type="hidden" name="id" value="<?php echo $id; ?>">

											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Slider Title');?></label>
												<div class="col-sm-9">
													<input type="text" class="form-control" name="slider[title]" value="<?php echo $params['title'];?>"/>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Short Code');?></label>
												<div class="col-sm-3">
													<input type="text" class="form-control" value="<?php echo $params['shortcode'];?>" readonly onclick="this.select();" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Delay (ms)');?></label>
												<div class="col-sm-2">
													<input type="text" class="form-control" name="slider[delay]" value="<?php echo $params['delay'];?>"/>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('FullWidth Mode');?></label>
												<div class="col-sm-2">
													<select class="form-control" name="slider[fullwidth]">
														<?php foreach( $fullwidth as $key => $value ) { ?>
															<option value="<?php echo $key;?>" <?php if( isset($params['fullwidth']) && ($key == $params['fullwidth']) ){ ?> selected="selected" <?php } ?> ><?php echo $value; ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Slider Demension');?></label>
												<div class="col-sm-2"><input class="form-control" type="text" name="slider[width]" value="<?php echo $params['width'];?>"/></div>
												<div class="col-sm-2"><input class="form-control" type="text" name="slider[height]" value="<?php echo $params['height'];?>"/></div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Stop On Hover');?></label>
												<div class="col-sm-9">
													<div class="kuler-switch-btn">
														<input type="hidden" name="slider[stop_on_hover]" value="0" />
														<input type="checkbox" name="slider[stop_on_hover]" value="1"<?php if( $params['stop_on_hover'] == 1 ){ ?> checked="checked" <?php } ?> />
														<span class="kuler-switch-btn-holder"></span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Shuffle Mode');?></label>
												<div class="col-sm-9">
													<div class="kuler-switch-btn">
														<input type="hidden" name="slider[shuffle_mode]" value="0" />
														<input type="checkbox" name="slider[stop_on_hover]" name="slider[shuffle_mode]" value="1"<?php if( $params['shuffle_mode'] == 1 ){ ?> checked="checked" <?php } ?> />
														<span class="kuler-switch-btn-holder"></span>
													</div>
												</div>
											</div>

											<fieldset>
												<legend><?php echo $ctrl->language->get('Appearance');?></legend>

												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Shadow Type');?></label>
													<div class="col-sm-2">
														<select class="form-control" name="slider[shadow_type]">
															<?php foreach( $shadow_types as $key => $value ) { ?>
																<option value="<?php echo $key;?>" <?php if( $key == $params['shadow_type'] ){ ?> selected="selected" <?php } ?> ><?php echo $value; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Show Time Line');?></label>
													<div class="col-sm-9">
														<div class="kuler-switch-btn">
															<input type="hidden" name="slider[show_time_line]" value="0" />
															<input type="checkbox" name="slider[show_time_line]" ng-init="show_time_line = <?php echo $params['show_time_line'] ? 'true' : 'false'; ?>" ng-model="show_time_line" value="1"<?php if( $params['show_time_line'] == 1 ){ ?> checked="checked" <?php } ?> />
															<span class="kuler-switch-btn-holder"></span>
														</div>
													</div>
												</div>
												<div class="form-group" ng-if="show_time_line">
													<label class="col-sm-3 control-label">Time Liner Position</label>
													<div class="col-sm-2">
														<select class="form-control" name="slider[time_line_position]">
															<?php foreach( $linepostions as $key => $value ) { ?>
																<option value="<?php echo $key;?>" <?php if( $key == $params['time_line_position'] ){ ?> selected="selected" <?php } ?> ><?php echo $value; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
											</fieldset>

											<fieldset>
												<legend><?php echo $ctrl->language->get('Navigator');?></legend>

												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Arrows');?></label>
													<div class="col-sm-2">
														<select class="form-control" name="slider[navigator_arrows]">
															<?php foreach( $navigation_arrows as $key => $value ) { ?>
																<option value="<?php echo $key;?>" <?php if( $key == $params['navigator_arrows'] ){ ?> selected="selected" <?php } ?> ><?php echo $value; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Navigator Type');?></label>
													<div class="col-sm-2">
														<select class="form-control" name="slider[navigator_type]" ng-init="navigator_type = '<?php echo $params['navigator_type']; ?>'" ng-model="navigator_type">
															<?php foreach( $navigator_types as $key => $value ) { ?>
																<option value="<?php echo $key;?>" <?php if( $key == $params['navigator_type'] ){ ?> selected="selected" <?php } ?> ><?php echo $value; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
												<div class="form-group" ng-if="navigator_type !== 'none'">
													<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Style');?></label>
													<div class="col-sm-2">
														<select class="form-control" name="slider[navigation_style]">
															<?php foreach( $navigation_style as $key => $value ) { ?>
																<option value="<?php echo $key;?>" <?php if( $key == $params['navigation_style'] ){ ?> selected="selected" <?php } ?> ><?php echo $value; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
												<div class="form-group" ng-if="navigator_type !== 'none'">
													<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Offset Horizontal (px)');?></label>
													<div class="col-sm-2">
														<input class="form-control" type="text" value="<?php echo $params['offset_horizontal'];?>" name="slider[offset_horizontal]"/>
													</div>
												</div>
												<div class="form-group" ng-if="navigator_type !== 'none'">
													<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Offset Vertical (px)');?></label>
													<div class="col-sm-2">
														<input class="form-control" type="text" value="<?php echo $params['offset_vertical'];?>" name="slider[offset_vertical]"/>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Always Show Navigator');?></label>
													<div class="col-sm-9">
														<div class="kuler-switch-btn">
															<input type="hidden" name="slider[show_navigator]" value="0" />
															<input type="checkbox" name="slider[show_navigator]" ng-init="show_navigator = <?php echo $params['show_navigator'] ? 'true' : 'false'; ?>" ng-model="show_navigator" value="1"<?php if( $params['show_navigator'] == 1 ){ ?> checked="checked" <?php } ?> />
															<span class="kuler-switch-btn-holder"></span>
														</div>
													</div>
												</div>
												<div class="form-group" ng-if="!show_navigator">
													<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Hide Navigator After (ms)');?></label>
													<div class="col-sm-2">
														<input class="form-control" type="text" value="<?php echo $params['hide_navigator_after'];?>" name="slider[hide_navigator_after]"/>
													</div>
												</div>
											</fieldset>

											<fieldset style="display: none;">
												<legend><?php echo $ctrl->language->get('Thumbnails');?></legend>
												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Thumbnail Width');?></label>
													<div class="col-sm-2">
														<input class="form-control" type="text" value="<?php echo $params['thumbnail_width'];?>" name="slider[thumbnail_width]"/>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Thumbnail Height');?> </label>
													<div class="col-sm-2">
														<input class="form-control" type="text" value="<?php echo $params['thumbnail_height'];?>" name="slider[thumbnail_height]"/>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Number of Thumbnails');?> </label>
													<div class="col-sm-2">
														<input class='form-control' type="text" value="<?php echo $params['thumbnail_amount'];?>" name="slider[thumbnail_amount]"/>
													</div>
												</div>
											</fieldset>

											<fieldset>
												<legend><?php echo $ctrl->language->get('Mobile Visiblity');?></legend>
												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $ctrl->language->get('Hide Under Width (px)');?></label>
													<div class="col-sm-2">
														<input class="form-control" type="text" value="<?php echo $params['hide_screen_width'];?>" name="slider[hide_screen_width]"/>
													</div>
												</div>
											</fieldset>
										</div>
									</div>
								</div>
							</div>
						</section>

					</div>
				</div>
			</section>
		</section>
	</form>
</div>
<?php echo $footer; ?>