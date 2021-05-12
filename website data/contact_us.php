<?
include_once("common.php");
$meta_arr = $generalobj->getsettingSeo(2);
$sql = "SELECT * from language_master where eStatus = 'Active'";
$db_lang = $obj->MySQLSelect($sql);
$sql = "SELECT * from country where eStatus = 'Active'";
$db_code = $obj->MySQLSelect($sql);
//echo "<pre>";print_r($db_lang);
$script = "Contact Us";
if (isset($_POST['SUBMIT']) && $_POST['SUBMIT'] != "") {
    if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {

        $valiedRecaptch = $generalobj->checkRecaptchValied($GOOGLE_CAPTCHA_SECRET_KEY, $_POST['g-recaptcha-response']);
        if ($valiedRecaptch) {
            $Data['vFirstName'] = stripcslashes($_POST['vName']);
            $Data['vLastName'] = stripcslashes($_POST['vLastName']);
            $Data['eSubject'] = stripcslashes($_POST['vSubject']);
            $Data['tSubject'] = nl2br(stripcslashes($_POST['vDetail']));
            $Data['vEmail'] = $_POST['vEmail'];
            $Data['cellno'] = $_POST['vPhone'];
            $return = $generalobj->send_email_user("CONTACTUS", $Data);


            if ($return) {
                $success = 1;
                $var_msg = $langage_lbl['LBL_SENT_CONTACT_QUERY_SUCCESS_TXT'];
            } else {
                $error = 1;
                $var_msg = $langage_lbl['LBL_ERROR_OCCURED'];
            }
        } else {
            $error = 1;
            $var_msg = $langage_lbl['LBL_CAPTCHA_MATCH_MSG'];
        }
    } else {
        $error = 1;
        $var_msg = 'Please check reCAPTCHA box.';
    }
}
$userid = $_SESSION['sess_iUserId'];
$rider_query = "SELECT * from register_user where iUserId = $userid";

