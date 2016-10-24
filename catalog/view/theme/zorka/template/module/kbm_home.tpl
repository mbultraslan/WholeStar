<?php echo $header; ?>
	<div class="container">
		<ul class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
			<?php } ?>
		</ul>
		<div class="row">
			<!--<?php echo $column_left; ?>
			<?php if ($column_left && $column_right) { ?>
				<?php $class = 'col-lg-6 col-md-6'; ?>
			<?php } elseif ($column_left || $column_right) { ?>
				<?php $class = 'col-lg-9 col-md-9'; ?>
			<?php } else { ?>
				<?php $class = 'col-lg-12 col-md-12'; ?>
			<?php } ?>-->
			<div id="content" class="<?php echo $class; ?>">
				<?php echo $content_top; ?>
				<?php if ($show_title) { ?>
					<h1 class="row-heading">
						<?php echo $heading_title; ?>
					</h1>
				<?php } ?>
				<!--<?php if ($feed) { ?>
					<a class="blog-feed" href="<?php echo $feed_url; ?>"><img src="catalog/view/kulercore/images/kbm_feed.png" /></a>
				<?php } ?>-->
				<div class="box">
					<?php if ($blog_description) { ?>
						<p class="blog-description"> <?php echo $blog_description; ?> </p>
					<?php } ?>
					<?php if ($articles) { ?>
						<div class="row article-list clearafter">
							<?php foreach ($articles as $article) { ?>
								<?php if ($column==2) { ?>
									<?php $class = 'col-lg-6 col-md-6'; ?>
								<?php } elseif ($column==3) { ?>
									<?php $class = 'col-lg-4 col-md-4'; ?>
								<?php } elseif ($column==4) { ?>
									<?php $class = 'col-lg-3 col-md-3'; ?>
								<?php } else { ?>
									<?php $class = 'col-lg-12 col-md-12'; ?>
								<?php } ?>
								<div class="article <?php echo $class; ?>">
									<div class="row">
										<div class="col-lg-4 col-sm-6 col-xs-12">
											<div class="article__image">
												<a href="<?php echo $article['link']; ?>" class="article-image">
													<img src="<?php echo $article['featured_image_thumb']; ?>" />
												</a>
											</div>
										</div>
										<div class="col-lg-8 col-sm-6 col-xs-12">
											<div class="article__header">
												<h2><a href="<?php echo $article['link']; ?>"><?php echo $article['name']; ?></a></h2>
												<div class="article__extra-info">
													<!--<?php if ($article['display_author']) { ?>
														<?php echo '<span class="article__extra-info__author vcard">'; echo _t('text_by_x', '<a rel="author">'. $article['author_name'] .'</a></span>'); ?>
													<?php } ?>-->

													<?php if ($article['display_category'] && $article['categories']) { ?>
														<?php echo '<span class="article__extra-info__category">'; ?>
														<?php echo _t('text_in'); ?>
														<?php $article_links = array(); ?>
														<?php foreach ($article['categories'] as $article_category) {
															$article_links[] = sprintf('<a href="%s">%s</a>', $article_category['link'], $article_category['name']);
														} ?>
														<?php echo implode(', ', $article_links); ?>
														<?php echo '</span>'; ?>
													<?php } ?>

													<?php if ($article['display_date']) { ?>
														<span class="article__extra-info__date"><?php echo _t('text_on'); ?><time><?php echo $article['date_added_formatted'];?></time></span>
													<?php } ?>

													<?php if ($article['comment_total'] && $article['display_comment']) { ?>
														<span class="article__extra-info__comment-total"><a href="<?php echo $article['link']; ?>#comments"><?php echo _t('text_x_comments', $article['comment_total']); ?></a></span>
													<?php } ?>
												</div>
											</div>
											<div class="article__content">
												<div class="article__description">
													<?php echo $article['description']; ?>
												</div>
												<div class="article__read-more">
													<a class="read-more btn" href="<?php echo $article['link']; ?>">
														<span><?php echo _t('text_read_more'); ?></span>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
						<div class="row">
							<div class="col-xs-12 text-center">
								<?php echo $pagination; ?>
							</div>
						</div>
					<?php } else { ?>
						<p><?php echo _t('text_no_articles'); ?></p>
					<?php } ?>
				</div>
			</div>
			<?php echo $content_bottom; ?>
			<?php echo $column_right; ?>
		</div>
	</div>
<?php echo $footer; ?>