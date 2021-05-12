<?php
$sql = "select vTitle, vCode, vCurrencyCode, eDefault from language_master where eStatus='Active' ORDER BY iDispOrder ASC";
$db_lng_mst = $obj->MySQLSelect($sql);
$count_lang = count($db_lng_mst);

if (isset($_POST['vNamenewsletter'])) {
    if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $valiedRecaptch = $generalobj->checkRecaptchValied($GOOGLE_CAPTCHA_SECRET_KEY, $_POST['g-recaptcha-response']);
        if ($valiedRecaptch) {
            $vNamenewsletter = trim($_REQUEST['vNamenewsletter']);
            $vEmailnewsletter = trim($_REQUEST['vEmailnewsletter']);
            $eStatus = trim($_REQUEST['eStatus']);
            $remoteIp = $_SERVER['REMOTE_ADDR'];
            $dateTime = date("Y-m-d H:i:s");

            $chkUser = "SELECT * FROM `newsletter` WHERE vEmail = '" . $vEmailnewsletter . "' ";
            $chkUserCnt = $obj->MySQLSelect($chkUser);
            $fetchStatus = $chkUserCnt[0]['eStatus'];

            if (count($chkUserCnt) > 0) {

                if (($fetchStatus == "Unsubscribe") && ($eStatus == "Unsubscribe")) {
                    header("Location:thank-you.php?action=Alreadyunsubscribe");
                    exit;
                } if (($fetchStatus == "Subscribe") && ($eStatus == "Subscribe")) {
                    header("Location:thank-you.php?action=Alreadysubscribe");
                    exit;
                }
                if (($fetchStatus == "Subscribe") && ($eStatus == "Unsubscribe")) {
                    $maildata['EMAIL'] = $vEmailnewsletter;
                    $maildata['NAME'] = $vNamenewsletter;
                    $maildata['EMAILID'] = $SUPPORT_MAIL;
                    $maildata['PHONENO'] = $SUPPORT_PHONE;

                    $generalobj->send_email_user("MEMBER_NEWS_UNSUBSCRIBE_USER", $maildata);
                }
                if (($fetchStatus == "Unsubscribe") && ($eStatus == "Subscribe")) {
                    $maildata['EMAIL'] = $vEmailnewsletter;
                    $maildata['NAME'] = $vNamenewsletter;
                    $maildata['EMAILID'] = $SUPPORT_MAIL;
                    $maildata['PHONENO'] = $SUPPORT_PHONE;

                    $generalobj->send_email_user("MEMBER_NEWS_SUBSCRIBE_USER", $maildata);
                }

                $insert_query = "UPDATE newsletter SET vName='" . $vNamenewsletter . "', vIP='" . $remoteIp . "',tDate='" . $dateTime . "', eStatus = '" . $eStatus . "' WHERE vEmail='" . $vEmailnewsletter . "'";
            } else {

                if ((count($chkUserCnt) == 0) && $eStatus == 'Unsubscribe') {
                    header("Location:thank-you.php?action=Notsubscribe");
                    exit;
                }
                if ($eStatus == 'Subscribe') {
                    $maildata['EMAIL'] = $vEmailnewsletter;
                    $maildata['NAME'] = $vNamenewsletter;
                    $maildata['EMAILID'] = $SUPPORT_MAIL;
                    $maildata['PHONENO'] = $SUPPORT_PHONE;

                    $generalobj->send_email_user("MEMBER_NEWS_SUBSCRIBE_USER", $maildata);
                }

                $insert_query = "INSERT INTO newsletter SET vName='" . $vNamenewsletter . "',vEmail='" . $vEmailnewsletter . "',vIP='" . $remoteIp . "',tDate='" . $dateTime . "', eStatus = '" . $eStatus . "' ";
            }
            $obj->sql_query($insert_query);
            header("Location: thank-you.php?action=$eStatus");
            exit;
        } else {
            header("Location: thank-you.php?action=Recaptchafail");
            exit;
        }
    } else {
        header("Location: thank-you.php?action=Recaptchafail");
        exit;
    }
}
//Added by HJ for pages orderby,active/inactive functionality start On 02-11-2019
$default_lang = isset($_SESSION['sess_lang']) ? $_SESSION['sess_lang'] : "EN";
$PagesData = $obj->MySQLSelect("SELECT iPageId,vPageTitle_$default_lang as pageTitle FROM `pages` WHERE iPageId IN (1,3,2,4,6,7,33) AND eStatus = 'Active' ");
$pageCount = 0;
foreach ($PagesData as $key => $value) {
    if ($value['iPageId'] == 1) {
        $displayPages[$pageCount] = '<a href="about">' . $value['pageTitle'] . '</a>';
    } else if ($value['iPageId'] == 2) {
        $displayPages[$pageCount] = '<a href="help-center">' . $value['pageTitle'] . '</a>';
    } else if ($value['iPageId'] == 3) {
        $displayPages[$pageCount] = '<a href="legal">' . $value['pageTitle'] . '</a>';
    } else if ($value['iPageId'] == 4) {
        $displayPages[$pageCount] = '<a href="terms-condition">' . $value['pageTitle'] . '</a>';
    } else if ($value['iPageId'] == 6) {
        $displayPages[$pageCount] = '<a href="how-it-works">' . $value['pageTitle'] . '</a>';
    } else if ($value['iPageId'] == 7) {
        $displayPages[$pageCount] = '<a href="trust-safty-insurance">' . $value['pageTitle'] . '</a>';
    } else if ($value['iPageId'] == 33) {
        $displayPages[$pageCount] = '<a href="privacy-policy">' . $value['pageTitle'] . '</a>';
    }
    $pageCount++;
}
//Added by HJ for pages orderby,active/inactive functionality end On 02-11-2019
//echo "<pre>";print_r($displayPages);die;
?>
<footer>
    <div class="footer-top">
        <div class="footer-inner">
            <div class="footer-column">
                <h4><?= $langage_lbl['LBL_FOOTER_HOME_CONTACT_US_TXT']; ?></h4>
                <address><?= $COMPANY_ADDRESS ?></address>
                <ul class="contact-data">
                    <li><label><?= $langage_lbl['LBL_PHONE_FRONT_FOOTER']; ?> : </label><a href="tel:+<?= $SUPPORT_PHONE; ?>" style="direction: ltr;"><?= $SUPPORT_PHONE; ?></a></li>
                    <li><label><?= $langage_lbl['LBL_EMAIL_FRONT_FOOTER']; ?> : </label><a href="mailto:<?= $SUPPORT_MAIL; ?>"><?= $SUPPORT_MAIL; ?></a></li>
                </ul>
                </span>
            </div>
            <div class="footer-column">
                <ul>
                    <?php if (isset($displayPages[4])) { ?>
                        <li><?= $displayPages[4]; ?></li>
                    <?php } ?>
                    <?php if (isset($displayPages[5])) { ?>
                        <li><?= $displayPages[5]; ?></li>
                    <?php } ?>
                    <?php if (isset($displayPages[3])) { ?>
                        <li><?= $displayPages[3]; ?></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="footer-column">
                <ul>
                    <?php if (isset($displayPages[0])) { ?>
                        <li><?= $displayPages[0]; ?></li>
                    <?php } ?>
                    <li><a href="contact-us"><?= $langage_lbl['LBL_FOOTER_HOME_CONTACT_US_TXT']; ?></a></li>
                    <?php if (isset($displayPages[1])) { ?>
                        <li><?= $displayPages[1]; ?></li>
                    <?php } ?>

                </ul>
            </div>
            <div class="footer-column">
                <ul>
                    <?php if (isset($displayPages[6])) { ?>
                        <li><?= $displayPages[6]; ?></li>
                    <?php } ?>
                    <li><a href="faq"><?= $langage_lbl['LBL_FAQs']; ?></a></li>
                    <?php if (isset($displayPages[2])) { ?>
                        <li><?= $displayPages[2]; ?></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="footer-column">
                <?php if ((!empty($FB_LINK_FOOTER)) || (!empty($TWITTER_LINK_FOOTER)) || (!empty($LINKEDIN_LINK_FOOTER)) || (!empty($GOOGLE_LINK_FOOTER)) || (!empty($INSTAGRAM_LINK_FOOTER)) || ($ENABLE_NEWSLETTERS_SUBSCRIPTION_SECTION == "Yes")) { ?>
                    <ul class="social-media-list">
                        <?php if (!empty($FB_LINK_FOOTER)) { ?>
                            <li><a href="<?php echo $FB_LINK_FOOTER; ?>" target="_blank"><i class="fa fa-facebook"></i></a></li> 
                            <?php
                        }
                        if (!empty($TWITTER_LINK_FOOTER)) {
                            ?>
                            <li><a href="<?php echo $TWITTER_LINK_FOOTER; ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                            <?php
                        }
                        if (!empty($LINKEDIN_LINK_FOOTER)) {
                            ?>
                            <li><a href="<?php echo $LINKEDIN_LINK_FOOTER; ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                            <?php
                        }
                        if (!empty($GOOGLE_LINK_FOOTER)) {
                            ?>
                            <li><a href="<?php echo $GOOGLE_LINK_FOOTER; ?>" target="_blank"><i class="fa fa-google"></i></a></li>
                            <?php
                        }
                        if (!empty($INSTAGRAM_LINK_FOOTER)) {
                            ?>
                            <li><a href="<?php echo $INSTAGRAM_LINK_FOOTER; ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                            <?php
                        }
                        if ($ENABLE_NEWSLETTERS_SUBSCRIPTION_SECTION == "Yes") {
                            ?> 
                            <li><a href="#" data-target="#newsletter" data-toggle="modal" class="MainNavText" id="MainNavHelp" ><i class="fa fa-envelope"></i></a></li>
                        <?php } ?>
                    </ul>
                <?php } ?>
                <div class="download-links">
                    <a href="<?= $IPHONE_APP_LINK ?>" target="_blank"><img src="assets/img/ios-store.png" alt=""></a>
                    <a href="<?= $ANDROID_APP_LINK ?>" target="_blank"><img src="assets/img/google-play_.png" alt=""></a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="footer-inner">
            &copy; <?= $COPYRIGHT_TEXT ?>
        </div>
    </div>
</footer>
<? include_once 'newsletter.php'; ?>
<script>
    function change_lang(lang) {
        document.location = 'common.php?lang=' + lang;
    }
</script>
<script type="text/javascript" src="assets/js/validation/jquery.validate.min.js" ></script>

<? include_once 'include/livechat.php'; ?>