<?php
//added by SP for pages orderby,active/inactive functionality start
$default_lang = isset($_SESSION['sess_lang']) ? $_SESSION['sess_lang'] : "EN";
$PagesData = $obj->MySQLSelect("SELECT iPageId,vPageTitle_$default_lang as pageTitle FROM `pages` WHERE iPageId IN (1,2,4,6,7,33) AND eStatus = 'Active' order by iOrderBy Asc");
$pageCount = 0;
foreach ($PagesData as $key => $value) {
    if($value['iPageId']==1) {
        $displayPages[$pageCount] = '<a href="about">'.$value['pageTitle'].'</a>';
    } else if($value['iPageId']==2) {
        $displayPages[$pageCount] = '<a href="help-center">'.$value['pageTitle'].'</a>';
    } else if($value['iPageId']==4) {
        $displayPages[$pageCount] = '<a href="terms-condition">'.$value['pageTitle'].'</a>';
    } else if($value['iPageId']==6) {
        $displayPages[$pageCount] = '<a href="how-it-works">'.$value['pageTitle'].'</a>';
    } else if($value['iPageId']==7) {
        $displayPages[$pageCount] = '<a href="trust-safty-insurance">'.$value['pageTitle'].'</a>';
    } else if($value['iPageId']==33) {
        $displayPages[$pageCount] = '<a href="privacy-policy">'.$value['pageTitle'].'</a>';
    }
    $pageCount++;
}
//added by SP for pages orderby,active/inactive functionality end
?>
<!-- footer -->

<div class="footer">

    <div class="footer-inner">

        <div class="footer-top">

            <div class="footer-col">

                <h4><?= $langage_lbl['LBL_FOOTER_HOME_CONTACT_US_TXT']; ?></h4>

                <p><?= $COMPANY_ADDRESS ?></p>

                <span>

                    <p style="direction: ltr;"><b>P :</b><?= $SUPPORT_PHONE; ?></p>

                    <p><b>E :</b><a href="#"><?= $SUPPORT_MAIL; ?></a></p>

                </span>

            </div>

            <div class="footer-col">

                <h4><?= $langage_lbl['LBL_FOOTER_HOME_RESTAURANT_TXT']; ?></h4>

                <ul>

                    <li><a href="contact-us"><?= $langage_lbl['LBL_FOOTER_HOME_CONTACT_US_TXT']; ?></a></li>

                    <li><?php echo $displayPages[0]; //added by SP for pages orderby,active/inactive functionality ?></li>

                    <li><?php echo $displayPages[1]; ?></li>
                    <li><a href="faq"><?= $langage_lbl['LBL_FAQs']; ?></a></li>

                    <li><a href="SignUp" style="text-transform: capitalize;"><?= $langage_lbl['LBL_BECOME_A_DRIVER']; ?></a></li>

                </ul>

            </div>

            <div class="footer-col">

                <h4><?= $langage_lbl['LBL_OTHER_PAGE_FOOTER']; ?></h4>

                <ul>

                    <li><?php echo $displayPages[2]; ?></li>

                    <li><?php echo $displayPages[3]; ?></li>

                    <li><?php echo $displayPages[4]; ?></li>

                    <li><?php echo $displayPages[5]; ?></li>

                </ul>

            </div>

            <div class="footer-col">

                <h4>Follow with us</h4>

                <?php if ((!empty($FB_LINK_FOOTER)) || (!empty($TWITTER_LINK_FOOTER)) || (!empty($LINKEDIN_LINK_FOOTER)) || (!empty($GOOGLE_LINK_FOOTER)) || (!empty($INSTAGRAM_LINK_FOOTER))) { ?>

                    <div class="social">

                        <?php if (!empty($FB_LINK_FOOTER)) { ?>

                            <a rel="nofollow" target="_blank" href="<?php echo $FB_LINK_FOOTER; ?>"><img alt="" src="assets/img/home-new/fb.jpg" onclick="return submitsearch(document.frmsearch);" onmouseover="this.src = 'assets/img/home-new/fb-hover.jpg'" onmouseout="this.src = 'assets/img/home-new/fb.jpg'"></a>

                        <?php }

                        if (!empty($TWITTER_LINK_FOOTER)) {
                            ?>

                            <a rel="nofollow" target="_blank" href="<?php echo $TWITTER_LINK_FOOTER; ?>"><img alt="" src="assets/img/home-new/twitter.jpg" onclick="return submitsearch(document.frmsearch);" onmouseover="this.src = 'assets/img/home-new/twitter-hover.jpg'" onmouseout="this.src = 'assets/img/home-new/twitter.jpg'"></a>

    <?php } if (!empty($LINKEDIN_LINK_FOOTER)) { ?>

                            <a rel="nofollow" target="_blank" href="<?php echo $LINKEDIN_LINK_FOOTER; ?>"><img alt="" src="assets/img/home-new/linkedin.jpg" onclick="return submitsearch(document.frmsearch);" onmouseover="this.src = 'assets/img/home-new/linkedin-hover.jpg'" onmouseout="this.src = 'assets/img/home-new/linkedin.jpg'"></a>

    <?php } ?>

                        <a rel="nofollow" target="_blank" href="#"><img alt="" src="assets/img/home-new/pinterest.jpg" onclick="return submitsearch(document.frmsearch);" onmouseover="this.src = 'assets/img/home-new/pinterest-hover.jpg'" onmouseout="this.src = 'assets/img/home-new/pinterest.jpg'"></a>
                    </div>

<?php } ?>

                <div class="footer-box1">

                    <div class="lang" id="lang_open">

                        <b>

                            <a href="javascript:void(0);"><?= $langage_lbl['LBL_LANGUAGE_SELECT']; ?></a>

                        </b>

                    </div>

                    <div class="lang-all" id="lang_box">

                        <ul>

                            <?php
                            $sql = "select vTitle, vCode, vCurrencyCode, eDefault from language_master where eStatus='Active' ORDER BY iDispOrder ASC";

                            $db_lng_mst = $obj->MySQLSelect($sql);

                            $count_lang = count($db_lng_mst);

                            foreach ($db_lng_mst as $key => $value) {

                                $status_lang = "";

                                if ($_SESSION['sess_lang'] == $value['vCode']) {

                                    $status_lang = "active";
                                }
                                ?>

                                <li onclick="change_lang(this.id);" id="<?php echo $value['vCode']; ?>"><a href="javascript:void(0);" class="<?php echo $status_lang; ?>"><?php echo ucfirst(strtolower($value['vTitle'])); ?></a></li>

                            <?php }

                            if ($count_lang > 4) {
                                ?>

                            <!--     <li><a href="contact-us" ><?= $langage_lbl['LBL_LANG_NOT_FIND']; ?></a></li> -->

<?php } ?>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

        <!-- -->

        <div class="footer-bottom-part">

            <p>&copy; <?= $COPYRIGHT_TEXT ?></p>

        </div>

        <div style="clear:both;"></div>

    </div>

