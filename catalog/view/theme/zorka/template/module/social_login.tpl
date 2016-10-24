<div class="list-group">

<?php if (!$logged) { ?>
	   <?php if($facebook_login_url!='') { ?>
	   <a style="border:none; text-align:center; background: none;" href="<?php echo $facebook_login_url;?>" class="list-group-item"><img src="image/login_with_facebook.jpg" align="Login with facebook" /></a>
	   <?php } ?>
	 <?php } else { ?>
	  <p>Welcome <?php echo $first_name;?>,</p>
	 
	  <a href="<?php echo $account; ?>" class="list-group-item"><?php echo $text_account; ?></a>
	  <a href="<?php echo $edit; ?>" class="list-group-item"><?php echo $text_edit; ?></a>
	  <a href="<?php echo $password; ?>" class="list-group-item"><?php echo $text_password; ?></a>
	  <a href="<?php echo $logout; ?>" class="list-group-item"><?php echo $text_logout; ?></a>
	
	 <?php } ?>
</div>
