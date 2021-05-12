<?php
include_once('../../../../include_config.php');
ini_set("display_errors", 1);
error_reporting(E_ALL);
/* Xendit Test Card Details
  Xendit.card.validateCardNumber('4000000000000002'); // true
  Xendit.card.validateCardNumber('abc'); // false

  Xendit.card.validateExpiry('09', '2017'); // true
  Xendit.card.validateExpiry('13', '2017'); // false

  Xendit.card.validateCvn('123'); // true
  Xendit.card.validateCvn('aaa'); // false */
//Testing Url : http://192.168.1.131/cubejekdev/assets/libraries/webview/xendit/index.php?iUserId=87&iOrderId=257&amount=5000&ccode=USD&userAmount=$%205000&vOrderNo=2527612222&ePaymentOption=Card&CheckUserWallet=No&eSystem=DeliverAll&vStripeToken=&type=CaptureCardPaymentOrder&Platform=Android&tSessionId=fojmbca656fth1nef44vuugge61537160313&GeneralMemberId=87&GeneralUserType=Passenger&GeneralDeviceType=Ios&GeneralAppVersion=&vTimeZone=Asia\\/Kolkata&vUserDeviceCountry=IN&iServiceId=1&vCurrentTime=2018-09-29&returnUrl=webservice_dl_shark.php&vPayMethod=Instant&AppThemeColor=239707&AppThemeTxtColor=FFFFFF
require 'XenditPHPClient.php';
$itemPrice = isset($_REQUEST["amount"]) ? $_REQUEST["amount"] : 0;
$currency = isset($_REQUEST["ccode"]) ? $_REQUEST["ccode"] : '';
$userAmount = isset($_REQUEST["userAmount"]) ? $_REQUEST["userAmount"] : '';
$themeColor = isset($_REQUEST["AppThemeColor"]) ? $_REQUEST["AppThemeColor"] : '000000';
$textColor = isset($_REQUEST["AppThemeTxtColor"]) ? $_REQUEST["AppThemeTxtColor"] : 'FFFFFF';
$generalConfigPaymentArr = $generalobj->getGeneralVarAll_Payment_Array();
$xenditSecretKey = $generalConfigPaymentArr['XENDIT_SECRET_KEY'];
$xenditPublishKey = $generalConfigPaymentArr['XENDIT_PUBLIC_KEY'];
//$xenditSecretKey = "xnd_development_P4qDfOss0OCpl8RtKrROHjaQYNCk9dN5lSfk+R1l9Wbe+rSiCwZ3jw==";
//print_r($generalConfigPaymentArr);die;
if (isset($_POST['xendit_token'])) {
    //echo "<pre>";
    //print_r($_POST);die;
    $options['secret_api_key'] = $xenditSecretKey;
    $xenditPHPClient = new XenditClient\XenditPHPClient($options);
    $external_id = substr(number_format(time() * rand(), 0, '', ''), 0, 15);
    $token_id = $_POST['xendit_token'];
    //$itemPrice = "5000";
    $response = $xenditPHPClient->getBalance();
    $response = $xenditPHPClient->captureCreditCardPayment($external_id, $token_id, $itemPrice);
    if (isset($response['errors'])) {
        $statusMsg = $response['errors'];
    }
    if (isset($response['message'])) {
        $statusMsg = $response['message'];
    }
    $redirectUrl = $tconfig['tsite_url'] . "assets/libraries/webview/result.php?success=0";
    if (isset($response['status']) && $response['status'] == "CAPTURED") {
        $resultCode = $response['status'];
        $iOrderId = isset($_REQUEST["iOrderId"]) ? $_REQUEST["iOrderId"] : '';
        $vOrderNo = isset($_REQUEST["vOrderNo"]) ? $_REQUEST["vOrderNo"] : '';
        $iUserId = isset($_REQUEST["iUserId"]) ? $_REQUEST["iUserId"] : '';
        $returnUrl = isset($_REQUEST['returnUrl']) ? trim($_REQUEST['returnUrl']) : 'webservice_dl_shark.php';
        $date = date("Y-m-d H:i:s");
        $status = "succeeded";
        $statusMsg = "Transaction has been " . $resultCode;
        //echo "<pre>";
        //print_r($response)die;
        $amount = $itemPrice;
        if (isset($response['authorized_amount'])) {
            $amount = $response['authorized_amount'];
        }
        if (isset($response['capture_amount'])) {
            $amount = $response['capture_amount'];
        }
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
        //$redirectUrl = $tconfig['tsite_url'] . "assets/libraries/webview/result.php?success=1";
        $redirectUrl = $tconfig['tsite_url'] . $returnUrl . "?iUserId=" . $iUserId . "&iOrderId=" . $iOrderId . "&amount=" . $amount . "&ccode=" . $currency . "&vOrderNo=" . $vOrderNo . $extraParameters . "&payStatus=" . $status . "&vPayMethod=" . $vPayMethod . "&AppThemeColor=" . $themeColor . "&AppThemeTxtColor=" . $textColor;
        //header('Location: ' . $redirectUrl); //Redirect for Update Database Table Process
    }
    ?>
    <script>window.location.replace("<?php echo $redirectUrl; ?>");
    </script>
    <?php
}
?>
<html>
    <head>
        <title>Xendit Payment</title>
    </head>
    <link rel="stylesheet" media="screen" type="text/css" href="css/screen.css" />
    <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="https://js.xendit.co/v1/xendit.min.js"></script>
    <style type="text/css">
        .credit-card-box .panel-title {
            display: inline;
            font-weight: bold;
        }
        .credit-card-box .form-control.error {
            border-color: red;
            outline: 0;
            box-shadow: inset 0 1px 1px rgba(0,0,0,0.075),0 0 8px rgba(255,0,0,0.6);
        }
        .credit-card-box label.error {
            font-weight: bold;
            color: red;
            padding: 2px 8px;
            margin-top: 2px;
        }
        .credit-card-box .payment-errors {
            font-weight: bold;
            color: red;
            padding: 2px 8px;
            margin-top: 2px;
        }
        .credit-card-box label {
            display: block;
        }
        .submit-button {
            background-color: #1ace9b;
            color: #ffffff;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 10;
        }

        #three-ds-container {
            width: 550px;
            height: 450px;
            line-height: 200px;
            position: fixed;
            top: 25%;
            left: 40%;
            margin-top: -100px;
            margin-left: -150px;
            background-color: #ffffff;
            border-radius: 5px;
            text-align: center;
            z-index: 11; /* 1px higher than the overlay layer */
        }

        pre {
            white-space: pre-wrap;
        }

        div.request {
            width: 50%;
            float: left;
        }

        pre.result {
            width: 49%;
        }
    </style>
    <body>
        <div class="main-part"> 
            <div class="page-contant">  
                <div class="page-contant-inner"> 
                    <div class="card-form">
