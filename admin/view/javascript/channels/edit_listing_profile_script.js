$( document ).ready(function() {

	$('#save_profile_step1').click(function(){
		if($('#ebay_loading').length) {
			console.log('now processing');
		} else {
			$('div.pr').append('<div id="ebay_loading" style="position: absolute; width: 100%; height: 100%; opacity: 0.3; left: 0px; top: 0px; z-index: 1000000; background: url(http://localhost/ebay/admin/view/image/channels/ajax-loader.gif) 50% 50% no-repeat black;"></div>');
			setTimeout(function(){
				$('#ebay_add_list_profile').submit();
			}, 1000);
		}
		return false;
	});

	$('div.wizard .save_profile').click(function(){
		var action = $(this).data('action');
		if(action == 'close') {
			$('<input>').attr({
				type: 'hidden',
				name: 'close_form',
				value: '1'
			}).appendTo('#ebay_add_list_profile');
		}

		if($('#ebay_loading').length) {
			console.log('now processing');
		} else {
			$('div.pr').append('<div id="ebay_loading" style="position: absolute; width: 100%; height: 100%; opacity: 0.3; left: 0px; top: 0px; z-index: 1000000; background: url(http://localhost/ebay/admin/view/image/channels/ajax-loader.gif) 50% 50% no-repeat black;"></div>');
			setTimeout(function(){
				$('#ebay_add_list_profile').submit();
			}, 1000);
		}
		return false;
	});

	$('select[name="site_id"]').change(function() {
		$('.bcs').addClass("hide");
		$('#browse_category_' + $(this).val()).removeClass("hide");
	});

	$("a.import_categories").click(function(){
		var btn = $(this);
		var label = btn.html();
		btn.html('Loading...');
		$('select[name="site_id"]').prop('disabled', true);
		$.get(btn.attr('href'), {'site_id' : $('select[name="site_id"]').val()}, function(data){
			btn.html(label);
			$('select[name="site_id"]').prop('disabled', false);
			if(data.status) {
				btn.addClass('hide');
				$('.browse_group', btn.parent()).removeClass("hide");
			} else if(data.hasOwnProperty('error')) {
				alert(data.error);
			}
		});
		return false;
	});

	$("a.import_store_categories").click(function(){
		var btn = $(this);
		var label = btn.html();
		btn.html('Loading...');
		$('select[name="site_id"]').prop('disabled', true);
		$.get(btn.attr('href'), {'site_id' : $('select[name="site_id"]').val()}, function(data){
			btn.html(label);
			if(data.status) {
				btn.addClass('hide');
				$('.store_browse_group', btn.parent()).removeClass("hide");
			} else if(data.hasOwnProperty('error')) {
				alert(data.error);
			}
		});
		return false;
	});


	$("a.update_categories").click(function(){
		var btn = $(this);
		var label = btn.html();
		btn.html('Loading...');
		$('select[name="site_id"]').prop('disabled', true);
		$.get(btn.attr('href'), {'site_id' : $('select[name="site_id"]').val()}, function(data){
			btn.html(label);
			$('select[name="site_id"]').prop('disabled', false);
			if(data.status) {
				alert("Completed!");
			} else if(data.hasOwnProperty('error')) {
				alert(data.error);
			}
		});
		return false;
	});

	$('input.category_input').keyup(function(){

	});


    $('.browse_btn').click(function(){
        $('#select_category_modal').modal('show');
    });

    $('.browse_store_btn').click(function(){
        $('#select_store_category_modal').modal('show');
    });

    $('#select_category_modal').on('show.bs.modal', function (e) {
        console.log('show my modal');
        var tree = $('<div style="overflow: auto; height: 400px;"></div>');
        var siteId = null;
        if($('select[name="site_id"]').length) {
            siteId =  $('select[name="site_id"]').val();
        } else {
            siteId =  $('input[name="site_id"]').val();
        }
        $('#choose_category_btn').addClass('hide');
        $('#tree_panel').html('').append(tree);
        tree.dynatree({
            persist: false,
            checkbox: false,
            selectMode: 1,
            onPostInit: function(isReloading, isError) {
                this.reactivate();
            },
            fx: { height: "toggle", duration: 200 },
            initAjax: {url: $('#tree_panel').attr('data-url'),
                dataType: "json",
                timeout: 10000,
                data: {"site_id": siteId}
            },
            onLazyRead: function(node){
                node.appendAjax(
                    {url: $('#tree_panel').attr('data-url'),
                        dataType: "json",
                        data: {"parent_id": node.data.key,	"site_id": siteId}
                    });
            },
            onActivate: function(node) {
                if(!node.data.unselectable) {
                    $('#choose_category_btn').attr('data-id',node.data.key).removeClass('hide');
                } else {
                    $('#choose_category_btn').addClass('hide');
                }
            }
        });
    });

    $('#choose_category_btn').click(function(){
        var siteId = null;
        if($('select[name="site_id"]').length) {
            siteId =  $('select[name="site_id"]').val();
        } else {
            siteId =  $('input[name="site_id"]').val();
        }

        $('input[name="ebay_category_id_' + siteId+ '"]').val($(this).attr('data-id'));
        var $help =  $('.hinfo', $('#browse_category_' + siteId));
        $help.html('loading...');
        $.get($('#tree_panel').attr('data-path'), {'category_id' : $(this).attr('data-id'), "site_id": siteId}, function(data) {
            if(data.hasOwnProperty('error')) {
                alert(data.error);
            } else {
                $help.html(data.path);
            }
        })

        $('#select_category_modal').modal('hide');


        return false;
    });

    $('#select_store_category_modal').on('show.bs.modal', function (e) {
        console.log('show my modal');
        var tree = $('<div style="overflow: auto; height: 400px;"></div>');
        $('#choose_store_category_btn').addClass('hide');
        $('#store_tree_panel').html('').append(tree);
        tree.dynatree({
            persist: false,
            checkbox: false,
            selectMode: 1,
            onPostInit: function(isReloading, isError) {
                this.reactivate();
            },
            fx: { height: "toggle", duration: 200 },
            initAjax: {url: $('#store_tree_panel').attr('data-url'),
                dataType: "json",
                timeout: 10000
            },
            onLazyRead: function(node){
                node.appendAjax(
                    {url: $('#store_tree_panel').attr('data-url'),
                        dataType: "json",
                        data: {"parent_id": node.data.key}
                    });
            },
            onActivate: function(node) {
                if(!node.data.unselectable) {
                    $('#choose_store_category_btn').attr('data-id',node.data.key).removeClass('hide');
                } else {
                    $('#choose_store_category_btn').addClass('hide');
                }
            }
        });
    });


    $('#choose_store_category_btn').click(function(){
        $('input[name="ebay_store_category_id"]').val($(this).attr('data-id'));
        var $help =  $('#store_help');
        $help.html('loading...');
        $.get($('#store_tree_panel').attr('data-path'), {'category_id' : $(this).attr('data-id')}, function(data) {
             if(data.hasOwnProperty('error')) {
                 alert(data.error);
             } else {
                 $help.html(data.path);
             }
        });

        $('#select_store_category_modal').modal('hide');


        return false;
    });

	$('#tab-listings .item_specific_button').click(function(){
		$('#is_' + $(this).attr('data-id')).removeClass('hide');
		$(this).addClass('hide');
		return false;
	});

	$('#tab-listings .isbr').click(function(){
		$('#is_' + $(this).attr('data-id')).addClass('hide');
		$('#isb_' + $(this).attr('data-id')).removeClass('hide');

		$('input[checked="checked"]', $('#is_' + $(this).attr('data-id'))).prop('checked', false);


		return false;
	});


	$('#tab-listings select[name="listing_type"]').change(function() {
	    if($(this).val() == 'FixedPriceItem') {
	     $('#tab-listings select[name="auction_duration"]').addClass('hide');
	     $('#tab-listings select[name="fixed_duration"]').removeClass('hide');

	     $('#auction-binp-group').addClass('hide');
	     $('#fixedprice-binp-group').removeClass('hide');

	    } else if($(this).val() == 'Chinese') {
	     $('#tab-listings select[name="fixed_duration"]').addClass('hide');
	     $('#tab-listings select[name="auction_duration"]').removeClass('hide');
	     $('#fixedprice-binp-group').addClass('hide');
	     $('#auction-binp-group').removeClass('hide');
	    }
	 });


	$('#tab-listings input[name="start_price_option"]').change(function() {
		  if($(this).val() == 'product_price') {
		   $('#use-custom-price').addClass('hide');
		   $('#use-price-extra').addClass('hide');
		  } else if($(this).val() == 'price_extra') {
		   $('#use-custom-price').addClass('hide');
		   $('#use-price-extra').removeClass('hide');
		  } else if($(this).val() == 'custom_price') {
		   $('#use-price-extra').addClass('hide');
		   $('#use-custom-price').removeClass('hide');
		  }
	 });

	$('#tab-listings input[name="bin_option"]').change(function() {
		  if($(this).val() == 'product_price') {
		   $('#use-bin-custom-price').addClass('hide');
		   $('#use-bin-price-extra').addClass('hide');
		  } else if($(this).val() == 'price_extra') {
		   $('#use-bin-custom-price').addClass('hide');
		   $('#use-bin-price-extra').removeClass('hide');
		  } else if($(this).val() == 'custom_price') {
		   $('#use-bin-price-extra').addClass('hide');
		   $('#use-bin-custom-price').removeClass('hide');
		  }
	 });

	$('#tab-listings input[name="fixed_price_option"]').change(function() {
		  if($(this).val() == 'product_price') {
		   $('#use-custom-fixedprice').addClass('hide');
		   $('#use-fixedprice-extra').addClass('hide');
		  } else if($(this).val() == 'price_extra') {
		   $('#use-custom-fixedprice').addClass('hide');
		   $('#use-fixedprice-extra').removeClass('hide');
		  } else if($(this).val() == 'custom_price') {
		   $('#use-fixedprice-extra').addClass('hide');
		   $('#use-custom-fixedprice').removeClass('hide');
		  }
	 });

	$('#tab-listings input[name="bin_enabled"]').change(function() {
	  if($(this).is(':checked')) {
	   $('#bin_options').removeClass('hide');
	  } else {
	   $('#bin_options').addClass('hide');
	  }
	 });

	$('#tab-listings select[name="returns_accepted"]').change(function() {
		 if($(this).val() == 'ReturnsNotAccepted') {
			 $('#returns_within').addClass('hide');
			 $('#refunds').addClass('hide');
			 $('#shippingcost_paidby').addClass('hide');
			 $('#return_policy_details').addClass('hide');
		 } else {
			 $('#returns_within').removeClass('hide');
			 $('#refunds').removeClass('hide');
			 $('#shippingcost_paidby').removeClass('hide');
			 $('#return_policy_details').removeClass('hide');
		 }
	});


	changeShipping($('#tab-listings select[name="shipping_type"]'));
	$('#tab-listings select[name="shipping_type"]').change(function() {
		changeShipping($(this));
	 });


	$('#tab-listings input[name="has_international_shipping"]').change(function() {

        if($(this).is(':checked')) {
            $('#shipping-tabs a:last').show();
        } else if(!($('#epz select[name="shipping_type"]').val() == 'FlatDomesticCalculatedInternational' || $('#epz select[name="shipping_type"]').val() == 'CalculatedDomesticFlatInternational')) {
            $('#shipping-tabs a:last').hide();
            $('#shipping-tabs a:first').tab('show');
        }

	 });


	var $weightData = [{name : '', label : '', lbs : 0, oz : 0},
	                   {name : 'Plastic Pen', label : '1 oz.', lbs : 0, oz : 1},
	                   {name : 'Deck of Cards', label : '3 oz.', lbs : 0, oz : 3},
	                   {name : 'CD Case', label : '4 oz.', lbs : 0, oz : 4},
	                   {name : 'Smart Phone', label : '5 oz.', lbs : 0, oz : 5},
	                   {name : 'DVD Case', label : '6 oz.', lbs : 0, oz : 6},
	                   {name : 'Soda Can (14 oz.)', label : '14 oz.', lbs : 0, oz : 14},
	                   {name : 'Basketball', label : '1 lb. 6oz.', lbs : 1, oz : 6},
	                   {name : 'Toaster', label : '5 lbs.', lbs : 5, oz : 0},
	                   {name : 'Six Pack Soda', label : '5 lbs. 4oz.', lbs : 5, oz : 4},
	                   {name : 'One Clay Brick', label : '5 lbs. 10oz.', lbs : 5, oz : 10}];

	var $oz = $('#package_weight input[name="weight_minor"]').val();
	var $lbs = $('#package_weight input[name="weight_major"]').val();
	var $wl = 1;
	for(i=0; i<$weightData.length; i++) {
		var $w = $weightData[i];
		if($oz == $w.oz && $lbs == $w.lbs) {
			$wl = i + 1;
			$('#package_weight img').attr('src', 'view/image/channels/pack' + $wl + '.png');
	    	$('#package_weight .weight_prew_label div').html($w.name);
	    	$('#package_weight .weight_prew_label span').html($w.label);
	    	$('#package_weight input[name="weight_minor"]').val($w.oz);
	    	$('#package_weight input[name="weight_major"]').val($w.lbs);
		}
	}

	$(".eprice").each(function(){
		$(this).priceField();
	});

	$(".enumeric").each(function(){
		$(this).numericField();
	});

    $(".eint").each(function(){
        $(this).intField();
    });

	$('a.item-specifics-remove-button').click(function(){
		$($(this).attr('href')).show();
		$('select', $(this).closest('div.form-group')).val('0');
		$(this).closest('div.form-group').hide();
		return false;
	});

	$('select.i-s-s').change(function(){
		$('input.c-v', $(this).closest('div.form-group')).hide();
		if($(this).val() == '00') {
			$('input.c-v', $(this).closest('div.form-group')).show();
		}
	});

	$('a.item-specifics-button').click(function(){
		var itemSpecific = $($(this).attr('href'));
		itemSpecific.show();
		$(this).hide();
		return false;
	});
	$('#add-custom-attribute-btn').click(function(){
		$('#add_custom_attribute_modal div.form-group').removeClass('has-error');
		$('#custom-attribute-name').val('');
		$('#add_custom_attribute_modal').modal('show');
		return false;
	});

	$('#save_custom_attribute').click(function(){
		var parent = $('#add-custom-attribute-btn').closest(".form-group");
		var name = $('#custom-attribute-name').val();
		var id = new Date().getTime();

		if(name.length) {
			if(name.length > 65) {
				$('#add_custom_attribute_modal div.text-danger').html('max Name length 65 chars');
				$('#add_custom_attribute_modal div.form-group').addClass('has-error');
				return false;
			}


			$('#custom-attribute-name').val('');
			var ca = $("#custom-attribute-template").tmpl({ "id" : id, "name" : name});
			$('a.item-specifics-remove-button', ca).click(function(){
				ca.remove();
				return false;
			});
			$('select.i-s-s', ca).change(function(){
				$('input.c-v', $(this).closest('div.form-group')).hide();
				if($(this).val() == '00') {
					$('input.c-v', $(this).closest('div.form-group')).show();
				}
			});

			parent.before(ca);

			$('#add_custom_attribute_modal div.form-group').removeClass('has-error');
			$('#add_custom_attribute_modal div.text-danger').html('');

			$('#add_custom_attribute_modal').modal('hide');
		} else {
			$('#add_custom_attribute_modal div.text-danger').html('Name is required');
			$('#add_custom_attribute_modal div.form-group').addClass('has-error');
		}

		return false;
	});




	$(window)
		.scroll(function(){
			$(".wizard").each(function() {

				var el             = $(this),
					offset         = el.offset(),
					scrollTop      = $(window).scrollTop();


				if (scrollTop > offset.top) {
					if(!el.hasClass('wizard-fixed')) {
						el.addClass('wizard-fixed');
						el.data('offest', offset);
					}

				}

				if(el.hasClass('wizard-fixed')) {
					var offset = el.data('offest');
					console.log('scrollTop: ' + scrollTop);
					console.log('offset.top: ' + offset.top);

					if (scrollTop < offset.top) {
						el.removeClass('wizard-fixed');
					}
				}
			});
		})
		.trigger("scroll");

});

