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

<?php if( ! empty( $available_new_version_of_smk ) ) { ?>
	<div class="alert alert-info" id="available_new_version_of_smk">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>
		<?php echo $available_new_version_of_smk; ?>
	</div>

	<script type="text/javascript">
		$('#available_new_version_of_smk button').click(function(){
			$.get('<?php echo $available_new_version_of_smk_mark_as_readed; ?>'.replace(/&amp;/g,'&'));
		});
	</script>
<?php } ?>
