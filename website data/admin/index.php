<?php
include_once('../common.php');
include_once('../include/config.php');
//echo '<pre>';print_r($tconfig);die;
unset($_SESSION['login_redirect_url']);
$tsiteUrl = $tconfig["tsite_url"];
$userType = isset($_REQUEST['userType']) ? $_REQUEST['userType'] : 'admin';
$data = $obj->MySQLSelect('SELECT * FROM setup_info');
if (isset($data[0]['eEnableHotel']) && $data[0]['eEnableHotel'] == "No" && strtolower($userType) == "hotel") {
    header('Location:' . $tsiteUrl . 'admin');
    exit();
}
$filePanel = "Admin"; // Used In setup_validation.php File For Include Js File By HJ On 22-04-2019
if ($_SERVER["HTTP_HOST"] == "192.168.1.131" || $_SERVER["HTTP_HOST"] == "192.168.1.141") {
    //$data = $obj->MySQLSelect('SELECT * FROM setup_info');
    if (isset($data[0]['iSetupId']) && $data[0]['iSetupId'] > 0) {
        include_once("../setup_validation.php");
        if ($errorcountsystemvalidation > 0) {
            exit();
        }
    }
}
if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->go_to_home();
$logo = "logo.png";
if ($host_system == 'cubetaxishark' || $host_system == 'cubetaxi5plus') {
    $logo = "logo-taxi.png";
} else if ($host_system == 'cubedelivery') {
    $logo = "logo_delivery.png";
}
$activeTab = "1";
$activeTabId = "super001";
if ($userType == "billing") {
    $activeTab = "3";
    $activeTabId = "billing001";
}

if (file_exists($tconfig["tpanel_path"] . $logogpath . $logo)) {
    $admin_logo = $tsiteUrl . $logogpath . $logo;
} else {
    $admin_logo = $tsiteUrl . '' . SITE_ADMIN_URL . '/images/' . $logo;
}
$fav_icon_image = 'favicon.ico';
if (file_exists($tconfig["tpanel_path"] . $logogpath . $fav_icon_image)) {
    $fav_icon_image = $tsiteUrl . $logogpath . $fav_icon_image;
} else {
    $fav_icon_image = $tsiteUrl . $fav_icon_image;
}
?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="UTF-8" />
        <title>Admin | Login Page</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <link rel="icon" href="<?= $fav_icon_image; ?>" type="image/x-icon">
        <link rel="stylesheet" href="<?= $tconfig["tsite_url_main_admin"]; ?>css/bootstrap.css" />
        <link rel="stylesheet" href="<?= $tconfig["tsite_url_main_admin"]; ?>css/login.css" />
        <link rel="stylesheet" href="<?= $tconfig["tsite_url_main_admin"]; ?>css/style.css" />
        <link rel="stylesheet" href="<?= $tsiteUrl; ?>assets/css/animate/animate.min.css" />
        <link rel="stylesheet" href="<?= $tsiteUrl; ?>assets/plugins/magic/magic.css" />
        <link rel="stylesheet" href="<?= $tconfig["tsite_url_main_admin"]; ?>css/font-awesome.css" />
        <link rel="stylesheet" href="<?= $tsiteUrl; ?>assets/plugins/font-awesome-4.6.3/css/font-awesome.min.css" />
    </head>
    <!-- END HEAD -->
    <!-- BEGIN BODY -->
    <body class="nobg loginPage">

        <input type="hidden" name="hdf_class" id="hdf_class" value="<?php echo $_SESSION['edita']; ?>">
        <div class="topNav">
            <div class="userNav">
                <ul>
                    <li><a href="<?= $tsiteUrl; ?>index.php" title=""><i class="icon-reply"></i><span>Main website</span></a></li>
                    <li><a href="<?= $tsiteUrl . $cjRiderLogin; ?>" title=""><i class="icon-user"></i><span><?= $langage_lbl_admin['LBL_RIDER'] ?> Login</span></a></li>
                    <li><a href="<?= $tsiteUrl . $cjDriverLogin; ?>" title=""><i class="icon-comments"></i><span><?= $langage_lbl_admin['LBL_DRIVER'] ?> Login</span></a></li>
                    <?php
                    $labelCompany = $langage_lbl_admin['LBL_COMPANY_SIGNIN'];
                    if (DELIVERALL == "Yes") {
                        $labelCompany = $langage_lbl_admin['LBL_COMPANY'];
                    }
                    ?>
                    <li><a href="<?= $tsiteUrl . $cjCompanyLogin; ?>" title=""><i class="icon-home"></i><span><?= $labelCompany; ?> Login</span></a></li>
                </ul>
            </div>
        </div>
        <!-- PAGE CONTENT -->
        <div class="container animated fadeInDown">

            <div class="text-center"> <img src="<?= $admin_logo; ?>" id="Admin" alt=" Admin" /> </div>
