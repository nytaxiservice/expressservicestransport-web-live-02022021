<?php
include_once('common.php');

$sql = "SELECT * FROM setup_info";
$db_setup_info = $obj->MySQLSelect($sql);
/* Give Below File Permission First
 * project/setup_info/uploads
 * project/webimages and it's all folder
 * Permission Folder For Auto Delete Files : Root,Admin,Admin/action folder
 * Changes In File
 * 1) project/assets/libraries/configuration_variables.php - Change Folder Name
 * 2) project/assets/libraries/db_info.php - Change Db Info
 */
$query = "SELECT * FROM service_categories WHERE eStatus='Active'";
$db_service_categories = $obj->MySQLSelect($query);

$array_column = array_column($db_service_categories, 'iServiceId');
$matchresult = array_diff($array_column, $service_categories_ids_arr);
$matchresult2 = array_diff($service_categories_ids_arr, $array_column);
$errorcountsystemvalidation = $fileCount = 0;
?>
<style>
    ol.validation li {
        background: #cce5ff;
        margin: 5px;
        padding: 10px;
    }
</style>
<?php if (isset($filePanel) && $filePanel == "Admin") { ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <?
}
$deleteFileArr = array();
if (count($db_setup_info) > 0) {
    $ePackageType = $db_setup_info[0]['ePackageType'];
    $eProductType = $db_setup_info[0]['eProductType'];
    $eDeliveryType = $db_setup_info[0]['eDeliveryType'];
    $eEnableKiosk = $eEnableHotel = "No";
    $eConfigurationApplied = "Yes";
    if (isset($db_setup_info[0]['eEnableKiosk'])) {
        $eEnableKiosk = $db_setup_info[0]['eEnableKiosk'];
    }
    if (isset($db_setup_info[0]['eEnableHotel'])) {
        $eEnableHotel = $db_setup_info[0]['eEnableHotel'];
    }
    if (isset($db_setup_info[0]['eConfigurationApplied'])) {
        $eConfigurationApplied = $db_setup_info[0]['eConfigurationApplied'];
    }
    $applyConfiguration = 0; //0-Not Run Configuration Setting,1-Set Default Configuration Setting As Per Product and Package
    if ($eConfigurationApplied == "No") {
        $applyConfiguration = 1; //0-Not Run Configuration Setting,1-Set Default Configuration Setting As Per Product and Package
    }
    $setupShFile = dirname(__FILE__) . "/setup_info/setup.sh";
    $shFileCommand = "sh " . $setupShFile;
    $permissionTxt = "";
    if (!is_writable($setupShFile)) {
        $permissionTxt = "Note: Please assign 777 Permission to " . $setupShFile . " File";
    }
    //echo $permissionTxt;die;
    echo "<div style='background:#ff0000;padding:20px;color:#ffffff;font-size:25px;text-align:center;display:none;' id='permissionmsg'>Please tell Chirag sir or Anurag sir to run below command from command prompt to delete below files.<br><b><u>" . $shFileCommand . "</u></b><br>" . $permissionTxt . "</div>";
    echo '<ol class="validation">';
    /* if(ENABLEHOTELPANEL == 'Yes'){ 
      $errorcountsystemvalidation +=1;
      echo "<li>Please Update ENABLEHOTELPANEL value as 'No' in configuration_variables file.</li>";
      } */
    if (strtoupper($ePackageType) != strtoupper($PACKAGE_TYPE)) {
        $errorcountsystemvalidation += 1;
        echo "<li>Please Set Project Package Type : " . $ePackageType . "</li>";
    }
    $configAppType = "";
    $getAppType = $obj->MySQLSelect("SELECT vValue FROM configurations WHERE vName='APP_TYPE'");
    if (count($getAppType) > 0) {
        $configAppType = $getAppType[0]['vValue'];
    }
    $eProductTypeCheck = $eProductType;
    if ($eProductType == "Deliverall") {
        $eProductTypeCheck = "Ride-Delivery-UberX";
    }
    if (strtoupper($eProductTypeCheck) != strtoupper($APP_TYPE)) {
        $errorcountsystemvalidation += 1;
        echo "<li>Please Set Project App Type : " . $eProductType . "</li>";
    }
    if (strtoupper($configAppType) != strtoupper($APP_TYPE)) {
        $errorcountsystemvalidation += 1;
        echo "<li>Please Set Project App Type In Configuration Table : " . $eProductType . "</li>";
    }
    //Added By HJ On 02-03-2019 For Remove Main Webservice File Start
    if ($eProductType == "Ride" || $eProductType == "Delivery" || $eProductType == "UberX" || $eProductType == "Ride-Delivery") {
        $mianWebserviceArr = array("webservice_dl_shark.php", "generalFunctions_dl_shark.php");
    } else if ($eProductType == "Foodonly" || $eProductType == "Deliverall") {
        $mianWebserviceArr = array("include_webservice_shark.php", "include_generalFunctions_shark.php");
    }
    $unUsefulFiles = array("test_socket.php", "chkR.php", "test_socket.php", "resizeImg - Copy.php", "trip_tracking - Copy.php", "expired_documents.php", "dummy_data_insert.php", "dummy_data_insert-14052019.php", "dummy_data_insert_gojek.php", "dummy_data_insert_taxi.php", "set_demo_store_img.php", "profile_190718.php", "cron_notification_email07082018.php", "=index.php", "=sign-in.php", "1.php");
    $finalRootFileArr = array_merge($mianWebserviceArr, $unUsefulFiles);
    foreach ($finalRootFileArr as $key => $filename) {
        if (file_exists(dirname(__FILE__) . "/" . $filename)) {
            $errorcountsystemvalidation += 1;
            echo "<li>Please Delete File From root folder : " . $filename . "</li>";
            $deleteFileArr[] = dirname(__FILE__) . "/" . $filename;
        }
    }
    //Added By HJ On 02-03-2019 For Remove Main Webservice File End
    if ($eProductType == 'Ride') {
        $ridewebservicefilearray = array("include/uberx/include_webservice_uberx.php", "include/uberx/action_booking_admin.php", "include/delivery/include_webservice_delivery.php", "include/delivery/add_booking_admin4.php", "include/ride-delivery/add_booking_admin1.php", "include/ride-delivery/add_booking_admin3.php", "include/ride-delivery/add_booking_admin7.php", "include/ride-delivery/ajax_get_vehicletype_airportsurcharge_admin1.php", "include/ride-delivery/ajax_get_vehicletype_fixfare_admin1.php", "include/ride-delivery-uberx/add_booking_admin2.php", "include/ride-delivery-uberx/add_booking_admin5.php", "include/ride-delivery-uberx/add_booking_admin8.php", "include/ride-delivery-uberx/ajax_get_vehicletype_airportsurcharge_admin2.php", "include/ride-delivery-uberx/ajax_get_vehicletype_fixfare_admin2.php");
        foreach ($ridewebservicefilearray as $key => $filename) {
            if (file_exists(dirname(__FILE__) . "/" . $filename)) {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Delete File From include folder from root " . $filename . "</li>";
                $deleteFileArr[] = dirname(__FILE__) . "/" . $filename;
            }
        }
        $obj->sql_query("DELETE FROM vehicle_type WHERE eIconType!='Car'");
    } else if ($eProductType == 'Delivery') {
        $deliverywebservicefilearray = array("include/uberx/include_webservice_uberx.php", "include/uberx/action_booking_admin.php", "include/ride/include_webservice_ride.php", "include/ride/add_booking_admin6.php", "include/ride-delivery/add_booking_admin1.php", "include/ride-delivery/add_booking_admin3.php", "include/ride-delivery/add_booking_admin7.php", "include/ride-delivery/ajax_get_vehicletype_airportsurcharge_admin1.php", "include/ride-delivery/ajax_get_vehicletype_fixfare_admin1.php", "include/ride-delivery-uberx/add_booking_admin2.php", "include/ride-delivery-uberx/add_booking_admin5.php", "include/ride-delivery-uberx/add_booking_admin8.php", "include/ride-delivery-uberx/ajax_get_vehicletype_airportsurcharge_admin2.php", "include/ride-delivery-uberx/ajax_get_vehicletype_fixfare_admin2.php");
        foreach ($deliverywebservicefilearray as $key => $filename) {
            if (file_exists(dirname(__FILE__) . "/" . $filename)) {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Delete File From include folder from root " . $filename . "</li>";
                $deleteFileArr[] = dirname(__FILE__) . "/" . $filename;
            }
        }
        $obj->sql_query("DELETE FROM admin_groups WHERE iGroupId ='4'");
    } else if ($eProductType == 'UberX') {
        $ufxwebservicefilearray = array("include/delivery/include_webservice_delivery.php", "include/delivery/add_booking_admin4.php", "include/ride/include_webservice_ride.php", "include/ride/add_booking_admin6.php", "include/ride-delivery/add_booking_admin1.php", "include/ride-delivery/add_booking_admin3.php", "include/ride-delivery/add_booking_admin7.php", "include/ride-delivery/ajax_get_vehicletype_airportsurcharge_admin1.php", "include/ride-delivery/ajax_get_vehicletype_fixfare_admin1.php", "include/ride-delivery-uberx/add_booking_admin2.php", "include/ride-delivery-uberx/add_booking_admin5.php", "include/ride-delivery-uberx/add_booking_admin8.php", "include/ride-delivery-uberx/ajax_get_vehicletype_airportsurcharge_admin2.php", "include/ride-delivery-uberx/ajax_get_vehicletype_fixfare_admin2.php");
        foreach ($ufxwebservicefilearray as $key => $filename) {
            if (file_exists(dirname(__FILE__) . "/" . $filename)) {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Delete File From include folder from root " . $filename . "</li>";
                $deleteFileArr[] = dirname(__FILE__) . "/" . $filename;
            }
        }
    } else if ($eProductType == 'Ride-Delivery') {
        $ridedeliverywebservicefilearray = array("include/ride-delivery-uberx/add_booking_admin2.php", "include/ride-delivery-uberx/add_booking_admin5.php", "include/ride-delivery-uberx/add_booking_admin8.php", "include/ride-delivery-uberx/ajax_get_vehicletype_airportsurcharge_admin2.php", "include/ride-delivery-uberx/add_booking_admin2.php", "include/uberx/action_booking_admin.php", "include/uberx/include_webservice_uberx.php");
        foreach ($ridedeliverywebservicefilearray as $key => $filename) {
            if (file_exists(dirname(__FILE__) . "/" . $filename)) {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Delete File From include folder from root " . $filename . "</li>";
                $deleteFileArr[] = dirname(__FILE__) . "/" . $filename;
            }
        }
    }

    if ($eProductType != 'Delivery' && $eProductType != 'Ride-Delivery-UberX' && $eProductType != 'Ride-Delivery' && $eProductType != 'Ride-Delivery-UberX-Shark') {
        if (ENABLE_MULTI_DELIVERY == 'Yes') {
            $errorcountsystemvalidation += 1;
            echo "<li>Please Update ENABLE_MULTI_DELIVERY value as 'No' in configuration_variables file.</li>";
        }
    }

    if ($eProductType == 'Delivery' || $eProductType == 'Ride-Delivery-UberX' || $eProductType == 'Ride-Delivery-UberX-Shark' || $eProductType == 'Ride-Delivery') {
        if ($eDeliveryType == 'Multi') {
            if (ENABLE_MULTI_DELIVERY == 'No') {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Update ENABLE_MULTI_DELIVERY value as 'Yes' in configuration_variables file.</li>";
            }
        }
    }

    if ($eProductType == 'UberX') {
        //$sql1 = "UPDATE configurations SET `eAdminDisplay` = 'No' WHERE (vName = 'DRIVER_REQUEST_METHOD' || vName = 'ENABLE_HAIL_RIDES' || vName='ENABLE_ROUTE_CALCULATION_MULTI' || vName='DELIVERY_VERIFICATION_METHOD' || vName='ENABLE_ROUTE_OPTIMIZE_MULTI' || vName='MAX_ALLOW_NUM_DESTINATION_MULTI')";
        //$obj->sql_query($sql1); // By HJ On 07-03-2019
    }

    //if ($eProductType != 'UberX' && $eProductType != 'Ride-Delivery-UberX' && $eProductType != 'Ride-Delivery-UberX-Shark') { // Commented By HJ On 14-03-2019 As Per Discuss With KS Sir
    if ($eProductType == 'Ride' || ONLYDELIVERALL == "Yes") { // Added By HJ On 14-03-2019 As Per Discuss With KS Sir
        $uberxfilearray = array("admin/service_type.php", "admin/vehicle_category.php", "admin/left_menu_ufx_array.php", "admin/left_menu_ufx.php", "admin/left_menu_ufx_n.php", "admin/vehicle_sub_category.php", "admin/vehicle_category_action.php", "admin/service_type_action.php", "admin/action/service_type.php", "admin/add_availability.php", "admin/manage_service_type.php", "admin/action/vehicle_category.php", "admin/action/vehicle_sub_category.php");
        foreach ($uberxfilearray as $key => $filename) {
            if (file_exists(dirname(__FILE__) . "/" . $filename)) {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Delete File From Admin Panel " . $filename . "</li>";
                $deleteFileArr[] = dirname(__FILE__) . "/" . $filename;
            }
        }
    }
    //Added By HJ On 11-06-2019 For Remove Driver Subscription and Favorite Feature Related Files Start
    if (ONLYDELIVERALL == "Yes" || strtoupper($ePackageType) != "SHARK" || $eProductType != "Ride-Delivery-UberX") {
        $driverSubscriptionArr = array("admin/driver_subscription.php", "admin/driver_subscription_action.php", "admin/driver_subscription_report.php", "admin/action/driver_subscription.php", "cron_driver_subscription.php", "admin/ajax_driver_subscription.php", "booking/ajax_driver_subscription.php", "include/features/include_fav_driver.php", "include/features/include_gojek_gopay.php", "admin/donation_action.php", "admin/donation.php", "admin/action/donation.php", "include/features/include_donation.php", "include/features/include_stop_over_point.php", "include/features/include_driver_subscription.php", "include/features/include_destinations_driver.php");
        foreach ($driverSubscriptionArr as $key => $driverSubFile) {
            if (file_exists(dirname(__FILE__) . "/" . $driverSubFile)) {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Delete File From Admin Panel " . $driverSubFile . "</li>";
                $deleteFileArr[] = dirname(__FILE__) . "/" . $driverSubFile;
            }
        }
    }
    //Added By HJ On 11-06-2019 For Remove Driver Subscription and Favorite Feature Related Files End
    //Added By HJ On 11-06-2019 For Remove Driver Subscription and Favorite Feature Related Files End
    if ($eProductType != 'Delivery' && $eProductType != 'Ride-Delivery-UberX' && $eProductType != 'Ride-Delivery' && $eProductType != 'Ride-Delivery-UberX-Shark') {
        if ($eDeliveryType != 'Multi') {
            $multifilearray = array("admin/invoice_multi_delivery.php");
            foreach ($multifilearray as $key => $filename) {
                if (file_exists(dirname(__FILE__) . "/" . $filename)) {
                    $errorcountsystemvalidation += 1;
                    echo "<li>Please Delete File From Admin Panel " . $filename . "</li>";
                    $deleteFileArr[] = dirname(__FILE__) . "/" . $filename;
                }
            }
        }
        $deliveryfilearray = array("admin/package_type.php", "admin/package_type_action.php", "admin/action/package_type.php", "admin/blocked_driver.php", "admin/blocked_rider.php", "admin/action/blocked_driver.php", "admin/action/blocked_rider.php");
        foreach ($deliveryfilearray as $key => $filename) {
            if (file_exists(dirname(__FILE__) . "/" . $filename)) {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Delete File From Admin Panel " . $filename . "</li>";
                $deleteFileArr[] = dirname(__FILE__) . "/" . $filename;
            }
        }
    }
    /* if (($eProductType == "Ride" || $eProductType == "Ride-Delivery" || $eProductType == "Ride-Delivery-UberX") && ENABLEKIOSKPANEL == "Yes") {
      $errorcountsystemvalidation += 1;
      echo "<li>Please Update ENABLEKIOSKPANEL value as 'No' in configuration_variables file.</li>";
      } */
    if (ENABLEKIOSKPANEL != $eEnableKiosk) {
        $errorcountsystemvalidation += 1;
        echo "<li>Please Update ENABLEKIOSKPANEL value as '$eEnableKiosk' in configuration_variables file.</li>";
    }
    if (ENABLEHOTELPANEL != $eEnableHotel) {
        $errorcountsystemvalidation += 1;
        echo "<li>Please Update ENABLEHOTELPANEL value as '$eEnableHotel' in configuration_variables file.</li>";
    }
    if (ENABLEKIOSKPANEL == 'No' && ENABLEHOTELPANEL == 'No') {
        $hotelfilearray = array("admin/hotel_rider.php", "admin/hotel_rider_action.php", "admin/hotel_payment_report.php", "admin/hotel_index.php", "admin/hotel_booking.php", "admin/hotel_banner_action.php", "admin/hotel_banner.php", "admin/export_hotel_pay_details.php", "admin/create_request.php", "admin/action/hotel_payment_report.php", "admin/action/hotel_rider.php");
        foreach ($hotelfilearray as $key => $filename) {
            if (file_exists(dirname(__FILE__) . "/" . $filename)) {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Delete File From Admin Panel " . $filename . "</li>";
                $deleteFileArr[] = dirname(__FILE__) . "/" . $filename;
            }
        }
    }
    if ($eProductType != 'Ride-Delivery-UberX' && $eProductType != 'Ride-Delivery-UberX-Shark') {
        $cubejekfilearray = array("admin/home_content_new.php", "admin/home_content_action_new.php", "admin/app_home_settings.php", "admin/app_home_settings_action.php");
        foreach ($cubejekfilearray as $key => $filename) {
            if (file_exists(dirname(__FILE__) . "/" . $filename)) {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Delete File From Admin Panel " . $filename . "</li>";
                $deleteFileArr[] = dirname(__FILE__) . "/" . $filename;
            }
        }
    }

    if ($eProductType != 'Ride' && $eProductType != 'Ride-Delivery' && $eProductType != 'Ride-Delivery-UberX' && $eProductType != 'Ride-Delivery-UberX-Shark') {
        $ridefilearray = array("admin/user_profile_master_action.php", "admin/user_profile_master.php", "admin/trip_reason.php", "admin/trip_reason_action.php", "admin/profile.php", "admin/organization_document_fetch.php", "admin/organization.php", "admin/organization_action.php", "admin/org_payment_report.php", "admin/org_cancellation_payment_report.php", "admin/location-airport.php", "admin/location_action_airport.php", "admin/action/location-airport.php", "admin/action/trip_reason.php", "admin/action/user_profile_master.php");
        foreach ($ridefilearray as $key => $filename) {
            if (file_exists(dirname(__FILE__) . "/" . $filename)) {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Delete File From Admin Panel " . $filename . "</li>";
                $deleteFileArr[] = dirname(__FILE__) . "/" . $filename;
            }
        }
    }
    if ($eProductType == 'Foodonly') {
        /* $sql2 = "UPDATE configurations_cubejek SET `eStatus` = 'Inactive' WHERE (vName = 'GROCERY_APP_SHOW_SELECTION' || vName = 'GROCERY_APP_GRID_ICON_NAME' || vName = 'GROCERY_APP_BANNER_IMG_NAME' || vName = 'GROCERY_APP_DETAIL_BANNER_IMG_NAME' || vName = 'GROCERY_APP_DETAIL_GRID_ICON_NAME' || vName = 'GROCERY_APP_PACKAGE_NAME' || vName = 'GROCERY_APP_IOS_APP_ID' || vName = 'GROCERY_APP_IOS_PACKAGE_NAME' || vName = 'GROCERY_APP_SERVICE_ID' || vName = 'DELIVER_ALL_APP_IOS_PACKAGE_NAME' || vName = 'DELIVER_ALL_APP_IOS_APP_ID' || vName = 'DELIVER_ALL_APP_PACKAGE_NAME' || vName = 'DELIVER_ALL_APP_DETAIL_GRID_ICON_NAME' || vName = 'DELIVER_ALL_APP_DETAIL_BANNER_IMG_NAME' || vName = 'DELIVER_ALL_APP_BANNER_IMG_NAME' || vName='DELIVER_ALL_APP_GRID_ICON_NAME' || vName = 'DELIVER_ALL_APP_SHOW_SELECTION' )";
          $obj->sql_query($sql2); */
    }

    if ($eProductType != 'Foodonly' && $eProductType != 'Deliverall') {
        if ($ePackageType == 'standard' || $ePackageType == 'enterprise') {
            if ($eProductType != 'Foodonly' && $eProductType != 'Deliverall' && $eProductType != 'Ride-Delivery-UberX-Shark') {
                /* $sql2 = "UPDATE configurations_cubejek SET `eStatus` = 'Inactive' WHERE (vName = 'FOOD_APP_SHOW_SELECTION' || vName = 'FOOD_APP_GRID_ICON_NAME' || vName = 'FOOD_APP_BANNER_IMG_NAME' || vName = 'FOOD_APP_DETAIL_BANNER_IMG_NAME' || vName = 'FOOD_APP_DETAIL_GRID_ICON_NAME' || vName = 'FOOD_APP_PACKAGE_NAME' || vName = 'FOOD_APP_IOS_APP_ID' || vName = 'FOOD_APP_IOS_PACKAGE_NAME'  || vName = 'FOOD_APP_SERVICE_ID' || vName = 'GROCERY_APP_SHOW_SELECTION' || vName = 'GROCERY_APP_GRID_ICON_NAME' || vName = 'GROCERY_APP_BANNER_IMG_NAME' || vName = 'GROCERY_APP_DETAIL_BANNER_IMG_NAME' || vName = 'GROCERY_APP_DETAIL_GRID_ICON_NAME' || vName = 'GROCERY_APP_PACKAGE_NAME' || vName = 'GROCERY_APP_IOS_APP_ID' || vName = 'GROCERY_APP_IOS_PACKAGE_NAME' || vName = 'GROCERY_APP_SERVICE_ID' || vName = 'DELIVER_ALL_APP_IOS_PACKAGE_NAME' || vName = 'DELIVER_ALL_APP_IOS_APP_ID' || vName = 'DELIVER_ALL_APP_PACKAGE_NAME' || vName = 'DELIVER_ALL_APP_DETAIL_GRID_ICON_NAME' || vName = 'DELIVER_ALL_APP_DETAIL_BANNER_IMG_NAME' || vName = 'DELIVER_ALL_APP_BANNER_IMG_NAME' || vName='DELIVER_ALL_APP_GRID_ICON_NAME' || vName = 'DELIVER_ALL_APP_SHOW_SELECTION' )";
                  $obj->sql_query($sql2);
                 */
                $s1 = "DROP TABLE IF EXISTS `cuisine`, `food_menu`, `service_categories`, `food_menu_images`, `orders`, `order_details`, `order_later`, `order_status`, `order_status_logs`, `menuitem_options`, `menu_items`, `language_label_6`, `language_label_5`";
                $obj->sql_query($s1);
            }

            $foodfilearray = array("admin/cuisine.php", "admin/cuisine_action.php", "admin/delivery_charges_action.php", "admin/delivery_charges.php", "admin/restaurants_pay_report.php", "admin/food_menu.php", "admin/food_menu_action.php", "admin/store.php", "admin/store_action.php", "admin/store_banner_action.php", "admin/store_banner.php", "admin/store_cancel_reason.php", "admin/store_cancel_reason_action.php", "admin/store_dashboard.php", "admin/store_document_action.php", "admin/store_document_fetch.php", "admin/store_driver_pay_report.php", "admin/store_payment_report.php", "admin/store_review.php", "admin/store_vehicle_type.php", "admin/store_vehicle_type_action.php", "admin/store-dashboard.php", "admin/order_invoice.php", "admin/order_status.php", "admin/menu_item_action.php", "admin/order_status_action.php", "admin/menu_item.php", "admin/action/food_menu.php", "admin/action/ordar_status_type.php", "admin/action/restaurants_pay_report.php", "admin/action/store.php", "admin/action/store_cancel_reason.php", "admin/action/store_driver_pay_report.php", "admin/action/store_payment_report.php", "admin/action/store_review.php", "admin/action/store_vehicle_type.php", "admin/action/menu_item.php", "admin/action/cuisine.php", "admin/action/delivery_charges.php", "admin/service_provider.php", "admin/service_provider_action.php", "admin/service_category.php", "admin/service_category_action.php", "admin/left_menu_deliverall_array.php", "admin/homecontent.php", "admin/homecontent_action.php", "admin/cancelled_orders.php", "admin/allorders.php", "admin/ajax_get_cuisine.php", "admin/ajax_get_food_category.php", "admin/ajax_get_restorantcat_filter.php", "admin/ajax_check_deliverycharge_area.php", "admin/ajax_store_details.php", "admin/advertise_banners.php", "admin/advertise_banner_action.php", "admin/admin_groups.php", "admin/admin_group_action.php", "admin/blocked_driver.php", "admin/blocked_rider.php", "admin/admin_permissions.php", "admin/admin_permission_action.php", "admin/action/admin_permissions.php", "admin/action/admin_groups.php", "admin/action/banner_impression.php", "admin/action/blocked_driver.php", "admin/action/blocked_rider.php", "admin/action/advertise_banners.php", "admin/action/news.php", "admin/action/newsletter.php", "admin/action/organization.php", "admin/action/airport_surcharge.php", "admin/airport_surcharge_action.php", "admin/airport_surcharge.php", "admin/news.php", "admin/news_action.php", "admin/newsletter.php", "admin/newsletter---.php", "admin/action/service_provider.php", "admin/package_type.php", "admin/package_type_action.php", "admin/action/package_type.php");
            if ($eEnableKiosk == "No") {
                $foodfilearray[] = "admin/visit.php";
                $foodfilearray[] = "admin/visit_address_action.php";
                $foodfilearray[] = "admin/action/visit.php";
            }
            foreach ($foodfilearray as $key => $filename) {
                if (file_exists(dirname(__FILE__) . "/" . $filename)) {
                    $errorcountsystemvalidation += 1;
                    echo "<li>Please Delete File From Admin Panel " . $filename . "</li>";
                    $deleteFileArr[] = dirname(__FILE__) . "/" . $filename;
                }
            }
            //$obj->sql_query("UPDATE configurations SET `vValue` = 'Normal' WHERE vName='RIDE_DRIVER_CALLING_METHOD'"); // By HJ On 07-03-2019
            //$obj->sql_query("UPDATE configurations SET `eAdminDisplay` = 'No' WHERE (vName='LIST_RESTAURANT_LIMIT_BY_DISTANCE' || vName = 'ADMIN_COMMISSION' || vName = 'MIN_ORDER_CANCELLATION_CHARGES' || vName = 'COMPANY_EMAIL_VERIFICATION' || vName = 'COMPANY_PHONE_VERIFICATION')"); // By HJ On 07-03-2019
            //Added By HJ On 28-02-2019 For Disable New Features Start
            //$obj->sql_query("UPDATE configurations SET `vValue` = 'No',`eAdminDisplay` = 'No' WHERE (vName = 'ENABLE_INSURANCE_TRIP_REPORT'||  vName = 'ENABLE_INSURANCE_ACCEPT_REPORT' || vName = 'ENABLE_INSURANCE_IDLE_REPORT' ||  vName = 'ENABLE_NEWSLETTERS_SUBSCRIPTION_SECTION' || vName = 'ENABLE_INTRANSIT_SHOPPING_SYSTEM' || vName = 'ENABLE_AIRPORT_SURCHARGE_SECTION' || vName = 'ENABLE_NEWS_SECTION' || vName = 'ENABLE_LIVE_CHAT' || vName = 'ENABLE_RESTAURANTS_ADVERTISEMENT_BANNER' || vName = 'ENABLE_DRIVER_ADVERTISEMENT_BANNER' || vName = 'ENABLE_RIDER_ADVERTISEMENT_BANNER' || vName = 'BOOK_FOR_ELSE_ENABLE' || vName = 'CHILD_SEAT_ACCESSIBILITY_OPTION' || vName = 'POOL_ENABLE' || vName = 'ENABLE_CORPORATE_PROFILE' || vName = 'PASSENGER_LINKEDIN_LOGIN' || vName = 'DRIVER_LINKEDIN_LOGIN' || vName = 'PASSENGER_LINKEDIN_LOGIN')"); // By HJ On 07-03-2019
            //$obj->sql_query("UPDATE configurations SET `vValue` = 'Disable',`eAdminDisplay` = 'No' WHERE vName='ADVERTISEMENT_TYPE'"); // By HJ On 07-03-2019
            //$obj->sql_query("UPDATE configurations SET `vValue` = '',`eAdminDisplay` = 'No' WHERE (vName='LINKEDIN_APP_SECRET_KEY' || vName='LINKEDIN_APP_ID')"); // By HJ On 07-03-2019
            //Added By HJ On 28-02-2019 For Disable New Features End
            $frontfoodfilesarray = array("food_menu.php", "food_menu_action.php", "invoice_deliverall.php", "menu_item_action.php", "menuitems.php", "myorder.php", "order_invoice.php", "settings.php", "orderdetails_mail_format.php", "processing_orders.php", "sign-up-restaurant.php", "organization_login.php", "organization_trip.php", "organization_users_trip.php", "organization_profile_action.php", "organization-profile.php", "my_users.php", "ajax_organization_login_action.php", "signup_action_organization.php", "organization-logout.php", "sign-up-organization.php");
            if ($eProductType != 'Ride-Delivery-UberX' && $eProductType != 'UberX') {
                $frontfoodfilesarray[] = "provider_images.php";
            }
            foreach ($frontfoodfilesarray as $key => $filesname) {
                if (file_exists((dirname(__FILE__) . "/" . $filesname))) {
                    $errorcountsystemvalidation += 1;
                    echo "<li>Please Delete File From root folder " . $filesname . "</li>";
                    $deleteFileArr[] = dirname(__FILE__) . "/" . $filesname;
                }
            }
            $webserviceIncludeFilesArr = array("include/include_webservice_sharkfeatures.php", "include/livechat.php");
            foreach ($webserviceIncludeFilesArr as $key => $filesname) {
                if (file_exists((dirname(__FILE__) . "/" . $filesname))) {
                    $errorcountsystemvalidation += 1;
                    echo "<li>Please Delete File From include folder " . $filesname . "</li>";
                    $deleteFileArr[] = dirname(__FILE__) . "/" . $filesname;
                }
            }
            if ($eProductType != 'Ride-Delivery-UberX' && $eProductType != 'Ride-Delivery-UberX-Shark') {
                /* $que1 = 'TRUNCATE TABLE configurations_cubejek';
                  $obj->sql_query($que1); */
            }
        } else {
            if ($eProductType != 'Ride-Delivery-UberX-Shark' && ($eProductType != 'Ride-Delivery-UberX' && $ePackageType != 'shark')) {
                $foodfilearray = array("admin/cuisine.php", "admin/cuisine_action.php", "admin/delivery_charges_action.php", "admin/delivery_charges.php", "admin/restaurants_pay_report.php", "admin/food_menu.php", "admin/food_menu_action.php", "admin/store.php", "admin/store_action.php", "admin/store_banner_action.php", "admin/store_banner.php", "admin/store_cancel_reason.php", "admin/store_cancel_reason_action.php", "admin/store_dashboard.php", "admin/store_document_action.php", "admin/store_document_fetch.php", "admin/store_driver_pay_report.php", "admin/store_payment_report.php", "admin/store_review.php", "admin/store_vehicle_type.php", "admin/store_vehicle_type_action.php", "admin/store-dashboard.php", "admin/order_invoice.php", "admin/order_status.php", "admin/menu_item_action.php", "admin/order_status_action.php", "admin/menu_item.php", "admin/action/food_menu.php", "admin/action/ordar_status_type.php", "admin/action/restaurants_pay_report.php", "admin/action/store.php", "admin/action/store_cancel_reason.php", "admin/action/store_driver_pay_report.php", "admin/action/store_payment_report.php", "admin/action/store_review.php", "admin/action/store_vehicle_type.php", "admin/action/menu_item.php", "admin/action/cuisine.php", "admin/action/delivery_charges.php", "admin/service_provider.php", "admin/service_provider_action.php", "admin/service_category.php", "admin/service_category_action.php", "admin/left_menu_deliverall_array.php", "admin/homecontent.php", "admin/homecontent_action.php", "admin/cancelled_orders.php", "admin/allorders.php", "admin/ajax_get_cuisine.php", "admin/ajax_get_food_category.php", "admin/ajax_get_restorantcat_filter.php", "admin/ajax_check_deliverycharge_area.php", "admin/ajax_store_details.php");
                if ($eEnableKiosk == "No") {
                    $foodfilearray[] = "admin/visit.php";
                    $foodfilearray[] = "admin/visit_address_action.php";
                    $foodfilearray[] = "admin/action/visit.php";
                }
                foreach ($foodfilearray as $key => $filename) {
                    if (file_exists(dirname(__FILE__) . "/" . $filename)) {
                        $errorcountsystemvalidation += 1;
                        echo "<li>Please Delete File From Admin Panel " . $filename . "</li>";
                        $deleteFileArr[] = dirname(__FILE__) . "/" . $filename;
                    }
                }
            }
        }
    }
    if (DELIVERALL == "No" || DELIVERALL != "Yes") {
        $foodfilearray = array("include/features/include_fav_store.php");
        foreach ($foodfilearray as $key => $filename) {
            if (file_exists(dirname(__FILE__) . "/" . $filename)) {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Delete File From Include Folder " . $filename . "</li>";
                $deleteFileArr[] = dirname(__FILE__) . "/" . $filename;
            }
        }
    }
    if (DELIVERALL == "Yes" && ($eProductType == "Ride" || $eProductType == "Ride-Delivery" || $eProductType == "Delivery" || $eProductType == "UberX")) {
        $errorcountsystemvalidation += 1;
        echo "<li>Please Update DELIVERALL value as 'No' in configuration_variables file.</li>";
    }
    if ($eProductType == 'Foodonly' || $eProductType == 'Deliverall') {

        if (DELIVERALL == 'No') {
            $errorcountsystemvalidation += 1;
            echo "<li>Please Update DELIVERALL value as 'Yes' in configuration_variables file.</li>";
        }

        if (ONLYDELIVERALL == 'No') {
            $errorcountsystemvalidation += 1;
            echo "<li>Please Update ONLYDELIVERALL value as 'Yes' in configuration_variables file.</li>";
        }

        if (ENABLE_RENTAL_OPTION == 'Yes') {
            $errorcountsystemvalidation += 1;
            echo "<li>Please Update ENABLE_RENTAL_OPTION value as 'No' in configuration_variables file.</li>";
        }

        if ($eDeliveryType != 'Multi') {
            if (ENABLE_MULTI_DELIVERY == 'Yes') {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Update ENABLE_MULTI_DELIVERY value as 'No' in configuration_variables file.</li>";
            }
        }

        foreach ($db_service_categories as $key => $value) {
            $iServiceId = $value['iServiceId'];
            if (!empty($iServiceId)) {
                $q1 = "show tables like 'language_label_" . $iServiceId . "'";
                $dbchecktabel = $obj->MySQLSelect($q1);
                if (count($dbchecktabel) <= 0) {
                    $errorcountsystemvalidation += 1;
                    echo "<li>Please Create 'language_label_" . $iServiceId . "' tabel using language_label_2 tabel.</li>";
                }
            }
        }

        if (!empty($matchresult) || !empty($matchresult2)) {
            $errorcountsystemvalidation += 1;
            echo "<li>Please Add or Remove 'Service Ids' in configuration file in array 'service_categories_ids_arr'.</li>";
        }

        //$sql3 = "UPDATE configurations SET `eAdminDisplay` = 'No' WHERE (vName='DRIVER_REQUEST_METHOD' || vName = 'ENABLE_TOLL_COST' || vName = 'TOLL_COST_APP_ID' || vName = 'TOLL_COST_APP_CODE' || vName = 'ENABLE_HAIL_RIDES' ||  vName = 'ENABLE_SURGE_CHARGE_RENTAL' || vName = 'ENABLE_WAITING_CHARGE_RENTAL')";
        //$obj->sql_query($sql3); // By HJ On 07-03-2019
        //$sql3 = "UPDATE configurations SET `eAdminDisplay` = 'Yes' WHERE (vName='LIST_RESTAURANT_LIMIT_BY_DISTANCE' || vName = 'ADMIN_COMMISSION' || vName = 'MIN_ORDER_CANCELLATION_CHARGES' || vName = 'COMPANY_EMAIL_VERIFICATION' || vName = 'COMPANY_PHONE_VERIFICATION')";
        //$obj->sql_query($sql3); // By HJ On 07-03-2019

        /* $query2 = 'TRUNCATE TABLE configurations_cubejek';
          $obj->sql_query($query2); */

        //$sql5 = "UPDATE configurations SET `eAdminDisplay` = 'No' WHERE ( vName = 'ENABLE_SURGE_CHARGE_RENTAL' || vName = 'ENABLE_WAITING_CHARGE_RENTAL'|| vName = 'ENABLE_HAIL_RIDES' || vName = 'FEMALE_RIDE_REQ_ENABLE' || vName = 'HANDICAP_ACCESSIBILITY_OPTION' ||  vName =  'ENABLE_WAITING_CHARGE_FLAT_TRIP' ||  vName =  'APPLY_SURGE_ON_FLAT_FARE' || vName =  'BOOKING_LATER_ACCEPT_BEFORE_INTERVAL' || vName =  'BOOKING_LATER_ACCEPT_AFTER_INTERVAL')";
        //$obj->sql_query($sql5); // By HJ On 07-03-2019
        //$sql4 = "UPDATE configurations SET `eAdminDisplay` = 'No' WHERE ( vName = 'DELIVERY_VERIFICATION_METHOD')";
        //$obj->sql_query($sql4); // By HJ On 07-03-2019
    } else {
        if ($ePackageType == 'standard') {
            if (ENABLE_RENTAL_OPTION == 'Yes') {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Update ENABLE_RENTAL_OPTION value as 'No' in configuration_variables file.</li>";
            }
            /* if($eDeliveryType != 'Multi'){
              if(ENABLE_MULTI_DELIVERY == 'Yes'){
              $errorcountsystemvalidation +=1;
              echo "<li>Please Update ENABLE_MULTI_DELIVERY value as 'No' in configuration_variables file.</li>";
              }
              } */
            if (DELIVERALL == 'Yes') {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Update DELIVERALL value as 'No' in configuration_variables file.</li>";
            }
            if (ONLYDELIVERALL == 'Yes') {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Update ONLYDELIVERALL value as 'No' in configuration_variables file.</li>";
            }
            //$sql4 = "UPDATE configurations SET `vValue` = 'No' WHERE (vName = 'ENABLE_TOLL_COST'||  vName = 'CALLMASKING_ENABLED' || vName = 'ENABLE_HAIL_RIDES' ||  vName = 'ENABLE_SURGE_CHARGE_RENTAL' || vName = 'ENABLE_WAITING_CHARGE_RENTAL' || vName = 'WAYBILL_ENABLE')";
            //$obj->sql_query($sql4); // By HJ On 07-03-2019
            //$obj->sql_query("UPDATE configurations SET `eAdminDisplay` = 'No' WHERE ( vName = 'WAYBILL_ENABLE')"); // By HJ On 07-03-2019
            if ($eProductType == 'UberX' || $eProductType == 'Delivery' || $eProductType == 'Foodonly' || $eProductType == 'Deliverall') {
                //$sql8 = "UPDATE configurations SET `eAdminDisplay` = 'No' WHERE (vName = 'FEMALE_RIDE_REQ_ENABLE' || vName = 'HANDICAP_ACCESSIBILITY_OPTION' ||  vName =  'ENABLE_WAITING_CHARGE_FLAT_TRIP' ||  vName =  'APPLY_SURGE_ON_FLAT_FARE')";
                //$obj->sql_query($sql8); // By HJ On 07-03-2019
            }
            //$sql3 = "UPDATE configurations SET `eAdminDisplay` = 'No' WHERE (vName = 'ENABLE_TOLL_COST' || vName = 'TOLL_COST_APP_ID' || vName = 'TOLL_COST_APP_CODE' || vName = 'CALLMASKING_ENABLED' || vName = 'ENABLE_HAIL_RIDES' ||  vName = 'ENABLE_SURGE_CHARGE_RENTAL' || vName = 'ENABLE_WAITING_CHARGE_RENTAL')";
            //$obj->sql_query($sql3); // By HJ On 07-03-2019
            $standardfilearray = array("admin/masking_numbers.php", "admin/masking_numbers_action.php", "admin/location_wise_fare.php", "admin/location_wise_fare_action.php", "admin/locationwise_fare.php", "admin/rental_package.php", "admin/action/masking_numbers.php", "admin/action/locationwise_fare.php", "admin/action/rental_package.php");
            foreach ($standardfilearray as $key => $filename) {
                if (file_exists(dirname(__FILE__) . "/" . $filename)) {
                    $errorcountsystemvalidation += 1;
                    echo "<li>Please Delete File From Admin Panel " . $filename . "</li>";
                    $deleteFileArr[] = dirname(__FILE__) . "/" . $filename;
                }
            }
            $frontFilesarray = array("call.php", "callmask.php", "tollroute.php");
            foreach ($frontFilesarray as $filenamefront) {
                if (file_exists(dirname(__FILE__) . "/" . $filenamefront)) {
                    $errorcountsystemvalidation += 1;
                    echo "<li>Please Delete File From Root " . $filenamefront . "</li>";
                    $deleteFileArr[] = dirname(__FILE__) . "/" . $filenamefront;
                }
            }
            $foldersArray = array("assets/libraries/adyen", "assets/libraries/MPesa", "assets/libraries/omise", "assets/libraries/paymaya", "assets/libraries/xendit", "assets/libraries/webview/flutterwave", "assets/libraries/webview/flutterwave_old", "assets/libraries/webview/hyper_pay", "assets/libraries/webview/hyperpay", "assets/libraries/webview/iugu", "assets/libraries/webview/payu_ro", "assets/libraries/webview/payubiz", "assets/libraries/webview/payulatam", "assets/libraries/webview/payzen", "assets/libraries/webview/mpesa", "assets/libraries/webview/final_payu_live_update", "assets/libraries/webview/alu-client-php-master", "assets/libraries/webview/static_google_map", "assets/libraries/webview/mcp");
            foreach ($foldersArray as $k => $val) {
                if (is_dir(dirname(__FILE__) . "/" . $val)) {
                    $errorcountsystemvalidation += 1;
                    echo "<li>Please Delete Folders From assets/libraries : " . $val . "</li>";
                    $deleteFileArr[] = dirname(__FILE__) . "/" . $val;
                }
            }
            $webserviceIncludeFilesArr = array("include/include_webservice_dl_enterprisefeatures.php", "include/include_webservice_enterprisefeatures.php");
            foreach ($webserviceIncludeFilesArr as $key => $filesname) {
                if (file_exists((dirname(__FILE__) . "/" . $filesname))) {
                    $errorcountsystemvalidation += 1;
                    echo "<li>Please Delete File From include folder " . $filesname . "</li>";
                    $deleteFileArr[] = dirname(__FILE__) . "/" . $filesname;
                }
            }
            if ($eProductType != 'Foodonly' || $eProductType != 'Deliverall') {
                //$sql3 = "UPDATE configurations SET `eAdminDisplay` = 'No' WHERE (vName='LIST_RESTAURANT_LIMIT_BY_DISTANCE' || vName = 'ADMIN_COMMISSION' || vName = 'MIN_ORDER_CANCELLATION_CHARGES' || vName = 'COMPANY_EMAIL_VERIFICATION' || vName = 'COMPANY_PHONE_VERIFICATION')";
                //$obj->sql_query($sql3); // By HJ On 07-03-2019
            }

            if ($eDeliveryType != 'Multi') {
                //$sql5 = "UPDATE configurations SET `eAdminDisplay` = 'No' WHERE ( vName =  'DELIVERY_VERIFICATION_METHOD')";
                //$obj->sql_query($sql5); // By HJ On 07-03-2019
            }
        } else if ($ePackageType == 'enterprise') {
            if (ENABLE_MULTI_DELIVERY == 'No') {
                /* $sql3 = "UPDATE configurations_cubejek SET `eStatus` = 'Inactive' WHERE (vName = 'MULTI_DELIVERY_SHOW_SELECTION' || vName = 'MULTI_DELIVERY_GRID_ICON_NAME' || vName = 'MULTI_DELIVERY_BANNER_IMG_NAME')";
                  $obj->sql_query($sql3); */
            }
            if ($eProductType != 'Foodonly' || $eProductType != 'Deliverall') {
                $enterpriseOrderFilesarray = array("include/include_webservice_dl_enterprisefeatures.php");
                foreach ($enterpriseOrderFilesarray as $filenameEnterprise) {
                    if (file_exists(dirname(__FILE__) . "/" . $filenameEnterprise)) {
                        $errorcountsystemvalidation += 1;
                        echo "<li>Please Delete File From Include " . $filenameEnterprise . "</li>";
                        $deleteFileArr[] = dirname(__FILE__) . "/" . $filenameEnterprise;
                    }
                }
            }

            if ($eProductType != 'Delivery' && $eProductType != 'UberX') {
                if (ENABLE_RENTAL_OPTION == 'No') {
                    $errorcountsystemvalidation += 1;
                    echo "<li>Please Update ENABLE_RENTAL_OPTION value as 'Yes' in configuration_variables file.</li>";
                }
            } else {
                if (ENABLE_RENTAL_OPTION == 'Yes') {
                    $errorcountsystemvalidation += 1;
                    echo "<li>Please Update ENABLE_RENTAL_OPTION value as 'No' in configuration_variables file.</li>";
                }
            }

            if ($eDeliveryType != 'Multi') {
                if (ENABLE_MULTI_DELIVERY == 'Yes') {
                    $errorcountsystemvalidation += 1;
                    echo "<li>Please Update ENABLE_MULTI_DELIVERY value as 'No' in configuration_variables file.</li>";
                }
            }

            if (DELIVERALL == 'Yes') {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Update DELIVERALL value as 'No' in configuration_variables file.</li>";
            }

            if (ONLYDELIVERALL == 'Yes') {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Update ONLYDELIVERALL value as 'No' in configuration_variables file.</li>";
            }
            //$sql3 = "UPDATE configurations SET `eAdminDisplay` = 'Yes' WHERE (vName='CALLMASKING_ENABLED' || vName = 'ENABLE_TOLL_COST' || vName ='TOLL_COST_APP_ID' ||  vName ='TOLL_COST_APP_CODE')";
            //$obj->sql_query($sql3); // By HJ On 07-03-2019
            //$obj->sql_query("UPDATE configurations SET `eAdminDisplay` = 'No',`vValue`='Yes' WHERE vName =  'WAYBILL_ENABLE'"); // Added By HJ On 02-03-2019
            if ($eProductType == 'Ride' || $eProductType == 'Ride-Delivery-UberX' || $eProductType == 'Ride-Delivery') {
                //$sql5 = "UPDATE configurations SET `eAdminDisplay` = 'Yes' WHERE ( vName = 'ENABLE_SURGE_CHARGE_RENTAL' || vName = 'ENABLE_WAITING_CHARGE_RENTAL'|| vName = 'ENABLE_HAIL_RIDES' || vName = 'FEMALE_RIDE_REQ_ENABLE' || vName = 'HANDICAP_ACCESSIBILITY_OPTION' ||  vName =  'ENABLE_WAITING_CHARGE_FLAT_TRIP' ||  vName =  'APPLY_SURGE_ON_FLAT_FARE')";
                $obj->sql_query($sql5);
            } else {
                //$sql5 = "UPDATE configurations SET `eAdminDisplay` = 'No' WHERE ( vName = 'ENABLE_SURGE_CHARGE_RENTAL' || vName = 'ENABLE_WAITING_CHARGE_RENTAL'|| vName = 'ENABLE_HAIL_RIDES' || vName = 'FEMALE_RIDE_REQ_ENABLE' || vName = 'HANDICAP_ACCESSIBILITY_OPTION' ||  vName =  'ENABLE_WAITING_CHARGE_FLAT_TRIP' ||  vName =  'APPLY_SURGE_ON_FLAT_FARE')";
                $obj->sql_query($sql5);
                $enterpriseFilesarray = array("admin/rental_package.php", "admin/rental_vehicle_list.php", "admin/action/rental_package.php");
                foreach ($enterpriseFilesarray as $filenameEnterprise) {
                    if (file_exists(dirname(__FILE__) . "/" . $filenameEnterprise)) {
                        $errorcountsystemvalidation += 1;
                        echo "<li>Please Delete File From Admin " . $filenameEnterprise . "</li>";
                        $deleteFileArr[] = dirname(__FILE__) . "/" . $filenameEnterprise;
                    }
                }
            }
            ?>
        <? } else { ?>
            <?
            if (DELIVERALL == 'No' && ($eProductType == "Ride-Delivery-UberX" || $eProductType == 'Foodonly' || $eProductType == 'Deliverall')) {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Update DELIVERALL value as 'Yes' in configuration_variables file.</li>";
            }
            //$obj->sql_query("UPDATE configurations SET `vValue` = 'Voip' WHERE vName='RIDE_DRIVER_CALLING_METHOD'");
            //$obj->sql_query("UPDATE configurations SET `eAdminDisplay` = 'No',`vValue`='Yes' WHERE vName =  'WAYBILL_ENABLE'"); // Added By HJ On 02-03-2019
            if (ENABLE_RENTAL_OPTION == 'No') {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Update ENABLE_RENTAL_OPTION value as 'Yes' in configuration_variables file.</li>";
            }

            if ($eDeliveryType != 'Multi') {
                if (ENABLE_MULTI_DELIVERY == 'Yes') {
                    $errorcountsystemvalidation += 1;
                    echo "<li>Please Update ENABLE_MULTI_DELIVERY value as 'No' in configuration_variables file.</li>";
                }
            }

            if (ONLYDELIVERALL == 'Yes') {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Update ONLYDELIVERALL value as 'No' in configuration_variables file.</li>";
            }
            /* $sql2 = "UPDATE configurations_cubejek SET `eStatus` = 'Active' WHERE (vName = 'FOOD_APP_SHOW_SELECTION' || vName = 'FOOD_APP_GRID_ICON_NAME' || vName = 'FOOD_APP_BANNER_IMG_NAME' || vName = 'FOOD_APP_DETAIL_BANNER_IMG_NAME' || vName = 'FOOD_APP_DETAIL_GRID_ICON_NAME' || vName = 'FOOD_APP_PACKAGE_NAME' || vName = 'FOOD_APP_IOS_APP_ID' || vName = 'FOOD_APP_IOS_PACKAGE_NAME'  || vName = 'FOOD_APP_SERVICE_ID' || vName = 'GROCERY_APP_SHOW_SELECTION' || vName = 'GROCERY_APP_GRID_ICON_NAME' || vName = 'GROCERY_APP_BANNER_IMG_NAME' || vName = 'GROCERY_APP_DETAIL_BANNER_IMG_NAME' || vName = 'GROCERY_APP_DETAIL_GRID_ICON_NAME' || vName = 'GROCERY_APP_PACKAGE_NAME' || vName = 'GROCERY_APP_IOS_APP_ID' || vName = 'GROCERY_APP_IOS_PACKAGE_NAME' || vName = 'GROCERY_APP_SERVICE_ID' || vName = 'DELIVER_ALL_APP_IOS_PACKAGE_NAME' || vName = 'DELIVER_ALL_APP_IOS_APP_ID' || vName = 'DELIVER_ALL_APP_PACKAGE_NAME' || vName = 'DELIVER_ALL_APP_DETAIL_GRID_ICON_NAME' || vName = 'DELIVER_ALL_APP_DETAIL_BANNER_IMG_NAME' || vName = 'DELIVER_ALL_APP_BANNER_IMG_NAME' || vName='DELIVER_ALL_APP_GRID_ICON_NAME' || vName = 'DELIVER_ALL_APP_SHOW_SELECTION' )";
              $obj->sql_query($sql2); */
            foreach ($db_service_categories as $key => $value) {
                $iServiceId = $value['iServiceId'];
                if (!empty($iServiceId)) {
                    $q1 = "show tables like 'language_label_" . $iServiceId . "'";
                    $dbchecktabel = $obj->MySQLSelect($q1);
                    if (count($dbchecktabel) <= 0) {
                        $errorcountsystemvalidation += 1;
                        echo "<li>Please Create 'language_label_" . $iServiceId . "' tabel using language_label_2 tabel.</li>";
                    }
                }
            }
            if (!empty($matchresult) || !empty($matchresult2)) {
                $errorcountsystemvalidation += 1;
                echo "<li>Please Add 'Service Ids' in configuration file in array 'service_categories_ids_arr'.</li>";
            }
        }
    }
    //Added By HJ On 20-03-2019 For Remove SMS and Email Templates Start Bug - 6406
    if (strtoupper($ePackageType) != "SHARK") {
        $obj->sql_query("DELETE FROM email_templates WHERE vEmail_Code IN ('USER_REGISTRATION_ORGANIZATION','ORGANIZATION_UPDATE_USERPROFILESTATUS_TO_USER','ORGANIZATION_REGISTRATION_ADMIN','ORGANIZATION_REGISTRATION_USER','ADMIN_UPDATE_USERPROFILESTATUS_TO_ORGANIZATION','STORE_REGISTRATION_USER','STORE_REGISTRATION_ADMIN','MEMBER_BLOCKED_INACTIVE_DRIVER','MEMBER_BLOCKED_ACTIVE_DRIVER','MEMBER_BLOCKED_INACTIVE_USER','MEMBER_BLOCKED_ACTIVE_USER','MEMBER_NEWS_SUBSCRIBE_USER','MEMBER_NEWS_UNSUBSCRIBE_USER')");
    }
    //Added By HJ On 01-07-2019 As Per Discuss With KS Sir Start
    if (ENABLEHOTELPANEL == "No") {
        $obj->sql_query("DELETE FROM administrators WHERE iGroupId='4'"); // By HJ On 20-03-2019 For Solved Bug - 6403
        $obj->sql_query("DELETE FROM admin_groups WHERE iGroupId='4'"); // By HJ On 20-03-2019 For Solved Bug - 6403
        $obj->sql_query("DELETE FROM admin_group_permission WHERE group_id='4'"); // By HJ On 20-03-2019 For Solved Bug - 6403
        $obj->sql_query("DELETE FROM admin_permissions WHERE `permission_name` LIKE 'manage-hotel-payment-report'"); // By HJ On 20-03-2019 For Solved Bug - 6403
    }
    //Added By HJ On 01-07-2019 As Per Discuss With KS Sir End
    if ($eProductType == "Delivery" || $eProductType == "UberX" || ONLYDELIVERALL == "Yes") {
        $obj->sql_query("DELETE FROM email_templates WHERE vEmail_Code IN ('USER_REGISTRATION_ORGANIZATION','ORGANIZATION_UPDATE_USERPROFILESTATUS_TO_USER','ORGANIZATION_REGISTRATION_ADMIN','ORGANIZATION_REGISTRATION_USER','ADMIN_UPDATE_USERPROFILESTATUS_TO_ORGANIZATION')");
        $obj->sql_query("DELETE FROM send_message_templates WHERE vEmail_Code IN ('EMERGENCY_SMS_FOR_USER_RIDE','EMERGENCY_SMS_FOR_DRIVER_RIDE')");
    }
    if ($eProductType == "Ride" || $eProductType == "Delivery" || $eProductType == "Ride-Delivery" || ONLYDELIVERALL == "Yes") {
        $obj->sql_query("DELETE FROM email_templates WHERE vEmail_Code IN ('MANUAL_TAXI_DISPATCH_DRIVER_APP_SP','MANUAL_BOOKING_ACCEPT_BYDRIVER_SP','MANUAL_BOOKING_DECLINED_BYDRIVER_SP','MANUAL_BOOKING_CANCEL_BYDRIVER_SP','MANUAL_BOOKING_CANCEL_BYRIDER_SP')");
        $obj->sql_query("DELETE FROM send_message_templates WHERE vEmail_Code IN ('EMERGENCY_SMS_FOR_USER_SP','EMERGENCY_SMS_FOR_DRIVER_SP','DRIVER_SEND_MESSAGE_SP','BOOKING_ACCEPT_BYDRIVER_MESSAGE_SP','BOOKING_DECLINED_BYDRIVER_MESSAGE_SP','BOOKING_CANCEL_BYRIDER_MESSAGE_SP','BOOKING_CANCEL_BYDRIVER_MESSAGE_SP')");
    }
    if (strtoupper($ePackageType) != "SHARK" || $eProductType == "Delivery" || $eProductType == "UberX" || ONLYDELIVERALL == "Yes") {
        $obj->sql_query("DELETE FROM send_message_templates WHERE vEmail_Code IN ('BOOK_FOR_SOMEONE_ELSE_SMS')");
    }
    if (ENABLEKIOSKPANEL != "Yes") {
        $obj->sql_query("DELETE FROM send_message_templates WHERE vEmail_Code IN ('BOOKING_IN_KIOSK')");
    }
    if ($eProductType == "Ride" || $eProductType == "UberX" || ONLYDELIVERALL == "Yes") {
        $obj->sql_query("DELETE FROM send_message_templates WHERE vEmail_Code IN ('EMERGENCY_SMS_FOR_USER_DELIVERY','EMERGENCY_SMS_FOR_DRIVER_DELIVERY')");
    }
    if (strtoupper($ePackageType) == "STANDARD" && ($eProductType == "Delivery" || $eProductType == "Ride-Delivery")) {
        $obj->sql_query("DELETE FROM vehicle_category WHERE eDeliveryType='Multi'");
        $obj->sql_query("DELETE FROM vehicle_category WHERE eFor='DeliveryCategory' AND eCatType='MultipleDelivery'");
        if (ENABLE_MULTI_DELIVERY == 'Yes') {
            $errorcountsystemvalidation += 1;
            echo "<li>Please Update ENABLE_MULTI_DELIVERY value as 'No' in configuration_variables file.</li>";
        }
    }
    if ($eProductType == "Ride-Delivery-UberX" && strtoupper($ePackageType) != "SHARK") {
        $getCatId = $obj->MySQLSelect("SELECT VC.iVehicleCategoryId FROM vehicle_category AS VC WHERE VC.eCatType='MoreDelivery' AND VC.eFor='DeliveryCategory'");
        $catIds = "";
        for ($fr = 0; $fr < count($getCatId); $fr++) {
            $catIds .= ",'" . $getCatId[$fr]['iVehicleCategoryId'] . "'";
        }
        if ($catIds != "") {
            $obj->sql_query("DELETE FROM vehicle_category WHERE iParentId=" . trim($catIds, ","));
        }
        $obj->sql_query("DELETE FROM vehicle_category WHERE (((eFor='DeliveryCategory' OR eFor='DeliverAllCategory') AND (eCatType='MoreDelivery' OR eCatType='MultipleDelivery')) OR (eCatType='DeliverAll') OR eDeliveryType='Multi')");
        $obj->sql_query("UPDATE vehicle_category SET iParentId='0',eShowType='Icon' WHERE eCatType='Delivery' OR eCatType='MotoDelivery'");
        $obj->sql_query("DELETE FROM cancel_reason WHERE eType='Deliverall'"); // For Solved Bug - 6467
        $obj->sql_query("DELETE FROM document_master WHERE doc_usertype='store'"); // For Solved Bug - 6465
        $obj->sql_query("DELETE FROM help_detail WHERE eSystem='DeliverAll'"); // For Solved Bug - 6466
        $obj->sql_query("DELETE FROM help_detail_categories WHERE eSystem='DeliverAll'"); // For Solved Bug - 6466
    }
    if (strtoupper($ePackageType) == "STANDARD") {
        $obj->sql_query("DELETE FROM vehicle_category WHERE eCatType='Rental' OR eCatType='MotoRental'");
    }
    //Added By HJ On 20-03-2019 For Remove SMS and Email Templates End Bug - 6406
    //Added By HJ On 11-03-2019 For Set Default configurations As Per KS Sir Start
    if ($applyConfiguration == 1) {
        if ($ePackageType == 'standard') {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'No',eAdminDisplay='No',eStatus='Inactive' WHERE vName='WAYBILL_ENABLE'");
            $obj->sql_query("UPDATE configurations SET `vValue` = 'Normal',eAdminDisplay='No',eStatus='Inactive' WHERE vName='RIDE_DRIVER_CALLING_METHOD'");
            $obj->sql_query("UPDATE configurations SET `vValue` = '',eAdminDisplay='No',eStatus='Inactive' WHERE (vName='SINCH_APP_ENVIRONMENT_HOST' || vName='SINCH_APP_SECRET_KEY' || vName='SINCH_APP_KEY')");
        } else {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'Yes',eAdminDisplay='Yes',eStatus='Active' WHERE vName='WAYBILL_ENABLE'");
            $obj->sql_query("UPDATE configurations SET `vValue` = 'Voip',eAdminDisplay='Yes',eStatus='Active' WHERE vName='RIDE_DRIVER_CALLING_METHOD'");
            $obj->sql_query("UPDATE configurations SET `vValue` = 'sandbox.sinch.com',eAdminDisplay='Yes',eStatus='Active' WHERE vName='SINCH_APP_ENVIRONMENT_HOST'");
            $obj->sql_query("UPDATE configurations SET `vValue` = '',eAdminDisplay='Yes',eStatus='Active' WHERE (vName='SINCH_APP_SECRET_KEY' || vName='SINCH_APP_KEY')");
        }
        $obj->sql_query("UPDATE configurations_payment SET `vValue` = 'Method-1',eAdminDisplay='No',eStatus='Inactive' WHERE vName='SYSTEM_PAYMENT_FLOW'");
        $obj->sql_query("UPDATE configurations SET `vValue` = 'NonStrict',eAdminDisplay='No',eStatus='Inactive' WHERE vName='APP_DESTINATION_MODE'");
        $obj->sql_query("UPDATE configurations SET `vValue` = 'Yes',eAdminDisplay='No',eStatus='Inactive' WHERE (vName='PUBNUB_DISABLED' || vName='ENABLE_PUBNUB' || vName='ENABLE_DELIVERY_MODULE')");
        $obj->sql_query("UPDATE configurations SET `vValue` = 'No',eAdminDisplay='Yes',eStatus='Active' WHERE vName='MAILGUN_ENABLE'");
        $obj->sql_query("UPDATE configurations SET `vValue` = '',eAdminDisplay='Yes',eStatus='Active' WHERE (vName='MAILGUN_USER' || vName='MAILGUN_KEY')");
        $obj->sql_query("UPDATE configurations SET `vValue` = 'Provider',eAdminDisplay='No',eStatus='Inactive' WHERE vName='SERVICE_PROVIDER_FLOW'");
        /* if (($eProductType == 'UberX' || $eProductType == "Ride-Delivery-UberX") && ONLYDELIVERALL != "Yes") {
          $obj->sql_query("UPDATE configurations SET `vValue` = 'Provider',eAdminDisplay='Yes',eStatus='Active' WHERE vName='SERVICE_PROVIDER_FLOW'");
          } else {
          $obj->sql_query("UPDATE configurations SET `vValue` = 'Provider',eAdminDisplay='No',eStatus='Inactive' WHERE vName='SERVICE_PROVIDER_FLOW'");
          } */
        if ($ePackageType == 'standard' || $ePackageType == 'enterprise') {
            $obj->sql_query("UPDATE configurations SET `vValue` = '5',eAdminDisplay='No',eStatus='Inactive' WHERE vName='SHOW_ADVERTISE_AFTER_MINUTES'");
            $obj->sql_query("UPDATE configurations SET `vValue` = '24',eAdminDisplay='No',eStatus='Inactive' WHERE vName='CANCEL_DECLINE_TRIPS_IN_HOURS'");
            $obj->sql_query("UPDATE configurations SET `vValue` = '',eAdminDisplay='No',eStatus='Inactive' WHERE (vName='LINKEDIN_APP_SECRET_KEY' || vName='LINKEDIN_APP_ID')");
            $obj->sql_query("UPDATE configurations SET `vValue` = 'Disable',eAdminDisplay='No',eStatus='Inactive' WHERE vName='ADVERTISEMENT_TYPE'");
            $obj->sql_query("UPDATE configurations SET `vValue` = 'No',eAdminDisplay='No',eStatus='Inactive' WHERE (vName='ENABLE_NEWSLETTERS_SUBSCRIPTION_SECTION' || vName='ENABLE_NEWS_SECTION' || vName='ENABLE_LIVE_CHAT' || vName='ENABLE_DRIVER_ADVERTISEMENT_BANNER' || vName='ENABLE_RIDER_ADVERTISEMENT_BANNER' || vName='LIVE_CHAT_LICENCE_NUMBER' || vName='PASSENGER_LINKEDIN_LOGIN' || vName='DRIVER_LINKEDIN_LOGIN')");
        } else {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'Yes',eAdminDisplay='Yes',eStatus='Active' WHERE (vName='ENABLE_NEWSLETTERS_SUBSCRIPTION_SECTION' || vName='ENABLE_NEWS_SECTION' || vName='ENABLE_LIVE_CHAT' || vName='ENABLE_DRIVER_ADVERTISEMENT_BANNER' || vName='ENABLE_RIDER_ADVERTISEMENT_BANNER' || vName='PASSENGER_LINKEDIN_LOGIN' || vName='DRIVER_LINKEDIN_LOGIN')");
            //$obj->sql_query("UPDATE configurations SET `vValue` = 'sandbox.sinch.com',eAdminDisplay='Yes',eStatus='Active' WHERE vName='SINCH_APP_ENVIRONMENT_HOST'");
            $obj->sql_query("UPDATE configurations SET `vValue` = '5',eAdminDisplay='Yes',eStatus='Active' WHERE vName='SHOW_ADVERTISE_AFTER_MINUTES'");
            $obj->sql_query("UPDATE configurations SET `vValue` = '24',eAdminDisplay='Yes',eStatus='Active' WHERE vName='CANCEL_DECLINE_TRIPS_IN_HOURS'");
            //$obj->sql_query("UPDATE configurations SET `vValue` = '',eAdminDisplay='Yes',eStatus='Active' WHERE (vName='SINCH_APP_SECRET_KEY' || vName='SINCH_APP_KEY')");
            $obj->sql_query("UPDATE configurations SET `vValue` = 'Sequential',eAdminDisplay='No',eStatus='Inactive' WHERE vName='ADVERTISEMENT_TYPE'");
        }
        if (DELIVERALL == "Yes" && strtoupper($ePackageType) == 'SHARK') {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'Yes',eAdminDisplay='Yes',eStatus='Active' WHERE (vName='ENABLE_RESTAURANTS_ADVERTISEMENT_BANNER' || vName='ENABLE_FAVORITE_STORE_MODULE')");
        } else {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'No',eAdminDisplay='No',eStatus='Inactive' WHERE (vName='ENABLE_RESTAURANTS_ADVERTISEMENT_BANNER' || vName='ENABLE_FAVORITE_STORE_MODULE')");
        }
        $obj->sql_query("UPDATE configurations SET `vValue` = 'No',eAdminDisplay='No',eStatus='Inactive' WHERE (vName='ENABLE_INSURANCE_ACCEPT_REPORT' || vName='ENABLE_INSURANCE_TRIP_REPORT' || vName='ENABLE_INSURANCE_IDLE_REPORT' || vName='WHEEL_CHAIR_ACCESSIBILITY_OPTION' || vName='PASSENGER_TWITTER_LOGIN' || vName='DRIVER_TWITTER_LOGIN')");
        if (($eProductType == 'Delivery' || $eProductType == 'UberX') || ($ePackageType == 'standard' || $ePackageType == 'enterprise') || ONLYDELIVERALL == "Yes") {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'No',eAdminDisplay='No',eStatus='Inactive' WHERE (vName='ENABLE_INTRANSIT_SHOPPING_SYSTEM' || vName='ENABLE_AIRPORT_SURCHARGE_SECTION' || vName='BOOK_FOR_ELSE_ENABLE' || vName='CHILD_SEAT_ACCESSIBILITY_OPTION' || vName='POOL_ENABLE' || vName='ENABLE_CORPORATE_PROFILE' || vName='FEMALE_RIDE_REQ_ENABLE')");
            $obj->sql_query("UPDATE configurations SET `vValue` = '5',eAdminDisplay='No',eStatus='Inactive' WHERE (vName='RESTRICTION_KM_NEAREST_DESTINATION_POOL' || vName='RESTRICTION_KM_NEAREST_TAXI_POOL' || vName='BOOK_FOR_ELSE_SHOW_NO_CONTACT')");
        } else {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'Yes',eAdminDisplay='Yes',eStatus='Active' WHERE (vName='ENABLE_INTRANSIT_SHOPPING_SYSTEM' || vName='ENABLE_AIRPORT_SURCHARGE_SECTION' || vName='BOOK_FOR_ELSE_ENABLE' || vName='CHILD_SEAT_ACCESSIBILITY_OPTION' || vName='POOL_ENABLE' || vName='ENABLE_CORPORATE_PROFILE')");
            $obj->sql_query("UPDATE configurations SET `vValue` = '5',eAdminDisplay='Yes',eStatus='Active' WHERE (vName='RESTRICTION_KM_NEAREST_DESTINATION_POOL' || vName='RESTRICTION_KM_NEAREST_TAXI_POOL' || vName='BOOK_FOR_ELSE_SHOW_NO_CONTACT')");
        }
        $obj->sql_query("UPDATE configurations SET `vValue` = '9',eAdminDisplay='Yes',eStatus='Active' WHERE vName='GRID_TILES_MAX_COUNT'");
        if ($eProductType == "Ride-Delivery-UberX" && ONLYDELIVERALL != "Yes") {
            $obj->sql_query("UPDATE configurations SET eAdminDisplay='Yes',eStatus='Active' WHERE vName='GRID_TILES_MAX_COUNT'");
        } else {
            $obj->sql_query("UPDATE configurations SET eAdminDisplay='No',eStatus='Inactive' WHERE vName='GRID_TILES_MAX_COUNT'");
        }
        $obj->sql_query("UPDATE configurations SET `vValue` = 'Single',eAdminDisplay='No',eStatus='Inactive' WHERE vName='DELIVERY_MODULE_MODE'");
        $obj->sql_query("UPDATE configurations SET `vValue` = '',eAdminDisplay='No',eStatus='Inactive' WHERE vName='YALGAAR_CLIENT_KEY'");
        if (strtoupper($ePackageType) != 'SHARK') {
            $obj->sql_query("UPDATE configurations SET `vValue` = '',eAdminDisplay='No',eStatus='Inactive' WHERE vName='EXCHANGE_CURRENCY_RATES_APP_ID'");
        } else {
            $obj->sql_query("UPDATE configurations SET `vValue` = '',eAdminDisplay='Yes',eStatus='Inactive' WHERE vName='EXCHANGE_CURRENCY_RATES_APP_ID'");
        }
        $obj->sql_query("UPDATE configurations SET `vValue` = 'SocketCluster',eAdminDisplay='No',eStatus='Inactive' WHERE vName='PUBSUB_TECHNIQUE'");
        $obj->sql_query("UPDATE configurations SET `vValue` = '2',eAdminDisplay='Yes',eStatus='Active' WHERE vName='MIN_CHAR_REQ_GOOGLE_AUTO_COMPLETE'");
        $obj->sql_query("UPDATE configurations SET `vValue` = '30-180',eAdminDisplay='No',eStatus='Inactive' WHERE vName='FETCH_TRIP_STATUS_TIME_INTERVAL'");
        $obj->sql_query("UPDATE configurations SET `vValue` = '100',eAdminDisplay='No',eStatus='Inactive' WHERE vName='SITE_POLICE_CONTROL_NUMBER'");
        $obj->sql_query("UPDATE configurations SET `vValue` = '1',eAdminDisplay='Yes',eStatus='Inactive' WHERE vName='DESTINATION_UPDATE_TIME_INTERVAL'");
        $obj->sql_query("UPDATE configurations SET `vValue` = '1',eAdminDisplay='No',eStatus='Inactive' WHERE vName='ONLINE_DRIVER_LIST_UPDATE_TIME_INTERVAL'");
        $obj->sql_query("UPDATE configurations SET `vValue` = 'sec-c-KoPMtUgEL2QPdViKFr88UiKlOlReQWSyRGE6IJFROvgbLbKY',eAdminDisplay='No',eStatus='Inactive' WHERE vName='PUBNUB_SECRET_KEY'");
        $obj->sql_query("UPDATE configurations SET `vValue` = 'sub-c-9r3u6k8c-h9kl-66s9-b85h-d8e695euy20k',eAdminDisplay='No',eStatus='Inactive' WHERE vName='PUBNUB_SUBSCRIBE_KEY'");
        $obj->sql_query("UPDATE configurations SET `vValue` = 'pub-c-49394564-gr96-95g7-8530-96f5f2dv9w53',eAdminDisplay='No',eStatus='Inactive' WHERE vName='PUBNUB_PUBLISH_KEY'");
        $obj->sql_query("UPDATE configurations SET `vValue` = 'fg5k3i7i7l5ghgk1jcv43w0j41',eAdminDisplay='No',eStatus='Inactive' WHERE vName='PUBNUB_UUID'");
        if ($ePackageType != 'standard' && $eProductType != "UberX" && $eProductType != "Ride" && ONLYDELIVERALL == "No") {
            $obj->sql_query("UPDATE configurations SET `vValue` = '5',eAdminDisplay='Yes',eStatus='Active' WHERE vName='MAX_ALLOW_NUM_DESTINATION_MULTI'");
            $obj->sql_query("UPDATE configurations SET `vValue` = 'Yes',eAdminDisplay='Yes',eStatus='Active' WHERE (vName='ENABLE_ROUTE_CALCULATION_MULTI' || vName='ENABLE_ROUTE_OPTIMIZE_MULTI')");
        } else {
            $obj->sql_query("UPDATE configurations SET `vValue` = '1',eAdminDisplay='No',eStatus='Inactive' WHERE vName='MAX_ALLOW_NUM_DESTINATION_MULTI'");
            $obj->sql_query("UPDATE configurations SET `vValue` = 'No',eAdminDisplay='No',eStatus='Inactive' WHERE (vName='ENABLE_ROUTE_CALCULATION_MULTI' || vName='ENABLE_ROUTE_OPTIMIZE_MULTI')");
        }
        if (ENABLEKIOSKPANEL == "Yes") {
            $obj->sql_query("UPDATE configurations SET `vValue` = '45',eAdminDisplay='Yes',eStatus='Active' WHERE vName='KIOSK_BOOKING_CONFIRM_TIME_IN_SECONDS'");
        } else {
            $obj->sql_query("UPDATE configurations SET `vValue` = '45',eAdminDisplay='No',eStatus='Inactive' WHERE vName='KIOSK_BOOKING_CONFIRM_TIME_IN_SECONDS'");
        }
        if (ONLYDELIVERALL == "Yes" || DELIVERALL == "Yes") {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'Yes',eAdminDisplay='Yes',eStatus='Active' WHERE (vName='COMPANY_PHONE_VERIFICATION' || vName='COMPANY_EMAIL_VERIFICATION')");
            $obj->sql_query("UPDATE configurations SET `vValue` = '10',eAdminDisplay='Yes',eStatus='Active' WHERE (vName='MIN_ORDER_CANCELLATION_CHARGES' || vName='ADMIN_COMMISSION')");
            $obj->sql_query("UPDATE configurations SET `vValue` = '5',eAdminDisplay='Yes',eStatus='Active' WHERE vName='LIST_RESTAURANT_LIMIT_BY_DISTANCE'");
        } else {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'No',eAdminDisplay='No',eStatus='Inactive' WHERE (vName='COMPANY_PHONE_VERIFICATION' || vName='COMPANY_EMAIL_VERIFICATION')");
            $obj->sql_query("UPDATE configurations SET `vValue` = '10',eAdminDisplay='No',eStatus='Inactive' WHERE (vName='MIN_ORDER_CANCELLATION_CHARGES' || vName='ADMIN_COMMISSION')");
            $obj->sql_query("UPDATE configurations SET `vValue` = '5',eAdminDisplay='No',eStatus='Inactive' WHERE vName='LIST_RESTAURANT_LIMIT_BY_DISTANCE'");
        }
        $obj->sql_query("UPDATE configurations SET `vValue` = 'Yes',eAdminDisplay='No',eStatus='Inactive' WHERE vName='ENABLE_SOCKET_CLUSTER'");
        $obj->sql_query("UPDATE configurations SET `vValue` = '5',eAdminDisplay='No',eStatus='Inactive' WHERE vName='PUBSUB_PUBLISH_DRIVER_LOC_DISTANCE_LIMIT'");
        $obj->sql_query("UPDATE configurations SET `vValue` = '15',eAdminDisplay='Yes',eStatus='Active' WHERE vName='VERIFICATION_CODE_RESEND_TIME_IN_SECONDS_EMERGENCY'");
        $obj->sql_query("UPDATE configurations SET `vValue` = '30',eAdminDisplay='Yes',eStatus='Active' WHERE vName='VERIFICATION_CODE_RESEND_TIME_IN_SECONDS'");
        $obj->sql_query("UPDATE configurations SET `vValue` = '5',eAdminDisplay='Yes',eStatus='Active' WHERE (vName='VERIFICATION_CODE_RESEND_COUNT_EMERGENCY' || vName='VERIFICATION_CODE_RESEND_COUNT_RESTRICTION_EMERGENCY' || vName='VERIFICATION_CODE_RESEND_COUNT_RESTRICTION' || vName='VERIFICATION_CODE_RESEND_COUNT')");
        $obj->sql_query("UPDATE configurations SET `vValue` = '125-300',eAdminDisplay='No',eStatus='Inactive' WHERE vName='AIRPORT_TIME_INTERVAL'");
        if ($ePackageType != 'standard' && $eProductType != 'Delivery' && $eProductType != 'UberX' && ONLYDELIVERALL != "Yes") {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'Yes',eAdminDisplay='Yes',eStatus='Active' WHERE (vName='ENABLE_WAITING_CHARGE_FLAT_TRIP' || vName='ENABLE_WAITING_CHARGE_RENTAL' || vName='ENABLE_SURGE_CHARGE_RENTAL')");
        } else {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'No',eAdminDisplay='No',eStatus='Inactive' WHERE (vName='ENABLE_WAITING_CHARGE_FLAT_TRIP' || vName='ENABLE_WAITING_CHARGE_RENTAL' || vName='ENABLE_SURGE_CHARGE_RENTAL')");
        }
        if (($eProductType == "UberX" || $eProductType == "Ride-Delivery-UberX") && ONLYDELIVERALL == "No") {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'Yes',eAdminDisplay='Yes',eStatus='Active' WHERE (vName='PROVIDER_AVAIL_LOC_CUSTOMIZE')");
            $obj->sql_query("UPDATE configurations SET `vValue` = '30',eAdminDisplay='Yes',eStatus='Active' WHERE vName='BOOKING_LATER_ACCEPT_AFTER_INTERVAL'");
            $obj->sql_query("UPDATE configurations SET `vValue` = '120',eAdminDisplay='Yes',eStatus='Active' WHERE vName='BOOKING_LATER_ACCEPT_BEFORE_INTERVAL'");
        } else {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'No',eAdminDisplay='No',eStatus='Inactive' WHERE (vName='PROVIDER_AVAIL_LOC_CUSTOMIZE')");
            $obj->sql_query("UPDATE configurations SET `vValue` = '30',eAdminDisplay='No',eStatus='Inactive' WHERE vName='BOOKING_LATER_ACCEPT_AFTER_INTERVAL'");
            $obj->sql_query("UPDATE configurations SET `vValue` = '120',eAdminDisplay='No',eStatus='Inactive' WHERE vName='BOOKING_LATER_ACCEPT_BEFORE_INTERVAL'");
        }
        $obj->sql_query("UPDATE configurations SET eAdminDisplay='No',eStatus='Inactive' WHERE vName='ALLOW_SERVICE_PROVIDER_AMOUNT'");
        if ($eProductType != 'Delivery' && $eProductType != 'UberX' && ONLYDELIVERALL == "No" && $ePackageType != "standard") {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'Yes',eAdminDisplay='Yes',eStatus='Active' WHERE (vName='APPLY_SURGE_ON_FLAT_FARE' || vName='ENABLE_HAIL_RIDES')");
            $obj->sql_query("UPDATE configurations SET `vValue` = 'No',eAdminDisplay='Yes',eStatus='Active' WHERE  vName='ENABLE_TOLL_COST'");
        } else {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'No',eAdminDisplay='No',eStatus='Inactive' WHERE (vName='APPLY_SURGE_ON_FLAT_FARE' || vName='ENABLE_HAIL_RIDES' || vName='ENABLE_TOLL_COST')");
        }
        $obj->sql_query("UPDATE configurations SET `vValue` = '#',eAdminDisplay='Yes',eStatus='Active' WHERE (vName='TOLL_COST_APP_CODE' || vName='TOLL_COST_APP_ID')");
        if ($eProductType == 'UberX' || $eProductType == 'Delivery' || $eProductType == 'Foodonly' || $eProductType == 'Deliverall') {
            $sql8 = $obj->sql_query("UPDATE configurations SET `vValue` = 'Yes',`eAdminDisplay` = 'No',eStatus='Inactive' WHERE (vName = 'FEMALE_RIDE_REQ_ENABLE' || vName = 'HANDICAP_ACCESSIBILITY_OPTION' ||  vName =  'ENABLE_WAITING_CHARGE_FLAT_TRIP' ||  vName =  'APPLY_SURGE_ON_FLAT_FARE')");
        }
        if ($eProductType == "UberX") {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'Yes',eAdminDisplay='No',eStatus='Inactive' WHERE vName='ENABLE_EDIT_DRIVER_VEHICLE'");
        } else {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'Yes',eAdminDisplay='Yes',eStatus='Active' WHERE vName='ENABLE_EDIT_DRIVER_VEHICLE'");
        }
        if ($eProductType == "UberX" || ONLYDELIVERALL == "Yes") {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'All',eAdminDisplay='No',eStatus='Inactive' WHERE vName='DRIVER_REQUEST_METHOD'");
        } else {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'All',eAdminDisplay='Yes',eStatus='Active' WHERE vName='DRIVER_REQUEST_METHOD'");
        }
        if ($eProductType != 'Delivery' && $eProductType != 'UberX' && ONLYDELIVERALL == "No") {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'Yes',eAdminDisplay='Yes',eStatus='Active' WHERE (vName='IS_DEST_ANYTIME_CHANGE' || vName='ENABLE_TIP_MODULE')");
        } else {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'No',eAdminDisplay='No',eStatus='Inactive' WHERE (vName='IS_DEST_ANYTIME_CHANGE' || vName='ENABLE_TIP_MODULE')");
        }
        if ($eDeliveryType == 'Multi' && $eProductType != 'Ride' && $eProductType != 'UberX' && ONLYDELIVERALL == "No") {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'Code',eAdminDisplay='Yes',eStatus='Active' WHERE vName='DELIVERY_VERIFICATION_METHOD'");
        } else {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'None',eAdminDisplay='No',eStatus='Inactive' WHERE vName='DELIVERY_VERIFICATION_METHOD'");
        }
        if ($eProductType == "Delivery") {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'No',eAdminDisplay='Yes',eStatus='Active' WHERE (vName='ENABLE_ROUTE_CALCULATION_MULTI' || vName='ENABLE_ROUTE_OPTIMIZE_MULTI')");
            $obj->sql_query("UPDATE configurations SET eAdminDisplay='No',eStatus='Inactive' WHERE (vName='DELIVERY_VERIFICATION_METHOD' || vName='ENABLE_SURGE_CHARGE_RENTAL' || vName='ENABLE_WAITING_CHARGE_RENTAL' || vName='ENABLE_WAITING_CHARGE_FLAT_TRIP' || vName='KIOSK_BOOKING_CONFIRM_TIME_IN_SECONDS' || vName='DRIVER_DESTINATIONS_RESET_TIME' || vName='PROVIDER_BOOKING_ACCEPT_TIME_INTERVAL')");
        }
        if ($eProductType == "Ride-Delivery-UberX" && strtoupper($ePackageType) != "SHARK") {
            $obj->sql_query("UPDATE configurations SET eAdminDisplay='No',eStatus='Inactive' WHERE (vName='DELIVERY_VERIFICATION_METHOD' || vName='GRID_TILES_MAX_COUNT' || vName='ENABLE_ROUTE_CALCULATION_MULTI' || vName='ENABLE_ROUTE_OPTIMIZE_MULTI' || vName='MAX_ALLOW_NUM_DESTINATION_MULTI')");
        }
        if (ONLYDELIVERALL == "Yes") {
            $obj->sql_query("UPDATE configurations_payment SET `vValue` = 'Yes',eAdminDisplay='No',eStatus='Inactive' WHERE vName='COMMISION_DEDUCT_ENABLE'");
            $obj->sql_query("UPDATE configurations SET eAdminDisplay='No',eStatus='Inactive' WHERE (vName='RIDE_LATER_BOOKING_ENABLED' || vName='DRIVER_REQUEST_METHOD' || vName='VERIFICATION_CODE_RESEND_COUNT_RESTRICTION_EMERGENCY' || vName='VERIFICATION_CODE_RESEND_COUNT_EMERGENCY' || vName='VERIFICATION_CODE_RESEND_TIME_IN_SECONDS_EMERGENCY' || vName='WAYBILL_ENABLE' || vName='PROVIDER_BOOKING_ACCEPT_TIME_INTERVAL' || vName='CANCEL_DECLINE_TRIPS_IN_HOURS')");
        }
        //Added By HJ On 11-06-2019 For Enable/Disable Driver Subscription and Favorite Feature Related Configuration Start
        if (ONLYDELIVERALL == "Yes" || strtoupper($ePackageType) != "SHARK" || $eProductType != "Ride-Delivery-UberX") {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'No',eAdminDisplay='No',eStatus='Inactive' WHERE (vName='DRIVER_SUBSCRIPTION_ENABLE' || vName='DRIVER_SUBSCRIPTION_REMINDER_NOTIFICATION_DAYS' || vName='ENABLE_FAVORITE_DRIVER_MODULE' || vName='DONATION_ENABLE' || vName='ENABLE_DRIVER_DESTINATIONS' || vName='ENABLE_STOPOVER_POINT')");
            $obj->sql_query("UPDATE configurations_payment SET `vValue` = 'No',eAdminDisplay='No',eStatus='Inactive' WHERE vName='ENABLE_GOPAY'");
            $obj->sql_query("UPDATE configurations SET eAdminDisplay='No',eStatus='Inactive' WHERE (vName='MAX_DRIVER_DESTINATIONS' || vName='DRIVER_DESTINATIONS_RESET_TIME' || vName='RESTRICTION_KM_NEAREST_DESTINATION_DRIVER' || vName='MAX_NUMBER_STOP_OVER_POINTS')");
        } else {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'Yes',eAdminDisplay='Yes',eStatus='Active' WHERE (vName='DRIVER_SUBSCRIPTION_ENABLE' || vName='DRIVER_SUBSCRIPTION_REMINDER_NOTIFICATION_DAYS' || vName='ENABLE_FAVORITE_DRIVER_MODULE' || vName='DONATION_ENABLE' || vName='ENABLE_DRIVER_DESTINATIONS' || vName='ENABLE_STOPOVER_POINT')");
            $obj->sql_query("UPDATE configurations_payment SET `vValue` = 'Yes',eAdminDisplay='Yes',eStatus='Active' WHERE vName='ENABLE_GOPAY'");
            $obj->sql_query("UPDATE configurations SET eAdminDisplay='Yes',eStatus='Active' WHERE (vName='MAX_DRIVER_DESTINATIONS' || vName='DRIVER_DESTINATIONS_RESET_TIME' || vName='RESTRICTION_KM_NEAREST_DESTINATION_DRIVER' || vName='MAX_NUMBER_STOP_OVER_POINTS')");
        }
        //Added By HJ On 11-06-2019 For Enable/Disable Driver Subscription and Favorite Feature Related Configuration End
        if ($eProductType == "UberX") {
            $obj->sql_query("UPDATE configurations SET `vValue` = 'No',eAdminDisplay='No',eStatus='Inactive' WHERE vName='WAYBILL_ENABLE'");
        }
        $obj->sql_query("UPDATE setup_info SET `eConfigurationApplied` = 'Yes'");
    }
    //Added By HJ On 11-03-2019 For Set Default configurations As Per KS Sir End
    //Added By HJ On 20-03-2019 For Solved Bug - 6403 As Per Discuss with BM Mam QA. Start
    if (strtoupper($ePackageType) != "SHARK" || $eProductType == "Delivery" || $eProductType == "UberX" || ONLYDELIVERALL == "Yes") {
        $obj->sql_query("DELETE FROM vehicle_type WHERE ePoolStatus='Yes'");
    }
    $obj->sql_query("UPDATE `language_label` SET `vValue` = 'Tax' WHERE `vLabel` = 'LBL_TAX1_TXT' || `vLabel` = 'LBL_TAX2_TXT'");
    //Added By HJ On 20-03-2019 For Solved Bug - 6403 As Per Discuss with BM Mam QA. End
    //echo "<pre>";print_r($deleteFileArr);die;
    //Added BY HJ On 11-03-2019 For Auto Remove File Start
    $fileCount = count($deleteFileArr);
    if (count($deleteFileArr) > 0) {
        $shFilePath = dirname(__FILE__) . "/setup_info/setup.sh";
        $fp = fopen($shFilePath, "w");
        fwrite($fp, "#!/bin/bash");
        fwrite($fp, "\n");
        for ($g = 0; $g < count($deleteFileArr); $g++) {
            $filename = $deleteFileArr[$g] . "\n";
            //fwrite($fp, "rm -f " . $filename);
            fwrite($fp, "rm -R -f " . $filename);
            //unlink($filename);
            /* if (is_writable($filename)) {
              //unlink($filename);
              echo "Writable - " . $filename . "<br>";
              } else {
              echo "Not Writable - " . $filename . "<br>";
              } */
        }
    }
    //Added BY HJ On 11-03-2019 For Auto Remove File End
    //Added BY HJ On 12-03-2019 For Check webimages Folder Permission for Upload Image Start
    $webImagePermission = dirname(__FILE__) . "/webimages";
    if (!is_writable($webImagePermission)) {
        echo "<li>Please Set write permission to folder " . $webImagePermission . "</li>";
        $errorcountsystemvalidation += 1;
    }
    //Added BY HJ On 12-03-2019 For Check webimages Folder Permission for Upload Image Start
    //Added BY HJ On 12-03-2019 For Set Default Configuration in php.ini file Start
    $memory_limit = trim(ini_get('memory_limit'), "M"); // Must Be -1
    if ($memory_limit != "-1") {
        echo "<li>Please Set memory_limit '-1' in php.ini configuration file.</li>";
        $errorcountsystemvalidation += 1;
    }
    $upload_max_filesize = getUploadSizeInMB(ini_get('upload_max_filesize')); // Must Be Min 100
    if ($upload_max_filesize < 100) {
        echo "<li>Please Set upload_max_filesize minimum 100 in php.ini configuration file.</li>";
        $errorcountsystemvalidation += 1;
    }
    $post_max_size = getUploadSizeInMB(ini_get('post_max_size')); // Must Be Min 100
    if ($post_max_size < 100) {
        echo "<li>Please Set post_max_size minimum 100 in php.ini configuration file.</li>";
        $errorcountsystemvalidation += 1;
    }
    $post_max_execution_time = getUploadSizeInMB(ini_get('max_execution_time')); // Must Be 0
    if ($post_max_execution_time != 0) {
        echo "<li>Please Set max_execution_time 0 in php.ini configuration file.</li>";
        $errorcountsystemvalidation += 1;
    }
    //Added BY HJ On 12-03-2019 For Set Default Configuration in php.ini file End
    echo '</ol>';
    if ($errorcountsystemvalidation > 0) {
        echo '<ol class="validation">';
        $notUsedFolder = dirname(__FILE__) . "/admin/NOTUSED";
        $translateFolder = dirname(__FILE__) . "/translate";
        if (file_exists($notUsedFolder) || file_exists($translateFolder)) {
            echo "<li>[Note :Please Remove Below Folder in all APP Type.] <br/> ";
        }
        $number = 0;
        if (file_exists($notUsedFolder)) {
            $number += 1;
            echo $number . ". Remove admin/NOTUSED folder in admin panel. <br/>";
        }
        if (file_exists($translateFolder)) {
            $number += 1;
            echo $number . ". Remove 'translate' folder in root.";
        }
        if ($eProductType != 'Foodonly' && $eProductType != 'Deliverall') {
            if ($ePackageType == 'standard') {
                ?>
                [Note : Please Remove location wise fare menu From admin left menu and geo location files.]
                <?
            }
        }
        echo "<br>Set Cron File.";
        echo "<li>Set Cron for Later Ride Booking File Name in root folder : cron_schedule_ride_new_parent.php</li>";
        echo "<li>Set Cron for Send Notification File Name in root folder: cron_notification_email_parent.php</li>";
        if ($ePackageType == "shark") {
            echo "<li>Set Cron for Auto Update Currency rate File Name in root folder: cron_update_currency.php</li>";
        }
        echo '</ol>';
    }
}

function getUploadSizeInMB($sSize) {
    $sSuffix = strtoupper(substr($sSize, -1));
    if (!in_array($sSuffix, array('P', 'T', 'G', 'M', 'K'))) {
        return (int) $sSize;
    }
    $iValue = substr($sSize, 0, -1);
    switch ($sSuffix) {
        case 'P':
            $iValue = $iValue * 1024 * 1024 * 1024;
            break;
        case 'T':
            $iValue = $iValue * 1024 * 1024;
            break;
        case 'G':
            $iValue = $iValue * 1024;
            break;
        case 'M':
            $iValue = $iValue;
            break;
        case 'K':
            $iValue = $iValue / 1024;
            break;
    }
    return (int) $iValue;
}
?>
<script>
    var fileCount = '<?= $fileCount; ?>';
    $(document).ready(function () {
        if (fileCount > 0) {
            $("#permissionmsg").show();
        } else {
            $("#permissionmsg").hide();
        }
    });
</script>
