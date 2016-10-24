<?php require DIR_TEMPLATE . 'module/seo_mega_pack-header.tpl'; ?>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-js.tpl'; ?>

<script type="text/javascript">
	var seoMegaPack_variableTags = {};
</script>

<?php if( $is_installed ) { ?>	
	<div class="tab-content">
		<div class="tab-pane active">
			<br />
			<ul class="nav nav-tabs">
				<li><a href="#about" data-toggle="tab">SEO Mega Kit</a></li>
				<?php if( ! empty( $integrations['data'] ) ) { ?>
					<li><a href="#integrations" data-toggle="tab"><?php echo $text_integrations; ?><?php echo empty( $integrations['data']['integrations'] ) ? '' : ' (' . count( $integrations['data']['integrations'] ) . ')'; ?></a></li>
				<?php } ?>
			</ul>
			
			<div class="tab-content">
				<div class="tab-pane active" id="about">
					<?php if( ! empty( $download_new_version_of_smk ) ) { ?>
						<br />
						<div class="alert alert-info">
							<button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>
							<?php echo $download_new_version_of_smk; ?>
						</div>
					<?php } ?>
					<div class="col-md-6">
						<table class="table">
							<thead>
								<tr>
									<td width="200"><?php echo $entry_ext_version; ?></td>
									<td><?php echo $smp_version; ?></td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo $entry_author; ?></td>
									<td><?php echo $smp_author; ?></td>
								</tr>
								<tr>
									<td><?php echo $entry_support; ?></td>
									<td><?php echo $smp_support; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-6">
						<br />
						<ul class="nav nav-tabs">
							<li><a href="#info" data-toggle="tab"><?php echo $text_info_about_your_version; ?></a></li>
							<?php if( ! empty( $versions['data']['versions'] ) ) { ?>
								<li><a href="#changelog" data-toggle="tab"><?php echo $text_changelog; ?></a></li>
							<?php } ?>
						</ul>
						<div class="tab-content">
							<div class="tab-pane" id="info" style="padding-top: 10px;">
								<?php echo $versions['data']['changelog'] ? nl2br( $versions['data']['changelog'] ) : '-'; ?>
							</div>
							<?php if( ! empty( $versions['data']['versions'] ) ) { ?>
								<div class="tab-pane" id="changelog">
									<div class="col-xs-2" style="padding-top: 10px;">
										<ul class="nav nav-tabs tabs-left">
											<?php foreach( $versions['data']['versions'] as $version => $changelog ) { ?>
												<li><a href="#v-<?php echo md5( $version ); ?>" data-toggle="tab"><?php echo $version; ?></a></li>
											<?php } ?>
										<ul>
									</div>

									<div class="col-xs-10" style="padding-top: 10px;">
										<div class="tab-content">
											<?php foreach( $versions['data']['versions'] as $version => $changelog ) { ?>
												<div class="tab-pane" id="v-<?php echo md5( $version ); ?>"><?php echo nl2br( $changelog ); ?></div>
											<?php } ?>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				
				<?php if( ! empty( $integrations['data'] ) ) { ?>
					<div class="tab-pane" id="integrations">
						<br />
						<button type="button" class="btn btn-xs btn-primary pull-right" id="check_which_extensions_you_need_to_install">
							<?php echo $text_check_which_extensions_you_need_to_install; ?>
							<i class="glyphicon glyphicon-question-sign"></i>
						</button>
						<br /><br />
						<table class="table">
							<thead>
								<tr>
									<th width="10"></th>
									<th width="300"><?php echo $text_name; ?></th>
									<th width="100" class="text-center"><?php echo $text_version; ?></th>
									<th><?php echo $text_description; ?></th>
									<th width="100" class="text-center"><?php echo $text_price; ?></th>
									<th width="100" class="text-center"><?php echo $text_installed; ?></th>
									<th width="180" class="text-center"><?php echo $text_i_need_it; ?></th>
								</tr>
							</thead>
							<?php if( empty( $integrations['data']['integrations'] ) ) { ?>
								<tbody><tr><td colspan="7" class="text-center"><?php echo $text_empty_list; ?></td></tr></tbody>
							<?php } else { ?>
								<tbody>
									<?php foreach( $integrations['data']['integrations'] as $integration ) { ?>
										<tr data-codename="<?php echo $integration['codename']; ?>">
											<td width="10"><input type="checkbox" name="integrations[]" value="<?php echo $integration['codename']; ?>" data-price="<?php echo (float) $integration['price']; ?>" /></td>
											<td><?php echo $integration['name']; ?></td>
											<td class="text-center"><?php echo $integration['version']; ?></td>
											<td><?php echo $integration['description'] ? nl2br( $integration['description'] ) : '-'; ?></td>
											<td class="text-center"><?php echo $integration['price'] ? '$'.$integration['price'] : $text_free; ?></td>
											<td class="text-center"><?php echo in_array( $integration['codename'], $integrations_installed ) ? $text_yes : $text_no; ?></td>
											<td class="text-center i-need-install-it"><i class="glyphicon glyphicon-question-sign"></i></td>
										</tr>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="7">
											<?php echo $text_please_select_integrations; ?>
										</td>
									</tr>
								</tfoot>
							<?php } ?>
						</table>
						
						<div id="request-form" style="display: none;">
							<div class="col-sm-5">
								<h3><?php echo $text_request_form; ?></h3>
								<form class="form-horizontal">
									<div class="form-group">
										<label class="col-sm-3 control-label"><?php echo $text_order_id; ?>:*</label>
										<div class="col-sm-3">
											<input class="form-control" type="text" id="req-order_id" value="" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label"><?php echo $text_total_price; ?>:</label>
										<div class="col-sm-3">
											<div class="input-group">
												<div class="input-group-addon">$</div>
												<input class="form-control" type="text" id="req-total_price" value="" readonly="readonly" />
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label"><?php echo $text_your_email; ?>:*</label>
										<div class="col-sm-9">
											<input class="form-control" type="text" id="req-email" value="<?php echo $config_email; ?>" />
										</div>
									</div>
									<div class="form-group text-center" id="if-price" style="display: none;">
										<?php echo $text_before_send_request; ?>
									</div>
									<button type="submit" class="btn btn-primary pull-right" id="request-button"><?php echo $text_download_buy; ?></button>
								</form>
							</div>
							<div class="col-sm-7" id="response-message"></div>
						</div>
					</div>
				
					<script type="text/javascript">
						$$('input[name="integrations[]"]').change(function(){
							var price = 0,
								count = 0;
							
							$$('input[name="integrations[]"]:checked').each(function(){
								price += parseFloat( $$(this).attr('data-price') );
								count++;
							});
							
							$$('#req-total_price').val( price );
							
							$$('#if-price')[price?'show':'hide']();
							$$('#request-form')[count?'show':'hide']();
						});
						
						(function(){
							var locked = false;
							
							$$('#request-button').click(function(){
								if( locked ) return false;
								
								var order_id = $$('#req-order_id').val(),
									email = $$('#req-email').val(),
									integrations = [],
									$btn = $$(this);

								if( ! order_id ) {
									alert( '<?php echo $text_order_id_is_required; ?>' );
								} else if( ! email ) {
									alert( '<?php echo $text_email_is_required; ?>' );
								} else {
									$btn.attr('disabled','disabled').text('<?php echo $text_please_wait; ?>');
									
									locked = true;
									
									$$('input[name="integrations[]"]:checked').each(function(){
										integrations.push( $$(this).val() );
									});

									$$.post( '<?php echo $action_request; ?>'.replace(/&amp;/g, '&'), {
										'order_id' : order_id,
										'email' : email,
										'integrations' : integrations
									}, function( response ){
										locked = false;
										
										$btn.removeAttr('disabled').text('<?php echo $text_download_buy; ?>');
										
										$('#response-message').html( response );
									});
								}

								return false;
							});
						})();
						
						$$('#check_which_extensions_you_need_to_install').click(function(){
							var integrations = (function(){
									var arr = [];

									$$('tr[data-codename]').each(function(){
										arr.push( $$(this).attr('data-codename') );
									});

									return arr;
								})(),
							self = $$(this).attr('disabled',true);
							
							function ajax() {
								var codename = integrations.pop(),
									$tr = $$('tr[data-codename="' + codename + '"]'),
									$td = $tr.find('.i-need-install-it').html( SMP_LOADER );
								
								$$.get( '<?php echo $action_need_install; ?>&codename='.replace( /&amp;/g, '&' ) + codename, {}, function( response ){
									var data = $$.parseJSON( response );
									
									if( data.success ) {
										$td.html( data.should_install ? '<?php echo $text_yes; ?>' : '<?php echo $text_no; ?>' );
										
										if( data.should_install ) {
											
										}
										
										if( integrations.length ) {
											ajax();
										} else {
											self.removeAttr('disabled');
										}
									} else {
										self.removeAttr('disabled');
										
										alert( data.alert );
									}
								});
								
								
							}
							
							ajax();
							
							return false;
						});
					</script>
				<?php } ?>
			</div>
		</div>
	</div>
<?php } else { ?>
	<br /><br /><center><?php echo $smk_error_warning; ?></center>
<?php } ?>
					
<?php require DIR_TEMPLATE . 'module/seo_mega_pack-footer.tpl'; ?>