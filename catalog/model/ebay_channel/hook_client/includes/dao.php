<?php

function insertOrder($order) {

    $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}, {country}, {city}, {postcode}' . "\n";
    $find = array(
        '{firstname}',
        '{lastname}',
        '{company}',
        '{address_1}',
        '{address_2}',
        '{city}',
        '{postcode}',
        '{zone}',
        '{zone_code}',
        '{country}'
    );


    $replace = array(
        'firstname' => $order['first_name'],
        'lastname'  => $order['last_name'],
        'company'   => $order['company'],
        'address_1' => $order['address_1'],
        'address_2' => $order['address_2'],
        'city'      => $order['city'],
        'postcode'  => $order['postcode'],
        'country'   => $order['country']
    );

    $content = trim(str_replace($find, $replace, $format));
    $content .= "\n\nOrder: #" . $order['order_id'];
    $content .= "\nDate Ordered: " . $order['date_added'];
    $content .= "\nPayment Method: " . $order['payment_method'];
    if(!empty($order['phone'])) {
        $content .= "\nPhone: " . $order['phone'];
    }

    $content .= "\n\n";
    foreach ($order['products'] as $product) {
        $content .=  "\r\n" . $product['title'];
    }

    $name_and_country = $order['first_name'] . ' ' . $order['last_name'] . ', ' . $order['country'];

    db_query("INSERT INTO info (ebay_order_id, title, content, name_and_country, status, customer_email, updated)
                      VALUES ('". $order['order_id'] ."', '".db_input($order['buyer_user_id']) ."', '".db_input($content) . "', '".db_input($name_and_country) ."', 0, '" . db_input($order['email']) . "', '"  .date("Y-m-d H:i:s")."')");
}

function checkIfExists($order_id) {
    $check_query = db_query("SELECT count(*) as cnt FROM info WHERE ebay_order_id = '". $order_id."'");
    $check = mysqli_fetch_array($check_query);
    if($check['cnt'] > 0) {
        return true;
    }

    return false;
}