<?php
//ini_set("display_errors", 1);
//error_reporting(E_ALL);
require_once('lib/Braintree.php');
require_once('../../../../include_config.php');
$generalConfigPaymentArr = $generalobj->getGeneralVarAll_Payment_Array();
//ini_set("display_errors", 1);
//error_reporting(E_ALL);
$envMode = $generalConfigPaymentArr['BRAINTREE_ENVIRONMENT'];
$braintreeMerchantId = $generalConfigPaymentArr['BRAINTREE_MERCHANT_ID'];
$braintreePublicKey = $generalConfigPaymentArr['BRAINTREE_PUBLIC_KEY'];
$braintreePrivateKey = $generalConfigPaymentArr['BRAINTREE_PRIVATE_KEY'];
Braintree_Configuration::environment(strtolower($envMode));
Braintree_Configuration::merchantId($braintreeMerchantId);
Braintree_Configuration::publicKey($braintreePublicKey);
Braintree_Configuration::privateKey($braintreePrivateKey);
?>