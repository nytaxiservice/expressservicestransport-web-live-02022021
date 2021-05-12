<?
include_once("common.php");
include_once("generalFunctions.php");
$iDriverId = isset($_REQUEST['iDriverId']) ? $_REQUEST['iDriverId'] : '';
$vCarType = isset($_REQUEST['selected']) ? $_REQUEST['selected'] : '';
$vCarTyp = explode(",", $vCarType);

$vRentalCarType = isset($_REQUEST['rentalselected']) ? $_REQUEST['rentalselected'] : '';
$vRentalCarTyp = explode(",", $vRentalCarType);

$eType = isset($_REQUEST['eType']) ? $_REQUEST['eType'] : '';
$Front = isset($_REQUEST['Front']) ? $_REQUEST['Front'] : '';

$catdata = serviceCategories;
$allservice_cat_data = json_decode($catdata, true);
if(isset($_SESSION['sess_lang']) && $_SESSION['sess_lang'] != ""){
    $default_lang = $_SESSION['sess_lang'];
}
$eStatus = $eStatusQuery = $eQuery = '';
//Added By HJ On 10-01-2019 For Solve Bug - 6166 Start
if(ONLYDELIVERALL == "Yes"){
    $eQuery .= " AND eType = 'DeliverAll'";
} else {
    if ($APP_TYPE == "Delivery") {
        $eQuery .= " AND eType = 'Deliver'";
    } else if ($APP_TYPE == "Ride-Delivery" || $APP_TYPE == "Ride-Delivery-UberX") {
        $isMultipleVehicleCategoryAvailable = true;
        $eQuery .= " AND eType != 'UberX'";
        ### Checking Vehicles For Ride , Delivery Icons and Banners Availability ##
        $CheckRideDeliveryFeatureDisable_Arr = CheckRideDeliveryFeatureDisable();
        $eShowRideVehicles = $CheckRideDeliveryFeatureDisable_Arr['eShowRideVehicles'];
        $eShowDeliveryVehicles = $CheckRideDeliveryFeatureDisable_Arr['eShowDeliveryVehicles'];
        $eShowDeliverAllVehicles = $CheckRideDeliveryFeatureDisable_Arr['eShowDeliverAllVehicles'];
        if ($eShowRideVehicles == "No") {
            $eQuery .= " AND eType != 'Ride'";
        }
        if ($eShowDeliveryVehicles == "No") {
            $eQuery .= " AND eType != 'Deliver'";
        }
        if ($eShowDeliverAllVehicles == "No" || DELIVERALL == "No") {
            $eQuery .= " AND eType != 'DeliverAll'";
        }
    } else {
        $eQuery .= " AND eType = '" . $APP_TYPE . "'";
    }
}
//Added By HJ On 10-01-2019 For Solve Bug - 6166 End
//Added By HJ On 23-11-2019 For Solved 141 Mantis Issue = 1740 Start
if($POOL_ENABLE == "No"){
    $eQuery .= " AND vt.ePoolStatus='No'";
}
//Added By HJ On 23-11-2019 For Solved 141 Mantis Issue = 1740 End
if ($eStatus == '') {
    $eStatusQuery = "AND vt.eStatus = 'Active'"; //added by SP only active vehicle shown  on 04-10-2019
}
if (!empty($iDriverId)) {
    $userSQL = "SELECT c.iCountryId FROM register_driver AS rd LEFT JOIN country AS c ON c.vCountryCode=rd.vCountry where rd.iDriverId='" . $iDriverId . "'";
    $drivers = $obj->MySQLSelect($userSQL);
    $iCountryId = $drivers[0]['iCountryId'];
    if ($iCountryId != '') {
        $vehicle_type_sql = "SELECT vt.*,c.vCountry,ct.vCity,st.vState,lm.vLocationName from  vehicle_type as vt left join country as c ON c.iCountryId = vt.iCountryId left join state as st ON st.iStateId = vt.iStateId left join city as ct ON ct.iCityId = vt.iCityId left join location_master as lm ON lm.iLocationId = vt.iLocationid where vt.eType !='UberX' AND (lm.iCountryId='" . $iCountryId . "' OR vt.iLocationId = '-1') $eQuery $eStatusQuery";
    } else {
       $vehicle_type_sql = "SELECT vt.*,c.vCountry,ct.vCity,st.vState,lm.vLocationName from  vehicle_type as vt left join country as c ON c.iCountryId = vt.iCountryId left join state as st ON st.iStateId = vt.iStateId left join city as ct ON ct.iCityId = vt.iCityId left join location_master as lm ON lm.iLocationId = vt.iLocationid where vt.eType !='UberX' AND vt.iLocationId = '-1' $eQuery $eStatusQuery";
    }
} else {
    $vehicle_type_sql = "SELECT vt.*,c.vCountry,ct.vCity,st.vState,lm.vLocationName from  vehicle_type as vt left join country as c ON c.iCountryId = vt.iCountryId left join state as st ON st.iStateId = vt.iStateId left join city as ct ON ct.iCityId = vt.iCityId left join location_master as lm ON lm.iLocationId = vt.iLocationid where vt.eType !='UberX' $eQuery $eStatusQuery";
}
$vehicle_type_data = $obj->MySQLSelect($vehicle_type_sql);


