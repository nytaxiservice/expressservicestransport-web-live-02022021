<?php

include_once('common.php');
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
//global generalobj;
$iCompanyId = $_SESSION['sess_iCompanyId'];
$iDriverId = $_SESSION['sess_iUserId'];
$str = $userType = '';
$memberId = 0;
if (isset($_SESSION['sess_user']) && $_SESSION['sess_user'] == 'driver') {
    $tbl = 'register_driver';
    $where = " WHERE `iDriverId` = '" . $iDriverId . "'";
    $memberId = $iDriverId;
    $userType = "DRIVER";
}
if (isset($_SESSION['sess_user']) && $_SESSION['sess_user'] == 'company') {
    $tbl = 'company';
    $where = " WHERE `iCompanyId` = '" . $iCompanyId . "'";
    $memberId = $iCompanyId;
    $userType = "COMPANY";
}
if ($action == 'login') {
    if (SITE_TYPE == 'Demo' && ($_SESSION['sess_vEmail'] == 'company@gmail.com' || $_SESSION['sess_vEmail'] == 'provider@demo.com')) {
        echo $var_msg = '2';
        return $var_msg;
        exit;
    }
    if ($ENABLE_EDIT_DRIVER_PROFILE == "No") {
        echo $var_msg = '3';
        return $var_msg;
        exit;
    } else {
        $phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $tProfileDescription = isset($_POST['tProfileDescription']) ? $_POST['tProfileDescription'] : '';
        $lname = isset($_POST['lname']) ? $_POST['lname'] : '';
        $vCountry = isset($_POST['vCountry']) ? $_POST['vCountry'] : '';
        $vCode = isset($_POST['vCode']) ? $_POST['vCode'] : '';
        $vCurrencyDriver = isset($_POST['vCurrencyDriver']) ? $_POST['vCurrencyDriver'] : '';
        $vCompany = isset($_POST['vCompany']) ? $_POST['vCompany'] : '';
        $vWorkLocationLatitude = isset($_POST['vWorkLocationLatitude']) ? $_POST['vWorkLocationLatitude'] : '';
        $vWorkLocationLongitude = isset($_POST['vWorkLocationLongitude']) ? $_POST['vWorkLocationLongitude'] : '';
        $vWorkLocation = isset($_POST['vWorkLocation']) ? $_POST['vWorkLocation'] : '';
        $vWorkLocationRadius = isset($_POST['vWorkLocationRadius']) ? $_POST['vWorkLocationRadius'] : '';
        $vVatNum = isset($_POST['vVatNum']) ? $_POST['vVatNum'] : '';
        $_SESSION["sess_vCurrency"] = $vCurrencyDriver;

        if (isset($_SESSION['sess_user']) && $_SESSION['sess_user'] == 'driver') {
            //$str = ",`vCurrencyDriver`='" . $vCurrencyDriver . "', eStatus = 'active'";
            $str = ",`tProfileDescription` = '" . $tProfileDescription . "',`vWorkLocation` = '" . $vWorkLocation . "',`vWorkLocationLatitude` = '" . $vWorkLocationLatitude . "',`vWorkLocationLongitude` = '" . $vWorkLocationLongitude . "',`vWorkLocationRadius` = '" . $vWorkLocationRadius . "',`vCurrencyDriver`='" . $vCurrencyDriver . "'";
        } else {
            $str = ",`vCompany`='" . $vCompany . "',`vVat`='" . $vVatNum . "'";
        }
        $csql = "SELECT eZeroAllowed,vCountryCode FROM `country` WHERE vPhoneCode = '" . $vCode . "'";
        $CountryData = $obj->MySQLSelect($csql);
        $eZeroAllowed = $CountryData[0]['eZeroAllowed'];
        if ($eZeroAllowed == 'Yes') {
            $phone = $phone;
        } else {
            $first = substr($phone, 0, 1);

            if ($first == "0") {
                $phone = substr($phone, 1);
            }
        }
        $eSystem = "";
        if($userType == "COMPANY"){
            $eSystem = "General";
        }
        $checEmailExist = $generalobj->checkMemberDataInfo($phone, "", $userType, $vCountry, $memberId,$eSystem); //Added By HJ On 12-09-2019
        if ($checEmailExist['status'] == 0) {
            $var_msg = $langage_lbl['LBL_MOBILE_EXIST'];
            $action = 0;
        } else if ($checEmailExist['status'] == 2) {
            $var_msg = $langage_lbl['LBL_INVALID_MEMBER_USER_COUNTRY_EMAIL_TXT'];
            $action = 2;
        } else {
            $q = "UPDATE ";
            $sql = "select * from " . $tbl . $where;
            $edit_data = $obj->sql_query($sql);
            if (isset($_SESSION['sess_user']) && $_SESSION['sess_user'] == 'driver' && $_REQUEST['email'] != $edit_data[0]['vEmail']) {
                $query = $q . " `" . $tbl . "` SET `eEmailVerified` = 'No' " . $where;
                $obj->sql_query($query);
            }
            if (isset($_SESSION['sess_user']) && $_SESSION['sess_user'] == 'driver' && $_REQUEST['phone'] != $edit_data[0]['vPhone']) {
                $query = $q . " `" . $tbl . "` SET `ePhoneVerified` = 'No' " . $where;
                $obj->sql_query($query);
            }
            if (isset($_SESSION['sess_user']) && $_SESSION['sess_user'] == 'driver' && $_REQUEST['vCode'] != $edit_data[0]['vCode']) {
                $query = $q . " `" . $tbl . "` SET `ePhoneVerified` = 'No' " . $where;
                $obj->sql_query($query);
            }
            if (isset($_SESSION['sess_user']) && $_SESSION['sess_user'] == 'driver' && $APP_TYPE == 'UberX') {
                $query = $q . " `" . $tbl . "` SET `tProfileDescription` = 'No' " . $where;
                $obj->sql_query($query);
            }
            $query = $q . " `" . $tbl . "` SET
			`vEmail` = '" . $email . "',
			`vLoginId` = '" . $username . "',
			`vName` = '" . $name . "' ,
			`vLastName` = '" . $lname . "',
			`vCountry` = '" . $vCountry . "',
			`vCode` = '" . $vCode . "',
			`vPhone` = '" . $phone . "' $str" . $where;
            $obj->sql_query($query);
            echo $var_msg = $langage_lbl['LBL_PORFILE_UPDATE_MSG'];
            $action = 1;
        }
        echo $action;
        die;
        return $var_msg;
        exit;
    }
}
if ($action == 'address') {
    if (SITE_TYPE == 'Demo' && ($_SESSION['sess_vEmail'] == 'company@gmail.com' || $_SESSION['sess_vEmail'] == 'provider@demo.com')) {
        echo $var_msg = '2';
        return $var_msg;
        exit;
    }
    $address1 = isset($_REQUEST['address1']) ? $_REQUEST['address1'] : '';
    $address2 = isset($_POST['address2']) ? $_POST['address2'] : '';
    $vCountry = isset($_POST['vCountry']) ? $_POST['vCountry'] : '';
    $vCity = isset($_POST['vCity']) ? $_POST['vCity'] : '';
    $vState = isset($_POST['vState']) ? $_POST['vState'] : '';
    $zipcode = isset($_POST['vZipcode']) ? $_POST['vZipcode'] : '';
    $q = "UPDATE ";
    $query = $q . " `" . $tbl . "` SET
			`vCaddress` = '" . $address1 . "',
			`vCadress2` = '" . $address2 . "',
			`vCity` = '" . $vCity . "',
			`vCountry` = '" . $vCountry . "',
			`vState` = '" . $vState . "',
			`vZip` = '" . $zipcode . "' $str" . $where;
    $obj->sql_query($query);
    echo $var_msg = $langage_lbl['LBL_ADDRESS_UPDATE_MSG'];
    return $var_msg;
    exit;
}
if ($action == 'pass') {
    if (SITE_TYPE == 'Demo' && ($_SESSION['sess_vEmail'] == 'company@gmail.com' || $_SESSION['sess_vEmail'] == 'provider@demo.com')) {
        echo $var_msg = '2';
        return $var_msg;
        exit;
    }
    $npass = isset($_REQUEST['npass']) ? $_REQUEST['npass'] : '';
    $npass = $generalobj->encrypt_bycrypt($npass);
    $q = "UPDATE ";
    $query = $q . " `" . $tbl . "` SET
			`vPassword` = '" . $npass . "' $str" . $where;
    $obj->sql_query($query);
    echo $var_msg = $langage_lbl['LBL_PASS_UPDATE_MSG'];
    return $var_msg;
    exit;
}
if ($action == 'lang1') {
    if (SITE_TYPE == 'Demo' && ($_SESSION['sess_vEmail'] == 'company@gmail.com' || $_SESSION['sess_vEmail'] == 'provider@demo.com')) {
        echo $var_msg = '2';
        return $var_msg;
        exit;
    }
    $lang = isset($_REQUEST['lang1']) ? $_REQUEST['lang1'] : '';
    $q = "UPDATE ";
    $query = $q . " `" . $tbl . "` SET
			`vLang` = '" . $lang . "' $str" . $where;
    $obj->sql_query($query);
    $_SESSION["sess_lang"] = $lang;
    echo $var_msg = $langage_lbl['LBL_LANG_UPDATE_MSG'];
    return $var_msg;
    exit;
}
if ($action == 'vat') {
    $vat = isset($_REQUEST['vat']) ? $_REQUEST['vat'] : '';
    $q = "UPDATE ";
    $query = $q . " `" . $tbl . "` SET
			`vVat` = '" . $vat . "' $str" . $where;
    $obj->sql_query($query);
    echo $var_msg = $langage_lbl['LBL_VAT_UPDATE_MESSAGE'];
    return $var_msg;
    exit;
}

