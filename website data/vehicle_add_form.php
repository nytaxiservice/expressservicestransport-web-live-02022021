<?php
include_once('common.php');
$generalobj->check_member_login();
$abc = 'admin,driver,company';
$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$generalobj->setRole($abc, $url);
$start = @date("Y");
$end = '1970';
$script = "Vehicle";
$tbl_name = 'driver_vehicle';
$_REQUEST['id'] = base64_decode(base64_decode(trim($_REQUEST['id'])));
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$action = ($id != '') ? 'Edit' : 'Add';
$action_show = ($id != '') ? $langage_lbl['LBL_VEHICLE_EDIT'] : $langage_lbl['LBL_VEHICLE_ACTION_ADD'];
$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
if ($_SESSION['sess_user'] == 'driver') {
    $sql = "SELECT rd.iCompanyId,c.iCountryId FROM `register_driver` AS rd LEFT JOIN country AS c ON c.vCountryCode=rd.vCountry WHERE rd.iDriverId = '" . $_SESSION['sess_iUserId'] . "'";
    $db_usr = $obj->MySQLSelect($sql);
    $iCompanyId = $db_usr[0]['iCompanyId'];
    $iCountryId = $db_usr[0]['iCountryId'];
    $iDriverId = $_SESSION['sess_iUserId'];
}
if ($_SESSION['sess_user'] == 'company') {
    $iCompanyId = $_SESSION['sess_iCompanyId'];
    $sql = "SELECT * FROM register_driver WHERE iCompanyId = '" . $_SESSION['sess_iCompanyId'] . "' AND eStatus !='Deleted' order by vName ASC";
    $db_drvr = $obj->MySQLSelect($sql);
    $sql = "SELECT cn.iCountryId FROM `company` AS c LEFT JOIN country AS cn ON cn.vCountryCode=c.vCountry WHERE c.iCompanyId = '" . $_SESSION['sess_iCompanyId'] . "'";
    $db_usr = $obj->MySQLSelect($sql);
    $iCountryId = $db_usr[0]['iCountryId'];
    $iDriverId = isset($_POST['iDriverId']) ? $_POST['iDriverId'] : '';
}
$sql = "SELECT * FROM driver_vehicle WHERE iDriverVehicleId = '" . $id . "' ";
$db_mdl = $obj->MySQLSelect($sql);

// set all variables with either post (when submit) either blank (when insert)
$vLicencePlate = isset($_POST['vLicencePlate']) ? $_POST['vLicencePlate'] : (isset($_REQUEST['vLicencePlate']) ? $_REQUEST['vLicencePlate'] : "");
$iMakeId = isset($_POST['iMakeId']) ? $_POST['iMakeId'] : (isset($_REQUEST['iMakeId']) ? $_REQUEST['iMakeId'] : "");
$iModelId = isset($_POST['iModelId']) ? $_POST['iModelId'] : (isset($_REQUEST['iModelId']) ? $_REQUEST['iModelId'] : "");
$iYear = isset($_POST['iYear']) ? $_POST['iYear'] : (isset($_REQUEST['iYear']) ? $_REQUEST['iYear'] : "");
$eStatus_check = isset($_POST['eStatus']) ? $_POST['eStatus'] : 'off';
$eHandiCapAccessibility_check = isset($_POST['eHandiCapAccessibility']) ? $_POST['eHandiCapAccessibility'] : 'off';
$eChildSeatAvailable_check = isset($_POST['eChildSeatAvailable']) ? $_POST['eChildSeatAvailable'] : 'off';
$eWheelChairAvailable_check = isset($_POST['eWheelChairAvailable']) ? $_POST['eWheelChairAvailable'] : 'off';
$vCarType = isset($_POST['vCarType']) ? $_POST['vCarType'] : '';
$vRentalCarType = isset($_POST['vRentalCarType']) ? $_POST['vRentalCarType'] : '';
$vColour = isset($_POST['vColour']) ? $_POST['vColour'] : '';
$eStatus = ($eStatus_check == 'on') ? 'Active' : 'Inactive';
$eHandiCapAccessibility = ($eHandiCapAccessibility_check == 'on') ? 'Yes' : 'No';
$eChildSeatAvailable = ($eChildSeatAvailable_check == 'on') ? 'Yes' : 'No';
$eWheelChairAvailable = ($eWheelChairAvailable_check == 'on') ? 'Yes' : 'No';
$eType = isset($_POST['eType']) ? $_POST['eType'] : 'Ride';