<?php if ($userAmount != "") { ?>
                            <div class="our-work-new" style="background-color: #<?php echo $themeColor; ?>;color: #<?php echo $textColor; ?>;">
                                <span class="our-text" style="font-size: 18px;padding: 10px;">Pay : <?php echo $userAmount; ?></span>
                            </div>
<?php } ?>
                        <form action="" method="post" id="checkout">
                            <input type="hidden" name="xendit_token">
                            <div class="back-img" style="padding-top: 20px;"><img src="img/card.png"></div>
                            <span style="color: red;" id="token_errors"></span>
                            <input class="form-control" type="hidden" id="api-key" placeholder="API Key" value="<?= $xenditPublishKey; ?>"/>
                            <div class="our-work-new">
                                <span class="our-text">Card Number</span>
                            </div>
                            <label class="field" for="xenditcardnumber">
                                <b class="class-box" >
                                    <input class="card-number" data-xendit="number" type="number" id="xenditcardnumber" size="20" autocomplete="off" maxlength="20" data-encrypted-name="number" placeholder="Card Number" onkeypress="return isNumber(event)" required=""/>
                                </b>
                            </label>
                            <span id="cardType"></span>
                            <div class="our-work-new">
                                <span class="our-text">CVV</span>
                            </div>
                            <label class="field" for="xenditcvc">
                                <b class="class-box">
                                    <input class="card-cvc" data-xendit="security_code" id="card-cvc" type="password" id="xenditcvc" size="4" maxlength="4" autocomplete="off" data-encrypted-name="cvc" min="1" max="999" placeholder="CVV" onkeypress="return isNumber(event)" required="" />
                                </b>
                            </label>
                            <div class="our-work-new">
                                <span class="our-text">Card Holder Name</span>
                            </div>  
                            <label class="field" for="xenditholder">
                                <b class="class-box">
                                    <input  type="text" data-xendit="holder_name" id="xenditholder" size="20" autocomplete="off" placeholder="Card Holder Name" data-encrypted-name="holderName" />
                                </b>
                            </label>
                            <div class="our-work-new">     
                                <span class="our-text">Expiration (MM/YYYY)</span>
                            </div>
                            <label class="field-a" for="xenditmonth">
                                <b  class="class-box-a">
                                    <input class="card-expiry-month" max="12" data-xendit="expiration_month" inputmethod="numeric" type="number" onkeyup="this.value = minmax(this.value, '', 12)"  id="xenditmonth" maxlength="2" size="2" autocomplete="off" onkeypress="return isNumber(event)" data-encrypted-name="expiryMonth" placeholder="MM" />
                                </b> 
                            </label>
                            <img class="float-work" src="img/line.jpg">
                            <b  class="class-box-b">
                                <input class="card-expiry-year" type="text" data-xendit="expiration_year" id="xendityear" maxlength="4" size="4" autocomplete="off" data-encrypted-name="expiryYear" onkeypress="return isNumber(event)" placeholder="YYYY" />
                            </b>
                            <div class="work-card"> 
                                <div class="card-num-a">     
                                    <button type="submit" class="submit button-num" id="create_token">Submit Payment</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="three-ds-container" style="display: none;">
                <iframe height="450" width="550" id="sample-inline-frame" name="sample-inline-frame"> </iframe>
            </div>
        </div>
    </body>
