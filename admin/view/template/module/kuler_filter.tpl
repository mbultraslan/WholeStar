<?php include_once(DIR_TEMPLATE . 'module/kuler_helper.tpl'); ?>
<?php echo $header; ?><?php echo $column_left; ?>
    <div id="content">
        <section id="kuler-module-content" class="kuler-module" ng-app="kulerModule" ng-controller="FilterCtrl">
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

                <div class="row">
                    <div class="col-sm-12">
	                    <section class="panel" id="kuler-panel-navigation">
		                    <nav class="navbar" role="navigation" id="kuler-navbar-container">
			                    <div class="kuler-navigation-left">
				                    <div class="navbar-header">
					                    <h2><img src="view/kuler/image/icon/kuler_logo.png" /> <?php echo _t('heading_kuler_module'); ?></h2>
				                    </div>
			                    </div>
			                    <div class="kuler-navigation-right" id="kuler-navigation-space">
				                    <div class="pull-right main-actions">
					                    <button class="btn-kuler btn btn-success" ng-click="save()"><i class="fa fa-check-circle-o"></i> <?php echo _t('button_save'); ?></button>
					                    <a class="btn-kuler btn btn-danger" href="<?php echo $cancel_url; ?>"><i class="fa fa-times-circle"></i> <?php echo _t('button_cancel'); ?></a>
				                    </div>
			                    </div>
		                    </nav>
	                    </section>

                        <section class="panel" id="kuler-panel-section">
                            <div class="panel-body">
								<tabset vertical="true" main-tab="true" type="pills" id="kuler-module-container" class="clearfix">
									<tab ng-repeat="module in modules" active="module.active" select="onSelectModule($index)">
										<tab-heading>
											<i class="fa fa-file-text-o"></i>
											{{module.mainTitle}}
											<span class="module-remover" ng-click="removeModule($index)" tooltip="<?php echo _t('button_remove', 'Remove') ?>" event-prevent-default event-stop-propagation><i class="fa fa-minus-circle"></i></span>
										</tab-heading>
										<div class="module" id="module-{{$index}}" ng-init="moduleIndex = $index">
											<?php echo renderBeginOptionContainer(); ?>
											<?php echo renderOption(array(
												'label' => _t('entry_name', 'Module Name'),
												'type' => 'input',
												'name' => 'module.name',
											)); ?>
											<?php echo renderOption(array(
												'label' => _t('entry_title', 'Title'),
												'type' => 'multilingual_input',
												'name' => 'module.title',
												'inputAttrs' => 'index="{{$index}}" on-change="onTitleChanged"'
											)); ?>
											<?php echo renderOption(array(
												'label' => _t('entry_shortcode', 'Short Code'),
												'type' => 'input',
												'name' => 'module.shortcode',
												'inputAttrs' => 'disabled'
											)); ?>
											<?php echo renderOption(array(
												'label' => _t('entry_show_title', 'Show Title'),
												'type' => 'switch',
												'name' => 'module.show_title'
											)); ?>
											<?php echo renderOption(array(
												'label' => _t('entry_status', 'Status'),
												'type' => 'switch',
												'name' => 'module.status'
											)); ?>
                                            <?php echo renderOption(array(
                                                'label' => _t('entry_show_category_filter', 'Show Category Filter'),
                                                'type' => 'switch',
                                                'name' => 'module.category'
                                            )); ?>
                                            <?php echo renderOption(array(
                                                'label' => _t('entry_show_manufacturer_filter', 'Show Manufacturer Filter'),
                                                'type' => 'switch',
                                                'name' => 'module.manufacture'
                                            )); ?>
                                            <?php echo renderOption(array(
                                                'label' => _t('entry_show_attribute_filter', 'Show Attribute Filter'),
                                                'type' => 'switch',
                                                'name' => 'module.attribute'
                                            )); ?>
                                            <div class="form-group" ng-if="module.attribute">
                                                <label class="col-lg-2 col-sm-2 control-label"><?php echo _t('entry_exclude_attributes', 'Exclude Attributes') ?></label>
                                                <div class="col-sm-8">
                                                    <button type="button" class="btn btn-default" ng-click="module.$$attributeClose = !module.$$attributeClose"><?php echo _t('button_show_hide_attributes'); ?></button>
                                                    <div class="tree" collapse="module.$$attributeClose">
                                                        <?php if ($attr_groups) { ?>
                                                            <ul>
                                                                <?php foreach ($attr_groups as $attr_group) { ?>
                                                                    <li>
                                                                        <label><input type="checkbox" ng-checked="isChecked('attribute_group', moduleIndex, '<?php echo $attr_group['attribute_group_id']; ?>')" ng-click="toggleExclude('attribute_group', moduleIndex, '<?php echo $attr_group['attribute_group_id']; ?>')" /> <?php echo $attr_group['name']; ?></label>
                                                                        <?php if (!empty($attr_group['attrs'])) { ?>
                                                                            <ul>
                                                                                <?php foreach ($attr_group['attrs'] as $attr) { ?>
                                                                                    <li><label><input type="checkbox" ng-checked="isChecked('attribute', moduleIndex, '<?php echo $attr['attribute_id']; ?>')" ng-click="toggleExclude('attribute', moduleIndex, '<?php echo $attr['attribute_id']; ?>')" /> <?php echo $attr['name']; ?></label></li>
                                                                                <?php } ?>
                                                                            </ul>
                                                                        <?php } ?>
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php echo renderOption(array(
                                                'label' => _t('entry_show_option_filter', 'Show Option Filter'),
                                                'type' => 'switch',
                                                'name' => 'module.option'
                                            )); ?>
                                            <div class="form-group" ng-if="module.option">
                                                <label class="col-lg-2 col-sm-2 control-label"><?php echo _t('entry_exclude_attributes', 'Exclude Attributes') ?></label>
                                                <div class="col-sm-8">
                                                    <button type="button" class="btn btn-default" ng-click="module.$$optionClose = !module.$$optionClose"><?php echo $__['button_show_hide_options']; ?></button>
                                                    <div class="tree" collapse="module.$$optionClose">
                                                        <?php if ($options) { ?>
                                                            <ul>
                                                                <?php foreach ($options as $option) { ?>
                                                                    <li>
                                                                        <label><input type="checkbox" ng-checked="isChecked('option_group', moduleIndex, '<?php echo $option['option_id']; ?>')" ng-click="toggleExclude('option_group', moduleIndex, '<?php echo $option['option_id']; ?>')" /> <?php echo $option['name']; ?></label>
                                                                        <?php if (!empty($option['option_values'])) { ?>
                                                                            <ul>
                                                                                <?php foreach ($option['option_values'] as $option_value) { ?>
                                                                                    <li><label><input type="checkbox" ng-checked="isChecked('option', moduleIndex, '<?php echo $option_value['option_value_id']; ?>')" ng-click="toggleExclude('option', moduleIndex, '<?php echo $option_value['option_value_id']; ?>')" /> <?php echo $option_value['name']; ?></label></li>
                                                                                <?php } ?>
                                                                            </ul>
                                                                        <?php } ?>
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php echo renderOption(array(
                                                'label' => _t('entry_show_price_filter', 'Show Price Filter'),
                                                'type' => 'switch',
                                                'name' => 'module.price_filter'
                                            )); ?>
                                            <?php echo renderOption(array(
                                                'label' => _t('entry_price_range', 'Price Range'),
                                                'type' => 'html',
                                                'html' => renderInput(array(
                                                        'name' => 'module.price_min',
                                                        'column' => 2
                                                    )) . renderInput(array(
                                                        'name' => 'module.price_max',
                                                        'column' => 2
                                                    )),
                                                'rowAttrs' => array('ng-if="module.price_filter"')
                                            )); ?>
                                            <?php echo renderOption(array(
                                                'label' => _t('entry_price_currency', 'Price Currency'),
                                                'type' => 'select',
                                                'name' => 'module.currency',
                                                'options' => $currency_options,
                                                'rowAttrs' => array('ng-if="module.price_filter"')
                                            )); ?>
                                            <?php echo renderCloseOptionContainer(); ?>
										</div>
									</tab>
								</tabset>
                            </div>
                        </section>

                    </div>
                </div>
            </section>
            <div id="kuler-loader" ng-if="loading"></div>
        </section>
    </div>
    <script>
        var Kuler = {
            store_id: <?php echo $store_id ?>,
            actionUrl: <?php echo json_encode($action_url); ?>,
            cancelUrl: <?php echo json_encode($cancel_url); ?>,
            front_base: <?php echo json_encode($front_base); ?>,
            storeUrl: <?php echo json_encode($store_url); ?>,
            token: <?php echo json_encode($token); ?>,
            extensionCode: <?php echo json_encode($extension_code); ?>,
            modules: <?php echo json_encode($modules); ?>,
            languages: <?php echo json_encode($languages); ?>,
            configLanguage: <?php echo json_encode($config_language); ?>,
            messages: <?php echo json_encode($messages); ?>,
            defaultModule: <?php echo json_encode($default_module); ?>
        };
    </script>
<?php echo $footer; ?>