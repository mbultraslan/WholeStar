<?php $kuler = Kuler::getInstance();?>
<?php
$modules = Kuler::getInstance()->getModules('footer_top');
if ($modules) {
echo implode('', $modules);
}
?>
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                <h3 class="box-heading"><?php echo $text_extra; ?></h3>
                <ul class="list-unstyled list-10">
                    <!--<li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li> -->
                    <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
                    <!--<li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li> -->
                    <!--<li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a> -->
                    <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
                    <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
                    <li><a href="<?php echo $blog; ?>"><?php echo $text_blog; ?></a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                <h3 class="box-heading"><?php echo $text_account; ?></h3>
                <ul class="list-unstyled list-10">
                    <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
                    <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
                    <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
                    <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
                    <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <?php if ($informations) { ?>
                <h3 class="box-heading"><?php echo $text_information; ?></h3>
                <ul class="list-unstyled list-10">
                    <?php foreach ($informations as $information) { ?>
                    <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 socialfooterblock">
                <h3 class="box-heading" id="socialheading"></h3>
                <div class="social-icons">
                    <?php if ($kuler->getSkinOption('show_social_icons')) { ?>
                    <?php /*if ($kuler->getSkinOption('show_social_icons_title')) { ?>
                    <h3><span><?php echo $kuler->translate($kuler->getSkinOption('social_icon_title')); ?></span></h3>
                    <?php } */?>
                    <?php if ($social_icons = $kuler->getSocialIcons()) { ?>
                    <ul class="social-icons__list">
                        <?php foreach ($social_icons as $social_icon) { ?>
                        <li>
                            <a href="<?php echo $social_icon['link']; ?>" target="_blank" class="<?php echo $social_icon['class']; ?> social-icons__item"></a>
                        </li>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                    <?php } ?>
                </div><!--/social icons-->
            </div>
            <?php if ($kuler->getSkinOption('show_information')) { ?>
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 footer__information footer_new">

                <?php /*if ($kuler->getSkinOption('show_information_title')) { ?>
                <h3 class="box-heading divide"><span><?php echo $kuler->translate($kuler->getSkinOption('information_title')); ?></span></h3>
                <?php } */?>
                <p class="footer__information-text">
                    <?php echo $kuler->translate($kuler->getSkinOption('information_content')); ?>
                </p>
                <?php } ?>

                <?php if ($kuler->getSkinOption('show_contact')) { ?>
                <?php if ($kuler->getSkinOption('show_contact_title')) { ?>
                <h3 class="box-heading">
                    <span>
                        <?php echo $kuler->translate($kuler->getSkinOption('contact_title')); ?>
                    </span>
                </h3>
                <?php } ?>

            </div><!--/.footer__information-->
            <?php } ?>
        </div>
    </div>
</footer>
<div id="powered">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-xs-12 copyright">
                <?php if ($kuler->getSkinOption('show_custom_copyright')) { ?>
                <?php echo $kuler->translate($kuler->getSkinOption('custom_copyright')); ?>
                <?php } else { ?>
                <?php echo $powered; ?>
                <?php } ?>
            </div><!--.powered-->
            <?php if ($kuler->getSkinOption('show_payment_icons') && $payment_icons = $kuler->getPaymentIcons()) { ?>
            <div class="col-lg-8 col-md-8 col-xs-12 col-no-padding payment">
                <ul>
                    <?php foreach ($payment_icons as $payment_icon) { ?>
                    <li class="payment__item"><a href="<?php echo $payment_icon['link']; ?>"<?php if ($payment_icon['new_tab']) echo ' target="_blank"'; ?> title="<?php echo $kuler->translate($payment_icon['name']); ?>"><img src="<?php echo $payment_icon['thumb']; ?>" alt="<?php echo $kuler->translate($payment_icon['name']); ?>" style="height:31px;" /></a></li>					
                    <?php } ?>
                </ul>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php
$modules = Kuler::getInstance()->getModules('footer_bottom');
if ($modules) {
echo implode('', $modules);
}
?>
</div>
<?php if ($kuler->getSkinOption('login_popup')) { ?>
<?php $kuler->loginPopupInit($data); ?>
<div style="display: none">
    <div id="login-popup">
        <div class="col-sm-6 left">
            <h2><?php echo _t('text_new_customer'); ?></h2>
            <div class="content">
                <p><b><?php echo _t('text_register'); ?></b></p>
                <p><?php echo _t('text_register_account'); ?></p>
                <a href="index.php?route=account/register" class="button"><?php echo _t('button_continue'); ?></a></div>
        </div>
        <div class="col-sm-6 right">
            <h2><?php echo _t('text_returning_customer'); ?></h2>
            <form id="popup-login-form">
                <div class="content">
                    <p><?php echo _t('text_i_am_returning_customer'); ?></p>
                    <b><?php echo _t('entry_email'); ?></b><br />
                    <input type="text" name="email" />
                    <br />
                    <br />
                    <b><?php echo _t('entry_password'); ?></b><br />
                    <input type="password" name="password" />
                    <br />
                    <a href="<?php echo $data['forgotten_url']; ?>"><?php echo _t('text_forgotten'); ?></a><br />
                    <br />
                    <input type="submit" value="<?php echo _t('button_login'); ?>" class="button" />
                </div>
            </form>
        </div>
        <?php echo $social_login; ?>
    </div>
</div>
<?php } ?>
<?php if ($kuler->getSkinOption('enable_scroll_up')) { ?>
<a class="scrollup"><?php echo $kuler->translate($kuler->getSkinOption('scroll_up_text')); ?></a>
<?php } ?>
<!-- {BODY_SCRIPTS} -->
<!-- Theme Version: <?php echo $kuler->getThemeVersion(); ?> | Kuler Version: <?php echo $kuler->getKulerVersion(); ?> | Skin: <?php echo $kuler->getRootSkin(); ?> -->
<?php if(!$kuler->mobile->isMobile()){ ?>
<?php } ?>

<?php
if(!empty($stn_popup))
{
?>
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
            });
/*
            $("#owl_123").owlCarousel({
                loop: true,
                items: 1,
                dots: false,
                autoHeight: true,
                rtl: false
            });

            $(".chnge_proti .next").click(function () {
                $("#owl_123").trigger('next.owl.carousel');
            });

            $(".chnge_proti .prev").click(function () {
                $("#owl_123").trigger('prev.owl.carousel');
            });*/
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

<!-- Google Code for Remarketing Tag -->
<!--------------------------------------------------
Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. See more information and instructions on how to setup the tag on: http://google.com/ads/remarketingsetup
--------------------------------------------------->
<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 965020876;
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
    <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/965020876/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>


					<?php if( ! empty( $smp_google_analytics ) ) { ?>
						<?php echo htmlspecialchars_decode( $smp_google_analytics ); ?>
					<?php } ?>
				</body>
			
			
</html>