<?php

include_once('common.php');
$email = isset($_POST['femail']) ? $_POST['femail'] : '';
$action = isset($_POST['action']) ? $_POST['action'] : '';
$iscompany = isset($_POST['iscompany']) ? $_POST['iscompany'] : '0';

if ($action == 'driver') {
    if ($iscompany == '1') {
        $sql = "SELECT * from company where vEmail = '" . $email . "' and eStatus != 'Deleted'";
        $db_login = $obj->MySQLSelect($sql);
        if (count($db_login) > 0) {
            if (SITE_TYPE != 'Demo') {
                $milliseconds = time();
                $tempGenrateCode = substr($milliseconds, 1);
                //$url = $tconfig["tsite_url"].'reset_password.php?type='.$action.'&generatepsw='.$tempGenrateCode;
                $Today = Date('Y-m-d');
                $type = base64_encode(base64_encode('company'));
                $id = $generalobj->encrypt($db_login[0]["iCompanyId"]);
                $today = base64_encode(base64_encode($Today));
                $newToken = $generalobj->RandomString(32);
                $url = $tconfig["tsite_url"] . 'reset_password.php?type=' . $type . '&id=' . $id . '&_token=' . $newToken;
                $maildata['EMAIL'] = $db_login[0]["vEmail"];
                $maildata['NAME'] = $db_login[0]["vCompany"];
                $maildata['LINK'] = '<a href="' . $url . '" target="_blank">Clicking here</a>';
                $status = $generalobj->send_email_user("CUSTOMER_RESET_PASSWORD", $maildata);
            } else {
                $status = 1;
            }

            if ($status == 1) {
                $sql = "UPDATE company set vPassword_token='" . $newToken . "' WHERE vEmail='" . $email . "' and eStatus != 'Deleted'";
                $obj->sql_query($sql);

                $var_msg = $langage_lbl['LBL_PASSWORD_SENT_TXT'];
                $error_msg = "1";
            } else {
                $var_msg = $langage_lbl['LBL_ERROR_PASSWORD_MAIL'];
                $error_msg = "0";
            }
        } else {
            $var_msg = $langage_lbl['LBL_EMAIL_NOT_FOUND'];
            $error_msg = "0";
        }
    } else {
        $sql = "SELECT * from register_driver where vEmail = '" . $email . "' and eStatus != 'Deleted'";
        $db_login = $obj->MySQLSelect($sql);
        if (count($db_login) > 0) {
            if (SITE_TYPE != 'Demo') {

                $tempGenrateCode = substr($milliseconds, 1);
                $Today = Date('Y-m-d H:i:s');
                $type = base64_encode(base64_encode($action));
                $newToken = $generalobj->RandomString(32);
                $id = $generalobj->encrypt($db_login[0]["iDriverId"]);
                $today = base64_encode(base64_encode($Today));
                $url = $tconfig["tsite_url"] . 'reset_password.php?type=' . $type . '&id=' . $id . '&_token=' . $newToken;

                $maildata['EMAIL'] = $db_login[0]["vEmail"];
                $maildata['NAME'] = $db_login[0]["vName"] . " " . $db_login[0]["vLastName"];
                $maildata['LINK'] = '<a href="' . $url . '" target="_blank">Clicking here</a>';

                $status = $generalobj->send_email_user("CUSTOMER_RESET_PASSWORD", $maildata);
            } else {
                $status = 1;
            }
            //echo $status;exit;
            if ($status == 1) {
                $sql = "UPDATE register_driver set vPassword_token='" . $newToken . "' WHERE vEmail='" . $email . "' and eStatus != 'Deleted'";
                $obj->sql_query($sql);

                $var_msg = $langage_lbl['LBL_PASSWORD_SENT_TXT'];
                $error_msg = "1";
            } else {
                $var_msg = $langage_lbl['LBL_ERROR_PASSWORD_MAIL'];
                $error_msg = "0";
            }
        } else {
            $var_msg = $langage_lbl['LBL_EMAIL_NOT_FOUND'];
            $error_msg = "0";
        }
    }
    //echo $error_msg;
}
if ($action == 'rider') {
    $sql = "SELECT * from register_user where vEmail = '" . $email . "' and eStatus != 'Deleted'";
    $db_login = $obj->MySQLSelect($sql);
    if (count($db_login) > 0) {
        if (SITE_TYPE != 'Demo') {
            $milliseconds = time();
            $id = $generalobj->encrypt($db_login[0]["iUserId"]);
            $tempGenrateCode = substr($milliseconds, 1);
            $newToken = $generalobj->RandomString(32);
            $type = base64_encode(base64_encode($action));
            $url = $tconfig["tsite_url"] . 'reset_password.php?type=' . $type . '&id=' . $id . '&_token=' . $newToken;
            $maildata['EMAIL'] = $db_login[0]["vEmail"];
            $maildata['NAME'] = $db_login[0]["vName"] . " " . $db_login[0]["vLastName"];
            $maildata['LINK'] = '<a href="' . $url . '" target="_blank">Clicking here</a>';

            $status = $generalobj->send_email_user("CUSTOMER_RESET_PASSWORD", $maildata);
        } else {
            $status = 1;
        }
        if ($status == 1) {

            $sql = "UPDATE register_user set vPassword_token='" . $newToken . "' WHERE vEmail='" . $email . "' and eStatus != 'Deleted'";
            $obj->sql_query($sql);

            $var_msg = $langage_lbl['LBL_PASSWORD_SENT_TXT'];
            $error_msg = "1";
        } else {
            $var_msg = $langage_lbl['LBL_ERROR_PASSWORD_MAIL'];
            $error_msg = "0";
        }
    } else {
        $var_msg = $langage_lbl['LBL_EMAIL_NOT_FOUND'];
        $error_msg = "3";
    }
}
if ($action == 'changecurrency') {
    $currency = $_POST['q'];
    $tbl = 'register_user';
    unset($_SESSION['sess_currency']);
    $_SESSION['sess_currency'] = $currency;
    if (isset($_SESSION['sess_user'])) {
        $iUserId = $_SESSION['sess_iUserId'];
        $where = " WHERE `iUserId` = '" . $iUserId . "'";
        $query = "UPDATE  `" . $tbl . "` SET `vCurrencyPassenger`='" . $currency . "'" . $where; //exit;
        $obj->sql_query($query);
    }
    $var_msg = $langage_lbl['LBL_PROFILE_UPDATE_SUCCESS'];
    $error_msg = "1";
}

