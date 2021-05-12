<?php

define('ENABLE_RENTAL_OPTION','No');
define('ENABLE_MULTI_DELIVERY','No');
define('ENABLEHOTELPANEL','No');
define('ENABLEKIOSKPANEL','No');
define('ONLYDELIVERALL','No');
define('DELIVERALL','No');
$tconfig["tsite_folder"] = ($_SERVER["HTTP_HOST"] == "localhost") ? "/" : "/";

if ($_SERVER["HTTP_HOST"] == "192.168.1.131" || $_SERVER["HTTP_HOST"] == "192.168.1.141") {

    $hst_arr = explode("/", $_SERVER["REQUEST_URI"]);

    $hst_var = $hst_arr[1];

    $tconfig["tsite_folder"] = "/" . $hst_arr[1] . "/";

}

if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {

    $http = "https://";

} else {

    $http = "http://";

}

/* For admin URL */

define('SITE_ADMIN_URL', 'admin');

$tconfig["tsite_url"] = $http . $_SERVER["HTTP_HOST"] . $tconfig["tsite_folder"];

$tconfig["tsite_url_main_admin"] = $http . $_SERVER["HTTP_HOST"] . $tconfig["tsite_folder"] .SITE_ADMIN_URL. '/';

$tconfig["tsite_url_admin"] = $http . $_SERVER["HTTP_HOST"] . $tconfig["tsite_folder"] . 'appadmin/';

$tconfig["tpanel_path"] = $_SERVER["DOCUMENT_ROOT"] . "" . $tconfig["tsite_folder"];

$tconfig["tsite_libraries"] = $tconfig["tsite_url"] . "assets/libraries/";

$tconfig["tsite_libraries_v"] = $tconfig["tpanel_path"] . "assets/libraries/";

$tconfig["tsite_img"] = $tconfig["tsite_url"] . "assets/img";

$tconfig["tsite_home_images"] = $tconfig["tsite_img"] . "/home/";

$tconfig["tsite_upload_images"] = $tconfig["tsite_img"] . "/images/";

$tconfig["tsite_upload_images_panel"] = $tconfig["tpanel_path"] . "assets/img/images/";

//Start ::Company folder

$tconfig["tsite_upload_images_compnay_path"] = $tconfig["tpanel_path"] . "webimages/upload/Company";

$tconfig["tsite_upload_images_compnay"] = $tconfig["tsite_url"] . "webimages/upload/Company";

//End ::Company folder

//Start :: Organization folder

$tconfig["tsite_upload_images_organization_path"] = $tconfig["tpanel_path"] . "webimages/upload/Organization";

$tconfig["tsite_upload_images_organization"] = $tconfig["tsite_url"] . "webimages/upload/Organization";

//End ::Organization folder

//Start ::donation folder

$tconfig["tsite_upload_images_donation_path"] = $tconfig["tpanel_path"] . "webimages/upload/donation";

$tconfig["tsite_upload_images_donation"] = $tconfig["tsite_url"] . "webimages/upload/donation";

//End ::donation folder

/* To upload compnay documents */

$tconfig["tsite_upload_compnay_doc_path"] = $tconfig["tpanel_path"] . "webimages/upload/documents/company";

$tconfig["tsite_upload_compnay_doc"] = $tconfig["tsite_url"] . "webimages/upload/documents/company";

$tconfig["tsite_upload_documnet_size1"] = "250";

$tconfig["tsite_upload_documnet_size2"] = "800";

//Start ::Driver folder

$tconfig["tsite_upload_images_driver_path"] = $tconfig["tpanel_path"] . "webimages/upload/Driver";

$tconfig["tsite_upload_images_driver"] = $tconfig["tsite_url"] . "webimages/upload/Driver";

/* To upload driver documents */

$tconfig["tsite_upload_driver_doc_path"] = $tconfig["tpanel_path"] . "webimages/upload/documents/driver";

$tconfig["tsite_upload_driver_doc"] = $tconfig["tsite_url"] . "webimages/upload/documents/driver";

