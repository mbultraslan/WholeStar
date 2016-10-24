<?php
require_once(DIR_APPLICATION . 'controller/module/kuler_cp.php');
$kuler = Kuler::getInstance();
?>

<div class="kt-module<?php if (!empty($settings['css_class'])) echo ' ' . $settings['css_class']; ?>" id="kt-module-<?php echo $module; ?>">
  <div class="box kuler-module">
    <?php if (!empty($settings['show_title'])) { ?>
      <div class="box-heading"><span><?php echo $kuler->translate($settings['title']); ?></span></div>
    <?php } ?>
    <div class="box-content">
      <div class="col-xs-12 col-no-padding kt-carousel">
        <?php if (!empty($settings['testimonials'])) { ?>
          <?php foreach ($settings['testimonials'] as $testimonial) { ?>
            <div class="kt-module__item">
              <?php if (!empty($testimonial['writer_image'])) { ?>
                <img src="image/<?php echo $kuler->translate($testimonial['writer_image']); ?>" class="kt-module__item__image">
              <?php } ?>
              <?php if (!empty($testimonial['writer_name'])) { ?>
                <p class="kt-module__item__writer"><?php echo $kuler->translate($testimonial['writer_name']); ?></p>
              <?php } ?>
              <?php if (!empty($testimonial['testimonial_information'])) { ?>
                <p class="kt-module__item__information"><?php echo $kuler->translate($testimonial['testimonial_information']); ?></p>
              <?php } ?>
              <?php if (!empty($testimonial['testimonial'])) { ?>
                <p class="kt-module__item__testimonial"><?php echo $kuler->translate($testimonial['testimonial']); ?></p>
              <?php } ?>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<script>
  $('.kt-carousel').owlCarousel({
    items: <?php echo json_encode(intval($settings['testimonials_per_view'])); ?>,
    <?php if ($settings['auto_play']) { ?>
    autoPlay: 5000,
    <?php } ?>
    loop:true,
    responsiveClass:true,
    pagination : true
  })
</script>