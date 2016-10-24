<?php
$kuler = Kuler::getInstance();
?>
<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($kuler->getSkinOption('show_google_map')) { ?>
    <div id="google-map">
      <div id="map" style="width: 100%; height: 400px;"></div>
      <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
      <script>
        Kuler.latitude = <?php echo json_encode($kuler->getSkinOption('latitude')); ?>;
        Kuler.longitude = <?php echo json_encode($kuler->getSkinOption('longitude')); ?>;
        Kuler.map_type = <?php echo json_encode($kuler->getSkinOption('map_type')); ?>;

        (function () {
          jQuery(document).ready(function ($) {
            var map_canvas = document.getElementById('map');
            var map_options = {
              center: new google.maps.LatLng(Kuler.latitude, Kuler.longitude),
              zoom: 8,
              mapTypeId: google.maps.MapTypeId[Kuler.map_type]
            }
            var map = new google.maps.Map(map_canvas, map_options);
            map.setZoom(15);

            var latLng = new google.maps.LatLng(Kuler.latitude, Kuler.longitude);
            var image = new google.maps.MarkerImage(
              'catalog/view/theme/' + Kuler.theme + '/image/icon/map-marker.png',
              new google.maps.Size(57, 76),
              new google.maps.Point(0,0),
              new google.maps.Point(30, 76)
            );

            var marker = new google.maps.Marker({
              position: latLng,
              map: map,
              icon: image
            });
          });

        })();
      </script>
    </div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
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
			
			
      <!--<h1><?php echo $heading_title; ?></h1>
      <?php if ($kuler->getSkinOption('show_custom_information')) { ?>
        <div class="custom">
          <?php echo $kuler->translate($kuler->getSkinOption('custom_information')); ?>
        </div>
      <?php } ?>
      <h3><?php echo $text_location; ?></h3>
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <?php if ($image) { ?>
            <div class="col-sm-3"><img src="<?php echo $image; ?>" alt="<?php echo $store; ?>" title="<?php echo $store; ?>" class="img-thumbnail" /></div>
            <?php } ?>
            <div class="col-sm-3"><strong><?php echo $store; ?></strong><br />
              <address>
              <?php echo $address; ?>
              </address>
              <?php if ($geocode) { ?>
              <a href="https://maps.google.com/maps?q=<?php echo urlencode($geocode); ?>&hl=en&t=m&z=15" target="_blank" class="btn btn-info"><i class="fa fa-map-marker"></i> <?php echo $button_map; ?></a>
              <?php } ?>
            </div>
            <div class="col-sm-3"><strong><?php echo $text_telephone; ?></strong><br>
              <?php echo $telephone; ?><br />
              <br />
              <?php if ($fax) { ?>
              <strong><?php echo $text_fax; ?></strong><br>
              <?php echo $fax; ?>
              <?php } ?>
            </div>
            <div class="col-sm-3">
              <?php if ($open) { ?>
              <strong><?php echo $text_open; ?></strong><br />
              <?php echo $open; ?><br />
              <br />
              <?php } ?>
              <?php if ($comment) { ?>
              <strong><?php echo $text_comment; ?></strong><br />
              <?php echo $comment; ?>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
      <?php if ($locations) { ?>
      <h3><?php echo $text_store; ?></h3>
      <div class="panel-group" id="accordion">
        <?php foreach ($locations as $location) { ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title"><a href="#collapse-location<?php echo $location['location_id']; ?>" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"><?php echo $location['name']; ?> <i class="fa fa-caret-down"></i></a></h4>
          </div>
          <div class="panel-collapse collapse" id="collapse-location<?php echo $location['location_id']; ?>">
            <div class="panel-body">
              <div class="row">
                <?php if ($location['image']) { ?>
                <div class="col-sm-3"><img src="<?php echo $location['image']; ?>" alt="<?php echo $location['name']; ?>" title="<?php echo $location['name']; ?>" class="img-thumbnail" /></div>
                <?php } ?>
                <div class="col-sm-3"><strong><?php echo $location['name']; ?></strong><br />
                  <address>
                  <?php echo $location['address']; ?>
                  </address>
                  <?php if ($location['geocode']) { ?>
                  <a href="https://maps.google.com/maps?q=<?php echo urlencode($location['geocode']); ?>&hl=en&t=m&z=15" target="_blank" class="btn btn-info"><i class="fa fa-map-marker"></i> <?php echo $button_map; ?></a>
                  <?php } ?>
                </div>
                <div class="col-sm-3"> <strong><?php echo $text_telephone; ?></strong><br>
                  <?php echo $location['telephone']; ?><br />
                  <br />
                  <?php if ($location['fax']) { ?>
                  <strong><?php echo $text_fax; ?></strong><br>
                  <?php echo $location['fax']; ?>
                  <?php } ?>
                </div>
                <div class="col-sm-3">
                  <?php if ($location['open']) { ?>
                  <strong><?php echo $text_open; ?></strong><br />
                  <?php echo $location['open']; ?><br />
                  <br />
                  <?php } ?>
                  <?php if ($location['comment']) { ?>
                  <strong><?php echo $text_comment; ?></strong><br />
                  <?php echo $location['comment']; ?>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <?php } ?>-->
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
          <h3><?php echo $text_contact; ?></h3>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" id="input-name" class="form-control col-lg-6 col-md-6 col-sm-12 col-xs-12" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
            <div class="col-sm-10">
              <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control col-lg-6 col-md-6 col-sm-12 col-xs-12" />
              <?php if ($error_email) { ?>
              <div class="text-danger"><?php echo $error_email; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-enquiry"><?php echo $entry_enquiry; ?></label>
            <div class="col-sm-10">
              <textarea name="enquiry" rows="10" id="input-enquiry" class="form-control col-lg-6 col-md-6 col-sm-12 col-xs-12"><?php echo $enquiry; ?></textarea>
              <?php if ($error_enquiry) { ?>
              <div class="text-danger"><?php echo $error_enquiry; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-captcha"><?php echo $entry_captcha; ?></label>
            <div class="col-sm-10">
              <input type="text" name="captcha" id="input-captcha" class="form-control  col-lg-6 col-md-6 col-sm-12 col-xs-12" />
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-10 pull-right">
              <img src="index.php?route=tool/captcha" alt="" />
              <?php if ($error_captcha) { ?>
                <div class="text-danger"><?php echo $error_captcha; ?></div>
              <?php } ?>
            </div>
          </div>
        </fieldset>
        <div class="buttons">
          <div class="pull-right">
            <input class="btn btn-primary" type="submit" value="<?php echo $button_submit; ?>" />
          </div>
        </div>
      </form>
      <?php echo $content_bottom; ?></div>
      <style>
.newsletter--parallax.parallax-banner{margin-top: 50px!important;}
</style>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>