//Start ::Passenger Profile Image

$tconfig["tsite_upload_images_passenger_path"] = $tconfig["tpanel_path"] . "webimages/upload/Passenger";

$tconfig["tsite_upload_images_passenger"] = $tconfig["tsite_url"] . "webimages/upload/Passenger";

//Start ::Hotel Passenger Profile Image

$tconfig["tsite_upload_images_hotel_passenger_path"] = $tconfig["tpanel_path"] . "webimages/upload/Hotel_Passenger";

$tconfig["tsite_upload_images_hotel_passenger"] = $tconfig["tsite_url"] . "webimages/upload/Hotel_Passenger";

$tconfig["tsite_upload_images_hotel_passenger_size1"] = "64";

$tconfig["tsite_upload_images_hotel_passenger_size2"] = "150";

$tconfig["tsite_upload_images_hotel_passenger_size3"] = "256";

$tconfig["tsite_upload_images_hotel_passenger_size4"] = "512";

$tconfig["tsite_upload_images_hotel_banner_size1"] = "1024";

//Start ::Hotel Banners

$tconfig["tsite_upload_images_hotel_banner_path"] = $tconfig["tpanel_path"] . "webimages/upload/Hotel_Banners";

$tconfig["tsite_upload_images_hotel_banner"] = $tconfig["tsite_url"] . "webimages/upload/Hotel_Banners";

$tconfig["tsite_upload_images_hotel_banner_size1"] = "128";

$tconfig["tsite_upload_images_hotel_banner_size2"] = "256";

$tconfig["tsite_upload_images_hotel_banner_size3"] = "512";

$tconfig["tsite_upload_images_hotel_banner_size4"] = "640";

//Start ::news feed folder

$tconfig["tsite_upload_images_news_feed_path"] = $tconfig["tpanel_path"] . "webimages/upload/newsfeed";

$tconfig["tsite_upload_images_news_feed"] = $tconfig["tsite_url"] . "webimages/upload/newsfeed";

//End ::news feed folder

/* To upload images for static pages */

$tconfig["tsite_upload_page_images"] = $tconfig["tsite_img"] . "/page/";

$tconfig["tsite_upload_page_images_panel"] = $tconfig["tpanel_path"] . "assets/img/page";

/* To upload images for new home pages */

$tconfig["tsite_upload_home_page_images"] = $tconfig["tsite_img"] . "/home-new";

$tconfig["tsite_upload_home_page_images_panel"] = $tconfig["tpanel_path"] . "assets/img/home-new";

// for home page icon

$tconfig["tsite_upload_home_page_service_images"] = $tconfig["tsite_img"] . "/home-new/services";

$tconfig["tsite_upload_home_page_service_images_panel"] = $tconfig["tpanel_path"] . "assets/img/home-new/services";

/* To upload passenger Docunment */

$tconfig["tsite_upload_vehicle_doc"] = $tconfig["tpanel_path"] . "webimages/upload/documents/vehicles";

$tconfig["tsite_upload_vehicle_doc_panel"] = $tconfig["tsite_url"] . "webimages/upload/documents/vehicles/";

/* To upload driver documents */

//$tconfig["tsite_upload_driver_doc"] = $tconfig["tsite_upload_vehicle_doc"]."driver/";

//$tconfig["tsite_upload_driver_doc_panel"] = $tconfig["tsite_upload_vehicle_doc_panel"]."driver/";

/* To upload images for Appscreenshort pages */

$tconfig["tsite_upload_apppage_images"] = $tconfig["tpanel_path"] . "webimages/upload/Appscreens/";

$tconfig["tsite_upload_apppage_images_panel"] = $tconfig["tsite_url"] . "webimages/upload/Appscreens/";

//Start ::Vehicle Type

$tconfig["tsite_upload_images_vehicle_type_path"] = $tconfig["tpanel_path"] . "webimages/icons/VehicleType";

