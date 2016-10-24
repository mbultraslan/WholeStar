<div id="carousel<?php echo $module; ?>" class="carousel container owl-carousel">
  <?php foreach ($banners as $banner) { ?>
    <div class="item text-center">
      <?php if ($banner['link']) { ?>
        <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" /></a>
      <?php } else { ?>
        <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" />
      <?php } ?>
    </div>
  <?php } ?>
</div>
<script type="text/javascript">
  $('#carousel<?php echo $module; ?>').owlCarousel({
    loop:true,
    margin:10,
    autoPlay: 9000,
    navigation: false,
    pagination: true,
    responsive:{
      0:{
        items:2
      },
      600:{
        items:4
      },
      1000:{
        items:6
      }
    }
  })
</script>