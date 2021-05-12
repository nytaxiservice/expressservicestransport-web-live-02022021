<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <meta http-equiv="content-type" content="text/html; charset=windows-1250">
    <meta name="generator" content="PSPad editor, www.pspad.com">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
    <script type="text/javascript" src="js/validation_stripe_js.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <link rel="stylesheet" media="screen" type="text/css" href="css/screen.css" />
    <?php
    ini_set("display_errors", 1);
    error_reporting(E_ALL);
    include_once('../../../../include_taxi_webservices.php');
    $statusMsg = "Transaction has been failed";
    $generalConfigPaymentArr = $generalobj->getGeneralVarAll_Payment_Array();
    $stripeSecretKey = $generalConfigPaymentArr['STRIPE_SECRET_KEY'];
    $stripePublishKey = $generalConfigPaymentArr['STRIPE_PUBLISH_KEY'];
    //echo "<pre>";
    //print_r($_REQUEST);die;
    ?>
    <script type="text/javascript">
        //set your publishable key
        Stripe.setPublishableKey('<?php echo $stripePublishKey; ?>');
        //callback to handle the response from stripe
        function stripeResponseHandler(status, response) {
            if (response.error) {
                //enable the submit button
                $('#payBtn').removeAttr("disabled");
                //display the errors on the form
                $(".payment-errors").html(response.error.message);
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            } else {
                var form$ = $("#paymentFrm");
                //get token id
                var token = response['id'];
                //insert the token into the form
                form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                //submit form to the server
                var url = document.location.href;
                var newurl = url + "&paymentprocess=yes";
                window.history.pushState({path: newurl}, '', newurl);
                form$.get(0).submit();
            }
        }
        $(document).ready(function () {
            //on form submit
            $("#paymentFrm").submit(function (event) {
                //disable the submit button to prevent repeated clicks
                $('#payBtn').attr("disabled", "disabled");
                //create single-use token to charge the user
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
                //submit from callback
                return false;
            });
        });
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
    <?php
    $itemPrice = isset($_REQUEST["amount"]) ? $_REQUEST["amount"] : 0;
    $currency = isset($_REQUEST["ccode"]) ? $_REQUEST["ccode"] : '';
    $userAmount = isset($_REQUEST["userAmount"]) ? $_REQUEST["userAmount"] : '';
    $themeColor = isset($_REQUEST["AppThemeColor"]) ? $_REQUEST["AppThemeColor"] : '000000';
    $textColor = isset($_REQUEST["AppThemeTxtColor"]) ? $_REQUEST["AppThemeTxtColor"] : 'FFFFFF';
    //echo "<pre>";
    //print_r($_REQUEST);die;
    if (isset($_POST['stripeToken'])) {
        //echo "<pre>";
        //print_r($_REQUEST);die;
        $token = isset($_POST["stripeToken"]) ? $_POST["stripeToken"] : '';
        $name = isset($_POST["name"]) ? $_POST["name"] : '';
        $email = isset($_POST["email"]) ? $_POST["email"] : '';
        $card_num = isset($_POST["card_num"]) ? $_POST["card_num"] : '';
        $card_cvc = isset($_POST["cvc"]) ? $_POST["cvc"] : '';
        $card_exp_month = isset($_POST["exp_month"]) ? $_POST["exp_month"] : '';
        $card_exp_year = isset($_POST["exp_year"]) ? $_POST["exp_year"] : '';
        $iOrderId = isset($_REQUEST["iOrderId"]) ? $_REQUEST["iOrderId"] : '';
        $vOrderNo = isset($_REQUEST["vOrderNo"]) ? $_REQUEST["vOrderNo"] : '';
        $iUserId = isset($_REQUEST["iUserId"]) ? $_REQUEST["iUserId"] : '';
        $returnUrl = isset($_REQUEST['returnUrl']) ? trim($_REQUEST['returnUrl']) : 'webservice_dl_shark.php';
        //include Stripe PHP library
        require_once('stripe-php/init.php');
        //set api key
        $stripe = array(
            "secret_key" => $stripeSecretKey,
            "publishable_key" => $stripePublishKey
        );
        \Stripe\Stripe::setApiKey($stripe['secret_key']);
        //print_r($customer);die;
        //item information
        $tDescription = "Amount charge for order no" . $vOrderNo;
        //charge a credit or a debit card
        $charge = \Stripe\Charge::create(array(
                    "amount" => $itemPrice,
                    "currency" => $currency,
                    "source" => $token,
                    "description" => $tDescription
        ));
        //retrieve charge details
        $chargeJson = $charge->jsonSerialize();
        //check whether the charge is successful
        if ($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1) {
            //order details 
            $amount = $chargeJson['amount'];
            $balance_transaction = $chargeJson['balance_transaction'];
            $currency = $chargeJson['currency'];
            $status = $chargeJson['status'];
            $date = date("Y-m-d H:i:s");
            $statusMsg = "Transaction has been " . $status;
            if ($status == "succeeded") {
                //echo "<pre>";
                //print_r($_REQUEST);die;
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

                $extraParameters = "&ePaymentOption=" . $ePaymentOption . "&CheckUserWallet=" . $CheckUserWallet . "&eSystem=" . $eSystem . "&vStripeToken=" . $vStripeToken . "&type=" . $type . "&Platform=" . $Platform . "&tSessionId=" . $tSessionId . "&GeneralMemberId=" . $GeneralMemberId . "&GeneralUserType=" . $GeneralUserType . "&GeneralDeviceType=" . $GeneralDeviceType . "&GeneralAppVersion=" . $GeneralAppVersion . "&vTimeZone=" . $vTimeZone . "&vUserDeviceCountry=" . $vUserDeviceCountry . "&iServiceId=" . $iServiceId . "&vCurrentTime=" . $vCurrentTime;
                $redirectUrl = $tconfig['tsite_url'] . $returnUrl . "?iUserId=" . $iUserId . "&iOrderId=" . $iOrderId . "&amount=" . $amount . "&ccode=" . $currency . "&vOrderNo=" . $vOrderNo . $extraParameters . "&payStatus=" . $status . "&vPayMethod=" . $vPayMethod . "&AppThemeColor=" . $themeColor . "&AppThemeTxtColor=" . $textColor;
                //echo $redirectUrl;die;
                header('Location: ' . $redirectUrl); //Redirect for Update Database Table Process
            } else {
                echo $statusMsg;
                die;
            }
        } else {
            echo $statusMsg;
            die;
        }
    }
    ?>
    <!-- display errors returned by createToken -->
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
                        <form action="" method="POST" id="paymentFrm">
                            <div class="back-img" style="padding-top: 20px;"><img src="img/card.png"></div>
                            <span style="color: red;" class="payment-errors"></span>
                            <div class="our-work-new">
                                <span class="our-text">Card Number</span>
                            </div>
                            <label class="field" for="adyen-encrypted-form-number">
                                <b class="class-box" >
                                    <input class="card-number"  type="number" id="adyen-encrypted-form-number class-box" size="20" autocomplete="off" maxlength="20" data-encrypted-name="number" placeholder="Card Number" onKeyPress="return isNumber(event)" required=""/>
                                </b>
                            </label>
                            <span id="cardType"></span>
                            <div class="our-work-new">
                                <span class="our-text">CVV</span>
                            </div>
                            <label class="field" for="adyen-encrypted-form-cvc">
                                <b class="class-box">
                                    <input class="card-cvc" id="card-cvc" type="password" id="adyen-encrypted-form-cvc" size="4" maxlength="4" autocomplete="off" data-encrypted-name="cvc" min="1" max="999" placeholder="CVV" onkeypress="return isNumber(event)" required=""; />
                                </b>
                            </label>
                            <!--<div class="our-work-new">
                                <span class="our-text">Card Holder Name</span>
                            </div>  
                            <label class="field" for="adyen-encrypted-form-holder-name">
                                <b class="class-box">
                                    <input  type="text" id="adyen-encrypted-form-holder-name" size="20" autocomplete="off" data-encrypted-name="holderName" placeholder="Card Holder Name" />
                                </b>
                            </label>-->
                            <div class="our-work-new">     
                                <span class="our-text">Expiration (MM/YYYY)</span>
                            </div>
                            <label class="field-a" for="adyen-encrypted-form-expiry-month">
                                <b  class="class-box-a">
                                    <input class="card-expiry-month" inputmethod="numeric" type="number" onKeyUp="this.value = minmax(this.value, '', 12)"  id="adyen-encrypted-form-expiry-month" maxlength="2" size="2" autocomplete="off" onKeyPress="return isNumber(event)" data-encrypted-name="expiryMonth" placeholder="MM" />
                                </b> 
                            </label>
                            <img class="float-work" src="img/line.jpg">
                            <b  class="class-box-b">
                                <input class="card-expiry-year" type="text" id="adyen-encrypted-form-expiry-year" maxlength="4" size="4" autocomplete="off" data-encrypted-name="expiryYear" onKeyPress="return isNumber(event)" placeholder="YYYY" />
                            </b>
                            <div class="work-card"> 
                                <div class="card-num-a">     
                                    <button type="submit" class="button-num" id="payBtn">Submit Payment</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>