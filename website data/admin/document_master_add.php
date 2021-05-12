<?php
include_once('../common.php');

require_once(TPATH_CLASS . "/Imagecrop.class.php");
$thumb = new thumbnail();

if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
////$generalobjAdmin->check_member_login();
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$message_print_id = $id;
$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
$action = ($id != '') ? 'Edit' : 'Add';

$tbl_name = 'document_master';
$script = 'Document Master';

$doc_usertype = isset($_POST['doc_type']) ? $_POST['doc_type'] : '';
$doc_country1 = isset($_POST['country']) ? $_POST['country'] : '';
$Document_type = isset($_POST['Document_type']) ? $_POST['Document_type'] : '';
$exp = isset($_POST['exp']) ? $_POST['exp'] : '';
$eType = isset($_POST['eType']) ? $_POST['eType'] : 'Ride';
$backlink = isset($_POST['backlink']) ? $_POST['backlink'] : '';
$previousLink = isset($_POST['backlink']) ? $_POST['backlink'] : '';
//exit();


$vTitle_store = array();
$sql = "SELECT vCode,vTitle,eDefault FROM `language_master` where eStatus='Active' ORDER BY `iDispOrder`";
$db_master = $obj->MySQLSelect($sql);

$count_all = count($db_master);
if ($count_all > 0) {
    for ($i = 0; $i < $count_all; $i++) {
        $vValue = 'doc_name_' . $db_master[$i]['vCode'];
        array_push($vTitle_store, $vValue);
        $$vValue = isset($_POST[$vValue]) ? $_POST[$vValue] : '';
    }
}

if (isset($_POST['btnsubmit'])) {

    if ($action == "Add" && !$userObj->hasPermission('create-documents')) {
        $_SESSION['success'] = 3;
        $_SESSION['var_msg'] = 'You do not have permission to create Document.';
        header("Location:document_master_list.php");
        exit;
    }

    if ($action == "Edit" && !$userObj->hasPermission('edit-documents')) {
        $_SESSION['success'] = 3;
        $_SESSION['var_msg'] = 'You do not have permission to update Document.';
        header("Location:document_master_list.php");
        exit;
    }

    $sql1 = "SELECT vCountry FROM country where iCountryId='" . $doc_country1 . "'";
    $data_contry = $obj->MySQLSelect($sql1);
    $doc_country = $data_contry[0]['vCountry'];

    if ($eFareType == "Fixed") {
        $ePickStatus = "Inactive";
        $eNightStatus = "Inactive";
    } else {
        $ePickStatus = $ePickStatus;
        $eNightStatus = $eNightStatus;
    }


    if ($eNightStatus == "Active") {
        if ($tNightStartTime > $tNightEndTime) {
            header("Location:vehicle_type_action.php?id=" . $id . "&success=4");
            exit;
        }
    }
    if (SITE_TYPE == 'Demo') {
        header("Location:vehicle_type_action.php?id=" . $id . "&success=2");
        exit;
    }

    for ($i = 0; $i < count($vTitle_store); $i++) {

        $vValue = 'doc_name_' . $db_master[$i]['vCode'];
        // echo $_POST[$vTitle_store[$i]] ; exit;
        $q = "INSERT INTO ";
        $where = '';
        if ($id != '') {
            $q = "UPDATE ";
            $where = " WHERE `doc_masterid` = '" . $id . "'";
        }


        $query = $q . " `" . $tbl_name . "` SET             
    `doc_usertype` = '" . $doc_usertype . "',
    `doc_name` = '" . $Document_type . "' ,
    `country` = '" . $doc_country1 . "',
    `ex_status` = '" . $exp . "',
    `eType` = '" . $eType . "', 
    " . $vValue . " = '" . $_POST[$vTitle_store[$i]] . "'"
                . $where;

        $obj->sql_query($query);

        $id = ($id != '') ? $id : $obj->GetInsertId();
    }


    $_SESSION['success'] = '1';
    if ($action == "Edit") {
        $msg = $langage_lbl_admin['LBL_Record_Updated_successfully'];
    } else {
        $msg = $langage_lbl_admin['LBL_RECORD_INSERT_MSG'];
    }
    $_SESSION['var_msg'] = $msg;
    // $obj->sql_query($query);
    header("Location:" . $backlink);
    exit;
    // header("Location:document_master_list.php");
}

