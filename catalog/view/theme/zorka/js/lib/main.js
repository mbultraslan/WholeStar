jQuery(document).ready(function($){
	//if you change this breakpoint in the style.css file (or _layout.scss if you use SASS), don't forget to update this value as well
	var MqL = 1170;
	//move nav element position according to window width

	//mobile - open lateral menu clicking on the menu icon
	$('.kl-nav-trigger').on('click', function(event){
		event.preventDefault();
		if( $('.kl-main-content').hasClass('nav-is-visible') ) {
			closeNav();
			$('.kl-overlay').removeClass('is-visible');
		} else {
			$(this).addClass('nav-is-visible');
			$('.kl-primary-nav').addClass('nav-is-visible');
			$('.header').addClass('nav-is-visible');
			$('.topbar').addClass('nav-is-visible');
			$('.kl-main-content').addClass('nav-is-visible').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
				$('body').addClass('overflow-hidden');
			});
			toggleSearch('close');
			$('.kl-overlay').addClass('is-visible');
		}
	});

	//open search form
	$('.kl-search-trigger').on('click', function(event){
		event.preventDefault();
		toggleSearch();
		closeNav();
	});

	//close lateral menu on mobile 
	$('.kl-overlay').on('swiperight', function(){
		if($('.kl-primary-nav').hasClass('nav-is-visible')) {
			closeNav();
			$('.kl-overlay').removeClass('is-visible');
		}
	});
	$('.nav-on-left .kl-overlay').on('swipeleft', function(){
		if($('.kl-primary-nav').hasClass('nav-is-visible')) {
			closeNav();
			$('.kl-overlay').removeClass('is-visible');
		}
	});
	$('.kl-overlay').on('click', function(){
		closeNav();
		toggleSearch('close')
		$('.kl-overlay').removeClass('is-visible');
	});


	//prevent default clicking on direct children of .kl-primary-nav 
	$('.kl-primary-nav').children('.has-children').children('a').on('click', function(event){
		event.preventDefault();
	});
	//open submenu
	$('.has-children').children('a').on('click', function(event){
		if( !checkWindowWidth() ) event.preventDefault();
		var selected = $(this);
		if( selected.next('ul').hasClass('is-hidden') ) {
			//desktop version only
			selected.addClass('selected').next('ul').removeClass('is-hidden').end().parent('.has-children').parent('ul').addClass('moves-out');
			selected.parent('.has-children').siblings('.has-children').children('ul').addClass('is-hidden').end().children('a').removeClass('selected');
			$('.kl-overlay').addClass('is-visible');
		} else {
			selected.removeClass('selected').next('ul').addClass('is-hidden').end().parent('.has-children').parent('ul').removeClass('moves-out');
			$('.kl-overlay').removeClass('is-visible');
		}
		toggleSearch('close');
	});

	//submenu items - go back link
	$('.go-back').on('click', function(){
		$(this).parent('ul').addClass('is-hidden').parent('.has-children').parent('ul').removeClass('moves-out');
	});

	function closeNav() {
		$('.kl-nav-trigger').removeClass('nav-is-visible');
		$('.header').removeClass('nav-is-visible');
		$('.topbar').removeClass('nav-is-visible');
		$('.kl-primary-nav').removeClass('nav-is-visible');
		$('.has-children ul').addClass('is-hidden');
		$('.has-children a').removeClass('selected');
		$('.moves-out').removeClass('moves-out');
		$('.kl-main-content').removeClass('nav-is-visible').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
			$('body').removeClass('overflow-hidden');
		});
	}

	function toggleSearch(type) {
		if(type=="close") {
			//close serach 
			$('.kl-search').removeClass('is-visible');
			$('.kl-search-trigger').removeClass('search-is-visible');
		} else {
			//toggle search visibility
			$('.kl-search').toggleClass('is-visible');
			$('.kl-search-trigger').toggleClass('search-is-visible');
			if($(window).width() > MqL && $('.kl-search').hasClass('is-visible')) $('.kl-search').find('input[type="search"]').focus();
			($('.kl-search').hasClass('is-visible')) ? $('.kl-overlay').addClass('is-visible') : $('.kl-overlay').removeClass('is-visible') ;
		}
	}

	function checkWindowWidth() {
		//check window width (scrollbar included)
		var e = window,
			a = 'inner';
		if (!('innerWidth' in window )) {
			a = 'client';
			e = document.documentElement || document.body;
		}
		if ( e[ a+'Width' ] >= MqL ) {
			return true;
		} else {
			return false;
		}
	}

	function moveNavigation(){
		var navigation = $('.kl-nav');
		var desktop = checkWindowWidth();
		if ( desktop ) {
			navigation.detach();
			navigation.insertBefore('.kl-header-buttons');
		} else {
			navigation.detach();
			navigation.insertAfter('.kl-main-content');
		}
	}
});