</html>
<script>Xendit.setPublishableKey('<?= $xenditPublishKey; ?>');</script>
<script type="text/javascript">
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
    $(function () {
        var $form = $('#checkout');
        $form.submit(function () {
            var form = $(this);
            Xendit.setPublishableKey($form.find('#api-key').val());
            // Disable the submit button to avoid repeated click.
            $form.find('.submit').prop('disabled', true);
            var tokenData = {
                "amount": "1",
                "card_number": form.find("[data-xendit=number]").val(),
                "card_exp_month": form.find("[data-xendit=expiration_month]").val(),
                "card_exp_year": form.find("[data-xendit=expiration_year]").val(),
                "card_cvn": form.find("[data-xendit=security_code]").val(),
                "is_multiple_use": false,
                "should_authenticate": false
            }
            Xendit.card.createToken(tokenData, function (err, data) {
                if (err) {
                    //Define error handling
                    $("#token_errors").html(err);
                }
                console.log(data.status);
                if (data.status === 'VERIFIED') {
                    // Handle success
                    $form.find("[name=xendit_token]").val(data.id);
                    $("#token_id").html(data.id);
                    $form.get(0).submit();
                } else if (data.status === 'IN_REVIEW') {
                    // Handle authentication (3DS)
                    window.open(creditCardCharge.payer_authentication_url, 'sample-inline-frame');
                    $('.overlay').show();
                    $('#three-ds-container').show();

                } else if (data.status === 'FRAUD') {
                    $("#token_errors").html(data.status);
                } else if (data.status === 'FAILED') {
                    // Handle failure
                    $("#token_errors").html(data.status);
                }
            });
            // Prevent the form from being submitted;
            return false;
        });
    });
</script>