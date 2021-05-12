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
    include_once('../../../../include_config.php');
    $statusMsg = "Transaction has been failed";
    $generalConfigPaymentArr = $generalobj->getGeneralVarAll_Payment_Array();
    $stripeSecretKey = $generalConfigPaymentArr['STRIPE_SECRET_KEY'];
    $stripePublishKey = $generalConfigPaymentArr['STRIPE_PUBLISH_KEY'];
    //echo "<pre>";
    //print_r($_REQUEST);die;
    $UserType= isset($_REQUEST["UserType"]) ? $_REQUEST["UserType"] : '';
    $iUserId = isset($_REQUEST["iUserId"]) ? $_REQUEST["iUserId"] : '';
    $iServiceId= isset($_REQUEST["iServiceId"]) ? $_REQUEST["iServiceId"] : '';

    if ($UserType == "Rider") {
        $sql = "SELECT iUserId,eStatus,vLang FROM `register_user` WHERE iUserId='".$iUserId."'";
    } else {
        $sql = "SELECT iDriverId,eStatus,vLang FROM `register_driver` WHERE iDriverId='".$iUserId."'";
    }
    $userData = $obj->MySQLSelect($sql);
    $vLang = $userData[0]['vLang'];
    if ($vLang == "") {
        $vLang = get_value('language_master', 'vCode', 'eDefault', 'Yes', '', 'true');
    }

    $languageLabelsArr = $generalobj->getLanguageLabelsArr($vLang, "1", $iServiceId);
    ?>
    <script type="text/javascript">
        //set your publishable key
        Stripe.setPublishableKey('<?php echo $stripePublishKey; ?>');
        //callback to handle the response from stripe
        function stripeResponseHandler(status, response) {
            //console.log(response);
            //return false;
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
                //var newurl = url + "&paymentprocess=yes";
                //window.history.pushState({path: newurl}, '', newurl);
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
    $itemPrice = isset($_REQUEST["amount"]) ? $_REQUEST["amount"] : 1;
    $currency = isset($_REQUEST["ccode"]) ? $_REQUEST["ccode"] : 'USD';
    $themeColor = isset($_REQUEST["AppThemeColor"]) ? $_REQUEST["AppThemeColor"] : '000000';
    $textColor = isset($_REQUEST["AppThemeTxtColor"]) ? $_REQUEST["AppThemeTxtColor"] : 'FFFFFF';
    $stripeamount= isset($_REQUEST["stripeamount"]) ? $_REQUEST["stripeamount"] : 50;
    
    $UniqueCode= isset($_REQUEST["UniqueCode"]) ? $_REQUEST["UniqueCode"] : '';

    $eForTip= isset($_REQUEST["eForTip"]) ? $_REQUEST["eForTip"] : 'No';
    $DebitAmt= isset($_REQUEST["DebitAmt"]) ? $_REQUEST["DebitAmt"] : '';
    $iTripId= isset($_REQUEST["iTripId"]) ? $_REQUEST["iTripId"] : '';

    if (isset($_POST['stripeToken'])) {
        $token = isset($_POST["stripeToken"]) ? $_POST["stripeToken"] : '';
        $name = isset($_POST["name"]) ? $_POST["name"] : '';
        $currency = isset($_POST["ccurrency"]) ? $_POST["ccurrency"] : '';
        $email = isset($_POST["email"]) ? $_POST["email"] : '';
        $card_num = isset($_POST["card_num"]) ? $_POST["card_num"] : '';
        $card_cvc = isset($_POST["cvc"]) ? $_POST["cvc"] : '';
        $card_exp_month = isset($_POST["exp_month"]) ? $_POST["exp_month"] : '';
        $card_exp_year = isset($_POST["exp_year"]) ? $_POST["exp_year"] : '';

        $returnUrl = isset($_REQUEST['returnUrl']) ? trim($_REQUEST['returnUrl']) : 'payment_result.php';
        //include Stripe PHP library
        require_once('stripe-php/init.php');
        //set api key
        //print_r($currency);die;
        $stripe = array(
            "secret_key" => $stripeSecretKey,
            "publishable_key" => $stripePublishKey
        );
        /*ini_set("display_errors", 1);
        error_reporting(E_ALL);*/
        /*print_r($stripe);die;*/
        \Stripe\Stripe::setApiKey($stripe['secret_key']);
        //item information
        if($eForTip == 'Yes'){
            $tDescription = "Amount Debit for " . $UserType;
        } else {
            $tDescription = "Amount Add for " . $UserType;
        }
        //charge a credit or a debit card

        $charge = \Stripe\Charge::create(array(
                    "amount" => $stripeamount,
                    "currency" => $currency,
                    "source" => $token,
                    "description" => $tDescription
        ));
        //retrieve charge details
        $chargeJson = $charge->jsonSerialize();
       /* echo "<pre>";
        print_r($chargeJson);die;*/
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
                //$iServiceId = isset($_REQUEST["iServiceId"]) ? $_REQUEST["iServiceId"] : '';
                $vUserDeviceCountry = isset($_REQUEST["vUserDeviceCountry"]) ? $_REQUEST["vUserDeviceCountry"] : '';

                $extraParameters = "?iUserId=" . $iUserId ."&UserType=" . $UserType . "&amount=" . $itemPrice . "&ccode=" . $currency . "&vUserDeviceCountry=" . $vUserDeviceCountry . "&returnUrl=" . urlencode($returnUrl) . "&AppThemeColor=" . $themeColor . "&AppThemeTxtColor=" . $textColor."&UniqueCode=".$UniqueCode."&eForTip=".$eForTip."&iTripId=".$iTripId."&DebitAmt=".$DebitAmt;

                $redirectUrl = $returnUrl . $extraParameters . "&payStatus=" . $status;
                header('Location: ' . $redirectUrl);  exit;
                ?><script>window.location.replace("<?php echo $redirectUrl; ?>");
                </script>
                <?php
                //header('Location: ' . $redirectUrl); //Redirect for Update Database Table Process
                die;
            } else {
                header('Location: ' . $redirectUrl . "?payStatus=Fail"); //Redirect for Update Database Table Process
                echo $statusMsg;
                die;
            }
        } else {
            header('Location: ' . $redirectUrl . "?payStatus=Fail"); //Redirect for Update Database Table Process
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
                    <lagel class="card-form">
                        <!-- stripe payment form -->
                        <?php if ($itemPrice != "") { ?>
                            <div class="our-work-new" style="background-color: #<?php echo $themeColor; ?>;color: #<?php echo $textColor; ?>;">
                                <span class="our-text" style="font-size: 18px;padding: 10px;">Pay : <?php echo round($itemPrice,2); ?><?if($eForTip == 'Yes'){?><br/><? echo round($DebitAmt,2) ." ".$languageLabelsArr['LBL_WALLET_DEDUCT_TXT']; } ?></span>
                            </div>
                        <?php } ?>
                        <form action="" method="POST" id="paymentFrm">
                            <label class="back-img" style="padding-top: 20px;"><img src="img/card.png"></label>
                            <span style="color: red;" class="payment-errors"></span>
                            <label class="our-work-new new-one">
                                <span class="our-text">Card Number</span>
                            </label>
                            <label class="field" for="adyen-encrypted-form-number">
                                <b class="class-box" >
                                    <input class="card-number"  type="number" id="adyen-encrypted-form-number class-box" size="20" autocomplete="off" maxlength="20" data-encrypted-name="number" placeholder="Card Number" onKeyPress="return isNumber(event)" required=""/>
                                </b>
                            </label>
                            <span id="cardType"></span>
                            <label class="our-work-new new-one">
                                <span class="our-text">CVV</span>
                            </label>
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
                            <label class="our-work-new new-one">     
                                <span class="our-text">Expiration (MM/YYYY)</span>
                            </label>
                            <label class="field-a" for="adyen-encrypted-form-expiry-month">

                                <input class="card-expiry-month" inputmethod="numeric" type="number" onKeyUp="this.value = minmax(this.value, '', 12)"  id="adyen-encrypted-form-expiry-month" maxlength="2" size="2" autocomplete="off" onKeyPress="return isNumber(event)" data-encrypted-name="expiryMonth" placeholder="MM" />

                            </label>
                            <input type="hidden" value="<?= $currency; ?>" name="ccurrency">
                            <label  class="float-work"> <img src="img/line.jpg"></label>
                            <label  class="class-box-b">
                                <input class="card-expiry-year" type="text" id="adyen-encrypted-form-expiry-year" maxlength="4" size="4" autocomplete="off" data-encrypted-name="expiryYear" onKeyPress="return isNumber(event)" placeholder="YYYY" />
                            </label>
                            <label class="work-card" align="center"> 
                                <div class="card-num-a"  align="center">     
                                    <button type="submit" class="button-num" id="payBtn">Submit Payment</button>
                                </div>
                            </label>
                        </form>
                        </label>
                </div>

            </div>
        </div>
    </body>
</html>