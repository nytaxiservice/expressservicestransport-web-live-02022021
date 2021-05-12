<?php
include_once('../common.php');

if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}

////$generalobjAdmin->check_member_login();

function cleandata($str) {
    global $obj;
    $str = trim($str);
    $str = $obj->SqlEscapeString($str);
    $str = htmlspecialchars($str);
    $str = strip_tags($str);
    return($str);
}

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
$action = ($id != '') ? 'Edit' : 'Add';
$backlink = isset($_POST['backlink']) ? $_POST['backlink'] : '';
$previousLink = isset($_POST['backlink']) ? $_POST['backlink'] : '';
$message_print_id = $id;
$vCode = isset($_POST['vCode']) ? $_POST['vCode'] : '';
$tbl_name = 'homecontent';
$script = 'homecontent';
/* $vBannerBgImage = isset($_POST['vBannerBgImage']) ? $_POST['vBannerBgImage'] : '';
  $vBannerLeftImg = isset($_POST['vBannerLeftImg']) ? $_POST['vBannerLeftImg'] : ''; */
$vBannerRightTitle = isset($_POST['vBannerRightTitle']) ? ($_POST['vBannerRightTitle']) : '';
$vBannerRightTitleSmall = isset($_POST['vBannerRightTitleSmall']) ? ($_POST['vBannerRightTitleSmall']) : '';
$tBannerRightContent = isset($_POST['tBannerRightContent']) ? ($_POST['tBannerRightContent']) : '';

$vDeliveryPartTitle = isset($_POST['vDeliveryPartTitle']) ? ($_POST['vDeliveryPartTitle']) : '';
$vDeliveryPartContent = isset($_POST['vDeliveryPartContent']) ? ($_POST['vDeliveryPartContent']) : '';

$vMidSectionTitle = isset($_POST['vMidSectionTitle']) ? $_POST['vMidSectionTitle'] : '';

/* $vMidFirstImg = isset($_POST['vMidFirstImg']) ? $_POST['vMidFirstImg'] : ''; */
$vMidFirstTitle = isset($_POST['vMidFirstTitle']) ? $_POST['vMidFirstTitle'] : '';
$tMidFirstContent = isset($_POST['tMidFirstContent']) ? ($_POST['tMidFirstContent']) : '';

/* $vMidSecondImg = isset($_POST['vMidSecondImg']) ? $_POST['vMidSecondImg'] : ''; */
$vMidSecondTitle = isset($_POST['vMidSecondTitle']) ? $_POST['vMidSecondTitle'] : '';
$tMidSecondContent = isset($_POST['tMidSecondContent']) ? ($_POST['tMidSecondContent']) : '';

/* $vMidThirdImg = isset($_POST['vMidThirdImg']) ? $_POST['vMidThirdImg'] : ''; */
$vMidThirdTitle = isset($_POST['vMidThirdTitle']) ? $_POST['vMidThirdTitle'] : '';
$tMidThirdContent = isset($_POST['tMidThirdContent']) ? ($_POST['tMidThirdContent']) : '';

/* $vThirdSectionImg1 = isset($_POST['vThirdSectionImg1']) ? $_POST['vThirdSectionImg1'] : '';
  $vThirdSectionImg2 = isset($_POST['vThirdSectionImg2']) ? $_POST['vThirdSectionImg2'] : '';
  $vThirdSectionImg3 = isset($_POST['vThirdSectionImg3']) ? $_POST['vThirdSectionImg3'] : ''; */
$vThirdSectionRightTitle = isset($_POST['vThirdSectionRightTitle']) ? $_POST['vThirdSectionRightTitle'] : '';
$tThirdSectionRightContent = isset($_POST['tThirdSectionRightContent']) ? ($_POST['tThirdSectionRightContent']) : '';
/* $vThirdSectionAPPImgAPPStore = isset($_POST['vThirdSectionAPPImgAPPStore']) ? $_POST['vThirdSectionAPPImgAPPStore'] : '';
  $vThirdSectionAPPImgPlayStore = isset($_POST['vThirdSectionAPPImgPlayStore']) ? $_POST['vThirdSectionAPPImgPlayStore'] : ''; */

$vLastSectionTitle = isset($_POST['vLastSectionTitle']) ? $_POST['vLastSectionTitle'] : '';
/* $vLastSectionImg = isset($_POST['vLastSectionImg']) ? $_POST['vLastSectionImg'] : ''; */

$vLastSectionFirstTitle = isset($_POST['vLastSectionFirstTitle']) ? $_POST['vLastSectionFirstTitle'] : '';
$tLastSectionFirstContent = isset($_POST['tLastSectionFirstContent']) ? ($_POST['tLastSectionFirstContent']) : '';

