<?
include_once('../common.php');

if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
////$generalobjAdmin->check_member_login();

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
$action = ($id != '') ? 'Edit' : 'Add';

$tbl_name = 'country';
$script = 'country';

$backlink = isset($_POST['backlink']) ? $_POST['backlink'] : '';
$previousLink = isset($_POST['backlink']) ? $_POST['backlink'] : '';

// set all variables with either post (when submit) either blank (when insert)
$vCountry = isset($_POST['vCountry']) ? $_POST['vCountry'] : '';
$vCountryCode = isset($_POST['vCountryCode']) ? $_POST['vCountryCode'] : '';
$vCountryCodeISO_3 = isset($_POST['vCountryCodeISO_3']) ? $_POST['vCountryCodeISO_3'] : '';
$vPhoneCode = isset($_POST['vPhoneCode']) ? $_POST['vPhoneCode'] : '';
$vEmergencycode = isset($_POST['vEmergencycode']) ? $_POST['vEmergencycode'] : '';
$fTax1 = isset($_POST['fTax1']) ? $_POST['fTax1'] : '';
$eUnit = isset($_POST['eUnit']) ? $_POST['eUnit'] : '';
$eEnableToll = isset($_POST['eEnableToll']) ? $_POST['eEnableToll'] : '';
$eStatus_check = isset($_POST['eStatus']) ? $_POST['eStatus'] : 'off';
$eStatus = ($eStatus_check == 'on') ? 'Active' : 'Inactive';

if (isset($_POST['submit'])) {
    if ($action == "Add" && !$userObj->hasPermission('create-country')) {
        $_SESSION['success'] = 3;
        $_SESSION['var_msg'] = 'You do not have permission to create country.';
        header("Location:country.php");
        exit;
    }

    if ($action == "Edit" && !$userObj->hasPermission('edit-country')) {
        $_SESSION['success'] = 3;
        $_SESSION['var_msg'] = 'You do not have permission to update country.';
        header("Location:country.php");
        exit;
    }


    if (SITE_TYPE == 'Demo' && $id != "") {
        $_SESSION['success'] = '2';
        header("location:" . $backlink);
        exit;
    }

    //Add Custom validation
    require_once("library/validation.class.php");
    $validobj = new validation();
    $validobj->add_fields($_POST['vCountry'], 'req', 'Country Name is required');
    $validobj->add_fields($_POST['vCountryCode'], 'req', 'Country Code is required');
    $validobj->add_fields($_POST['vPhoneCode'], 'req', 'Phone Code is required.');
    $validobj->add_fields($_POST['vEmergencycode'], 'req', 'Police number is required.');

    $error = $validobj->validate();
    //Added By HJ On 21-01-2019 For Check Country Name and It's Code As Per Client Bug - 6726 Start
    $whereCond = '';
    if ($id != '') {
        $whereCond = " AND `iCountryId` != '" . $id . "'";
    }
    $checkCountryCode = $obj->MySQLSelect("SELECT iCountryId FROM country WHERE eStatus='Active' $whereCond AND (`vCountry` LIKE '" . $vCountry . "' OR `vCountryCode` LIKE '" . $vCountryCode . "')");
    //print_r($checkCountryCode);die;
    if (count($checkCountryCode) > 0) {
        $error = 'Country Name or Code already exists.';
    }
    //Added By HJ On 21-01-2019 For Check Country Name and It's Code As Per Client Bug - 6726 End
    if ($error) {
        $success = 3;
        $newError = $error;
        //exit;
    } else {
        $q = "INSERT INTO ";
        $where = '';
        if ($id != '') {
            $q = "UPDATE ";
            $where = " WHERE `iCountryId` = '" . $id . "'";
        }
        $query = $q . " `" . $tbl_name . "` SET
			`vCountry` = '" . $vCountry . "',
			`vCountryCode` = '" . $vCountryCode . "',
			`vPhoneCode` = '" . $vPhoneCode . "',
			`vEmergencycode` = '" . $vEmergencycode . "',
			`fTax1` = '" . $fTax1 . "',
			`eUnit` = '" . $eUnit . "',
			`eEnableToll` = '" . $eEnableToll . "',
			`eStatus` = '" . $eStatus . "'"
                . $where;
        $obj->sql_query($query);
        $id = ($id != '') ? $id : $obj->GetInsertId();

        if ($action == "Add") {
            $_SESSION['success'] = '1';
            $_SESSION['var_msg'] = $langage_lbl_admin['LBL_RECORD_INSERT_MSG'];
        } else {
            $_SESSION['success'] = '1';
            $_SESSION['var_msg'] = $langage_lbl_admin['LBL_Record_Updated_successfully'];
        }
        header("location:" . $backlink);
        die;
    }
}

