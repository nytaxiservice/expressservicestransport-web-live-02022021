<?php

ini_set("display_errors", 1);
error_reporting(E_ALL);
#ref https://github.com/omise/omise-php
require_once dirname(__FILE__) . '/config.php';
require_once dirname(__FILE__) . '/lib/Omise.php';
//print_r($_POST);die;
if (isset($_POST['omise_token']) && $_POST['omise_token'] != "") {
    $card_token = $_POST['omise_token'];
    $price = $_POST['price'];
#Step1 create customer first
    /* $customer = OmiseCustomer::create([
      'email' => $email,
      'description' => $holder_name,
      'card' => $card_token
      ]); */
#Step2 charge money
    $vOrderNo = "24324234";
    $tDescription = "Amount charge for order no" . $vOrderNo;
    $charge = OmiseCharge::create(array(
                "amount" => $price,
                "currency" => "thb",
                "card" => $card_token,
                "description" => $tDescription
    ));
    echo "<pre>";
    print_r($charge);
    die;
}

/*
  OmiseCustomer Object
  (
  [OMISE_CONNECTTIMEOUT:OmiseApiResource:private] => 30
  [OMISE_TIMEOUT:OmiseApiResource:private] => 60
  [_values:protected] => Array
  (
  [object] => customer
  [id] => cust_test_853sk4x0g0ydw9s7704
  [livemode] =>
  [location] => /customers/cust_test_5453sk4x0g0ydw9s7704
  [default_card] => card_test_5253sk4upsf76h4f8p1l
  [email] => daikyo@abc.com
  [description] => Daikyo
  [created] => 2016-08-24T04:36:59Z
  [cards] => Array
  (
  [object] => list
  [from] => 1970-01-01T00:00:00+00:00
  [to] => 2016-08-24T04:36:59+00:00
  [offset] => 0
  [limit] => 20
  [total] => 1
  [order] =>
  [location] => /customers/cust_test_5453sk4x0g0ydw9s7704/cards
  [data] => Array
  (
  [0] => Array
  (
  [object] => card
  [id] => card_test_553sxk4upsf76h4f8p1l
  [livemode] =>
  [location] => /customers/cust_test_553sk4x03g0ydw9s7704/cards/card_test_553sk4xupsf76h4f8p1l
  [country] => us
  [city] =>
  [postal_code] =>
  [financing] =>
  [bank] =>
  [last_digits] => 4242
  [brand] => Visa
  [expiration_month] => 8
  [expiration_year] => 2028
  [fingerprint] => wRHnsgn/CSUS5JbmdxnVU/U++p4MO7cSxccaLDLxy9bNdc=
  [name] => Daikyo
  [security_code_check] => 1
  [created] => 2016-08-24T04:36:59Z
  )

  )

  )

  )

  [_secretkey:protected] => skey_test_bb51z5fp9zfnev8d3dqww
  [_publickey:protected] => pkey_test_c433451z4rr9fpvp546yfysp
  )
 */
?>