<?php if ($userType == 'hotel') { ?>
                <div id="login">				
                    <p style="display:none; padding:5px 0;" class="btn-block btn btn-rect btn-success" id="success" ></p>
                    <!-- <p style="display:none; padding:5px 0;" class="btn-block btn btn-rect btn-danger text-muted text-center" id="errmsg"></p> -->
                    <?php
                    if (isset($_SESSION['checkadminmsg']) && !empty($_SESSION['checkadminmsg'])) {
                        $msg = $_SESSION['checkadminmsg'];
                        //unset($_SESSION['checkadminmsg']);
                        echo ' <div class="data-msg-center"><p style="display:block;" class="btn-block btn btn-rect btn-danger errormsg text-muted text-center errormsg" id="errmsg">' . $msg . ' </p></div>';
                        unset($_SESSION['checkadminmsg']);
                    } else {
                        ?>
                        <div class="data-msg-center"><p style="display:none;" class="btn-block btn btn-rect btn-danger text-muted text-center" id="errmsg"></p></div>
    <?php } ?>  
                    <div class="admin-home-tab">
                        <div class="tab-content clearfix custom-tab">
                            <h4>Hotel Administrator</h4>
                            <div>
                                <form action="" class="form-signin" method = "post" id="login_box" onSubmit="return chkValid();" style="margin:0 auto;border:0;">
                                    <br>
                                    <b><label for="email">Hotel Administrator E-mail</label>
                                        <input type="text" placeholder="Email Address" class="form-control" name="vEmail" id="vEmail" required Value="<?= (SITE_TYPE == "Demo") ? 'hoteladmin@demo.com' : ''; ?>"/>
                                    </b>
                                    <b><label for="password">Password</label>
                                        <input type="password" placeholder="Password" class="form-control" name="vPassword" id="vPassword" required Value="<?= (SITE_TYPE == "Demo") ? '123456' : ''; ?>"/>
                                    </b>
                                    <input type="hidden" name="group_id" id="group_id" value="4"/>
                                    <input type="submit" class="btn text-muted text-center btn-default" value="SIGN IN"/>
                                    <br>
                                </form>
                            </div>
                        </div>
    <? if (SITE_TYPE == "Demo") { ?>
                            <div class="tab-content">
                                <div id="super001" class="tab-pane active">
                                    <h3> Use below Detail for Demo Version</h3>							
                                    <p><b>User Name:</b> hoteladmin@demo.com</p>
                                    <p><b>Password:</b> 123456 </p>
                                    <p>Hotel Administrator can book taxi.</p>
                                </div>
                            </div>
    <? } ?>
                        <div style="clear:both;"></div>
                    </div>
                </div>
<?php } else { ?>
                <div class="tab-content">
                    <div id="login" class="tab-pane active">				
                        <p style="display:none; padding:5px 0;" class="btn-block btn btn-rect btn-success" id="success" ></p>
                        <?php
                        if (isset($_SESSION['checkadminmsg']) && !empty($_SESSION['checkadminmsg'])) {
                            $msg = $_SESSION['checkadminmsg'];
                            //unset($_SESSION['checkadminmsg']);
                            echo '<div class="data-msg-center"><p style="display:block;" class="btn-block btn btn-rect btn-danger text-muted text-center errormsg" id="errmsg">' . $msg . ' </p></div>';
                            unset($_SESSION['checkadminmsg']);
                        } else {
                            ?>
                            <div class="data-msg-center"><p style="display:none;" class="btn-block btn btn-rect btn-danger text-muted text-center errormsg" id="errmsg"></p></div>
    <?php } ?>    


                        <!--
                                <form action="" class="form-signin" method = "post" id="login_box" onSubmit="return chkValid();">
                                <p class="head_login_005">Login</p>
                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span> 
                                <input type="text" placeholder="Email Address" class="form-control" name="vEmail" id="vEmail" required />
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span> 
                                <input type="password" placeholder="Password" class="form-control" name="vPassword" id="vPassword" required />
                                <input type="submit" class="btn text-muted text-center btn-default" value="SIGN IN"/>
                                <br>
                        </form>-->
                        <div class="admin-home-tab">
                            <ul class="nav nav-tabs">
                                <li <?php if ($userType == "admin") { ?>class="active"<?php } ?>  onClick="setCredentials('1', '<?= SITE_TYPE; ?>');passLoginid('super001', '1');"><a data-toggle="tab" href="#super001">All <br> Administrator</a></li>
                                <li onClick="setCredentials('2', '<?= SITE_TYPE; ?>');passLoginid('dispatch001', '2');"><a data-toggle="tab" href="#dispatch001">Dispatcher Administrator</a></li>
                                <li <?php if ($userType == "billing") { ?>class="active"<?php } ?> onClick="setCredentials('3', '<?= SITE_TYPE; ?>');passLoginid('billing001', '3');"><a data-toggle="tab" class="active" href="#billing001">Billing Administrator</a></li>
                            </ul>
                            <div class="tab-content clearfix custom-tab">
                                <div class="tab-pane active" id="super001">
                                    <form action="" class="form-signin" method = "post" id="login_box" onSubmit="return chkValid();" style="margin:0 auto;border:0;">
                                        <br>
                                        <b><label for="email">Administrator E-mail</label>
                                            <input type="text" placeholder="Email Address" class="form-control" name="vEmail" id="vEmail" required Value="<?= (SITE_TYPE == "Demo") ? 'demo@demo.com' : ''; ?>"/>
                                        </b>
                                        <b><label for="password">Password</label>
                                            <input type="password" placeholder="Password" class="form-control" name="vPassword" id="vPassword" required Value="<?= (SITE_TYPE == "Demo") ? '123456' : ''; ?>"/>
                                        </b>
                                        <input type="hidden" name="group_id" id="group_id" value="1"/>
                                        <input type="submit" class="btn text-muted text-center btn-default" value="SIGN IN"/>
                                        <br>
                                    </form>
                                </div>
                            </div>
    <? if (SITE_TYPE == "Demo") { ?>
                                <div class="tab-content">
                                    <div id="super001admin" class="tab-pane active">
                                        <h3> Use below Detail for Demo Version</h3>
                                        <p><b>User Name:</b> demo@demo.com</p>
                                        <p><b>Password:</b> 123456 </p>
                                        <p>Super Administrator can manage whole system and other user's rights too.</p>
                                    </div>
                                    <div id="dispatch001admin" class="tab-pane">
                                        <h3> Use below Detail for Demo Version</h3>
                                        <p><b>User Name:</b> demo2@demo.com</p>
                                        <p><b>Password:</b> 123456 </p>
                                        <p>Call Center Panel / Administrator Dispatcher Panel / Manual Taxi Booking Panel. This panel allows one to see all taxi's on map using God's View. And book taxi's for customer's who would call to book a taxi.</p>
                                    </div>
                                    <div id="billing001admin" class="tab-pane">
                                        <h3> Use below Detail for Demo Version</h3>
                                        <p><b>User Name:</b> demo3@demo.com</p>
                                        <p><b>Password:</b> 123456 </p>
                                        <p>This use will have access to reports only. Will be used by Accounts Team to manage finances and see profits/revenue.</p>
                                    </div>
                                </div>
    <? } ?>
                            <div style="clear:both;"></div>
                        </div>
                    </div>
                    <div id="forgot" class="tab-pane">
                        <form  class="form-signin" method="post" id="frmforget">
                            <input type="email"  required="required" placeholder="Your E-mail"  class="form-control" id="femail"/>
                            <br />
                            <button class="btn text-muted text-center btn-success" type="submit" onClick="forgotPass();">Recover Password</button>
                        </form>
                    </div>
                </div>
<?php } ?>
        </div>
        <!--END PAGE CONTENT -->
        <!-- PAGE LEVEL SCRIPTS -->
        <script src="<?= $tsiteUrl; ?>assets/plugins/jquery-2.0.3.min.js"></script>
        <script src="<?= $tsiteUrl; ?>assets/plugins/bootstrap/js/bootstrap.js"></script>
        <script src="<?= $tsiteUrl; ?>assets/js/login.js"></script>
        <script>
                            var testLink = '<?php echo $_SESSION['current_link']; ?>';
