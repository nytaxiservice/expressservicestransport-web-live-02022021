<?php

include_once('configuration_variables.php');

// This Code For APP.
 
$service_categories_ids_arr = [1, 2, 3, 4, 5];
if (isset($_REQUEST['DEFAULT_SERVICE_CATEGORY_ID']) && $_REQUEST['DEFAULT_SERVICE_CATEGORY_ID'] != "") {
    $service_categories_ids_arr_new_arr = $_REQUEST['DEFAULT_SERVICE_CATEGORY_ID'];
    $service_categories_ids_arr = (array) $service_categories_ids_arr_new_arr;
}

$Lsql = "SELECT vCode,vTitle FROM language_master WHERE eDefault = 'Yes'";
$Data_langArr = $obj->MySQLSelect($Lsql);

$langugaeCode = isset($_REQUEST["vLang"]) ? ($_REQUEST["vLang"] == "" ? $Data_langArr[0]['vCode'] : $_REQUEST["vLang"]) : $Data_langArr[0]['vCode'];
if ($langugaeCode != '') {
    $sql = "SELECT  `vCode` FROM  `language_master` WHERE eStatus = 'Active' AND `vCode` = '" . $langugaeCode . "' ";
    $check_label = $obj->MySQLSelect($sql);

    $sql = "SELECT  `vCode` FROM  `language_master` WHERE eStatus = 'Active' AND `eDefault` = 'Yes' ";
    $default_label = $obj->MySQLSelect($sql);

    $langugaeCode = (isset($check_label[0]['vCode']) && $check_label[0]['vCode']) ? $check_label[0]['vCode'] : $default_label[0]['vCode'];
} else {
    $sql = "SELECT `vCode` FROM  `language_master` WHERE eStatus = 'Active' AND `eDefault` = 'Yes' ";
    $default_label = $obj->MySQLSelect($sql);
    $langugaeCode = $default_label[0]['vCode'];
}

if (isset($_SESSION['sess_lang']) && $_SESSION['sess_lang'] != "") {
    $langugaeCode = $_SESSION['sess_lang'];
}
$enablesevicescategory = implode(",", $service_categories_ids_arr);

$Ssql = "SELECT iServiceId,vServiceName_" . $langugaeCode . " as vServiceName,vImage FROM  `service_categories` WHERE iServiceId IN (" . $enablesevicescategory . ") AND eStatus='Active' order by iDisplayOrder ASC";
$ServiceData = $obj->MySQLSelect($Ssql);

$serviceCategoriesTmp = array();
if (!empty($ServiceData)) {
    foreach ($ServiceData as $key => $value) {
        if ($value['vImage'] != '') {
            $value['vImage'] = $tconfig["tsite_upload_service_categories_images"] . $value['vImage'];
        }
        $serviceCategoriesTmp[] = $value;
    }
}

$iServiceId = isset($_REQUEST["iServiceId"]) ? $_REQUEST["iServiceId"] : $ServiceData[0]['iServiceId'];
if (empty($_REQUEST["iServiceId"])) {
    $iServiceId = $ServiceData[0]['iServiceId'];
    $_REQUEST["iServiceId"] = $iServiceId;
}
define('serviceCategories', json_encode($serviceCategoriesTmp));
// End Code
//$host_system = "uberridedelivery4"; 
//Ride=cubetaxiplus   Ride+Delivery = uberridedelivery4,  Deliveryonly = uberdelivery4  
if ($hst_var == "ufxforall") {
    $host_system = "ufxforall";
} elseif ($hst_var == "deliveryonly") {
    $host_system = "uberdelivery4";
} elseif ($hst_var == "ridedelivery") {
    $host_system = "uberridedelivery4";
} elseif ($hst_var == "massage") {
    $host_system = "ufxforall";
} elseif ($hst_var == "doctor") {
    $host_system = "ufxforall";
} elseif ($hst_var == "beautician") {
    $host_system = "ufxforall";
} elseif ($hst_var == "carwash") {
    $host_system = "ufxforall";
} elseif ($hst_var == "dogwalking") {
    $host_system = "ufxforall";
} elseif ($hst_var == "towtruck_v4") {
    $host_system = "ufxforall";
} else {
    $host_system = "cubetaxiplus";
}


###### deliverall label changes #################
if (isset($_SESSION['sess_user']) && $_SESSION['sess_user'] == 'company') {
    $query = "SELECT iServiceId FROM company WHERE iCompanyId = '" . $_SESSION['sess_iUserId'] . "'";
    $dbQueryData = $obj->MySQLSelect($query);
    if (count($dbQueryData) > 0) {
        $iServiceIdWeb = $dbQueryData[0]['iServiceId'];
    } else {
        $iServiceIdWeb = $ServiceData[0]['iServiceId'];
    }
} else {
    $iServiceIdWeb = $ServiceData[0]['iServiceId'];
}

