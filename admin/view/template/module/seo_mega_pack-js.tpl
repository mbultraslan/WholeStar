<script type="text/javascript">
	$ = jQuery = $.noConflict(true);
</script>
<script type="text/javascript" src="view/smp/js/jquery.min.js"></script>

<script type="text/javascript">
	var $$			= $.noConflict(true),
		$jQuery		= $$,
		SMP_LOADER	= '<img src="view/smp/img/loading.gif" alt="" />',
		SMP_CANCEL	= false,
		SMP			= {
			_loader	: null,
			
			_progress: null,
			
			// Loader //////////////////////////////////////////////////////////
			
			openLoader: function() {
				if( this._loader != null )
					return;
				
				this._loader = {
					body	: $('<div>')
						.css({
							'position'	: 'fixed',
							'left'		: '0',
							'right'		: '0',
							'top'		: '0',
							'bottom'	: '0',
							'z-index'	: '1040'
						})
						.append($('<div>')
							.css({
								'position'		: 'relative',
								'z-index'		: '1050',
								'margin'		: '0 auto',
								'top'			: '100px',
								'background'	: 'rgba(255,255,255,0.5)',
								'text-align'	: 'center',
								'padding'		: '10px',
								'border-radius'	: '5px',
								'width'			: '80px'
							})
							.append( SMP_LOADER )
						)
						.appendTo( $('body') ),
					bg		: $('<div>')
						.addClass( 'modal-backdrop fade in' )
						.appendTo( $('body') )
				};
			},
			
			closeLoader: function() {
				if( this._loader == null )
					return;
				
				this._loader.body.remove();
				this._loader.bg.remove();
				this._loader = null;
			},
			
			// Progress ////////////////////////////////////////////////////////
			
			progress: function( percent, text ) {
				var self = this;
			
				percent = Math.round( percent );
				
				if( percent > 100 )
					percent = 100;
				
				function textFormat( text ) {
					return text ? '<span style="border-radius: 3px; padding: 3px 7px; display: inline-block; background: rgba(255,255,255,0.7)">' + text + '</span>' : '';
				}
			
				if( self._progress == null ) {
					self._progress = {
						body	: $('<div>')
							.addClass( 'seo-mega-pack' )
							.css({
								'position'	: 'fixed',
								'left'		: '0',
								'right'		: '0',
								'top'		: '0',
								'bottom'	: '0',
								'z-index'	: '1040'
							})
							.append($('<div>')
								.css({
									'position'		: 'relative',
									'z-index'		: '1050',
									'margin'		: '0 auto',
									'top'			: '100px',
									'background'	: 'rgba(255,255,255,0.5)',
									'text-align'	: 'center',
									'padding'		: '10px',
									'border-radius'	: '5px',
									'width'			: '600px',
									'min-height'	: '40px',
									'overflow'		: 'hidden'
								})
								.append($('<div>')
									.addClass( 'text-center text-container' )
								)
								.append($('<div>')
									.addClass( 'progress' )
									.css({
										'margin'	: '5px'
									})
									.append($('<div>')
										.attr({
											'class'			: 'progress-bar',
											'role'			: 'progressbar',
											'aria-valuenow'	: '0',
											'aria-valuemin'	: '0',
											'aria-valuemax'	: '100'
										})
										.css( 'width', '0' )
										.append($('<span>')
											.addClass( 'sr-only' )
											.css({
												'width'			: 'auto',
												'height'		: 'auto',
												'margin-top'	: '1px',
												'clip'			: 'auto'
											})
											.text( '0%' )
										)
									)
								)
								.append($('<div>')
									.addClass( 'text-right' )
									.append($('<a>')
										.addClass( 'btn btn-xs btn-danger' )
										.css( 'margin-right', '5px' )
										.append( '<i class="glyphicon glyphicon-remove"></i> ' )
										.append( '<?php echo $button_cancel; ?>' )
										.click(function(){
											SMP_CANCEL = true;
											
											self._progress.body.find('.text-container').html( textFormat( '<?php echo $text_please_wait; ?>' ) );
											
											return;
										})
									)
								)
							)
							.appendTo( $('body') ),
						bg		: $('<div>')
							.addClass( 'modal-backdrop fade in' )
							.appendTo( $('body') )
					};
				}
					
				self._progress.body.find('.text-container').html( textFormat( text ) );
				
				self._progress.body.find('.progress-bar').attr({
					'aria-valuenow'	: percent
				}).css('width', percent+'%').find('.sr-only').text( percent + '%' );
			},
			
			closeProgress: function() {
				if( this._progress == null )
					return;
				
				this._progress.body.remove();
				this._progress.bg.remove();
				this._progress = null;
			}
		};
</script>

<script type="text/javascript" src="view/smp/js/bootstrap.js"></script>
<script type="text/javascript" src="view/smp/js/bootbox.js"></script>
<script type="text/javascript" src="view/smp/js/jquery.form.js"></script>
<script type="text/javascript" src="view/smp/js/jquery.caret.js"></script>

<script type="text/javascript">
	$$().ready(function(){
		$$('.nav-tabs').each(function(){
			if( ! $$(this).find( '> li.active' ).length ) {
				$$(this).find( '> li:first > a' ).tab( 'show' );
			}
		});
		
		$$('.seo-mega-pack [data-toggle=tooltip]').tooltip();
		
		$$('.seo-mega-pack [data-toggle=dropdown]').unbind('click').dropdown();
	});
		
	$().ready(function(){
		$('#header [data-toggle="dropdown"]').dropdown();
	});
</script>