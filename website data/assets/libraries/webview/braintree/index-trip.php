<?php
require_once('braintree_environment.php');
/* Test Card Details
 * 4111111111111111 ,4005519200000004, 5555555555554444,2223000048400011
 * 123
 * 12/21
 * Test URL : http:\/\/cubejekshark.bbcsproducts.com\/assets\/libraries\/webview\/payment_configuration.php?iUserId=19&iOrderId=12&amount=2900&ccode=USD&userAmount=$ 29&vOrderNo=2214249022&ePaymentOption=Card&CheckUserWallet=No&eSystem=DeliverAll&vStripeToken=&type=CaptureCardPaymentOrder&Platform=IOS&tSessionId=ku88o2lu11r8t7j3h7epn0aic11546492133&GeneralMemberId=19&GeneralUserType=Passenger&GeneralDeviceType=Ios&GeneralAppVersion=&vTimeZone=Asia\/Kolkata&vUserDeviceCountry=US&iServiceId=1&vCurrentTime=2019-01-03 10:39:32&returnUrl=webservice_shark.php&vPayMethod=Instant&AppThemeColor=000000&AppThemeTxtColor=FFFFFF
 */
ini_set("display_errors", 1);
error_reporting(E_ALL);
function braintree_text_field($label, $name, $result, $placeholder) {
    echo ('<div class="our-work-new"><span class="our-text">' . $label . '</span></div>');
    $fieldValue = isset($result) ? $result->valueForHtmlField($name) : '';
    $isNumber = $maxLength = $id = "";
    if ($label == "CVV" || $label == "Card Number") {
        $isNumber = 'onkeypress="return isNumber(event)"';
    }
    if ($label == "CVV") {
        $maxLength = 'maxlength="4"';
        $id = 'id="card-cvc"';
    } else if ($label == "Card Number") {
        $maxLength = 'maxlength="19"';
    }
    echo('<div><input placeholder="' . $placeholder . '" ' . $isNumber . ' ' . $maxLength . ' ' . $id . ' class="card-number" type="text" name="' . $name . '" value="' . $fieldValue . '" required=""/></div>');
    $errors = isset($result) ? $result->errors->onHtmlField($name) : array();
    foreach ($errors as $error) {
        echo('<div style="color: red;">' . $error->message . '</div>');
    }
    echo("\n");
}