$tconfig["tsite_upload_images_vehicle_type"] = $tconfig["tsite_url"] . "webimages/icons/VehicleType";

$tconfig["tsite_upload_images_vehicle_type_size1_android"] = "60";

$tconfig["tsite_upload_images_vehicle_type_size2_android"] = "90";

$tconfig["tsite_upload_images_vehicle_type_size3_both"] = "120";

$tconfig["tsite_upload_images_vehicle_type_size4_android"] = "180";

$tconfig["tsite_upload_images_vehicle_type_size5_both"] = "240";

$tconfig["tsite_upload_images_vehicle_type_size5_ios"] = "360";

$tconfig["tsite_upload_images_member_size1"] = "64";

$tconfig["tsite_upload_images_member_size2"] = "150";

$tconfig["tsite_upload_images_member_size3"] = "256";

$tconfig["tsite_upload_images_member_size4"] = "512";

//Start ::Vehicle category

$tconfig["tsite_upload_images_vehicle_category_path"] = $tconfig["tpanel_path"] . "webimages/icons/VehicleCategory";

$tconfig["tsite_upload_images_vehicle_category"] = $tconfig["tsite_url"] . "webimages/icons/VehicleCategory";

$tconfig["tsite_upload_images_vehicle_category_size1_android"] = "60";

$tconfig["tsite_upload_images_vehicle_category_size2_android"] = "90";

$tconfig["tsite_upload_images_vehicle_category_size3_both"] = "120";

$tconfig["tsite_upload_images_vehicle_category_size4_android"] = "180";

$tconfig["tsite_upload_images_vehicle_category_size5_both"] = "240";

$tconfig["tsite_upload_images_vehicle_category_size5_ios"] = "360";

/* $tconfig["tsite_upload_images_member_size1"] = "64";

  $tconfig["tsite_upload_images_member_size2"] = "150";

  $tconfig["tsite_upload_images_member_size3"] = "256";

  $tconfig["tsite_upload_images_member_size4"] = "512"; */

/* To upload images for trips */

$tconfig["tsite_upload_trip_images_path"] = $tconfig["tpanel_path"] . "webimages/upload/beforeafter/";

$tconfig["tsite_upload_trip_images"] = $tconfig["tsite_url"] . "webimages/upload/beforeafter/";

/* To upload images for order proof */

$tconfig["tsite_upload_order_images_path"] = $tconfig["tpanel_path"] . "webimages/upload/order_proof/";

$tconfig["tsite_upload_order_images"] = $tconfig["tsite_url"] . "webimages/upload/order_proof/";

/* For Back-up Database */

$tconfig["tsite_upload_files_db_backup_path"] = $tconfig["tpanel_path"] . "webimages/upload/backup/";

$tconfig["tsite_upload_files_db_backup"] = $tconfig["tsite_url"] . "webimages/upload/backup/";

/* To upload preference images */

$tconfig["tsite_upload_preference_image"] = $tconfig["tpanel_path"] . "webimages/upload/preferences/";

$tconfig["tsite_upload_preference_image_panel"] = $tconfig["tsite_url"] . "webimages/upload/preferences/";

/* Home Page Image Size */

$tconfig["tsite_upload_images_home"] = "300";

/* To upload images for trip delivery signatures */

$tconfig["tsite_upload_trip_signature_images_path"] = $tconfig["tpanel_path"] . "webimages/upload/trip_signature/";

$tconfig["tsite_upload_trip_signature_images"] = $tconfig["tsite_url"] . "webimages/upload/trip_signature/";

$tconfig["tsite_upload_docs_file_extensions"] = "pdf,jpg,png,gif,bmp,jpeg,doc,docx,txt,xls,xlxs";

/* To upload images for serive categories */

$tconfig["tsite_upload_service_categories_images_path"] = $tconfig["tpanel_path"] . "webimages/upload/ServiceCategories/";

$tconfig["tsite_upload_service_categories_images"] = $tconfig["tsite_url"] . "webimages/upload/ServiceCategories/";

//Start ::Food Menu

