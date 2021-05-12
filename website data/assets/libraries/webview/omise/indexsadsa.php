<?php

ini_set("display_errors", 1);
error_reporting(E_ALL);
include_once('../../../../include_taxi_webservices.php');
$generalConfigPaymentArr = $generalobj->getGeneralVarAll_Payment_Array();
//echo "<pre>";
//print_R($generalConfigPaymentArr);die;
$paymayaSecretKey = $generalConfigPaymentArr['PAYMAYA_SECRET_KEY'];
$paymayaPublishKey = $generalConfigPaymentArr['PAYMAYA_CHECKOUT_PUBLISH_KEY'];
$paymayaAPIUrl = $generalConfigPaymentArr['PAYMAYA_API_URL'];

function paymaya_checkouts($itemName, $orderNo, $amount, $successUrl, $failedUrl, $paymayaAPIUrl, $paymaya_auth) {
    $url = $paymayaAPIUrl . "checkout/v1/checkouts";
    $data = '{"totalAmount": {"currency": "PHP","value": "' . $amount . '"},"items": [ { "name": "' . $itemName . '","amount": { "value": "' . $amount . '"},"totalAmount": { "value": "' . $amount . '" } } ],"redirectUrl": {"success": "' . $successUrl . '","failure": "' . $failedUrl . '","cancel": "' . $failedUrl . '"},"requestReferenceNumber": "000141386713"}';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic ' . $paymaya_auth));
    $responseData = curl_exec($ch);
    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return $responseData;
}

$itemPrice = isset($_REQUEST["amount"]) ? $_REQUEST["amount"] : 0;
$currency = isset($_REQUEST["ccode"]) ? $_REQUEST["ccode"] : '';
$iOrderId = isset($_REQUEST["iOrderId"]) ? $_REQUEST["iOrderId"] : '';
$vOrderNo = isset($_REQUEST["vOrderNo"]) ? $_REQUEST["vOrderNo"] : '';
$iUserId = isset($_REQUEST["iUserId"]) ? $_REQUEST["iUserId"] : '';
$returnUrl = isset($_REQUEST['returnUrl']) ? trim($_REQUEST['returnUrl']) : 'webservice_dl_shark.php';

$ePaymentOption = isset($_REQUEST["ePaymentOption"]) ? $_REQUEST["ePaymentOption"] : '';
$vStripeToken = isset($_REQUEST["vStripeToken"]) ? $_REQUEST["vStripeToken"] : '';
$CheckUserWallet = isset($_REQUEST["CheckUserWallet"]) ? $_REQUEST["CheckUserWallet"] : 'No';
$type = isset($_REQUEST['type']) ? trim($_REQUEST['type']) : '';
$eSystem = isset($_REQUEST["eSystem"]) ? $_REQUEST["eSystem"] : '';
$tSessionId = isset($_REQUEST['tSessionId']) ? trim($_REQUEST['tSessionId']) : '';
$GeneralMemberId = isset($_REQUEST['GeneralMemberId']) ? trim($_REQUEST['GeneralMemberId']) : '';
$GeneralUserType = isset($_REQUEST['GeneralUserType']) ? trim($_REQUEST['GeneralUserType']) : '';
$GeneralAppVersion = isset($_REQUEST['GeneralAppVersion']) ? trim($_REQUEST['GeneralAppVersion']) : '';
$Platform = isset($_REQUEST['Platform']) ? trim($_REQUEST['Platform']) : 'Android';
$vTimeZone = isset($_REQUEST["vTimeZone"]) ? $_REQUEST["vTimeZone"] : '';
$iServiceId = isset($_REQUEST["iServiceId"]) ? $_REQUEST["iServiceId"] : '';
$vUserDeviceCountry = isset($_REQUEST["vUserDeviceCountry"]) ? $_REQUEST["vUserDeviceCountry"] : '';
$vCurrentTime = isset($_REQUEST["vCurrentTime"]) ? $_REQUEST["vCurrentTime"] : '';
$GeneralDeviceType = isset($_REQUEST['GeneralDeviceType']) ? trim($_REQUEST['GeneralDeviceType']) : '';
$vPayMethod = isset($_REQUEST["vPayMethod"]) ? $_REQUEST["vPayMethod"] : ''; // Instant,Manual
$status = "failed";
$extraParameters = "?iUserId=" . $iUserId . "&iOrderId=" . $iOrderId . "&amount=" . $itemPrice . "&ccode=" . $currency . "&vOrderNo=" . $vOrderNo . "&ePaymentOption=" . $ePaymentOption . "&CheckUserWallet=" . $CheckUserWallet . "&eSystem=" . $eSystem . "&vStripeToken=" . $vStripeToken . "&type=" . $type . "&Platform=" . $Platform . "&tSessionId=" . $tSessionId . "&GeneralMemberId=" . $GeneralMemberId . "&GeneralUserType=" . $GeneralUserType . "&GeneralDeviceType=" . $GeneralDeviceType . "&GeneralAppVersion=" . $GeneralAppVersion . "&vTimeZone=" . $vTimeZone . "&vUserDeviceCountry=" . $vUserDeviceCountry . "&iServiceId=" . $iServiceId . "&vCurrentTime=" . $vCurrentTime . "&payStatus=" . $status . "&vPayMethod=" . $vPayMethod;
//echo "<pre>";
//print_r($_REQUEST);die;
$tDescription = "Amount charge for order no :" . $vOrderNo;
$successUrl = $redirectUrl = $tconfig['tsite_url'] . $returnUrl . "?iUserId=" . $iUserId . "&iOrderId=" . $iOrderId . "&amount=" . $amount . "&ccode=" . $currency . "&vOrderNo=" . $vOrderNo . $extraParameters . "&payStatus=succeeded&vPayMethod=" . $vPayMethod;
$failedUrl = $redirectUrl = $tconfig['tsite_url'] . $returnUrl . "?iUserId=" . $iUserId . "&iOrderId=" . $iOrderId . "&amount=" . $amount . "&ccode=" . $currency . "&vOrderNo=" . $vOrderNo . $extraParameters . "&payStatus=" . $status . "&vPayMethod=" . $vPayMethod;
$paymaya_auth = base64_encode($paymayaPublishKey . ":");
$responseData = paymaya_checkouts($tDescription, $vOrderNo, $itemPrice, $successUrl, $failedUrl, $paymayaAPIUrl, $paymaya_auth);
//echo "<pre>";
$data = (array) json_decode($responseData);
if (isset($data['redirectUrl']) && $data['redirectUrl'] != "") {
    $paymaya_url = $data['redirectUrl'];
    header('Location: ' . $paymaya_url);
}
?>