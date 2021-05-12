<?php

include_once('../common.php');

if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
if ($default_lang == "") {
    $default_lang = "EN";
}
////$generalobjAdmin->check_member_login();
$countryId = isset($_REQUEST['countryId']) ? $_REQUEST['countryId'] : '';
$iVehicleTypeId = isset($_REQUEST['iVehicleTypeId']) ? $_REQUEST['iVehicleTypeId'] : '';
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
$eType = isset($_REQUEST['eType']) ? $_REQUEST['eType'] : 'Ride';

$sql = "SELECT iCountryId FROM country WHERE vCountryCode = '" . $countryId . "'";
$countryarray = $obj->MySQLSelect($sql);
$countryid = $countryarray[0]['iCountryId'];

$locations_where = "";
if (count($userObj->locations) > 0) {
    $locations = implode(', ', $userObj->locations);
    $locations_where = " AND vt.iLocationid IN(-1, {$locations}) ";
}
//Added By HJ On 06-06-2019 For Get All Vehicle Category Status Start
$getAllVehicleData = $obj->MySQLSelect("SELECT iVehicleCategoryId,eStatus FROM vehicle_category");
$vehicleStatusArr = array();
for ($r = 0; $r < count($getAllVehicleData); $r++) {
    $vehicleStatusArr[$getAllVehicleData[$r]['iVehicleCategoryId']] = $getAllVehicleData[$r]['eStatus'];
}
//Added By HJ On 06-06-2019 For Get All Vehicle Category Status End
if ($type == 'getVehicles') {
    if ($eType == "UberX") {
        $whereParentId = "";
        if ($parent_ufx_catid > 0) {
            $whereParentId = " AND vc.iParentId='" . $parent_ufx_catid . "'";
        }
        $sql23 = "SELECT vt.*,vc.iParentId,vc.vCategory_EN,lm.vLocationName FROM `vehicle_type` AS vt LEFT JOIN `country` AS c ON c.iCountryId=vt.iCountryId LEFT JOIN vehicle_category as vc on vc.iVehicleCategoryId = vt.iVehicleCategoryId left join location_master as lm ON lm.iLocationId = vt.iLocationid WHERE (lm.iCountryId='" . $countryid . "' OR vt.iLocationid = '-1') $whereParentId AND vt.eType='" . $eType . "' AND vt.ePoolStatus='No' AND vc.eStatus = 'Active' AND vt.eStatus = 'Active' $locations_where ORDER BY vt.iVehicleTypeId ASC";
    } else {
        $sql23 = "SELECT vt.*,lm.vLocationName FROM `vehicle_type` AS vt LEFT JOIN `country` AS c ON c.iCountryId=vt.iCountryId left join location_master as lm ON lm.iLocationId = vt.iLocationid WHERE (lm.iCountryId='" . $countryid . "' OR vt.iLocationid = '-1') AND vt.eType='" . $eType . "' AND vt.eStatus = 'Active' AND vt.ePoolStatus='No' AND vt.eStatus = 'Active' $locations_where ORDER BY vt.iVehicleTypeId ASC";
    }
    $db_carType = $obj->MySQLSelect($sql23);
    if ($eType == "UberX") {
        echo '<option value="" >Select Service Type</option>';
    } else {
        echo '<option value="" >Select Vehicle Type</option>';
    }
    foreach ($db_carType as $db_car) {
        //Added By HJ On 06-06-2019 For Check Vehicle Category Parent Id Status Start
        $iParentId = 0;
        $enableVehile = 1;
        if (isset($db_car['iParentId']) && $db_car['iParentId'] > 0) {
            $iParentId = $db_car['iParentId'];
            $enableVehile = 0;
            if (isset($vehicleStatusArr[$iParentId]) && $vehicleStatusArr[$iParentId] == "Active") {
                $enableVehile = 1;
            }
        }
        //Added By HJ On 06-06-2019 For Check Vehicle Category Parent Id Status End
        if ($enableVehile == 1) {
            $selected = '';
            if ($db_car['iVehicleTypeId'] == $iVehicleTypeId) {
                $selected = "selected=selected";
            }
            if ($db_car['vLocationName'] != '') {
                $location = " (" . $db_car['vLocationName'] . ")";
            } else {
                $location = " (All location)"; //added by SP when all location is selected then show ALl on 31-07-2019
            }
            if ($eType == "UberX") {
                echo "<option value=" . $db_car['iVehicleTypeId'] . " " . $selected . ">" . $db_car['vCategory_'.$default_lang] . "-" . $db_car['vVehicleType'] . $location . "</option>";
            } else {
                echo "<option value=" . $db_car['iVehicleTypeId'] . " " . $selected . ">" . $db_car['vVehicleType_'.$default_lang] . $location . "</option>";
            }
        }
    }
    exit;
}
?>
