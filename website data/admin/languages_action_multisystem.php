<?
include_once('../common.php');
if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
//$generalobjAdmin->check_member_login();
$script = 'language_label';

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$lp_name = isset($_REQUEST['lp_name']) ? $_REQUEST['lp_name'] : '';
$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
$var_msg = isset($_REQUEST['var_msg']) ? $_REQUEST['var_msg'] : '';
$action = ($id != '') ? 'Edit' : 'Add';

$tbl_name = 'language_label';
$backlink = isset($_POST['backlink']) ? $_POST['backlink'] : '';
$previousLink = isset($_POST['backlink']) ? $_POST['backlink'] : '';


// set all variables with either post (when submit) either blank (when insert)
$vLabel = isset($_POST['vLabel']) ? $_POST['vLabel'] : $id;
$lPage_id = isset($_POST['lPage_id']) ? $_POST['lPage_id'] : '';
$eAppType = isset($_POST['eAppType']) ? $_POST['eAppType'] : '';

$vValue_cubejek = isset($_POST['vValue_cubejek']) ? $_POST['vValue_cubejek'] : '';
$vValue_ride_delivery_uberx = isset($_POST['vValue_ride_delivery_uberx']) ? $_POST['vValue_ride_delivery_uberx'] : '';
$vValue_ride = isset($_POST['vValue_ride']) ? $_POST['vValue_ride'] : '';
$vValue_delivery = isset($_POST['vValue_delivery']) ? $_POST['vValue_delivery'] : '';
$vValue_uberx = isset($_POST['vValue_uberx']) ? $_POST['vValue_uberx'] : '';
$vValue_ride_delivery = isset($_POST['vValue_ride_delivery']) ? $_POST['vValue_ride_delivery'] : '';
$vValue_cubejekNew = isset($_POST['vValue_cubejekNew']) ? $_POST['vValue_cubejekNew'] : '';
$vValue_cubejekShark = isset($_POST['vValue_cubejekShark']) ? $_POST['vValue_cubejekShark'] : '';

$vValue_deliverall = isset($_POST['vValue_deliverall']) ? $_POST['vValue_deliverall'] : '';
$vValue_food = isset($_POST['vValue_food']) ? $_POST['vValue_food'] : '';
############################ cubejek5 #####################################
define('TSITE_SERVER5', 'localhost');
define('TSITE_DB5', 'cubejek');
define('TSITE_USERNAME5', 'root');
define('TSITE_PASS5', 'root');

/* ############################### Ride ###################################### */
define('TSITE_SERVER1', '192.168.1.141');
define('TSITE_DB1', 'master_taxi');
define('TSITE_USERNAME1', 'dbuser');
define('TSITE_PASS1', 'Admin@205');

############################### Delivery ######################################
define('TSITE_SERVER2', '192.168.1.141');
define('TSITE_DB2', 'master_delivery');
define('TSITE_USERNAME2', 'dbuser');
define('TSITE_PASS2', 'Admin@205');

############################### uberx ######################################
define('TSITE_SERVER3', '192.168.1.141');
define('TSITE_DB3', 'master_ufx');
define('TSITE_USERNAME3', 'dbuser');
define('TSITE_PASS3', 'Admin@205');

#################################### ride-delivery ################################################
define('TSITE_SERVER4', '192.168.1.141');
define('TSITE_DB4', 'master_taxi_delivery');
define('TSITE_USERNAME4', 'dbuser');
define('TSITE_PASS4', 'Admin@205');

#################################### only CubejekNew ################################################
define('TSITE_SERVER8', '192.168.1.141');
define('TSITE_DB8', 'master_cubejek');
define('TSITE_USERNAME8', 'dbuser');
define('TSITE_PASS8', 'Admin@205');

#################################### FoodNew ################################################
define('TSITE_SERVER6', '192.168.1.141');
define('TSITE_DB6', 'master_food');
define('TSITE_USERNAME6', 'dbuser');
define('TSITE_PASS6', 'Admin@205');


