<?php

/*
  This api run only 2 times per a day
 */
session_start();

include_once('common.php');
include_once('assets/libraries/class.general.php');

/* ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL); */

$generalobj = new General();
$app_id = $EXCHANGE_CURRENCY_RATES_APP_ID;
$sql = "SELECT vName FROM currency WHERE eDefault='Yes'";
$db_currency = $obj->MySQLSelect($sql);
$default_currency = $db_currency[0]['vName'];
//echo $default_currency;die;
$defaultcurrency = 'USD';

$returnArr = array();
$ch = curl_init();
$oxr_url = "https://openexchangerates.org/api/latest.json?app_id=" . $app_id . '&base=' . $defaultcurrency;

// Open CURL session:
$ch = curl_init($oxr_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Get the data:
$json = curl_exec($ch);
curl_close($ch);


$exchangeRates = json_decode($json, TRUE);

//echo "<pre>";print_r($exchangeRates);die;
function getDefaultCurrenyRate($default_db_currency, $exchangeRates) {
    if (isset($exchangeRates['rates']) && !empty($exchangeRates['rates']) && count($exchangeRates['rates']) > 0) {
        if ($default_db_currency == "ZWD") {
            $default_db_currency = "ZWL";
        }
        if (isset($exchangeRates['rates'][$default_db_currency])) {
            return $exchangeRates['rates'][$default_db_currency];
        }
    }
    return 1;
}

if (isset($exchangeRates['rates']) && !empty($exchangeRates['rates']) && count($exchangeRates['rates']) > 0) {
    $vSymbol = "";
    foreach ($exchangeRates['rates'] as $key => $data) {

        $locale = 'en-US';
        $currency = $key;

        // $fmt = new NumberFormatter( $locale."@currency=$currency", NumberFormatter::CURRENCY );
        // $vSymbol = $fmt->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
        header("Content-Type: text/html; charset=UTF-8;");
        //echo "<pre>";print_r($data);die;
        $data = $data / getDefaultCurrenyRate($default_currency, $exchangeRates);

        $eDefault = "No";
        $ThresholdAmount = 50;
        $fThresholdAmount = $ThresholdAmount * $data;
        if ($key == "ZWL") {
            $key = "ZWD";
        }
        $sql = "select  vName FROM currency  where vName='" . $key . "'";
        $db_select_currency = $obj->MySQLSelect($sql);
        $counter = count($db_select_currency);

        if ($counter > 0) {
            $notkey = $db_select_currency[0]['vName'];
            if ($notkey != "USD") {
                $Table = "currency";
                $updateData['Ratio'] = trim($data);
                $updateData['fThresholdAmount'] = trim($fThresholdAmount);

                $where = "vName='" . $key . "'";
                $res = $obj->MySQLQueryPerform($Table, $updateData, 'update', $where);

                $Ratio = trim($data);
                $subquery = "update `master_currency`  SET `Ratio` = '" . $Ratio . "' where `vName` = '" . $key . "'";
                $obj->sql_query($subquery);
            }
        } else {

            $query = "Insert into  master_currency (vName,vSymbol,eDefault,Ratio) values ('" . $key . "','" . $vSymbol . "','" . $eDefault . "','" . $data . "')";
            $obj->sql_query($query);
            $id = $obj->GetInsertId();
        }
    }


    $returnArr['Action'] = "1";
    $returnArr['message'] = "Record Updated successfully.";
    echo json_encode($returnArr);
    exit;
} else {
    $returnArr['Action'] = "0";
    $returnArr['message'] = "Can't communicate with server. Please check your internet connection or try again later.";
    echo json_encode($returnArr);
    exit;
}
?>