$vLastSectionSecondTitle = isset($_POST['vLastSectionSecondTitle']) ? $_POST['vLastSectionSecondTitle'] : '';
$tLastSectionSecondContent = isset($_POST['tLastSectionSecondContent']) ? ($_POST['tLastSectionSecondContent']) : '';

$vLastSectionThirdTitle = isset($_POST['vLastSectionThirdTitle']) ? $_POST['vLastSectionThirdTitle'] : '';
$tLastSectionThirdContent = isset($_POST['tLastSectionThirdContent']) ? ($_POST['tLastSectionThirdContent']) : '';

$vLastSectionFourthTitle = isset($_POST['vLastSectionFourthTitle']) ? $_POST['vLastSectionFourthTitle'] : '';
$tLastSectionFourthContent = isset($_POST['tLastSectionFourthContent']) ? ($_POST['tLastSectionFourthContent']) : '';

$vLastSectionFifthTitle = isset($_POST['vLastSectionFifthTitle']) ? $_POST['vLastSectionFifthTitle'] : '';
$tLastSectionFifthContent = isset($_POST['tLastSectionFifthContent']) ? ($_POST['tLastSectionFifthContent']) : '';

$vLastSectionSixthTitle = isset($_POST['vLastSectionSixthTitle']) ? $_POST['vLastSectionSixthTitle'] : '';
$tLastSectionSixthContent = isset($_POST['tLastSectionSixthContent']) ? ($_POST['tLastSectionSixthContent']) : '';
//echo '<prE>'; print_R($_REQUEST); print_r($_FILES);die;
$eStatus_check = isset($_POST['eStatus']) ? $_POST['eStatus'] : 'off';
$eStatus = ($eStatus_check == 'on') ? 'Active' : 'Inactive';

if (isset($_POST['catlogo'])) {
    if (SITE_TYPE == 'Demo') {
        header("Location:home_content_action_new.php?id=" . $id . "&success=2");
        exit;
    }
    $vacategoryid = isset($_POST['aid']) ? $_POST['aid'] : '';
    //$vcatlogo = isset($_POST['aid']) ? $_POST['aid'] : '';
    //$img_path = $tconfig["tsite_upload_page_images_panel"];
    //$temp_gallery = $img_path . '/' . $_FILES['vHomepageLogo']['name'];
    //$image_name = $_FILES['vHomepageLogo']['name'];
    //$image_name1 = $_FILES['vHomepageLogo']['temp_name']._catlogo_. date('dmYhis');

    $img_arr = $_FILES;
    if (!empty($img_arr)) {
        foreach ($img_arr as $key => $value) {
            if (!empty($value['name'])) {
                $img_path = $tconfig["tsite_upload_home_page_service_images_panel"];
                $temp_gallery = $img_path . '/';
                $image_object = $value['tmp_name'];
                $image_name = $value['name'];
                $check_file_query = "SELECT " . $key . " FROM vehicle_category where iVehicleCategoryId='" . $vacategoryid . "'";
                $check_file = $obj->MySQLSelect($check_file_query);
                if ($message_print_id != "") {
                    $check_file = $img_path . '/' . $check_file[0][$key];
                    if ($check_file != '' && file_exists($check_file[0][$key])) {
                        @unlink($check_file);
                    }
                }
                $Photo_Gallery_folder = $img_path . '/';
                if (!is_dir($Photo_Gallery_folder)) {
                    mkdir($Photo_Gallery_folder, 0777);
                }
                $img = $generalobj->imageupload($Photo_Gallery_folder, $image_object, $image_name, '', 'png,jpg,jpeg,gif');
                //$img = $generalobj->fileupload_home($Photo_Gallery_folder,$image_object,$image_name,'','png,jpg,jpeg,gif','');
                if ($img[2] == "1") {
                    $_SESSION['success'] = '0';
                    $_SESSION['var_msg'] = $img[1];
                    header("location:" . $backlink);
                }
                if (!empty($img[0])) {
                    $sql = "UPDATE vehicle_category SET " . $key . " = '" . $img[0] . "' WHERE iVehicleCategoryId = '" . $vacategoryid . "'";
                    $obj->sql_query($sql);
                }
            }
        }
    }
}

