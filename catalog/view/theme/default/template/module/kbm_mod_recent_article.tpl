<div id="kbm-recent-article-<?php echo $module; ?>" class="kbm-recent-article">
    <div class="box kuler-module">
	    <?php if ($show_title) { ?>
        <div class="box-heading"><span><?php echo $title; ?></span></div>
	    <?php } ?>
        <div class="box-content">
            <div class="row articles">
	            <?php foreach ($articles as $article) { ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
	                <?php if ($product_featured_image) { ?>
                        <div class="image"><img src="<?php echo $article['featured_image_thumb']; ?>" class="avatar" alt="<?php echo $article['name']; ?>" /></div>
	                <?php } ?>
                    <a href="<?php echo $article['link']; ?>" class="article-title"><?php echo $article['name']; ?></a>
	                <?php if ($product_description) { ?>
	                    <p><?php echo $article['description']; ?></p>
	                <?php } ?>
                    <span class="date"><?php echo $article['date_added_formatted']; ?></span>
                </div>
	            <?php } ?>
            </div>
        </div>
    </div>
</div>