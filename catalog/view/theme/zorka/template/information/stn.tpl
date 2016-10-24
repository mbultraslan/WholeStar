<?php print_r($stnimages); die(); ?>
<?php foreach($stnimages as $stnimage){?>
	  <div class="col-lg-12 col-md-12" style="padding-left:0;">
	  <a href="<?php echo $stnimage['link']; ?>" target="_blank"><span class="allign-rgt-title" style="padding-left:0;"><?php echo $stnimage['banner_title']; ?></span><span class="allign-lft-img"><?php if($stnimage['image'] != ''){echo '<img src="image/'.$stnimage['image'].'"';} ?></span><span class="allign-lft-img"><?php echo $stnimage['banner_width']; ?></span></a>
	  </div>
	  <?php } ?>