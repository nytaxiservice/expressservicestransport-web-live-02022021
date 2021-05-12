<?php

#ref https://github.com/omise/omise-php
require_once dirname(__FILE__).'/config.php';
require_once dirname(__FILE__).'/lib/Omise.php';

$customer = OmiseCustomer::retrieve("cust_test_553sk4x0g0ydw9s7704");

$customer = (array)$customer;
// echo json_encode($customer);

// echo "<pre>";
// print_r($customer);

foreach($customer as $k => $v){
	if(is_array($v)){
		$cust['id'] = $v['id'];
		$cust['default_card'] = $v['default_card'];
	}
}

echo "<pre>";
print_r($cust);





/*

{
OmiseApiResourceOMISE_CONNECTTIMEOUT: 30,
OmiseApiResourceOMISE_TIMEOUT: 60,
*_values: {
object: "list",
from: "1970-01-01T00:00:00+00:00",
to: "2016-08-24T05:16:01+00:00",
offset: 0,
limit: 20,
total: 9,
order: "chronological",
location: "/customers",
data: [
{
object: "customer",
id: "cust_test_553hyu5r2loom82ie8f",
livemode: false,
location: "/customers/cust_test_553hyu5r2loom82ie8f",
default_card: "card_test_553hyop2ssdsdsj1uvr0vt5l7",
email: "chai1@ssss.com",
description: "Chai (id:1)",
created: "2016-08-23T10:33:48Z",
cards: {
object: "list",
from: "1970-01-01T00:00:00+00:00",
to: "2016-08-24T05:16:01+00:00",
offset: 0,
limit: 20,
total: 1,
order: null,
location: "/customers/cust_test_553hyu5r2loom82ie8f/cards",
]}
*/
?>
