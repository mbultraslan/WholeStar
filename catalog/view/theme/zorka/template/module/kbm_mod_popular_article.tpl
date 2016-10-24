<div id="kbm-article--popular-<?php echo $module; ?>" class="kbm-articles kbm-articles--popular">
  <div class="box kuler-module">
    <?php if ($show_title) { ?>
      <div class="box-heading"><span><?php echo $title; ?></span></div>
    <?php } ?>
    <div class="box-content">
      <div class="row">
        <?php foreach ($articles as $article) { ?>
          <div class="col-lg-6 col-md-6 article">
            <?php if ($product_featured_image) { ?>
              <div class="article__image">
                <a href="<?php echo $article['link']; ?>" ><img src="<?php echo $article['featured_image_thumb']; ?>" alt="<?php echo $article['name']; ?>" /></a>
              </div>
            <?php } ?>
            <a href="<?php echo $article['link']; ?>" class="article__title">
              <?php echo $article['name']; ?>
            </a>
							<span class="article__date">
                  <?php echo $article['date_added_formatted']; ?>
							</span>
            <?php if ($product_description) { ?>
              <div class="article__description">
                <?php echo $article['description']; ?>
              </div>
            <?php } ?>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>