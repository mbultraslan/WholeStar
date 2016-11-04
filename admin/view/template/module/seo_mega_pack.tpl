<?php require DIR_TEMPLATE . 'module/seo_mega_pack-header.tpl'; ?>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-js.tpl'; ?>

<script type="text/javascript">
	var seoMegaPack_variableTags = {};
</script>
					
<div class="tab-content">
	<div class="tab-pane active">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<input type="hidden" name="save_parameters" value="1" /><br />
			
			<div class="col-xs-2">
				<ul class="nav nav-tabs tabs-left">
					<?php foreach( $extensions as $group => $items ) { ?>	
						<li><a href="#group-<?php echo $group; ?>" data-toggle="tab"><?php echo ${'tab_group_'.$group}; ?></a></li>
					<?php } ?>
				<ul>
			</div>
			
			<div class="col-xs-10">
				<div class="tab-content">
					<?php foreach( $extensions as $group => $items ) { ?>
						<div class="tab-pane" id="group-<?php echo $group; ?>">
							<table class="table table-hover">
								<thead>
									<tr>
										<td class="text-left" width="300"><h4><?php echo ${'tab_group_'.$group}; ?></h4></td>
										<td class="text-center">																
											<?php if( $group != 'auto' ) { ?>
												<div class="pull-right">
													<a data-group="<?php echo $group; ?>" data-type="generate-all" class="btn btn-primary btn-sm">
														<i class="glyphicon glyphicon-fire"></i>
														<?php echo $button_generate_all; ?>
													</a>
													
													<?php if( $group != 'description' ) { ?>
														<?php if( $group != 'url' ) { ?>
															<div class="btn-group">
																<button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">
																	<i class="glyphicon glyphicon-trash"></i>
																	<?php echo $button_clear_all; ?>
																	<span class="caret"></span>
																</button>
																<ul class="dropdown-menu pull-right" role="menu">
																	<li class="text-left">
																		<a data-auto-generated="1" data-group="<?php echo $group; ?>" data-type="clear-all">
																			<?php echo $button_clear_only_ag; ?>
																		</a>
																	</li>
																	<li class="text-left">
																		<a data-auto-generated="0" data-group="<?php echo $group; ?>" data-type="clear-all">
																			<?php echo $button_clear_all; ?>
																		</a>
																	</li>
																</ul>
															</div>
														<?php } else { ?>
															<a data-group="<?php echo $group; ?>" data-type="clear-all" class="btn btn-danger btn-sm">
																<i class="glyphicon glyphicon-trash"></i>
																<?php echo $button_clear_all; ?>
															</a>
														<?php } ?>
													<?php } ?>
													
													<a data-config="all" data-group="<?php echo $group; ?>"  class="btn btn-warning btn-sm">
														<i class="glyphicon glyphicon-cog"></i>
													</a>
												</div>
											<?php } ?>
										</td>
									</tr>
								</thead>
								<tbody>
									<?php foreach( $items as $item ) { ?>
										<?php $hasAction = $item->hasAction(); ?>
										<tr>
											<td class="text-left"><?php echo $item->icon(); ?><strong><?php echo $item->title(); ?></strong></td>
											<td class="text-left" style="color: #565656"<?php if( ! $hasAction ) { ?> colspan="2"<?php } ?>>
												<div class="<?php if( $group != 'auto' ) { ?>hide <?php } ?>ext-description" data-group="<?php echo $group; ?>">
													<?php echo $item->description(); ?>

													<?php if( $item->hasDefaultSetParams() && strpos( get_class( $item ), 'SeoUrlsGenerator' ) === false && $item->name() != 'auto_generator' ) { ?>
														<?php if( $item->description() ) { ?>
															<br /><br />
														<?php } ?>
															
														<?php if( strpos( get_class( $item ), 'Related' ) === false ) { ?>
															<?php $klang = 0; foreach ($languages as $key => $language) { ?>
																<?php

																	$flag = version_compare(VERSION, '2.2.0.0', '>=') ? 'language/' . $language['code'] . '/' . $language['code'] . '.png' : 'view/image/flags/' . $language['image'];

																?>
																<?php if( $item->tags() ) { ?>
																	<?php echo $item->printTags( '', array(), '-' . $language['language_id'] ); ?>
																<?php } ?>
																<div class="input-group">
																	<input type="text" class="form-control" value="<?php echo $item->getParams( $language['language_id'] ); ?>" name="extensions[<?php echo $item->name(); ?>][<?php echo $language['language_id']; ?>]" data-name="<?php echo $item->name(); ?>-<?php echo $language['language_id']; ?>" />
																	<div class="input-group-addon">
																		<img src="<?php echo $flag; ?>" title="<?php echo $language['name']; ?>" />
																	</div>
																</div>
																<?php if( $klang < count( $languages ) - 1 ) { ?>
																	<hr />
																<?php } ?>
															<?php $klang++; } ?>
														<?php } else { ?>
																<?php if( $item->tags() ) { ?>
																	<?php echo $item->printTags( '', array() ); ?>
																<?php } ?>
																<input type="text" class="form-control" value="<?php echo $item->getParams(); ?>" name="extensions[<?php echo $item->name(); ?>]" data-name="<?php echo $item->name(); ?>" />
														<?php } ?>
													<?php } ?>

													<?php if( $item->tags() ) { ?>
														<script type="text/javascript">
															seoMegaPack_variableTags['<?php echo $item->name(); ?>'] = '<?php echo $item->tags(); ?>'.split(',');
														</script>
													<?php } ?>

													<br />
												</div>

												<?php if( $group != 'auto' ) { ?>
													<button data-group="<?php echo $group; ?>" data-config="item" type="button" class="btn btn-warning btn-sm pull-right">
														<i class="glyphicon glyphicon-cog"></i>
													</button>
												<?php } ?>

												<?php if( $hasAction ) { ?>
													<?php $functions = array( 'generateUrl' => 'generate', 'previewUrl' => 'preview', 'clearUrl' => 'clear' ); ?>

													<?php foreach( $functions as $function => $type ) { ?>
														<?php if( NULL != ( $url = $item->{$function}() ) ) { ?>
															<?php 
																if( ! is_array( $url ) ) 
																	$url = array(''); 

																$className = $type == 'generate' ? 'btn-primary' : ( $type == 'preview' ? 'btn-info' : 'btn-danger' );
															?>

															<?php if( count( $url ) > 1 ) { ?>															
																<div class="btn-group">
																	<button data-mode="" data-title="<?php echo $item->title(); ?>" data-group="<?php echo $group; ?>" data-extension="<?php echo $item->name(); ?>" data-type="<?php echo $type; ?>" type="button" class="btn <?php echo $className; ?> btn-sm">
																		<i class="glyphicon <?php echo $type == 'generate' ? 'glyphicon-fire' : ( $type == 'preview' ? 'glyphicon-eye-open' : 'glyphicon-trash' ); ?>"></i>
																		<?php echo ${'button_' . $type . '_for_all'}; ?>
																	</button>
																	<button type="button" class="btn <?php echo $className; ?> btn-sm dropdown-toggle" data-toggle="dropdown">
																		<span class="caret"></span>
																	</button>
																	<ul class="dropdown-menu" role="menu">
																		<?php foreach( $url as $mode ) { ?>
																			<li>
																				<a data-mode="<?php echo $mode; ?>" data-title="<?php echo $item->title(); ?>" data-group="<?php echo $group; ?>" data-extension="<?php echo $item->name(); ?>" data-type="<?php echo $type; ?>" href="#">
																					<?php echo ${'button_' . $type . ( $mode ? '_' . $mode : '' )}; ?>
																				</a>
																			</li>
																		<?php } ?>
																	</ul>
																</div>
															<?php } else { ?>
																<?php foreach( $url as $mode ) { ?>
																	<?php if( $type == 'preview' || $type == 'generate' || $item->name() == 'seo_images_generator' ) { ?>
																		<button type="button" data-mode="<?php echo $mode; ?>" data-title="<?php echo $item->title(); ?>" data-group="<?php echo $group; ?>" data-extension="<?php echo $item->name(); ?>" data-type="<?php echo $type; ?>" class="btn <?php echo $className; ?> btn-sm">
																			<i class="glyphicon <?php echo $type == 'generate' ? 'glyphicon-fire' : ( $type == 'preview' ? 'glyphicon-eye-open' : 'glyphicon-trash' ); ?>"></i>
																			<?php echo ${'button_' . $type . ( $mode ? '_' . $mode : '' )}; ?>
																		</button>
																	<?php } else { ?>
																		<div class="btn-group">
																			<button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">
																				<i class="glyphicon glyphicon-trash"></i>
																				<?php echo ${'button_' . $type . ( $mode ? '_' . $mode : '' )}; ?>
																				<span class="caret"></span>
																			</button>
																			<ul class="dropdown-menu" role="menu">
																				<li>
																					<a data-auto-generated="1" data-mode="<?php echo $mode; ?>" data-title="<?php echo $item->title(); ?>" data-group="<?php echo $group; ?>" data-extension="<?php echo $item->name(); ?>" data-type="<?php echo $type; ?>">
																						<?php echo $button_clear_only_ag; ?>
																					</a>
																				</li>
																				<li>
																					<a data-auto-generated="0" data-mode="<?php echo $mode; ?>" data-title="<?php echo $item->title(); ?>" data-group="<?php echo $group; ?>" data-extension="<?php echo $item->name(); ?>" data-type="<?php echo $type; ?>">
																						<?php echo $button_clear_all; ?>
																					</a>
																				</li>
																			</ul>
																		</div>
																	<?php } ?>
																<?php } ?>
															<?php } ?>
														<?php } ?>
													<?php } ?>
												<?php } ?>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					<?php } ?>
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
	$$(function() {
		$$('[data-config]').each(function(){
			var group	= $$(this).attr('data-group'),
				config	= $$(this).attr('data-config');
			
			if( config == 'all' ) {
				$$(this).click(function(){
					if( $$(this).hasClass( 'h' ) ) {
						$$('.ext-description:not(.hide)[data-group=' + group + ']').slideUp('normal', function(){
							$$(this).addClass('hide');
						});
					} else {
						$$('.ext-description.hide[data-group=' + group + ']').removeClass('hide').hide().slideDown();
					}

					$$(this).toggleClass( 'h' );
				});
			} else {
				var $item = $$(this).parent().find('.hide');
				
				$$(this).click(function(){
					if( $item.hasClass( 'hide' ) )
						$item.removeClass('hide').hide().slideDown();
					else
						$item.slideUp('normal', function(){
							$$(this).addClass('hide');
						});
				});
			}
		});
		
		$$('input[name^=extensions][data-name]').on('click keyup', function () {
			$$(this).attr('data-caret', $$(this).caret());
		});
		
		$$('button[data-insert-tag]').each(function(){
			var tag		= $$(this).attr('data-insert-tag'),
				t		= tag.replace( '{ '.replace(' ',''), '' ).replace( '}', '' ),
				l		= $$(this).attr('data-language-id'),
				name	= $$(this).attr('data-name');
			
			if( t == 'description' || t == 'sample_products' )
				$$(this).find('i').after(' <i class="glyphicon glyphicon-wrench"></i>');
			
			$$(this).click(function(){
				var input	= $$('[name^=extensions][data-name="' + name + '"]' + ( typeof l != 'undefined' ? '[data-language-id="' + l + '"]' : '' )),
					$div	= null;
					
				function insert( tag ) {
					var val		= input.val(),
						pos		= input.attr('data-caret') ? parseInt( input.attr('data-caret') ) : val.length,
						name	= input.attr('name'),
						str		= val.substring( 0, pos ) + tag + val.substring( pos, val.length ),
						caret	= pos + tag.length;
						
					input.val( str ).trigger('change');
					
					if( typeof l == 'undefined' ) {
						input.trigger('focus').caret( caret ).attr('data-caret', caret);
					} else if( typeof CKEDITOR != 'undefined' && typeof CKEDITOR.instances[name] != 'undefined' ) {
						CKEDITOR.instances[name].setData( CKEDITOR.instances[name].getData() + tag );
					}
				}

				switch( t ) {
					case 'category_description' :
					case 'description' : {
						var $div = $$('<div>')
							.append( 'Total number of sentences: ' )
							.append( '<input type="text" value="1" class="form-control" name="sentences" />' );

						break;
					}
					case 'sample_products' : {
						var $div = $$('<div>')
							.append( 'Total number of products: ' )
							.append( '<input type="text" value="3" class="form-control" name="total" />' )
							.append( '<br />Separator between products: ' )
							.append( '<input type="text" value=", " class="form-control" name="sep" />' );

						break;
					}
				}

				if( $div !== null ) {
					bootbox.dialog({
						'title'		: 'Configuration',
						'message'	: $div,
						'buttons'	: {
							'ok'	: {
								'label'		: 'OK',
								'className'	: 'btn-success',
								'callback'	: function(){
									var params = [];

									$div.find('input,select').each(function(){
										params.push( $$(this).attr('name') + '#' + $$(this).val().replace( /#/g, ' ' ) );
									});

									insert( tag.replace( /\}$/, '#' + params.join( '#' ) + '}' ) );
								}
							}
						}
					});
				} else {
					insert( tag );
				}
			});
		});
	
		$$('button[data-type],a[data-type]').click(function(){
			var self				= $$(this),
				type				= self.attr('data-type'),
				group				= self.attr('data-group'),
				mode				= self.attr('data-mode'),
				onlyAutoGenerated	= self.attr('data-auto-generated'),
				extensions			= [],
				idx					= 0,
				count				= 0,
				lang				= {
					'generate'		: 'Generate',
					'generate-all'	: 'Generate all',
					'preview'		: 'Preview new items',
					'clear'			: 'Clear',
					'clear-all'		: 'Clear all'
				},
				url_generate	= '<?php echo $url_generate; ?>',
				url_clear		= '<?php echo $url_clear; ?>';
				
			if( typeof onlyAutoGenerated == 'undefined' )
				onlyAutoGenerated = '0';
				
			function addExtension( $item ) {
				for( var i = 0; i < extensions.length; i++ ) {
					if( $item.attr('data-extension') == extensions[i].name ) {
						extensions[i].types.push( $item.attr('data-type') );
						
						return;
					}
				}
			
				extensions.push({
					'name'	: $item.attr('data-extension'),
					'title'	: $item.attr('data-title'),
					'types'	: [ $item.attr('data-type') ]
				});
			}
			
			if( type == 'generate-all' || type == 'clear-all' ) {
				$$('[data-type][data-group="' + group + '"][data-extension]').each(function(){
					addExtension( $$(this) );
				});
			} else {
				addExtension( $$(this) );
			}
			
			function run() {
				var $form	= $$('#form'),
					start	= 0,
					limit	= 200,
					names	= [],
					url		= type == 'clear' || type == 'clear-all' ? url_clear : url_generate;
					//modes	= $$('[data-mode!=""][data-group="' + group + '"][data-type="' + type.replace('-all','') + '"][data-extension="' + extensions[idx].name + '"]').length;
					
				//if( modes > 1 ) {
				//	limit = parseInt( limit / modes );
					
				//	if( limit < 1 )
				//		limit = 1;
				//}
				
				for( var i = 0; i < extensions.length; i++ ) {
					for( var j = 0; j < extensions[i].types.length; j++ ) {
						if( extensions[i].types[j] == type || ( type == 'clear-all' && extensions[i].types[j] == 'clear' ) || ( type == 'generate-all' && extensions[i].types[j] == 'generate' ) ) {
							names.push( extensions[i].name );
							
							break;
						}
					}
				}
				
				SMP.openLoader();				
		
				if( typeof CKEDITOR != 'undefined' ) {
					for( var i in CKEDITOR.instances ) {
						(function( name ){
							$$('textarea[name="' + name + '"]').val( CKEDITOR.instances[name].getData() );
						})(i);
					}
				}
				
				$form.ajaxSubmit({
					'url'		: url + 
						( mode ? '&mode=' + mode : '' ) +
						( type == 'preview' ? '&preview=1' : '' ) + 
						( type == 'generate' || type == 'generate-all' ? '&info=1' : '' ) + 
						( type == 'clear' || type == 'clear-all' ? '&onlyAutoGenerated=' + onlyAutoGenerated : '' ) + 
						'&extensions=' + names.join( ',' ),
					'success'	: function( response ) {
						SMP.closeLoader();
				
						function progressTitle() {
							var t = 1,
								m = 0,
								s = '',
								i, j;
								
							for( i = 0; i < idx; i++ )
								for( j = 0; j < items[extensions[i].name].length; j++ )
									if( items[extensions[i].name][j].length )
										t += 1;
							
							for( i in items ) {
								for( j = 0; j < items[i].length; j++ ) {
									if( items[i][j].length ) {
										m += 1;
										
										if( i == extensions[idx].name && j < index )
											t += 1;
									}
								}
							}
							
							s += 'Step: ' + t + '/' + m;

							if( extensions.length > 1 )
								s += ' - ' + extensions[idx].title;

							return s + ( items[extensions[idx].name][index].text ? ' - ' + items[extensions[idx].name][index].text : '' );
						}
						
						var $div	= $$('<div>')
								.html( response ),
							items	= {},
							index	= 0,
							buttons	= {};
						
						$div.find('[data-items]').each(function(){
							var ex = $$(this).attr('data-extension');
							
							if( typeof items[ex] == 'undefined' )
								items[ex] = [];
						
							items[ex].push({ 
								'text'			: $$(this).html(), 
								'mode'			: $$(this).attr('data-mode'),
								'length'		: parseInt( $$(this).attr('data-items') ), 
								'language_id'	: $$(this).attr('data-language-id')
							});
							
							count += parseInt( $$(this).attr('data-items') );
						});
						
						if( type == 'generate' || type == 'generate-all' ) {
							buttons['run'] = {
								'label'		: '<i class="glyphicon glyphicon-fire"></i> <?php echo $button_generate; ?>',
								'className'	: 'btn-primary',
								'callback'	: function(){
									if( typeof extensions[idx] == 'undefined' ) return;
									
									SMP.progress(0, progressTitle());
									
									SMP_CANCEL = false;
										
									(function run() {
										if( SMP_CANCEL ) {
											SMP_CANCEL = false;
											SMP.closeProgress();
											
											return;
										}
									
										var p = start * limit;
										
										if( typeof items[extensions[idx].name][index] != 'undefined' && p >= items[extensions[idx].name][index].length ) {
											SMP.progress(100, progressTitle());
											
											start = p = 0;
											
											index++;
											
											if( index < items[extensions[idx].name].length ) {
												SMP.progress(0, progressTitle());
											}
										}
										
										if( index >= items[extensions[idx].name].length ) {
											idx++;
										
											if( typeof extensions[idx] != 'undefined' && typeof items[extensions[idx].name] != 'undefined' ) {
												index = start = p = 0;
												
												SMP.progress(0, progressTitle());
											} else {
												index--;
												
												SMP.progress(100, '<b><?php echo $text_data_generation_complete; ?></b>');													
													
												setTimeout(function(){
													SMP.closeProgress();
												}, 3000);

												return;
											}
										}
										
										if( items[extensions[idx].name][index].length > 0 ) {
											$.get( url + ( items[extensions[idx].name][index].mode ? '&mode=' + items[extensions[idx].name][index].mode : '' ), {
												'limit'	: limit,
												'language_id' : items[extensions[idx].name][index].language_id,
												'extensions' : extensions[idx].name
											}, function( response ){
												start++;

												p = ( ( start * limit ) / items[extensions[idx].name][index].length ) * 100;

												SMP.progress( p, progressTitle() );

												if( p >= 100 ) {
													setTimeout(function(){ run(); },1000);
												} else {
													run();
												}
											});
										} else {
											start++;
											run();
										}
									})();
								}
							};
						}
						
						buttons['close'] = {
							'label'		: '<i class="glyphicon glyphicon-remove"></i> <?php echo $button_close; ?>',
							'className'	: 'btn-danger'
						};
						
						bootbox.dialog({
							'title'		: lang[type],
							'message'	: $div,
							'className'	: 'modal-1000',
							'buttons'	: buttons
						});
					}
				});
			}
			
			if( type == 'clear' || type == 'clear-all' ) {
				bootbox.confirm( '<?php echo $text_confirm_clear; ?>', function( result ){
					if( result )
						run();
				});
				
				return false;
			}
			
			run();
			
			return false;
		});
	
		function split( val ) {
		  return val.split( / +/ );
		}
		function extractLast( term ) {
		  return split( term ).pop();
		}
		/*
		$('input[name^="extensions"]').each(function(){
			var name = $(this).attr('data-name');
			
			if( typeof seoMegaPack_variableTags[name] == 'undefined' ) return;
			
			$(this)
				.bind('keydown', function(e){
					if( e.keyCode === $.ui.keyCode.TAB && $(this).data('ui-autocomplete').menu.active )
						e.preventDefault();
				})
				.autocomplete({
					minLength: 0,
					source: function( request, response ) {
						response( $.ui.autocomplete.filter(
							seoMegaPack_variableTags[name], extractLast( request.term )
						));
					},
					focus: function() {
						return false;
					},
					select: function( e, ui ) {
						var terms = split( this.value );
						
						terms.pop();
						terms.push( ui.item.value );
						terms.push('');
						
						this.value = terms.join(' ');
						
						return false;
					}
				});
		});*/
  });
</script>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-footer.tpl'; ?>