<?php echo $header; ?>

<style>
    .column {
        width: 50%;
        float: left;
        padding-bottom: 100px;
    }
    .portlet {
        margin: 0 1em 1em 0;
        padding: 0.3em;
    }
    .portlet-header {
        padding: 0.2em 0.3em;
        margin-bottom: 0.5em;
        position: relative;
    }
    .portlet-toggle {
        position: absolute;
        top: 50%;
        right: 0;
        margin-top: -8px;
    }

    .portlet-refresh {
        position: absolute;
        top: 50%;
        right: 0;
        margin-top: -8px;
        margin-right: 18px;
    }

    .portlet-content {
        padding: 0.4em;
    }
    .portlet-placeholder {
        border: 1px dotted black;
        margin: 0 1em 1em 0;
        height: 100px;
    }
</style>



<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($ebay_global_message) { ?>
    <div class="attention"><?php echo $ebay_global_message; ?></div>
    <?php } ?>
    <?php if ($error_attention) { ?>
    <div class="attention"><?php echo $error_attention; ?></div>
    <?php } ?>

    <div class="box">
        <div class="heading">
            <h1><img src="view/image/channels/ebay.png" alt="" /> <?php echo $heading_title; ?> - <?php echo $version; ?></h1>
        </div>
        <div class="content">

            <div class="vtabs">
                <a href="<?php echo $tab_dashboard; ?>"><img src="view/image/channels/dashboard.png"/>Dashboard</a>
                <a href="<?php echo $tab_products; ?>"><img src="view/image/channels/listings.png"/>Products</a>
                <a href="<?php echo $tab_selling; ?>"><img src="view/image/channels/sell_32.png"/> <div>My Selling</div></a>
                <a href="<?php echo $tab_listing_profiles; ?>"><img src="view/image/channels/profile_listing.png"/>Listings</a>

                <a href="<?php echo $tab_shipping_profiles; ?>" class="selected"><img src="view/image/channels/profile_shipping.png"/>Shipping</a>
                <a href="<?php echo $tab_listing_profiles; ?>"><img src="view/image/channels/creditcard.png"/>Payment</a>
                <a href="<?php echo $tab_listing_profiles; ?>"><img src="view/image/channels/redirect.png"/>Returns</a>

                <a href="<?php echo $tab_ebay_account; ?>"><img src="view/image/channels/accounts.png"/>Account</a>
                <a href="<?php echo $tab_ebay_syncronize; ?>"><img src="view/image/channels/redirect.png" style="margin-bottom: 9px;margin-top: 8px;"/>Import</a>
                <a href="<?php echo $tab_ebay_templates; ?>"><img src="view/image/channels/graphic.png" />Templates</a>
                <a href="<?php echo $tab_feedback; ?>"><img src="view/image/channels/feedback.png" />Feedback</a>
                <a href="<?php echo $tab_ebay_settings; ?>"><img src="view/image/channels/settings.png" />Settings</a>
                <a href="<?php echo $tab_ebay_logs; ?>"><img src="view/image/channels/history.png" /><br>Logs</a>
            </div>
            <div class="vtabs-content">
                <h2 class="toolbar">
                    <span>Shipping profile</span>
                    <a href="<?php echo $shipping_profiles_edit; ?>" class="button button-primary">Add shipping profile</a>
                </h2>

                <table id="list_table" class="list" data-url="<?php echo $shipping_profiles_list; ?>"  width="100%">
                    <thead>
                    <tr class="header-t">
                        <th class="center">Name</th>
                        <th class="center">Is Default?</th>
                        <th class="center"></th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
        </div>
    </div>
</div>
</div>

<script>

    $( document ).ready(function() {
        oTable = $('#list_table').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": $('#list_table').attr('data-url'),
            "sServerMethod": "POST",
            "sPaginationType": "full_numbers",
            "iDisplayLength": 25,

            "oLanguage": {
                "sLengthMenu": "Display _MENU_ profiles per page",
                "sZeroRecords": "Nothing found - sorry",
                "sInfo": "Showing _START_ to _END_ of _TOTAL_ profiles",
                "sInfoEmpty": "Showing 0 to 0 of 0 profiles",
                "sInfoFiltered": "(filtered from _MAX_ total profiles)"
            },

            "sDom": 'C<"clear">lfrtip',

            "aoColumns":
                    [
                        {
                            "sWidth": "85%",
                            "sClass": "left"
                        },
                        {
                            "sWidth": "5%",
                            "sClass": "left"
                        },
                        {
                            "sWidth": "10%",
                            "sClass": "left",
                            "fnRender" : function(o){
                                return   '<a href="<?php echo $shipping_profiles_edit; ?>&id=' + o.aData[2] + '" class="button button-secondary" data-id="'+ o.aData[2] +'"><span class="ui-button-icon-secondary ui-icon ui-icon-pencil"></span></a>'
                                       + '<a href="#" style="margin-left: 5px!important;" class="button button-danger remove" data-url="<?php echo $shipping_profiles_delete; ?>" data-id="'+ o.aData[2] +'"><span class="ui-button-icon-secondary ui-icon ui-icon-trash"></span></a>';
                            }
                        }
                    ],
            "fnDrawCallback": function ( ) {
                $('#list_table a.remove').click(function() {
                    var btn = $(this);
                    if( confirm('Are you sure you want to delete this profile?') ){
                        $.post( btn.attr('data-url'), {id : btn.attr('data-id')}, function(){
                            oTable.fnDraw();
                        });
                    }
                    return false;
                });
            }
        });

    });

</script>


<div id="jobs_tab" data-action="<?php echo $jobs_service; ?>" class="hide">
    <div class="th">
        <div id="circleG"><div class="cg1 circleG"></div></div>
        <span>Running now <b>0</b> job(s)</span>
    </div>
</div>

<?php echo $footer; ?>