$tconfig["tsite_upload_images_food_menu_path"] = $tconfig["tpanel_path"] . "webimages/upload/FoodMenu";

$tconfig["tsite_upload_images_food_menu"] = $tconfig["tsite_url"] . "webimages/upload/FoodMenu";

$tconfig["tsite_upload_images_food_menu_size1_android"] = "60";

$tconfig["tsite_upload_images_food_menu_size2_android"] = "90";

$tconfig["tsite_upload_images_food_menu_size3_both"] = "120";

$tconfig["tsite_upload_images_food_menu_size4_android"] = "180";

$tconfig["tsite_upload_images_food_menu_size5_both"] = "240";

$tconfig["tsite_upload_images_food_menu_size5_ios"] = "360";

//Start ::Menu Items

$tconfig["tsite_upload_images_menu_item_path"] = $tconfig["tpanel_path"] . "webimages/upload/MenuItem";

$tconfig["tsite_upload_images_menu_item"] = $tconfig["tsite_url"] . "webimages/upload/MenuItem";

$tconfig["tsite_upload_images_menu_item_size1_android"] = "60";

$tconfig["tsite_upload_images_menu_item_size2_android"] = "90";

$tconfig["tsite_upload_images_menu_item_size3_both"] = "120";

$tconfig["tsite_upload_images_menu_item_size4_android"] = "180";

$tconfig["tsite_upload_images_menu_item_size5_both"] = "240";

$tconfig["tsite_upload_images_menu_item_size5_ios"] = "360";

//Start ::Profile Master Icons 

$tconfig["tsite_upload_profile_master_path"] = $tconfig["tpanel_path"] . "webimages/upload/ProfileMaster";

$tconfig["tsite_upload_images_profile_master"] = $tconfig["tsite_url"] . "webimages/upload/ProfileMaster";

$tconfig["tsite_upload_images_profile_master_size1"] = "16";

$tconfig["tsite_upload_images_profile_master_size2"] = "32";

$tconfig["tsite_upload_images_profile_master_size3"] = "48";

$tconfig["tsite_upload_images_profile_master_size4"] = "64";

$tconfig["tsite_upload_advertise_banner_path"] = $tconfig["tpanel_path"] . "webimages/upload/AdvImages"; //Added By HJ On 12-12-2018 For Advertisement Banners Path

$tconfig["tsite_upload_advertise_banner"] = $tconfig["tsite_url"] . "webimages/upload/AdvImages"; //Added By HJ On 12-12-2018 For Advertisement Banners URL

$tconfig["tsite_upload_provider_image_path"] = $tconfig["tpanel_path"] . "webimages/upload/Provider_Images/"; //Added By Hasmukh On 24-01-2019 For Provider Image Path

$tconfig["tsite_upload_provider_image"] = $tconfig["tsite_url"] . "webimages/upload/Provider_Images"; //Added By Hasmukh On 24-01-2019 For Provider Image URL

$tconfig["tsite_upload_prescription_image_path"] = $tconfig["tpanel_path"] . "webimages/upload/Prescription_Images/"; //For Prescription required added by SP

$tconfig["tsite_upload_prescription_image"] = $tconfig["tsite_url"] . "webimages/upload/Prescription_Images"; //For Prescription required added by SP

### ==================================== label configuration =========================================

//store sample image path

$tconfig["tsite_sample_images_store_path"] = $tconfig["tpanel_path"] . "webimages/icons/company_sample_images/";

/* Change appropriate values only. Below settings are related to socket cluster */

