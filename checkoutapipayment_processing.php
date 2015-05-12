<?php

define('SKIP_COOKIE_CHECK', true);

require_once './auth.php';
include './payment/includes/autoload.php';

x_load('order');

$posted_data = file_get_contents("php://input");

if (empty($posted_data)) {
    // empty request
    exit();
    
} else {
    
    $payment_cc_data = func_query_first("SELECT * FROM $sql_tbl[ccprocessors] WHERE processor='checkoutapipayment.php'");

    $Api = CheckoutApi_Api::getApi(array('mode' => $payment_cc_data['param01']));
    $objectCharge = $Api->chargeToObj($posted_data);

    if ($objectCharge->isValid()) {

        /*
         * Need to get track id
         */
        $order_id = $objectCharge->getTrackId();

        if ($objectCharge->getCaptured()) {

            $advinfo = 'Your payment has been successfully completed';
            func_change_order_status($order_id, 'C', $advinfo); // completed status?
        } elseif ($objectCharge->getRefunded()) {

            $advinfo = 'Your payment has been refunded';
            func_change_order_status($order_id, 'D', $advinfo); // declined status?
            
        } elseif(!$objectCharge->getAuthorised()) { 

            $advinfo[] = 'Your order has been cancelled';
            func_change_order_status($order_id, 'D', $advinfo); // cancelled status?
        }
    }
}