function filterElementFromArray($array, $key, $value, $New = '') {
    $DeliverAllvehicles = array();
    foreach ($array as $subKey => $subArray) {
        if ($subArray[$key] == $value) {
            if ($New == 'New') {
                $DeliverAllvehicles[] = $subArray;
            } else {
                unset($array[$subKey]);
            }
        }
    }
    if ($New == 'New') {
        return $DeliverAllvehicles;
    } else {
        return $array;
    }
}

$vehicle_type_data_new = filterElementFromArray($vehicle_type_data, "eType", "DeliverAll");
$DeliverAllvehiclesData = filterElementFromArray($vehicle_type_data, "eType", "DeliverAll", "New");
?>
<ul>
    <?php
    foreach ($vehicle_type_data_new as $key => $value) {
        $vname = $value['vVehicleType_' . $default_lang];
        $vCountry = $value['vCountry'];
        $vCity = $value['vCity'];
        $vState = $value['vState'];
        $db_etype = $value['eType'];
        ?>
        <?php
        $localization = '';
        if (!empty($value['vLocationName'])) {
            $localization .= $value['vLocationName'];
        }
        if ($db_etype == 'Ride') {
            $eTypeLangName = $langage_lbl['LBL_RIDE_TXT'];
        } else if ($db_etype == 'Deliver') {
            $eTypeLangName = $langage_lbl['LBL_DELIVERY_TXT'];
        }
        ?>
        <li>
            <b>
                <div><?php echo $vname . " (" . $eTypeLangName . ")"; ?></div>
                <div style="font-size: 12px;text-transform: capitalize;">
                    <?php
                    if (!empty($value['vLocationName'])) {
                        echo "( " . $langage_lbl['LBL_LOCATION_FOR_FRONT'] . " : " . $localization . ")";
                    } else if ($value['iLocationid'] == "-1") {
                        echo "(  " . $langage_lbl['LBL_ALL_LOCATIONS'] . " )";
                    }
                    ?>	
                </div>
            </b>
            <?php if ($value['eType'] != 'DeliverAll') { ?>
                <div class="make-switch make-swith001" data-on="success" data-off="warning">
                    <input type="checkbox" class="chk" name="vCarType[]" id="vCarType_<?= $key; ?>" <?php if (in_array($value['iVehicleTypeId'], $vCarTyp)) { ?>checked<?php } ?> value="<?= $value['iVehicleTypeId'] ?>"/>
                </div>
            <?php } else { ?>
                <div class="make-switch make-swith001 radio2" data-on="success" data-off="warning" style="margin:10px 0 0">
                    <input type="radio" name="vCarType[]" <?php if (in_array($value['iVehicleTypeId'], $vCarTyp)) { ?>checked<?php } ?> value="<?= $value['iVehicleTypeId'] ?>">
                </div>
            <?php } ?>

            <?php if (ENABLE_RENTAL_OPTION == 'Yes') { ?>
                <?
                $checkrentalquery = "SELECT count(iRentalPackageId) as totalrental FROM `rental_package` WHERE iVehicleTypeId = '" . $value['iVehicleTypeId'] . "'";
                $rental_data = $obj->MySQLSelect($checkrentalquery);
                if ($rental_data[0]['totalrental'] > 0) {
                    ?>
                    <div id="<?= 'RentalVehicleType_' . $key; ?>" style="display: none;">
                        <div class="RentalCheckbox">
                            <input type="checkbox" class="chk" name="vRentalCarType[]" <?php if (in_array($value['iVehicleTypeId'], $vRentalCarTyp)) { ?>checked<?php } ?> value="<?= $value['iVehicleTypeId'] ?>"/> Accept rental request for <?php echo $vname; ?> vehicle type?
                        </div>
                    </div>
                    <?
                }
            }
            ?>
        </li>

        <?
    }

    if (ENABLE_RENTAL_OPTION == 'Yes') {
        foreach ($vehicle_type_data_new as $key => $value) {
            ?>
            <script>
                $(function () {
                    if ($("#vCarType_" +<?= $key ?>).is(':checked')) {
                        $("#RentalVehicleType_" +<?= $key ?>).show();
                    } else {
                        $("#RentalVehicleType_" +<?= $key ?>).hide();
                    }

                    $("#vCarType_" +<?= $key ?>).on('change.bootstrapSwitch', function (event)
                    {
                        if ($(this).is(':checked'))
                        {
                            $("#RentalVehicleType_" +<?= $key ?>).show();
                        } else {
                            $("#RentalVehicleType_" +<?= $key ?>).hide();
                        }
                    })
                });
            </script>
            <?
        }
    }
    ?>
