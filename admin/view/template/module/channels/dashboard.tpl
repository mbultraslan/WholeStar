<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?> - <?php echo $version; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">

        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <?php if ($ebay_global_message) { ?>
        <div class="alert alert-success" role="alert">
            <?php echo $ebay_global_message; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <?php if ($error_attention) { ?>
        <div class="alert alert-warning" role="alert">
            <?php echo $error_attention; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>

        <?php } ?>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs tab-size-bigger">
            <li class="active"><a href="<?php echo $tab_dashboard; ?>" ><i class="fa fa-tachometer bigger-130"></i> <span class="bigger-110"> Dashboard</span></a></li>
            <li><a href="<?php echo $tab_products; ?>" ><i class="fa fa-gavel bigger-130"></i> <span class="bigger-110"> Products</span></a></li>
            <li><a href="<?php echo $tab_selling; ?>" ><i class="fa fa-shopping-cart bigger-130"></i> <span class="bigger-110"> My Selling</span></a></li>
            <li><a href="<?php echo $tab_listing_profiles; ?>" ><i class="fa fa-list bigger-130"></i> <span class="bigger-110"> Listings</span></a></li>
            <li><a href="<?php echo $tab_ebay_account; ?>" ><i class="fa fa-user bigger-130"></i> <span class="bigger-110"> Account</span></a></li>
            <li><a href="<?php echo $tab_ebay_syncronize; ?>" ><i class="fa fa-tasks bigger-130"></i> <span class="bigger-110"> Tasks</span></a></li>

            <li><a href="<?php echo $tab_ebay_templates; ?>" ><i class="fa fa-desktop bigger-130"></i> <span class="bigger-110"> Templates</span></a></li>

            <li><a href="<?php echo $tab_feedback; ?>" ><i class="fa fa-comments bigger-130"></i> <span class="bigger-110"> Feedback</span></a></li>
            <li><a href="<?php echo $tab_ebay_settings; ?>"><i class="fa fa-cogs bigger-130"></i> <span class="bigger-110"> Settings</span></a></li>
            <li><a href="<?php echo $tab_ebay_logs; ?>"><i class="fa fa-history bigger-130"></i> <span class="bigger-110"> Logs</span></a></li>
        </ul>

        <div  id="tab-content" class="tab-content pr">

            <div class="tab-content pr">



                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-sx-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="pull-right">
                                    <a href="#" class="btn-refresh" data-refresh="<?php echo $refresh_ebay_feedback;?>"><i class="fa fa-refresh"></i></a>
                                </div>
                                <h3 class="panel-title"><i class="fa fa-retweet"></i> eBay Feedback</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <th class="text-right">1 Months</th>
                                        <th class="text-right">6 Months</th>
                                        <th class="text-right">12 Months</th>
                                    </tr>
                                    <tr>
                                        <td>Positive</td>
                                        <td><img src="view/image/channels/iconPos_16x16.gif"/></td>
                                        <td align="right">
                                            <?php foreach ($dashboard['feedback_summary'] as $feedback_summary) { ?>
                                            <?php if($feedback_summary['name'] == 'positive' && $feedback_summary['period_in_days'] == 30) { ?>
                                            <?php echo $feedback_summary['count'];?>
                                            <?php }?>
                                            <?php }?>
                                        </td>
                                        <td align="right">
                                            <?php foreach ($dashboard['feedback_summary'] as $feedback_summary) { ?>
                                            <?php if($feedback_summary['name'] == 'positive' && $feedback_summary['period_in_days'] == 180) { ?>
                                            <?php echo $feedback_summary['count'];?>
                                            <?php }?>
                                            <?php }?>
                                        </td>
                                        <td align="right">
                                            <?php foreach ($dashboard['feedback_summary'] as $feedback_summary) { ?>
                                            <?php if($feedback_summary['name'] == 'positive' && $feedback_summary['period_in_days'] == 365) { ?>
                                            <?php echo $feedback_summary['count'];?>
                                            <?php }?>
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Neutral</td>
                                        <td><img src="view/image/channels/iconNeu_16x16.gif"/></td>
                                        <td align="right">
                                            <?php foreach ($dashboard['feedback_summary'] as $feedback_summary) { ?>
                                            <?php if($feedback_summary['name'] == 'neutral' && $feedback_summary['period_in_days'] == 30) { ?>
                                            <?php echo $feedback_summary['count'];?>
                                            <?php }?>
                                            <?php }?>
                                        </td>
                                        <td align="right">
                                            <?php foreach ($dashboard['feedback_summary'] as $feedback_summary) { ?>
                                            <?php if($feedback_summary['name'] == 'neutral' && $feedback_summary['period_in_days'] == 180) { ?>
                                            <?php echo $feedback_summary['count'];?>
                                            <?php }?>
                                            <?php }?>
                                        </td>
                                        <td align="right">
                                            <?php foreach ($dashboard['feedback_summary'] as $feedback_summary) { ?>
                                            <?php if($feedback_summary['name'] == 'neutral' && $feedback_summary['period_in_days'] == 365) { ?>
                                            <?php echo $feedback_summary['count'];?>
                                            <?php }?>
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Negative</td>
                                        <td><img src="view/image/channels/iconNeg_16x16.gif"/></td>
                                        <td align="right">
                                            <?php foreach ($dashboard['feedback_summary'] as $feedback_summary) { ?>
                                            <?php if($feedback_summary['name'] == 'negative' && $feedback_summary['period_in_days'] == 30) { ?>
                                            <?php echo $feedback_summary['count'];?>
                                            <?php }?>
                                            <?php }?>
                                        </td>
                                        <td align="right">
                                            <?php foreach ($dashboard['feedback_summary'] as $feedback_summary) { ?>
                                            <?php if($feedback_summary['name'] == 'negative' && $feedback_summary['period_in_days'] == 180) { ?>
                                            <?php echo $feedback_summary['count'];?>
                                            <?php }?>
                                            <?php }?>
                                        </td>
                                        <td align="right">
                                            <?php foreach ($dashboard['feedback_summary'] as $feedback_summary) { ?>
                                            <?php if($feedback_summary['name'] == 'negative' && $feedback_summary['period_in_days'] == 365) { ?>
                                            <?php echo $feedback_summary['count'];?>
                                            <?php }?>
                                            <?php }?>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="pull-right">
                                    <a href="#" class="btn-refresh" data-refresh="<?php echo $refresh_ebay_selling_summary;?>"><i class="fa fa-refresh"></i></a>
                                </div>
                                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Monthly selling limits</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th align="left">Your selling activity</th>
                                        <th align="right">Items</th>
                                        <th align="right">Amount</th>
                                    </tr>
                                    <tr>
                                        <td>You have sold</td>
                                        <td align="right"><?php echo $dashboard['selling_summary']['total_sold_count'];?> </td>
                                        <td align="right"><?php echo $dashboard['selling_summary']['total_sold_value_currency'];?> <?php echo $dashboard['selling_summary']['total_sold_value'];?></td>
                                    </tr>
                                    <tr>
                                        <td>You can list</td>
                                        <td align="right"><?php echo $dashboard['selling_summary']['quantity_limit_remaining'];?></td>
                                        <td align="right"><?php echo $dashboard['selling_summary']['amount_limit_remaining_currency'];?> <?php echo $dashboard['selling_summary']['amount_limit_remaining'];?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> Ebay Sales last 30 days</h3>
                            </div>
                            <div class="panel-body">
                                <div id="orders_sell_div" data-url="<?php echo $refresh_ebay_selling_orders;?>"></div>
                            </div>
                        </div>



                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-sx-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="pull-right">
                                    <a href="#" class="btn-refresh" data-refresh="<?php echo $refresh_ebay_seller_rating;?>"><i class="fa fa-refresh"></i></a>
                                </div>
                                <h3 class="panel-title"><i class="fa fa-star-half-o"></i> Average Detailed Seller Ratings</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th class="text-right"></th>
                                        <th class="text-right">30 Days</th>
                                        <th class="text-right"></th>
                                        <th class="text-right">52 Weeks</th>
                                        <th class="text-right"></th>
                                    </tr>

                                    <?php foreach ($dashboard['seller_rating_summary'] as $seller_rating_summary) { ?>
                                    <tr>
                                    <th align="left"><?php echo $seller_rating_summary['rating_detail'];?> </th>
                                    <td align="right">
                                    <div class="rate" data-score="<?php echo $seller_rating_summary['ThirtyDays']['rating'];?>"></div>
                                    </td>
                                    <th align="left">
                                    <?php echo $seller_rating_summary['ThirtyDays']['rating_count'];?>
                                    </th>

                                    <td align="right">
                                    <div class="rate" data-score="<?php echo $seller_rating_summary['FiftyTwoWeeks']['rating'];?>"></div>
                                    </td>
                                    <th align="left">
                                    <?php echo $seller_rating_summary['FiftyTwoWeeks']['rating_count'];?>
                                    </th>
                                    </tr>
                                    <?php }?>

                                </table>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="pull-right">
                                    <a href="#" class="btn-refresh" data-refresh="<?php echo $refresh_ebay_seller_dashboard;?>"><i class="fa fa-refresh"></i></a>
                                </div>
                                <h3 class="panel-title"><i class="fa fa-newspaper-o"></i> Summary</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th align="left">eBay Official Time</th>
                                        <td align="right">-</td>
                                    </tr>
                                    <tr>
                                        <th align="left">BuyerSatisfaction</th>
                                        <td align="right">
                                            <?php foreach ($dashboard['seller_dashboard'] as $seller_dashboard) {?>
                                            <?php if($seller_dashboard['name'] == 'buyerSatisfaction') {?>
                                            <?php echo $seller_dashboard['status'];?>
                                            <?php }?>
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th align="left">PowerSeller status</th>
                                        <td align="right">
                                            <?php foreach ($dashboard['seller_dashboard'] as $seller_dashboard) {?>
                                            <?php if($seller_dashboard['name'] == 'powerSellerStatus') {?>
                                            <?php echo $seller_dashboard['level'];?>
                                            <?php }?>
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th align="left">Search Standing</th>
                                        <td align="right">
                                            <?php foreach ($dashboard['seller_dashboard'] as $seller_dashboard) {?>
                                            <?php if($seller_dashboard['name'] == 'searchStanding') {?>
                                            <?php echo $seller_dashboard['status'];?>
                                            <?php }?>
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th align="left">Seller Account</th>
                                        <td align="right">
                                            <?php foreach ($dashboard['seller_dashboard'] as $seller_dashboard) {?>
                                            <?php if($seller_dashboard['name'] == 'sellerAccount') {?>
                                            <?php echo $seller_dashboard['status'];?>
                                            <?php }?>
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th align="left">Seller Fee Discount</th>
                                        <td align="right">
                                            <?php foreach ($dashboard['seller_dashboard'] as $seller_dashboard) {?>
                                            <?php if($seller_dashboard['name'] == 'sellerFeeDiscount') {?>
                                            <?php echo $seller_dashboard['percent'];?> %
                                            <?php }?>
                                            <?php }?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>






            </div>

        </div>


    </div>
</div>



<script>


    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(orderSell);

    function orderSell() {
console.log($('#orders_sell_div').attr('data-url'));
        $.get($('#orders_sell_div').attr('data-url'), function(resp){
            chart_data = $.parseJSON(resp);
            console.log(chart_data.data);

            var data = google.visualization.arrayToDataTable(chart_data.data);

            var options = {
                backgroundColor: 'transparent',
                hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}, slantedText:false },
                vAxis: {minValue: 0}
            };

            var chart = new google.visualization.AreaChart(document.getElementById("orders_sell_div"));
            chart.draw(data, options);

        });




    }


    $(function() {

        $('div.rate').raty({
            score: function() {
                return $(this).attr('data-score');
            },
            path: 'view/image/channels/',
            precision  : true,
            numberMax : 5,
            readOnly   : true
        });

        $( "a.btn-refresh" ).click(function() {
            var btn = $( this );
            var serviceUrl = btn.attr('data-refresh');
            var $container = $('div.table-responsive', btn.closest('div.panel'));

            $("<div />").css({
                position: "absolute",
                width: "100%",
                height: "100%",
                filter: "alpha(opacity=50)",
                "-moz-opacity" : "0.3",
                "-khtml-opacity" : "0.3",
                "opacity" : "0.3",
                left: 0,
                top: 0,
                zIndex: 1000000,  // to be on the safe side
                background: "url(view/image/channels/ajax-loader.gif) no-repeat 50% 50%",
                "background-color": "black"
            }).appendTo($container.css("position", "relative"));


            $.get(serviceUrl, function($html){
                $container.html($html);

                $('div.rate').raty({
                    score: function() {
                        return $(this).attr('data-score');
                    },
                    path: 'view/image/channels/',
                    precision  : true,
                    numberMax : 5,
                    readOnly   : true
                });

            });

            return false;
        });



    });
</script>


<?php echo $footer; ?>


