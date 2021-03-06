<?php
$kuler = Kuler::getInstance();
$theme = $kuler->getTheme();
$kuler->language->load('kuler/zorka');

$kuler->addStyle(array(
  "catalog/view/javascript/bootstrap/css/bootstrap.min.css",
  "catalog/view/theme/$theme/stylesheet/_grid.css",
  "catalog/view/javascript/font-awesome/css/font-awesome.css",
  "catalog/view/theme/$theme/assets/pe-icon-7-stroke/css/pe-icon-7-stroke.css",
  "catalog/view/theme/$theme/stylesheet/stylesheet.css",
));

$kuler->addScript(array(
  'catalog/view/javascript/jquery/jquery-2.1.1.min.js',
  'catalog/view/javascript/bootstrap/js/bootstrap.min.js',
  "catalog/view/theme/$theme/js/common.js",
  "catalog/view/theme/$theme/js/lib/jquery.magnific-popup.min.js",
  "catalog/view/theme/$theme/js/lib/main.js",
  "catalog/view/theme/$theme/js/lib/parallax.js",
  
));

$kuler->addScript(array("catalog/view/theme/$theme/js/lib/owl.carousel.min.js"), true);
$kuler->addScript(array("catalog/view/theme/$theme/js/utils.js"), true);
?>
  <!DOCTYPE html>
  <!--[if IE]><![endif]-->
  <!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
  <!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
  <!--<![endif]-->
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?></title>
    <base href="<?php echo $base; ?>" />
    <?php if ($description) { ?>
      <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    <?php if ($keywords) { ?>
      <meta name="keywords" content= "<?php echo $keywords; ?>" />
    <?php } ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if ($icon) { ?>
      <link href="<?php echo $icon; ?>" rel="icon" />
    <?php } ?>
    <?php foreach ($links as $link) { ?>
      <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
    <?php } ?>
    <!-- {STYLES} -->
    <!-- {SCRIPTS} -->
    <?php foreach ($styles as $style) { ?>
      <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
    <?php } ?>
    <script src="catalog/view/javascript/common.js" type="text/javascript"></script>
    <?php foreach ($scripts as $script) { ?>
      <script src="<?php echo $script; ?>" type="text/javascript"></script>
    <?php } ?>
    <?php echo $google_analytics; ?>
    <?php if($direction == "rtl") { ?>
      <link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $kuler->getTheme() ?>/stylesheet/rtl.css" media="screen">
    <?php } ?>
  </head>
  
  <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&appId=1460340360870422&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<body class="<?php echo $class; ?> <?php echo $kuler->getBodyClass(); ?>">
<script>
 jQuery(document).ready(function() {
    // Fetch Facebook Likes once and every 30 seconds thereafter
    // Adjust setInterval to either fetch content in different frequency or remove to only fetch once.
    //realtime_fb_likes();
   // setInterval("realtime_fb_likes()", 30000);
  });
 
  // Fetch FB Likes
  // Replace "Starbucks" with your Facebook Profile/Page ID, or full external website address.
  // Get FB ID here:  http://graph.facebook.com/your_page_name
  function realtime_fb_likes() {
    $.getJSON('http://graph.facebook.com/dressdirect.co.uk/', function(data) {
      var fb_likes = addCommas(data.likes);
      $('#fb-likes-count').text(fb_likes);
    });
  }
 
  // Pretty number format to add commas between numbers
  // Source: http://www.mredkj.com/javascript/nfbasic.html
  function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
  }
</script>

