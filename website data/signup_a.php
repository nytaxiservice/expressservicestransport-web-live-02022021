<?php

include_once("common.php");
include_once ('app_common_functions.php');
//$POST_CAPTCHA = $_POST['POST_CAPTCHA'];
//$SESS_CAPTCHA = $_SESSION['SESS_CAPTCHA'];
//echo "sign-up-a";exit;
//echo "<pre>";print_r($_POST);exit;
//if ($POST_CAPTCHA == $SESS_CAPTCHA) {
if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
    $valiedRecaptch = $generalobj->checkRecaptchValied($GOOGLE_CAPTCHA_SECRET_KEY, $_POST['g-recaptcha-response']);
    //echo $valiedRecaptch;exit;
    if ($valiedRecaptch) {
        if ($_POST) {
            $user_type = $_POST['user_type'];
            if ($user_type == 'driver') {
                $table_name = "register_driver";
                $msg = $generalobj->checkDuplicateFront('vEmail', 'register_driver', Array('vEmail'), $tconfig["tsite_url"] . "sign-up.php?error=1&var_msg=Email already Exists", "Email already Exists", "", "");
            }
            /* Use For Organization Module */ else if ($user_type == 'organization') {
                $table_name = "company";
                $msg = $generalobj->checkDuplicateFront('vEmail', 'company', Array('vEmail'), $tconfig["tsite_url"] . "sign-up-organization.php?error=1&var_msg=Email already Exists", "Email already Exists", "", "");
            }

            /* Use For Organization Module */ else {
                $table_name = "company";
                $msg = $generalobj->checkDuplicateFront('vEmail', 'company', Array('vEmail'), $tconfig["tsite_url"] . "sign-up.php?error=1&var_msg=Email already Exists", "Email already Exists", "", "");
            }
            $eSystem = "";
            $checPhoneExist = $generalobj->checkMemberDataInfo($_POST['vPhone'],"",$user_type,$_POST['vCountry'],"",$eSystem);
            //echo "<pre>";
            //print_r($checPhoneExist);exit;
            if($checPhoneExist['status'] == 0){
                $_SESSION['postDetail'] = $_REQUEST;
                header("Location:" . $tconfig["tsite_url"] . "sign-up.php?error=1&var_msg=Phone number already exist.");
                exit;
            }else if($checPhoneExist['status'] == 2){
                header("Location:" . $tconfig["tsite_url"] . "sign-up.php?error=1&var_msg=".$langage_lbl['LBL_INVALID_MEMBER_USER_COUNTRY_EMAIL_TXT']);
                exit;
            }

            if ($user_type == 'driver') {
                $eReftype = "Driver";
                $Data['vRefCode'] = $generalobj->ganaraterefercode($eReftype);
                $Data['iRefUserId'] = $_POST['iRefUserId'];
                $Data['eRefType'] = $_POST['eRefType'];
                $Data['dRefDate'] = Date('Y-m-d H:i:s');
            }

            $Data['vName'] = $_POST['vFirstName'];
            $Data['vLastName'] = $_POST['vLastName'];
            $Data['vLang'] = $_SESSION['sess_lang'];
            $Data['vPassword'] = $generalobj->encrypt_bycrypt($_REQUEST['vPassword']);
            $Data['vEmail'] = $_POST['vEmail'];
            //$Data['dBirthDate'] = $_POST['vYear'].'-'.$_POST['vMonth'].'-'.$_POST['vDay'];
            $Data['vPhone'] = $_POST['vPhone'];
            $Data['vCaddress'] = $_POST['vCaddress'];
            $Data['vCadress2'] = $_POST['vCadress2'];
            $Data['vCity'] = $_POST['vCity'];
            $Data['vCountry'] = $_POST['vCountry'];
            $Data['vState'] = $_POST['vState'];
            $Data['vZip'] = $_POST['vZip'];
            $Data['vCode'] = $_POST['vCode'];
            $Data['vBackCheck'] = $_POST['vBackCheck'];
            $Data['vInviteCode'] = $_POST['vInviteCode'];
            $Data['vFathersName'] = $_POST['vFather'];
            $Data['vCompany'] = $_POST['vCompany'];
            $Data['tRegistrationDate'] = Date('Y-m-d H:i:s');

            $csql = "SELECT eZeroAllowed,vCountryCode FROM `country` WHERE vPhoneCode = '" . $_POST['vCode'] . "'";
            $CountryData = $obj->MySQLSelect($csql);
            $eZeroAllowed = $CountryData[0]['eZeroAllowed'];

            if ($eZeroAllowed == 'Yes') {
                $Data['vPhone'] = $Data['vPhone'];
            } else {
                $first = substr($Data['vPhone'], 0, 1);

                if ($first == "0") {
                    $Data['vPhone'] = substr($Data['vPhone'], 1);
                }
            }
            if (SITE_TYPE == 'Demo') {
                $Data['eStatus'] = 'Active';
                //Added By HJ On 17-07-2019 For Auto Verify Email and Phone Driver When Register Start
                $Data['eEmailVerified'] = $Data['ePhoneVerified'] = 'Yes';
                //Added By HJ On 17-07-2019 For Auto Verify Email and Phone Driver When Register End
                //Added By HJ On 31-07-2019 For Enable Service At Location Feature Start
                if (strtolower($user_type) == 'driver') {
                    $Data['eEnableServiceAtLocation'] = 'Yes';
                }
                //Added By HJ On 31-07-2019 For Enable Service At Location Feature End
            }

            if ($user_type == 'driver') {
                $Data['eDestinationMode'] = 'No';
                $Data['iDestinationCount'] = 0;
                $Data['tDestinationModifiedDate'] = date('Y-m-d H:i:s');
                $table = 'register_driver';
                $Data['vCurrencyDriver'] = $_POST['vCurrencyDriver'];
                $Data['eGender'] = $_POST['eGender'];
                $user_type = 'driver';
                $Data['iCompanyId'] = 1;
            } else {
                $table = 'company';
                $Data['eSystem'] = ($user_type == 'organization') ? 'Organization' : 'General';  /* Use For Company & Organization */
                //$user_type='company';
                $Data['vVat'] = $_POST['vVat'];
            }
            $eSystem = "";
            $checkValid = $generalobj->checkMemberDataInfo($_POST['vEmail'], "", $user_type,$_POST['vCountry'],"",$eSystem);
            if ($checkValid['status'] == 1) {
                $id = $obj->MySQLQueryPerform($table, $Data, 'insert');
                //Added By HJ On 27-07-2019 For Add Money Into Wallet When Register Driver In Demo Mode Start - Discuss With CD and KS Sir
                if ($user_type == 'driver' && SITE_TYPE == 'Demo') {
                    $tDescription = '#LBL_AMOUNT_CREDIT#';
                    $ePaymentStatus = 'Unsettelled';
                    $dDate = Date('Y-m-d H:i:s');
                    $generalobj->InsertIntoUserWallet($id, "Driver", 500, 'Credit', 0, "Deposit", $tDescription, $ePaymentStatus, $dDate);
                }
                //Added By HJ On 27-07-2019 For Add Money Into Wallet When Register Driver In Demo Mode End - Discuss With CD and KS Sir
                if ($SITE_VERSION == "v5" && $user_type == 'driver') {
                    $set_driver_pref = $generalobj->Insert_Default_Preferences($id);
                }

               
                if (($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX') && ONLYDELIVERALL == "No") {
                    if ($user_type == 'driver') {
                        $query = "SELECT GROUP_CONCAT(iVehicleTypeId) as countId FROM `vehicle_type` WHERE `eType` = 'UberX'";
                        $result = $obj->MySQLSelect($query);

                        $Drive_vehicle['iDriverId'] = $id;
                        $Drive_vehicle['iCompanyId'] = "1";
                        $Drive_vehicle['iMakeId'] = "3";
                        $Drive_vehicle['iModelId'] = "1";
                        $Drive_vehicle['iYear'] = Date('Y');
                        $Drive_vehicle['vLicencePlate'] = "My Services";
                        $Drive_vehicle['eStatus'] = "Active";
                        $Drive_vehicle['eType'] = "UberX";
                        $Drive_vehicle['eCarX'] = "Yes";
                        $Drive_vehicle['eCarGo'] = "Yes";
                        if (SITE_TYPE == 'Demo') {
                            $Drive_vehicle['vCarType'] = $result[0]['countId'];
                        } else {
                            $Drive_vehicle['vCarType'] = "";
                        }
                        //$Drive_vehicle['vCarType'] = $result[0]['countId'];
                        $iDriver_VehicleId = $obj->MySQLQueryPerform('driver_vehicle', $Drive_vehicle, 'insert');

                        if ($APP_TYPE == 'UberX') {
                            $sql = "UPDATE register_driver set iDriverVehicleId='" . $iDriver_VehicleId . "' WHERE iDriverId='" . $id . "'";
                            $obj->sql_query($sql);
                        }

                        if ($APP_TYPE == 'Ride-Delivery-UberX') {
                            if (SITE_TYPE == 'Demo') {
                                $query = "SELECT GROUP_CONCAT(iVehicleTypeId)as countId FROM `vehicle_type` WHERE (`eType` = 'Ride' OR `eType` = 'Deliver')";
                                $result = $obj->MySQLSelect($query);

                                $query1 = "SELECT GROUP_CONCAT(iVehicleTypeId)as countRentalId FROM `vehicle_type` WHERE `eType` = 'Ride'";
                                $resultrental = $obj->MySQLSelect($query1);

                                $Drive_vehicle_Ride['iDriverId'] = $id;
                                $Drive_vehicle_Ride['iCompanyId'] = "1";
                                $Drive_vehicle_Ride['iMakeId'] = "5";
                                $Drive_vehicle_Ride['iModelId'] = "18";
                                $Drive_vehicle_Ride['iYear'] = "2014";
                                $Drive_vehicle_Ride['vLicencePlate'] = "CK201";
                                $Drive_vehicle_Ride['eStatus'] = "Active";
                                $Drive_vehicle_Ride['eCarX'] = "Yes";
                                $Drive_vehicle_Ride['eCarGo'] = "Yes";
                                $Drive_vehicle_Ride['eType'] = "Ride";
                                $Drive_vehicle_Ride['vCarType'] = $result[0]['countId'];
                                if (ENABLE_RENTAL_OPTION == 'Yes') {
                                    $Drive_vehicle_Ride['vRentalCarType'] = $resultrental[0]['countRentalId'];
                                }
                                $iDriver_VehicleId = $obj->MySQLQueryPerform('driver_vehicle', $Drive_vehicle_Ride, 'insert');

                                $sql = "UPDATE register_driver set iDriverVehicleId='" . $iDriver_VehicleId . "' WHERE iDriverId='" . $id . "'";
                                $obj->sql_query($sql);
                                //$query = "SELECT GROUP_CONCAT(iVehicleTypeId)as countId FROM `vehicle_type` WHERE (`eType` = 'Ride' OR `eType` = 'Deliver')"; //Commented By HJ For Add New Condition In Below Query
                                $query = "SELECT GROUP_CONCAT(iVehicleTypeId)as countId FROM `vehicle_type` WHERE eType = 'Ride' OR eType = 'Deliver' OR eType = 'DeliverAll'"; //Added By HJ For Add New Condition (OR eType = 'DeliverAll') In Query Discuss With KS Sir
                                $result = $obj->MySQLSelect($query);
                                $Drive_vehicle_Deliver['iDriverId'] = $id;
                                $Drive_vehicle_Deliver['iCompanyId'] = "1";
                                $Drive_vehicle_Deliver['iMakeId'] = "5";
                                $Drive_vehicle_Deliver['iModelId'] = "18";
                                $Drive_vehicle_Deliver['iYear'] = "2014";
                                $Drive_vehicle_Deliver['vLicencePlate'] = "CK201";
                                $Drive_vehicle_Deliver['eStatus'] = "Active";
                                $Drive_vehicle_Deliver['eCarX'] = "Yes";
                                $Drive_vehicle_Deliver['eCarGo'] = "Yes";
                                $Drive_vehicle_Deliver['eType'] = "Delivery";
                                $Drive_vehicle_Deliver['vCarType'] = $result[0]['countId'];
                                $Drive_vehicle_Deliver['vRentalCarType'] = $result[0]['countId'];
                                $iDriver_VehicleId = $obj->MySQLQueryPerform('driver_vehicle', $Drive_vehicle_Deliver, 'insert');
                            }
                        }
                    }
                } else {
                    if (SITE_TYPE == 'Demo') {
                        if ($APP_TYPE == 'Delivery') {
                            $app_type = 'Deliver';
                        } else {
                            $app_type = $APP_TYPE;
                        }
                        if ($app_type == 'Ride-Delivery') {
                            $query = "SELECT GROUP_CONCAT(iVehicleTypeId)as countId FROM `vehicle_type` WHERE (`eType` = 'Ride' OR `eType` = 'Deliver')";

                            $query1 = "SELECT GROUP_CONCAT(iVehicleTypeId)as RentalcountId FROM `vehicle_type` WHERE (`eType` = 'Ride')";
                            $resultReantal = $obj->MySQLSelect($query1);
                        } else {
                            $query = "SELECT GROUP_CONCAT(iVehicleTypeId)as countId FROM `vehicle_type`  WHERE `eType` = '" . $app_type . "'";
                        }
                        $result = $obj->MySQLSelect($query);
                        /* 	$query ="SELECT GROUP_CONCAT(iVehicleTypeId)as countId FROM `vehicle_type`  WHERE `eType` = '".$app_type ."'";
                          $result = $obj->MySQLSelect($query); */
                        $Drive_vehicle['iDriverId'] = $id;
                        $Drive_vehicle['iCompanyId'] = "1";
                        $Drive_vehicle['iMakeId'] = "5";
                        $Drive_vehicle['iModelId'] = "18";
                        $Drive_vehicle['iYear'] = "2014";
                        $Drive_vehicle['vLicencePlate'] = "CK201";
                        $Drive_vehicle['eStatus'] = "Active";
                        $Drive_vehicle['eCarX'] = "Yes";
                        $Drive_vehicle['eCarGo'] = "Yes";
                        $Drive_vehicle['eType'] = $app_type;
                        $Drive_vehicle['vCarType'] = $result[0]['countId'];

                        if (($app_type == 'Ride' || $app_type == 'Ride-Delivery') && ENABLE_RENTAL_OPTION == 'Yes') {
                            $Drive_vehicle['vRentalCarType'] = $resultReantal[0]['RentalcountId'];
                        }

                        $iDriver_VehicleId = $obj->MySQLQueryPerform('driver_vehicle', $Drive_vehicle, 'insert');
                        $sql = "UPDATE register_driver set iDriverVehicleId='" . $iDriver_VehicleId . "' WHERE iDriverId='" . $id . "'";
                        $obj->sql_query($sql);
                    }
                }

                if ($id != "") {
                    $_SESSION['sess_iUserId'] = $id;
                    if ($user_type == 'driver') {
                        $_SESSION['sess_iCompanyId'] = 1;
                        $_SESSION["sess_vName"] = $Data['vName'] . ' ' . $Data['vLastName'];
                        $_SESSION["sess_vCurrency"] = $Data['vCurrencyDriver'];
                    } else {
                        $_SESSION['sess_iCompanyId'] = $id;
                        $_SESSION["sess_vName"] = $Data['vCompany'];
                        $_SESSION["eSystem"] = $Data['eSystem'];
                    }

                    $_SESSION["sess_company"] = $Data['vCompany'];
                    $_SESSION["sess_vEmail"] = $Data['vEmail'];
                    $_SESSION["sess_user"] = $user_type;
                    $_SESSION["sess_new"] = 1;

                    $maildata['EMAIL'] = $_SESSION["sess_vEmail"];
                    $maildata['NAME'] = $_SESSION["sess_vName"];
                    //$maildata['PASSWORD'] = $langage_lbl['LBL_PASSWORD'].": ". $_REQUEST['vPassword']; //Commented By HJ On 11-01-2019 For Hide Password As Per Discuss With QA BM
                    $maildata['SOCIALNOTES'] = '';
                    //$generalobj->send_email_user("MEMBER_REGISTRATION_USER",$maildata);
                    if ($user_type == 'driver') {
                        $generalobj->send_email_user("DRIVER_REGISTRATION_ADMIN", $maildata);
                        $generalobj->send_email_user("DRIVER_REGISTRATION_USER", $maildata);
                    } else {
                        $generalobj->send_email_user("COMPANY_REGISTRATION_ADMIN", $maildata);
                        $generalobj->send_email_user("COMPANY_REGISTRATION_USER", $maildata);
                    }
                    #header("Location:profile.php?first=yes");
                    //User login log added by Rs start
                    if($user_type == 'driver')
                        $generalobj->createUserLog('Driver', 'Yes', $id, 'Web');
                    else if($user_type == 'organization')
                        $generalobj->createUserLog('Organization', 'Yes', $id, 'Web');
                    else
                        $generalobj->createUserLog('Company', 'Yes', $id, 'Web');
                    //User login log added by Rs End
                    
                    if ($APP_TYPE == 'UberX' && $user_type == 'driver') {
                        header("Location:add_services.php?iDriverId=" . base64_encode(base64_encode($_SESSION['sess_iUserId'])));
                        exit;
                    } else {
                        header("Location:profile.php?first=yes");
                        exit;
                    }
                }
            } else if($checkValid['status'] == 2) {
                $_SESSION['postDetail'] = $_REQUEST;
                header("Location:" . $tconfig["tsite_url"] . "sign-up.php?error=1&var_msg=var_msg=".$langage_lbl['LBL_INVALID_MEMBER_USER_COUNTRY_EMAIL_TXT']);
                exit;
            }
        }
    } else {
        $_SESSION['postDetail'] = $_REQUEST;
        header("Location:" . $tconfig["tsite_url"] . "sign-up.php?error=1&var_msg=".$langage_lbl['LBL_CAPTCHA_MATCH_MSG']);
        exit;
    }
} else {
    $_SESSION['postDetail'] = $_REQUEST;
    header("Location:" . $tconfig["tsite_url"] . "sign-up.php?error=1&var_msg=Please check reCAPTCHA box.");
    exit;
}
/* } else {
  $_SESSION['postDetail'] = $_REQUEST;
  header("Location:" . $tconfig["tsite_url"] . "sign-up.php?error=1&var_msg=Captcha did not match.");
  exit;
  } */
?>
