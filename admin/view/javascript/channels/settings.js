/**
 * Created by Ion on 5/13/2015.
 */

$( document ).ready(function() {
    $('.settings-header a').click(function(){
        if($(this).parent().hasClass('set-selected')) {
            $('.settings-header').removeClass('set-selected');
            $('.settings-header a').html('Show');
            $(this).html('Show');
        } else {
            $('.settings-header a').html('Show');
            $(this).html('Hide');
            $('.settings-header').removeClass('set-selected');
            $(this).parent().addClass('set-selected')
        }
        return false;
    });


    $('button.save-settings-btn').click(function() {
        var btn = $(this);
        btn.attr('disabled','disabled').html(btn.attr('data-loading'));
        window[btn.attr('data-callback')](btn);
        return false;
    });


    $('button.add-tag').click(addNewFilter);
    $('button.remove-tag').click(removeFilter);

});

function removeFilter(){
    $(this).closest('tr').remove();
    if($('#tag-filters tr.tag-row').length < 1) {
        addNewFilter();
    }

    return false;
}

function addNewFilter(){
    var row = $('<tr class="tag-row"><td><input class="tag-name" type="text"></td><td><input class="tag-attribute" type="text"></td><td><input class="tag-value" type="text"></td><td><button class="btn btn-danger btn-xs remove-tag"><i class="fa fa-times"></i></button></td></tr>');
    $('#tag-filters tr:last').after(row);
    $('button.remove-tag', row).click(removeFilter);
    return false;
}

function save_orders_settings(btn) {
    $('#ebay_loading').remove();
    $.post(btn.attr('data-action'),
        {
            'order_settings[order_store_id]' : $('#os-store').val(),
            'order_settings[order_customer_group_id]' : $('#os-customer-group').val(),
            'order_settings[import_compleded]' : $('#os-import-compleded:checked').length,
            'order_settings[default_status]' : $('#os-default_status').val(),
            'order_settings[order_subtract_stock]' : $('#os-import_subtract_stock').val(),
            'order_settings[order_tax_class_id]' : $('#os-order_tax_class_id').val(),
            'order_settings[completed_status]' : $('#os-completed_status').val(),
            'order_settings[shipped_status]' : $('#os-shipped_status').val(),
            'order_settings[inprocess_status]' : $('#os-inprocess_status').val(),
            'order_settings[active_status]' : $('#os-active_status').val(),
            'order_settings[cancelled_status]' : $('#os-cancelled_status').val(),
            'order_settings[cancelpending_status]' : $('#os-cancelpending_status').val(),
            'order_settings[inactive_status]' : $('#os-inactive_status').val(),
            'order_settings[invalid_status]' : $('#os-invalid_status').val(),
            'order_settings[order_hook_url]' : $('#os-hook-url').val(),
            'order_settings[order_import_older]' : $('#os-import_older_orders').val(),
            'order_settings[customer_import_enabled]' : $('#os-customer_import_enabled:checked').length,
        },
        function() {
            btn.removeAttr('disabled').html(btn.attr('data-title'));
        });
}


function save_cron_settings(btn) {
    $('#ebay_loading').remove();
    $.post(btn.attr('data-action'),
        {
            'cron_settings[stock_and_price_interval]' : $('#stock_and_price_interval').val(),
            'cron_settings[stock_and_price_interval_enabled]' : $('#stock_and_price_interval_enabled:checked').length,

            'cron_settings[orders_update_interval]' : $('#orders_update_interval').val(),
            'cron_settings[orders_update_enabled]' : $('#orders_update_enabled:checked').length,

            'cron_settings[syncronize_interval]' : $('#syncronize_interval').val(),
            'cron_settings[syncronize_enabled]' : $('#syncronize_enabled:checked').length,

            'cron_settings[feedback_enabled]' : $('#feedback_enabled:checked').length,

            'cron_settings[import_items_interval]' : $('#import_items_interval').val(),
            'cron_settings[import_items_enabled]' : $('#import_items_enabled:checked').length,

            'cron_settings[active_status]' : $('#os-active_status').val(),
            'cron_settings[cancelled_status]' : $('#os-cancelled_status').val(),
            'cron_settings[cancelpending_status]' : $('#os-cancelpending_status').val(),
            'cron_settings[inactive_status]' : $('#os-inactive_status').val(),
            'cron_settings[invalid_status]' : $('#os-invalid_status').val()
        },
        function() {
            btn.removeAttr('disabled').html(btn.attr('data-title'));
        });
}


