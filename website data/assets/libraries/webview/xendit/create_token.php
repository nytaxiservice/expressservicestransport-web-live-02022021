<?php

ini_set("display_errors", 1);
error_reporting(E_ALL);
require 'XenditPHPClient.php';

$options['secret_api_key'] = 'xnd_development_P4qDfOss0OCpl8RtKrROHjaQYNCk9dN5lSfk+R1l9Wbe+rSiCwZ3jw==';

  $xenditPHPClient = new XenditClient\XenditPHPClient($options);

  $external_id = 'sample-external-id-1475459775872';
  $token_id = '58e2096018b815f555c8a524';
  $amount = 2;
  $authentication_id = '58e2097218b815f555c8a526';
  $capture = false;

  $response = $xenditPHPClient->captureCreditCardPayment($external_id, $token_id, $amount);
  print_r($response);
?>