<?php if ($userType != 'hotel') { ?>
                                $(document).ready(function () {
                                    passLoginid('<?= $activeTabId; ?>', '<?= $activeTab; ?>');
                                    setCredentials('<?= $activeTab; ?>', '<?= SITE_TYPE; ?>');
                                });
<?php } ?>
                            function setCredentials(tpd, site_type) {
                                if (site_type == "Demo")
                                {
                                    if (tpd == 2) {
                                        $("#vEmail").val('demo2@demo.com');
                                        $("#vPassword").val('123456');
                                    } else if (tpd == 3)
                                    {
                                        $("#vEmail").val('demo3@demo.com');
                                        $("#vPassword").val('123456');
                                    } else
                                    {
                                        $("#vEmail").val('demo@demo.com');
                                        $("#vPassword").val('123456');
                                    }
                                }
                            }
                            function passLoginid(tabid, login_group_id) {
                                $(".custom-tab .tab-pane").attr('id', tabid);
                                $("#group_id").val(login_group_id);
                                $("#super001admin,#dispatch001admin,#billing001admin").hide();
                                if (tabid == "dispatch001") {
                                    $("#dispatch001admin").show();
                                    $("label[for = email]").text("Dispatcher Administrator E-mail");
                                } else if (tabid == "billing001") {
                                    $("#billing001admin").show();
                                    $("label[for = email]").text("Billing Administrator E-mail");
                                } else {
                                    $("#super001admin").show();
                                    $("label[for = email]").text("Administrator E-mail");
                                }
                            }
                            $('input').keyup(function () {
                                $this = $(this);
                                if ($this.val().length == 1)
                                {
                                    var x = new RegExp("[\x00-\x80]+"); // is ascii
                                    var isAscii = x.test($this.val());
                                    if (isAscii)
                                    {
                                        $this.attr("dir", "ltr");
                                    } else
                                    {
                                        $this.attr("dir", "rtl");
                                    }
                                }
                            });
                            function change_heading(heading, addClass, removeClass)
                            {
                                document.getElementById("login").innerHTML = heading;
                                document.getElementById(addClass).className = "tab-pane";
                                document.getElementById(removeClass).className = "tab-pane active";
                            }
                            function chkValid()
                            {
                                var id = document.getElementById("vEmail").value;
                                var pass = document.getElementById("vPassword").value;
                                if (id == '' || pass == '')
                                {
                                    document.getElementById("errmsg").style.display = '';
                                    setTimeout(function () {
                                        document.getElementById('errmsg').style.display = 'none';
                                    }, 2000);
                                } else
                                {
                                    var request = $.ajax({
                                        type: "POST",
                                        url: '<?= $tconfig["tsite_url_main_admin"]; ?>ajax_login_action.php',
                                        data: $("#login_box").serialize(),
                                        success: function (dataHTml)
                                        {
                                            dataHTml = dataHTml.trim();
                                            if (dataHTml == 1) {
                                                document.getElementById("errmsg").innerHTML = 'You are not active.Please contact administrator to activate your account.';
                                                document.getElementById("errmsg").style.display = '';
                                                return false;
                                            } else if (dataHTml == 2) {
                                                document.getElementById("errmsg").style.display = 'none';
                                                var hdf_class = $("#hdf_class").val();
                                                if (hdf_class != "")
                                                {
                                                    window.location = "<?= $tconfig["tsite_url_main_admin"]; ?>languages.php";
                                                } else
                                                {
<?php
//added by SP for redirection on admin after login on 15-7-2019
$redirecturl = explode('/', $_SESSION['login_redirect_url']);
if (!empty($redirecturl[count($redirecturl) - 1])) {
    if ($redirecturl[count($redirecturl) - 1] == 'dashboard.php' && (ONLYDELIVERALL == "Yes")) {
        $dashboardLink = $tconfig["tsite_url_main_admin"] . 'store-dashboard.php';
    } else if ($redirecturl[3] == 'store-dashboard.php' && (ONLYDELIVERALL == "No")) {
        $dashboardLink = $tconfig["tsite_url_main_admin"] . 'dashboard.php';
    } else {
        $dashboardLink = $tconfig["tsite_url_main_admin"] . $redirecturl[count($redirecturl) - 1];
    }
} /* else if(isset($_SERVER['HTTP_REFERER'])) {
  $dashboardLink = $_SERVER['HTTP_REFERER'];
  } */ else {
    $dashboardLink = (ONLYDELIVERALL == "Yes") ? $tconfig["tsite_url_main_admin"] . 'store-dashboard.php' : $tconfig["tsite_url_main_admin"] . 'dashboard.php';
}
/* if($_SERVER["HTTP_HOST"] != "192.168.1.131" && $_SERVER["HTTP_HOST"] != "mobileappsdemo.com" && $_SERVER["HTTP_HOST"] == "192.168.1.141"){
  $dashboardLink = 'live_system_validation.php';
  } */
?>
                                                    if (testLink == "") {
                                                        //testLink = "<?= $tconfig["tsite_url_main_admin"]; ?>dashboard.php";
                                                        testLink = "<?= $dashboardLink; ?>";
                                                    }
                                                    window.location = testLink;
                                                    //window.location = '<?php echo $_SERVER['HTTP_REFERER']; ?>';
                                                }
                                                return true; // success registration
                                            } else if (dataHTml == 3) {
                                                document.getElementById("errmsg").innerHTML = 'Invalid combination of username & Password';
                                                document.getElementById("errmsg").style.display = '';
                                                //return false;
                                            } else {
                                                document.getElementById("errmsg").innerHTML = 'Invalid Email or Password';
                                                document.getElementById("errmsg").style.display = '';
                                                //setTimeout(function() {document.getElementById('errmsg1').style.display='none';},2000);
                                                //return false;
                                            }
                                            if ($('#errmsg').html() != '') {
                                                setTimeout(function () {
                                                    $('#errmsg').fadeOut();
                                                }, 2000);
                                            }
                                        }
                                    });
                                    request.fail(function (jqXHR, textStatus) {
                                        alert("Request failed: " + textStatus);
                                    });
                                }
                                return false;
                            }
                            function forgotPass()
                            {
                                var id = document.getElementById("femail").value;
                                if (id == '')
                                {
                                    document.getElementById("errmsg_email").style.display = '';
                                    document.getElementById("errmsg_email").innerHTML = 'Please enter Email Address';
                                    return false;
                                } else {
                                    var request = $.ajax({
                                        type: "POST",
                                        url: 'ajax_fpass_action.php',
                                        data: $("#frmforget").serialize(),
                                        beforeSend: function ()
                                        {
                                            alert(data);
                                        },
                                        success: function (data)
                                        {
                                            if (data == 1)
                                            {
                                                document.getElementById("page_title").innerHTML = "Login";
                                                document.getElementById("forgot").className = "tab-pane";
                                                document.getElementById("login").className = "tab-pane active";
                                                document.getElementById("success").innerHTML = 'Your Password has been sent Successfully.';
                                                document.getElementById("success").style.display = '';
                                                return false;
                                            } else if (data == 0)
                                            {
                                                document.getElementById("errmsg_email").innerHTML = 'Error in Sending Password.';
                                                document.getElementById("errmsg_email").style.display = '';
                                                return false;
                                            } else if (data == 3)
                                            {
                                                document.getElementById("errmsg_email").innerHTML = 'Sorry ! The Email address you have entered is not found.';
                                                document.getElementById("errmsg_email").style.display = '';
                                                return false;
                                            }
                                            return false;
                                        }
                                    });
                                    request.fail(function (jqXHR, textStatus) {
                                        alert("Request failed: " + textStatus);
                                        return false;
                                    });
                                    return false;
                                }
                                return false;
                            }



        </script>
        <!--END PAGE LEVEL SCRIPTS -->
    </body>
    <!-- END BODY -->
    <!-- Powered by cubejekshark.com -->
</html>	
