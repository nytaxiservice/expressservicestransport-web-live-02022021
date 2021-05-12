<?php
//$APP_DELIVERY_MODE = $generalobj->getConfigurations("configurations", "APP_DELIVERY_MODE");
//$RIDE_LATER_BOOKING_ENABLED = $generalobj->getConfigurations("configurations", "RIDE_LATER_BOOKING_ENABLED");
//$DRIVER_SUBSCRIPTION_ENABLE = $generalobj->getConfigurations("configurations", "DRIVER_SUBSCRIPTION_ENABLE");

$catdata = serviceCategories;
$allservice_cat_data = json_decode($catdata, true);
//Added By HJ On 16-10-2019 For Get New Feature Configuratio  Start
$getSetupData = $obj->MySQLSelect("SELECT lAddOnConfiguration FROM setup_info");
//echo "<pre>";print_r($getSetupData);die;
$DONATION = $DRIVER_DESTINATION = $FAVOURITE_DRIVER = $FAVOURITE_STORE = $DRIVER_SUBSCRIPTION = $GOJEK_GOPAY = $MULTI_STOPOVER_POINTS = $MANUAL_STORE_ORDER_WEBSITE = $MANUAL_STORE_ORDER_STORE_PANEL = $MANUAL_STORE_ORDER_ADMIN_PANEL = "No";
if (isset($getSetupData[0]['lAddOnConfiguration'])) {
    $addOnData = json_decode($getSetupData[0]['lAddOnConfiguration'], true);
    foreach ($addOnData as $key => $val) {
        $$key = $val;
    }
    //echo "<pre>";print_r($addOnData);die;
}
//Added By HJ On 16-10-2019 For Get New Feature Configuratio  End
if ($APP_TYPE == 'UberX') {
    $menu = include 'left_menu_ufx_array.php';
} else if (ONLYDELIVERALL == "Yes") {
    $menu = include 'left_menu_deliverall_array.php';
} else {
    $menu = include 'left_menu_uberapp_array.php';
}
?>
<section class="sidebar">
    <!-- Sidebar -->
    <div id="sidebar" class="test" >
        <nav class="menu">
            <?php echo get_admin_nav($menu); ?>
        </nav>
    </div>
</section>