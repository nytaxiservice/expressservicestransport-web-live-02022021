<?php
    include_once("common.php");		
    $generalobj->go_to_home();
    $action = isset($_GET['action'])?$_GET['action']:'';
	$script="Login Main";	
	$meta_arr = $generalobj->getsettingSeo(1);		
?>
<!DOCTYPE html>
<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!--<title><?=$SITE_NAME?> | Login Page</title>-->
    <title><?php echo $meta_arr['meta_title'];?></title>
    <!-- Default Top Script and css -->
    <?php include_once("top/top_script.php");?>
    <!-- End: Default Top Script and css-->
    <script>
        $(document).ready(function(){
            /**************this script for set equal height of element******************/
            $.fn.equalHeight = function() {
                var maxHeight = 0;
                return this.each(function(index, box) {
                    var boxHeight = $(box).height();
                    maxHeight = Math.max(maxHeight, boxHeight);
                }).height(maxHeight);
            };
            function EQUAL_HEIGHT(){
                $('[class*="sign-in"] p').equalHeight();
                $(window).resize(function(){
                    $('[class*="sign-in"] p').css('height','auto');
                    $('[class*="sign-in"] p').equalHeight();
                });
            }
            $(window).load(function(){
                EQUAL_HEIGHT();
            })

            $(document).on('click','ul.TABSWITCH li',function(){
                $('ul.TABSWITCH li').removeClass('active');
                $(this).addClass('active');
                var ID = $(this).attr('data-id');
                $('.login-holder-main [class*="sign-in"]').removeClass('active');
                $(document).find('#'+ID).addClass('active')
            })
        })
        
    </script>
</head>
<body>
  <div id="main-uber-page">
    <!-- Left Menu -->
    <?php include_once("top/left_menu.php");?>
    <!-- End: Left Menu-->
    <!-- home page -->
  
        <!-- Top Menu -->
        <?php include_once("top/header_topbar.php");?>
        <!-- End: Top Menu-->
        <!-- contact page-->
        <div class="page-contant">
            <div class="page-contant-inner">
                <h2 class="header-page"><?=$langage_lbl['LBL_SIGN_IN_SIGN_IN_TXT'];?></h2>
                <!-- login in page -->
                <div class="sign-in">
				<?php  if($template == 'taxishark' || ($template=='deliverall')){
						$tab_manage = '';
						if(empty($ENABLE_CORPORATE_PROFILE) ||  $ENABLE_CORPORATE_PROFILE=='No'){
							$tab_manage = 'three_tabs';
						}	
					?>
                    <ul class="TABSWITCH <?= $tab_manage;?>">
						<li class="active" data-id="RIDER"><?=$langage_lbl['LBL_SIGNIN_RIDER'];?></li>
                        <li data-id="DRIVER"><?=$langage_lbl['LBL_SIGNIN_DRIVER'];?></li>
						<li data-id="COMPANY"><?=$langage_lbl['LBL_COMPANY'];?></li>
						<?php if(PACKAGE_TYPE=='SHARK' && ($APP_TYPE == 'Ride-Delivery-UberX' || $APP_TYPE == 'Ride-Delivery' || $APP_TYPE == 'Ride' ) && $ENABLE_CORPORATE_PROFILE=='Yes'){?>
                        <li data-id="ORG"><?=$langage_lbl['LBL_ORGANIZATION'];?></li>
						<?php } ?>
                    </ul>
				<?php  } ?>		
				<?php  $msg1=$_REQUEST['msg1']; ?>
				
                            <? if (!empty($msg1)) { ?>
                                <div class="alert alert-danger alert-dismissable msgs_hide">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                     Invalid Tokens
                                </div><br/>
                            <? } ?>
					<div class="login-holder-main">
                        <div class="sign-in-driver" id="COMPANY">
                            <h3><?=$langage_lbl['LBL_COMPANY'];?></h3>
                                <p><?=$langage_lbl['LBL_SIGN_NOTE3'];?></p>
                                <span><a href="<?= $cjCompanyLogin; ?>"><?=$langage_lbl['LBL_SIGNIN_COMPNY_SIGNIN'];?><img src="assets/img/arrow-white-right.png" alt="" /></a></span>
                            </div>
                        <div class="sign-in-driver" id="DRIVER">
                            <h3><?=$langage_lbl['LBL_SIGNIN_DRIVER'];?></h3>
                            <p><?=$langage_lbl['LBL_SIGN_NOTE1'];?></p>
                            <?php if($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX'){?>
                                <span><a href="<?= $cjProviderLogin; ?>"><?= $langage_lbl['LBL_SIGNIN_DRIVERSIGNIN'];?><img src="assets/img/arrow-white-right.png" alt="" /></a></span>
                            <?php } else { ?>
                                <span><a href="<?= $cjDriverLogin; ?>"><?= $langage_lbl['LBL_SIGNIN_DRIVERSIGNIN'];?><img src="assets/img/arrow-white-right.png" alt="" /></a></span>
                            <?php }?>
                        </div>
                        <div class="sign-in-rider active" id="RIDER">
                            <h3><?=$langage_lbl['LBL_SIGNIN_RIDER'];?></h3>
                            <p><?=$langage_lbl['LBL_SIGN_NOTE2'];?></p>
                            <?php if($APP_TYPE == 'UberX' || $APP_TYPE == 'Ride-Delivery-UberX'){?>
                                <span><a href="<?= $cjUserLogin; ?>"><?=$langage_lbl['LBL_SIGNIN_RIDER_SIGNIN'];?><img src="assets/img/arrow-white-right.png" alt="" /></a></span>
                            <?php } else { ?>
                                <span><a href="<?= $cjRiderLogin; ?>"><?=$langage_lbl['LBL_SIGNIN_RIDER_SIGNIN'];?><img src="assets/img/arrow-white-right.png" alt="" /></a></span>
                            <?php }?>
                        </div>
					<?php if(($template == 'taxishark' || ($template=='deliverall')) && PACKAGE_TYPE=='SHARK' && $ENABLE_CORPORATE_PROFILE=='Yes'){?>
                        <div class="sign-in-rider" id="ORG">
                            <h3><?=$langage_lbl['LBL_ORGANIZATION'];?></h3>
                            <p><?=$langage_lbl['LBL_SIGN_NOTE4'];?></p>
                            
                                <span><a href="<?= $cjOrganizationLogin; ?>"><?=$langage_lbl['LBL_ORGANIZATION_SIGNIN'];?><img src="assets/img/arrow-white-right.png" alt="" /></a></span>
                        </div>
					<?php } ?>	
                    </div>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
    <!-- home page end-->
    <!-- footer part -->
    <?php include_once('footer/footer_home.php');?>
      <!-- End:contact page-->
      <div style="clear:both;"></div>
    </div>
    <!-- footer part end -->
    <!-- Footer Script -->
    <?php include_once('top/footer_script.php');?>
    <!-- End: Footer Script -->
</body>
</html>
