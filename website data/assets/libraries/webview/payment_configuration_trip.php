<?php

include_once('../../../include_config.php');
include_once('../configuration.php');
include_once('../../../generalFunctions.php');
global $APP_PAYMENT_METHOD;
//print_r($tconfig);die;
$tSiteUrl = $tconfig['tsite_url'];
$generalConfigPaymentArr = $generalobj->getGeneralVarAll_Payment_Array();
$price_new = isset($_REQUEST["amount"]) ? $_REQUEST["amount"] : 1;
$currencyCode = isset($_REQUEST["ccode"]) ? $_REQUEST["ccode"] : 'USD';
$iUserId = isset($_REQUEST["iUserId"]) ? $_REQUEST["iUserId"] : '0';
$UserType = isset($_REQUEST["UserType"]) ? $_REQUEST["UserType"] : '';
//$iServiceId = isset($_REQUEST["iServiceId"]) ? $_REQUEST["iServiceId"] : '';
$vUserDeviceCountry = isset($_REQUEST["vUserDeviceCountry"]) ? $_REQUEST["vUserDeviceCountry"] : '';
$returnUrl = $tSiteUrl . 'assets/libraries/webview/payment_result.php';
$themeColor = isset($_REQUEST["AppThemeColor"]) ? $_REQUEST["AppThemeColor"] : '000000';
$textColor = isset($_REQUEST["AppThemeTxtColor"]) ? $_REQUEST["AppThemeTxtColor"] : 'FFFFFF';

$UniqueCode = isset($_REQUEST["UniqueCode"]) ? $_REQUEST["UniqueCode"] : '';

$eForTip = isset($_REQUEST["eForTip"]) ? $_REQUEST["eForTip"] : 'No';
$iTripId = isset($_REQUEST["iTripId"]) ? $_REQUEST["iTripId"] : '';

if($UserType == "Passenger"){
    $UserType = 'Rider';
}
if($eForTip == 'Yes'){
    $user_available_balance = $generalobj->get_user_available_balance($iUserId,$UserType);
    if($user_available_balance > $price_new) {
        $where = " iTripId = '$iTripId'";
        $data['fTipPrice'] = $price_new;
        $id = $obj->MySQLQueryPerform("trips", $data, 'update', $where);
        $vRideNo = get_value('trips', 'vRideNo', 'iTripId', $iTripId, '', 'true');
        $data_wallet['iUserId'] = $iUserId;
        $data_wallet['eUserType'] = $UserType;
        $data_wallet['iBalance'] = $price_new;
        $data_wallet['eType'] = "Debit";
        $data_wallet['dDate'] = date("Y-m-d H:i:s");
        $data_wallet['iTripId'] = $iTripId;
        $data_wallet['eFor'] = "Booking";
        $data_wallet['ePaymentStatus'] = "Unsettelled";
        $data_wallet['tDescription'] = "#LBL_DEBITED_BOOKING#" . $vRideNo;
        $generalobj->InsertIntoUserWallet($data_wallet['iUserId'], $data_wallet['eUserType'], $data_wallet['iBalance'], $data_wallet['eType'], $data_wallet['iTripId'], $data_wallet['eFor'], $data_wallet['tDescription'], $data_wallet['ePaymentStatus'], $data_wallet['dDate']);
        $redirectUrl = $returnUrl . "?sucess=1";
        header('Location: ' . $redirectUrl); // Comment Here For app
        exit();
    } else {
        $user_available_balance_new = round($user_available_balance,2);
        $user_wallet_debit_amount = $user_available_balance_new;
        $price_new = $price_new - $user_available_balance_new;
    }
}

$stripeamount = $price_new;
if ($APP_PAYMENT_METHOD == "Stripe") {
    $stripeamount = $price_new * 100;
}
$extraParameters = "?iUserId=" . $iUserId . "&UserType=" . $UserType . "&amount=" . $price_new . "&ccode=" . $currencyCode . "&vUserDeviceCountry=" . $vUserDeviceCountry . "&returnUrl=" . $returnUrl . "&AppThemeColor=" . $themeColor . "&AppThemeTxtColor=" . $textColor."&stripeamount=".$stripeamount."&UniqueCode=".$UniqueCode."&eForTip=".$eForTip."&DebitAmt=".$user_wallet_debit_amount."&iTripId=".$iTripId;

//print_R($APP_PAYMENT_METHOD);die;

if ($iUserId != "") {
    if ($APP_PAYMENT_METHOD == "Stripe") {
        $getWayUrl = $tconfig['tsite_url'] . "assets/libraries/webview/stripe/index-trip.php" . $extraParameters;
        header('Location: ' . $getWayUrl); // Comment Here For app
    } else if ($APP_PAYMENT_METHOD == "Braintree") {
        $getWayUrl = $tconfig['tsite_url'] . "assets/libraries/webview/braintree/index-trip.php" . $extraParameters;
        header('Location: ' . $getWayUrl); // Comment Here For app
    } else if ($APP_PAYMENT_METHOD == "Paymaya") {
        $getWayUrl = $tconfig['tsite_url'] . "assets/libraries/webview/paymaya/index-trip.php" . $extraParameters;
        header('Location: ' . $getWayUrl); // Comment Here For app
    } else if ($APP_PAYMENT_METHOD == "Omise") {
        $getWayUrl = $tconfig['tsite_url'] . "assets/libraries/webview/omise/index-trip.php" . $extraParameters;
        header('Location: ' . $getWayUrl); // Comment Here For app
    } else if ($APP_PAYMENT_METHOD == "Xendit") {
        $getWayUrl = $tconfig['tsite_url'] . "assets/libraries/webview/xendit/index-trip.php" . $extraParameters;
        header('Location: ' . $getWayUrl); // Comment Here For app
    } else {
        echo "Payment Getaway not found for Payment method :" . $APP_PAYMENT_METHOD;
        die;
    }
}
?>