// for Edit
if ($action == 'Edit') {

    $sql = "SELECT * FROM " . $tbl_name . " WHERE doc_masterid = '" . $id . "'";
    $db_data = $obj->MySQLSelect($sql);


    $vLabel = $id;
    if (count($db_data) > 0) {
        for ($i = 0; $i < count($db_master); $i++) {

            foreach ($db_data as $key => $value) {
                $vValue = 'doc_name_' . $db_master[$i]['vCode'];
                $$vValue = $value[$vValue];
                $doc_usertype = $value['doc_usertype'];
                $doc_country = $value['country'];
                $doc_name = $value['doc_name'];
                $exp = $value['ex_status'];
                $eType = $value['eType'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> 
<html lang="en"> <!--<![endif]-->
    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title>Admin | <?php echo $langage_lbl_admin['LBL_DOCUMENT_TYPE']; ?> <?= $action; ?></title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
        <?
        include_once('global_files.php');
        ?>
        <!-- On OFF switch -->
        <link href="../assets/css/jquery-ui.css" rel="stylesheet" />
        <link rel="stylesheet" href="../assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />
    </head>
    <!-- END  HEAD-->
    <!-- BEGIN BODY-->
    <body class="padTop53 " >
        <!-- MAIN WRAPPER -->
        <div id="wrap">
            <?
            include_once('header.php');
            include_once('left_menu.php');
            ?>
            <!--PAGE CONTENT -->
            <div id="content">
                <div class="inner">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2> <?php echo $langage_lbl_admin['LBL_DOCUMENT_TYPE']; ?> </h2>
                            <a href="javascript:void(0);" class="back_link">
                                <input type="button" value="Back to Listing" class="add-btn">
                            </a>
                            <!--                         <a href="document_master_list.php">
                                                        <input type="button" value="Back to Listing" class="add-btn">
                                                    </a> -->
                        </div>
                    </div>
                    <hr />
                    <div class="body-div">
                        <div class="form-group">
                            <? if ($success == 1) { ?>
                                <div class="alert alert-success alert-dismissable msgs_hide">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                    <?= $langage_lbl_admin['LBL_Record_Updated_successfully']; ?>
                                </div><br/>
                            <? } elseif ($success == 2) { ?>
                                <div class="alert alert-danger alert-dismissable ">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                    <?php echo $langage_lbl_admin['LBL_EDIT_DELETE_RECORD']; ?>
                                </div><br/>
                            <? } elseif ($success == 3) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                    <?php echo $_REQUEST['varmsg']; ?> 
                                </div><br/>	
                            <? } elseif ($success == 4) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                    "Please Select Night Start Time less than Night End Time." 
                                </div><br/>	
                            <? } ?>
                            <? if ($_REQUEST['var_msg'] != Null) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    Record  Not Updated .
                                </div><br/>
                            <? } ?>

                            <form id="vtype" method="post" action="" enctype="multipart/form-data" >
                                <input type="hidden" name="id" value="<?= $id; ?>"/>
                                <input type="hidden" name="previousLink" id="previousLink" value="<?php echo $previousLink; ?>"/>
                                <input type="hidden" name="backlink" id="backlink" value="document_master_list.php"/>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Document For <span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <select  class="form-control" name = 'doc_type'  id="doc_type" required>
                                            <?php if ($APP_TYPE != "UberX") { ?>
                                                <option value="car" <?php if ($doc_usertype == "car") echo 'selected="selected"'; ?> >Car</option>
                                            <?php } ?>
                                            <? if (ONLYDELIVERALL == "No") { ?>
                                                <option value="company"<?php if ($doc_usertype == "company") echo 'selected="selected"'; ?>>Company</option>
                                            <?php } ?>
                                            <option value="driver"<?php if ($doc_usertype == "driver") echo 'selected="selected"'; ?>><?php echo $langage_lbl_admin['LBL_RIDER_DRIVER_RIDE_DETAIL'] ?></option>

                                            <?php if (DELIVERALL == "Yes") { ?>
                                                <option value="store" <?php if ($doc_usertype == "store") echo 'selected="selected"'; ?>>Store</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <!--   <?php if ($APP_TYPE == 'Ride-Delivery' || $APP_TYPE == 'Ride-Delivery-UberX') { ?>
                                      <div class="row" id="servicetype">
                                          <div class="col-lg-12">
                                              <label>Service Type<span class="red">*</span></label>
                                          </div>
                                          <div class="col-lg-6">
                                              <select  class="form-control" name = 'eType' required id='etypedelivery'>
                                                  <option value="Ride" <?php if ($eType == "Ride") echo 'selected="selected"'; ?> >Ride</option>
                                                  <option value="Delivery"<?php if ($eType == "Delivery") echo 'selected="selected"'; ?>>Delivery</option>
                                                  <option value="UberX" <?php if ($eType == "UberX") echo 'selected="selected"'; ?> id="servicetype-uberx" >Other Services</option>
                                              </select>
                                          </div>
                                      </div>
                                <?php } else { ?>
                                      <input type="hidden" name="eType" value="<?= $APP_TYPE ?>" id='etypedelivery'>
                                <?php } ?> -->

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Country <span class="red"> *</span></label>
                                    </div>
                                    <div class="col-lg-6">
                                        <select id="country"  class="form-control" name = 'country'  required >
                                            <option value="All">All Country</option>
                                            <?php
                                            // country 
                                            $sql = "SELECT iCountryId,vCountry,vCountryCode FROM country WHERE eStatus='Active' ORDER BY iCountryId ASC";
                                            $db_data1 = $obj->MySQLSelect($sql);
                                            foreach ($db_data1 as $value) {
                                                ?>
                                                <option <?php if ($db_data[0]['country'] == $value['vCountryCode']) {
                                                echo 'selected';
                                            } ?> value="<?php echo $value['vCountryCode']; ?>"><?php echo $value['vCountry']; ?></option>
<?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-lg-12">
                                        <label>Expire On Date <span class="red"> *</span> 
                                            <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='Yes option will ask for Date'></i>
                                        </label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="radio"  name="exp"  id="exp"  value="yes"  <?php if ($exp == "yes") echo 'checked="checked"'; ?>  required > Yes
                                        <input type="radio"  name="exp"   id="exp" value="no"  <?php if ($exp == "no") echo 'checked="checked"'; ?>  required > No
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Document Name <span class="red"> *</span> 
                                            <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='Name of Document for admin use. e.g. Insurance, Driving Licence... etc'></i>

                                        </label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="Document_type"  id="Docmaster"  value="<?= $doc_name; ?>"  required>
                                    </div>
                                </div>
                                <?php
                                if ($count_all > 0) {
                                    for ($i = 0; $i < $count_all; $i++) {
                                        $vCode = $db_master[$i]['vCode'];
                                        $vTitle = $db_master[$i]['vTitle'];
                                        $eDefault = $db_master[$i]['eDefault'];
                                        $vValue = 'doc_name_' . $vCode;
                                        $required = ($eDefault == 'Yes') ? 'required' : '';
                                        $required_msg = ($eDefault == 'Yes') ? '<span class="red"> *</span>' : '';
                                        ?>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label><?php echo $langage_lbl_admin['LBL_DOCUMENT_TYPE']; ?> (<?= $vTitle; ?>)<?php echo $required_msg; ?> 
                                                    <i class="icon-question-sign" data-placement="top" data-toggle="tooltip" data-original-title='Name of Document as per language. e.g. Insurance, Driving Licence... etc'></i>
                                                </label>

                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="<?= $vValue; ?>" id="<?= $vValue; ?>" value="<?= $$vValue; ?>" placeholder="<?= $vTitle; ?>Value" <?= $required; ?>>

                                            </div>
                                        </div>
    <? }
}
?>

                                <div class="row">
                                    <div class="col-lg-12">
<?php if (($action == 'Edit' && $userObj->hasPermission('edit-documents')) || ($action == 'Add' && $userObj->hasPermission('create-documents'))) { ?>
                                            <input type="submit" class="save btn-info" name="btnsubmit" id="btnsubmit" value="<?= $action . " " . $langage_lbl_admin['LBL_DOCUMENT_TYPE']; ?>" >
<?php } ?>
                                        <a href="document_master_list.php" class="btn btn-default back_link">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div style="clear:both;"></div>
                </div>

            </div>

            <!--END PAGE CONTENT -->
        </div>
        <!--END MAIN WRAPPER -->

<? include_once('footer.php'); ?>
        <script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>	
        <script>
            $(document).ready(function () {
                var referrer;
                if ($("#previousLink").val() == "") {
                    referrer = document.referrer;
                } else {
                    referrer = $("#previousLink").val();
                }
                if (referrer == "") {
                    referrer = "document_master_list.php";
                } else {
                    $("#backlink").val(referrer);
                }
                $(".back_link").attr('href', referrer);
            });

            $('[data-toggle="tooltip"]').tooltip();
            var successMSG1 = '<?php echo $success; ?>';

            if (successMSG1 != '') {
                setTimeout(function () {
                    $(".msgs_hide").hide(1000)
                }, 5000);
            }

            if ($("#doc_type option:selected").val() == 'car') {
                $("#servicetype-uberx").hide();
            } else {
                $("#servicetype-uberx").show();
            }

            if ($("#doc_type option:selected").val() == 'company') {
                $("#servicetype").hide();
            } else {
                $("#servicetype").show();
            }

            $('#doc_type').on('change', function (e) {
                var valueSelected = this.value;
                if (valueSelected == 'company') {
                    $("#servicetype").hide();
                    $("#servicetype-uberx").show();
                } else if (valueSelected == 'car') {
                    $("#servicetype-uberx").hide();
                    $("#servicetype").show();
                } else {
                    $("#servicetype").show();
                    $("#servicetype-uberx").show();
                }
            });
        </script>
    </body>
    <!-- END BODY-->
</html>
