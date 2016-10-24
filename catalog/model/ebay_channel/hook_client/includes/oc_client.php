<?php

function addTrackNumber($order_id, $tracking_number, $shipping_carrier_used) {
    callJob(OPEN_CART_URL,'ebay_channel/notification/complete_sale&tracking_number=' . $tracking_number . '&order_id=' . $order_id . '&shipping_carrier_used=' . str_replace(' ', '%20', $shipping_carrier_used), 5);
}

function callJob($url, $scope, $delay=1) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '/?route=' . $scope);
    curl_setopt($ch, CURLOPT_TIMEOUT, $delay);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $a = curl_exec($ch);
    curl_close($ch);
//echo $a; die();

}