$rider_data = $obj->MySQLSelect($rider_query);
$driver_query = "SELECT * from register_driver where iDriverId = $userid";
$driver_data = $obj->MySQLSelect($driver_query);
//print_r($_SESSION);exit;
?>
<!DOCTYPE html>
<html lang="en" dir="<?= (isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "") ? $_SESSION['eDirectionCode'] : 'ltr'; ?>">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">

        <title><?php echo $meta_arr['meta_title']; ?></title>
        <meta name="keywords" value="<?= $meta_arr['meta_keyword']; ?>"/>
        <meta name="description" value="<?= $meta_arr['meta_desc']; ?>"/>
        <!-- Default Top Script and css -->
        <?php include_once("top/top_script.php"); ?>
        <?php include_once("top/validation.php"); ?>
        <!-- End: Default Top Script and css-->
        <style type="text/css">
            .contact-form span strong.captcha-signup .contact-input {
                width: 60px !important; 
            }
            .contact-form span strong.captcha-signup span{
                margin : 0 0 15px !important;
                padding : 0px;
                float : left;
                width : 100%;
            }

        </style>
    </head> 
    <body>
        <!-- home page -->
        <div id="main-uber-page">
            <!-- Left Menu -->
            <?php include_once("top/left_menu.php"); ?>
            <!-- End: Left Menu-->
            <!-- Top Menu -->
            <?php include_once("top/header_topbar.php"); ?>
            <!-- End: Top Menu-->
            <!-- contact page-->
            <div class="page-contant">
                <div class="page-contant-inner">
                    <h2 class="header-page-ab"><?= $langage_lbl['LBL_CONTACT_US_HEADER_TXT']; ?>

                    </h2>

                    <p class="head-p"><?= $langage_lbl['LBL_WELCOME_TO']; ?> <?= $SITE_NAME ?>, <?= $langage_lbl['LBL_CONTACT_US_SECOND_TXT']; ?>.</p>
                    <!-- contact page -->
                    <div style="clear:both;"></div>
                    <?php if ($success == 1) { ?>
                        <div class="alert alert-success alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button> 
                            <?= $var_msg ?>
                        </div>
                        <?php
                    } else if ($error == 1) {
                        ?>
                        <div class="alert alert-danger alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button> 
                            <?= $var_msg ?>
                        </div>
                    <?php } ?>
                    <div style="clear:both;"></div>
                    <form name="frmsignup" id="frmsignup" method="post" action="">
                        <div class="contact-form"> 
                            <?php
                            if ($_SESSION['sess_user'] == 'rider') {
                                foreach ($rider_data as $rider_datas) {
                                    ?>
                                    <b>
                                        <span class="newrow"><strong><input type="text" name="vName" placeholder="<?= $langage_lbl['LBL_CONTECT_US_FIRST_NAME_HEADER_TXT']; ?>" class="contact-input " value="<?php echo (isset($rider_datas['vName']) ? $rider_datas['vName'] : '') ?>" /></strong></span>
                                        <span class="newrow"><strong><input type="text" name="vLastName" placeholder="<?= $langage_lbl['LBL_CONTECT_US_LAST_NAME_HEADER_TXT']; ?>" class="contact-input " value="<?php echo (isset($rider_datas['vLastName']) ? $rider_datas['vLastName'] : '') ?>" /></strong></span>
                                        <span class="newrow"><strong><input type="text" placeholder="<?= $langage_lbl['LBL_CONTECT_US_EMAIL_LBL_TXT']; ?>" name="vEmail" value="<?php echo (isset($rider_datas['vEmail']) ? $rider_datas['vEmail'] : '') ?>" autocomplete="off" class="contact-input "/></strong></span>
                                        <span class="newrow"><strong><input type="text" placeholder="<?= $langage_lbl['LBL_CONTECT_US_777-777-7777'] ?>"value="<?php echo (isset($rider_datas['vPhone']) ? $rider_datas['vPhone'] : '') ?>" name="vPhone" class="contact-input " /></strong></span>
                                    </b>
                                <?php } ?>
                            <?php } else if ($_SESSION['sess_user'] == 'driver') { ?>
                                <?php foreach ($driver_data as $driver_datas) { ?>

                                    <b>
                                        <span class="newrow"><strong><input type="text" name="vName" placeholder="<?= $langage_lbl['LBL_CONTECT_US_FIRST_NAME_HEADER_TXT']; ?>" class="contact-input " value="<?php echo (isset($driver_datas['vName']) ? $driver_datas['vName'] : '') ?>" /></strong></span>
                                        <span class="newrow"><strong><input type="text" name="vLastName" placeholder="<?= $langage_lbl['LBL_CONTECT_US_LAST_NAME_HEADER_TXT']; ?>" class="contact-input " value="<?php echo (isset($driver_datas['vLastName']) ? $driver_datas['vLastName'] : '') ?>" /></strong></span>
                                        <span class="newrow"><strong><input type="text" placeholder="<?= $langage_lbl['LBL_CONTECT_US_EMAIL_LBL_TXT']; ?>" name="vEmail" value="<?php echo (isset($driver_datas['vEmail']) ? $driver_datas['vEmail'] : '') ?>" autocomplete="off" class="contact-input "/></strong></span>
                                        <span class="newrow"><strong><input type="text" placeholder="<?= $langage_lbl['LBL_CONTECT_US_777-777-7777'] ?>"value="<?php echo (isset($driver_datas['vPhone']) ? $driver_datas['vPhone'] : '') ?>" name="vPhone" class="contact-input " /></strong></span>
                                    </b>
                                <?php } ?>
                            <?php } else { ?>
                                <b>
                                    <span class="newrow"><strong><input type="text" name="vName" placeholder="<?= $langage_lbl['LBL_CONTECT_US_FIRST_NAME_HEADER_TXT']; ?>" class="contact-input " value="" /></strong></span>
                                    <span class="newrow"><strong><input type="text" name="vLastName" placeholder="<?= $langage_lbl['LBL_CONTECT_US_LAST_NAME_HEADER_TXT']; ?>" class="contact-input " value="" /></strong></span>
                                    <span class="newrow"><strong><input type="text" placeholder="<?= $langage_lbl['LBL_CONTECT_US_EMAIL_LBL_TXT']; ?>" name="vEmail" value="" autocomplete="off" class="contact-input "/></strong></span>
                                    <span class="newrow"><strong><input type="text" placeholder="<?= $langage_lbl['LBL_CONTECT_US_777-777-7777'] ?>" name="vPhone" class="contact-input " /></strong></span>
                                     <span class="newrow">
                            
                             <strong class="captcha-signup">
                                        <?php include_once("recaptcha.php"); ?>
                               <span id="recaptcha-msg" style="display: none;" class="error">This field is required.</span>
                             </strong>
                           
                        </span>
                                </b>
                            <?php } ?>
                            <b>
                                <span class="newrow"><strong><input type="text" name="vSubject" placeholder="<?= $langage_lbl['LBL_ADD_SUBJECT_HINT_CONTACT_TXT']; ?>" class="contact-input " /></strong></span>
                                <span class="newrow"><strong><textarea cols="61" rows="5" placeholder="<?= $langage_lbl['LBL_ENTER_DETAILS_TXT']; ?>" name="vDetail" class="contact-textarea "></textarea></strong></span>
                             
                        </b> 
                        <b>
                            <input type="submit" class="submit-but" value="<?= $langage_lbl['LBL_BTN_CONTECT_US_SUBMIT_TXT']; ?>" name="SUBMIT" />
                        </b> 
                    </div>
                </form>
                <div style="clear:both;"></div>
            </div>
        </div>
    <!-- footer part -->
            <?php include_once('footer/footer_home.php'); ?>
    <!-- footer part end -->
            <!-- End:contact page-->
            <div style="clear:both;"></div>
    </div>
    <!-- home page end-->
    <!-- Footer Script -->
        <?php
        include_once('top/footer_script.php');
        $lang = get_langcode($_SESSION['sess_lang']);
        ?>
	<script type="text/javascript" src="assets/js/validation/jquery.validate.min.js" ></script>
        <?php if ($lang != 'en') { ?>
                  <script type="text/javascript" src="assets/js/validation/localization/messages_<?= $lang; ?>.js" ></script>
        <?php } ?>
	<script type="text/javascript" src="assets/js/validation/additional-methods.js" ></script>
    <script type="text/javascript">

            $('#frmsignup').validate({
                ignore: 'input[type=hidden]',
                errorClass: 'help-block',
                errorElement: 'span',
                errorPlacement: function (error, e) {
                    e.parents('.newrow > strong').append(error);
                },
                highlight: function (e) {
                    $(e).closest('.newrow').removeClass('has-success has-error').addClass('has-error');
                    $(e).closest('.newrow strong input').addClass('has-shadow-error');
                    $(e).closest('.help-block').remove();
                },
                success: function (e) {
                    //e.prev('input').removeClass('has-shadow-error');
                    e.parent().find('input').removeClass('has-shadow-error');
                    e.closest('.newrow').removeClass('has-success has-error');
                    e.closest('.help-block').remove();
                    e.closest('.help-inline').remove();
                },
                rules: {
                    vName: {required: true},
                    vLastName: {required: true},
                    vSubject: {required: true},
                    vDetail: {required: true},
                    vEmail: {required: true, email: true},
                    vPassword: {required: true, minlength: 6},
                    vPhone: {required: true, phonevalidate: true},
                    POST_CAPTCHA: {required: true, remote: {
                            url: 'ajax_captcha_new.php',
                            type: "post",
                            data: {iDriverId: ''},
                        }},
                },
                messages: {
                    vPhone: {phonevalidate: '<?= addslashes($langage_lbl['LBL_PHONE_VALID_MSG']); ?>'},
                    POST_CAPTCHA: {remote: '<?= addslashes($langage_lbl['LBL_CAPTCHA_MATCH_MSG']); ?>'}
                }
            });

                        </script>
	
	
	
	
    <script>
            function submit_form()
            {
                if (validatrix()) {
                    //alert("Submit Form");
                    document.frmsignup.submit();
                } else {
                    console.log("Some fields are required");
                    return false;
                }
                return false; //Prevent form submition
            }
                    </script>
    <script type="text/javascript">
            function validate_email(id)
            {
                var eml = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                result = eml.test(id);
                if (result == true)
                {
                    $('#emailCheck').html('<i class="icon icon-ok alert-success alert"> Valid</i>');
                    $('input[type="submit"]').removeAttr('disabled');
                } else
                {
                    $('#emailCheck').html('<i class="icon icon-remove alert-danger alert"> Enter Proper Email</i>');
                    $('input[type="submit"]').attr('disabled', 'disabled');
                    return false;
                }
            }
            function changeCode(id)
            {

                var request = $.ajax({
                    type: "POST",
                    url: 'change_code.php',
                    data: 'id=' + id,
                    success: function (data)
                    {
                        document.getElementById("code").value = data;
                        //window.location = 'profile.php';
                    }
                });
            }

            function validate_mobile(mobile)
            {

                var eml = /^[0-9]+$/;
                result = eml.test(mobile);
                if (result == true)
                {
                    $('#mobileCheck').html('<i class="icon icon-ok alert-success alert"> Valid</i>');
                    $('input[type="submit"]').removeAttr('disabled');
                } else
                {
                    $('#mobileCheck').html('<i class="icon icon-remove alert-danger alert"> Enter Proper Mobile No</i>');
                    $('input[type="submit"]').attr('disabled', 'disabled');
                    return false;
                }
            }
            function refreshCaptcha()
            {
                var img = document.images['captchaimg'];
                img.src = img.src.substring(0, img.src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000;
                $('#POST_CAPTCHA').val('');
            }




                    </script>
    <!-- End: Footer Script -->
	<!-- Powered by cubejekshark.com -->
</body>
</html>
