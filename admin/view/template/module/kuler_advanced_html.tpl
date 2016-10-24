<?php echo $header; ?><?php echo $column_left; ?>

<?php if (isset($ksb_building_mode)) { ?>
	<style type="text/css">
		#header, .breadcrumb, .box > .heading .buttons,.vtabs, #footer {
			display: none !important;
		}

		#content {
			padding: 0 !important;
		}

		.box > .content {
			background: none !important;
		}

		.vtabs-content {
			padding-left: 15px !important;
		}
	</style>
<?php } ?>
<?php if (isset($ksb_updated_module)) { ?>
<script type="text/javascript">
	var ksb_updated_module = <?php echo $ksb_updated_module; ?>;
</script>
<?php } ?>

<div id="content">
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<section id="kuler-module-content" class="kuler-module kuler-module-content-v1">
			<?php if ($success) { ?>
				<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
					<button type="button" class="close" data-dismiss="alert">&times;</button>
				</div>
			<?php } ?>
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
						<section class="panel" id="kuler-panel-navigation">
							<nav class="navbar" role="navigation" id="kuler-navbar-container">
								<div class="kuler-navigation-left">
									<div class="navbar-header">
										<h2><img src="view/kuler/image/icon/kuler_logo.png" /> <?php echo _t('heading_module'); ?></h2>
									</div>
								</div>
								<div class="kuler-navigation-right" id="kuler-navigation-space">
									<div class="pull-right main-actions" style="margin-top: 7px;">
										<a onclick="$('#form').submit();" class="btn-kuler btn btn-success"><?php echo $button_save; ?></a>
										<a href="<?php echo $cancel; ?>" class="btn-kuler btn btn-danger"><?php echo $button_cancel; ?></a>
									</div>
								</div>
							</nav>
						</section>

						<section class="panel" id="kuler-panel-section">
							<div class="panel-body">
								<div id="kuler-module-container" class="clearfix">
									<ul class="nav nav-pills nav-stacked vtabs">
										<?php $module_row = 1; ?>
										<?php foreach ($modules as $module) { ?>
											<li id="ModuleTabItem_{{tab.row}}" class="ModuleTabItem">
												<a href="#tab-module-<?php echo $module_row; ?>" id="module-<?php echo $module_row; ?>">
													<?php echo $module['main_title']; ?>
													<span class="remove-element module-remover" onclick="$('#module-<?php echo $module_row; ?>').remove(); $('#tab-module-<?php echo $module_row; ?>').remove(); $('.vtabs a:first').trigger('click'); return false;"><i class="fa fa-minus-circle"></i></span>
												</a>
											</li>
											<?php $module_row++; ?>
										<?php } ?>
									</ul>
									<div class="tab-content">
									<?php $module_row = 1; ?>
									<?php foreach ($modules as $module) { ?>
										<div id="tab-module-<?php echo $module_row; ?>" class="vtabs-content">
										<div class="form-horizontal">
											<div class="form-group">
												<label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
												<div class="col-sm-10">
													<div class="input-group col-sm-12" style="margin-top: 5px;">
															<input type="text" class="form-control" name="name" value="<?php echo $module['name']; ?>" />
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label"><?php echo $entry_title; ?></label>
												<div class="col-sm-10">
													<?php foreach ($languages as $language) { ?>
														<div class="input-group" style="margin-top: 5px;">
															<input type="text" class="form-control" name="kuler_advanced_html_module[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($kuler_advanced_html_module[$language['language_id']]['title']) ? $kuler_advanced_html_module[$language['language_id']]['title'] : ''; ?>" />
															<span class="input-group-addon btn-white"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"></span>
														</div>
													<?php } ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label"><?php echo $entry_showtitle; ?></label>
												<div class="col-sm-10">
													<div class="kuler-switch-btn">
														<input type='hidden' name='showtitle' value='0' />
														<input type="checkbox" name="showtitle"<?php echo $module['showtitle'] ? ' checked="checked"' : '' ?>  value="1">
														<span class="kuler-switch-btn-holder"></span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
												<div class="col-sm-10">
													<div class="kuler-switch-btn">
														<input type='hidden' name='status' value='0' />
														<input type="checkbox" name="status"<?php echo $module['status'] ? ' checked="checked"' : '' ?>  value="1">
														<span class="kuler-switch-btn-holder"></span>
													</div>
												</div>
											</div>
											<div class="form-group" style="display: none;">
												<label class="col-sm-2 control-label"><?php echo $__['entry_module_type']; ?></label>
												<div class="col-sm-10">
													<select name="kuler_advanced_html_module[<?php echo $module_row; ?>][module_type]" class="form-control Selector ModuleTypeSelector" data-widget-type-selector="#WidgetTypeSelector_<?php echo $module_row; ?>" data-editor-panel="#EditorPanel_<?php echo $module_row; ?>" data-product-panel="#ProductPanel_<?php echo $module_row; ?>" data-related-standard=".StandardRelatedRow_<?php echo $module_row; ?>" data-related-widget=".WidgetRelatedRow_<?php echo $module_row; ?>" style="width: 150px;">
														<option value="standard"<?php if (isset($module['module_type']) && $module['module_type'] == 'standard') echo ' selected="selected"'; ?>><?php echo $__['text_standard']; ?></option>
														<option value="widget"<?php if (isset($module['module_type']) && $module['module_type'] == 'widget') echo ' selected="selected"'; ?>><?php echo $__['text_widget']; ?></option>
													</select>
												</div>
											</div>

											<div class="HtmlRelatedRow_<?php echo $module_row; ?>" id="EditorPanel_<?php echo $module_row; ?>">
												<ul class="nav nav-tabs" id="language-<?php echo $module_row; ?>">
													<?php foreach ($languages as $language) { ?>
														<li>
															<a href="#tab-language-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>">
																<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>">
																<?php echo $language['name']; ?>
															</a>
														</li>
													<?php } ?>
												</ul>

												<?php foreach ($languages as $language) { ?>
													<div id="tab-language-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>" class="form-group">
														<label class="col-sm-2 control-label"><?php echo $entry_description; ?></label>
														<div class="col-sm-10">
															<textarea name="kuler_advanced_html_module[<?php echo $language['language_id']; ?>][description]" id="description-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>"><?php echo isset($kuler_advanced_html_module[$language['language_id']]['description']) ? $kuler_advanced_html_module[$language['language_id']]['description'] : ''; ?></textarea>
														</div>
													</div>
												<?php } ?>
											</div>
										</div>
									</div>
									<?php $module_row++; ?>
									<?php } ?>
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

