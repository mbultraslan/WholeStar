/* Layout */
{{#is layout_style 'boxed'}}
@media only screen and (min-width: 100em) {
.boxed #container {
max-width: calc({{maximum_width}} + 60px);
}
.container{
max-width:  {{maximum_width}};
}
}
{{/is}}
/* Custom Notification */
{{#if show_custom_notification}}
#notification {
top: 80px;
z-index: 9999;
opacity: 0;
right: 20px;
width: 300px;
position: fixed;
visibility: hidden;
transition: 0.3s ease-in-out;
-moz-transition: 0.3s ease-in-out;
-webkit-transition: 0.3s ease-in-out;
}

#notification.active {
top: 20px;
opacity: 1;
visibility: visible;
}
{{/if}}

/* Scroll up */
{{#if enable_scroll_up}}
.scrollup {
z-index: 2;
position: fixed;
right: 50px;
}
{{/if}}

/* Style Customization */
{{#if primary_color}}
.scrollup,
.kuler-accordion .item-title.selected,
button, .button, a.button,
#header .checkout a,
.cart-product-total-number,
.kuler-showcase-module .tab-heading span:after,
.kuler-filter .ui-widget-header,
.btn-primary,
.article__read-more .read-more,
.dropdown-menu .live-search-load-more:hover,
.product-info .product-detail-button--cart,
#tabs a.selected,
#top-bar .checkout .view:hover,
#top-bar .checkout .check:hover,
.product-tabs .nav-tabs>li.active>a,
.product-tabs .nav>li>a:hover,
.product-tabs .nav>li>a:focus,
.pagination .links a:hover,
.pagination .links b{
background-color: {{primary_color}};
}
{{/if}}
{{#if primary_color}}
.alert.alert-success,
a.articles__read-more,
a:visited.articles__read-more,
.product-name a:hover,
.kuler-showcase-module .tab-heading span.active,
.kuler-showcase-module .tab-heading span:hover{
color: {{primary_color}};
}
{{/if}}

{{#if body_container_bg_color}}
#container{
background-color: {{body_container_bg_color}};
}
{{/if}}

{{#if body_link_color}}
a,a:visited,
.mainmenu > li a,
#top-bar a{
color: {{body_link_color}};
}
{{/if}}


{{#if body_link_hover_color}}
a:hover,
a:active{
color: {{body_link_hover_color}};
}

.details .cart:hover,
.details .quick-view a:hover:before,
.details .cart a:hover:before,
.details .compare a:hover:before,
.details .wishlist a:hover:before{
background-color: {{body_link_hover_color}};
}
{{/if}}
{{#if body_bg_image.path}}
body {
background-image: url({{body_bg_image.path}});
{{#if body_bg_image.repeat}}
background-repeat: {{body_bg_image.repeat}};
{{/if}}
{{#if body_bg_image.position}}
background-position: {{body_bg_image.position}};
{{/if}}
{{#if body_bg_image.attachment}}
background-attachment: {{body_bg_image.attachment}};
{{/if}}
}
{{/if}}
{{#if body_font}}
body {
{{#if body_font.font_family}}
font-family: {{_fontFamily body_font.font_family}};
{{/if}}
{{#if body_font.font_size}}
font-size: {{body_font.font_size}};
{{/if}}
{{#if body_font.font_weight}}
font-weight: {{body_font.font_weight}};
{{/if}}
{{#if body_bg_color}}
background-color: {{body_bg_color}};
{{/if}}
{{#if body_pattern}}
background-image: url({{body_pattern}});
{{/if}}
{{#if body_text_color}}
color: {{body_text_color}};
{{/if}}
}
.kuler-tabs,.kuler-slides,
#header .checkout a,
.cart-total + .buttons a,
.ui-widget{
{{#if body_font.font_family}}
font-family: {{_fontFamily body_font.font_family}};
{{/if}}
}
{{/if}}

{{#if heading_font}}
h1,h2,h3,h4,h5,h6,.box-heading {
{{#if heading_font.font_family}}
font-family: {{_fontFamily heading_font.font_family}};
{{/if}}
{{#if heading_font.font_style}}
font-style: {{heading_font.font_style}};
{{/if}}
{{#if heading_font.font_weight}}
font-weight: {{heading_font.font_weight}};
{{/if}}
{{#if heading_font.text_transform}}
text-transform:{{heading_font.text_transform}};
{{/if}}
}
{{/if}}


{{#if heading_color}}
h1,h2,h3,h4,h5,h6,.box-heading {
color: {{heading_color}};
}
.footer h3:after,
#footer h3:after,
.box-heading:after,
.kuler-slides .box-heading:after{
background-color: {{heading_color}};
}
{{/if}}

{{#if topbar_background_color}}
.topbar{
{{#if topbar_background_color}}
background-color: {{topbar_background_color}};
{{/if}}
}
{{/if}}

{{#if topbar_link_color}}
.topbar a,
.topbar li>a,
.topbar .btn{
{{#if topbar_link_color}}
color: {{topbar_link_color}} !important;
{{/if}}
}
{{/if}}

{{#if topbar_link_hover_color}}
.topbar a:hover,
.topbar button:hover{
{{#if topbar_link_hover_color}}
color: {{topbar_link_hover_color}};
{{/if}}
}
{{/if}}

{{#if topbar_text_color}}
.topbar span{
{{#if topbar_text_color}}
color: {{topbar_text_color}};
{{/if}}
}
{{/if}}

{{#if topbar_border_color}}
.topbar{
{{#if topbar_border_color}}
border-bottom: 1px solid {{topbar_border_color}};
{{/if}}
}
{{/if}}


.header{
{{#if header_pattern}}
background-image: url({{header_pattern}});
{{/if}}
}
{{#if header_background_image.path}}
.header {
background-image: url({{header_background_image.path}});
{{#if header_background_image.repeat}}
background-repeat: {{header_background_image.repeat}};
{{/if}}
{{#if header_background_image.position}}
background-position: {{header_background_image.position}};
{{/if}}
{{#if header_background_image.attachment}}
background-attachment: {{header_background_image.attachment}};
{{/if}}
}
{{/if}}
{{#if header_background_color}}
.header{
{{#if header_background_color}}
background-color: {{header_background_color}};
{{/if}}
}
{{/if}}
{{#if header_search_border_color}}
.header #search input{
border-color: {{header_search_border_color}};
}
{{/if}}
{{#if header_search_bg_color}}
.header #search input{
background-color: {{header_search_bg_color}};
}
{{/if}}

{{#if bottom_background_color}}
.footer,.social-newsletter{
{{#if bottom_background_color}}
background-color: {{bottom_background_color}};
{{/if}}
}
{{/if}}

.social-newsletter .container,.footer{
{{#if bottom_border_color}}
border-color: {{bottom_border_color}};
{{/if}}
}

.footer,.social-newsletter{
{{#if bottom_pattern}}
background-image: url({{bottom_pattern}});
{{/if}}
{{#if bottom_text_color}}
color: {{bottom_text_color}};
{{/if}}
}

{{#if bottom_background_image.path}}
.footer,.social-newsletter {
background-image: url({{bottom_background_image.path}});
{{#if bottom_background_image.repeat}}
background-repeat: {{bottom_background_image.repeat}};
{{/if}}
{{#if bottom_background_image.position}}
background-position: {{bottom_background_image.position}};
{{/if}}
{{#if bottom_background_image.attachment}}
background-attachment: {{bottom_background_image.attachment}};
{{/if}}
}
{{/if}}

{{#if bottom_heading_color}}
.footer h3, .footer .box-heading{
{{#if bottom_heading_color}}
color: {{bottom_heading_color}};
{{/if}}
}
.footer h3:after,
.footer h3:after{
background-color: {{bottom_heading_color}};
}
{{/if}}

{{#if bottom_link_color}}
.footer a{
{{#if bottom_link_color}}
color: {{bottom_link_color}};
{{/if}}
}
{{/if}}

{{#if bottom_link_hover_color}}
.footer a:hover{
{{#if bottom_link_hover_color}}
color: {{bottom_link_hover_color}};
{{/if}}
}
.footer .contact li:hover{
{{#if bottom_link_hover_color}}
color: {{bottom_link_hover_color}};
{{/if}}
}

.social a:hover:before,
.newsletter button:hover{
{{#if bottom_link_hover_color}}
background-color: {{bottom_link_hover_color}};
{{/if}}
}
{{/if}}

#powered{
{{#if powered_background_color}}
background-color: {{powered_background_color}};
{{/if}}
{{#if powered_pattern}}
background-image: url({{powered_pattern}});
{{/if}}
}

{{#if powered_background_image.path}}
#powered {
background-image: url({{powered_background_image.path}});
{{#if powered_background_image.repeat}}
background-repeat: {{powered_background_image.repeat}};
{{/if}}
{{#if powered_background_image.position}}
background-position: {{powered_background_image.position}};
{{/if}}
{{#if powered_background_image.attachment}}
background-attachment: {{powered_background_image.attachment}};
{{/if}}
}
{{/if}}

#powered{
color: {{powered_text_color}};
}
#powered a{
{{#if powered_link_color}}
color: {{powered_link_color}};
{{/if}}
}

{{#if powered_link_hover_color}}
#powered a:hover{
{{#if powered_link_hover_color}}
color: {{powered_link_hover_color}};
{{/if}}
}
{{/if}}

{{#if social_newsletter_bg_color}}
.social-newsletter{
background: {{social_newsletter_bg_color}};
}
{{/if}}

.social-icons__item:before{
{{#if social_icon_bg}}
background-color: {{social_icon_bg}};
{{/if}}
{{#if social_icon_color}}
color: {{social_icon_color}};
{{/if}}
}

.social-icons__item:hover:before{
{{#if social_icon_hover_bg}}
background-color: {{social_icon_hover_bg}};
{{/if}}
{{#if social_icon_hover_color}}
color: {{social_icon_hover_color}};
{{/if}}
}

.navigation--mega{
{{#if menu_bg_color}}
background-color: {{menu_bg_color}};
{{/if}}
{{#if menu_border_color}}
border-color: {{menu_border_color}};
{{/if}}
}

{{#if menu_item_hover_color}}
.main-nav__item a:hover{
color: {{menu_item_hover_color}};
{{/if}}
}

{{#if menu_font}}
#menu,
#megamenu{
{{#if menu_font.font_family}}
font-family: {{_fontFamily menu_font.font_family}};
{{/if}}
{{#if menu_font.font_weight}}
font-weight: {{_fontFamily menu_font.font_weight}};
{{/if}}
{{#if menu_font.font_style}}
font-style: {{_fontFamily menu_font.font_style}};
{{/if}}
{{#if menu_font.text_transform}}
text-transform: {{_fontFamily menu_font.text_transform}};
{{/if}}
}
{{/if}}
{{#if menu_item_color}}
@media (min-width: 992px){
.main-nav__item a {
color: {{menu_item_color}};
}
}
{{/if}}
{{#if menu_item_color}}
@media only screen and (min-width: 992px){
.main-nav__item > a:before{
background: {{menu_item_color}};
}
}
{{/if}}
{{#if product_price_color}}
.product-price{
color: {{product_price_color}};
}
{{/if}}
{{#if product_old_price_color}}
.product-price--old{
color: {{product_old_price_color}};
}
{{/if}}
{{#if product_name_color}}
.product-name a,
.product-name a:visited{
color: {{product_name_color}};
}
{{/if}}

.product-sale{
{{#if product_cart_color}}
background-color: {{product_sale_color}};
{{/if}}

{{#if product_cart_color}}
color: {{product_sale_text_color}};
{{/if}}
}

{{#if deal_color}}
.countdown-amount{
background-color: {{deal_color}};
}
{{/if}}

{{#if deal_color}}
.countdown-period{
color: {{deal_color}};
}

{{/if}}
{{#if deal_text_color}}
.countdown-amount{
color: {{deal_text_color}};
}
{{/if}}
.product-detail-button--wishlist, .product-detail-button--compare, .product-detail-button--quick-view{
{{#if product_buttons_bg_color}}
background-color: {{product_buttons_bg_color}};
{{/if}}
}
.product-detail-button--wishlist i, .product-detail-button--compare i, .product-detail-button--quick-view i{
{{#if product_buttons_color}}
color: {{product_buttons_color}};
{{/if}}
}
.product-detail-button--wishlist:hover, .product-detail-button--compare:hover, .product-detail-button--quick-view:hover{
{{#if product_buttons_bg_hover_color}}
background-color: {{product_buttons_bg_hover_color}};
border: 1px solid {{product_buttons_bg_hover_color}};
{{/if}}
}
.product-detail-button--wishlist:hover i, .product-detail-button--compare:hover i, .product-detail-button--quick-view:hover i{
{{#if product_buttons_hover_color}}
color: {{product_buttons_hover_color}};
{{/if}}
}
.product-detail-button--cart{
{{#if product_cart_bg_color}}
background-color: {{product_cart_bg_color}};
{{/if}}
}
.product-detail-button--cart i, .product-detail-button--cart span{
{{#if product_cart_color}}
color: {{product_cart_color}};
{{/if}}
}
.product-detail-button--cart:hover{
{{#if product_cart_hover_bg_color}}
background-color: {{product_cart_hover_bg_color}};
border: 1px solid {{product_cart_hover_bg_color}};
{{/if}}
}
.product-detail-button--cart:hover i, .product-detail-button--cart:hover span{
{{#if product_cart_hover_color}}
color: {{product_cart_hover_color}};
{{/if}}
}

{{#if testimonial_bg_image.path}}
.kt-module {
background-image: url({{testimonial_bg_image.path}});
{{/if}}
}

{{#if testimonial_bg_color}}
.kt-module {
background: {{testimonial_bg_color}};
{{/if}}
}

.kt-module__item__testimonial{
{{#if testimonial_text_color_testimonial}}
color: {{testimonial_text_color_testimonial}};
{{/if}}
}

.kt-module__item__writer{
{{#if testimonial_text_color_writer}}
color: {{testimonial_text_color_writer}};
{{/if}}
}
.kt-module__item__information{
{{#if testimonial_text_color_information}}
color: {{testimonial_text_color_information}};
{{/if}}
}

button, .button, .btn, .mini-cart__view-cart, .mini-cart__checkout, .btn-primary, a.button, .read-more{
{{#if button_color}}
color: {{button_color}} !important;
{{/if}}

{{#if button_color}}
background: {{button_bg_color}};
{{/if}}
}
button:hover, .button:hover, .btn:hover,.mini-cart__view-cart:hover, .mini-cart__checkout:hover, .btn-primary:hover, a.button:hover, .read-more:hover{
{{#if button_color}}
color: {{button_hover_color}};
{{/if}}

{{#if button_color}}
background: {{button_bg_hover_color}};
{{/if}}
}