function save_general_settings(btn) {
    $('#ebay_loading').remove();
    $.post(btn.attr('data-action'),
        {
            'general_settings[truncate_title_enabled]' : $('#truncate_title_enabled:checked').length,
            'general_settings[end_item_on_delete_enabled]' : $('#end_item_on_delete_enabled:checked').length,
            'general_settings[end_item_out_stock_enabled]' : $('#end_item_out_stock_enabled:checked').length,
            'general_settings[revise_title_enabled]' : $('#revise_title_enabled:checked').length,
            'general_settings[revise_description_enabled]' : $('#revise_description_enabled:checked').length,
            'general_settings[revise_sku_enabled]' : $('#revise_sku_enabled:checked').length,
            'general_settings[revise_paypalemail_enabled]' : $('#revise_paypalemail_enabled:checked').length,
            'general_settings[revise_dispatch_time_max_enabled]' : $('#revise_dispatch_time_max_enabled:checked').length,
            'general_settings[revise_listing_duration_enabled]' : $('#revise_listing_duration_enabled:checked').length,
            'general_settings[revise_listing_type_enabled]' : $('#revise_listing_type_enabled:checked').length,
            'general_settings[revise_postal_code_enabled]' : $('#revise_postal_code_enabled:checked').length,
            'general_settings[revise_primary_category_enabled]' : $('#revise_primary_category_enabled:checked').length,
            'general_settings[revise_payment_methods_enabled]' : $('#revise_payment_methods_enabled:checked').length,
            'general_settings[revise_picture_details_enabled]' : $('#revise_picture_details_enabled:checked').length,
            'general_settings[revise_item_specifics_enabled]' : $('#revise_item_specifics_enabled:checked').length,
            'general_settings[revise_condition_enabled]' : $('#revise_condition_enabled:checked').length,
            'general_settings[revise_return_policy_enabled]' : $('#revise_return_policy_enabled:checked').length,
            'general_settings[revise_shipping_details_enabled]' : $('#revise_shipping_details_enabled:checked').length,

            'general_settings[revise_price_enabled]' : $('#revise_price_enabled:checked').length,
            'general_settings[revise_quantity_enabled]' : $('#revise_quantity_enabled:checked').length,

            'general_settings[special_price_enabled]' : $('#special_price_enabled:checked').length,
            'general_settings[general_use_taxes_enabled]' : $('#general_use_taxes_enabled:checked').length,
            'general_settings[general_list_product_cover_image]' : $('#general_list_product_cover_image:checked').length,

            'general_settings[general_prevent_timeout]' : $('#general_prevent_timeout:checked').length,
            'general_settings[image_type]' : $('#image_type').val(),

            'general_settings[http_basic_autentification_username]' : $('#http_basic_autentification_username').val(),
            'general_settings[http_basic_autentification_password]' : $('#http_basic_autentification_password').val()
        },
        function() {
            btn.removeAttr('disabled').html(btn.attr('data-title'));
        });
}

function save_products_settings(btn) {
    $('#ebay_loading').remove();



    var updateFields = $('#update_product_fields').val();
    var update_only_stock_and_price = 0;
    var update_only_price = 0;
    var update_only_stock = 0;
    if(updateFields == 'stock_and_price') {
        update_only_stock_and_price = 1;
    } else if(updateFields == 'price') {
        update_only_price = 1;
    } else if(updateFields == 'stock') {
        update_only_stock = 1;
    }

    console.log(updateFields);


    var data =  {
        'product_settings[import_new_products]' : $('#import_new_products:checked').length,

        'product_settings[update_only_stock_and_price]' : update_only_stock_and_price,
        'product_settings[update_only_price]' : update_only_price,
        'product_settings[update_only_stock]' : update_only_stock,

        'product_settings[disable_ended_items]' : $('#disable_ended_items:checked').length,
        'product_settings[product_import_categories]' : $('#product_import_categories').val(),
        'product_settings[product_entries_per_page]' : $('#product_entries_per_page').val(),
        'product_settings[product_import_variations]' : $('#product_import_variations:checked').length,
        'product_settings[product_import_specifics]' : $('#product_import_specifics:checked').length
    };

    data['product_settings[product_filter_site]'] = '';
    $('input[name="site-filter"]:checked').each(function(idx, el){
        data['product_settings[product_filter_site][' + idx + ']'] = $(this).val();
    });

    $('#tag-filters tr.tag-row').each(function(idx, el){
        if($('.tag-name', el).val() && $('.tag-attribute', el).val() && $('.tag-value', el).val()) {
            data['product_settings[product_filter_tags][' + idx + '][tag]'] = $('.tag-name', el).val();
            data['product_settings[product_filter_tags][' + idx + '][attribute]'] = $('.tag-attribute', el).val();
            data['product_settings[product_filter_tags][' + idx + '][value]'] = $('.tag-value', el).val();
        } else {
            data['product_settings[product_filter_tags]'] = '';
        }
    });

    $.post(btn.attr('data-action'), data, function() {
        btn.removeAttr('disabled').html(btn.attr('data-title'));
    });
}


