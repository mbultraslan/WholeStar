<?php echo $header; ?>

<?php require DIR_TEMPLATE . 'module/seo_mega_pack-js.tpl'; ?>

<script type="text/javascript">
	var seoMegaPack_variableTags = {};
</script>

<div id="content">
	<div class="breadcrumb">
		<?php foreach( $breadcrumbs as $breadcrumb ) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	
	<div class="seo-mega-pack">
		<?php require DIR_TEMPLATE . 'module/seo_mega_pack-messages.tpl'; ?>
	
		<div class="box">
			<div class="content">
				<b><center><?php echo $text_smp_info; ?></center></b>
				
				<div class="progress" style="margin: 5px 0">
					<div class="progress-bar" id="progress-bar-percent" role="progressbar"></div>
				</div>
				
				<div id="progress-bar-info" style="text-align: center"></div>
				
				<div class="progress" style="margin: 5px 0" id="progress-2">
					<div class="progress-bar progress-bar-info" id="progress-bar-percent-2" role="progressbar"></div>
				</div>
				
				<div id="progress-bar-info-2" style="text-align: center"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	(function(){
		var extensions	= '<?php echo implode( ",", $extensions ); ?>'.split(','),
			step		= 0,
			index		= 0;
			
		extensions.push( '__final_step__' );
		
		function percent( step, p, text ) {
			$('#progress-2')[p==-1?'hide':'show']();
			$('#progress-bar-info-2')[p==-1?'hide':'show']();
			$('#progress-bar-percent-2').css('width', ( p > -1 ? p : 0 ) + '%');
			$('#progress-bar-info-2').text( p + '%' );
			
			var st = step==0?1:step;
			var pp = Math.floor( ( st / extensions.length ) * 100 );
		
			$('#progress-bar-info').text( '<?php echo $text_step; ?>: ' + st + '/' + extensions.length + ' - ' + ( text == '__final_step__' ? '<?php echo $text_finishing_installation; ?>' : text ) );
			$('#progress-bar-percent').css('width', pp + '%');
		}
		
		function go( st, p ) {
			percent( st, p, extensions[step] );
		
			$.post( '<?php echo $action; ?>'.replace(/&amp;/g, '&'), {
				'extension'	: extensions[step],
				'index'		: index
			}, function( response ){			
				if( response == '1' ) {
					step++;
					
					if( step >= extensions.length ) {
						percent( step, -1, '<?php echo $text_please_wait; ?>' );
						
						window.location = '<?php echo $action_ready; ?>'.replace(/&amp;/g, '&');
					} else {
						if( index ) {
							percent( step, 100, extensions[step-1] );
							
							index = 0;
							
							setTimeout(function(){
								go( step+1, -1 );
							}, 1000);
						} else {
							percent( step, -1, extensions[step-1] );

							index = 0;

							go( step+1, -1 );
						}
					}
				} else {
					response = response.split('|');
				
					percent( step+1, parseFloat( response[1] ), extensions[step] );
					
					setTimeout(function(){
						index++;
						go( step+1, parseInt( response[1] ) );
					}, 1000);
				}
			});
		}
		
		go( 0, -1 );
	})();
</script>
<?php echo $footer; ?>