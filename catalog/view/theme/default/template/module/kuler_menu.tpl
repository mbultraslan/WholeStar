<?php $kuler = Kuler::getInstance(); ?>
<nav id="megamenu" class="navigation navigation--mega">
  <div class="container">
	  <span id="btn-mobile-toggle">
		  <?php echo $kuler->translate($kuler->getSkinOption('mobile_menu_title')); ?>
		</span>
    <ul class="main-nav">
      <li class="main-nav__item<?php if (!$route || $route == 'common/home') echo ' is-active'; ?>">
	      <a href="<?php echo $menu_ctrl->url->link('common/home'); ?>">
		      <?php echo $__['text_home']; ?>
	      </a>
      </li>
      <?php foreach ($items as $item_index => $item) { ?>
        <li class="main-nav__item<?php if (count($item['children'])) echo ' is-parent' ?> main-nav__item--<?php echo $item['type'] ?>">
          <a <?php if ($item['enable_hyperlink'] && $item['href']) echo 'href="' . $item['href'] . '"'; ?>
	          <?php if ($item['enable_hyperlink'] && $item['href'] && $item['new_tab']) echo ' target="_blank"'; ?>>
	          <?php echo $item['title']; ?>
          </a>
          <?php if (!empty($item['children'])) { ?>
	          <span class="btn-expand-menu"></span>
          <?php } ?>
          <?php if (!empty($item['children'])) { ?>
            <div class="main-nav__secondary-nav">
              <div class="container">
              <?php if ($item['type'] == 'custom') { ?>
                <ul>
                  <?php foreach ($item['children'] as $item2) { ?>
	                  <li>
		                  <a href="<?php echo $item2['href']; ?>"<?php if ($item['sub_new_tab']) echo ' target="_blank"'; ?>>
			                  <?php echo $item2['title']; ?>
		                  </a>
	                  </li>
                  <?php } ?>
                </ul>
              <?php } else if ($item['type'] == 'product') { ?>
                <div class="row">
                  <?php foreach ($item['children'] as $item2) { ?>
                    <?php echo $menu_ctrl->loadChromeTemplate($item, $item2); ?>
                  <?php } ?>
                </div>
              <?php } else if ($item['type'] == 'category') { ?>
                <ul class="row">
                  <?php foreach ($item['children'] as $item2) { ?>
                    <li class="col-md-3 <?php if (in_array($item2['category_id'], $paths)) echo ' active'; ?>">
                      <a href="<?php echo $item2['href']; ?>"
                         class="menu-category-title<?php if (in_array($item2['category_id'], $paths)) echo ' current'; ?>">
	                      <?php echo $item2['title']; ?>
                      </a>
                      <?php if ($item['description'] && $item2['description'] != '..') { ?>
                        <p class="menu-category-description"><?php echo $item2['description']; ?></p>
                      <?php } ?>
                      <?php if ($item['image']) { ?>
                        <img class="menu-category-image <?php echo $item['image_position']; ?>"
                             src="<?php echo $item2['thumb']; ?>" alt="<?php echo $item2['title']; ?>"/>
                      <?php } ?>
                      <?php if (!empty($item2['children'])) { ?>
                        <?php $subcat_offset = $item['category_image_width'] + 10; ?>
                        <ul class="subcat"<?php if ($item['image_position'] == 'float-left') echo " style=\"margin-left: {$subcat_offset}px\""; ?>
	                        <?php if ($item['image_position'] == 'float-right') echo " style=\"margin-right: {$subcat_offset}px\""; ?>>
                          <?php foreach ($item2['children'] as $item3) { ?>
                            <li>
	                            <a href="<?php echo $item3['href']; ?>">
		                            <?php echo $item3['title']; ?>
	                            </a>
                            </li>
                          <?php } ?>
                        </ul>
                      <?php } ?>
                    </li>
                  <?php } ?>
                </ul>
              <?php } else if ($item['type'] == 'html') { ?>
                <div class="dropdown">
                  <?php echo $item['html']; ?>
                </div>
              <?php } ?>
            </div>
            </div>
          <?php } ?>
        </li>
      <?php } ?>
    </ul>
  </div>
</nav>
<script>
	$(function () {
		var href = document.location.toString();

		$('.mainmenu a').each(function () {
			if (this.href && href == this.href) {
				$(this).parents('li').addClass('active');

				$('.mainmenu > li:eq(0)').removeClass('active');
			}
		});

		if (!$('.mainmenu .active').length) {
			$('.mainmenu > li:eq(0)').addClass('active');
		}
	});
</script>