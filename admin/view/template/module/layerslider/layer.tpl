<?php include_once(DIR_TEMPLATE . 'module/kuler_helper.tpl'); ?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content" class="items-sidebar">
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="slide_option_data" value="{{slide_option_data}}" />
		<input type="hidden" name="slide_data" value="{{slide_data}}" />
		<input type="hidden" name="layers_data" value="{{layers_data}}" />

		<section id="kuler-module-content" class="kuler-module" ng-app="kulerModule" ng-controller="LayerSliderCtrl">
			<section class="wrapper">
				<div class="alert alert-{{messageType}} fade in" ng-if="message">
					<button data-dismiss="alert" class="close close-sm" type="button">
						<i class="fa fa-times"></i>
					</button>
					{{message}}
				</div>

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

				<div class="float-actions">
					<a ng-click="save()" class="btn btn-success" tooltip="<?php echo $button_save; ?>"><i class="fa fa-save"></i></a>
				</div>

				<div class="row">
					<div class="col-sm-12">
						<div class="panel" id="kuler-panel-action">
							<div class="panel-body">
								<div class="row">
									<div class="pull-right main-actions">
										<a ng-click="save()" class="btn-kuler btn btn-success"><?php echo $button_save; ?></a>
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
											<a href="<?php echo $ctrl->url->link('module/kuler_layer_slider', 'id='. $group_id . '&token=' . $ctrl->session->data['token'], 'SSL'); ?>"><?php echo _t('text_slider_options', 'Slider Options'); ?></a>
										</li>
										<?php if ($group_id) { ?>
										<li<?php if ($ctrl->request->get['route'] == 'module/kuler_layer_slider/layer') echo ' class="active"'; ?>>
											<a href="<?php echo  $ctrl->url->link('module/kuler_layer_slider/layer', 'group_id=' . $group_id . '&token=' . $ctrl->session->data['token'], 'SSL');?>"><?php echo _t('text_footer', 'Slides'); ?></a>
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
										<li<?php if (empty($slider_id)) echo ' class="active"'; ?>><a href="<?php echo  $ctrl->url->link('module/kuler_layer_slider/layer', 'group_id=' . $group_id . '&token=' . $ctrl->session->data['token'], 'SSL'); ?>"><?php echo _t('text_create_new_slide', 'Create New Slide'); ?></a></li>

										<?php foreach ($sliders as $slide) { ?>
											<li<?php if ($slide['id'] == $slider_id) echo ' class="active"'; ?>>
												<a href="<?php echo $ctrl->url->link('module/kuler_layer_slider/layer', 'id=' . $slide['id'] . '&group_id='. $group_id . '&token=' . $ctrl->session->data['token'], 'SSL'); ?>">
													<?php echo $slide['title']; ?>
													<span class="module-remover" onclick="if (confirm('Are you sure to delete this?')) {location = '<?php echo  $ctrl->url->link('module/kuler_layer_slider/deleteslider', 'id=' . $slide['id'] . '&group_id=' . $group_id . '&token=' . $ctrl->session->data['token'], 'SSL')?>';} return false;"><i class="fa fa-minus-circle"></i></span>
												</a>
											</li>
										<?php } ?>
									</ul>
									<div class="tab-content  col-lg-9 col-sm-9">
										<?php echo renderBeginOptionContainer(); ?>

										<fieldset>
											<legend><?php echo _t('text_slide_general_options', 'Slide General Options'); ?></legend>
											<?php  echo renderOption(array(
												'label' => _t('entry_title', 'Title'),
												'type' => 'input',
												'name' => 'slide_option.title',
												'column' => 6
											)); ?>
											<?php echo renderOption(array(
												'label' => _t('entry_status', 'Status'),
												'type' => 'switch',
												'name' => 'slide_option.status'
											)); ?>
											<div class="form-group">
												<label class="col-sm-2 control-label"><?php echo _t('entry_transition', 'Transition'); ?></label>
												<div class="col-sm-5">
													<select class="form-control" ng-model="slide.slider_transition">
														<option value="notselectable1">RANDOM TRANSITIONS</option>
														<option value="random-static">Random Flat</option>
														<option value="random-premium">Random Premium</option>
														<option value="random">Random Flat and Premium</option>
														<option value="notselectable2">SLIDING TRANSITIONS</option>
														<option value="slideup">Slide To Top</option>
														<option value="slidedown">Slide To Bottom</option>
														<option value="slideright">Slide To Right</option>
														<option value="slideleft">Slide To Left</option>
														<option value="slidehorizontal">Slide Horizontal (depending on Next/Previous)</option>
														<option value="slidevertical">Slide Vertical (depending on Next/Previous)</option>
														<option value="boxslide">Slide Boxes</option>
														<option value="slotslide-horizontal">Slide Slots Horizontal</option>
														<option value="slotslide-vertical">Slide Slots Vertical</option>
														<option value="notselectable3">FADE TRANSITIONS</option>
														<option value="notransition">No Transition</option>
														<option value="fade">Fade</option>
														<option value="boxfade">Fade Boxes</option>
														<option value="slotfade-horizontal">Fade Slots Horizontal</option>
														<option value="slotfade-vertical">Fade Slots Vertical</option>
														<option value="fadefromright">Fade and Slide from Right</option>
														<option value="fadefromleft">Fade and Slide from Left</option>
														<option value="fadefromtop">Fade and Slide from Top</option>
														<option value="fadefrombottom">Fade and Slide from Bottom</option>
														<option value="fadetoleftfadefromright">Fade To Left and Fade From Right</option>
														<option value="fadetorightfadefromleft">Fade To Right and Fade From Left</option>
														<option value="fadetotopfadefrombottom">Fade To Top and Fade From Bottom</option>
														<option value="fadetobottomfadefromtop">Fade To Bottom and Fade From Top</option>
														<option value="notselectable4">PARALLAX TRANSITIONS</option>
														<option value="parallaxtoright">Parallax to Right</option>
														<option value="parallaxtoleft">Parallax to Left</option>
														<option value="parallaxtotop">Parallax to Top</option>
														<option value="parallaxtobottom">Parallax to Bottom</option>
														<option value="notselectable5">ZOOM TRANSITIONS</option>
														<option value="scaledownfromright">Zoom Out and Fade From Right</option>
														<option value="scaledownfromleft">Zoom Out and Fade From Left</option>
														<option value="scaledownfromtop">Zoom Out and Fade From Top</option>
														<option value="scaledownfrombottom">Zoom Out and Fade From Bottom</option>
														<option value="zoomout">ZoomOut</option>
														<option value="zoomin">ZoomIn</option>
														<option value="slotzoom-horizontal">Zoom Slots Horizontal</option>
														<option value="slotzoom-vertical">Zoom Slots Vertical</option>
														<option value="notselectable6">CURTAIN TRANSITIONS</option>
														<option value="curtain-1">Curtain from Left</option>
														<option value="curtain-2">Curtain from Right</option>
														<option value="curtain-3">Curtain from Middle</option>
														<option value="notselectable7">PREMIUM TRANSITIONS</option>
														<option value="3dcurtain-horizontal">3D Curtain Horizontal</option>
														<option value="3dcurtain-vertical">3D Curtain Vertical</option>
														<option value="cube">Cube Vertical</option>
														<option value="cube-horizontal">Cube Horizontal</option>
														<option value="incube">In Cube Vertical</option>
														<option value="incube-horizontal">In Cube Horizontal</option>
														<option value="turnoff">TurnOff Horizontal</option>
														<option value="turnoff-vertical">TurnOff Vertical</option>
														<option value="papercut">Paper Cut</option>
														<option value="flyin">Fly In</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label"><?php echo _t('entry_transition_duration', 'Transition Duration (ms)'); ?></label>
												<div class="col-sm-2">
													<input type="text" class="form-control" ng-model="slide.slider_duration" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label"><?php echo _t('entry_delay', 'Delay (ms)'); ?></label>
												<div class="col-sm-2">
													<input type="text" class="form-control" ng-model="slide.slider_delay" />
												</div>
											</div>
											<?php  echo renderOption(array(
												'label' => _t('entry_slide_link', 'Slide Link'),
												'type' => 'input',
												'name' => 'slide.slider_link',
												'column' => 6
											)); ?>
										</fieldset>

										<fieldset>
											<legend><?php echo _t('text_layers_editor', 'Layers Editor'); ?></legend>
											<div class="btn-group">
												<div type="button" class="btn btn-success" button-image-selector image="slide.slider_image"><i class="fa fa-picture-o"></i> <?php echo _t('button_update_slide_image', 'Update Slide Image'); ?></div>
												<button type="button" class="btn btn-success" button-image-selector image="layerImagePlaceholder"><i class="fa fa-picture-o"></i> <?php echo _t('button_add_layer_image', 'Add Layer Image'); ?></button>
												<button type="button" class="btn btn-success" style="display: none;"><i class="fa fa-toggle-right"></i> <?php echo _t('button_add_layer_video', 'Add Layer Video'); ?></button>
												<button type="button" class="btn btn-success" ng-click="addLayer('text')"><i class="fa fa-font"></i> <?php echo _t('button_add_layer_text', 'Add Layer Text'); ?></button>
												<button type="button" class="btn btn-danger" ng-click="deleteActiveLayer()"><i class="fa fa-trash-o"></i> <?php echo _t('button_delete_layer', 'Delete Layer'); ?></button>
											</div>
											<div ng-controller="SlidePreviewCtrl" class="slide-preview" style="width: <?php echo $sliderGroup['params']['width'] ?>px; height: <?php echo $sliderGroup['params']['height'] ?>px; background-size: cover; background-image: url('{{slide.$$slider_image_thumb}}');">
												<span class="tp-caption {{layer.layer_class}}" ng-repeat="layer in layers" ng-class="{'active': layer.$$active}" data-drag="true" jqyoui-draggable="{onDrag: 'onLayerDrag'}" ng-mousedown="setActiveLayer(layer)" ng-hide="layer.$$hide" layer="{{$index}}" layer-index="{{$index}}">
													<span ng-if="layer.layer_type == 'text'" ng-bind-html="layer.layer_caption"></span>
													<img ng-if="layer.layer_type == 'image'" ng-src="{{layer.$$thumb}}" />
												</span>
											</div>
											<div id="layer-options-container">
												<div class="row">
													<div class="col-sm-6 col-xs-12">
														<fieldset class="form-horizontal" class="layer-options-container" ng-controller="LayerOptionCtrl" ng-disabled="!activeLayer">
														<accordion>
															<accordion-group heading="<?php echo _t('text_layer_general_options', 'Layer General Options'); ?>" ng-init="layerGeneralOptionsOpen = true" is-open="layerGeneralOptionsOpen">
																<fieldset>
																	<legend><?php echo _t('text_layer_content', 'Layer Content'); ?></legend>
																	<div class="form-group">
																		<label class="col-sm-3 control-label"><?php echo _t('entry_style', 'Style'); ?></label>
																		<div class="col-sm-9">
																			<div class="input-group">
																				<input type="text" class="form-control" ng-model="activeLayer.layer_class" style="height: 35px;" />
																				<div class="input-group-btn">
																					<div class="btn-group" dropdown>
																						<button type="button" class="btn btn-success dropdown-toggle">
																							<?php echo _t('text_select_style', 'Select Style') ?> <span class="caret"></span>
																						</button>
																						<ul class="dropdown-menu layer-style-list">
																							<?php foreach ($layer_styles as $layer_style) { ?>
																							<li class="layer-style-item">
																								<a href="#" ng-click="activeLayer.layer_class = '<?php echo $layer_style; ?>'" event-prevent-default><?php echo $layer_style; ?></a>
																								<div class="layer-style-preview">
																									<span class="tp-caption <?php echo $layer_style ?>">Layer Style</span>
																								</div>
																							</li>
																							<?php } ?>
																						</ul>
																					</div>
																					<button type="button" class="btn btn-danger" ng-click="clearActiveLayerStyle()"><?php echo _t('button_clear', 'Clear'); ?></button>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="col-sm-3 control-label"><?php echo _t('entry_text_html', 'Text / HTML'); ?></label>
																		<div class="col-sm-9">
																			<textarea class="form-control" ng-model="activeLayer.layer_caption" style="height: 50px;" tooltip="<?php echo _t('help_allow_insert_html_code', 'Allow insert HTML code'); ?>"></textarea>
																		</div>
																	</div>
																</fieldset>

																<fieldset>
																	<legend><?php echo _t('text_align_and_position', 'Align & Position'); ?></legend>
																	<div class="row">
																		<div class="col-xs-3">
																			<table class="layer-align-table">
																				<tbody>
																				<tr>
																					<td><span ng-class="{'active' : isAlignActive('left', 'top')}" ng-click="alignActiveLayer('left', 'top')"></span></td>
																					<td><span ng-class="{'active' : isAlignActive('center', 'top')}" ng-click="alignActiveLayer('center', 'top')"></span></td>
																					<td><span ng-class="{'active' : isAlignActive('right', 'top')}" ng-click="alignActiveLayer('right', 'top')"></span></td>
																				</tr>
																				<tr>
																					<td><span ng-class="{'active' : isAlignActive('left', 'center')}" ng-click="alignActiveLayer('left', 'center')"></span></td>
																					<td><span ng-class="{'active' : isAlignActive('center', 'center')}" ng-click="alignActiveLayer('center', 'center')"></span></td>
																					<td><span ng-class="{'active' : isAlignActive('right', 'center')}" ng-click="alignActiveLayer('right', 'center')"></span></td>
																				</tr>
																				<tr>
																					<td><span ng-class="{'active' : isAlignActive('left', 'bottom')}" ng-click="alignActiveLayer('left', 'bottom')"></span></td>
																					<td><span ng-class="{'active' : isAlignActive('center', 'bottom')}" ng-click="alignActiveLayer('center', 'bottom')"></span></td>
																					<td><span ng-class="{'active' : isAlignActive('right', 'bottom')}" ng-click="alignActiveLayer('right', 'bottom')"></span></td>
																				</tr>
																				</tbody>
																			</table>
																		</div>
																		<div class="col-xs-9">
																			<div class="form-group">
																				<label class="col-sm-3 control-label"><?php echo _t('entry_x', 'X'); ?></label>
																				<div class="col-sm-7">
																					<input type="text" class="form-control" ng-model="activeLayer.layer_hoffset" />
																				</div>
																			</div>
																			<div class="form-group">
																				<label class="col-sm-3 control-label"><?php echo _t('entry_y', 'Y'); ?></label>
																				<div class="col-sm-7">
																					<input type="text" class="form-control" ng-model="activeLayer.layer_voffset" />
																				</div>
																			</div>
																		</div>
																	</div>
																</fieldset>

															</accordion-group>

															<accordion-group heading="<?php echo _t('text_layer_animation', 'Layer Animation'); ?>">
																<fieldset>
																	<legend><?php echo _t('text_start_transition', 'Start Transition'); ?></legend>
																	<div class="form-group">
																		<label class="col-sm-3 control-label"><?php echo _t('entry_start_effect', 'Start Effect'); ?></label>
																		<div class="col-sm-6">
																			<select class="form-control" tooltip="<?php echo _t('entry_start_animation', 'Start Animation'); ?>" ng-model="activeLayer.layer_animation">
																				<option value="tp-fade">Fade</option>
																				<option value="sft">Short from Top</option>
																				<option value="sfb">Short from Bottom</option>
																				<option value="sfr">Short from Right</option>
																				<option value="sfl">Short from Left</option>
																				<option value="lft">Long from Top</option>
																				<option value="lfb">Long from Bottom</option>
																				<option value="lfr">Long from Right</option>
																				<option value="lfl">Long from Left</option>
																				<option value="skewfromright">Skew From Long Right</option>
																				<option value="skewfromleft">Skew From Long Left</option>
																				<option value="skewfromleftshort">Skew From Short Right</option>
																				<option value="skewfromrightshort">Skew From Short Left</option>
																				<option value="randomrotate">Random Rotate</option>
																			</select>
																		</div>
																		<div class="col-sm-3">
																			<input type="text" class="form-control" tooltip="<?php echo _t('text_start_speed', 'Start Speed (ms)'); ?>" ng-model="activeLayer.layer_speed" />
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="col-sm-3 control-label"><?php echo _t('entry_start_easing', 'Start Easing'); ?></label>
																		<div class="col-sm-9">
																			<select class="form-control" ng-model="activeLayer.layer_easing">
																				<option value="Linear.easeNone">Linear.easeNone</option>
																				<option value="Power0.easeIn">Power0.easeIn  (linear)</option>
																				<option value="Power0.easeInOut">Power0.easeInOut  (linear)</option>
																				<option value="Power0.easeOut">Power0.easeOut  (linear)</option>
																				<option value="Power1.easeIn">Power1.easeIn</option>
																				<option value="Power1.easeInOut">Power1.easeInOut</option>
																				<option value="Power1.easeOut">Power1.easeOut</option>
																				<option value="Power2.easeIn">Power2.easeIn</option>
																				<option value="Power2.easeInOut">Power2.easeInOut</option>
																				<option value="Power2.easeOut">Power2.easeOut</option>
																				<option value="Power3.easeIn">Power3.easeIn</option>
																				<option value="Power3.easeInOut">Power3.easeInOut</option>
																				<option value="Power3.easeOut">Power3.easeOut</option>
																				<option value="Power4.easeIn">Power4.easeIn</option>
																				<option value="Power4.easeInOut">Power4.easeInOut</option>
																				<option value="Power4.easeOut">Power4.easeOut</option>
																				<option value="Quad.easeIn">Quad.easeIn  (same as Power1.easeIn)</option>
																				<option value="Quad.easeInOut">Quad.easeInOut  (same as Power1.easeInOut)</option>
																				<option value="Quad.easeOut">Quad.easeOut  (same as Power1.easeOut)</option>
																				<option value="Cubic.easeIn">Cubic.easeIn  (same as Power2.easeIn)</option>
																				<option value="Cubic.easeInOut">Cubic.easeInOut  (same as Power2.easeInOut)</option>
																				<option value="Cubic.easeOut">Cubic.easeOut  (same as Power2.easeOut)</option>
																				<option value="Quart.easeIn">Quart.easeIn  (same as Power3.easeIn)</option>
																				<option value="Quart.easeInOut">Quart.easeInOut  (same as Power3.easeInOut)</option>
																				<option value="Quart.easeOut">Quart.easeOut  (same as Power3.easeOut)</option>
																				<option value="Quint.easeIn">Quint.easeIn  (same as Power4.easeIn)</option>
																				<option value="Quint.easeInOut">Quint.easeInOut  (same as Power4.easeInOut)</option>
																				<option value="Quint.easeOut">Quint.easeOut  (same as Power4.easeOut)</option>
																				<option value="Strong.easeIn">Strong.easeIn  (same as Power4.easeIn)</option>
																				<option value="Strong.easeInOut">Strong.easeInOut  (same as Power4.easeInOut)</option>
																				<option value="Strong.easeOut">Strong.easeOut  (same as Power4.easeOut)</option>
																				<option value="Back.easeIn">Back.easeIn</option>
																				<option value="Back.easeInOut">Back.easeInOut</option>
																				<option value="Back.easeOut">Back.easeOut</option>
																				<option value="Bounce.easeIn">Bounce.easeIn</option>
																				<option value="Bounce.easeInOut">Bounce.easeInOut</option>
																				<option value="Bounce.easeOut">Bounce.easeOut</option>
																				<option value="Circ.easeIn">Circ.easeIn</option>
																				<option value="Circ.easeInOut">Circ.easeInOut</option>
																				<option value="Circ.easeOut">Circ.easeOut</option>
																				<option value="Elastic.easeIn">Elastic.easeIn</option>
																				<option value="Elastic.easeInOut">Elastic.easeInOut</option>
																				<option value="Elastic.easeOut">Elastic.easeOut</option>
																				<option value="Expo.easeIn">Expo.easeIn</option>
																				<option value="Expo.easeInOut">Expo.easeInOut</option>
																				<option value="Expo.easeOut">Expo.easeOut</option>
																				<option value="Sine.easeIn">Sine.easeIn</option>
																				<option value="Sine.easeInOut">Sine.easeInOut</option>
																				<option value="Sine.easeOut">Sine.easeOut</option>
																				<option value="SlowMo.ease">SlowMo.ease</option>
																				<option value="easeOutBack">easeOutBack</option>
																				<option value="easeInQuad">easeInQuad</option>
																				<option value="easeOutQuad">easeOutQuad</option>
																				<option value="easeInOutQuad">easeInOutQuad</option>
																				<option value="easeInCubic">easeInCubic</option>
																				<option value="easeOutCubic">easeOutCubic</option>
																				<option value="easeInOutCubic">easeInOutCubic</option>
																				<option value="easeInQuart">easeInQuart</option>
																				<option value="easeOutQuart">easeOutQuart</option>
																				<option value="easeInOutQuart">easeInOutQuart</option>
																				<option value="easeInQuint">easeInQuint</option>
																				<option value="easeOutQuint">easeOutQuint</option>
																				<option value="easeInOutQuint">easeInOutQuint</option>
																				<option value="easeInSine">easeInSine</option>
																				<option value="easeOutSine">easeOutSine</option>
																				<option value="easeInOutSine">easeInOutSine</option>
																				<option value="easeInExpo">easeInExpo</option>
																				<option value="easeOutExpo">easeOutExpo</option>
																				<option value="easeInOutExpo">easeInOutExpo</option>
																				<option value="easeInCirc">easeInCirc</option>
																				<option value="easeOutCirc">easeOutCirc</option>
																				<option value="easeInOutCirc">easeInOutCirc</option>
																				<option value="easeInElastic">easeInElastic</option>
																				<option value="easeOutElastic">easeOutElastic</option>
																				<option value="easeInOutElastic">easeInOutElastic</option>
																				<option value="easeInBack">easeInBack</option>
																				<option value="easeInOutBack">easeInOutBack</option>
																				<option value="easeInBounce">easeInBounce</option>
																				<option value="easeOutBounce">easeOutBounce</option>
																				<option value="easeInOutBounce">easeInOutBounce</option>
																			</select>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="col-sm-3 control-label"><?php echo _t('entry_split_text_per', 'Split Text per'); ?></label>
																		<div class="col-sm-9">
																			<select class="form-control" ng-model="activeLayer.layer_split">
																				<option value="none">No Split</option>
																				<option value="chars">Char Based</option>
																				<option value="words">Word Based</option>
																				<option value="lines">Line Based</option>
																			</select>
																		</div>
																	</div>
																</fieldset>

																<fieldset>
																	<legend><?php echo _t('text_end_transition', 'End Transition'); ?></legend>
																	<div class="form-group">
																		<label class="col-sm-3 control-label"><?php echo _t('entry_end_time', 'End Time (ms)'); ?></label>
																		<div class="col-sm-3">
																			<input type="text" class="form-control" ng-model="activeLayer.layer_endtime" />
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="col-sm-3 control-label"><?php echo _t('entry_end_effect', 'End Effect'); ?></label>
																		<div class="col-sm-6">
																			<select class="form-control" tooltip="<?php echo _t('text_end_animation', 'End Animation'); ?>" ng-model="activeLayer.layer_endanimation">
																				<option value="auto">Choose Automatic</option>
																				<option value="fadeout">Fade Out</option>
																				<option value="stt">Short to Top</option>
																				<option value="stb">Short to Bottom</option>
																				<option value="stl">Short to Left</option>
																				<option value="str">Short to Right</option>
																				<option value="ltt">Long to Top</option>
																				<option value="ltb">Long to Bottom</option>
																				<option value="ltl">Long to Left</option>
																				<option value="ltr">Long to Right</option>
																				<option value="skewtoright">Skew To Right</option>
																				<option value="skewtoleft">Skew To Left</option>
																				<option value="skewtorightshort">Skew To Right Short</option>
																				<option value="skewtoleftshort">Skew To Left Short</option>
																				<option value="randomrotateout">Random Rotate Out</option>
																			</select>
																		</div>
																		<div class="col-sm-3">
																			<input type="text" class="form-control" tooltip="<?php echo _t('text_end_speed', 'End Speed (ms)'); ?>" ng-model="activeLayer.layer_endspeed" />
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="col-sm-3 control-label"><?php echo _t('entry_end_easing', 'End Easing'); ?></label>
																		<div class="col-sm-9">
																			<select class="form-control" ng-model="activeLayer.layer_endeasing">
																				<option value="nothing">No Change</option>
																				<option value="Linear.easeNone">Linear.easeNone</option>
																				<option value="Power0.easeIn">Power0.easeIn  (linear)</option>
																				<option value="Power0.easeInOut">Power0.easeInOut  (linear)</option>
																				<option value="Power0.easeOut">Power0.easeOut  (linear)</option>
																				<option value="Power1.easeIn">Power1.easeIn</option>
																				<option value="Power1.easeInOut">Power1.easeInOut</option>
																				<option value="Power1.easeOut">Power1.easeOut</option>
																				<option value="Power2.easeIn">Power2.easeIn</option>
																				<option value="Power2.easeInOut">Power2.easeInOut</option>
																				<option value="Power2.easeOut">Power2.easeOut</option>
																				<option value="Power3.easeIn">Power3.easeIn</option>
																				<option value="Power3.easeInOut">Power3.easeInOut</option>
																				<option value="Power3.easeOut">Power3.easeOut</option>
																				<option value="Power4.easeIn">Power4.easeIn</option>
																				<option value="Power4.easeInOut">Power4.easeInOut</option>
																				<option value="Power4.easeOut">Power4.easeOut</option>
																				<option value="Quad.easeIn">Quad.easeIn  (same as Power1.easeIn)</option>
																				<option value="Quad.easeInOut">Quad.easeInOut  (same as Power1.easeInOut)</option>
																				<option value="Quad.easeOut">Quad.easeOut  (same as Power1.easeOut)</option>
																				<option value="Cubic.easeIn">Cubic.easeIn  (same as Power2.easeIn)</option>
																				<option value="Cubic.easeInOut">Cubic.easeInOut  (same as Power2.easeInOut)</option>
																				<option value="Cubic.easeOut">Cubic.easeOut  (same as Power2.easeOut)</option>
																				<option value="Quart.easeIn">Quart.easeIn  (same as Power3.easeIn)</option>
																				<option value="Quart.easeInOut">Quart.easeInOut  (same as Power3.easeInOut)</option>
																				<option value="Quart.easeOut">Quart.easeOut  (same as Power3.easeOut)</option>
																				<option value="Quint.easeIn">Quint.easeIn  (same as Power4.easeIn)</option>
																				<option value="Quint.easeInOut">Quint.easeInOut  (same as Power4.easeInOut)</option>
																				<option value="Quint.easeOut">Quint.easeOut  (same as Power4.easeOut)</option>
																				<option value="Strong.easeIn">Strong.easeIn  (same as Power4.easeIn)</option>
																				<option value="Strong.easeInOut">Strong.easeInOut  (same as Power4.easeInOut)</option>
																				<option value="Strong.easeOut">Strong.easeOut  (same as Power4.easeOut)</option>
																				<option value="Back.easeIn">Back.easeIn</option>
																				<option value="Back.easeInOut">Back.easeInOut</option>
																				<option value="Back.easeOut">Back.easeOut</option>
																				<option value="Bounce.easeIn">Bounce.easeIn</option>
																				<option value="Bounce.easeInOut">Bounce.easeInOut</option>
																				<option value="Bounce.easeOut">Bounce.easeOut</option>
																				<option value="Circ.easeIn">Circ.easeIn</option>
																				<option value="Circ.easeInOut">Circ.easeInOut</option>
																				<option value="Circ.easeOut">Circ.easeOut</option>
																				<option value="Elastic.easeIn">Elastic.easeIn</option>
																				<option value="Elastic.easeInOut">Elastic.easeInOut</option>
																				<option value="Elastic.easeOut">Elastic.easeOut</option>
																				<option value="Expo.easeIn">Expo.easeIn</option>
																				<option value="Expo.easeInOut">Expo.easeInOut</option>
																				<option value="Expo.easeOut">Expo.easeOut</option>
																				<option value="Sine.easeIn">Sine.easeIn</option>
																				<option value="Sine.easeInOut">Sine.easeInOut</option>
																				<option value="Sine.easeOut">Sine.easeOut</option>
																				<option value="SlowMo.ease">SlowMo.ease</option>
																				<option value="easeOutBack">easeOutBack</option>
																				<option value="easeInQuad">easeInQuad</option>
																				<option value="easeOutQuad">easeOutQuad</option>
																				<option value="easeInOutQuad">easeInOutQuad</option>
																				<option value="easeInCubic">easeInCubic</option>
																				<option value="easeOutCubic">easeOutCubic</option>
																				<option value="easeInOutCubic">easeInOutCubic</option>
																				<option value="easeInQuart">easeInQuart</option>
																				<option value="easeOutQuart">easeOutQuart</option>
																				<option value="easeInOutQuart">easeInOutQuart</option>
																				<option value="easeInQuint">easeInQuint</option>
																				<option value="easeOutQuint">easeOutQuint</option>
																				<option value="easeInOutQuint">easeInOutQuint</option>
																				<option value="easeInSine">easeInSine</option>
																				<option value="easeOutSine">easeOutSine</option>
																				<option value="easeInOutSine">easeInOutSine</option>
																				<option value="easeInExpo">easeInExpo</option>
																				<option value="easeOutExpo">easeOutExpo</option>
																				<option value="easeInOutExpo">easeInOutExpo</option>
																				<option value="easeInCirc">easeInCirc</option>
																				<option value="easeOutCirc">easeOutCirc</option>
																				<option value="easeInOutCirc">easeInOutCirc</option>
																				<option value="easeInElastic">easeInElastic</option>
																				<option value="easeOutElastic">easeOutElastic</option>
																				<option value="easeInOutElastic">easeInOutElastic</option>
																				<option value="easeInBack">easeInBack</option>
																				<option value="easeInOutBack">easeInOutBack</option>
																				<option value="easeInBounce">easeInBounce</option>
																				<option value="easeOutBounce">easeOutBounce</option>
																				<option value="easeInOutBounce">easeInOutBounce</option>
																			</select>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="col-sm-3 control-label"><?php echo _t('entry_split_text_per', 'Split Text per'); ?></label>
																		<div class="col-sm-9">
																			<select class="form-control" ng-model="activeLayer.layer_endsplit">
																				<option value="none">No Split</option>
																				<option value="chars">Char Based</option>
																				<option value="words">Word Based</option>
																				<option value="lines">Line Based</option>
																			</select>
																		</div>
																	</div>
																</fieldset>
															</accordion-group>
														</accordion>
														</fieldset>
													</div>
													<div class="col-sm-6 col-xs-12">
														<accordion>
															<accordion-group heading="<?php echo _t('text_layer_timing_and_sorting', 'Layer Timing & Sorting'); ?>" ng-init="ltsOpen = true; ltsDisabled = true;" is-open="ltsOpen" is-disabled="ltsDisabled">
																<p class="help-block" style="font-size: 12px; font-style: italic;">(<?php echo _t('text_layer_drag_help', 'Drag layer to sort by depth'); ?>)</p>
																<div class="layer-list" ui-sortable="layerSortableOptions" ng-model="layers" ng-controller="LayerListCtrl">
																	<div class="layer-item" ng-repeat="layer in layers" ng-class="{active: layer.$$active}" ng-mousedown="setActiveLayer(layer)">
																		<div class="layer-time-frame">
																			<span class="layer-time-frame-start-time">O ms</span>
																			<div class="layer-time-frame-bar" ui-slider="timeSliderOptions" min="0" max="{{slide.$$delay}}" step="1" ng-model="layer.$$time_range"></div>
																			<span class="layer-time-frame-end-time">{{slide.$$delay}} ms</span>
																		</div>
																		<div class="layer-info">
																			<p class="layer-description">{{layer.layer_caption}}</p>
																			<span class="layer-toggle" tooltip="<?php echo _t('text_show_hide', 'Show/Hide'); ?>" ng-click="toggleLayer(layer)"><i class="fa fa-fw fa-eye"></i></span>
																			<input type="text" class="layer-start-time" tooltip="<?php echo _t('text_enter_start_time_of_layer', 'Enter Start time of layer'); ?>" ng-model="layer.time_start" />
																		</div>
																	</div>
																</div>
															</accordion-group>
														</accordion>
													</div>
												</div>
											</div>
										</fieldset>
										<?php echo renderCloseOptionContainer() ?>
									</div>
								</div>
							</div>
						</section>
					</div>
				</div>
			</section>

			<div id="kuler-loader" ng-if="loading"></div>
		</section>
	</form>
</div>
<script>
	var Kuler = {
		store_id: '',
		save_url: <?php echo json_encode($save_url); ?>,
		front_base: <?php echo json_encode($front_base); ?>,
		messages: {},
		layerStyles: <?php echo json_encode($layer_styles); ?>,
		slider: <?php echo json_encode($sliderGroup); ?>,
		slide_option: <?php echo json_encode($slide_option); ?>,
		slide: <?php echo json_encode($slide_params); ?>,
		layers: <?php echo json_encode($layers); ?>,
		slideWidth: <?php echo json_encode($sliderGroup['params']['width']); ?>,
		slideHeight: <?php echo json_encode($sliderGroup['params']['height']); ?>
	};
</script>
<?php echo $footer; ?>