<?php
$curr_url = basename($_SERVER['PHP_SELF']);
//include 'common.php' ;
$db_pages = $obj->MySQLSelect("select iPageId,eStatus,vPageName from pages");
$user = isset($_SESSION["sess_user"]) ? $_SESSION["sess_user"] : '';
$eSystem = isset($_SESSION["sess_eSystem"]) ? $_SESSION["sess_eSystem"] : '';
$user = $_SESSION["sess_user"];
if ($user == 'driver') {
    $sql = "select * from register_driver where iDriverId = '" . $_SESSION['sess_iUserId'] . "'";
    $db_data = $obj->sql_query($sql);
    if ($db_data[0]['vImage'] == "NONE" || $db_data[0]['vImage'] == '') {
        $db_data[0]['img'] = "";
    } else {

        $db_data[0]['img'] = $tconfig["tsite_upload_images_driver"] . '/' . $_SESSION['sess_iUserId'] . '/2_' . $db_data[0]['vImage'];
    }
}
if ($user == 'company') {
    $sql = "select * from company where iCompanyId = '" . $_SESSION['sess_iUserId'] . "'";
    $db_data = $obj->sql_query($sql);

    if ($db_data[0]['vImage'] == "NONE" || $db_data[0]['vImage'] == '') {
        $db_data[0]['img'] = "";
    } else {
        $db_data[0]['img'] = $tconfig["tsite_upload_images_compnay"] . '/' . $_SESSION['sess_iUserId'] . '/2_' . $db_data[0]['vImage'];
    }
}
//Added By HJ On 07-01-2019 For Display Organization Menu Start
if ($user == 'organization') {
    
}
//Added By HJ On 07-01-2019 For Display Organization Menu End
if ($user == 'rider') {
    $sql = "select * from register_user where iUserId = '" . $_SESSION['sess_iUserId'] . "'";
    $db_data = $obj->sql_query($sql);
    if ($db_data[0]['vImgName'] != "NONE") {
        $db_data[0]['img'] = $tconfig["tsite_upload_images_passenger"] . '/' . $_SESSION['sess_iUserId'] . '/2_' . $db_data[0]['vImgName'];
    } else {
        $db_data[0]['img'] = "";
    }
}  //echo "<pre>";print_r($db_data);echo "</pre>";

if ($host_system == 'cubetaxishark' || $host_system == 'cubetaxi5plus') {
    $logo = "menu-logo-cubetaxi.png";
} else if ($host_system == 'cubedelivery') {
    $logo = "menu-logo_delivery.png";
} else {
    $logo = "menu-logo.png";
}
$logopath = '';
if ($host_system == "cubegrocery") {
    $logopath = 'grocery/';
} else if ($host_system == "cubepharmacy") {
    $logopath = 'pharmacy/';
} else if ($host_system == "cubeherbs") {
    $logopath = 'herbs/';
}