if ($iServiceIdWeb != "0") {
    $sql = "SELECT vLabel,vValue,LanguageLabelId FROM language_label_" . $iServiceIdWeb . " WHERE vCode='" . $_SESSION['sess_lang'] . "'";
    $db_lbl = $obj->MySQLSelect($sql);
    if (!empty($db_lbl)) {
        foreach ($db_lbl as $key => $value) {
            if (isset($_SESSION['sess_editingToken']) && $_SESSION['sess_editingToken'] == $db_config[0]['vValue']) {
                $langage_lbl[$value['vLabel']] = "<em class='label-dynmic'><i class='fa fa-edit label-i' data-id='" . $value['LanguageLabelId'] . "' data-value='main'></i>" . $value['vValue'] . "</em>";
            } else {
                $langage_lbl[$value['vLabel']] = $value['vValue'];
            }
        }
    }
}
if (empty($langage_lbl)) {
    $sql = "select vLabel,vValue,LanguageLabelId from language_label where vCode='" . $_SESSION['sess_lang'] . "'";
    $db_lbl = $obj->MySQLSelect($sql);
    foreach ($db_lbl as $key => $value) {
        if (isset($_SESSION['sess_editingToken']) && $_SESSION['sess_editingToken'] == $db_config[0]['vValue']) {
            $langage_lbl[$value['vLabel']] = "<em class='label-dynmic'><i class='fa fa-edit label-i' data-id='" . $value['LanguageLabelId'] . "' data-value='other'></i>" . $value['vValue'] . "</em>";
        } else {
            $langage_lbl[$value['vLabel']] = $value['vValue'];
        }
    }

    $sql_en = "SELECT  `vLabel` , `vValue`  FROM  `language_label` WHERE  `vCode` = 'EN' UNION SELECT `vLabel` , `vValue`  FROM  `language_label_other` WHERE  `vCode` = 'EN'";
    $all_label_en = $obj->MySQLSelect($sql_en);
    if (count($all_label_en) > 0) {
        for ($i = 0; $i < count($all_label_en); $i++) {
            $vLabel_tmp = $all_label_en[$i]['vLabel'];
            $vValue_tmp = $all_label_en[$i]['vValue'];
            if (isset($langage_lbl[$vLabel_tmp]) || array_key_exists($vLabel_tmp, $langage_lbl)) {
                if ($langage_lbl[$vLabel_tmp] == "") {
                    $langage_lbl[$vLabel_tmp] = $vValue_tmp;
                }
            } else {
                $langage_lbl[$vLabel_tmp] = $vValue_tmp;
            }
        }
    }

    /* $sql="select vLabel,vValue,LanguageLabelId from language_label_other where vCode='".$_SESSION['sess_lang']."'";
      $db_lbl=$obj->MySQLSelect($sql);
      foreach ($db_lbl as $key => $value) {
      if(isset($_SESSION['sess_editingToken']) && $_SESSION['sess_editingToken'] == $db_config[0]['vValue']){
      $langage_lbl[$value['vLabel']] = "<em class='label-dynmic'><i class='fa fa-edit label-i' data-id='".$value['LanguageLabelId']."' data-value='other'></i>".$value['vValue']."</em>";
      }else {
      $langage_lbl[$value['vLabel']] = $value['vValue'];
      }
      } */
}

if ($ServiceData[0]['iServiceId'] > 0) {
    $iServiceIdWeb = $ServiceData[0]['iServiceId'];

    $sql = "select vLabel,vValue from language_label_" . $iServiceIdWeb . " where vCode='EN'";
    $db_lbl_admin = $obj->MySQLSelect($sql);

    foreach ($db_lbl_admin as $key => $value) {
        $langage_lbl_admin[$value['vLabel']] = $value['vValue'];
    }
}

if (isset($_REQUEST['debug'])) {

    $_REQUEST['debug'] = empty($_REQUEST['debug']) ? E_ALL : $_REQUEST['debug'];
    ini_set('display_errors', 'On');
    ini_set('error_reporting', 1);
    error_reporting($_REQUEST['debug']);
}

/* include_once(dirname(dirname(dirname(__FILE__))).'/admin/library/helper.php');
  include_once(dirname(dirname(dirname(__FILE__))).'/admin/library/User.php');

  $userObj = new Admin\library\User(); */

$exclude_login = [
    'index.php',
    'ajax_login_action.php'
];

if (strpos($_SERVER['REQUEST_URI'], "/".SITE_ADMIN_URL) !== false) {

    include_once(dirname(dirname(dirname(__FILE__))) . '/'.SITE_ADMIN_URL.'/library/common_include.php');

    $userObj = new Admin\library\User();

    if (!in_array(basename($_SERVER['REQUEST_URI']), $exclude_login)) {
        $userObj->isLogin(true);
    }
}
?>