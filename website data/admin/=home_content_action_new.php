<?php
include_once('../common.php');

if(!isset($generalobjAdmin)){
	require_once(TPATH_CLASS."class.general_admin.php");
	$generalobjAdmin = new General_admin();
}
////$generalobjAdmin->check_member_login();

$id 		= isset($_REQUEST['id'])?$_REQUEST['id']:'';
$success	= isset($_REQUEST['success'])?$_REQUEST['success']:0;
$action 	= ($id != '')?'Edit':'Add';
$backlink = isset($_POST['backlink']) ? $_POST['backlink'] : '';

//$previousLink = isset($_POST['backlink']) ? $_POST['backlink'] : '';
$message_print_id = $id;
$vCode	 = isset($_POST['vCode']) ? $_POST['vCode'] : '';
$tbl_name 	= 'homecontent';
$script = 'homecontent';

$header_first_label = isset($_POST['header_first_label']) ? $_POST['header_first_label'] : '';
$third_sec_desc = isset($_POST['third_sec_desc']) ? $_POST['third_sec_desc'] : '';
$third_mid_desc_two1 = isset($_POST['third_mid_desc_two1']) ? $_POST['third_mid_desc_two1'] : '';
$third_mid_image_three1 = isset($_POST['third_mid_image_three1']) ? $_POST['third_mid_image_three1'] : '';
$home_banner_left_image = isset($_POST['home_banner_left_image']) ? $_POST['home_banner_left_image'] : '';

$header_second_label = isset($_POST['header_second_label']) ? $_POST['header_second_label'] : '';
$third_mid_desc_two = isset($_POST['third_mid_desc_two']) ? $_POST['third_mid_desc_two'] : '';
$home_banner_right_image = isset($_POST['home_banner_right_image']) ? $_POST['home_banner_right_image'] : '';

$third_sec_title = isset($_POST['third_sec_title']) ? $_POST['third_sec_title'] : '';
$third_mid_title_one = isset($_POST['third_mid_title_one']) ? $_POST['third_mid_title_one'] : '';
$third_mid_desc_three = isset($_POST['third_mid_desc_three']) ? $_POST['third_mid_desc_three'] : '';
$third_mid_image_two = isset($_POST['third_mid_image_two']) ? $_POST['third_mid_image_two'] : '';
$third_mid_title_two = isset($_POST['third_mid_title_two']) ? $_POST['third_mid_title_two'] : '';
$third_mid_desc_one = isset($_POST['third_mid_desc_one']) ? $_POST['third_mid_desc_one'] : '';

$third_mid_image_three = isset($_POST['third_mid_image_three']) ? $_POST['third_mid_image_three'] : '';

$third_mid_title_one1 = isset($_POST['third_mid_title_one1']) ? $_POST['third_mid_title_one1'] : '';
$taxi_app_right_desc = isset($_POST['taxi_app_right_desc']) ? $_POST['taxi_app_right_desc'] : '';
$taxi_app_bg_img = isset($_POST['taxi_app_bg_img']) ? $_POST['taxi_app_bg_img'] : '';

$mobile_app_right_title = isset($_POST['mobile_app_right_title']) ? $_POST['mobile_app_right_title'] : '';
$mobile_app_right_desc = isset($_POST['mobile_app_right_desc']) ? $_POST['mobile_app_right_desc'] : '';
$taxi_app_left_img = isset($_POST['taxi_app_left_img']) ? $_POST['taxi_app_left_img'] : '';

//$third_mid_title_three1 = isset($_POST['third_mid_title_three1']) ? $_POST['third_mid_title_three1'] : '';
$third_mid_title_three1 = '';
//$third_mid_title_three = isset($_POST['third_mid_title_three']) ? $_POST['third_mid_title_three'] : '';
$third_mid_title_three = '';
//$third_mid_desc_three1 = isset($_POST['third_mid_desc_three1']) ? $_POST['third_mid_desc_three1'] : '';
$third_mid_desc_three1 =  '';
//$mobile_app_bg_img1 = isset($_POST['mobile_app_bg_img1']) ? $_POST['mobile_app_bg_img1'] : '';
$mobile_app_bg_img1 = '';


//$third_mid_desc_one1 = isset($_POST['third_mid_desc_one1']) ? $_POST['third_mid_desc_one1'] : '';
$third_mid_desc_one1 = '';
$third_mid_title_two1 = isset($_POST['third_mid_title_two1']) ? $_POST['third_mid_title_two1'] : '';

