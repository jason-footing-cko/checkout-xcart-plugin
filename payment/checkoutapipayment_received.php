<?php

global $xcart_dir, $secure_oid, $sql_tbl, $xcart_catalogs, $cart, $module_params; //all these global variables are needed to process payment.

if ($_POST['cko-payment-token'] && $_POST['cko-track-id']) {
  require_once './auth.php';
  include 'includes/autoload.php';

 
  $xcart_catalogs['customer'];

  $paymentToken = $_POST['cko-payment-token'];
  $skey = $_POST['cko-track-id'];

  $payment_cc_data = func_query_first("SELECT * FROM $sql_tbl[ccprocessors] WHERE processor='checkoutapipayment.php'");

  $config['authorization'] = $payment_cc_data['param02'];
  $config['paymentToken'] = $paymentToken;
  $Api = CheckoutApi_Api::getApi(array('mode' => $payment_cc_data['param01']));
  $amountCents = $Api->valueToDecimal($cart['total_cost'],$payment_cc_data['param09']);
  $toValidate = array(
    'currency' => $payment_cc_data['param09'],
    'value' => $amountCents,
    'trackId' => $skey,
  );
  $objectCharge = $Api->verifyChargePaymentToken($config);
  
  $chargeUpdated = $Api->updateTrackId($objectCharge, $skey);
  $charge['chargeId'] = $objectCharge->getId(); 
  $objectCharge = $Api->getCharge($charge);
  $validateRequest = $Api::validateRequest($toValidate,$objectCharge);
  $message = 'Transaction approved. Charge ID : ' . $objectCharge->getId();
  if($validateRequest['status']){  
    foreach($validateRequest['message'] as $errormessage){
      $message .= $errormessage . '. ';
    }
  }

  if (preg_match('/^1[0-9]+$/', $objectCharge->getResponseCode())) {

    $bill_output['code'] = 1;
    $bill_output['billmes'] = $message;
  }
  else {

    $bill_output['code'] = 2;
    $bill_output['billmes'] = 'An error has occured. Please verify your credit card details and try again.  ' . $objectCharge->getId();
  }
  require($xcart_dir . '/payment/payment_ccend.php');
}
else {

  header('Location: ../');
  die('Access denied');
}

