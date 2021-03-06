<?php
header("Content-Type:application/json");
$shortcode='600211';
$consumerkey    ="xl4L2oMBKPk9mgVa1OjjRgG23BOKHkPO";
$consumersecret ="Uuuou67rmUYAHVlu";
$validationurl="http://cubejek.bbcsproducts.com/assets/libraries/Mpesa/validation.php";
$confirmationurl="http://cubejek.bbcsproducts.com/assets/libraries/Mpesa/confirmation.php";
/* testing environment, comment the below two lines if on production */
$authenticationurl='https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
$registerurl = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';
/* production un-comment the below two lines if you are in production */
//$authenticationurl='https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
//$registerurl = 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl';
$credentials= base64_encode($consumerkey.':'.$consumersecret);
$username=$consumerkey ;
$password=$consumersecret;
  // Request headers
  $headers = array(  
    'Content-Type: application/json; charset=utf-8'
  );
  // Request
  $ch = curl_init($authenticationurl);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  //curl_setopt($ch, CURLOPT_HEADER, TRUE); // Includes the header in the output
  curl_setopt($ch, CURLOPT_HEADER, FALSE); // excludes the header in the output
  curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password); // HTTP Basic Authentication
  $result = curl_exec($ch);  
  $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
$result = json_decode($result);
$access_token=$result->access_token;
curl_close($ch);

//Transaction urls
$url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$access_token));


$curl_post_data = array(
        //Fill in the request parameters with valid values
       'ShortCode' => $shortcode,
       'CommandID' => 'CustomerPayBillOnline',
       'Amount' => '1',
       'Msisdn' => '254708374149',   // MSISDN (phone number) sending the transaction, start with country code without the plus(+) sign.
       'BillRefNumber' => 'A00000'
);

$data_string = json_encode($curl_post_data);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

$curl_response = curl_exec($curl);
print_r($curl_response);

//echo $curl_response;
?>