if (isset($_POST['submit'])) {

    if (SITE_TYPE == 'Demo') {
        header("Location:home_content_action_new.php?id=" . $id . "&success=2");
        exit;
    }
    $img_arr = $_FILES;
    if (!empty($img_arr)) {
        foreach ($img_arr as $key => $value) {
            if (!empty($value['name'])) {
                $img_path = $tconfig["tsite_upload_home_page_images_panel"];
                $temp_gallery = $img_path . '/';
                $image_object = $value['tmp_name'];
                $image_name = $value['name'];
                $check_file_query = "SELECT " . $key . " FROM homecontent where vCode='" . $vCode . "'";
                $check_file = $obj->MySQLSelect($check_file_query);
                if ($message_print_id != "") {
                    $check_file = $img_path . '/' . $check_file[0][$key];
                    if ($check_file != '' && file_exists($check_file[0][$key])) {
                        @unlink($check_file);
                    }
                }
                $Photo_Gallery_folder = $img_path . "/";
                if (!is_dir($Photo_Gallery_folder)) {
                    mkdir($Photo_Gallery_folder, 0777);
                }

                $img = $generalobj->fileupload($Photo_Gallery_folder, $image_object, $image_name, '', 'png,jpg,jpeg,gif', $vCode);
                if ($img[2] == "1") {
                    $_SESSION['success'] = '0';
                    $_SESSION['var_msg'] = $img[1];
                    header("location:" . $backlink);
                }

                if (!empty($img[0])) {
                    $sql = "UPDATE " . $tbl_name . " SET " . $key . " = '" . $img[0] . "' WHERE `vCode` = '" . $vCode . "'";
                    $obj->sql_query($sql);
                }
            }
        }
    }


    $q = "INSERT INTO ";
    $where = '';

    if ($id != '') {
        $q = "UPDATE ";
        $where = " WHERE `vCode` = '" . $vCode . "'";
    }

    $query = $q . " `" . $tbl_name . "` SET
	`vBannerRightTitle` = '" . $vBannerRightTitle . "',
	`vBannerRightTitleSmall`  = '" . $vBannerRightTitleSmall . "',
	`tBannerRightContent` = '" . $tBannerRightContent . "', 
	`vDeliveryPartTitle` = '" . $vDeliveryPartTitle . "', 
	`vDeliveryPartContent` = '" . $vDeliveryPartContent . "', 
	`vMidSectionTitle` = '" . $vMidSectionTitle . "',  
	`vMidFirstTitle` = '" . $vMidFirstTitle . "', 
	`tMidFirstContent` = '" . $tMidFirstContent . "', 
	`vMidSecondTitle` = '" . $vMidSecondTitle . "', 
	`tMidSecondContent` = '" . $tMidSecondContent . "', 
	`vMidThirdTitle` = '" . $vMidThirdTitle . "',  
	`tMidThirdContent` = '" . $tMidThirdContent . "', 
	`vThirdSectionRightTitle` = '" . $vThirdSectionRightTitle . "', 
	`tThirdSectionRightContent` = '" . $tThirdSectionRightContent . "', 
	`vLastSectionTitle` = '" . $vLastSectionTitle . "',  
	`vLastSectionFirstTitle` = '" . $vLastSectionFirstTitle . "', 
	`tLastSectionFirstContent` = '" . $tLastSectionFirstContent . "', 
	`vLastSectionSecondTitle` = '" . $vLastSectionSecondTitle . "', 
	`tLastSectionSecondContent` = '" . $tLastSectionSecondContent . "',
	`vLastSectionThirdTitle` = '" . $vLastSectionThirdTitle . "', 
	`tLastSectionThirdContent` = '" . $tLastSectionThirdContent . "',
	`vLastSectionFourthTitle` = '" . $vLastSectionFourthTitle . "', 
	`tLastSectionFourthContent` = '" . $tLastSectionFourthContent . "',
	`vLastSectionFifthTitle` = '" . $vLastSectionFifthTitle . "', 
	`tLastSectionFifthContent` = '" . $tLastSectionFifthContent . "',
	`vLastSectionSixthTitle` = '" . $vLastSectionSixthTitle . "',
	`tLastSectionSixthContent` = '" . $tLastSectionSixthContent . "'"
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
}
// for Edit
if ($action == 'Edit') {
    $sql = "SELECT hc.*,lm.vTitle FROM homecontent as hc LEFT JOIN language_master as lm on lm.vCode = hc.vCode  WHERE hc.id = '" . $id . "'";
    $db_data = $obj->MySQLSelect($sql);
    $vLabel = $id;
    if (count($db_data) > 0) {
        foreach ($db_data as $key => $value) {
            $vCode = $value['vCode'];
            $vBannerBgImage = $value['vBannerBgImage'];
            $vBannerLeftImg = $value['vBannerLeftImg'];
            $vBannerRightTitle = $value['vBannerRightTitle'];
            $vBannerRightTitleSmall = $value['vBannerRightTitleSmall'];
            $tBannerRightContent = $value['tBannerRightContent'];

            $vDeliveryPartTitle = $value['vDeliveryPartTitle'];
            $vDeliveryPartContent = $value['vDeliveryPartContent'];
            $vDeliveryPartBgImg = $value['vDeliveryPartBgImg'];
            $vDeliveryPartImg = $value['vDeliveryPartImg'];

            $vMidSectionTitle = $value['vMidSectionTitle'];
            $vMidFirstImg = $value['vMidFirstImg'];
            $vMidFirstTitle = $value['vMidFirstTitle'];
            $tMidFirstContent = $value['tMidFirstContent'];

            $vMidSecondImg = $value['vMidSecondImg'];
            $vMidSecondTitle = $value['vMidSecondTitle'];
            $tMidSecondContent = $value['tMidSecondContent'];

            $vMidThirdImg = $value['vMidThirdImg'];
            $vMidThirdTitle = $value['vMidThirdTitle'];
            $tMidThirdContent = $value['tMidThirdContent'];

            $vThirdSectionImg1 = $value['vThirdSectionImg1'];
            $vThirdSectionImg2 = $value['vThirdSectionImg2'];
            $vThirdSectionImg3 = $value['vThirdSectionImg3'];
            $vThirdSectionRightTitle = $value['vThirdSectionRightTitle'];
            $tThirdSectionRightContent = $value['tThirdSectionRightContent'];

            $vThirdSectionAPPImgAPPStore = $value['vThirdSectionAPPImgAPPStore'];
            $vThirdSectionAPPImgPlayStore = $value['vThirdSectionAPPImgPlayStore'];

            $vLastSectionTitle = $value['vLastSectionTitle'];
            $vLastSectionImg = $value['vLastSectionImg'];

            $vLastSectionFirstTitle = $value['vLastSectionFirstTitle'];
            $tLastSectionFirstContent = $value['tLastSectionFirstContent'];
            $vLastSectionSecondTitle = $value['vLastSectionSecondTitle'];
            $tLastSectionSecondContent = $value['tLastSectionSecondContent'];
            $vLastSectionThirdTitle = $value['vLastSectionThirdTitle'];
            $tLastSectionThirdContent = $value['tLastSectionThirdContent'];
            $vLastSectionFourthTitle = $value['vLastSectionFourthTitle'];
            $tLastSectionFourthContent = $value['tLastSectionFourthContent'];
            $vLastSectionFifthTitle = $value['vLastSectionFifthTitle'];
            $tLastSectionFifthContent = $value['tLastSectionFifthContent'];
            $vLastSectionSixthTitle = $value['vLastSectionSixthTitle'];
            $tLastSectionSixthContent = $value['tLastSectionSixthContent'];

            $eStatus = $value['eStatus'];
            $title = $value['vTitle'];
        }
    }
}

