<ul class="nav nav-tabs">
	<li<?php if( $tab == 'index' ) { echo ' class="active"'; } ?>><a href="<?php echo $tab_action_extensions; ?>"><i class="glyphicon glyphicon-compressed"></i> <?php echo $tab_extensions; ?></a>
	<li<?php if( $tab == 'manager' ) { echo ' class="active"'; } ?>><a href="<?php echo $tab_action_manager; ?>"><i class="glyphicon glyphicon-align-justify"></i> <?php echo $tab_manager; ?></a>
	<li<?php if( $tab == 'custom' ) { echo ' class="active"'; } ?>><a href="<?php echo $tab_action_custom; ?>"><i class="glyphicon glyphicon-pencil"></i> <?php echo $tab_custom; ?></a>
	<li<?php if( $tab == 'redirects' || $tab == 'invalid_urls' ) { echo ' class="active"'; } ?>><a href="<?php echo $tab_action_redirects; ?>"><i class="glyphicon glyphicon-random"></i> <?php echo $tab_redirects; ?></a>
	<li<?php if( $tab == 'sitemap' ) { echo ' class="active"'; } ?>><a href="<?php echo $tab_action_sitemap; ?>"><i class="glyphicon glyphicon-globe"></i> <?php echo $tab_sitemap; ?></a>
	<li<?php if( $tab == 'extras' ) { echo ' class="active"'; } ?>><a href="<?php echo $tab_action_extras; ?>"><i class="glyphicon glyphicon-star"></i> <?php echo $tab_extras; ?></a>
	<li<?php if( $tab == 'cron' ) { echo ' class="active"'; } ?>><a href="<?php echo $tab_action_cron; ?>"><i class="glyphicon glyphicon-time"></i> <?php echo $tab_cron; ?></a>
	<li<?php if( $tab == 'settings' ) { echo ' class="active"'; } ?>><a href="<?php echo $tab_action_settings; ?>"><i class="glyphicon glyphicon-wrench"></i> <?php echo $tab_settings; ?></a>
	<li<?php if( $tab == 'help' ) { echo ' class="active"'; } ?>><a href="<?php echo $tab_action_help; ?>"><i class="glyphicon glyphicon-question-sign"></i> <?php echo $tab_help; ?></a>
	<li<?php if( $tab == 'about' ) { echo ' class="active"'; } ?>><a href="<?php echo $tab_action_about; ?>"><i class="glyphicon glyphicon-user"></i> <?php echo $tab_about; ?></a>
</ul>