<?php require DIR_TEMPLATE . 'module/seo_mega_pack-header.tpl'; ?>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-js.tpl'; ?>

<script type="text/javascript">
	var seoMegaPack_variableTags = {};
</script>
				<?php if( $is_installed ) { ?>

					
					<div class="tab-content">
						<div class="tab-pane active">
							<br />
							<a id="generate-sitemap" class="btn btn-sm btn-primary pull-right"><i class="glyphicon glyphicon-fire"></i> <?php echo $button_generate; ?></a>
							<br />
							<table class="table table-tbody">
								<tbody>
									<?php if( empty( $sitemaps ) ) { ?>
										<tr>
											<td><center><?php echo $text_list_is_empty; ?></center></td>
										</tr>
									<?php } else { ?>
										<?php foreach( $sitemaps as $sitemap ) { ?>
											<?php $isIndex = strpos( $sitemap['name'], '.sitemap.index.' ) !== false; ?>
											<tr>
												<td>
													<a href="<?php echo $sitemap['link']; ?>" target="_blank">
														<?php echo sprintf( $isIndex ? '<b>%s</b>' : '%s', $sitemap['link'] ); ?>
													</a>
													<?php if( $isIndex ) { ?>
														<div style="float: right">
															More info: <a href="https://support.google.com/webmasters/answer/71453?hl=en" target="_blank">https://support.google.com/webmasters/answer/71453?hl=en</a>
														</div>
													<?php } ?>
												</td>
											</tr>
										<?php } ?>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				<?php } else { ?>
					<br /><br /><center><?php echo $smk_error_warning; ?></center>
				<?php } ?>

<script type="text/javascript">
	$$('#generate-sitemap').click(function(){
		SMP.progress(0, '<?php echo $text_loading; ?>');
		
		(function run(){
			$.get( '<?php echo $createSitemap; ?>', {}, function( response ){
				response = response.split('||');
				
				if( $.trim( response[0] ) == '1' ) {
					SMP.progress(100, '<b><?php echo $text_data_generation_complete; ?></b>');
					
					setTimeout(function(){
						window.location.reload();
					}, 2000);
				} else {
					var url = 'view/image/flags/' + response[2];
					
					<?php if( version_compare( VERSION, '2.2.0.0', '>=' ) ) { ?>
						url = 'language/' + response[6] + '/' + response[6] + '.png';
					<?php } ?>
					
					response[4] = parseInt( response[4] );
					
					SMP.progress(
						response[4],
						'<img src="' + url + '" /> ' + response[3] + ' - ' + response[1] + ' - ' + response[5]
					);
						
					if( response[4] == 100 ) {
						setTimeout(function(){
							SMP.progress(0, '<?php echo $text_loading; ?>');
							
							run();
						}, 1000);
					} else {
						run();
					}
				}
			});
		})();
	});
</script>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-footer.tpl'; ?>