<?php
$modules = Kuler::getInstance()->getModules('header_top');
if ($modules) {
  echo '<div class="header-top"><div class="container">'.implode('', $modules).'</div></div>';
}
?>
  <nav class="topbar">
    <div class="container">
      <div class="row">
        <div class="col-lg-7 col-md-7 topbar__left">
          <?php if ($logged) { ?>
            <a href="<?php echo $account; ?>"><?php echo $text_account; ?></a>
            <a href="<?php echo $order; ?>"><?php echo $text_order; ?></a>
            <a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a>
            <a href="<?php echo $download; ?>"><?php echo $text_download; ?></a>
            <a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a>
          <?php } else { ?>
            <span><?php // echo $kuler->language->get('text_welcome'); ?></span>
            <a href="<?php echo $register; ?>"><?php echo $text_register; ?></a>
            <span><?php // echo $kuler->language->get('text_or'); ?>&nbsp; / &nbsp; </span>
            <a href="<?php echo $login; ?>"><?php echo $text_login; ?></a>
          <?php } ?>
        </div>
        <div class="col-lg-5 col-md-5 topbar__right">
          <div id="top-links" class="nav topbar__my-accounts">
            <ul class="list-inline">
             <li>
                    <a href="<?php echo $wishlist; ?>" id="wishlist-total" title="<?php echo $text_wishlist; ?>"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_wishlist; ?></span></a>
                  </li>
                  <li>
                    <a href="<?php echo $shopping_cart; ?>" title="<?php echo $text_shopping_cart; ?>"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_shopping_cart; ?></span></a>
                  </li>
                  <li>
                    <a href="<?php echo $checkout; ?>" title="<?php echo $text_checkout; ?>"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_checkout; ?></span></a>
                  </li>
              
              <li>
                <?php echo $language; ?>
              </li>
              <li>
                <?php echo $currency; ?>
              </li>
               <li> <?php echo $cart; ?></li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </nav><!-- /.topbar  -->
  <header class="header">
    <div class="container">
      <div class="row header__top">
      
        
        
         <div class="col-lg-12 col-md-12 col-xs-12"><!--/#this codes added by mbayrak-->
          <div id="logo">
            <?php if ($logo) { ?>
              <a href="<?php echo $home; ?>">
                <img style="max-width:300px;" src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" />
              </a>
            <?php } else { ?>
              <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
            <?php } ?>
          </div><!--/#logo-->
        </div>
        
      </div>
    </div>
    
    <?php
    $modules = Kuler::getInstance()->getModules('menu');
    if ($modules) {
      echo implode('', $modules);
    }else{
      ?>
      <?php if ($categories) { ?>
        <nav id="megamenu" class="navbar">
          <div class="container">
            <div class="navbar-header"><span id="category" class="visible-xs"><?php echo $text_category; ?></span>
              <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
              <ul class="mainmenu">
                <li style="display: none;"><a><?php echo $kuler->translate($kuler->getSkinOption('mobile_menu_title')); ?></a></li>
                <li class="item"><a href="<?php echo $base; ?>" <?php if ($kuler->getSkinOption('home_icon_type') == 'icon') { ?> class="home-icon" <?php } ?>><?php echo $kuler->language->get('text_home') ?></a></li>
                <?php foreach ($categories as $category) { ?>
                  <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
                    <?php if ($category['children']) { ?>
                      <div>
                        <?php for ($i = 0; $i < count($category['children']);) { ?>
                          <ul>
                            <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
                            <?php for (; $i < $j; $i++) { ?>
                              <?php if (isset($category['children'][$i])) { ?>
                                <li><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a>
                                  <?php if (!empty($category['children'][$i]['children'])) { ?>
                                    <?php echo renderSubMenuRecursive($category['children'][$i]['children']); ?>
                                  <?php } ?>
                                </li>
                              <?php } ?>
                            <?php } ?>
                          </ul>
                        <?php } ?>
                      </div>
                    <?php } ?>
                  </li>
                <?php } ?>
              </ul>
            </div>
            <?php if ($kuler->getSkinOption('live_search_status')) { ?>
             <?php include(DIR_TEMPLATE . Kuler::getInstance()->getTheme() . '/template/common/_live_search.tpl');?>
            <?php } else { ?>
              <?php echo $search; ?>
            <?php } ?>
          </div>
        </nav><!-- /.navbar -->
      <?php } ?>
    <?php } ?>
    <div class="container search-mobile">
      <div class="row">
        <div class="col-xs-12 hidden-md">
          <?php if ($kuler->getSkinOption('live_search_status')) {?>
            <?php include(DIR_TEMPLATE . Kuler::getInstance()->getTheme() . '/template/common/_live_search.tpl');?>
          <?php } else { ?>
            <?php echo $search;?>
          <?php } ?>
        </div>
      </div>
    </div>

  </header><!-- /.header--1 -->

<?php
function renderSubMenuRecursive($categories) {
  $html = '<ul class="sublevel">';

  foreach ($categories as $category)
  {
    $parent = !empty($category['children']) ? ' parent' : '';
    $active = !empty($category['active']) ? ' active' : '';
    $html .= sprintf("<li class=\"item$parent $active\"><a href=\"%s\">%s</a>", $category['href'], $category['name']);

    if (!empty($category['children']))
    {
      $html .= '<span class="btn-expand-menu"></span>';
      $html .= renderSubMenuRecursive($category['children']);
    }

    $html .= '</li>';
  }

  $html .= '</ul>';

  return $html;
}
?>
  <div class="kl-main-content">
<?php
$modules = Kuler::getInstance()->getModules('promotion');

if ($modules) {
  echo implode('', $modules);
}
?>