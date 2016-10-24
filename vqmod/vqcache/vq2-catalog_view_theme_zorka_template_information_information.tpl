<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"  style="margin-bottom:30px!important;"><?php echo $column_left; ?>
	  <?php if ($column_left && $column_right) { ?>
		  <?php $class = 'col-lg-6 col-sm-6'; ?>
	  <?php } elseif ($column_left || $column_right) { ?>
		  <?php $class = 'col-lg-9 col-sm-9'; ?>
	  <?php } else { ?>
		  <?php $class = 'col-lg-12 col-sm-12'; ?>
	  <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
				<?php echo $content_top; ?>
				
				<?php if( ! empty( $breadcrumbs ) && is_array( $breadcrumbs ) ) { ?>
					<ul style="display:none;">
						<?php foreach( $breadcrumbs as $breadcrumb ) { ?>
							<?php if( NULL != ( $smk_title = strip_tags( $breadcrumb['text'] ) ) ) { ?>
								<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
									<a href="<?php echo $breadcrumb['href']; ?>" itemprop="url"><span itemprop="title"><?php echo strip_tags( $breadcrumb['text'] ); ?></span></a>
								</li>
							<?php } ?>
						<?php } ?>
					</ul>
				<?php } ?>
			
			
      <h1><?php echo $heading_title; ?></h1>
      <?php echo $description; ?></div>
    <?php echo $column_right; ?></div>
<?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?> 