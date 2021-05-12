<?php
include_once('../common.php');
$generalobj->check_member_login();
$keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
$iVehicleTypeId = isset($_REQUEST['iVehicleTypeId']) ? $_REQUEST['iVehicleTypeId'] : '';
$vCountry = isset($_REQUEST['vCountry']) ? $_REQUEST['vCountry'] : '';
$dBooking_date = isset($_REQUEST['dBooking_date']) ? $_REQUEST['dBooking_date'] : '';
$AppeType = isset($_REQUEST['AppeType']) ? $_REQUEST['AppeType'] : '';
function fetchtripstatustimeMAXinterval() {
    global $generalobj, $FETCH_TRIP_STATUS_TIME_INTERVAL;
    $FETCH_TRIP_STATUS_TIME_INTERVAL_ARR = explode("-", $FETCH_TRIP_STATUS_TIME_INTERVAL);
    $FETCH_TRIP_STATUS_TIME_INTERVAL_MAX = $FETCH_TRIP_STATUS_TIME_INTERVAL_ARR[1];
    return $FETCH_TRIP_STATUS_TIME_INTERVAL_MAX;
}
$cmpMinutes = ceil((fetchtripstatustimeMAXinterval() + 60) / 60);
$str_date = @date('Y-m-d H:i:s', strtotime('-' . $cmpMinutes . ' minutes'));
$ssql = " AND rd.eStatus='Active'";
if ($keyword != "") {
    $ssql .= " AND CONCAT(rd.vName,' ',rd.vLastName) like '%$keyword%'";
}
$eLadiesRide = isset($_REQUEST['eLadiesRide']) ? $_REQUEST['eLadiesRide'] : '';
$eHandicaps = isset($_REQUEST['eHandicaps']) ? $_REQUEST['eHandicaps'] : 'No';
$eChildSeat = isset($_REQUEST['eChildSeat']) ? $_REQUEST['eChildSeat'] : 'No';
$eWheelChair = isset($_REQUEST['eWheelChair']) ? $_REQUEST['eWheelChair'] : 'No';
if ($eLadiesRide == 'Yes') {
    $ssql .= " AND (rd.eFemaleOnlyReqAccept = 'Yes' OR rd.eGender = 'Female')";
}
if ($eHandicaps == 'Yes') {
    $ssql .= " AND dv.eHandiCapAccessibility = 'Yes'";
}
if ($eChildSeat == 'Yes') {
    $ssql .= " AND dv.eChildSeatAvailable = 'Yes'";
}
if ($eWheelChair == 'Yes') {
    $ssql .= " AND dv.eWheelChairAvailable = 'Yes'";
}
if (!empty($vCountry)) {
    $ssql .= " AND rd.vCountry LIKE '" . $vCountry . "'";
}
$sess_iCompanyId = isset($_REQUEST['sess_iCompanyId']) ? $_REQUEST['sess_iCompanyId'] : '';
if ($sess_iCompanyId != '') {
    $ssql .= " AND rd.iCompanyId = '" . $sess_iCompanyId . "'";
}
if ($AppeType == "UberX" && !empty($dBooking_date)) {
    $vday = date('l', strtotime($dBooking_date));
    $curr_hour = date('H', strtotime($dBooking_date));
    $next_hour = $curr_hour + 01;
    if ($curr_hour == "00") {
        $curr_hour = "12";
        $next_hour = "01";
    }
    $selected_time = $curr_hour . "-" . $next_hour;
    $ssql .= "AND vDay LIKE '%" . $vday . "%' AND dmt.vAvailableTimes LIKE '%" . $selected_time . "%'";
}
if ($AppeType == "UberX") {
    $sql = "SELECT rd.iDriverId,rd.vEmail,rd.iCompanyId, CONCAT(rd.vName,' ',rd.vLastName) AS FULLNAME,rd.vLatitude,rd.vLongitude,rd.vServiceLoc,rd.vAvailability,rd.vTripStatus,rd.tLastOnline, rd.vImage, rd.vCode, rd.vPhone,rd.tLocationUpdateDate FROM register_driver AS rd RIGHT JOIN driver_manage_timing  AS dmt ON rd.iDriverId = dmt.iDriverId  WHERE rd.vLatitude !='' AND rd.vLongitude !='' " . $ssql . " GROUP BY dmt.iDriverId";
    $db_records = $obj->MySQLSelect($sql);
    foreach ($db_records as $key => $value) {
        $sql_vehicle = "SELECT vCarType FROM `driver_vehicle` WHERE iDriverId = '" . $value['iDriverId'] . "' AND eType='UberX'";
        $dbvehicle_records = $obj->MySQLSelect($sql_vehicle);
        $db_records[$key]['vCarType'] = $dbvehicle_records[0]['vCarType'];
    }
} else {
    $sql = "SELECT rd.iDriverId,rd.vEmail,rd.iCompanyId, CONCAT(rd.vName,' ',rd.vLastName) AS FULLNAME,rd.vLatitude,rd.vLongitude,rd.vServiceLoc,rd.vAvailability,rd.vTripStatus,rd.tLastOnline,rd.tLocationUpdateDate, rd.vImage, rd.vCode, rd.vPhone, dv.vCarType FROM register_driver AS rd LEFT JOIN driver_vehicle AS dv ON dv.iDriverVehicleId=rd.iDriverVehicleId WHERE rd.vLatitude !='' AND rd.vLongitude !='' " . $ssql;
    $db_records = $obj->MySQLSelect($sql);
}