</ul>
<?
if (count($DeliverAllvehiclesData) > 0) {
    if (count($allservice_cat_data) > 1) {
        $headinglabel = $langage_lbl['LBL_DELIVERY_ALL_VEHICLE_TYPE'];
    } else {
        $sql1 = "SELECT * FROM language_label_" . $allservice_cat_data[0]['iServiceId'] . " WHERE vLabel = 'LBL_DELIVERY_VEHICLE_TYPE' AND vCode='" . $_SESSION['sess_lang'] . "'";
        $lan_lbl_data = $obj->MySQLSelect($sql1);
        $headinglabel = $lan_lbl_data[0]['vValue'];
    }
    ?>

    <div style="float:left; width:100%;">
        <h4 class="col-lg-6" style="padding:0px;"><?= $headinglabel ?> <small>(<?= $langage_lbl['LBL_CHOOSE_ONE_VEHICLE'] ?>)</small></h4>
    </div>
<? } ?>
<ul>
    <?
    $vIds = "";
    foreach ($DeliverAllvehiclesData as $key => $value) {
        $vname = $value['vVehicleType_' . $default_lang];
        $vCountry = $value['vCountry'];
        $vCity = $value['vCity'];
        $vState = $value['vState'];
        $db_etype = $value['eType'];
        $vIds .= "," . $value['iVehicleTypeId'];
        ?>
        <?php
        $localization = '';
        if (!empty($value['vLocationName'])) {
            $localization .= $value['vLocationName'];
        }

        if (count($allservice_cat_data) > 1) {
            $db_etype_name = $langage_lbl['LBL_DELIVERALL'];
        } else {
            $db_etype_name = '';
        }
        ?>
        <li>
            <b>
                <? if (!empty($db_etype_name)) { ?>
                    <div><?php echo $vname . " (" . $db_etype_name . ")"; ?></div>
                <? } else { ?>
                    <div><?php echo $vname; ?></div>
                <? } ?>

                <div style="font-size: 12px;text-transform: capitalize;">
                    <?php
                    if (!empty($value['vLocationName'])) {
                        echo "( " . $langage_lbl['LBL_LOCATION_FOR_FRONT'] . " : " . $localization . ")";
                    } else if ($value['iLocationid'] == "-1") {
                        echo "( " . $langage_lbl['LBL_ALL_LOCATIONS'] . " )";
                    }
                    ?>	
                </div>
            </b>
            <?php if ($value['eType'] != 'DeliverAll') { ?>
                <div class="make-switch make-swith001" data-on="success" data-off="warning">
                    <input type="checkbox" class="chk" name="vCarType[]" id="vCarType_<?= $key; ?>" <?php if (in_array($value['iVehicleTypeId'], $vCarTyp)) { ?>checked<?php } ?> value="<?= $value['iVehicleTypeId'] ?>"/>
                </div>
            <?php } else { ?>
                <div class="make-switch make-swith001 radio2" data-on="success" data-off="warning" style="margin:10px 0 0">
                    <input type="radio" name="vCarType[]" <?php if (in_array($value['iVehicleTypeId'], $vCarTyp)) { ?>checked<?php } ?> value="<?= $value['iVehicleTypeId'] ?>">
                </div>
            <?php } ?>
            <?php if (ENABLE_RENTAL_OPTION == 'Yes') { ?>
                <?
                $checkrentalquery = "SELECT count(iRentalPackageId) as totalrental FROM `rental_package` WHERE iVehicleTypeId = '" . $value['iVehicleTypeId'] . "'";
                $rental_data = $obj->MySQLSelect($checkrentalquery);
                if ($rental_data[0]['totalrental'] > 0) {
                    ?>
                    <div id="<?= 'RentalVehicleType_' . $key; ?>" style="display:none;">
                        <div class="RentalCheckbox">
                            <input type="checkbox" class="chk" name="vRentalCarType[]" <?php if (in_array($value['iVehicleTypeId'], $vRentalCarTyp)) { ?>checked<?php } ?> value="<?= $value['iVehicleTypeId'] ?>"/> Accept rental request for <?php echo $vname; ?> vehicle type?
                        </div>
                    </div>
                    <?
                }
            }
            ?>
        </li>
    <? } ?>
    <input type="hidden" name="deliverall" value="<?php echo trim($vIds, ","); ?>">
</ul>
<script>
    $(".make-swith001").bootstrapSwitch();
    $(".radio2").on('switch-change', function () {
        $(".radio2").bootstrapSwitch('toggleRadioStateAllowUncheck', true);
    });
</script>

