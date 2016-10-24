<?php if( $smk_error_warning ) { ?>
	<div class="alert alert-danger">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>
		<?php echo $smk_error_warning; ?>
	</div>
<?php } ?>

<?php if( $success ) { ?>
	<div class="alert alert-success">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>
		<?php echo $success; ?>
	</div>
<?php } ?>
		
<?php if( $is_installed ) { ?>			
	<?php echo $results; ?>
<?php } else { ?>
	<span><?php echo $smk_error_warning; ?></span>
<?php } ?>