// for Edit
if ($action == 'Edit') {
    $sql = "SELECT * FROM " . $tbl_name . " WHERE iCountryId = '" . $id . "'";
    $db_data = $obj->MySQLSelect($sql);

    $vLabel = $id;
    if (count($db_data) > 0) {
        foreach ($db_data as $key => $value) {
            $vCountry = $value['vCountry'];
            $vCountryCode = $value['vCountryCode'];
            $eUnit = $value['eUnit'];
            $eEnableToll = $value['eEnableToll'];
            $vPhoneCode = $generalobjAdmin->clearPhone($value['vPhoneCode']);
            $vEmergencycode = $value['vEmergencycode'];
            $fTax1 = $value['fTax1'];
            $eStatus = $value['eStatus'];
        }
    }
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title>Admin | Country <?= $action; ?></title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />

        <link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />

        <? include_once('global_files.php'); ?>
        <!-- On OFF switch -->
        <link href="../assets/css/jquery-ui.css" rel="stylesheet" />
        <link rel="stylesheet" href="../assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />
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
                            <h2><?= $action; ?> Country</h2>
                            <a href="country.php" class="back_link">
                                <input type="button" value="Back to Listing" class="add-btn">
                            </a>	

                        </div>
                    </div>
                    <hr />
                    <div class="body-div">
                        <div class="form-group">
                            <? if ($success == 2) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                    <?php echo $langage_lbl_admin['LBL_EDIT_DELETE_RECORD']; ?>
                                </div><br/>
                            <? } ?>
                            <? if ($success == 3) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                    <?php print_r($error); ?>
                                </div><br/>
                            <? } ?>
                            <form method="post" name="_country_form" id="_country_form" action="">
                                <input type="hidden" name="id" value="<?= $id; ?>"/> 
                                <input type="hidden" name="previousLink" id="previousLink" value="<?php echo $previousLink; ?>"/>
                                <input type="hidden" name="backlink" id="backlink" value="country.php"/>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Country Name<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="vCountry"  id="vCountry" value="<?= $vCountry; ?>" placeholder="Country Name" >
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Country Code<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="vCountryCode"  id="vCountryCode" value="<?= $vCountryCode; ?>" placeholder="Country Code" >
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Country Phone Code<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="vPhoneCode"  id="vPhoneCode" value="<?= $vPhoneCode; ?>" placeholder="Country Phone Code" >
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Unit <span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <select class="form-control" name = 'eUnit' required>
                                            <option value = "KMs" <? if ($eUnit == "KMs") { ?>selected<?php } ?>>KMs</option>
                                            <option value = "Miles" <? if ($eUnit == "Miles") { ?>selected<?php } ?>>Miles</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Emergency number<span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="vEmergencycode"  id="vEmergencycode" value="<?= $vEmergencycode; ?>" placeholder="Emergency Number" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label> <?php echo $langage_lbl_admin['LBL_TAX1_TXT']; ?> <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title="Enter the value in % if you would like to apply the Tax as per your country. System takes Provider/Driver's country in consideraton while fetching and applying the Tax value."></i></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="fTax1"  id="fTax1" value="<?= $fTax1; ?>" placeholder="Tax" >
                                    </div>
                                </div>
                                <?php if ($ENABLE_TOLL_COST == 'Yes') { ?>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label>Toll Enable <span class="red"> *</span></label>
                                        </div>
                                        <div class="col-lg-6">
                                            <select class="form-control" name = 'eEnableToll' required>
                                                <option value = "No" <? if ($eEnableToll == "No") { ?>selected<?php } ?>>No</option>
                                                <option value = "Yes" <? if ($eEnableToll == "Yes") { ?>selected<?php } ?>>Yes</option>
                                            </select>
                                            <br/>
                                            <div>[Note: Please make sure that your application must have the currency available for the country you are going to enable the Toll feature.]</div>
                                        </div>								 
                                    </div>
                                <?php } else { ?>
                                    <input type="hidden" name="eEnableToll" value="No" />
                                <?php } ?>
                                <?php if ($eStatus != 'Deleted') { ?>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label>Status</label>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="make-switch" data-on="success" data-off="warning">
                                                <input type="checkbox" name="eStatus" <?= ($id != '' && $eStatus == 'Inactive') ? '' : 'checked'; ?>/>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php if (($action == 'Edit' && $userObj->hasPermission('edit-country')) || ($action == 'Add' && $userObj->hasPermission('create-country'))) { ?>
                                            <input type="submit" class="btn btn-default" name="submit" id="submit" value="<?= $action; ?> Country">
                                            <input type="reset" value="Reset" class="btn btn-default">
                                        <?php } ?>
                                        <!-- <a href="javascript:void(0);" onclick="reset_form('_country_form');" class="btn btn-default">Reset</a> -->
                                        <a href="country.php" class="btn btn-default back_link">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--END PAGE CONTENT -->
        </div>
        <!--END MAIN WRAPPER -->


        <? include_once('footer.php'); ?>
        <script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>
    </body>
    <!-- END BODY-->
</html>
<script>
    $(document).ready(function () {
        var referrer;
        if ($("#previousLink").val() == "") {
            referrer = document.referrer;
        } else {
            referrer = $("#previousLink").val();
        }
        if (referrer == "") {
            referrer = "country.php";
        } else {
            $("#backlink").val(referrer);
        }
        $(".back_link").attr('href', referrer);
    });
</script>