<script type="text/javascript">
    <?php $module_row = 1; ?>
    <?php foreach ($modules as $module) { ?>
    <?php foreach ($languages as $language) { ?>
    $('#description-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>').summernote({
	    height: 300
    });
    <?php } ?>
    <?php $module_row++; ?>
    <?php } ?>
</script>
<script type="text/javascript">
var saveUrl = '<?php echo $action; ?>';
saveUrl = saveUrl.replace(new RegExp('&amp;', 'g'), '&');
$('#StoreSelector').on('change', function () {
    window.location = saveUrl + '&store_id=' + $(this).val();
});

var StoreId = <?php echo $selected_store_id; ?>;

var g_token = '<?php echo $token; ?>';

$.fn.tabs = function() {
	var selector = this;

	this.each(function() {
		var obj = $(this);

		$(obj.attr('href')).hide();

		$(obj).click(function() {
			$(selector).removeClass('selected')
				.parent()
				.removeClass('active');;

			$(selector).each(function(i, element) {
				$($(element).attr('href')).hide();
			});

			$(this).addClass('selected').parent().addClass('active');

			$($(this).attr('href')).show();

			return false;
		});
	});

	$(this).show();

	$(this).first().click();
};

var Selector = (function ($) {
    return {
        init: function (selector) {
            $('body').on('change', selector, function () {
                var $selector = $(this),
                    data = $selector.data(),
                    values = $.map($selector.find('option'), function (option) {
                        return option.value;
                    });

                $.each(values, function (index, value) {
                    var prop = $.camelCase('related-' + value);

                    if (prop in data) {
                        if (value == $selector.val()) {
                            $(data[prop]).show();
                        } else {
                            $(data[prop]).hide();
                        }
                    }
                });
            });

            $(selector).trigger('change');
        }
    };
})(jQuery);

Selector.init('.Selector');

var ModuleTypeSelector = (function () {
    return {
        init: function (selector, context) {
            $(selector, context || document).on('change', function () {
                var $this = $(this);

                if ($this.val() == 'standard') {
                    $($this.data('editorPanel')).show();
                    $($this.data('productPanel')).hide();
                } else {
                    $($this.data('widgetTypeSelector')).trigger('change');
                }
            });
        }
    };
})();

ModuleTypeSelector.init('.ModuleTypeSelector')

var base = '<?php echo $base; ?>';
var ImageManager = (function () {
    return {
	    init: function (selector, context) {
		    var self = this;
		    self.$el = $(selector, context || document);

		    self.$el.bind('click', function (evt) {
			    evt.preventDefault();

			    self.showDialog($(this).data('field'), $(this).data('image'));
		    });

		    self.$el.each(function () {
			    var $this = $(this);

			    $($this.data('clear')).bind('click', function (evt) {
				    evt.preventDefault();

				    $('#' + $this.data('field')).val('');
				    $($this.data('image')).html('');
			    });
		    });
	    },
	    showDialog: function (field, image) {
		    $('#dialog').remove();

		    $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

		    var val1 = $('#' + field).val();

		    $('#dialog').dialog({
			    title: 'Image Manager',
			    close: function (event, ui) {
				    var val2 = $('#' + field).val();

				    if (val1 != val2) {
					    $('#' + field).val('image/' + val2).trigger('change');

					    if (image !== undefined) {
						    $(image).html($('<img />', {src: '<?php echo $base; ?>image/' + val2}));
					    }
				    }
			    },
			    bgiframe: false,
			    width: 700,
			    height: 400,
			    resizable: false,
			    modal: false
		    });
	    }
    };
})();

ImageManager.init('.ImageManager');

var module_row = <?php echo $module_row; ?>;

<?php $module_row = 1; ?>
<?php foreach ($modules as $module) { ?>
$('#language-<?php echo $module_row; ?> a').click(function() {
    $('#htab').val($(this).attr('href'));
}).tabs();
<?php $module_row++; ?>
<?php } ?>
</script>

<?php echo $footer; ?>