$manualOrderMenu = $langage_lbl['LBL_MANUAL_STORE_ORDER_TXT'];
if (isset($_SESSION[$orderServiceNameSession])) {
    $manualOrderMenu = $_SESSION[$orderServiceNameSession];
}
$manualOrderMenu = $langage_lbl['LBL_MANUAL_STORE_ORDER_TXT'];
$RideDeliveryIconArrStatus = $generalobj->CheckRideDeliveryFeatureDisableWeb();
$RideDeliveryBothFeatureDisable = $RideDeliveryIconArrStatus['RideDeliveryBothFeatureDisable'];
$DONATION = $DRIVER_DESTINATION = $FAVOURITE_DRIVER = $FAVOURITE_STORE = $DRIVER_SUBSCRIPTION = $GOJEK_GOPAY = $MULTI_STOPOVER_POINTS = $MANUAL_STORE_ORDER_WEBSITE = $MANUAL_STORE_ORDER_STORE_PANEL = $MANUAL_STORE_ORDER_ADMIN_PANEL = "No"; // Added By HJ On 12-07-2019
$setupData = $obj->sql_query("select lAddOnConfiguration from setup_info");
if (isset($setupData[0]['lAddOnConfiguration'])) {
    $addOnData = json_decode($setupData[0]['lAddOnConfiguration'], true);
    foreach ($addOnData as $addOnKey => $addOnVal) {
        $$addOnKey = $addOnVal;
    }
}
$siteUrl = $tconfig['tsite_url'];
?>
<span id="shadowbox" onClick="menuClose()"></span>
<nav>
    <ul id="listMenu">
        <span class="desktop">
            <div class="menu-logo">
                <section id="navBtn" class="navBtnNew navOpen" onClick="menuClose()">
                    <div></div>
                    <div></div>
                    <div></div>
                </section>
                <?php if (($db_data[0]['img'] == '' || $db_data[0]['img'] == 'NONE') && ($user == "")) { ?>
                    <a href="index.php" class="logo-left signin"><img src="assets/img/<?php echo $logopath . $logo; ?>" alt=""></a>
                    <label><a href="sign-in" class="<?php echo strstr($_SERVER['SCRIPT_NAME'], '/sign-in') || strstr($_SERVER['SCRIPT_NAME'], '/login-new') ? 'active' : '' ?>">
                            <i aria-hidden="true" class="fa fa-user"></i><?= $langage_lbl['LBL_HEADER_TOPBAR_SIGN_IN_TXT']; ?></a></label>
                <?php } else { ?>
                    <strong><a href="index.php" class="logo-left"><img src="<?php echo $db_data[0]['img']; ?>" alt=""></a></strong>
                    <p><!-- <?php echo $db_data[0]['vName'] . ' ' . $db_data[0]['vLastName']; ?> -->
                        <?
                        if ($_SESSION['sess_user'] == 'driver' || $_SESSION['sess_user'] == 'rider') {
                            echo $generalobj->cleanall(htmlspecialchars($db_data[0]['vName'] . " " . $db_data[0]['vLastName']));
                        }
                        ?><?
                        if ($_SESSION['sess_user'] == 'company') {
                            echo $generalobj->cleanall(htmlspecialchars($db_data[0]['vCompany']));
                        }
                        ?>
                    </p>
                <?php } ?>
            </div>
            <div class="menu-left-new">
                <?php if ($user == "") { ?>
                    <?php if ($db_pages[4]['eStatus'] == "Active") { ?><li><a href="how-it-works" class="<?= (isset($script) && $script == 'How It Works') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_HOW_IT_WORKS']; ?></a></li><?php } ?> 
                    <?php if ($db_pages[5]['eStatus'] == "Active") { ?><li><a href="trust-safty-insurance" class="<?= (isset($script) && $script == 'Trust Safty Insurance') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_SAFETY_AND_INSURANCE']; ?></a></li><?php } ?>
                    <?php if ($db_pages[3]['eStatus'] == "Active") { ?><li><a href="terms-condition" class="<?= (isset($script) && $script == 'Terms Condition') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_FOOTER_TERMS_AND_CONDITION']; ?></a></li><?php } ?> 
                    <?php if ($db_pages[2]['eStatus'] == "Active") { ?><li><a href="legal" class="<?= (isset($script) && $script == 'Legal') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_LEGAL']; ?></a></li><?php } ?> 
                    <li><a href="faq" class="<?= (isset($script) && $script == 'Faq') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_FAQs']; ?></a></li>
                    <?php
                } else {
                    if ($user == 'driver') {
                        ?>

                        <li><a href="profile" class="<?= (isset($script) && $script == 'Profile') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-profile-icon.png"></b><span><?= $langage_lbl['LBL_HEADER_TOPBAR_PROFILE_TITLE_TXT']; ?></span></a></li>
                        <?php
                        if ($APP_TYPE != 'UberX') {
                            if ($RideDeliveryBothFeatureDisable == 'No') {
                                ?>
                                <li><a href="vehicle" class="<?= (isset($script) && $script == 'Vehicle') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-taxi.png"></b><span><?= $langage_lbl['LBL_HEADER_TOPBAR_VEHICLES']; ?></span></a></li>
                                <?php
                            }
                        }
                        if ($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX') {
                            ?>
                            <li><a href="add_services.php?iDriverId=<?= base64_encode(base64_encode($_SESSION['sess_iUserId'])); ?>" class="<?= (isset($script) && $script == 'My Availability') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/repairing-service-2.png"></b><span><?= $langage_lbl['LBL_HEADER_MY_SERVICES']; ?></span></a></li>
                            <li><a href="add_availability.php" class="<?= (isset($script) && $script == 'My Services') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/avilable.png"></b><span><?= $langage_lbl['LBL_HEADER_MY_AVAILABILITY']; ?></span></a></li>
                            <?php
                        }
                        if ($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX') {
                            ?>
                            <li><a href="provider-job" class="<?= (isset($script) && $script == 'Trips') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-trips.png"></b><span><?= $langage_lbl['LBL_HEADER_TOPBAR_TRIPS_TEXT']; ?></span></a></li>
                        <?php } else { ?>
                            <li><a href="driver-trip" class="<?= (isset($script) && $script == 'Trips') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-trips.png"></b><span><?= $langage_lbl['LBL_HEADER_TOPBAR_TRIPS_TEXT']; ?></span></a></li>
                        <?php } ?>
                        <?php if (DELIVERALL == "Yes") { ?>
                            <li><a href="driver-order" class="<?= (isset($script) && $script == 'Order') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-trips.png"></b><span><?= $langage_lbl['LBL_MY_DRIVER_ORDERS_TXT']; ?></span></a></li>
                        <?php } ?>
                        <li><a href="payment-request" class="<?= (isset($script) && $script == 'Payment Request') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/myearnings.png"></b><span><?= $langage_lbl['LBL_HEADER_MY_EARN']; ?></span></a></li>
                        <?php
                        if ($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX') {
                            if ($WALLET_ENABLE == 'Yes') {
                                ?> 
                                <li><a href="provider_wallet" class="<?= (isset($script) && $script == 'Rider Wallet') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-wallet.png"></b><span><?= $langage_lbl['LBL_RIDER_WALLET']; ?></span></a></li>
                                <?
                            }
                        } else {
                            if ($WALLET_ENABLE == 'Yes') {
                                ?> 
                                <li><a href="driver_wallet" class="<?= (isset($script) && $script == 'Rider Wallet') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-wallet.png"></b><span><?= $langage_lbl['LBL_RIDER_WALLET']; ?></span></a></li>
                                <?
                            }
                        }
                        if (($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX') && $SERVICE_PROVIDER_FLOW == "Provider") {
                            ?>

                            <li><a href="provider_images" class="<?= (isset($script) && $script == 'Gallary') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/service_img.png"></b><span><?= $langage_lbl['LBL_MANAGE_GALLARY']; ?></span></a></li>

                        <? } ?>
                        <li class="logout"><a href="logout"><b><img alt="" src="assets/img/sign-out.png"></b><span><?= $langage_lbl['LBL_LOGOUT']; ?></span></a></li>

                    <?php } else if ($user == 'company') { ?>

                        <?php if ($eSystem == "DeliverAll") { ?>



                            <li><a href="dashboard" class="<?= (isset($script) && $script == 'Dashboard') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-profile.png"></b><span><?= $langage_lbl['LBL_HEADER_TOPBAR_PROFILE_TITLE_TXT']; ?></span></a></li>



                            <li><a href="food_menu.php" class="<?= (isset($script) && $script == 'FoodMenu') ? 'active' : ''; ?>"><b><i aria-hidden="true" class="fa fa-cutlery"></i></b><span><?= $langage_lbl['LBL_FOOD_CATEGORY_LEFT_MENU']; ?></span></a></li>



                            <li><a href="menuitems.php" class="<?= (isset($script) && $script == 'MenuItems') ? 'active' : ''; ?>"><b><i aria-hidden="true" class="fa fa-list-alt"></i></b><span><?= $langage_lbl['LBL_MENU_ITEM_LEFT_MENU']; ?></span></a></li>



                            <li><a href="processing-orders" class="<?= (isset($script) && $script == 'ProcessingOrder') ? 'active' : ''; ?>"><b><i aria-hidden="true" class="fa fa-dot-circle-o"></i></b><span><?= $langage_lbl['LBL_MY_PROCESSING_ORDERS_TXT']; ?></span></a></li>



                            <li><a href="company-order" class="<?= (isset($script) && $script == 'Order') ? 'active' : ''; ?>"><b><i aria-hidden="true" class="fa fa-dot-circle-o"></i></b><span><?= $langage_lbl['LBL_MY_ORDERS_RESTAURANT_TXT']; ?></span></a></li>
                            <?php if ($MANUAL_STORE_ORDER_WEBSITE == "Yes") { ?>
                                <li><a href="<?= $siteUrl; ?>user-order-information?order=store" target="_blank"><b><img alt="" src="assets/img/custom-order/shopping-cart.png"></b><?= $manualOrderMenu; ?></b></a></li>
                                <?php
                            }
                            ?>
                            <li><a href="settings" class="<?= (isset($script) && $script == 'Settings') ? 'active' : ''; ?>"><b><i aria-hidden="true" class="fa fa-gear"></i></b><span><?= $langage_lbl['LBL_SETTINGS']; ?></span></a></li>
                        <?php } else { ?>
                            <li><a href="profile" class="<?= (isset($script) && $script == 'Profile') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-profile.png"></b><span><?= $langage_lbl['LBL_HEADER_TOPBAR_PROFILE_TITLE_TXT']; ?></span></a></li>



                            <? if ($APP_TYPE == 'Ride-Delivery-UberX' || $APP_TYPE == 'UberX') { ?>

                                <li><a href="providerlist" class="<?= (isset($script) && $script == 'Driver') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-profile-icon.png"></b><span><?= $langage_lbl['LBL_HEADER_TOPBAR_DRIVER']; ?></span></a></li>

                            <? } else { ?>

                                <li><a href="driverlist" class="<?= (isset($script) && $script == 'Driver') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-profile-icon.png"></b><span><?= $langage_lbl['LBL_HEADER_TOPBAR_DRIVER']; ?></span></a></li>

                            <? } ?>

                            <? if ($PACKAGE_TYPE == "SHARK") { ?>

                                <li><a href="companybooking" class="<?= (isset($script) && $script == 'booking') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/manual-taxi-icon.png"></b><span><?= $langage_lbl['LBL_MANUAL_TAXI_DISPATCH']; ?></span></a></li>

                                <li><a href="cabbooking.php" class="<?= (isset($script) && $script == 'CabBooking') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/ride-later-bookings.png"></b><span><?= $langage_lbl['LBL_RIDE_LATER_BOOKINGS_ADMIN']; ?></span></a></li>

                            <? } ?>

                            <?php
                            if ($APP_TYPE != 'UberX') {

                                if ($RideDeliveryBothFeatureDisable == 'No') {
                                    ?>

                                    <li><a href="vehicle" class="<?= (isset($script) && $script == 'Vehicle') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-taxi.png"></b><span><?= $langage_lbl['LBL_HEADER_TOPBAR_VEHICLES']; ?></span></a></li>

                                    <?php
                                }
                            }
                            ?>

                            <li><a href="company-trip" class="<?= (isset($script) && $script == 'Trips') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-trips.png"></b><span><?= $langage_lbl['LBL_HEADER_TOPBAR_TRIPS']; ?></span></a></li>

                                                                            <!--<li><a href="booking.php" class="<?= (isset($script) && $script == 'Booking') ? 'active' : ''; ?>">My Bookings</a></li>-->



                        <?php } ?>



                        <li class="logout"><a href="logout"><b><img alt="" src="assets/img/sign-out.png"></b><span><?= $langage_lbl['LBL_LOGOUT']; ?></span></a></li>
                        <!-- Left Menu For Organization Module -->

                    <?php } else if ($user == 'organization') { ?>

                        <li><a href="organization-profile" class="<?= (isset($script) && $script == 'Organization-Profile') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-profile.png"></b><span><?= $langage_lbl['LBL_HEADER_TOPBAR_PROFILE_TITLE_TXT']; ?></span></a></li>

                        <li><a href="my_users.php" class="<?= (isset($script) && $script == 'MyUsers') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-profile-icon.png"></b><span><!-- <?= $langage_lbl['LBL_HEADER_TOPBAR_DRIVER']; ?> --> Organization Users </span></a></li>

                        <li><a href="users-trip" class="<?= (isset($script) && $script == 'Organization-Users-Trips') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-trips.png"></b>Organization User Trips</a></li>

                        <li><a href="organization-trip" class="<?= (isset($script) && $script == 'Trips') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-trips.png"></b><span><!-- <?= $langage_lbl['LBL_HEADER_TOPBAR_TRIPS']; ?> --> Organization Trip Report </span></a></li>

                        <li class="logout"><a href="logout"><b><img alt="" src="assets/img/sign-out.png"></b><span><?= $langage_lbl['LBL_LOGOUT']; ?></span></a></li>

                        <!-- Left Menu For Organization Module -->		
                    <?php } else if ($user == 'rider') { ?>
                        <?php if ($APP_TYPE == 'Ride-Delivery-UberX' || $APP_TYPE == 'UberX') { ?>
                            <li><a href="<?php echo $tconfig['tsite_url']; ?>profile-user" class="<?= (isset($script) && $script == 'Profile') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-profile.png"></b><span><?= $langage_lbl['LBL_HEADER_TOPBAR_PROFILE_TITLE_TXT']; ?></span></a></li>
                        <?php } else { ?>
                            <li><a href="<?php echo $tconfig['tsite_url']; ?>profile-rider" class="<?= (isset($script) && $script == 'Profile') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-profile.png"></b><span><?= $langage_lbl['LBL_HEADER_TOPBAR_PROFILE_TITLE_TXT']; ?></span></a></li>
                        <? } ?>

                        <li><a href="<?php echo $tconfig['tsite_url']; ?>mytrip" class="<?= (isset($script) && $script == 'Trips') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-trips.png"></b><span><?= $langage_lbl['LBL_HEADER_TOPBAR_TRIPS']; ?></span></a></li>
                        <?php if (DELIVERALL == "Yes") { ?>
                            <li><a href="<?php echo $tconfig['tsite_url']; ?>myorder" class="<?= (isset($script) && $script == 'Order') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-trips.png"></b><span><?= $langage_lbl['LBL_MY_ORDERS_TXT']; ?></span></a></li>
                        <?php } if ($MANUAL_STORE_ORDER_WEBSITE == "Yes") { ?>
                            <li><a href="<?= $siteUrl; ?>user-order-information?order=store" target="_blank"><b><img alt="" src="assets/img/custom-order/shopping-cart.png"></b><?= $manualOrderMenu; ?></b></a></li>
                            <?php
                        }
                        ?>

                                                                          <!-- <li><a href="<?php echo $tconfig['tsite_url']; ?>mobi" ><b><img alt="" src="assets/img/my-taxi.png"></b><span><?= $langage_lbl['LBL_BOOK_A_RIDE']; ?></span></a></li> -->
                        <?
                        if ($APP_TYPE == 'Ride-Delivery-UberX' || $APP_TYPE == 'UberX') {
                            if ($WALLET_ENABLE == 'Yes') {
                                ?> 
                                <li><a href="<?php echo $tconfig['tsite_url']; ?>user_wallet" class="<?= (isset($script) && $script == 'Rider Wallet') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-wallet.png"></b><span><?= $langage_lbl['LBL_RIDER_WALLET']; ?></span></a></li>
                                <?
                            }
                        } else {
                            if ($WALLET_ENABLE == 'Yes') {
                                ?>
                                <li><a href="<?php echo $tconfig['tsite_url']; ?>rider_wallet" class="<?= (isset($script) && $script == 'Rider Wallet') ? 'active' : ''; ?>"><b><img alt="" src="assets/img/my-wallet.png"></b><span><?= $langage_lbl['LBL_RIDER_WALLET']; ?></span></a></li>
                                <?php
                            }
                        }
                        ?>
                        <li class="logout"><a href="logout"><b><img alt="" src="assets/img/sign-out.png"></b><span><?= $langage_lbl['LBL_LOGOUT']; ?></span></a></li>
                        <?php
                    }
                }
                ?>
                <?php if ($user == "") { ?>

                    <b>
                        <a href="sign-up-rider" class="<?= (isset($script) && $script == 'Rider Sign-Up') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_LEFTMENU_SIGN_UP_TO_RIDE']; ?></a>
                        <a class="<?= (isset($script) && $script == 'Driver Sign-Up') ? 'active' : ''; ?>" href="<?= $cjSignUp; ?>"><?= $langage_lbl['LBL_LEFTMENU_BECOME_A_DRIVER']; ?></a>
                        <a class="<?= (isset($script) && $script == 'Organization Sign-Up') ? 'active' : ''; ?>" href="<?= $cjOrganizationLogin; ?>">BECOME AN ORGANIZATION</a></b>

                <?php } ?>

                <div style="clear:both;"></div>
            </div>
        </span>

        <span class="mobile">
            <div class="menu-logo">
                <section id="navBtn" class="navBtnNew navOpen" onClick="menuClose()">
                    <div></div>
                    <div></div>
                    <div></div>
                </section>
                <img src="assets/img/<?php echo $logopath . $logo; ?>" alt="">
            </div>
            <!-- Top Menu Mobile -->
            <div class="menu-left-new">
                <?php if ($user == 'driver') { ?>

                    <li><a href="profile" class="<?= (isset($script) && $script == 'Profile') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_MY_PROFILE_HEADER_TXT']; ?></a></li>
                    <?php if ($RideDeliveryBothFeatureDisable == 'No') { ?>
                        <li><a href="vehicle" class="<?= (isset($script) && $script == 'Vehicle') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_LEFT_MENU_VEHICLES']; ?></a></li>
                    <?php } ?>
                    <? if ($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX') { ?>
                        <li><a href="provider-job" class="<?= (isset($script) && $script == 'Trips') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_LEFT_MENU_TRIPS']; ?></a></li>
                    <? } else { ?>
                        <li><a href="driver-trip" class="<?= (isset($script) && $script == 'Trips') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_LEFT_MENU_TRIPS']; ?></a></li>
                    <? } ?>
                    <li><a href="payment-request" class="<?= (isset($script) && $script == 'Payment Request') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_PAYMENT']; ?></a></li>

                <?php } else if ($user == 'company') { ?>

                    <li><a href="profile" class="<?= (isset($script) && $script == 'Profile') ? 'active' : ''; ?>"> <?= $langage_lbl['LBL_MY_PROFILE_HEADER_TXT']; ?></a></li>
                    <li><a href="driver" class="<?= (isset($script) && $script == 'Driver') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_DRIVER']; ?></a></li>
                    <?php if ($RideDeliveryBothFeatureDisable == 'No') { ?>
                        <li><a href="vehicle" class="<?= (isset($script) && $script == 'Vehicle') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_LEFT_MENU_VEHICLES']; ?></a></li>
                    <?php } ?>
                    <li><a href="company-trip" class="<?= (isset($script) && $script == 'Trips') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_LEFT_MENU_TRIPS']; ?></a></li>

                <?php } else if ($user == 'rider') { ?>
                    <?php if ($APP_TYPE == 'Ride-Delivery-UberX' || $APP_TYPE == 'UberX') { ?>
                        <li><a href="profile-user" class="<?= (isset($script) && $script == 'Profile') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_MY_PROFILE_HEADER_TXT']; ?></a></li>
                    <?php } else { ?>
                        <li><a href="profile-rider" class="<?= (isset($script) && $script == 'Profile') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_MY_PROFILE_HEADER_TXT']; ?></a></li>
                    <? } ?>
                    <li><a href="mytrip" class="<?= (isset($script) && $script == 'Trips') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_LEFT_MENU_TRIPS']; ?></a></li>

                <?php } ?>
                <!-- End Top Menu Mobile -->

                <li><a href="index.php" class="<?= (isset($script) && $script == 'Home') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_HOME']; ?></a></li>
                <li><a href="about-us" class="<?= (isset($script) && $script == 'About Us') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_ABOUT_US_TXT']; ?></a></li>
                <?php if ($db_pages[1]['eStatus'] == "Active") { ?><li><a href="help-center" class="<?= (isset($script) && $script == 'Help Center') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_HELP_CENTER']; ?></a></li><?php } ?>
                <li><a href="contact-us" class="<?= (isset($script) && $script == 'Contact Us') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_CONTACT_US_TXT']; ?></a></li>

                <?php if ($user == "") { ?>

                    <li><a href="sign-in" class="<?= (isset($script) && $script == 'Login Main') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_LEFT_MENU_LOGIN']; ?></a></li>
                    <b>
                        <a href="sign-up-rider" class="<?= (isset($script) && $script == 'Rider Sign-Up') ? 'active' : ''; ?>"><?= $langage_lbl['LBL_LEFTMENU_SIGN_UP_TO_RIDE']; ?></a>
                        <a class="<?= (isset($script) && $script == 'Driver Sign-Up') ? 'active' : ''; ?>" href="<?= $cjSignUp; ?>"><?= $langage_lbl['LBL_LEFTMENU_BECOME_A_DRIVER']; ?></a>
                    </b>

                <?php } else { ?>

                    <li><a href="logout"><?= $langage_lbl['LBL_LOGOUT']; ?></a></li>
                    <div style="clear:both;"></div>

                <?php } ?>

            </div> 
        </span>
    </ul>
</nav>