if ($_SERVER["HTTP_HOST"] == "192.168.1.131" || $_SERVER["HTTP_HOST"] == "www.mobileappsdemo.com" || $_SERVER["HTTP_HOST"] == "www.webprojectsdemo.com" || $_SERVER["HTTP_HOST"] == "mobileappsdemo.com" || $_SERVER["HTTP_HOST"] == "webprojectsdemo.com" || $_SERVER["HTTP_HOST"] == "192.168.1.141"  || $_SERVER["HTTP_HOST"] == "mobileappsdemo.net" || $_SERVER["HTTP_HOST"] == "www.mobileappsdemo.net" ||  strpos($_SERVER["HTTP_HOST"], 'bbcsproducts.com') !== false) {

    $tconfig["tsite_sc_protocol"] = "http://"; // Protocol to access Socket Cluster.

    $tconfig["tsite_sc_host"] = "23.235.222.250"; // In which socket cluster is installed.

    $tconfig["tsite_host_sc_port"] = "1604"; // In which socket cluster is running on.

    $tconfig["tsite_host_sc_path"] = "/socketcluster/"; // This path should not change.

    /* Yalgaar settings url */

    $tconfig["tsite_yalgaar_url"] = "http://142.93.244.42:8081";

    /* Yalgaar settings url */

} else {

    $tconfig["tsite_sc_protocol"] = "http://"; // Protocol to access Socket Cluster.

    $tconfig["tsite_sc_host"] = "23.235.222.250"; // In which socket cluster is installed.

    $tconfig["tsite_host_sc_port"] = "1604"; // In which socket cluster is running on.

    $tconfig["tsite_host_sc_path"] = "/socketcluster/"; // This path should not change.

    /* Yalgaar settings url */

    $tconfig["tsite_yalgaar_url"] = "http://" . $_SERVER['SERVER_ADDR'] . ":8081";

    /* Yalgaar settings url */

}

/* Socket cluster settings are finished. For any new settings related to socket cluster should be declare above this line. */

/* Change appropriate values only. Below settings are related to MongoDB */

$tconfig["tmongodb_port"] = "27017";

$tconfig["tmongodb_databse"] = TSITE_DB;

/* Settings related to MongoDB is finished */

define('ENABLE_RENTAL_OPTION', 'Yes');

define('ENABLE_MULTI_DELIVERY', 'Yes');

/* To add enable deliveryall portion in cubejek */

/* old define('DELIVERALL','Yes'); */

define('DELIVERALL', isset($_REQUEST['DELIVERALL']) && !empty($_REQUEST['DELIVERALL']) ? $_REQUEST['DELIVERALL'] : 'Yes');

/* To add enable only deliveryall portion and hide all portion in cubejek */

/* old define('ONLYDELIVERALL','No'); */

define('ONLYDELIVERALL', isset($_REQUEST['ONLYDELIVERALL']) && !empty($_REQUEST['ONLYDELIVERALL']) ? $_REQUEST['ONLYDELIVERALL'] : 'No');

// for enable hotel panel in web

define('ENABLEHOTELPANEL', 'Yes');

define('HotelAPIUrl', 'webservice_shark.php');

// for enable kiosk

define('ENABLEKIOSKPANEL', 'Yes');

define('ManualBookingAPIUrl', 'webservice_shark.php');

///Added By HJ On 10-08-2019 For Define URL name For Login and Sign Up Of Front Panel Start

$cjSignIn = "cj-sign-in";

$cjSignUp = "cj-SignUp";

$cjProviderLogin = "cj-provider-login";

$cjDriverLogin = "cj-driver-login";

$cjUserLogin = "cj-user-login";

$cjRiderLogin = "cj-rider-login";

$cjCompanyLogin = "cj-company-login";

$cjOrganizationLogin = "cj-organization-login";

$cjSignUpUser = "cj-sign-up-user";

$cjSignUpRider = "cj-sign-up-rider";

$cjSignupCompany = "cj-sign-up";

$cjSignupRestaurant = "cj-sign-up-restaurant";

$cjSignupOrganization = "cj-sign-up-organization";

///Added By HJ On 10-08-2019 For Define URL name For Login and Sign Up Of Front Panel End

$tconfig["tsite_upload_apptype_page_images"] = $tconfig["tsite_img"] . "/page/home/apptype/";

$tconfig["tsite_upload_apptype_page_images_panel"] = $tconfig["tpanel_path"] . "assets/img/page/home/apptype/";

?>