</div>



<!-- <div class="footer">

  <div class="footer-top-part">

        <div class="footer-inner">

            <div class="footer-box1">

                <div class="lang" id="lang_open">

                <b><a href="javascript:void(0);"><?= $langage_lbl['LBL_LANGUAGE_SELECT']; ?></a></b>

                </div>

                <div class="lang-all" id="lang_box">

                    <ul>

<?php
$sql = "select vTitle, vCode, vCurrencyCode, eDefault from language_master where eStatus='Active' ORDER BY iDispOrder ASC";

$db_lng_mst = $obj->MySQLSelect($sql);

$count_lang = count($db_lng_mst);

foreach ($db_lng_mst as $key => $value) {

    $status_lang = "";

    if ($_SESSION['sess_lang'] == $value['vCode']) {

        $status_lang = "active";
    }
    ?>

                        <li onclick="change_lang(this.id);" id="<?php echo $value['vCode']; ?>"><a href="javascript:void(0);" class="<?php echo $status_lang; ?>"><?php echo ucfirst(strtolower($value['vTitle'])); ?></a></li>

<?php }

if ($count_lang > 4) {
    ?>



                        <li><a href="contact-us" ><?= $langage_lbl['LBL_LANG_NOT_FIND']; ?></a></li>

<?php } ?>

                    </ul>

                    </div>