#################################### DeliverAll NEw ################################################
define('TSITE_SERVER7', '192.168.1.141');
define('TSITE_DB7', 'master_DeliverAll');
define('TSITE_USERNAME7', 'dbuser');
define('TSITE_PASS7', 'Admin@205');

#################################################################################################
#################################### DeliverAll NEw ################################################
define('TSITE_SERVER9', 'localhost');
define('TSITE_DB9', 'cubejekshark');
define('TSITE_USERNAME9', 'root');
define('TSITE_PASS9', 'root');

#################################################################################################

if (isset($_POST['submit'])) {

    if ($id == '') {
        $sql = "SELECT * FROM `language_label` WHERE vLabel = '" . $vLabel . "'";
        $db_label_check = $obj->MySQLSelect($sql);
        if (count($db_label_check) > 0) {
            $var_msg = "Language label already exists in general label";
            header("Location:languages_action_multisystem.php?var_msg=" . $var_msg . '&success=0');
            exit;
        }

        $sql = "SELECT * FROM `language_label_other` WHERE vLabel = '" . $vLabel . "'";
        $db_label_check_ride = $obj->MySQLSelect($sql);
        if (count($db_label_check_ride) > 0) {
            $var_msg = "Language label already exists in ride label";
            header("Location:languages_action_multisystem.php?var_msg=" . $var_msg . '&success=0');
            exit;
        }
    }

    $q = "INSERT INTO ";
    $where = '';

    if ($id != '') {
        $q = "UPDATE ";
        $sql = "SELECT vLabel FROM " . $tbl_name . " WHERE LanguageLabelId = '" . $id . "'";
        $db_data = $obj->MySQLSelect($sql);

        $sql = "SELECT * FROM " . $tbl_name . " WHERE vLabel = '" . $db_data[0]['vLabel'] . "'";
        $db_data = $obj->MySQLSelect($sql);

        $vLabel = $db_data[0]['vLabel'];
        $where = " WHERE `vLabel` = '" . $vLabel . "' AND vCode = 'EN'";
    }

    /* ###############################################cubejekdev######################################### */
    $query = $q . " `" . $tbl_name . "` SET
		`vLabel` = '" . $vLabel . "',
		`eAppType` = '" . $eAppType . "',
		`lPage_id` = '" . $lPage_id . "',
		`vCode` = 'EN',
		`vValue` = '" . $vValue_ride_delivery_uberx . "'"
            . $where;

    $obj->sql_query($query);

    $query = "UPDATE register_driver SET eChangeLang = 'Yes' WHERE 1=1";
    $obj->sql_query($query);

    $query1 = "UPDATE register_user SET eChangeLang = 'Yes' WHERE 1=1";
    $obj->sql_query($query1);

    $obj->MySQLClose();
    /* #############################################cubejek########################################### */
    if (!isset($obj5)) {
        $obj5 = new DBConnection(TSITE_SERVER5, TSITE_DB5, TSITE_USERNAME5, TSITE_PASS5);
    }
    $query5 = $q . " `" . $tbl_name . "` SET
		`vLabel` = '" . $vLabel . "',
		`eAppType` = '" . $eAppType . "',
		`lPage_id` = '" . $lPage_id . "',
		`vCode` = 'EN',
		`vValue` = '" . $vValue_cubejek . "'"
            . $where;

    $obj5->sql_query($query5);
    $obj5->MySQLClose();
    /* #############################################Ride########################################### */
    if (!isset($obj1)) {
        $obj1 = new DBConnection(TSITE_SERVER1, TSITE_DB1, TSITE_USERNAME1, TSITE_PASS1);
    }
    $query1 = $q . " `" . $tbl_name . "` SET
		`vLabel` = '" . $vLabel . "',
		`eAppType` = '" . $eAppType . "',
		`lPage_id` = '" . $lPage_id . "',
		`vCode` = 'EN',
		`vValue` = '" . $vValue_ride . "'"
            . $where;

    $obj1->sql_query($query1);
    $obj1->MySQLClose();
    /* ############################################Delivery############################################ */
    if (!isset($obj2)) {
        $obj2 = new DBConnection(TSITE_SERVER2, TSITE_DB2, TSITE_USERNAME2, TSITE_PASS2);
    }
    $query2 = $q . " `" . $tbl_name . "` SET
		`vLabel` = '" . $vLabel . "',
		`eAppType` = '" . $eAppType . "',
		`lPage_id` = '" . $lPage_id . "',
		`vCode` = 'EN',
		`vValue` = '" . $vValue_delivery . "'"
            . $where;

    $obj2->sql_query($query2);
    $obj2->MySQLClose();

    /* ########################################## Uberx ############################################## */

    if (!isset($obj3)) {
        $obj3 = new DBConnection(TSITE_SERVER3, TSITE_DB3, TSITE_USERNAME3, TSITE_PASS3);
    }
    $query3 = $q . " `" . $tbl_name . "` SET
		`vLabel` = '" . $vLabel . "',
		`eAppType` = '" . $eAppType . "',
		`lPage_id` = '" . $lPage_id . "',
		`vCode` = 'EN',
		`vValue` = '" . $vValue_uberx . "'"
            . $where;

    $obj3->sql_query($query3);
    $obj3->MySQLClose();

    /* ######################################Ride Delivery################################################## */

    if (!isset($obj4)) {
        $obj4 = new DBConnection(TSITE_SERVER4, TSITE_DB4, TSITE_USERNAME4, TSITE_PASS4);
    }

    $query4 = $q . " `" . $tbl_name . "` SET
		`vLabel` = '" . $vLabel . "',
		`eAppType` = '" . $eAppType . "',
		`lPage_id` = '" . $lPage_id . "',
		`vCode` = 'EN',
		`vValue` = '" . $vValue_ride_delivery . "'"
            . $where;

    $obj4->sql_query($query4);
    $obj4->MySQLClose();
    /* ######################################Food new################################################## */
    if (!isset($obj6)) {
        $obj6 = new DBConnection(TSITE_SERVER6, TSITE_DB6, TSITE_USERNAME6, TSITE_PASS6);
    }
    $query6 = $q . " `" . $tbl_name . "` SET
		`vLabel` = '" . $vLabel . "',
		`eAppType` = '" . $eAppType . "',
		`lPage_id` = '" . $lPage_id . "',
		`vCode` = 'EN',
		`vValue` = '" . $vValue_food . "'"
            . $where;

    $obj6->sql_query($query6);
    $obj6->MySQLClose();
    /* ######################################Deliverall new################################################## */
    if (!isset($obj7)) {
        $obj7 = new DBConnection(TSITE_SERVER7, TSITE_DB7, TSITE_USERNAME7, TSITE_PASS7);
    }
    $query7 = $q . " `" . $tbl_name . "` SET
		`vLabel` = '" . $vLabel . "',
		`eAppType` = '" . $eAppType . "',
		`lPage_id` = '" . $lPage_id . "',
		`vCode` = 'EN',
		`vValue` = '" . $vValue_deliverall . "'"
            . $where;

    $obj7->sql_query($query7);
    $obj7->MySQLClose();
    /* ######################################cubejek new################################################## */
    if (!isset($obj8)) {
        $obj8 = new DBConnection(TSITE_SERVER8, TSITE_DB8, TSITE_USERNAME8, TSITE_PASS8);
    }
    $query8 = $q . " `" . $tbl_name . "` SET
		`vLabel` = '" . $vLabel . "',
		`eAppType` = '" . $eAppType . "',
		`lPage_id` = '" . $lPage_id . "',
		`vCode` = 'EN',
		`vValue` = '" . $vValue_cubejekNew . "'"
            . $where;

    $obj8->sql_query($query8);
    $obj8->MySQLClose();
    /* ######################################################################################## */
    /* ######################################cubejek new################################################## */
    if (!isset($obj9)) {
        $obj9 = new DBConnection(TSITE_SERVER9, TSITE_DB9, TSITE_USERNAME9, TSITE_PASS9);
    }
    $query9 = $q . " `" . $tbl_name . "` SET
		`vLabel` = '" . $vLabel . "',
		`eAppType` = '" . $eAppType . "',
		`lPage_id` = '" . $lPage_id . "',
		`vCode` = 'EN',
		`vValue` = '" . $vValue_cubejekShark . "'"
            . $where;
    $obj9->sql_query($query9);
    $obj9->MySQLClose();
    /* ######################################################################################## */
    //header("Location:languages.php?id=".$vLabel.'&success=1');
    if ($action == "Add") {
        $_SESSION['success'] = '1';
        $_SESSION['var_msg'] = $langage_lbl_admin['LBL_RECORD_INSERT_MSG'];
    } else {
        $_SESSION['success'] = '1';
        $_SESSION['var_msg'] = $langage_lbl_admin['LBL_Record_Updated_successfully'];
    }

    header("location:" . $backlink);
}

