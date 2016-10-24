<?php
$kuler = Kuler::getInstance();
$theme = $kuler->getTheme();

if ($settings['type'] == 'accordion') {
  $kuler->addScript($kuler->getThemeResource('catalog/view/javascript/kuler/accordion/kuler_accordion.js'), true);
}

if ($has_deal_date) {
$kuler->addScript($kuler->getThemeResource('catalog/view/javascript/kuler/countdown/jquery.plugin.min.js'), true);
$kuler->addScript($kuler->getThemeResource('catalog/view/javascript/kuler/countdown/jquery.countdown.min.js'), true);
}
?>

<div class="kuler-showcase-module<?php if (!empty($settings['css_class'])) echo ' ' . $settings['css_class']; ?>" id="kuler-showcase-module-<?php echo $module; ?>">
  <div class="box kuler-module">
    <?php if (!empty($settings['show_title'])) { ?>
    <div class="box-heading"><span><?php echo $kuler->translate($settings['title']); ?></span></div>
    <?php } ?>
    <?php if ($settings['type'] == 'tab') { ?>
    <div class="tab-heading">
      <?php foreach ($showcases as $showcase) { ?>
      <?php if (!empty($showcase['show_title'])) { ?>
      <span data-hover="<?php echo $kuler->translate($showcase['title']); ?>"><?php echo $kuler->translate($showcase['title']); ?></span>
      <?php } ?>
      <?php } ?>
    </div>
    <?php } ?>
    <?php if ($settings['type'] == 'slide' || $settings['type'] == 'tab') { ?>
    <div class="box-content">
      <?php foreach ($showcases as $showcase) { ?>
      <div class="showcase<?php if (!empty($showcase['css_class'])) echo ' ' . $showcase['css_class']; ?>">
        <div class="container col-no-padding" style="max-width:100%">
          <div class="row">
          <?php if (!empty($showcase['items'])) { ?>
            <?php foreach ($showcase['items'] as $item) { ?>
              <div class="item <?php echo $item['type']; ?><?php if (!empty($item['css_class'])) echo ' ' . $item['css_class']; ?>">
                <?php if ($item['type'] == 'html') { ?>
                  <?php echo $kuler->translate($item['html_content']); ?>
                <?php } else if ($item['type'] == 'product') { ?>
                  <div class="box-product">
                    <?php foreach ($item['products'] as $product) { ?>
                      <?php echo $kuler->common->loadProductTemplate($item, $product, 'grid'); ?>
                    <?php } ?>
                  </div>
                <?php } ?>
              </div>
            <?php } ?>
          <?php } ?>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
  <?php } ?>
  <?php if ($settings['type'] == 'slide') { ?>
  <div class="nav">
    <span class="prev">&lt;</span>
    <span class="next">&gt;</span>
  </div>
  <?php } ?>
  <?php if ($settings['type'] == 'accordion') { ?>
  <div id="kuler-accordion">
    <div class="box-content horizontal">
      <?php foreach ($showcases as $showcase) { ?>
      <div class="slide">
        <div class="item-title"><span><?php echo $showcase['mainTitle']; ?></span></div>
        <div class="item-content">
          <?php foreach ($showcase['items'] as $item) { ?>
          <div class="item <?php echo $item['type']; ?><?php if (!empty($item['css_class'])) echo ' ' . $item['css_class']; ?>">
            <?php if ($item['type'] == 'html') { ?>
            <?php echo $kuler->translate($item['html_content']); ?>
            <?php } else if ($item['type'] == 'product') { ?>
            <div class="box-product product-grid">
              <?php foreach ($item['products'] as $product) { ?>
              <?php echo $kuler->common->loadProductTemplate($item, $product, 'grid'); ?>
              <?php } ?>
            </div>
            <?php } ?>
          </div>
          <?php } ?>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
  <?php } ?>
</div>
</div>
<script>
  $(function () {
    if (!$('#kuler-showcase-module-<?php echo $module; ?> .tab-heading').children().length) {
      $('#kuler-showcase-module-<?php echo $module; ?> .tab-heading').remove();
    }

    if ($.fn.countdown) {
      $.countdown.setDefaults({
        labels: ['<?php echo $__['text_years'];  ?>', '<?php echo $__['text_months']; ?>', '<?php echo $__['text_weeks']; ?>', '<?php echo $__['text_days']; ?>', '<?php echo $__['text_hours']; ?>', '<?php echo $__['text_minutes']; ?>', '<?php echo $__['text_seconds']; ?>'],
        labels1: ['<?php echo $__['text_year']; ?>', '<?php echo $__['text_month']; ?>', '<?php echo $__['text_week']; ?>', '<?php echo $__['text_day']; ?>', '<?php echo $__['text_hour']; ?>', '<?php echo $__['text_minute']; ?>', '<?php echo $__['text_second']; ?>'],
        compactLabels: ['y', 'm', 'w', 'd'],
        whichLabels: null,
        digits: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
        timeSeparator: ':',
        isRTL: <?php echo $direction == 'rtl' ? 'true': 'false'; ?>
      });

      $('#kuler-showcase-module-<?php echo $module; ?> .product-deal-countdown').each(function () {
        var $dealEl = $(this);

        if ($dealEl.data('isDeal')) {
          $dealEl.countdown({
            until: new Date($dealEl.data('year'), $dealEl.data('month') - 1, $dealEl.data('day')),
            compact: false
          });
        }
      });
    }
  });

  $(window).on('load', function () {
    <?php if ($settings['type'] == 'slide') { ?> 
      
      var owl = $('#kuler-showcase-module-<?php echo $module; ?> .box-content');

      owl.owlCarousel({
        loop: true,
        items: 1,
        dots: false,
        autoHeight: true,
        rtl: <?php echo $direction == 'rtl' ? 'true' : 'false'; ?>
      });

      $("#kuler-showcase-module-<?php echo $module; ?> .next").click(function(){
        owl.trigger('next.owl.carousel');
      });

      $("#kuler-showcase-module-<?php echo $module; ?> .prev").click(function(){
        owl.trigger('prev.owl.carousel');
      });
    <?php } else if ($settings['type'] == 'tab') { ?>
      
      var $tabHeading = $("#kuler-showcase-module-<?php echo $module; ?> .tab-heading span");
          $tabHeading.first().addClass('active');

      var owl = $('#kuler-showcase-module-<?php echo $module; ?> .box-content');

      owl.owlCarousel({
        loop: true,
        items: 1,
        dots: false,
        autoHeight: true,
        rtl: <?php echo $direction == 'rtl' ? 'true' : 'false'; ?>
      });

      owl.on('changed.owl.carousel', function(e) {
        var tabIdx = e.item.index % e.item.count - 1;

        $tabHeading
          .removeClass('active')
          .eq(tabIdx)
          .addClass('active');
      });

      $tabHeading.on('touchstart mousedown', function(e) {
        e.preventDefault();
        owl.trigger('to.owl.carousel', [$(this).index(), 300, true]);
      });

    <?php } else if ($settings['type'] == 'accordion') { ?>
      $('#kuler-accordion .box-content').kulerAccordion({
        duration: 600
    });
    <?php } ?>
  });
</script>