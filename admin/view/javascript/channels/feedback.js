$( document ).ready(function() {
    w = $('<div id="o"></div><div>Please wait...</div>');
    cid = null;


    /******** SETTINGS DATA TABLE ********/
    oTable = $('#feedback_list_table').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": $('#feedback_list_table').attr('data-url'),
        "sServerMethod": "POST",
        "sPaginationType": "full_numbers",
        "iDisplayLength": 25,
        "order": [[ 4, "desc" ]],

        "oLanguage": {
            "sLengthMenu": "Display _MENU_ feedback per page",
            "sZeroRecords": "Nothing found - sorry",
            "sInfo": "Showing _START_ to _END_ of _TOTAL_ feedback",
            "sInfoEmpty": "Showing 0 to 0 of 0 feedback",
            "sInfoFiltered": "(filtered from _MAX_ total feedback)"
        },

        "sDom": 'C<"clear">lfrtip',

        "aoColumns":
            [
                {
                    "sWidth": "1%",
                    "sClass": "left",
                    "bSortable": false,
                    "fnRender" : function(o){
                        return '<img src="view/image/channels/' + o.aData[0] +'"/>';
                    }
                },

                {
                    "sWidth": "50%",
                    "sClass": "left",
                    "bSortable": false,
                    "fnRender" : function(o){
                        var obj = jQuery.parseJSON(o.aData[1]);
                        var html = '<div class="f-comment">' + obj.comment_text +'</div>';
                        html += '<div class="f-title">' + obj.item_title +'</div>';
                        html += '<div class="f-id">(#' + obj.item_id +')</div>';
                        return html;
                    }
                },
                {
                    "sWidth": "5%",
                    "bSortable": false ,
                    "sClass": "left",
                    "fnRender" : function(o){
                        var obj = jQuery.parseJSON(o.aData[2]);
                        var html = '<div class="f-buyer">Buyer: <a target="_blank" href="http://www.ebay.com/usr/' + obj.user + '">' + obj.user +'</a> (' + obj.score + ')</div>';
                        if(obj.item_price > 0) {
                            html += '<div class="f-item-price">' + obj.item_price + ' ' + obj.item_price_currency +'</div>';
                        }
                        return html;
                    }
                },
                {
                    "sWidth": "10%",
                    "bSortable": false ,
                    "sClass": "right",
                    "fnRender" : function(o){
                        var obj = jQuery.parseJSON(o.aData[3]);
                        var html = '<div class="f-comment-time">' + obj.comment_time +'</div>';
                        html += '<div class="f-item-view"><a target="_blank" href="http://www.ebay.com/itm/' + obj.item_id + '">View Item</a></div>';
                        return html;
                    }
                },
            ],
        "fnDrawCallback": function ( ) {

        }
    });

});