//echo '<prE>'; /*print_R($_REQUEST);*/ print_r($_FILES);
$eStatus_check = isset($_POST['eStatus'])?$_POST['eStatus']:'off';
$eStatus = ($eStatus_check == 'on')?'Active':'Inactive';
if (isset($_POST['catlogo'])) {
    if (SITE_TYPE == 'Demo') {
        header("Location:home_content_new.php?id=" . $id . "&success=2");
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
if(isset($_POST['submit'])) {
	
	if(SITE_TYPE=='Demo') {
		//header("Location:home_action.php?success=2");
		header("Location:home_content_new.php?id=".$id."&success=2");
		exit;
	}
	$img_arr =  $_FILES;
	
	if(!empty($img_arr)) {
		foreach ($img_arr as $key => $value) {
			if(!empty($value['name'])) {
				$img_path = $tconfig["tsite_upload_page_images_panel"];
		        $temp_gallery = $img_path . '/';
		        $image_object = $value['tmp_name'];
		        $image_name = $value['name'];
		        $check_file_query = "SELECT ".$key." FROM homecontent where vCode='".$vCode."'";
		        $check_file = $obj->MySQLSelect($check_file_query);
/*		        if ($message_print_id != "") {
		        	$check_file = $img_path . '/home/' . $check_file[0][$key];
		        	if ($check_file != '' && file_exists($check_file[0][$key]) ) {
	                    @unlink($check_file);
	                }
		        }*/
		        $Photo_Gallery_folder = $img_path . "/home/";
				
		        if (!is_dir($Photo_Gallery_folder)) {
	                mkdir($Photo_Gallery_folder, 0777);
	            }

	            $img = $generalobj->fileupload_home($Photo_Gallery_folder,$image_object, $image_name, '', 'png,jpg,jpeg,gif',$vCode);
				
				if($img[2] == "1") {
		        	$_SESSION['success'] = '0';
        			$_SESSION['var_msg'] = $img[1];
					header("location:".$backlink);
		        }
		           
	            if(!empty($img[0])) {
		            $sql = "UPDATE ".$tbl_name." SET ".$key." = '" . $img[0] . "' WHERE `vCode` = '".$vCode."'";
		            $obj->sql_query($sql);
	        	}
			}
		}
	}
	$q = "INSERT INTO ";
	$where = '';

	if($id != '' ){
		$q = "UPDATE ";
		$where = " WHERE `vCode` = '".$vCode."'";
	}

	$query = $q ." `".$tbl_name."` SET
	`header_first_label` = '".$header_first_label."', 
	`third_sec_desc` = '".$third_sec_desc."', 
	`third_mid_desc_two1` = '".$third_mid_desc_two1."',  
	`third_mid_desc_one` = '".$third_mid_desc_one."', 
	`header_second_label` = '".$header_second_label."', 
	`third_mid_desc_two` = '".$third_mid_desc_two."', 
	`third_sec_title` = '".$third_sec_title."', 
	`third_mid_title_one` = '".$third_mid_title_one."',  
	`third_mid_desc_three` = '".$third_mid_desc_three."', 
	`third_mid_title_two` = '".$third_mid_title_two."', 
	`third_mid_title_three` = '".$third_mid_title_three."', 
	`third_mid_title_one1` = '".$third_mid_title_one1."',  
	`taxi_app_right_desc` = '".$taxi_app_right_desc."', 
	`mobile_app_right_title` = '".$mobile_app_right_title."', 
	`mobile_app_right_desc` = '".$mobile_app_right_desc."', 
	`third_mid_title_three1` = '".$third_mid_title_three1."',
	`third_mid_desc_three1` = '".$third_mid_desc_three1."',
	`third_mid_desc_one1` = '".$third_mid_desc_one1."',
	`third_mid_title_two1` = '".$third_mid_title_two1."'".$where; //die;
	
	$obj->sql_query($query);

	$id = ($id != '')?$id:$obj->GetInsertId();
	//header("Location:make_action.php?id=".$id.'&success=1');
	if ($action == "Add") {
        $_SESSION['success'] = '1';
        $_SESSION['var_msg'] = 'Home Content Insert Successfully.';
    } else {
        $_SESSION['success'] = '1';
        $_SESSION['var_msg'] = 'Home Content Updated Successfully.';
    }
	header("location:".$backlink);

}
// for Edit
if($action == 'Edit') {
	$sql = "SELECT hc.*,lm.vTitle FROM homecontent as hc LEFT JOIN language_master as lm on lm.vCode = hc.vCode  WHERE hc.id = '".$id."'";
	$db_data = $obj->MySQLSelect($sql);
	$vLabel = $id;
	if(count($db_data) > 0) {
		foreach($db_data as $key => $value) {
			$vCode	 = $value['vCode'];
			$header_first_label = $value['header_first_label'];
			$third_sec_desc = $value['third_sec_desc'];
			$third_mid_desc_two1 = $value['third_mid_desc_two1'];
			$third_mid_desc_one = $value['third_mid_desc_one'];
			$home_banner_left_image = $value['home_banner_left_image'];
			$header_second_label = $value['header_second_label'];
			$third_mid_desc_two = $value['third_mid_desc_two'];
			$home_banner_right_image = $value['home_banner_right_image'];
			$third_mid_image_three1 = $value['third_mid_image_three1'];
			$third_sec_title = $value['third_sec_title'];
			$third_mid_title_one = $value['third_mid_title_one'];
			$third_mid_desc_three = $value['third_mid_desc_three'];
			$third_mid_image_two = $value['third_mid_image_two'];
			$third_mid_title_two = $value['third_mid_title_two'];
			$third_mid_title_three = $value['third_mid_title_three'];
			$third_mid_image_three = $value['third_mid_image_three'];
			$third_mid_title_one1 = $value['third_mid_title_one1'];
			$taxi_app_right_desc = $value['taxi_app_right_desc'];
			$taxi_app_bg_img = $value['taxi_app_bg_img'];
			$mobile_app_right_title = $value['mobile_app_right_title'];
			$mobile_app_right_desc = $value['mobile_app_right_desc'];
			$taxi_app_left_img = $value['taxi_app_left_img'];
			$third_mid_title_three1 = $value['third_mid_title_three1'];
			$third_mid_desc_three1 = $value['third_mid_desc_three1'];
			$mobile_app_bg_img1 = $value['mobile_app_bg_img1'];
			$third_mid_desc_one1 = $value['third_mid_desc_one1'];
			$third_mid_title_two1 = $value['third_mid_title_two1'];
			$eStatus = $value['eStatus'];
			$title = $value['vTitle'];
		}
	}
}
$catquery = "SELECT iVehicleCategoryId,vHomepageLogo,vCategory_EN FROM  `vehicle_category` WHERE iParentId = 0 and eStatus = 'Active' ORDER BY vCategory_EN";
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
	<title>Admin | Home Content <?=$action;?></title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
	<? include_once('global_files.php');?>
	<!-- On OFF switch -->
	<link href="../assets/css/jquery-ui.css" rel="stylesheet" />
	<link rel="stylesheet" href="../assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />
	<style>
	.body-div.innersection {
	    box-shadow: -1px -2px 73px 2px #dedede;
	    float: none;
	}
	.innerbg_image {
		width:auto;margin:10px 0;height: 150px;
	}
	.notes {
		font-weight: 700;font-style: italic;
	}
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
						<h2><?=$action;?> Home Content (<?php echo $title;?>)</h2>
						<a href="home_content_new.php" class="back_link">
							<input type="button" value="Back to Listing" class="add-btn">
						</a>
					</div>
				</div>
				<hr />
				<div class="body-div">
					<div class="form-group">
						<? if($success == 1) { ?>
							<div class="alert alert-success alert-dismissable">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<?php echo $langage_lbl_admin['LBL_Record_Updated_successfully']; ?>
							</div><br/>
							<? }elseif ($success == 2) { ?>
								<div class="alert alert-danger alert-dismissable">
										 <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										 <?php echo $langage_lbl_admin['LBL_EDIT_DELETE_RECORD']; ?>
								</div><br/>
							<? }?>
						<form method="post" name="_home_content_form" id="_home_content_form" action="" enctype='multipart/form-data'>
							<input type="hidden" name="id" value="<?=$id;?>"/>
							<input type="hidden" name="vCode" value="<?= $vCode;?>">
							<input type="hidden" name="previousLink" id="previousLink" value="<?php echo $previousLink; ?>"/>
							<input type="hidden" name="backlink" id="backlink" value="home_content_new.php"/>

							<!-- Start Home Header area-->
							<div class="body-div innersection">
								<div class="form-group">
									<div class="row">
										<div class="col-lg-12">
											<label>Home First Section Title<span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<input type="text" class="form-control" name="header_first_label"  id="header_first_label" value="<?=$header_first_label;?>" placeholder="Home First Section Title" required>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<label>Home First Section Description<span class="red"> *</span></label>
										</div>
										<div class="col-lg-12">
											<textarea class="form-control ckeditor" rows="10" name="third_sec_desc"  id="third_sec_desc"  placeholder="Home First Section Description" required><?=$third_sec_desc;?></textarea>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<label>Home First Section First Button Description<span class="red"> *</span></label>
										</div>
										<div class="col-lg-12">
											<textarea class="form-control ckeditor" rows="10" name="third_mid_desc_two1"  id="third_mid_desc_two1"  placeholder="Home First Section First Button Description" required><?=$third_mid_desc_two1;?></textarea>
										</div>
									</div>
									<!--<div class="row">
										<div class="col-lg-12">
											<label>Home First Section Second Button Description<span class="red"> *</span></label>
										</div>
										<div class="col-lg-12">
											<textarea class="form-control ckeditor" rows="10" name="third_mid_desc_one"  id="third_mid_desc_one"  placeholder=">Home First Section Second Button Description" required><?=$third_mid_desc_one;?></textarea>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<label>Home First Left DeliveryAll Image<span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<? //if($third_mid_image_three1 != '') { ?>
											<img src="<?=$tconfig["tsite_upload_page_images"]."home/".$third_mid_image_three1;?>" class="innerbg_image"/>
											<? //} ?>
											<input type="file" class="form-control" name="third_mid_image_three1"  id="third_mid_image_three1" accept=".png,.jpg,.jpeg,.gif">
											<br/>
											<span class="notes">[Note: For Better Resolution Upload only image size of 1920px * 844px.]</span>
										</div>
									</div>-->
									<div class="row">
										<div class="col-lg-12">
											<label>Home First Section Banner Image<span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<? if($home_banner_left_image != '') { ?>
											<img src="<?=$tconfig["tsite_upload_page_images"]."home/".$home_banner_left_image;?>" class="innerbg_image"/>
											<? } ?>
											<input type="file" class="form-control" name="home_banner_left_image"  id="home_banner_left_image" accept=".png,.jpg,.jpeg,.gif">
											<br/>
											<span class="notes">[Note: For Better Resolution Upload only image size of 1920px * 587px.]</span>
										</div>
									</div>
								</div>
							</div>
							
							<div class="body-div innersection">
								<div class="form-group">
									<div class="row">
										<div class="col-lg-12">
											<label>Home Second Section Title<span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<input type="text" class="form-control" name="header_second_label"  id="header_second_label" value="<?=$header_second_label;?>" placeholder="Home Second Section Title" required>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<label>Home Second Section Description<span class="red"> *</span></label>
										</div>
										<div class="col-lg-12">
											<textarea class="form-control ckeditor" rows="10" name="third_mid_desc_two"  id="third_mid_desc_two"  placeholder="Home Second Section Description" required><?=$third_mid_desc_two;?></textarea>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<label>Home Second Section Right Banner Image<span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<? if($home_banner_right_image != '') { ?>
											<img src="<?=$tconfig["tsite_upload_page_images"]."home/".$home_banner_right_image;?>" class="innerbg_image"/>
											<? } ?>
											<input type="file" class="form-control" name="home_banner_right_image"  id="home_banner_right_image" accept=".png,.jpg,.jpeg,.gif">
											<br/>
											<span class="notes">[Note: For Better Resolution Upload only image size of 493px * 740px.]</span>
										</div>
									</div>
								</div>
							</div>
							
							<div class="body-div innersection">
								<div class="form-group">
									<div class="row">
										<div class="col-lg-12">
											<label>Home Third Section Title<span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<input type="text" class="form-control" name="third_sec_title"  id="third_sec_title" value="<?=$third_sec_title;?>" placeholder="Home Third Section Title" required>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<label>Home Third Section Second Text<span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<input type="text" class="form-control" name="third_mid_title_one"  id="third_mid_title_one" value="<?=$third_mid_title_one;?>" placeholder="Home Third Section Second Text" required>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<label>Home Third Section Description<span class="red"> *</span></label>
										</div>
										<div class="col-lg-12">
											<textarea class="form-control ckeditor" rows="10" name="third_mid_desc_three"  id="third_mid_desc_three"  placeholder="Home Third Section Description" required><?=$third_mid_desc_three;?></textarea>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<label>Home Third Section Banner Image<span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<? if($third_mid_image_two != '') { ?>
											<img src="<?=$tconfig["tsite_upload_page_images"]."home/".$third_mid_image_two;?>" class="innerbg_image"/>
											<? } ?>
											<input type="file" class="form-control" name="third_mid_image_two"  id="third_mid_image_two" accept=".png,.jpg,.jpeg,.gif">
											<br/>
											<span class="notes">[Note: For Better Resolution Upload only image size of 1920px * 405px.]</span>
										</div>
									</div>
								</div>
							</div>
							
							<div class="body-div innersection">
								<div class="form-group">
									<div class="row">
										<div class="col-lg-12">
											<label>Home Forth Section First Text<span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<input type="text" class="form-control" name="third_mid_title_two"  id="third_mid_title_two" value="<?=$third_mid_title_two;?>" placeholder="Home Forth Section First Text" required>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<label>Home Forth Section Second Text<span class="red"> *</span></label>
										</div>
										<div class="col-lg-12">
											<textarea class="form-control ckeditor" rows="10" name="third_mid_desc_one"  id="third_mid_desc_one"  placeholder=">Home First Section Second Button Description" required><?=$third_mid_desc_one;?></textarea>
										</div>
									</div>
									<!--<div class="row">
										<div class="col-lg-12">
											<label>Home Forth Section Second Text<span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<input type="text" class="form-control" name="third_mid_title_three"  id="third_mid_title_three" value="<?=$third_mid_title_three;?>" placeholder="Home Forth Section Second Text" required>
										</div>
									</div>-->
									<div class="row">
										<div class="col-lg-12">
											<label>Home Forth Section Image<span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<? if($third_mid_image_three != '') { ?>
											<img src="<?=$tconfig["tsite_upload_page_images"]."home/".$third_mid_image_three;?>" class="innerbg_image" />
											<? } ?>
											<input type="file" class="form-control" name="third_mid_image_three"  id="third_mid_image_three" accept=".png,.jpg,.jpeg,.gif">
											<br/>
											<span class="notes">[Note: For Better Resolution Upload only image size of 564px * 570px.]</span>
										</div>
									</div>
								</div>
							</div>
							<div class="body-div innersection">
								<div class="form-group">
									<div class="row">
										<div class="col-lg-12">
											<label>Home Fifth Section Title<span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<input type="text" class="form-control" name="third_mid_title_one1"  id="third_mid_title_one1" value="<?=$third_mid_title_one1;?>" placeholder="Home Fifth Section Title" required>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<label>Home Fifth Section Description<span class="red"> *</span></label>
										</div>
										<div class="col-lg-12">
											<textarea class="form-control ckeditor" rows="10" name="taxi_app_right_desc"  id="taxi_app_right_desc"  placeholder="Home Fifth Section Description" required><?=$taxi_app_right_desc;?></textarea>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<label>Home Fifth Section Banner Image<span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<? if($taxi_app_bg_img != '') { ?>
											<img src="<?=$tconfig["tsite_upload_page_images"]."home/".$taxi_app_bg_img;?>" class="innerbg_image"/>
											<? } ?>
											<input type="file" class="form-control" name="taxi_app_bg_img"  id="taxi_app_bg_img" accept=".png,.jpg,.jpeg,.gif">
											<br/>
											<span class="notes">[Note: For Better Resolution Upload only image size of 609px * 547px.]</span>
										</div>
									</div>
								</div>
							</div>
							
							<div class="body-div innersection">
								<div class="form-group">
									<div class="row">
										<div class="col-lg-12">
											<label>Home Sixth Section Title<span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<input type="text" class="form-control" name="mobile_app_right_title"  id="mobile_app_right_title" value="<?=$mobile_app_right_title;?>" placeholder="Home Sixth Section Title" required>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<label>Home Sixth Section Description<span class="red"> *</span></label>
										</div>
										<div class="col-lg-12">
											<textarea class="form-control ckeditor" rows="10" name="mobile_app_right_desc"  id="mobile_app_right_desc"  placeholder="Home Sixth Section Description" required><?=$mobile_app_right_desc;?></textarea>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<label>Home Sixth Section Banner Image<span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<? if($taxi_app_left_img != '') { ?>
											<img src="<?=$tconfig["tsite_upload_page_images"]."home/".$taxi_app_left_img;?>" class="innerbg_image"/>
											<? } ?>
											<input type="file" class="form-control" name="taxi_app_left_img"  id="taxi_app_left_img" accept=".png,.jpg,.jpeg,.gif">
											<br/>
											<span class="notes">[Note: For Better Resolution Upload only image size of 534px * 275px.]</span>
										</div>
									</div>
								</div>
							</div>
							<?php /*?>
							<div class="body-div innersection">
								<div class="form-group">
									<div class="row">
										<div class="col-lg-12">
											<label>Home Seventh Section Title<span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<input type="text" class="form-control" name="third_mid_title_three1"  id="third_mid_title_three1" value="<?=$third_mid_title_three1;?>" placeholder="Home Seventh Section Title" required>
										</div>
									</div>
									<!--<div class="row">
										<div class="col-lg-12">
											<label>Home Seventh Section Second Text<span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<input type="text" class="form-control" name="third_mid_title_three"  id="third_mid_title_three" value="<?=$third_mid_title_three;?>" placeholder="Home Forth Section Second Text" required>
										</div>
									</div>-->
									<div class="row">
										<div class="col-lg-12">
											<label>Home Seventh Section Description<span class="red"> *</span></label>
										</div>
										<div class="col-lg-12">
											<textarea class="form-control ckeditor" rows="10" name="third_mid_desc_three1"  id="third_mid_desc_three1"  placeholder="Home Seventh Section Description" required><?=$third_mid_desc_three1;?></textarea>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<label>Home Seventh Section Banner Image<span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<? if($mobile_app_bg_img1 != '') { ?>
											<img src="<?=$tconfig["tsite_upload_page_images"]."home/".$mobile_app_bg_img1;?>" class="innerbg_image"/>
											<? } ?>
											<input type="file" class="form-control" name="mobile_app_bg_img1"  id="mobile_app_bg_img1" accept=".png,.jpg,.jpeg,.gif">
											<br/>
											<span class="notes">[Note: For Better Resolution Upload only image size of 1920px * 427px.]</span>
										</div>
									</div>
								</div>
							</div>
							<?php */?>
							<!-- Start Home icons area-->
                                <div class="body-div innersection">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label>Home Eighth Section Title<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" name="third_mid_title_two1"  id="third_mid_title_two1" value="<?= $third_mid_title_two1; ?>" placeholder="Home Middle Section Title" required>
                                        </div>
                                    </div>
									<!-- <div class="row">
										<div class="col-lg-12">
											<label>Home Eighth Section Description<span class="red"> *</span></label>
										</div>
										<div class="col-lg-12">
											<textarea class="form-control ckeditor" rows="10" name="third_mid_desc_one1"  id="third_mid_desc_one1"  placeholder="Home Seventh Section Description" required><?=$third_mid_desc_one1;?></textarea>
										</div>
									</div> -->
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
							<!-- End Home Header area-->
							<div class="row" style="display: none;">
								<div class="col-lg-12">
									<label>Status</label>
								</div>
								<div class="col-lg-6" >
									<div class="make-switch" data-on="success" data-off="warning">
										<input type="checkbox" name="eStatus" <?=($id != '' && $eStatus == 'Inactive')?'':'checked';?>/>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<input type="submit" class=" btn btn-default" name="submit" id="submit" value="<?=$action;?> Home Content">
									 <input type="reset" value="Reset" class="btn btn-default">
<!-- 									<a href="javascript:void(0);" onclick="reset_form('_home_content_form');" class="btn btn-default">Reset</a> -->
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
<? include_once('footer.php');?>
<script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>
<script src="../assets/plugins/ckeditor/ckeditor.js"></script>
<script src="../assets/plugins/ckeditor/config.js"></script>
<script>
	CKEDITOR.replace( 'ckeditor',{
		allowedContent : {
			i:{
				classes:'fa*'
			},
			span: true
		}
	} );
</script>
<script>
$(document).ready(function() {
	var referrer;
	if($("#previousLink").val() == "" ){ //alert('pre1');
		referrer =  document.referrer;
		// alert(referrer);
	}else { //alert('pre2');
		referrer = $("#previousLink").val();
	}

	if(referrer == "") {
		referrer = "home_content_new.php";
	}else { //alert('hi');
		//$("#backlink").val(referrer);
		referrer = "home_content_new.php";
		// alert($("#backlink").val(referrer));
	}
	$(".back_link").attr('href',referrer); 
	//alert($(".back_link").attr('href',referrer));	
});
/**
 * This will reset the CKEDITOR using the input[type=reset] clicks.
 */
$(function() {
    if (typeof CKEDITOR != 'undefined') {
        $('form').on('reset', function(e) {
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