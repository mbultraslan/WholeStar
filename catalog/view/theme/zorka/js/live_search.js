var RESULT_CLASS = 'kuler-finder-result',
	ITEM_CLASS = 'box-product list-layout',
	LOAD_MORE_CONTAINER_ID = 'load-more-container',
	LOAD_MORE_ID = 'load-more',
	LOADING_ID = 'kuler-finder-loading',
	LOADING_IMAGE_SRC = 'catalog/view/theme/'+ Kuler.theme +'/image/icon/loading.gif',
	LOAD_MORE_TEXT = Kuler.text_load_more;

jQuery(document).ready(function($) {
	// Autocomplete */
	(function($) {
		function Autocomplete(element, options) {
			this.element = element;
			this.options = options;
			this.timer = null;
			this.items = new Array();

			$(element).attr('autocomplete', 'off');
			$(element).on('focus', $.proxy(this.focus, this));
			$(element).on('blur', $.proxy(this.blur, this));
			$(element).on('keydown', $.proxy(this.keydown, this));

			$(element).after('<ul class="dropdown-menu"></ul>');
			$(element).siblings('ul.dropdown-menu');
		}

		Autocomplete.prototype = {
			focus: function() {
				this.request();
			},
			blur: function() {
				setTimeout(function(object) {
					object.hide();
				}, 200, this);
			},
			keydown: function(event) {
				switch(event.keyCode) {
					case 27: // escape
						this.hide();
						break;
					default:
						this.request();
						break;
				}
			},
			show: function() {
				var pos = $(this.element).position();

				$(this.element).siblings('ul.dropdown-menu').css({
					top: pos.top + $(this.element).outerHeight(),
					//left: pos.left-115
				});

				$(this.element).siblings('ul.dropdown-menu').show();
			},
			hide: function() {
				$(this.element).siblings('ul.dropdown-menu').hide();
			},
			request: function() {
				clearTimeout(this.timer);

				this.timer = setTimeout(function(object) {
					object.options.source($(object.element).val(), $.proxy(object.response, object));
				}, 200, this);
			},
			response: function(json) {
				var html = '';

				if (json.products && json.products.length) {
					$.each(json.products, function (index, product) {
						html += '<li>' + product.html + '</li>';
					});

					if (json.more) {
						html += '<li><a class="live-search-load-more">'+ Kuler.text_load_more +'</a></li>';
					}
				} else {
					html += '<li class="live-search-no-result">'+ Kuler.text_no_results +'</li>'
				}

				if (html) {
					this.show();
				} else {
					this.hide();
				}

				$(this.element).siblings('ul.dropdown-menu').html(html);

				$('.live-search-load-more').on('click', function () {
					$('.button-search')[0].click();
				});
			}
		};

		$.fn.livesearch = function(option) {
			return this.each(function() {
				var data = $(this).data('livesearch');

				if (!data) {
					data = new Autocomplete(this, option);

					$(this).data('livesearch', data);
				}
			});
		}
	})(window.jQuery);


	var $kfInput = $('.kf_search'),
		$kfBtnSearch = $('.button-search'),
		$kfCategory = $('.kf_category'),
		$kfManufacturer = $('.kf_manufacturer'),
		$kfContainer = $kfInput.parent(),
		currentSearchUrl, responseData;

	function search() {
		var url = $('base').attr('href') + 'index.php?route=product/search';

		var search = $kfInput.val();

		if (search) {
			url += '&search=' + encodeURIComponent(search);
		}

		if ($kfCategory.length) {
			url  += '&category_id=' + $kfCategory.val();
		}

		window.location = url;
	}

	/* Search */
	$kfBtnSearch.bind('click', search);

	/* Press enter */
	$kfInput.bind('keydown', function(e) {
		if (e.keyCode == 13) {
			search();
		}
	});

	$kfInput.livesearch({
		'source': function(request, response) {
			currentSearchUrl = 'index.php?route=module/kuler_cp/liveSearch&filter_name=' +  encodeURIComponent(request);

			if ($kfCategory.length && $kfCategory.val()) {
				currentSearchUrl += '&filter_category_id=' + $kfCategory.val();
			}

			if ($kfManufacturer.length && $kfManufacturer.val()) {
				currentSearchUrl  += '&filter_manufacturer_id=' + $kfManufacturer.val();
			}

			$.ajax({
				url: currentSearchUrl,
				dataType: 'json',
				success: function(data) {
					responseData = data;

					if (!data.status) {
						return;
					}

					response(data);
				}
			});
		}
	});
});