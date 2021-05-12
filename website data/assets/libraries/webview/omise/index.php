<!doctype html>
<html lang="en">
    <head>
        <script type="text/javascript" src="js/omise.js"></script>
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <link rel="stylesheet" media="screen" type="text/css" href="css/screen.css" />
        <title>Omise Payment</title>
        <?php
        include_once('../../../../include_config.php');
        ini_set("display_errors", 1);
        error_reporting(E_ALL);
        require_once dirname(__FILE__) . '/config.php';
        require_once dirname(__FILE__) . '/lib/Omise.php';
        $statusMsg = "Transaction has been failed";
        $generalConfigPaymentArr = $generalobj->getGeneralVarAll_Payment_Array();
        $omiseSecretKey = $generalConfigPaymentArr['OMISE_SECRET_KEY'];
        $omisePublishKey = $generalConfigPaymentArr['OMISE_PUBLIC_KEY'];
        //echo "<pre>";
        //print_r($omisePublishKey);die;
        $itemPrice = isset($_REQUEST["amount"]) ? $_REQUEST["amount"] : 0;
        $currency = isset($_REQUEST["ccode"]) ? $_REQUEST["ccode"] : '';
        $userAmount = isset($_REQUEST["userAmount"]) ? $_REQUEST["userAmount"] : '';
        $themeColor = isset($_REQUEST["AppThemeColor"]) ? $_REQUEST["AppThemeColor"] : '000000';
        $textColor = isset($_REQUEST["AppThemeTxtColor"]) ? $_REQUEST["AppThemeTxtColor"] : 'FFFFFF';
        //print_r($_POST);die;
        $redirectUrl = $tconfig['tsite_url'] . "assets/libraries/webview/result.php?success=0";
        if (isset($_POST['omise_token']) && $_POST['omise_token'] != "") {
            $iOrderId = isset($_REQUEST["iOrderId"]) ? $_REQUEST["iOrderId"] : '';
            $vOrderNo = isset($_REQUEST["vOrderNo"]) ? $_REQUEST["vOrderNo"] : '';
            $iUserId = isset($_REQUEST["iUserId"]) ? $_REQUEST["iUserId"] : '';
            $returnUrl = isset($_REQUEST['returnUrl']) ? trim($_REQUEST['returnUrl']) : 'webservice_dl_shark.php';
            $ePaymentOption = isset($_REQUEST["ePaymentOption"]) ? $_REQUEST["ePaymentOption"] : '';
            $vStripeToken = isset($_REQUEST["vStripeToken"]) ? $_REQUEST["vStripeToken"] : '';
            $CheckUserWallet = isset($_REQUEST["CheckUserWallet"]) ? $_REQUEST["CheckUserWallet"] : 'No';
            $type = isset($_REQUEST['type']) ? trim($_REQUEST['type']) : '';
            $eSystem = isset($_REQUEST["eSystem"]) ? $_REQUEST["eSystem"] : '';
            $tSessionId = isset($_REQUEST['tSessionId']) ? trim($_REQUEST['tSessionId']) : '';
            $GeneralMemberId = isset($_REQUEST['GeneralMemberId']) ? trim($_REQUEST['GeneralMemberId']) : '';
            $GeneralUserType = isset($_REQUEST['GeneralUserType']) ? trim($_REQUEST['GeneralUserType']) : '';
            $GeneralAppVersion = isset($_REQUEST['GeneralAppVersion']) ? trim($_REQUEST['GeneralAppVersion']) : '';
            $Platform = isset($_REQUEST['Platform']) ? trim($_REQUEST['Platform']) : 'Android';
            $vTimeZone = isset($_REQUEST["vTimeZone"]) ? $_REQUEST["vTimeZone"] : '';
            $iServiceId = isset($_REQUEST["iServiceId"]) ? $_REQUEST["iServiceId"] : '';
            $vUserDeviceCountry = isset($_REQUEST["vUserDeviceCountry"]) ? $_REQUEST["vUserDeviceCountry"] : '';
            $vCurrentTime = isset($_REQUEST["vCurrentTime"]) ? $_REQUEST["vCurrentTime"] : '';
            $GeneralDeviceType = isset($_REQUEST['GeneralDeviceType']) ? trim($_REQUEST['GeneralDeviceType']) : '';
            $vPayMethod = isset($_REQUEST["vPayMethod"]) ? $_REQUEST["vPayMethod"] : ''; // Instant,Manual
            $card_token = $_POST['omise_token'];
            $tDescription = "Amount charge for order no" . $vOrderNo;
            $charge = OmiseCharge::create(array(
                        "amount" => $itemPrice,
                        "currency" => $currency,
                        "card" => $card_token,
                        "description" => $tDescription
            ));
            //echo "<pre>";
            //print_r($charge);die;
            $status = "succeeded";
            //$chargeData = OmiseApiResource::getInstance($charge);
            $chargeJson = (array) $charge;

            $redirectUrl = $tconfig['tsite_url'] . "assets/libraries/webview/result.php?success=1";
            $extraParameters = "&ePaymentOption=" . $ePaymentOption . "&CheckUserWallet=" . $CheckUserWallet . "&eSystem=" . $eSystem . "&vStripeToken=" . $vStripeToken . "&type=" . $type . "&Platform=" . $Platform . "&tSessionId=" . $tSessionId . "&GeneralMemberId=" . $GeneralMemberId . "&GeneralUserType=" . $GeneralUserType . "&GeneralDeviceType=" . $GeneralDeviceType . "&GeneralAppVersion=" . $GeneralAppVersion . "&vTimeZone=" . $vTimeZone . "&vUserDeviceCountry=" . $vUserDeviceCountry . "&iServiceId=" . $iServiceId . "&vCurrentTime=" . $vCurrentTime;
            //$redirectUrl = $tconfig['tsite_url'] . $returnUrl . "?iUserId=" . $iUserId . "&iOrderId=" . $iOrderId . "&amount=" . $amount . "&ccode=" . $currency . "&vOrderNo=" . $vOrderNo . $extraParameters . "&payStatus=" . $status . "&vPayMethod=" . $vPayMethod . "&AppThemeColor=" . $themeColor . "&AppThemeTxtColor=" . $textColor;
            //echo $redirectUrl;die;
            //header('Location: ' . $redirectUrl); //Redirect for Update Database Table Process
            ?>
            <script>window.location.replace("<?php echo $redirectUrl; ?>");
            </script>
        <?php }
        ?>


        <script type="text/javascript">
            Omise.setPublicKey("<?php echo $omisePublishKey; ?>");
            function minmax(value, min, max)
            {
                if (parseInt(value) < min || isNaN(parseInt(value)))
                    return min;
                else if (parseInt(value) > max)
                    return max;
                else
                    return value;
            }
            function isNumber(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }
                return true;
            }
            $('#card-cvc').bind("cut copy paste", function (e) {
                e.preventDefault();
            });
        </script>
    </head>
    <body>
        <div class="main-part"> 
            <div class="page-contant">  
                <div class="page-contant-inner"> 
                    <div class="card-form">
                        <!-- stripe payment form -->
                        <?php if ($userAmount != "") { ?>
                            <div class="our-work-new" style="background-color: #<?php echo $themeColor; ?>;color: #<?php echo $textColor; ?>;">
                                <span class="our-text" style="font-size: 18px;padding: 10px;">Pay : <?php echo $userAmount; ?></span>
                            </div>
                        <?php } ?>
                        <form action="" method="post" id="checkout">
                            <input type="hidden" name="omise_token">
                            <div class="back-img" style="padding-top: 20px;"><img src="img/card.png"></div>
                            <span style="color: red;" id="token_errors"></span>
                            <div class="our-work-new">
                                <span class="our-text">Card Number</span>
                            </div>
                            <label class="field" for="adyen-encrypted-form-number">
                                <b class="class-box" >
                                    <input class="card-number" data-omise="number" type="number" id="adyen-encrypted-form-number class-box" size="20" autocomplete="off" maxlength="20" data-encrypted-name="number" placeholder="Card Number" onkeypress="return isNumber(event)" required=""/>
                                </b>
                            </label>
                            <span id="cardType"></span>
                            <div class="our-work-new">
                                <span class="our-text">CVV</span>
                            </div>
                            <label class="field" for="adyen-encrypted-form-cvc">
                                <b class="class-box">
                                    <input class="card-cvc" data-omise="security_code" id="card-cvc" type="password" id="adyen-encrypted-form-cvc" size="4" maxlength="4" autocomplete="off" data-encrypted-name="cvc" min="1" max="999" placeholder="CVV" onkeypress="return isNumber(event)" required="" />
                                </b>
                            </label>
                            <div class="our-work-new">
                                <span class="our-text">Card Holder Name</span>
                            </div>  
                            <label class="field" for="adyen-encrypted-form-holder-name">
                                <b class="class-box">
                                    <input  type="text" data-omise="holder_name" id="adyen-encrypted-form-holder-name" size="20" autocomplete="off" placeholder="Card Holder Name" data-encrypted-name="holderName" />
                                </b>
                            </label>
                            <div class="our-work-new">     
                                <span class="our-text">Expiration (MM/YYYY)</span>
                            </div>
                            <label class="field-a" for="adyen-encrypted-form-expiry-month">
                                <b  class="class-box-a">
                                    <input class="card-expiry-month" max="12" data-omise="expiration_month" inputmethod="numeric" type="number" onkeyup="this.value = minmax(this.value, '', 12)"  id="adyen-encrypted-form-expiry-month" maxlength="2" size="2" autocomplete="off" onkeypress="return isNumber(event)" data-encrypted-name="expiryMonth" placeholder="MM" />
                                </b> 
                            </label>
                            <img class="float-work" src="img/line.jpg">
                            <b  class="class-box-b">
                                <input class="card-expiry-year" type="text" data-omise="expiration_year" id="adyen-encrypted-form-expiry-year" maxlength="4" size="4" autocomplete="off" data-encrypted-name="expiryYear" onkeypress="return isNumber(event)" placeholder="YYYY" />
                            </b>
                            <div class="work-card"> 
                                <div class="card-num-a">     
                                    <button type="submit" class="button-num" id="create_token">Submit Payment</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $("#checkout").submit(function () {
                var form = $(this);
                // Disable the submit button to avoid repeated click.
                form.find("input[type=submit]").prop("disabled", true);
                // Serialize the form fields into a valid card object.
                var card = {
                    "name": form.find("[data-omise=holder_name]").val(),
                    "number": form.find("[data-omise=number]").val(),
                    "expiration_month": form.find("[data-omise=expiration_month]").val(),
                    "expiration_year": form.find("[data-omise=expiration_year]").val(),
                    "security_code": form.find("[data-omise=security_code]").val()
                };
                // Send a request to create a token then trigger the callback function once
                // a response is received from Omise.
                //
                // Note that the response could be an error and this needs to be handled within
                // the callback.
                Omise.createToken("card", card, function (statusCode, response) {
                    // alert(response.message);
                    if (response.object == "error") {
                        // Display an error message.
                        $("#token_errors").html(response.message);

                        // Re-enable the submit button.
                        form.find("input[type=submit]").prop("disabled", false);
                    } else {
                        // Then fill the omise_token.
                        form.find("[name=omise_token]").val(response.id);
                        $("#token_id").html(response.id);
                        // alert(response.id);
                        // Remove card number from form before submiting to server.
                        // form.find("[data-omise=number]").val("");
                        // form.find("[data-omise=security_code]").val("");
                        // submit token to server.
                        form.get(0).submit();
                    }
                });
                // Prevent the form from being submitted;
                return false;
            });
        </script>
    </body>
</html>
