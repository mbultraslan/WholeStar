<?php echo $header; ?>
&nbsp;
&nbsp;

<div class="container">

    <div class="row">

        <?php echo $column_left; ?>

        <?php if ($column_left && $column_right) { ?>

        <?php $class = 'col-lg-6 col-sm-6'; ?>

        <?php } elseif ($column_left || $column_right) { ?>

        <?php $class = 'col-lg-9 col-sm-9'; ?>

        <?php } else { ?>

        <?php $class = 'col-lg-12 col-sm-12'; ?>

        <?php } ?>

        <div id="content" class="<?php echo $class; ?>">

            
				<?php echo $content_top; ?>
				
				<?php if( ! empty( $breadcrumbs ) && is_array( $breadcrumbs ) ) { ?>
					<ul style="display:none;">
						<?php foreach( $breadcrumbs as $breadcrumb ) { ?>
							<?php if( NULL != ( $smk_title = strip_tags( $breadcrumb['text'] ) ) ) { ?>
								<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
									<a href="<?php echo $breadcrumb['href']; ?>" itemprop="url"><span itemprop="title"><?php echo strip_tags( $breadcrumb['text'] ); ?></span></a>
								</li>
							<?php } ?>
						<?php } ?>
					</ul>
				<?php } ?>
			
			

            <?php echo $content_bottom; ?>

        </div>

        <?php echo $column_right; ?>



    </div>

</div>

<?php if($stn_popup != 'N\A'){ ?>

<script>

    function termuser() {

        $('#blockuserform').show();

        $('body,html').stop(true, true).animate({

            scrollTop: 150

        }, 'slow');

    }

</script>

<div style="display:block;" class="usernamechangediv" id="blockuserform">

    <script>

        $(document).ready(function () {

            $('#eclo_blockuser').click(function () {

                $('#blockuserform').hide();

				$('body').removeAttr('style');

            });

			

			$('body').css({'overflow':'hidden','height':$(window).height()});

            

        });



    </script>

    <div class="pop_mesjh">

        <div class="chnge_proti">

            <h1><?php echo $stn_popup[0]['banner_title']; ?></h1>

            <a href="javascript:void(0);" id="eclo_blockuser" class="eclo">X</a>

            <div id="errorblockname"></div>

            <div class="rotm rotmg">

                <div class="item">

                    <div class="col-lg-12 col-md-12" style="padding-left:0;">

                        <p style="text-align:left;"><span class="allign-rgt-title" style="padding-left:0;"><?php echo $stn_popup[0]['link']; ?></span></p>

                        <p style="text-align:left;"><span style="padding-left:0;"><?php echo $stn_popup[0]['sort_order']; ?></span></p>

                            <span class="allign-lft-img">

                                <?php if($stn_popup[0]['image'] != ''){ echo '<img src="image/'.$stn_popup[0]['image'].'"';} ?>

                            </span>

                    </div>

                </div>                    

            </div>

            <div class="clr"></div>

        </div>

    </div>

</div>

<?php } ?>

<?php echo $footer; ?>