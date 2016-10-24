<?php require DIR_TEMPLATE . 'module/seo_mega_pack-header.tpl'; ?>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-js.tpl'; ?>

<?php

	$HTTP_URL = defined( 'HTTPS_CATALOG' ) ? HTTPS_CATALOG : ( defined( 'HTTP_CATALOG' ) ? HTTP_CATALOG : 'http://YOUR-STORE-DOMAIN/' );

?>

<script type="text/javascript">
	var seoMegaPack_variableTags = {};
</script>


<?php if( $is_installed ) { ?>					
	<div class="tab-content">
		<div class="tab-pane active">
			<br />
			<div class="bs-callout bs-callout-info">
				<?php echo sprintf( $text_cron_info, $HTTP_URL . 'module/smp_cron\;code\;<span class="secret_code"></span>', $HTTP_URL . 'module/smp_cron\;code\;<span class="secret_code"></span>' ); ?>
			</div>
			<table class="table table-tbody">
				<tbody>
					<tr>
						<td width="220">
							<?php echo $entry_enabled; ?>
						</td>
						<td>
							<input type="checkbox" style="vertical-align: middle; margin-top: -3px;" name="smp_cron[enabled]" value="1" <?php if( ! empty( $cron['enabled'] ) ) { ?>checked="checked"<?php } ?> />
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $entry_secret_code; ?>
						</td>
						<td>
							<input type="text" name="smp_cron[secret_code]" value="<?php echo ! empty( $cron['secret_code'] ) ? $cron['secret_code'] : substr( md5( microtime() ), 0, 5 );  ?>" />
											
							<script type="text/javascript">
								$('input[name="smp_cron[secret_code]"]').bind('change keyup', function(){
									$('.secret_code').text( $(this).val() );
								}).trigger('change');
							</script>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $entry_cron_limit; ?>
						</td>
						<td>
							<input type="text" name="smp_cron[limit]" value="<?php echo isset( $cron['limit'] ) ? (int) $cron['limit'] : 100;  ?>" />
							<span style="margin-bottom:5px" class="help"><?php echo $text_unlimited; ?></span>
							<span class="bg-danger"><?php echo $text_cron_limit_info; ?></div>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $entry_generate_sitemap; ?>
						</td>
						<td>
							<input type="checkbox" style="vertical-align: middle; margin-top: -3px;" name="smp_cron[sitemap]" value="1" <?php if( ! empty( $cron['sitemap'] ) ) { ?>checked="checked"<?php } ?> />
												
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $entry_frequency; ?>	   
										
							<input type="text" name="smp_cron[sitemap_frequency]" value="<?php echo isset( $cron['sitemap_frequency'] ) ? $cron['sitemap_frequency'] : 24;  ?>" />
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $entry_run_generators; ?>
						</td>
						<td>
							<?php echo $entry_frequency; ?>	   
									
							<input type="text" name="smp_cron[auto_generators_frequency]" value="<?php echo isset( $cron['auto_generators_frequency'] ) ? $cron['auto_generators_frequency'] : 24;  ?>" />
										
							<br /><br />
												
							<?php foreach( $extensions as $key => $extension ) { if( in_array( $key, array( 'AutoGenerator', 'SeoImagesGenerator' ) ) ) continue; ?>
								<input 
									type="checkbox" 
									style="vertical-align: middle; margin-top: -3px;" 
									name="smp_cron[auto_generators][]" 
									value="<?php echo $key; ?>" 
									<?php if( ! empty( $cron['auto_generators'] ) && in_array( $key, $cron['auto_generators'] ) ) { ?>checked="checked"<?php } ?> />
													
									<?php echo ${'tab_group_'.$extension->group()}; ?> - <?php echo $extension->title(); ?><br />
							<?php } ?>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td class="text-right" colspan="2">
							<button type="button" id="run-cron" class="btn btn-success"><i class="glyphicon glyphicon-fire"></i> <?php echo $text_test_now; ?></button>
								
							<div id="cron-result" class="text-left"></div>
												
							<script type="text/javascript">
								$('#run-cron').click(function(){
									$('#cron-result').css('margin-top','10px').html( '<pre><?php echo $text_loading; ?></pre>' );
														
									$.get( '<?php echo $HTTP_URL; ?>module/smp_cron;code;' + $('input[name="smp_cron[secret_code]"]').val(), { }, function( result ) {
										$('#cron-result').html( '<pre>' + result + '</pre>' );
									});
														
									return;
								});
							</script>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
<?php } else { ?>
	<br /><br /><center><?php echo $smk_error_warning; ?></center>
<?php } ?>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-footer.tpl'; ?>