// for Edit
if ($action == 'Edit') {
    $sql = "SELECT * FROM " . $tbl_name . " WHERE LanguageLabelId = '" . $id . "'";
    $db_data = $obj->MySQLSelect($sql);

    $eAppType = $db_data[0]['eAppType'];

    $vLabel = $db_data[0]['vLabel'];
    $lPage_id = $db_data[0]['lPage_id'];
    $vValue_ride_delivery_uberx = $db_data[0]['vValue'];
    $obj->MySQLClose();

    if (!isset($obj5)) {
        $obj5 = new DBConnection(TSITE_SERVER5, TSITE_DB5, TSITE_USERNAME5, TSITE_PASS5);
    }

    $sql5 = "SELECT vValue FROM " . $tbl_name . " WHERE vLabel = '" . $vLabel . "'";
    $db_data5 = $obj5->MySQLSelect($sql5);
    $vValue_cubejek = $db_data5[0]['vValue'];
    $obj5->MySQLClose();

    if (!isset($obj1)) {
        $obj1 = new DBConnection(TSITE_SERVER1, TSITE_DB1, TSITE_USERNAME1, TSITE_PASS1);
    }

    $sql1 = "SELECT vValue FROM " . $tbl_name . " WHERE vLabel = '" . $vLabel . "'";
    $db_data1 = $obj1->MySQLSelect($sql1);
    $vValue_ride = $db_data1[0]['vValue'];
    $obj1->MySQLClose();

    if (!isset($obj2)) {
        $obj2 = new DBConnection(TSITE_SERVER2, TSITE_DB2, TSITE_USERNAME2, TSITE_PASS2);
    }

    $sql2 = "SELECT vValue FROM " . $tbl_name . " WHERE vLabel = '" . $vLabel . "'";
    $db_data2 = $obj2->MySQLSelect($sql2);
    $vValue_delivery = $db_data2[0]['vValue'];
    $obj2->MySQLClose();

    if (!isset($obj3)) {
        $obj3 = new DBConnection(TSITE_SERVER3, TSITE_DB3, TSITE_USERNAME3, TSITE_PASS3);
    }

    $sql3 = "SELECT vValue FROM " . $tbl_name . " WHERE vLabel = '" . $vLabel . "'";
    $db_data3 = $obj3->MySQLSelect($sql3);
    $vValue_uberx = $db_data3[0]['vValue'];
    $obj3->MySQLClose();

    if (!isset($obj4)) {
        $obj4 = new DBConnection(TSITE_SERVER4, TSITE_DB4, TSITE_USERNAME4, TSITE_PASS4);
    }

    $sql4 = "SELECT vValue FROM " . $tbl_name . " WHERE vLabel = '" . $vLabel . "'";
    $db_data4 = $obj4->MySQLSelect($sql4);
    $vValue_ride_delivery = $db_data4[0]['vValue'];
    $obj4->MySQLClose();

    if (!isset($obj6)) {
        $obj6 = new DBConnection(TSITE_SERVER6, TSITE_DB6, TSITE_USERNAME6, TSITE_PASS6);
    }

    $sql6 = "SELECT vValue FROM " . $tbl_name . " WHERE vLabel = '" . $vLabel . "'";
    $db_data6 = $obj6->MySQLSelect($sql6);
    $vValue_food = $db_data6[0]['vValue'];
    $obj6->MySQLClose();

    if (!isset($obj7)) {
        $obj7 = new DBConnection(TSITE_SERVER7, TSITE_DB7, TSITE_USERNAME7, TSITE_PASS7);
    }

    $sql7 = "SELECT vValue FROM " . $tbl_name . " WHERE vLabel = '" . $vLabel . "'";
    $db_data7 = $obj7->MySQLSelect($sql7);
    $vValue_deliverall = $db_data7[0]['vValue'];
    $obj7->MySQLClose();

    if (!isset($obj8)) {
        $obj8 = new DBConnection(TSITE_SERVER8, TSITE_DB8, TSITE_USERNAME8, TSITE_PASS8);
    }

    $sql8 = "SELECT vValue FROM " . $tbl_name . " WHERE vLabel = '" . $vLabel . "'";
    $db_data8 = $obj8->MySQLSelect($sql8);
    $vValue_cubejekNew = $db_data8[0]['vValue'];
    $obj8->MySQLClose();

    if (!isset($obj9)) {
        $obj9 = new DBConnection(TSITE_SERVER9, TSITE_DB9, TSITE_USERNAME9, TSITE_PASS9);
    }

    $sql9 = "SELECT vValue FROM " . $tbl_name . " WHERE vLabel = '" . $vLabel . "'";
    $db_data9 = $obj9->MySQLSelect($sql9);
    $vValue_cubejekShark = $db_data9[0]['vValue'];
    $obj9->MySQLClose();
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title>Admin | Language <?= $action; ?></title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
        <? include_once('global_files.php'); ?>
    </head>
    <!-- END  HEAD-->
    <!-- BEGIN BODY-->
    <body class="padTop53 " >

        <!-- MAIN WRAPPER -->
        <div id="wrap">
            <? include_once('header.php'); ?>
            <? include_once('left_menu.php'); ?>
            <!--PAGE CONTENT -->
            <div id="content">
                <div class="inner">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2><?= $action; ?> Language Label</h2>
                            <a href="languages.php" class="back_link">
                                <input type="button" value="Back to Listing" class="add-btn">
                            </a>
                        </div>
                    </div>
                    <hr />
                    <div class="body-div">
                        <div class="form-group">
                            <? if ($success == 1) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $langage_lbl_admin['LBL_Record_Updated_successfully']; ?>
                                </div><br/>
                            <? } elseif ($success == 2) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                     <?php echo $langage_lbl_admin['LBL_EDIT_DELETE_RECORD']; ?>
                                </div><br/>
                            <? } elseif ($success == 0 && $var_msg != '') { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?= $var_msg; ?>
                                </div><br/>
                            <? } ?>
                            <form method="post" name="_languages_form" id="_languages_form" action="">
                                <input type="hidden" name="id" value="<?= $id; ?>"/>
                                <input type="hidden" name="previousLink" id="previousLink" value="<?php echo $previousLink; ?>"/>
                                <input type="hidden" name="backlink" id="backlink" value="languages.php"/>
                                <div class="row">
                                    <div class="col-lg-12" id="errorMessage">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Language Label <?= ($id != '') ? '' : '<span class="red"> *</span>'; ?></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="vLabel"  id="vLabel" value="<?= $vLabel; ?>" placeholder="Language Label" <?= ($id != '') ? 'disabled' : 'required'; ?>>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Label value for cubejek shark(English)<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="vValue_cubejek" id="vValue_cubejek" value="<?php echo htmlspecialchars($vValue_cubejek, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Label value for cubejek (English)" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Label value for Ride-Delivery-UberX (English)<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="vValue_ride_delivery_uberx" id="vValue_ride_delivery_uberx" value="<?php echo htmlspecialchars($vValue_ride_delivery_uberx, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Label value for Ride-Delivery-UberX (English)" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Label value for Ride (English)<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="vValue_ride" id="vValue_ride" value="<?php echo htmlspecialchars($vValue_ride, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Label value for Ride (English)" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Label value for Delivery (English)<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="vValue_delivery" id="vValue_delivery" value="<?php echo htmlspecialchars($vValue_delivery, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Label value for Delivery (English)" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Label value for UberX (English)<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="vValue_uberx" id="vValue_uberx" value="<?php echo htmlspecialchars($vValue_uberx, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Label value for UberX (English)" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Label value for Ride-Delivery (English)<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="vValue_ride_delivery" id="vValue_ride_delivery" value="<?php echo htmlspecialchars($vValue_ride_delivery, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Label value for Ride-Delivery (English)" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Label value for food(English)<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="vValue_food" id="vValue_food" value="<?php echo htmlspecialchars($vValue_food, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Label value for Food(English)" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Label value for deliverall(English)<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="vValue_deliverall" id="vValue_deliverall" value="<?php echo htmlspecialchars($vValue_deliverall, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Label value for DeliverAll(English)" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Label value for only cubejek(English)<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="vValue_cubejekNew" id="vValue_cubejekNew" value="<?php echo htmlspecialchars($vValue_cubejekNew, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Label value for only Cubejek(English)" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Label value for only cubejekShark(English)<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="vValue_cubejekShark" id="vValue_cubejekShark" value="<?php echo htmlspecialchars($vValue_cubejekShark, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Label value for only CubejekShark(English)" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Lable For<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <select name="eAppType" id="eAppType" class="form-control" required="required">
                                            <option value="General" <?= ($eAppType == 'General') ? 'selected' : ''; ?> >General</option>
                                            <option value="Ride" <?= ($eAppType == 'Ride') ? 'selected' : ''; ?> >Ride</option>
                                            <option value="Delivery" <?= ($eAppType == 'Delivery') ? 'selected' : ''; ?> >Delivery</option>
                                            <option value="Ride-Delivery" <?= ($eAppType == 'Ride-Delivery') ? 'selected' : ''; ?> >Ride-Delivery</option>
                                            <option value="UberX" <?= ($eAppType == 'UberX') ? 'selected' : ''; ?> >UberX</option>
                                            <option value="Multi-Delivery" <?= ($eAppType == 'Multi-Delivery') ? 'selected' : ''; ?> >Multi-Delivery</option>
                                            <option value="DeliverAll" <?= ($eAppType == 'DeliverAll') ? 'selected' : ''; ?> >DeliverAll</option>
                                            <option value="Kiosk" <?= ($eAppType == 'Kiosk') ? 'selected' : ''; ?> >Kiosk</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <input type="submit" class="btn btn-default" name="submit" id="submit" value="<?= $action; ?> Label">
                                        <input type="reset" value="Reset" class="btn btn-default">
                                        <a href="languages.php" class="btn btn-default back_link">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <!--END PAGE CONTENT -->
        </div>
        <!--END MAIN WRAPPER -->

        <div class="row loding-action" id="imageIcon" style="display:none;">
            <div align="center">                                                                       
                <img src="default.gif">                                                              
                <span>Language Translation is in Process. Please Wait...</span>                       
            </div>                                                                                 
        </div>


        <? include_once('footer.php'); ?>
    </body>
    <!-- END BODY-->
</html>
<script type="text/javascript" language="javascript">
    $(document).ready(function () {

        $('#imageIcon').hide();

        $("form[name='_languages_form']").submit(function () {
            var idvalue = $("input[name=id]").val();
            var vLabel = $("input[name=vLabel]").val();
            if (idvalue == '') {
                if (vLabel.match("^LBL_")) {
                    return true;
                } else {
                    alert('Please Add Language Label Start with \"LBL_\".');
                    return false;
                }

            } else {
                return true;
            }
        });

    });

    $(document).ready(function () {
        var referrer;
        if ($("#previousLink").val() == "") {
            referrer = document.referrer;
            //alert(referrer);		
        } else {
            referrer = $("#previousLink").val();
        }
        if (referrer == "") {
            referrer = "languages_action_multisystem.php";
        } else {
            $("#backlink").val(referrer);
        }
        $(".back_link").attr('href', referrer);
    });
</script>



