<div class="modal fade" id="newsletter" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h4><?= $langage_lbl['LBL_HEAD_SUBSCRIBE_NEWSLATTER_TXT']; ?></h4></div>
            <div class="modal-body">

                <div class="form-box-content export-popup">
                    <form  name="newsletter" id="frmnewsletter" method="post" action="" class="clearfix" enctype="multipart/form-data">
                        <div class="row">  
                            <div class="col-lg-12">
                                <label><?= $langage_lbl['LBL_USER_NAME_HEADER_SLIDE_TXT']; ?><span class="red"> *</span></label>
                            </div>
                            <div class="col-lg-8 rideo-work">
                                <input type="text" autocomplete="off" class="form-control" name="vNamenewsletter"  id="vNamenewsletter" value="<?= $vNamenewsletter; ?>" placeholder="<?= $langage_lbl['LBL_USER_NAME_HEADER_SLIDE_TXT']; ?>" >
                            </div>
                        </div>
                        <div class="row" style="margin-top:10px;">
                            <div class="col-lg-12">
                                <label><?= $langage_lbl['LBL_EMAIL_LBL_TXT']; ?><span class="red"> *</span></label>
                            </div>
                            <div class="col-lg-8 rideo-work">
                                <input type="text" autocomplete="off" class="form-control" name="vEmailnewsletter"  id="vEmailnewsletter" value="<?= $vEmailnewsletter; ?>" placeholder="<?= $langage_lbl['LBL_EMAIL_LBL_TXT']; ?>" > 
                            </div> 
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-8 rideo-work">
                                <span class="news_subs"><label><input type="radio" checked="" name="eStatus" value="Subscribe"></label><?php echo $langage_lbl['LBL_SUBSCRIBE']; ?></span>
                                <span class="news_subs"><label><input type="radio" name="eStatus" value="Unsubscribe"></label><?php echo $langage_lbl['LBL_UNSUBSCRIBE']; ?></span>
                            </div>
                        </div>
                        <br>
                        <!-- Captcha Syntax -->
                        <span class="newrow">
                            <?php include_once("newsletterrecaptch.php"); ?>
                            <span id="recaptcha-msg-newerror" style="display: none;" class="error">This field is required.</span>
                        </span>
                        <!-- Close Captcha -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><?= $langage_lbl['LBL_BTN_CANCEL_TRIP_TXT']; ?></button>
                            <input type="submit" class="btn btn-success"  name="submitss" id="submitss" value="<?php echo $langage_lbl_admin['LBL_BTN_SUBMIT_TXT']; ?>" >
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    function refreshCaptchanewsletter() {
        document.getElementById('POST_CAPTCHA_NEWSLETTER').value = '';
        var img = document.images['newslettercaptchaimg'];
        var codee = Math.random() * 1000;
        img.src = img.src.substring(0, img.src.lastIndexOf("?")) + "?rand=" + codee;
    }
    $(document).ready(function () {
        var errormessage;
        $('#frmnewsletter').validate({
            rules: {
                vNamenewsletter: {required: true, minlength: 2, maxlength: 40},
                vEmailnewsletter: {required: true, email: true}
            },
            messages: {vNamenewsletter: {
                    required: 'This field is required.',
                    minlength: 'Name at least 2 characters long.',
                    maxlength: 'Please enter less than 40 characters.'
                },
                vEmailnewsletter: {remote: function () {
                        return errormessage;
                    }}
            },
            submitHandler: function (form) {
                if (grecaptcha.getResponse() == '') {
                    $('#recaptcha-msg-newerror').css('display', 'block');
                    return false;
                }
                document.getElementById("frmnewsletter").submit();

            },
            onkeypress: true
        });
    });
</script>
