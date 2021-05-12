<?php
//ini_set("display_errors", 1);
//error_reporting(E_ALL);
//echo "<pre>";
include_once('../../../include_config.php');
include_once('../configuration.php');
include_once('../../../generalFunctions.php');
global $APP_PAYMENT_METHOD;
$generalConfigPaymentArr = $generalobj->getGeneralVarAll_Payment_Array();
$price_new = isset($_REQUEST["amount"]) ? $_REQUEST["amount"] : 0;
$userAmount = isset($_REQUEST["userAmount"]) ? $_REQUEST["userAmount"] : '';
$currencyCode = isset($_REQUEST["ccode"]) ? $_REQUEST["ccode"] : '';
$iOrderId = isset($_REQUEST["iOrderId"]) ? $_REQUEST["iOrderId"] : '';
$vOrderNo = isset($_REQUEST["vOrderNo"]) ? $_REQUEST["vOrderNo"] : '';
$ePaymentOption = isset($_REQUEST["ePaymentOption"]) ? $_REQUEST["ePaymentOption"] : '';
$vStripeToken = isset($_REQUEST["vStripeToken"]) ? $_REQUEST["vStripeToken"] : '';
$CheckUserWallet = isset($_REQUEST["CheckUserWallet"]) ? $_REQUEST["CheckUserWallet"] : 'No';
$iUserId = isset($_REQUEST["iUserId"]) ? $_REQUEST["iUserId"] : '';
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
$returnUrl = isset($_REQUEST['returnUrl']) ? trim($_REQUEST['returnUrl']) : 'webservice_shark.php';
$vPayMethod = isset($_REQUEST["vPayMethod"]) ? $_REQUEST["vPayMethod"] : ''; // Instant,Manual
$themeColor = isset($_REQUEST["AppThemeColor"]) ? $_REQUEST["AppThemeColor"] : '000000';
$textColor = isset($_REQUEST["AppThemeTxtColor"]) ? $_REQUEST["AppThemeTxtColor"] : 'FFFFFF';
$extraParameters = "?iUserId=" . $iUserId . "&iOrderId=" . $iOrderId . "&amount=" . $price_new . "&ccode=" . $currencyCode . "&userAmount=" . $userAmount . "&vOrderNo=" . $vOrderNo . "&ePaymentOption=" . $ePaymentOption . "&CheckUserWallet=" . $CheckUserWallet . "&eSystem=" . $eSystem . "&vStripeToken=" . $vStripeToken . "&type=" . $type . "&Platform=" . $Platform . "&tSessionId=" . $tSessionId . "&GeneralMemberId=" . $GeneralMemberId . "&GeneralUserType=" . $GeneralUserType . "&GeneralDeviceType=" . $GeneralDeviceType . "&GeneralAppVersion=" . $GeneralAppVersion . "&vTimeZone=" . $vTimeZone . "&vUserDeviceCountry=" . $vUserDeviceCountry . "&iServiceId=" . $iServiceId . "&vCurrentTime=" . $vCurrentTime . "&returnUrl=" . $returnUrl . "&vPayMethod=" . $vPayMethod . "&AppThemeColor=" . $themeColor . "&AppThemeTxtColor=" . $textColor;
//print_R($APP_PAYMENT_METHOD);die;
if ($iUserId != "" && $iOrderId != "") {
    if ($APP_PAYMENT_METHOD == "Stripe") {
        $getWayUrl = $tconfig['tsite_url'] . "assets/libraries/webview/stripe/index.php" . $extraParameters;
        header('Location: ' . $getWayUrl); // Comment Here For app
    } else if ($APP_PAYMENT_METHOD == "Braintree") {
        $getWayUrl = $tconfig['tsite_url'] . "assets/libraries/webview/braintree/index.php" . $extraParameters;
        header('Location: ' . $getWayUrl); // Comment Here For app
    } else if ($APP_PAYMENT_METHOD == "Paymaya") {
        $getWayUrl = $tconfig['tsite_url'] . "assets/libraries/webview/paymaya/index.php" . $extraParameters;
        header('Location: ' . $getWayUrl); // Comment Here For app
    } else if ($APP_PAYMENT_METHOD == "Omise") {
        $getWayUrl = $tconfig['tsite_url'] . "assets/libraries/webview/omise/index.php" . $extraParameters;
        header('Location: ' . $getWayUrl); // Comment Here For app
    } else if ($APP_PAYMENT_METHOD == "Xendit") {
        $getWayUrl = $tconfig['tsite_url'] . "assets/libraries/webview/xendit/index.php" . $extraParameters;
        header('Location: ' . $getWayUrl); // Comment Here For app
    } else {
        echo "Payment Getaway not found for Payment method :" . $APP_PAYMENT_METHOD;
        die;
    }
}
?>