$dbDrivers = array();
for ($i = 0; $i < count($db_records); $i++) {
    $newArray = array();
    $newArray = explode(',', $db_records[$i]['vCarType']);
    if ($iVehicleTypeId == '' || (!empty($newArray) && in_array($iVehicleTypeId, $newArray))) {
        if ($db_records[$i]['vImage'] != 'NONE' && $db_records[$i]['vImage'] != '' && file_exists($tconfig["tsite_upload_images_driver_path"] . '/' . $db_records[$i]['iDriverId'] . '/2_' . $db_records[$i]['vImage'])) {
            $DriverImage = $tconfig["tsite_upload_images_driver"] . '/' . $db_records[$i]['iDriverId'] . '/2_' . $db_records[$i]['vImage'];
        } else {
            $DriverImage = $tconfig["tsite_url"] . "assets/img/profile-user-img.png";
        }
        $db_records[$i]['vImageDriver'] = $DriverImage;
        $time = time();
        $last_online_time = strtotime($db_records[$i]['tLastOnline']);
        $time_difference = $time - $last_online_time;
        $vTripStatus = $db_records[$i]['vTripStatus'];
        if ($vTripStatus != 'Active' && $db_records[$i]['vAvailability'] == "Available" && $db_records[$i]['tLocationUpdateDate'] > $str_date) {
            $db_records[$i]['vAvailability'] = "Available";
            $dbDrivers[$i] = $db_records[$i];
        } else {
            if ($vTripStatus == 'Active' || $vTripStatus == 'On Going Trip' || $vTripStatus == 'Arrived') {
                $db_records[$i]['vAvailability'] = $vTripStatus;
            } else {
                $db_records[$i]['vAvailability'] = "Not Available";
            }
            $dbDrivers[$i] = $db_records[$i];
        }
    }
}

#marker Add
$con = "";
foreach ($dbDrivers as $key => $value) {
    if ($value['vAvailability'] == "Available") {
        $statusIcon = $tconfig["tsite_url"] . "booking/img/green-icon.png";
    } else if ($value['vAvailability'] == "Active") {
        $statusIcon = $tconfig["tsite_url"] . "booking/img/red.png";
    } else if ($value['vAvailability'] == "On Going Trip") {
        $statusIcon = $tconfig["tsite_url"] . "booking/img/yellow.png";
    } else if ($value['vAvailability'] == "Arrived") {
        $statusIcon = $tconfig["tsite_url"] . "booking/img/blue.png";
    } else {
        $statusIcon = $tconfig["tsite_url"] . "booking/img/offline-icon.png";
    }
    $con .= '<li onclick="showPopupDriver(' . $value['iDriverId'] . ');"><label class="map-tab-img"><label class="map-tab-img1"><img src="' . $value['vImageDriver'] . '"></label><img src="' . $statusIcon . '"></label><p class="driver_' . $value['iDriverId'] . '">' . $generalobj->clearName($value['FULLNAME']) . ' <b>+' . $generalobj->clearMobile($value['vCode'] . $value['vPhone']) . '</b></p><a href="javascript:void(0)" class="btn btn-success assign-driverbtn" onClick=\'checkUserBalance(' . $value['iDriverId'] . ');\'>' . $langage_lbl_admin['LBL_ASSIGN_DRIVER_BUTTON'] . '</a></li>';
}
echo $con;
exit;
?>