function changeShipping($this) {
	if($this.val() == 'FlatDomesticCalculatedInternational' || $this.val() == 'CalculatedDomesticFlatInternational') {
        $('#shipping-tabs a:last').show();

		  if($this.val() == 'FlatDomesticCalculatedInternational') {
			  $('#domestic_calculated_shipping').addClass('hide');
			  $('#domestic_flat_shipping').removeClass('hide');

			  $('#international_calculated_shipping').removeClass('hide');
			  $('#international_flat_shipping').addClass('hide');

		  }

		  if($this.val() == 'CalculatedDomesticFlatInternational') {
			  $('#domestic_flat_shipping').addClass('hide');
			  $('#domestic_calculated_shipping').removeClass('hide');

			  $('#international_calculated_shipping').addClass('hide');
			  $('#international_flat_shipping').removeClass('hide');
		  }

		  $('#dimensions').removeClass('hide');
		  $('#package_type').removeClass('hide');
		  $('#package_costs').removeClass('hide');
		  $('#package_weight').removeClass('hide');


	  } else {

		  if(!$('#tab-listings input[name="has_international_shipping"]').is(':checked')) {
              $('#shipping-tabs a:last').hide();
              $('#shipping-tabs a:first').tab('show');
		  }

		  if($this.val() == 'Flat') {
			  $('#domestic_calculated_shipping').addClass('hide');
			  $('#domestic_flat_shipping').removeClass('hide');

			  $('#international_calculated_shipping').addClass('hide');
			  $('#international_flat_shipping').removeClass('hide');
			  $('#dimensions').addClass('hide');
			  $('#package_costs').addClass('hide');
			  $('#package_weight').addClass('hide');
			  $('#package_type').addClass('hide');

		  }

		  if($this.val() == 'Calculated') {
			  $('#domestic_flat_shipping').addClass('hide');
			  $('#domestic_calculated_shipping').removeClass('hide');

			  $('#international_calculated_shipping').removeClass('hide');
			  $('#international_flat_shipping').addClass('hide');
			  $('#dimensions').removeClass('hide');
			  $('#package_costs').removeClass('hide');
			  $('#package_weight').removeClass('hide');
			  $('#package_type').removeClass('hide');
		  }

	  }
}