<?php if ((!empty($FB_LINK_FOOTER)) || (!empty($TWITTER_LINK_FOOTER)) || (!empty($LINKEDIN_LINK_FOOTER)) || (!empty($GOOGLE_LINK_FOOTER)) || (!empty($INSTAGRAM_LINK_FOOTER))) { ?>

                        <span>

    <?php if (!empty($FB_LINK_FOOTER)) { ?>

                            <a href="<?php echo $FB_LINK_FOOTER; ?>" target="_blank"><i class="fa fa-facebook"></i></a> 

    <?php }

    if (!empty($TWITTER_LINK_FOOTER)) {
        ?>

                            <a href="<?php echo $TWITTER_LINK_FOOTER; ?>" target="_blank"><i class="fa fa-twitter"></i></a>

    <?php }

    if (!empty($LINKEDIN_LINK_FOOTER)) {
        ?>

                            <a href="<?php echo $LINKEDIN_LINK_FOOTER; ?>" target="_blank"><i class="fa fa-linkedin"></i></a>

    <?php }

    if (!empty($GOOGLE_LINK_FOOTER)) {
        ?>

                            <a href="<?php echo $GOOGLE_LINK_FOOTER; ?>" target="_blank"><i class="fa fa-google"></i></a>

    <?php }

    if (!empty($INSTAGRAM_LINK_FOOTER)) {
        ?>

                            <a href="<?php echo $INSTAGRAM_LINK_FOOTER; ?>" target="_blank"><i class="fa fa-instagram"></i></a>

    <?php } ?>

                        </span>

<?php } ?>

                    

            </div>

            <div class="footer-box2">

                <ul>

                    <li><a href="how-it-works"><?= $langage_lbl['LBL_HOW_IT_WORKS']; ?></a></li>

                    <li><a href="trust-safty-insurance"><?= $langage_lbl['LBL_SAFETY_AND_INSURANCE']; ?></a></li>

                    <li><a href="terms-condition"><?= $langage_lbl['LBL_FOOTER_TERMS_AND_CONDITION']; ?></a></li>

                                        <li><a href="faq"><?= $langage_lbl['LBL_FAQs']; ?></a></li>

                    <li><a href="privacy-policy"><?= $langage_lbl['LBL_PRIVACY_POLICY_TEXT']; ?></a></li>

                </ul>

                <ul>

                    <li><a href="about"><?= $langage_lbl['LBL_ABOUT_US_HEADER_TXT']; ?></a></li>

                    <li><a href="contact-us"><?= $langage_lbl['LBL_FOOTER_HOME_CONTACT_US_TXT']; ?></a></li>

                    <li><a href="help-center"><?= $langage_lbl['LBL_FOOTER_HOME_HELP_CENTER']; ?></a></li>

                    <li><a href="legal"><?= $langage_lbl['LBL_LEGAL']; ?></a></li>

                </ul>

            </div>

            <div class="footer-box3"> 

                <span>

                    <a href="<?= $IPHONE_APP_LINK ?>" target="_blank"><img src="assets/img/app-stor-img.png" alt=""></a>

                </span> 

                <span>

                    <a href="<?= $ANDROID_APP_LINK ?>" target="_blank"><img src="assets/img/google-play-img.png" alt=""></a>

                </span> 

            </div>

            <div style="clear:both;"></div>

            </div>

        </div>

        <div class="footer-bottom-part"> 

                <div class="footer-inner">

            <span>&copy; <?= $COPYRIGHT_TEXT ?></span>

        </div>

        <div style=" clear:both;"></div>

    </div>

</div> -->

<script>

    function change_lang(lang) {

        document.location = 'common.php?lang=' + lang;

    }

</script>





<script type="text/javascript">

    $(document).ready(function () {

        $(".custom-select-new1").each(function () {

            var selectedOption = $(this).find(":selected").text();

            $(this).wrap("<em class='select-wrapper'></em>");

            $(this).after("<em class='holder'>" + selectedOption + "</em>");

        });

        $(".custom-select-new1").change(function () {

            var selectedOption = $(this).find(":selected").text();

            $(this).next(".holder").text(selectedOption);

        });

        $("#lang_box").hide();

        $("#lang_open").click(function () {

            $("#lang_box").slideToggle();

        });



        $('html').click(function (e) {

            $('#lang_box').hide();

        });



        $('#lang_open').click(function (e) {

            e.stopPropagation();

        });



    })

</script>

<? include_once 'include/livechat.php'; ?>