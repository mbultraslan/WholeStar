<?php if ($sliders) { ?>
<?php
$class = $sliderParams['fullwidth'] ? $sliderParams['fullwidth'] : 'boxed';
?>
<?php if (!$sliderParams['fullwidth']) { ?>
<style>
	.kls-container {
		margin: 0 auto;
		max-width: <?php echo $sliderParams['width']; ?>px;
	}
</style>
<?php } ?>
<div id="kls-<?php echo $module ?>" class="kls-container kls-<?php echo $sliderParams['fullwidth'] ?>">
	<div class="kls-banner">
		<ul>
			<?php foreach ($sliders as $slider) { ?>
			<?php if ($slider['status']) { ?>
			<li
				data-transition="<?php echo $slider['params']['slider_transition']; ?>"
				data-masterspeed="<?php echo $slider['params']['slider_duration']; ?>"
				<?php if (!empty($slider['params']['slider_slot'])) { ?>
				data-slotamound="<?php echo $slider['params']['slider_slot']; ?>"
				<?php } ?>
				<?php if (!empty($slider['params']['slider_delay'])) { ?>
				data-delay="<?php echo $slider['params']['slider_delay']; ?>"
				<?php } ?>
				<?php if ($slider['params']['slider_link']) { ?>
				data-link="<?php echo $slider['params']['slider_link']; ?>" data-target="_blank"
				<?php } ?>
				>
				<?php if ($slider['main_image']) { ?>
					<img src="<?php echo $slider['main_image']; ?>" alt="<?php echo $slider['title']; ?>" />
				<?php } ?>
				<?php foreach ($slider['layersparams']->layers as $i => $layer)  { ?>
				<?php 
					$type = $layer['layer_type'];
				?>
					<div class="caption <?php echo !empty($layer['layer_class']) ? $layer['layer_class'] : ''; ?> <?php echo $layer['layer_animation'];?> <?php echo $layer['layer_easing'];?><?php if (!empty($layer['layer_endanimation']) && $layer['layer_endanimation'] != 'auto') echo ' ' . $layer['layer_endanimation']; ?>"
						data-x="<?php echo $layer['layer_left'] == 'left' ? $layer['layer_hoffset'] : $layer['layer_left']; ?>"
						data-y="<?php echo $layer['layer_top'] == 'top' ? $layer['layer_voffset'] : $layer['layer_top']; ?>"
						<?php if (!empty($layer['layer_hoffset'])) { ?>
						data-hoffset="<?php echo $layer['layer_hoffset']; ?>"
						<?php } ?>
						<?php if (!empty($layer['layer_voffset'])) { ?>
						data-voffset="<?php echo $layer['layer_voffset']; ?>"
						<?php } ?>
						<?php if (!empty($layer['layer_endtime'])) { ?>
							data-end="<?php echo $layer['layer_endtime']; ?>"
						<?php } ?>
						<?php if (!empty($layer['layer_endeasing']) && $layer['layer_endeasing'] != 'nothing') { ?>
							data-endeasing="<?php echo $layer['layer_endeasing']; ?>"
						<?php } ?>
						<?php if (!empty($layer['layer_endeasing']) && $layer['layer_endeasing'] != 'nothing' && !empty($layer['layer_endspeed']) && $layer['layer_endspeed']) { ?>
							data-endspeed="<?php echo $layer['layer_endspeed']; ?>"
						<?php } ?>
						<?php if (!empty($layer['layer_speed'])) { ?>
						data-speed="<?php echo $layer['layer_speed']; ?>"
						<?php } ?>
						<?php if (!empty($layer['layer_split'])) { ?>
						data-splitin="<?php echo $layer['layer_split']; ?>"
						<?php } ?>
						<?php if (!empty($layer['time_start'])) { ?>
						data-start="<?php echo $layer['time_start']; ?>"
						<?php } ?>
						<?php if (!empty($layer['layer_endsplit'])) { ?>
							data-splitout="<?php echo $layer['layer_endsplit']; ?>"
						<?php } ?>
						<?php if (!empty($layer['layer_easing'])) { ?>
						data-easing="<?php echo $layer['layer_easing']; ?>">
						<?php } ?>
							<?php if( $type =='image') { ?>
								<img src="<?php echo $url."image/".$layer['layer_content']; ?>" alt="Layer">
							<?php } else { ?>
								<?php echo html_entity_decode(str_replace('_ASM_', '&', $layer['layer_caption']) , ENT_QUOTES, 'UTF-8'); ?>
							<?php } ?>
					</div>
				<?php } ?>
			</li>
			<?php } ?>
			<?php } ?>
		</ul>
		<?php if ($sliderParams['show_time_line']) { ?>
		<div class="tp-bannertimer tp-<?php echo $sliderParams['time_line_position']; ?>"></div>
		<?php } ?>
	</div>
</div>
<script>
	$(function () {
		jQuery('#kls-<?php echo $module ?> .kls-banner').revolution({
			delay:<?php echo $sliderParams['delay'];?>,
			startheight:<?php echo $sliderParams['height'];?>,
			startwidth:<?php echo $sliderParams['width'];?>,


			hideThumbs:<?php echo (int)$sliderParams['hide_navigator_after'];?>,

			thumbWidth:<?php echo (int)$sliderParams['thumbnail_width'];?>,
			thumbHeight:<?php echo (int)$sliderParams['thumbnail_height'];?>,
			thumbAmount:<?php echo (int)$sliderParams['thumbnail_amount'];?>,

			navigationType:"<?php echo $sliderParams['navigator_type'];?>",
			navigationArrows:"<?php echo $sliderParams['navigator_arrows'];?>",
			<?php if( $sliderParams['navigation_style'] != 'none' ) {   ?>
			navigationStyle:"<?php echo $sliderParams['navigation_style'];?>",
			<?php } ?>

			navOffsetHorizontal:<?php echo (int)$sliderParams['offset_horizontal'];?>,
			navOffsetVertical:<?php echo (int)$sliderParams['offset_vertical'];?>,

			touchenabled:"<?php echo ($sliderParams['touch_mobile']?'on':'off') ?>",
			onHoverStop:"<?php echo ($sliderParams['stop_on_hover']?'on':'off') ?>",
			shuffle:"<?php echo ($sliderParams['shuffle_mode']?'on':'off') ?>",
			stopAtSlide:-1,
			stopAfterLoops:-1,

			hideCaptionAtLimit:0,
			hideAllCaptionAtLilmit:0,
			hideSliderAtLimit: <?php echo intval($sliderParams['hide_screen_width']); ?>,
			<?php if ($sliderParams['fullwidth'] == 'fullwidth') { ?>
			fullWidth: "on",
			<?php } ?>
			<?php if ($sliderParams['fullwidth'] == 'fullscreen') { ?>
			fullScreen: 'on',
			fullScreenAlignForce:"on",
			<?php } ?>
			<?php if (empty($sliderParams['show_time_line'])) { ?>
			hideTimerBar: 'on',
			<?php } ?>
			<?php if (!empty($sliderParams['show_navigator'])) { ?>
			hideThumbs: 0,
			<?php } else if (!empty($sliderParams['hide_navigator_after'])) { ?>
			hideThumbs: <?php echo json_encode($sliderParams['hide_navigator_after']); ?>,
			<?php } ?>
			shadow: <?php echo (int)$sliderParams['shadow_type']; ?>
		});
	});
</script>
<?php } ?>