/* Use For Organization Module */

if ($action == 'organization') {
    $sql = "SELECT * from organization where vEmail = '" . $email . "' and eStatus != 'Deleted'";
    $db_login = $obj->MySQLSelect($sql);
    $var_msg = $langage_lbl['LBL_EMAIL_NOT_FOUND'];
    $error_msg = "0";
    if (count($db_login) > 0) {
        if (SITE_TYPE != 'Demo') {
            $milliseconds = time();
            $tempGenrateCode = substr($milliseconds, 1);
            //$url = $tconfig["tsite_url"].'reset_password.php?type='.$action.'&generatepsw='.$tempGenrateCode;
            $Today = Date('Y-m-d');
            $type = base64_encode(base64_encode('organization'));
            $id = $generalobj->encrypt($db_login[0]["iOrganizationId"]);
            $today = base64_encode(base64_encode($Today));
            $newToken = $generalobj->RandomString(32);
            $url = $tconfig["tsite_url"] . 'reset_password.php?type=' . $type . '&id=' . $id . '&_token=' . $newToken;
            $maildata['EMAIL'] = $db_login[0]["vEmail"];
            $maildata['NAME'] = $db_login[0]["vCompany"];
            $maildata['LINK'] = '<a href="' . $url . '" target="_blank">Clicking here</a>';
            $status = $generalobj->send_email_user("CUSTOMER_RESET_PASSWORD", $maildata);
        } else {
            $status = 1;
        }
        if ($status == 1) {
            $sql = "UPDATE organization set vPassword_token='" . $newToken . "' WHERE vEmail='" . $email . "' and eStatus != 'Deleted'";
            $obj->sql_query($sql);

            $var_msg = $langage_lbl['LBL_PASSWORD_SENT_TXT'];
            $error_msg = "1";
        } else {
            $var_msg = $langage_lbl['LBL_ERROR_PASSWORD_MAIL'];
            $error_msg = "0";
        }
    }
}

/* Use For Organization Module */


$data['msg'] = $var_msg;
$data['status'] = $error_msg;
echo json_encode($data);
?>