$itemPrice = isset($_REQUEST["amount"]) ? $_REQUEST["amount"] : 0;
$currency = isset($_REQUEST["ccode"]) ? $_REQUEST["ccode"] : '';
$userAmount = isset($_REQUEST["userAmount"]) ? $_REQUEST["userAmount"] : '';
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
$themeColor = isset($_REQUEST["AppThemeColor"]) ? $_REQUEST["AppThemeColor"] : '000000';
$textColor = isset($_REQUEST["AppThemeTxtColor"]) ? $_REQUEST["AppThemeTxtColor"] : 'FFFFFF';
$status = "failed";
$extraParameters = "?iUserId=" . $iUserId . "&iOrderId=" . $iOrderId . "&amount=" . $itemPrice . "&ccode=" . $currency . "&vOrderNo=" . $vOrderNo . "&ePaymentOption=" . $ePaymentOption . "&CheckUserWallet=" . $CheckUserWallet . "&eSystem=" . $eSystem . "&vStripeToken=" . $vStripeToken . "&type=" . $type . "&Platform=" . $Platform . "&tSessionId=" . $tSessionId . "&GeneralMemberId=" . $GeneralMemberId . "&GeneralUserType=" . $GeneralUserType . "&GeneralDeviceType=" . $GeneralDeviceType . "&GeneralAppVersion=" . $GeneralAppVersion . "&vTimeZone=" . $vTimeZone . "&vUserDeviceCountry=" . $vUserDeviceCountry . "&iServiceId=" . $iServiceId . "&vCurrentTime=" . $vCurrentTime . "&payStatus=" . $status . "&vPayMethod=" . $vPayMethod . "&AppThemeColor=" . $themeColor . "&AppThemeTxtColor=" . $textColor;
//echo "<pre>";
//print_r($_REQUEST);die;
?>
<html>
    <head>
        <title>Braintree Payment</title>
    </head>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <link rel="stylesheet" media="screen" type="text/css" href="css/screen.css" />
    <script type="text/javascript">
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
    <body>
        <?php
        if (isset($_GET["id"])) {
            $result = Braintree_TransparentRedirect::confirm($_SERVER['QUERY_STRING']);
            echo "<pre>";
            print_r($result);
            die;
        }
        if (isset($result) && $result->success) {
            echo "<pre>";
            print_r($result);
            die;
            ?>
            <h1>Braintree Payment</h1>
            <?php
            $transaction = $result->transaction;
            if (htmlentities($transaction->status) == "authorized") {
                $card_exp_year = isset($_POST["exp_year"]) ? $_POST["exp_year"] : '';
                $itemPrice = isset($_REQUEST["amount"]) ? $_REQUEST["amount"] : 0;
                $currency = isset($_REQUEST["ccode"]) ? $_REQUEST["ccode"] : '';
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
                $status = "succeeded";
                $extraParameters = "?iUserId=" . $iUserId . "&iOrderId=" . $iOrderId . "&amount=" . $itemPrice . "&ccode=" . $currency . "&vOrderNo=" . $vOrderNo . "&ePaymentOption=" . $ePaymentOption . "&CheckUserWallet=" . $CheckUserWallet . "&eSystem=" . $eSystem . "&vStripeToken=" . $vStripeToken . "&type=" . $type . "&Platform=" . $Platform . "&tSessionId=" . $tSessionId . "&GeneralMemberId=" . $GeneralMemberId . "&GeneralUserType=" . $GeneralUserType . "&GeneralDeviceType=" . $GeneralDeviceType . "&GeneralAppVersion=" . $GeneralAppVersion . "&vTimeZone=" . $vTimeZone . "&vUserDeviceCountry=" . $vUserDeviceCountry . "&iServiceId=" . $iServiceId . "&vCurrentTime=" . $vCurrentTime . "&payStatus=" . $status . "&vPayMethod=" . $vPayMethod;
                $redirectUrl = $tconfig['tsite_url'] . $returnUrl . $extraParameters;
                //echo $redirectUrl;die;
                header('Location: ' . $redirectUrl); //Redirect for Update Database Table Process
            }
            ?>
            <?php
        } else {
            if (!isset($result)) {
                $result = null;
            }
            ?>
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
                            <form action="<?php echo Braintree_TransparentRedirect::url() ?>" method="POST" id="paymentFrm" autocomplete="off">
                                <div class="back-img" style="padding-top: 20px;"><img src="img/card.png"></div>
                                <span style="color: red;" class="payment-errors"></span>
                                <label class="field" for="adyen-encrypted-form-number">
                                    <b class="class-box" >
                                        <?php braintree_text_field('Card Number', 'transaction[credit_card][number]', $result, "Credit card number must be 12-19 digits"); ?>
                                    </b>
                                </label>
                                <span id="cardType"></span>
                                <label class="field" for="adyen-encrypted-form-cvc">
                                    <b class="class-box">
                                        <?php braintree_text_field('CVV', 'transaction[credit_card][cvv]', $result, "CVV must be 4 digits for American Express and 3 digits for other card types"); ?>
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
                                <label class="field-a" for="adyen-encrypted-form-expiry-month">
                                    <b  class="class-box-a">
                                        <?php braintree_text_field('Expiration Date', 'transaction[credit_card][expiration_date]', $result, "MM / YY"); ?>
                                    </b> 
                                </label>

                                <?php
                                $itemPrice = isset($_REQUEST["amount"]) ? $_REQUEST["amount"] : 0;
                                $tr_data = Braintree_TransparentRedirect::transactionData(array('redirectUrl' => "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) . $extraParameters, 'transaction' => array('amount' => $itemPrice, 'type' => 'sale')))
                                ?>
                                <input type="hidden" name="tr_data" value="<?php echo $tr_data ?>" />
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
        <?php } ?>
    </body>
</html>