$(function() {
    (function( $ ) {


//$.widget( "ui.combobox", {
//        _create: function() {
//            var self = this,
//                select = this.element.hide(),
//                selected = select.children( ":selected" ),
//                value = selected.val() ? selected.text() : "";
//
//
//
//                var input = this.input = $( "#" + $(this.element).attr('data-input-id'))    // your input box
//                .insertAfter( select )
//                .autocomplete({
//                    delay: 0,
//                    minLength: 0,
//                    source: function( request, response ) {
//                        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
//                        response( select.children( "option" ).map(function() {
//                            var text = $( this ).text();
//                            if ( this.value && ( !request.term || matcher.test(text) ) )
//                                return {
//                                    label: text.replace(
//                                        new RegExp(
//                                            "(?![^&;]+;)(?!<[^<>]*)(" +
//                                            $.ui.autocomplete.escapeRegex(request.term) +
//                                            ")(?![^<>]*>)(?![^&;]+;)", "gi"
//                                        ), "<strong>$1</strong>" ),
//                                    value: text,
//                                    option: this
//                                };
//                        }) );
//                    },
//                    select: function( event, ui ) {
//                        ui.item.option.selected = true;
//                        self._trigger( "selected", event, {
//                            item: ui.item.option
//                        });
//                    },
//                    change: function( event, ui ) {
//                        if ( !ui.item ) {
//                        var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
//                            valid = false;
//                            select.children( "option" ).each(function() {
//                            if ( $( this ).text().match( matcher ) ) {
//                            this.selected = valid = true;
//                            return false;
//                            }
//                            });
//                        }
//                    }
//                })
//                .addClass( "ui-widget ui-widget-content ui-corner-left" );
//
//            input.data( "autocomplete" )._renderItem = function( ul, item ) {
//                return $( "<li></li>" )
//                    .data( "item.autocomplete", item )
//                    .append( "<a>" + item.label + "</a>" )
//                    .appendTo( ul );
//            };
//
//            this.button = $( "<button type='button'>&nbsp;</button>" )
//                .attr( "tabIndex", -1 )
//                .attr( "title", "Show All Items" )
//                .insertAfter( input )
//                .button({
//                    icons: {
//                        primary: "ui-icon-triangle-1-s"
//                    },
//                    text: false
//                })
//                .removeClass( "ui-corner-all" )
//                .addClass( "ui-corner-right ui-button-icon" )
//                .click(function() {
//                    if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
//                        input.autocomplete( "close" );
//                        return;
//                    }
//                    $( this ).blur();
//                    input.autocomplete( "search", "" );
//                    input.focus();
//                });
//        },
//
//        destroy: function() {
//            this.input.remove();
//            this.button.remove();
//            this.element.show();
//            $.Widget.prototype.destroy.call( this );
//        }
//    });


})( jQuery );

 $('.combobox').each(function(){
	 $(this).combobox();
 })


});