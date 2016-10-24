$(document).ready(function() {
	
	$('a.tgb').click(function(){
		if($($(this).attr('href')).is(":visible")) {
			$($(this).attr('href')).hide();
			$(this).html('Show');
			$('.imp_range', $(this).parent().parent()).addClass('hide');
		} else {
			$($(this).attr('href')).show();
			$(this).html('Hide');
			$('.imp_range', $(this).parent().parent()).removeClass('hide');
		}
		return false;
	});

    $('#import_orders_btn').click(function() {
        var btn = $(this);
        var label = btn.html();
        btn.attr('disabled','disabled').html('Loading...');
        var numberOfDays = $('#numberOfDays').val();
        $.get(btn.attr('data-action'), {'action' : 'order_import', 'numberOfDays' : numberOfDays}, function() {
            btn.removeAttr('disabled').html(label);
            refreshJobs();
        });
        return false;
    });
	
	$('#import_products_btn').click(function() {
        var btn = $(this);
        var label = btn.html();
        btn.attr('disabled','disabled').html('Loading...');
        $.get(btn.attr('data-action'), {'action' : 'product_import'}, function() {
            btn.removeAttr('disabled').html(label);
            refreshJobs();
        });
		return false;
	});

    $('#import_feedback_btn').click(function() {
        var btn = $(this);
        var label = btn.html();
        btn.attr('disabled','disabled').html('Loading...');
        $.get(btn.attr('data-action'), {'action' : 'feedback_import'}, function() {
            btn.removeAttr('disabled').html(label);
            refreshJobs();
        });
        return false;
    });

    $('#sync_items_btn').click(function() {
        var btn = $(this);
        var label = btn.html();
        btn.attr('disabled','disabled').html('Loading...');
        $.get(btn.attr('data-action'), {'action' : 'syncronize_items'}, function() {
            btn.removeAttr('disabled').html(label);
            refreshJobs();
        });
        return false;
    });

    $('#update_inventory_btn').click(function() {
        var btn = $(this);
        var label = btn.html();
        btn.attr('disabled','disabled').html('Loading...');
        $.get(btn.attr('data-action'), {'action' : 'update_inventory_items'}, function() {
            btn.removeAttr('disabled').html(label);
            refreshJobs();
        });
        return false;
    });

    $('#import_links_btn').click(function() {
        var btn = $(this);
        var label = btn.html();
        btn.attr('disabled','disabled').html('Loading...');
        $.get(btn.attr('data-action'), {'action' : 'links_import'}, function() {
            btn.removeAttr('disabled').html(label);
            refreshJobs();
        });
        return false;
    });
});