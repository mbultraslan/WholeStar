<div id="kuler-advanced-html-<?php echo $module; ?>" class="kuler-advanced-html"<?php if (isset($setting['module_type']) && $setting['module_type'] == 'widget') { echo ' style="display: none;"'; } ?>>
    <?php if (isset($setting['module_type']) && $setting['module_type'] == 'widget') { ?>
	<span class="widget-icon<?php if (!empty($setting['widget_icon'])) echo ' custom-icon' ?>"<?php if (!empty($setting['widget_icon_color'])) echo ' style="background-color: '. $setting['widget_icon_color'] .'"'; ?>>
        <?php if (!empty($setting['widget_icon'])) { ?>
            <img src="<?php echo $setting['widget_icon']; ?>" />
        <?php } ?>
	</span>
    <?php } ?>

	<div class="box kuler-module">
		<?php if($show_title) { ?>
			<div class="box-heading"><span><?php echo $heading_title; ?></span></div>
		<?php } ?>
		<div class="box-content clearafter">
           <?php echo $html; ?>
		</div>
	</div>
</div>

<?php if (isset($setting['module_type']) && $setting['module_type'] == 'widget') { ?>
<script type="text/javascript">
    $(function () {
        if (!$('.kuler-widget.<?php echo $setting['widget_position']; ?>').length) {
            $('<div class="kuler-widget <?php echo $setting['widget_position']; ?>" />').appendTo('body');
        }

        $('#kuler-advanced-html-<?php echo $module; ?>')
            .appendTo('.kuler-widget.<?php echo $setting['widget_position']; ?>')
            .show();
    });
</script>
<?php } ?>