if ($action == 'access') {
    $access = isset($_REQUEST['access']) ? $_REQUEST['access'] : '';
    $q = "UPDATE ";
    $query = $q . " `" . $tbl . "` SET
			`eAccess` = '" . $access . "' $str" . $where;
    $obj->sql_query($query);
    echo $var_msg = $langage_lbl['LBL_ACCESSIBILITY_UPDATE_MSG'];
    return $var_msg;
    exit;
}
if ($action == 'bankdetail') {
    global $generalobj;
    $vAccountNumber = isset($_POST['vAccountNumber']) ? $_POST['vAccountNumber'] : '';
    $vBIC_SWIFT_Code = isset($_POST['vBIC_SWIFT_Code']) ? $_POST['vBIC_SWIFT_Code'] : '';
    $vBankAccountHolderName = isset($_POST['vBankAccountHolderName']) ? $_POST['vBankAccountHolderName'] : '';
    $vBankLocation = isset($_POST['vBankLocation']) ? $_POST['vBankLocation'] : '';
    $vBankName = isset($_POST['vBankName']) ? $_POST['vBankName'] : '';
    $vPaymentEmail = isset($_POST['vPaymentEmail']) ? $_POST['vPaymentEmail'] : '';
    if (SITE_TYPE == 'Demo' && ($_SESSION['sess_vEmail'] == 'company@gmail.com' || $_SESSION['sess_vEmail'] == 'provider@demo.com')) {
        echo $var_msg = '2';
        return $var_msg;
        exit;
    }
    $q = "UPDATE ";
    $query = $q . " `" . $tbl . "` SET
			`vAccountNumber` = '" . $vAccountNumber . "',
			`vBIC_SWIFT_Code` = '" . $vBIC_SWIFT_Code . "',
			`vBankAccountHolderName` = '" . $vBankAccountHolderName . "' ,
			`vBankLocation` = '" . $vBankLocation . "',
			`vBankName` = '" . $vBankName . "',			
			`vPaymentEmail` = '" . $vPaymentEmail . "' $str" . $where;
    $id = $obj->sql_query($query);
    if ($id > 0) {
        $sql_query = "select vName,vLastName,vEmail,vCode,vPhone From register_driver" . $where;
        $result_data = $obj->sql_query($sql_query);
        if (count($result_data) > 0) {
            $DriverName = $result_data[0]['vName'] . ' ' . $result_data[0]['vLastName'];
            $vPhonenumber = $result_data[0]['vCode'] . ' ' . $result_data[0]['vPhone'];
            $email = $result_data[0]['vEmail'];
            $maildata['NAME'] = $DriverName;
            $maildata['EMAIL'] = $email;
            $maildata['PHONE'] = $vPhonenumber;
            $generalobj->send_email_user("BANK_DETAIL_NOTIFY_ADMIN", $maildata);
        }
    }
    echo $var_msg = $langage_lbl['LBL_BANK_DETAIL_UPDATE_MSG'];
    return $var_msg;
    exit;
}
?>