//$sql = "SELECT * FROM make WHERE eStatus='Active' ORDER BY vMake ASC";
$sql = "SELECT ma.* FROM make AS ma JOIN model as mo ON ma.iMakeId=mo.iMakeId WHERE ma.eStatus='Active' AND mo.eStatus='Active' GROUP BY ma.iMakeId ORDER By ma.vMake ASC";
$db_make = $obj->MySQLSelect($sql);
if (isset($_POST['submit'])) {

    if($ENABLE_EDIT_DRIVER_VEHICLE == "No" && $action == 'Edit'){
        $error_msg = $langage_lbl['LBL_EDIT_VEHICLE_DISABLED'];
        header("Location:vehicle.php?success=0&var_msg=" . $error_msg);
        exit;
     }
     
    if (!isset($_REQUEST['vCarType'])) {
        $error_msg = $langage_lbl['LBL_SELECT_CAR_TYPE'];
        header("Location:vehicle_add_form.php?id=" . base64_encode(base64_encode($id)) . "&error_msg=" . $error_msg . "&success=2&iMakeId=" . $iMakeId . "&iModelId=" . $iModelId . "&iYear=" . $iYear . "&vLicencePlate=" . $vLicencePlate);
        exit;
    }
    if ($APP_TYPE == 'UberX') {
        $vLicencePlate = 'My Services';
    } else {
        $vLicencePlate = $vLicencePlate;
    }
    $dsql = "";
    if ($id != '') {
        $dsql = " and iDriverVehicleId != '$id'";
    }
    //$sql = "select * from driver_vehicle where vLicencePlate='" . $vLicencePlate . "' and eStatus!='Deleted' " . $dsql;
    //$db_li_plate = $obj->MySQLSelect($sql);
    //
    //if (count($db_li_plate) > 0) {
    //    $error_msg = $langage_lbl['LBL_LICENCE_PLATE_EXIST'];
    //    header("Location:vehicle_add_form.php?id=" . $id . "&error_msg=" . $error_msg . "&success=2");
    //    exit;
    //} else {
        //Added By Hasmukh On 30-10-2018 For Check eAddedDeliverVehicle Value Start
        $deliverAllArr = $eAddedDeliverVehicleArr = array();
        if (isset($_POST['deliverall']) && $_POST['deliverall'] != "") {
            $deliverAllArr = explode(",", $_POST['deliverall']);
            for ($f = 0; $f < count($deliverAllArr); $f++) {
                if (in_array($deliverAllArr[$f], $_REQUEST['vCarType'])) {
                    $eAddedDeliverVehicleArr[] = 1;
                }
            }
        }
        $eAddedDeliverVehicle = "No";
        if (in_array(1, $eAddedDeliverVehicleArr)) {
            $eAddedDeliverVehicle = "Yes";
        }
        //echo $eAddedDeliverVehicle;die;
        //Added By Hasmukh On 30-10-2018 For Check eAddedDeliverVehicle Value End
        $q = "INSERT INTO ";
        $where = '';
        if ($action == 'Edit') {
            $str = ' ';
        } else {
            // if (SITE_TYPE == 'Demo')
            //     $str = ", eStatus = 'Active' ";
            // else
            //     $str = ", eStatus = 'Inactive' "; // $eStatus = 'Active'; // comment  issue to fix 212
        }
        $cartype = implode(",", $_REQUEST['vCarType']);
        $vRentalCarType = '';
        if (!empty($_REQUEST['vRentalCarType'])) {
            $vRentalCarType = implode(",", $_REQUEST['vRentalCarType']);
        }
        $rental_query = " `vRentalCarType` = '" . $vRentalCarType . "', ";
        if ($id != '') {
            $q = "UPDATE ";
            $where = " WHERE `iDriverVehicleId` = '" . $id . "'";
        }
        $query = $q . " `" . $tbl_name . "` SET
		`iModelId` = '" . $iModelId . "',
		`vLicencePlate` = '" . $vLicencePlate . "',
		`iYear` = '" . $iYear . "',
		`iMakeId` = '" . $iMakeId . "',
		`iCompanyId` = '" . $iCompanyId . "',
		`iDriverId` = '" . $iDriverId . "',
		`vColour` = '" . $vColour . "',
		`eType`  = '" . $eType . "',
                `eAddedDeliverVehicle` = '" . $eAddedDeliverVehicle . "',
		`eHandiCapAccessibility` = '" . $eHandiCapAccessibility . "',
                    `eChildSeatAvailable` = '" . $eChildSeatAvailable . "',
			`eWheelChairAvailable` = '" . $eWheelChairAvailable . "',
		$rental_query
		`vCarType` = '" . $cartype . "' $str"
                . $where;
        $obj->sql_query($query);
        $id = ($id != '') ? $id : $obj->GetInsertId();
        if ($action == "Add") {
            $sql = "SELECT * FROM company WHERE iCompanyId = '" . $iCompanyId . "'";
            $db_compny = $obj->MySQLSelect($sql);
            $sql = "SELECT * FROM register_driver WHERE iDriverId = '" . $iDriverId . "'";
            $db_status = $obj->MySQLSelect($sql);
            $maildata['EMAIL'] = $db_status[0]['vEmail'];
            $maildata['NAME'] = $db_status[0]['vName'] . " " . $db_status[0]['vLastName'];
            $maildata['DETAIL'] = "Thanks for adding your vehicle.<br />We will soon verify and check it's documentation and proceed ahead with activating your account.<br />We will notify you once your account become active and you can then take rides with passengers.";
            //$generalobj->send_email_user("VEHICLE_BOOKING",$maildata);
            //$maildata['DETAIL']="Vehicle is Added For ".$db_compny[0]['vCompany']." . Below is link to activate.<br>
            $sql1 = "SELECT mo.vTitle,m.vMake from make as m LEFT JOIN model as mo on mo.iMakeId = m.iMakeId where m.iMakeId = '" . $iMakeId . "'";
            $db_make_data = $obj->MySQLSelect($sql1);

            $maildata['MAKE'] = $db_make_data[0]['vMake'];
            $maildata['MODEL'] = $db_make_data[0]['vTitle'];
            $maildata['DETAIL'] = "You can active this Vehicle by clicking below link<br>
			<p><a href='" . $tconfig["tsite_url"] . "".SITE_ADMIN_URL."/vehicle_add_form.php?id=$id'>Active this Vehicle</a></p>";
            $generalobj->send_email_user("VEHICLE_BOOKING_ADMIN", $maildata);
            $var_msg = $langage_lbl['LBL_RECORD_INSERT_MSG'];
        } else {
            $var_msg = $langage_lbl['LBL_Record_Updated_successfully'];
        }
        header("Location:vehicle.php?success=1&var_msg=" . $var_msg);
    //}
}
// for Edit
if ($action == 'Edit') {
    if ($_SESSION['sess_user'] == 'driver') {
        $ssql = "and iDriverId = '" . $_SESSION['sess_iUserId'] . "'";
    } else {
        $ssql = "and iCompanyId = '" . $_SESSION['sess_iCompanyId'] . "'";
    }
    $sql = "SELECT * from  $tbl_name where iDriverVehicleId = '" . $id . "' " . $ssql . " ";
    $db_data = $obj->MySQLSelect($sql);
    $vLabel = $id;
    if (count($db_data) > 0) {
        foreach ($db_data as $key => $value) {
            $iMakeId = $value['iMakeId'];
            $iModelId = $value['iModelId'];
            $vLicencePlate = $value['vLicencePlate'];
            $iYear = $value['iYear'];
            $eCarX = $value['eCarX'];
            $eCarGo = $value['eCarGo'];
            $eType = $value['eType'];
            $iDriverId = $value['iDriverId'];
            $eHandiCapAccessibility = $value['eHandiCapAccessibility'];
            $eChildSeatAvailable = $value['eChildSeatAvailable'];
            $eWheelChairAvailable = $value['eWheelChairAvailable'];
            $vCarType = $value['vCarType'];
            $vRentalCarType = $value['vRentalCarType'];
            $vColour = $value['vColour'];
        }
    } else {
        header("location:vehicle.php");
    }
}
$vCarTyp = explode(",", $vCarType);
$vRentalCarTyp = explode(",", $vRentalCarType);
##################### new added ########################
$RideDeliveryIconArrStatus = $generalobj->CheckRideDeliveryFeatureDisableWeb();
$eShowRideVehicles = $RideDeliveryIconArrStatus['eShowRideVehicles'];
$eShowDeliveryVehicles = $RideDeliveryIconArrStatus['eShowDeliveryVehicles'];
?>
<!DOCTYPE html>
<html lang="en" dir="<?= (isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "") ? $_SESSION['eDirectionCode'] : 'ltr'; ?>">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title><?= $SITE_NAME ?> | <?= $langage_lbl['LBL_Vehicle']; ?> <?= $action_show; ?></title>
        <!-- Default Top Script and css -->
        <?php include_once("top/top_script.php"); ?>
        <!-- End: Default Top Script and css-->
    </head>
    <body>
        <!-- home page -->
        <div id="main-uber-page">
            <!-- Left Menu -->
            <?php include_once("top/left_menu.php"); ?>
            <!-- End: Left Menu-->
            <!-- Top Menu -->
            <?php include_once("top/header_topbar.php"); ?>
            <link rel="stylesheet" href="assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />
            <!-- End: Top Menu-->
            <!-- contact page-->
            <div class="page-contant">
                <div class="page-contant-inner page-trip-detail">
                    <h2 class="header-page trip-detail driver-detail1"><?= $action_show; ?> <?= $langage_lbl['LBL_Vehicle']; ?><a href="vehicle.php"><img src="assets/img/arrow-white.png" alt="" /><?= $langage_lbl['LBL_BACK_MY_TAXI_LISTING']; ?></a></h2>
                    <!-- trips detail page -->
                    <div class="driver-add-vehicle"> 
                        <? if ($success == 1) { ?>
                            <div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <?= $langage_lbl['LBL_Record_Updated_successfully']; ?>
                            </div>
                        <? } else if ($success == 2) { ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <?= isset($_REQUEST['error_msg']) ? $_REQUEST['error_msg'] : ' '; ?>
                            </div>
                        <? } ?>
                        <form method="post" action="">
                            <input type="hidden" name="id" value="<?= base64_encode(base64_encode($id)); ?>"/>
                            <?php if ($APP_TYPE != 'UberX') { ?>
                                <span> 
                                    <b>
                                        <label><?= $langage_lbl['LBL_CHOOSE_MAKE']; ?><span class="red">*</span></label>
                                        <select name = "iMakeId" id="iMakeId" class="custom-select-new" data-key="<?= $langage_lbl['LBL_CHOOSE_MAKE']; ?>" onChange="get_model(this.value, '')" required>
                                            <option value=""><?= $langage_lbl['LBL_CHOOSE_MAKE']; ?></option>
                                            <?php for ($j = 0; $j < count($db_make); $j++) { ?>
                                                <option value="<?= $db_make[$j]['iMakeId'] ?>" <?php if ($iMakeId == $db_make[$j]['iMakeId']) { ?> selected <?php } ?>><?= $db_make[$j]['vMake'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </b> 
                                    <b id="carmdl">
                                        <label><?= $langage_lbl['LBL_CHOOSE_VEHICLE_MODEL']; ?><span class="red">*</span></label>
                                        <select name = "iModelId" id="iModelId" data-key="<?= $langage_lbl['LBL_CHOOSE_VEHICLE_MODEL']; ?>" class="custom-select-new validate[required]" required>
                                            <option value=""><?= $langage_lbl['LBL_CHOOSE_VEHICLE_MODEL']; ?> </option>
                                            <?php for ($j = 0; $j < count($db_model); $j++) { ?>
                                                <option value="<?= $db_model[$j]['iModelId'] ?>" <?php if ($iModelId == $db_model[$j]['iModelId']) { ?> selected <?php } ?>><?= $db_model[$j]['vModel'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </b> 
                                </span> 
                                <span> 
                                    <b>
                                        <label><?= $langage_lbl['LBL_CHOOSE_YEAR']; ?><span class="red">*</span></label>
                                        <select name = "iYear" data-key="<?= $langage_lbl['LBL_CHOOSE_YEAR']; ?>" id="iYear" class="custom-select-new" required>
                                            <option value=""><?= $langage_lbl['LBL_CHOOSE_YEAR']; ?> </option>
                                            <?php for ($j = $start; $j >= $end; $j--) { ?>
                                                <option value="<?= $j ?>" <? if ($iYear == $j) { ?> selected <? } ?>><?= $j ?></option>
                                            <?php } ?>
                                        </select>
                                    </b> 
                                    <b>
                                        <label><?= $langage_lbl['LBL_LICENCE_PLATE_TXT']; ?><span class="red">*</span></label>
                                                                   <input type="text" class="form-control" name="vLicencePlate"  id="vLicencePlate" value="<?= $vLicencePlate; ?>" placeholder="<?= $langage_lbl['LBL_LICENCE_PLATE_TXT']; ?>" required> <!-- onBlur="check_licence_plate(this.value,'<?= $id ?>')"  -->
                                        <span id="plate_warning" class="error"></span>
                                    </b> 
                                </span>
                                <span>
                                    <? if ($_SESSION['sess_user'] == 'company') { ?>
                                        <b>
                                            <label><?= $langage_lbl['LBL_CHOOSE_DRIVER']; ?></label>
                                            <select name = "iDriverId" id="iDriverId" class="custom-select-new" required>
                                                <option value=""><?= $langage_lbl['LBL_CHOOSE_DRIVER']; ?></option>
                                                <?php for ($j = 0; $j < count($db_drvr); $j++) { ?>
                                                    <option value="<?= $db_drvr[$j]['iDriverId'] ?>" <? if ($db_drvr[$j]['iDriverId'] == $iDriverId) { ?> selected <? } ?>><?= $generalobj->clearName($db_drvr[$j]['vName'] . ' ' . $db_drvr[$j]['vLastName']); ?></option>
                                                <?php } ?>
                                            </select>
                                        </b>
                                    <? } ?>
                                    <b>
                                        <label><?= $langage_lbl['LBL_VEHICLE_TITLE'] . " " . $langage_lbl['LBL_COLOR_ADD_VEHICLES']; ?></label>
                                        <input type="text" class="form-control" name="vColour"  id="vColour" value="<?= $vColour; ?>"  placeholder="<?= $langage_lbl['LBL_VEHICLE_COLOR']; ?>" >
                                    </b>
                                </span>
                                <? if ($APP_TYPE != 'Delivery' && ONLYDELIVERALL != 'Yes') { ?>
                                    <?php if (isset($HANDICAP_ACCESSIBILITY_OPTION) && $HANDICAP_ACCESSIBILITY_OPTION == "Yes") { ?>
                                        <span id="handicapaccess">
                                            <b>
                                                <label><?= $langage_lbl['LBL_HANDICAP_QUESTION_ADD_VEHICLES']; ?></label>
                                                <div class="make-switch" data-on="success" data-off="warning" data-on-label='Yes' data-off-label='No'>
                                                    <input type="checkbox" name="eHandiCapAccessibility" id="eHandiCapAccessibility" <?= ($eHandiCapAccessibility == 'No') ? '' : 'checked'; ?> />
                                                </div>
                                            </b>
                                        </span>
                                    <?php } if (isset($CHILD_SEAT_ACCESSIBILITY_OPTION) && $CHILD_SEAT_ACCESSIBILITY_OPTION == "Yes") { ?>
                                        <span id="childseataccess">
                                            <b>
                                                <label><?= $langage_lbl['LBL_CHILD_SEAT_ADD_VEHICLES']; ?></label>
                                                <div class="make-switch" data-on="success" data-off="warning" data-on-label='Yes' data-off-label='No'>
                                                    <input type="checkbox" name="eChildSeatAvailable" id="eChildSeatAvailable" <?= ($eChildSeatAvailable == 'No') ? '' : 'checked'; ?> />
                                                </div>
                                            </b>
                                        </span>
                                    <?php } if (isset($WHEEL_CHAIR_ACCESSIBILITY_OPTION) && $WHEEL_CHAIR_ACCESSIBILITY_OPTION == "Yes") { ?>
                                        <span id="wheelchairaccess">
                                            <b>
                                                <label><?= $langage_lbl['LBL_WHEEL_CHAIR_ADD_VEHICLES']; ?></label>
                                                <div class="make-switch" data-on="success" data-off="warning" data-on-label='Yes' data-off-label='No'>
                                                    <input type="checkbox" name="eWheelChairAvailable" id="eWheelChairAvailable" <?= ($eWheelChairAvailable == 'No') ? '' : 'checked'; ?> />
                                                </div>
                                            </b>
                                        </span>
                                    <?php
                                    }
                                }
                                ?>

                                <h3><?= $langage_lbl['LBL_Car_Type']; ?><span class="red">*</span></h3>
<?php } ?>
                            <div class="car-type-vehicle ">
                                <div id="vehicleTypes001">
                                </div>
                                <strong><input type="submit" class="save-vehicle" name="submit" id="submit" value="<?= $action_show; ?> <?= $langage_lbl['LBL_Vehicle']; ?>"> </strong>
                            </div>
                            <!-- -->
                        </form>
                    </div>
                    <div style="clear:both;"></div>
                </div>
            </div>
            <!-- footer part -->
<?php include_once('footer/footer_home.php'); ?>
            <!-- footer part end -->
            <!-- End:contact page-->
            <div style="clear:both;"></div>
        </div>
        <!-- home page end-->
        <!-- Footer Script -->
        <?php include_once('top/footer_script.php'); ?>
        <script src="assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>
<?php if ($action == 'Edit') { ?>
            <script>
                                            $(document).ready(function () {
                                                //window.onload = function () {
                                                get_model('<?php echo $db_mdl[0]['iMakeId']; ?>', '<?php echo $db_mdl[0]['iModelId']; ?>');
                                                get_vehicleType('<?= $iDriverId; ?>', '<?= $vCarType; ?>', '<?= $eType ?>', '<?= $vRentalCarType ?>');
                                            });
            </script>
<? } else { ?>
            <script>
                //window.onload = function () {
                $(document).ready(function () {
                    var appType = '<?= $APP_TYPE ?>';
                    if (appType == 'Ride-Delivery-UberX' || appType == 'Ride-Delivery') {
                        SelectedAppType = 'Ride';
                    } else if (appType == 'Delivery') {
                        SelectedAppType = 'Deliver';
                    } else {
                        SelectedAppType = appType;
                    }
                    get_vehicleType('', '', SelectedAppType);
                });
            </script> 
        <?php } ?>
<?php if ($error_msg != ' ') { ?>
            <script>
                window.onload = function () {
                    get_model('<?php echo $iMakeId; ?>', '<?php echo $iModelId; ?>');
                };
            </script>
<? } ?>
        <script>
            $('#etypedelivery').on('change', function () {
                get_vehicleType('<?= $iDriverId; ?>', '<?php echo $vCarType; ?>', this.value, '<?= $vRentalCarType ?>');
                if (this.value == 'Delivery') {
                    $("#handicapaccess,#childseataccess,#wheelchairaccess").hide();
                } else {
                    $("#handicapaccess,#childseataccess,#wheelchairaccess").show();
                }
            });
<?php if ($_SESSION['sess_user'] == 'company') { ?>
                $('#iDriverId').on('change', function () {
                    get_vehicleType(this.value, '<?php echo $vCarType; ?>', $("#etypedelivery").val(), '<?= $vRentalCarType ?>');
                });
    <?
} else {
    if ($APP_TYPE == 'Ride') {
        ?>
                    get_vehicleType('<?= $iDriverId; ?>', '<?= $vCarType; ?>', '<?= $eType ?>', '<?= $vRentalCarType ?>');
        <?
    }
}
?>
            function get_vehicleType(iDriverId, selected = '', eType = '', rentalselected = '') {
                if (eType == 'Delivery') {
                    var eType = 'Deliver';
                } else {
                    var eType = eType;
                }
                $("#vehicleTypes001").html('Wait...');
                var request = $.ajax({
                    type: "POST",
                    url: 'ajax_find_vehicleType.php',
                    data: "iDriverId=" + iDriverId + "&selected=" + selected + "&eType=" + eType + "&Front=front&rentalselected=" + rentalselected,
                    success: function (data) {
                        $("#vehicleTypes001").html(data);
                    }
                });
                request.fail(function (jqXHR, textStatus) {
                    alert("Request failed: " + textStatus);
                });
            }
            function get_model(model, modelid) {
                var request = $.ajax({
                    type: "POST",
                    url: 'ajax_find_model_new.php',
                    data: "action=get_model&model=" + model + "&iModelId=" + modelid,
                    success: function (data) {
                        $("#iModelId").empty().append(data);
                        var selectedOption = $('#iModelId').find(":selected").text();
                        if (selectedOption != "") {
                            $('#iModelId').next(".holder").text(selectedOption);
                        }
                    }
                });
                request.fail(function (jqXHR, textStatus) {
                    alert("Request failed: " + textStatus);
                });
            }
        </script>
        <!-- End: Footer Script -->
    </body>
</html>