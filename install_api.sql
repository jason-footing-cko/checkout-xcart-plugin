INSERT INTO `xcart_ccprocessors` (`module_name`, `type`, `processor`, `template`, `param01`, `param02`, `param03`, `param04`, `param05`, `param06`, `param07`, `param08`, `param09`, `disable_ccinfo`, `background`, `testmode`, `is_check`, `is_refund`, `c_template`, `paymentid`, `cmpi`, `use_preauth`, `preauth_expire`, `has_preauth`, `capture_min_limit`, `capture_max_limit`) VALUES
('Checkout.com Gateway 3.0', 'C', 'checkoutapipayment.php', 'Checkoutapipayment/checkoutapiadmin.tpl', '', '', '', '', '', '', '', '', '', 'Y', 'Y', 'Y', '', '', 'payments/Checkoutapipayment/creditcardpci.tpl', 514, '', '', 0, '', '0%', '0%');


INSERT INTO `xcart_payment_methods` (`paymentid`, `payment_method`, `payment_details`, `payment_template`, `payment_script`, `protocol`, `orderby`, `active`, `is_cod`, `af_check`, `processor_file`, `surcharge`, `surcharge_type`) VALUES
(514, 'Credit Card - Checkout.com', '', 'payments/Checkoutapipayment/creditcardpci.tpl', 'payment_cc.php', 'http', 999, 'Y', 'N', 'N', 'checkoutapipayment.php', '0.00', '$');
