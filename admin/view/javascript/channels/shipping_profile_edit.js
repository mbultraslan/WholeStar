/**
 * Created by Ion on 6/2/2015.
 */

$( document ).ready(function() {
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

    $('#weight_slider').slider({
        range: "min",
        min: 1,
        max: 11,
        value: $wl,
        slide: function( event, ui ) {
            $('#package_weight img').attr('src', 'view/image/channels/pack' + ui.value + '.png');
            var $w = $weightData[ui.value - 1];
            $('#package_weight .weight_prew_label div').html($w.name);
            $('#package_weight .weight_prew_label span').html($w.label);
            $('#package_weight input[name="weight_minor"]').val($w.oz);
            $('#package_weight input[name="weight_major"]').val($w.lbs);

        }
    });

    $('select[name="site_id"]').change(function() {
        var s = $(this);
        $('div.pr').append('<div id="ebay_loading" style="position: absolute; width: 100%; height: 100%; opacity: 0.3; left: 0px; top: 0px; z-index: 1000000; background: url(http://localhost/ebay/admin/view/image/channels/ajax-loader.gif) 50% 50% no-repeat black;"></div>');
        setTimeout(function(){
            window.location = $('#edit-shipping-form').attr('action') + '&site_id=' + s.val();
        }, 1000);


    });

    changeShipping($('select[name="service_type"]'));
    $('select[name="service_type"]').change(function() {
        changeShipping($(this));
    });

    $('input[name="has_international_shipping"]').change(function() {
        if($(this).is(':checked')) {
            $('#international_shipping_services').removeClass('hide');
        } else if(!($('select[name="service_type"]').val() == 'FlatDomesticCalculatedInternational' || $('select[name="service_type"]').val() == 'CalculatedDomesticFlatInternational')) {
            $('#international_shipping_services').addClass('hide');
        }
    });

    $('div.ssl button.remove').click(function() {
        $(this).closest('div.ssl').remove();
    });

    $('a.save-btn').click(function() {
        //$('div.pr').append('<div id="ebay_loading" style="position: absolute; width: 100%; height: 100%; opacity: 0.3; left: 0px; top: 0px; z-index: 1000000; background: url(http://localhost/ebay/admin/view/image/channels/ajax-loader.gif) 50% 50% no-repeat black;"></div>');
        setTimeout(function(){
            $('#edit-shipping-form').submit();
        }, 1000);

        return false;
    });



    $('#add-domestic-service').click(function(){
        var container = $('#domestic-services');
        var count = $('#domestic-services div.ssl').length;

        var template = $('<div class="ssl">' + $('#domestic-template').html() + '</div>')
        template.appendTo(container);

        $('select.flat', template).attr('name', "services[domestic_service][" + count + "][flat]");
        $('select.calculated', template).attr('name', "services[domestic_service][" + count + "][calculated]");
        $('input.cost', template).attr('name', "services[domestic_service][" + count + "][shipping_cost]");
        $('input.additional_cost', template).attr('name', "services[domestic_service][" + count + "][additional_cost]");
        $('input.free_shipping', template).attr('name', "services[domestic_service][" + count + "][free_shipping]");

        $('select.flat', template).rules("add", {
            required: true
        });

        $('select.calculated', template).rules("add", {
            required: true
        });

        $(".eprice", template).each(function(){
            $(this).priceField();
        });

        $(".enumeric", template).each(function(){
            $(this).numericField();
        });

        $('button.remove', template).click(function() {
            $(this).closest('div.ssl').remove();
        });

        return false;
    });

    $('#add-international-service').click(function(){
        var container = $('#international-services');
        var count = $('#international-services div.ssl').length;

        var template = $('<div class="ssl">' + $('#international-template').html() + '</div>')
        template.appendTo(container);

        $('select.flat', template).attr('name', "services[international_service][" + count + "][flat]");
        $('select.calculated', template).attr('name', "services[international_service][" + count + "][calculated]");
        $('input.cost', template).attr('name', "services[international_service][" + count + "][shipping_cost]");
        $('input.additional_cost', template).attr('name', "services[international_service][" + count + "][additional_cost]");
        $('input.free_shipping', template).attr('name', "services[international_service][" + count + "][free_shipping]");

        $('input.shipping_location', template).each(function(){
            $(this).attr('name', "services[international_service][" + count + "][shipping_location]");
        });

        $('select.flat', template).rules("add", {
            required: true
        });


        $('select.calculated', template).rules("add", {
            required: true
        });

        $('input.shipping_location', template).rules( "add", {
            required: true,
            minlength: 1,
            messages: {
                required: "Please select at least 1 location",
                minlength: "Please select at least {0} location"
            }
        });

        $(".eprice", template).each(function(){
            $(this).priceField();
        });

        $(".enumeric", template).each(function(){
            $(this).numericField();
        });

        $('button.remove', template).click(function() {
            $(this).closest('div.ssl').remove();
        });

        return false;
    });

    $("#edit-shipping-form").validate({
        rules: {
            name: "required",
            postal_code: {
                required: function(element) {
                    return $("#location").val() == '';
                }
            },
            location: {
                required: function(element) {
                    return $("#postal_code").val() == '';
                }
            },

            dispatch_time_max: "required",
            "services[domestic_service][0][flat]" : "required",
            "services[domestic_service][0][calculated]" : "required",
            "services[international_service][0][flat]" : "required",
            "services[international_service][0][calculated]" : "required",
            "services[international_service][0][shipping_location][]" : {
                required:true,
                minlength: 1
            }
        },
        messages: {
            name: "Please enter profile name",
            postal_code: {
                required: 'Location or Zip/Postcode is required.'
            },
            location: {
                required: 'Location or Zip/Postcode is required.'
            },
            dispatch_time_max: {
                required: 'Handling Time min 1 day.'
            },
            "services[international_service][0][shipping_location][]" : "Please select at least 1 location"
        },
        errorPlacement: function (error, element) {
            var type = $(element).attr("type");
            if (type === "checkbox") {
                error.appendTo(element.parent().parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

});

function changeShipping($this) {
    if($this.val() == 'FlatDomesticCalculatedInternational' || $this.val() == 'CalculatedDomesticFlatInternational') {
        $('#international_shipping_services').removeClass('hide');

        if($this.val() == 'FlatDomesticCalculatedInternational') {
            $('#domestic-services select.calculated').addClass('hide').val('');
            $('#domestic-services select.flat').removeClass('hide');

            $('#international-services select.flat').addClass('hide').val('');
            $('#international-services select.calculated').removeClass('hide');
        }

        if($this.val() == 'CalculatedDomesticFlatInternational') {
            $('#domestic-services select.flat').addClass('hide').val('');
            $('#domestic-services select.calculated').removeClass('hide');

            $('#international-services select.calculated').addClass('hide').val('');
            $('#international-services select.flat').removeClass('hide');
        }

        $('#dimensions').removeClass('hide');
        $('#package_type').removeClass('hide');
        $('#package_costs').removeClass('hide');
        $('#package_weight').removeClass('hide');


    } else {

        if(!$('input[name="has_international_shipping"]').is(':checked')) {
            $('#international_shipping_services').addClass('hide');
        }

        if($this.val() == 'Flat') {
            $('select.flat').removeClass('hide');
            $('select.calculated').addClass('hide').val('');


            $('#dimensions').addClass('hide');
            $('#package_costs').addClass('hide');
            $('#package_weight').addClass('hide');
            $('#package_type').addClass('hide');

        }

        if($this.val() == 'Calculated') {
            $('select.flat').addClass('hide').val('');
            $('select.calculated').removeClass('hide');


            $('#dimensions').removeClass('hide');
            $('#package_costs').removeClass('hide');
            $('#package_weight').removeClass('hide');
            $('#package_type').removeClass('hide');
        }

    }
}