//$catquery = "SELECT iVehicleCategoryId,vHomepageLogo,vCategory_EN FROM  `vehicle_category` WHERE iParentId = 0 and eStatus = 'Active' ORDER BY vCategory_EN";
$catquery = "SELECT iVehicleCategoryId,vHomepageLogo,vCategory_".$default_lang." as vCategory_EN FROM  `vehicle_category` WHERE iParentId = 0 and eStatus = 'Active' ORDER BY vCategory_".$default_lang."";
$vcatdata = $obj->MySQLSelect($catquery);

if ($_POST['submit'] == 'submit') {
    $required = 'required';
} else if ($_POST['catlogo'] == 'catlogo') {
    $required = '';
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title>Admin | Home Content <?= $action; ?></title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
        <? include_once('global_files.php'); ?>
        <!-- On OFF switch -->
        <!-- 	<link href="../assets/css/jquery-ui.css" rel="stylesheet" />
                <link rel="stylesheet" href="../assets/plugins/switch/static/stylesheets/bootstrap-switch.css" /> -->
        <style>
            .body-div.innersection {  box-shadow: -1px -2px 73px 2px #dedede;float: none;}
            .innerbg_image {width: auto; margin: 10px 0;  /* height: 150px; */  max-width: 50%;}
            .notes {	font-weight: 700;font-style: italic;}
        </style>
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
                            <h2><?= $action; ?> Home Content (<?php echo $title; ?>)</h2>
                            <a href="home_content_new.php" class="back_link">
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
                            <? } ?>
                            <form method="post" name="_home_content_form" id="_home_content_form" action="" enctype='multipart/form-data'>
                                <input type="hidden" name="id" value="<?= $id; ?>"/>
                                <input type="hidden" name="vCode" value="<?= $vCode; ?>">
                                <input type="hidden" name="previousLink" id="previousLink" value="<?php echo $previousLink; ?>"/>
                                <input type="hidden" name="backlink" id="backlink" value="home_content_new.php"/>
                                <!-- Start Home Banner area-->
                                <div class="body-div innersection">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Page Banner Image<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <? if ($vBannerBgImage != '') { ?>
                                                    <img src="<?= $tconfig["tsite_upload_home_page_images"] . "/" . $vBannerBgImage; ?>" class="innerbg_image"/>
                                                <? } ?>
                                                <input type="file" class="form-control" name="vBannerBgImage"  id="vBannerBgImage" accept=".png,.jpg,.jpeg,.gif">
                                                <br/>
                                                <span class="notes">[Note: For Better Resolution Upload only image size of 1300px * 600px.]</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Page Banner Left Image<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <? if ($vBannerLeftImg != '') { ?>
                                                    <img src="<?= $tconfig["tsite_upload_home_page_images"] . "/" . $vBannerLeftImg; ?>" class="innerbg_image"/>
                                                <? } ?>
                                                <input type="file" class="form-control" name="vBannerLeftImg"  id="vBannerLeftImg" accept=".png,.jpg,.jpeg,.gif">
                                                <br/>
                                                <span class="notes">[Note: For Better Resolution Upload only image size of 1300px * 600px.]</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Page Banner Title (One)<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="vBannerRightTitle"  id="vBannerRightTitle" value="<?= $vBannerRightTitle; ?>" placeholder="Home Page Banner Title (One)" <?= $required; ?>>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Page Banner Title (Second)<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="vBannerRightTitleSmall"  id="vBannerRightTitleSmall" value="<?= $vBannerRightTitleSmall; ?>" placeholder="Home Page Banner Title (Second)" <?= $required; ?>>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Page Banner Right Content<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-12">
                                                <textarea class="form-control ckeditor" rows="10" name="tBannerRightContent"  id="tBannerRightContent" <?= $required; ?>><?= $tBannerRightContent; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Home Banner area-->
                                <!-- Start Home icons area-->
                                <div class="body-div innersection">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label>Home Middle Section Title<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" name="vMidSectionTitle"  id="vMidSectionTitle" value="<?= $vMidSectionTitle; ?>" placeholder="Home Middle Section Title" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-10row" style="height:500px;overflow:scroll;">
                                            <label>Vehical Category Icon</label>
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Icon</th>
                                                        <th>Name</th>
                                                        <th>Upload</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                if (!empty($vcatdata)) {
                                                    for ($i = 0; $i < count($vcatdata); $i++) {
                                                        ?>
                                                        <tbody>
                                                        <td align="center">	
                                                            <?php if ($vcatdata[$i]['vHomepageLogo'] != '') { ?>
                                                                <img src="<?= $tconfig["tsite_upload_home_page_service_images"] . '/' . $vcatdata[$i]['vHomepageLogo'] ?>" style="width:35px;height:35px;">
                                                            <? } ?>														
                                                        </td>
                                                        <td>
                                                            <?= $vcatdata[$i]['vCategory_EN']; ?>
                                                        </td>
                                                        <td align="center">
                                                        <center><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?= $vcatdata[$i]['iVehicleCategoryId']; ?>">Upload</button></center>
                                                        </td>
                                                        </tbody>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Home icons area-->
                                <!-- Start Deliver aLl Section area-->
                                <? if (DELIVERALL == 'Yes') { ?>
                                    <div class="body-div innersection">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label>Deliver All Background Image<span class="red"> *</span></label>
                                                </div>
                                                <div class="col-lg-6">
                                                    <? if ($vDeliveryPartBgImg != '') { ?>
                                                        <img src="<?= $tconfig["tsite_upload_home_page_images"] . "/" . $vDeliveryPartBgImg; ?>" class="innerbg_image"/>
                                                    <? } ?>
                                                    <input type="file" class="form-control" name="vDeliveryPartBgImg"  id="vDeliveryPartBgImg" accept=".png,.jpg,.jpeg,.gif">
                                                    <br/>
                                                    <span class="notes">[Note: For Better Resolution Upload only image size of 1300px * 600px.]</span>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label>Deliver All Section Title<span class="red"> *</span></label>
                                                </div>
                                                <div class="col-lg-6">
                                                    <input type="text" class="form-control" name="vDeliveryPartTitle"  id="vDeliveryPartTitle" value="<?= $vDeliveryPartTitle; ?>" placeholder="DeliverAll Section Title" required>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label>Deliver All Section Content<span class="red"> *</span></label>
                                                </div>
                                                <div class="col-lg-12">
                                                    <textarea class="form-control ckeditor" rows="10" name="vDeliveryPartContent"  id="vDeliveryPartContent" required><?= $vDeliveryPartContent; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label>DeliverAll Right Image<span class="red"> *</span></label>
                                                </div>
                                                <div class="col-lg-6">
                                                    <? if ($vDeliveryPartImg != '') { ?>
                                                        <img src="<?= $tconfig["tsite_upload_home_page_images"] . "/" . $vDeliveryPartImg; ?>" class="innerbg_image"/>
                                                    <? } ?>
                                                    <input type="file" class="form-control" name="vDeliveryPartImg"  id="vDeliveryPartImg" accept=".png,.jpg,.jpeg,.gif">
                                                    <br/>
                                                    <span class="notes">[Note: For Better Resolution Upload only image size of 1300px * 600px.]</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                <? } ?>
                                <!-- End Delvier ALl  Middle Section area-->
                                <!-- Start Home Middle Section area-->
                                <div class="body-div innersection">
                                    <div class="form-group">
                                        <!-- First Section Start -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Middle First Box Image<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <? if ($vMidFirstImg != '') { ?>
                                                    <img src="<?= $tconfig["tsite_upload_home_page_images"] . "/" . $vMidFirstImg; ?>" class="innerbg_image"/>
                                                <? } ?>
                                                <input type="file" class="form-control" name="vMidFirstImg"  id="vMidFirstImg" accept=".png,.jpg,.jpeg,.gif">
                                                <br/>
                                                <span class="notes">[Note: For Better Resolution Upload only image size of 1300px * 600px.]</span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Middle First Box Title<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="vMidFirstTitle"  id="vMidFirstTitle" value="<?= $vMidFirstTitle; ?>" placeholder="Middle Section First Title" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Middle First Box Content<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-12">
                                                <textarea class="form-control ckeditor" rows="10" name="tMidFirstContent"  id="tMidFirstContent" required><?= $tMidFirstContent; ?></textarea>
                                            </div>
                                        </div>
                                        <!-- First Section End -->

                                        <!-- Second Section Start -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Middle Second Box Image<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <? if ($vMidSecondImg != '') { ?>
                                                    <img src="<?= $tconfig["tsite_upload_home_page_images"] . "/" . $vMidSecondImg; ?>" class="innerbg_image"/>
                                                <? } ?>
                                                <input type="file" class="form-control" name="vMidSecondImg"  id="vMidSecondImg" accept=".png,.jpg,.jpeg,.gif">
                                                <br/>
                                                <span class="notes">[Note: For Better Resolution Upload only image size of 1300px * 600px.]</span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Middle Second Box Title<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="vMidSecondTitle"  id="vMidSecondTitle" value="<?= $vMidSecondTitle; ?>" placeholder="Middle Second Box Title" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Middle Second Box Content<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-12">
                                                <textarea class="form-control ckeditor" rows="10" name="tMidSecondContent"  id="tMidSecondContent" required><?= $tMidSecondContent; ?></textarea>
                                            </div>
                                        </div>
                                        <!-- Second Section End -->

                                        <!-- Third Section Start -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Middle Third Box Image<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <? if ($vMidThirdImg != '') { ?>
                                                    <img src="<?= $tconfig["tsite_upload_home_page_images"] . "/" . $vMidThirdImg; ?>" class="innerbg_image"/>
                                                <? } ?>
                                                <input type="file" class="form-control" name="vMidThirdImg"  id="vMidThirdImg" accept=".png,.jpg,.jpeg,.gif">
                                                <br/>
                                                <span class="notes">[Note: For Better Resolution Upload only image size of 1300px * 600px.]</span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Middle Third Box Title<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="vMidThirdTitle"  id="vMidThirdTitle" value="<?= $vMidThirdTitle; ?>" placeholder="Middle Section Third Box Title" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Middle Third Box Content<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-12">
                                                <textarea class="form-control ckeditor" rows="10" name="tMidThirdContent"  id="tMidThirdContent" required><?= $tMidThirdContent; ?></textarea>
                                            </div>
                                        </div>

                                        <!-- Third Section End -->
                                    </div>
                                </div>
                                <!-- End Home Home Middle Section area-->
                                <!-- Start Home Third Section -->
                                <div class="body-div innersection">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Third Section Title<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="vThirdSectionRightTitle"  id="vThirdSectionRightTitle" value="<?= $vThirdSectionRightTitle; ?>" placeholder="Home Third Section Title" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Third Section Description<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-12">
                                                <textarea class="form-control ckeditor" rows="10" name="tThirdSectionRightContent"  id="tThirdSectionRightContent"  placeholder="Home Third Section Description" required><?= $tThirdSectionRightContent; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Third Section Image One<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <? if ($vThirdSectionImg1 != '') { ?>
                                                    <img src="<?= $tconfig["tsite_upload_home_page_images"] . "/" . $vThirdSectionImg1; ?>" class="innerbg_image"/>
                                                <? } ?>
                                                <input type="file" class="form-control" name="vThirdSectionImg1"  id="vThirdSectionImg1" accept=".png,.jpg,.jpeg,.gif">
                                                <br/>
                                                <span class="notes">[Note: For Better Resolution Upload only image size of 400px * 225px.]</span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Third Section Image Two<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <? if ($vThirdSectionImg2 != '') { ?>
                                                    <img src="<?= $tconfig["tsite_upload_home_page_images"] . "/" . $vThirdSectionImg2; ?>" class="innerbg_image"/>
                                                <? } ?>
                                                <input type="file" class="form-control" name="vThirdSectionImg2"  id="vThirdSectionImg2" accept=".png,.jpg,.jpeg,.gif">
                                                <br/>
                                                <span class="notes">[Note: For Better Resolution Upload only image size of 400px * 225px.]</span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Third Section Image Three<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <? if ($vThirdSectionImg3 != '') { ?>
                                                    <img src="<?= $tconfig["tsite_upload_home_page_images"] . "/" . $vThirdSectionImg3; ?>" class="innerbg_image"/>
                                                <? } ?>
                                                <input type="file" class="form-control" name="vThirdSectionImg3"  id="vThirdSectionImg3" accept=".png,.jpg,.jpeg,.gif">
                                                <br/>
                                                <span class="notes">[Note: For Better Resolution Upload only image size of 400px * 225px.]</span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Third Section APP Store Image<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <? if ($vThirdSectionAPPImgAPPStore != '') { ?>
                                                    <img src="<?= $tconfig["tsite_upload_home_page_images"] . "/" . $vThirdSectionAPPImgAPPStore; ?>" class="innerbg_image"/>
                                                <? } ?>
                                                <input type="file" class="form-control" name="vThirdSectionAPPImgAPPStore"  id="vThirdSectionAPPImgAPPStore" accept=".png,.jpg,.jpeg,.gif">
                                                <br/>
                                                <span class="notes">[Note: For Better Resolution Upload only image size of 400px * 225px.]</span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Third Section Play Store Image<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <? if ($vThirdSectionAPPImgPlayStore != '') { ?>
                                                    <img src="<?= $tconfig["tsite_upload_home_page_images"] . "/" . $vThirdSectionAPPImgPlayStore; ?>" class="innerbg_image"/>
                                                <? } ?>
                                                <input type="file" class="form-control" name="vThirdSectionAPPImgPlayStore"  id="vThirdSectionAPPImgPlayStore" accept=".png,.jpg,.jpeg,.gif">
                                                <br/>
                                                <span class="notes">[Note: For Better Resolution Upload only image size of 400px * 225px.]</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Home Third Section-->
                                <!-- Start Home Last Section-->
                                <div class="body-div innersection">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Last Section Title<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="vLastSectionTitle"  id="vLastSectionTitle" value="<?= $vLastSectionTitle; ?>" placeholder="Home Last Section Title" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Last Section Background Image<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <? if ($vLastSectionImg != '') { ?>
                                                    <img src="<?= $tconfig["tsite_upload_home_page_images"] . "/" . $vLastSectionImg; ?>" class="innerbg_image"/>
                                                <? } ?>
                                                <input type="file" class="form-control" name="vLastSectionImg"  id="vLastSectionImg" accept=".png,.jpg,.jpeg,.gif">
                                                <br/>
                                                <span class="notes">[Note: For Better Resolution Upload only image size of 1350px * 650px.]</span>
                                            </div>
                                        </div>

                                        <!-- Start First Section -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Last Section First Title<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="vLastSectionFirstTitle"  id="vLastSectionFirstTitle" value="<?= $vLastSectionFirstTitle; ?>" placeholder="Home Last Section First Title" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Last Section First Description<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-12">
                                                <textarea class="form-control ckeditor" rows="10" name="tLastSectionFirstContent"  id="tLastSectionFirstContent"  placeholder="Home Last Section First Description" required><?= $tLastSectionFirstContent; ?></textarea>
                                            </div>
                                        </div>

                                        <!-- End First Section -->
                                        <!-- Start Second Section -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Last Section Second Title<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="vLastSectionSecondTitle"  id="vLastSectionSecondTitle" value="<?= $vLastSectionSecondTitle; ?>" placeholder="Home Last Section Second Title" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Last Section Second Description<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-12">
                                                <textarea class="form-control ckeditor" rows="10" name="tLastSectionSecondContent"  id="tLastSectionSecondContent" required><?= $tLastSectionSecondContent; ?></textarea>
                                            </div>
                                        </div>

                                        <!-- End Second Section -->
                                        <!-- Start Third Section -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Last Section Third Title<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="vLastSectionThirdTitle"  id="vLastSectionThirdTitle" value="<?= $vLastSectionThirdTitle; ?>" placeholder="Home Last Section Third Title" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Last Section Third Description<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-12">
                                                <textarea class="form-control ckeditor" rows="10" name="tLastSectionThirdContent"  id="tLastSectionThirdContent" required><?= $tLastSectionThirdContent; ?></textarea>
                                            </div>
                                        </div>

                                        <!-- End Third Section -->
                                        <!-- Start Fourth Section -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Last Section Fourth Title<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="vLastSectionFourthTitle"  id="vLastSectionFourthTitle" value="<?= $vLastSectionFourthTitle; ?>" placeholder="Home Last Section Fourth Title" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Last Section Fourth Description<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-12">
                                                <textarea class="form-control ckeditor" rows="10" name="tLastSectionFourthContent"  id="tLastSectionFourthContent" required><?= $tLastSectionFourthContent; ?></textarea>
                                            </div>
                                        </div>

                                        <!-- End Fourth Section -->
                                        <!-- Start Fifth Section -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Last Section Fifth Title<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="vLastSectionFifthTitle"  id="vLastSectionFifthTitle" value="<?= $vLastSectionFifthTitle; ?>" placeholder="Home Last Section Fifth Title" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Last Section Fifth Description<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-12">
                                                <textarea class="form-control ckeditor" rows="10" name="tLastSectionFifthContent"  id="tLastSectionFifthContent" required><?= $tLastSectionFifthContent; ?></textarea>
                                            </div>
                                        </div>

                                        <!-- End Fifth Section -->
                                        <!-- Start Sixth Section -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Last Section Sixth Title<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="vLastSectionSixthTitle"  id="vLastSectionSixthTitle" value="<?= $vLastSectionSixthTitle; ?>" placeholder="Home Last Section Sixth Title" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label>Home Last Section Sixth Description<span class="red"> *</span></label>
                                            </div>
                                            <div class="col-lg-12">
                                                <textarea class="form-control ckeditor" rows="10" name="tLastSectionSixthContent"  id="tLastSectionSixthContent" required><?= $tLastSectionSixthContent; ?></textarea>
                                            </div>
                                        </div>
                                        <!-- End Sixth Section -->
                                    </div>
                                </div>

                                <!-- End Home Last Section area-->

                                <div class="row" style="display: none;">
                                    <div class="col-lg-12">
                                        <label>Status</label>
                                    </div>
                                    <div class="col-lg-6" >
                                        <div class="make-switch" data-on="success" data-off="warning">
                                            <input type="checkbox" name="eStatus" <?= ($id != '' && $eStatus == 'Inactive') ? '' : 'checked'; ?>/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php if (($action == 'Edit' && $userObj->hasPermission('edit-home-page-content'))) { ?>
                                            <input type="submit" class=" btn btn-default" name="submit" id="submit" value="<?= $action; ?> Home Content">
                                            <input type="reset" value="Reset" class="btn btn-default">
                                        <?php } ?>
                                        <a href="home_content_new.php" class="btn btn-default back_link">Cancel</a>
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
        <?php
        if (!empty($vcatdata)) {
            for ($i = 0; $i < count($vcatdata); $i++) {
                ?>
                <div class="modal fade" id="<?= $vcatdata[$i]['iVehicleCategoryId']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <form method="post" name="test" id="test" action="" enctype='multipart/form-data'>
                        <input type="hidden" name="aid" value="<?php echo $vcatdata[$i]['iVehicleCategoryId'] ?>" /> 
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">x</button>
                                    <h4 class="modal-title">upload vehical category icon</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <?php
                                            if (!empty($vcatdata[$i]['vHomepageLogo'])) {
                                                ?>
                                                <img src="<?= $tconfig["tsite_upload_home_page_service_images"] . '/' . $vcatdata[$i]['vHomepageLogo']; ?>" class="innerbg_image"/>
                                            <?php } ?>
                                        </div>
                                        <div class="col-lg-12">
                                            <span><b><?= $vcatdata[$i]['vCategory_EN']; ?></b></span>
                                        </div>
                                        <div class="clearfix">&nbsp;</div>
                                        <div class="col-lg-12">
                                            <input type="file" class="form-control" name="vHomepageLogo" id="vHomepageLogo" >
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="catlogo" value="catlogo" class="btn btn-primary">Save changes</button>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <?php
            }
        }
        ?>
        <? include_once('footer.php'); ?>
        <script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>
        <script src="../assets/plugins/ckeditor/ckeditor.js"></script>
        <script src="../assets/plugins/ckeditor/config.js"></script>
        <script>
            CKEDITOR.replace('ckeditor', {
                allowedContent: {
                    i: {
                        classes: 'fa*'
                    },
                    span: true
                }
            });
        </script>
        <script>
            $(document).ready(function () {
                var referrer;
                if ($("#previousLink").val() == "") {
                    referrer = document.referrer;
                } else {
                    referrer = $("#previousLink").val();
                }

                if (referrer == "") {
                    referrer = "home_content_new.php";
                } else {
                    $("#backlink").val(referrer);
                }
                $(".back_link").attr('href', referrer);
            });
            /**
             * This will reset the CKEDITOR using the input[type=reset] clicks.
             */
            $(function () {
                if (typeof CKEDITOR != 'undefined') {
                    $('form').on('reset', function (e) {
                        if ($(CKEDITOR.instances).length) {
                            for (var key in CKEDITOR.instances) {
                                var instance = CKEDITOR.instances[key];
                                if ($(instance.element.$).closest('form').attr('name') == $(e.target).attr('name')) {
                                    instance.setData(instance.element.$.defaultValue);
                                }
                            }
                        }
                    });
                }
            });

        </script>
    </body>
    <!-- END BODY-->
</html>