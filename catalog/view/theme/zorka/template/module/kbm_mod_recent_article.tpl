<?php
$kuler = Kuler::getInstance();
$kuler->language->load('kuler/zorka');
$setting['article_limit'] = !empty($setting['article_limit']) ? intval($setting['article_limit']) : 3;

$col = 4;
if (12 % $setting['article_limit']) {
  if ($setting['article_limit'] == 5) {
    $col = 20;
  }
  if ($setting['article_limit'] > 6) {
    $col = 3;
  }
} else {
  $col = 12 / $setting['article_limit'];
}
?>
<div id="kbm-recent-article-<?php echo $module; ?>" class="kbm-recent-article">
  <div class="container">
    <div class="box kuler-module">
      <?php if ($show_title) { ?>
        <div class="box-heading"><span><?php echo $title; ?></span></div>
      <?php } ?>
      <div class="box-content">
        <div class="row articles">
          <?php foreach ($articles as $article) { ?>
            <div class="col-lg-<?php echo $col; ?> col-md-<?php echo $col; ?> col-sm-6 col-xs-12">
              <?php if ($product_featured_image) { ?>
                <div class="articles__image"><a href="<?php echo $article['link']; ?>"><img src="<?php echo $article['featured_image_thumb']; ?>" class="avatar" alt="<?php echo $article['name']; ?>" /></a></div>
              <?php } ?>
              <a href="<?php echo $article['link']; ?>" class="article__title"><?php echo $article['name']; ?></a>
              <?php if ($product_description) { ?>
                <p class="articles__description"><?php echo $article['description']; ?></p>
              <?php } ?>
              <span class="articles__date"><?php echo $article['date_added_formatted']; ?></span>
              <a href="<?php echo $article['link']; ?>" class="articles__read-more"><?php echo $kuler->language->get('text_readmore'); ?></a>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>