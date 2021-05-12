<?php

include_once("common.php");

$script="Home";
$meta_arr = $generalobj->getsettingSeo(7);
$meta1 = $generalobj->getStaticPage(23,$_SESSION['sess_lang']);
$meta2 = $generalobj->getStaticPage(24,$_SESSION['sess_lang']);
$meta3 = $generalobj->getStaticPage(25,$_SESSION['sess_lang']);
$meta4 = $generalobj->getStaticPage(26,$_SESSION['sess_lang']);
$homepage_banner = $generalobj->getStaticPage(19,$_SESSION['sess_lang']);
$meta5 = $generalobj->getStaticPage(27,$_SESSION['sess_lang']);
$meta6 = $generalobj->getStaticPage(28,$_SESSION['sess_lang']);
$image3 = $generalobj->getStaticPage(29,$_SESSION['sess_lang']);
$image4 = $generalobj->getStaticPage(30,$_SESSION['sess_lang']);
$meta7 = $generalobj->getStaticPage(32,$_SESSION['sess_lang']);

if(ONLYDELIVERALL == 'Yes'){
    $data = $generalobj->gethomeDataFood($_SESSION['sess_lang']);
    //$data = $generalobj->gethomeDataNew($_SESSION['sess_lang']);
} else {
    if($APP_TYPE == 'Ride-Delivery-UberX') {
        $data = $generalobj->gethomeDataNew($_SESSION['sess_lang']);
        //$data = $generalobj->gethomeData($_SESSION['sess_lang']);
    } else {
        $data = $generalobj->gethomeData($_SESSION['sess_lang']);
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densityDpi=device-dpi">
    <!--<title><?=$SITE_NAME?></title>-->
	<title><?php echo $meta_arr['meta_title'];?></title>
	<meta name="keywords" value="<?=$meta_arr['meta_keyword'];?>"/>
	<meta name="description" value="<?=$meta_arr['meta_desc'];?>"/>
    <!-- Default Top Script and css -->
    <?php include_once("top/top_script.php");?>
    <!-- End: Default Top Script and css-->
</head>
<body>
    <!-- home page -->
    <!-- home page -->
    <?php if($template!='taxishark'){?>
    <div id="main-uber-page">
    <?php } ?>  
        <!-- Left Menu --> 
    <?php  include_once("top/left_menu.php");?>
    <!-- End: Left Menu-->
        <!-- Top Menu -->
        <?php include_once("top/header_topbar.php");?>
        <!-- End: Top Menu-->
        <!-- First Section -->
		<?php include_once("top/header.php");?>
        <!-- End: First Section -->
        <?php include_once("top/home.php");?>
   
    <!-- home page end-->
    <!-- footer part -->
    <?php include_once('footer/footer_home.php');?>
    
    <div style="clear:both;"></div>
     <?php if($template!='taxishark'){?>
     </div>
     <?php } ?> 
    <!-- footer part end -->
<!-- Footer Script -->
<?php include_once('top/footer_script.php');?>
<?php
    //echo $APP_TYPE;
    //echo APP_TYPE;
    //echo $PACKAGE_TYPE;
?>
<!-- End: Footer Script -->
</body>
</html>
