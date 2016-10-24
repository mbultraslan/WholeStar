<?php
/**
 * Created by PhpStorm.
 * User: Ion
 */

require "includes/config.php";
require "includes/db.php";
require "includes/dao.php";

$response = array('status' => false);

if(isset($_GET["action"])) {

    switch ($_GET["action"]) {
        case 'addOrder':
            addOrder();
            break;
        case 'getOrder':
            getOrder();
            break;
        case 'getOrderByExternalId':
            getOrderByExternalId();
            break;
        default:
            http_response_code(404);
            $response = array('message' => 'Invalid action!');
    }

} else {
    http_response_code(404);
    $response = array('message' => 'Invalid action!');
}


function addOrder() {
    global $response;
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_GET["order_external_id"])) {

            $body = file_get_contents('php://input');
            $order= json_decode($body, true);
            if(!empty($order)) {
                $order_id = $_GET["order_external_id"];

                if(checkIfExists($order_id)) {
                    http_response_code(300);
                    $response['message'] = 'Order already exists';
                    return ;
                }

                if(!isset($order['order_status']) || empty($order['order_status'])) {
                    http_response_code(405);
                    $response['message'] = 'Order status is required';
                    return ;
                }

                if(!isset($order['products']) || empty($order['products'])) {
                    http_response_code(405);
                    $response['message'] = 'Order products are required';
                    return ;
                }

                //insert order in db
                insertOrder($order);

                http_response_code(201);
                $response['status'] = true;
            } else {
                http_response_code(405);
                $response['message'] = 'invalid json body!';
            }
        } else {
            http_response_code(405);
            $response['message'] = 'order_external_id is required';
        }
    } else {
        http_response_code(405);
        $response['message'] = 'Unsupported HTTP request method detected - please use HTTP POST request';
    }
}

function getOrder() {
    global $response;
    if(isset($_GET["order_id"])) {
        $order_query = mysql_query("SELECT * FROM temp_sys WHERE id = '".(int)$_GET["order_id"]."'");
        $order = mysql_fetch_array($order_query);
        if(!empty($order)) {
            http_response_code(200);
            $response['status'] = true;
            $response['order'] = $order;
        } else {
            http_response_code(404);
            $response['message'] = 'Order #' . (int)$_GET["order_id"] . ' not found';
        }
    } else {
        http_response_code(404);
        $response = array('message' => 'Invalid action!');
    }
}

function getOrderByExternalId() {
    global $response;
    if(isset($_GET["order_external_id"]) && isset($_GET["store"])) {
        $order_query = mysql_query("SELECT * FROM temp_sys WHERE store = '" . tep_db_input($_GET["store"])  ."' AND ebay_order_id = '".(int)$_GET["order_external_id"]."'");
        $order = mysql_fetch_array($order_query);
        if(!empty($order)) {
            http_response_code(200);
            $response['status'] = true;
            $response['order'] = $order;
        } else {
            http_response_code(404);
            $response['message'] = 'Order #' . (int)$_GET["order_external_id"] . ' not found';
        }
    } else {
        http_response_code(404);
        $response = array('message' => 'Invalid action!');
    }
}

// HTTP headers for no cache etc
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');
if(isset($_GET["pretty"])) {
    die(json_encode($response, JSON_PRETTY_PRINT));
} else {
    die(json_encode($response));
}
