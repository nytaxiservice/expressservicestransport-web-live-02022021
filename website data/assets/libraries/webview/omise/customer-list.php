<?php

#ref https://github.com/omise/omise-php
require_once dirname(__FILE__).'/config.php';
require_once dirname(__FILE__).'/lib/Omise.php';

$customer = OmiseCustomer::retrieve();

// $omise = (array)$customer;

// echo json_encode($omise);

foreach ($customer as $key => $val) {
	if($key == 'data'){
		foreach ($val as $k => $value) {
			echo "<br/>==<br/>";
			//print_r($value);
			echo $value['object'];
			echo "<br/>";
			echo $value['id'];
		}
	}
}



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
default_card: "card_test_553hyop2j1uvr0vt5l7",
email: "chai1@sdfasdfdsf.com",
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
data: [
{
object: "card",
id: "card_test_553hyop2j1uvr0vt5l7",
livemode: false,
location: "/customers/cust_test_553hyu5r2loom82ie8f/cards/card_test_553hyop2j1uvr0vt5l7",
country: "us",
city: null,
postal_code: null,
financing: "",
bank: "",
last_digits: "4242",
brand: "Visa",
expiration_month: 12,
expiration_year: 2020,
fingerprint: "wRHnsgn/CSUS5JbmdxnVU/U++p4MO7cSxLDLxy9bNdc=",
name: "Chai1 Naja",
security_code_check: true,
created: "2016-08-23T10:33:23Z"
}
]
}
},
{
object: "customer",
id: "cust_test_553hzbwwbojon4q17gx",
livemode: false,
location: "/customers/cust_test_553hzbwwbojon4q17gx",
default_card: "card_test_553hz8pwy9wyjgv0w9j",
email: "chai1@ฟฟฟ.com",
description: "Chai (id:1)",
created: "2016-08-23T10:35:13Z",
cards: {
object: "list",
from: "1970-01-01T00:00:00+00:00",
to: "2016-08-24T05:16:01+00:00",
offset: 0,
limit: 20,
total: 1,
order: null,
location: "/customers/cust_test_553hzbwwbojon4q17gx/cards",
data: [
{
object: "card",
id: "card_test_553hz8pwy9wyjgv0w9j",
livemode: false,
location: "/customers/cust_test_553hzbwwbojon4q17gx/cards/card_test_553hz8pwy9wyjgv0w9j",
country: "us",
city: null,
postal_code: null,
financing: "",
bank: "",
last_digits: "4242",
brand: "Visa",
expiration_month: 11,
expiration_year: 2020,
fingerprint: "wRHnsgn/CSUS5JbmdxnVU/U++p4MO7cSxLDLxy9bNdc=",
name: "Chai01",
security_code_check: true,
created: "2016-08-23T10:34:57Z"
}
]
}
},
{
object: "customer",
id: "cust_test_553i13ekcbhd8qcnuti",
livemode: false,
location: "/customers/cust_test_553i13ekcbhd8qcnuti",
default_card: "card_test_553i0ua5dcafxgyz6o0",
email: "chai1@ๆๆๆ.com",
description: "Chai (id:1)",
created: "2016-08-23T10:40:13Z",
cards: {
object: "list",
from: "1970-01-01T00:00:00+00:00",
to: "2016-08-24T05:16:01+00:00",
offset: 0,
limit: 20,
total: 1,
order: null,
location: "/customers/cust_test_553i13ekcbhd8qcnuti/cards",
data: [
{
object: "card",
id: "card_test_553i0ua5dcafxgyz6o0",
livemode: false,
location: "/customers/cust_test_553i13ekcbhd8qcnuti/cards/card_test_553i0ua5dcafxgyz6o0",
country: "us",
city: null,
postal_code: null,
financing: "",
bank: "",
last_digits: "4242",
brand: "Visa",
expiration_month: 8,
expiration_year: 2016,
fingerprint: "wRHnsgn/CSUS5JbmdxnVU/U++p4MO7cSxLDLxy9bNdc=",
name: "Chai2",
security_code_check: true,
created: "2016-08-23T10:39:30Z"
}
]
}
},
{
object: "customer",
id: "cust_test_553i24cl4odaec36vhs",
livemode: false,
location: "/customers/cust_test_553i24cl4odaec36vhs",
default_card: "card_test_553i245ic3k0vdwwrfb",
email: "chai1@aaaa.com",
description: "Chai (id:1)",
created: "2016-08-23T10:43:08Z",
cards: {
object: "list",
from: "1970-01-01T00:00:00+00:00",
to: "2016-08-24T05:16:01+00:00",
offset: 0,
limit: 20,
total: 1,
order: null,
location: "/customers/cust_test_553i24cl4odaec36vhs/cards",
data: [
{
object: "card",
id: "card_test_553i245ic3k0vdwwrfb",
livemode: false,
location: "/customers/cust_test_553i24cl4odaec36vhs/cards/card_test_553i245ic3k0vdwwrfb",
country: "us",
city: null,
postal_code: null,
financing: "",
bank: "",
last_digits: "4242",
brand: "Visa",
expiration_month: 8,
expiration_year: 2016,
fingerprint: "wRHnsgn/CSUS5JbmdxnVU/U++p4MO7cSxLDLxy9bNdc=",
name: "Chai3",
security_code_check: true,
created: "2016-08-23T10:43:07Z"
}
]
}
},
{
object: "customer",
id: "cust_test_553i31es45xti9kqn37",
livemode: false,
location: "/customers/cust_test_553i31es45xti9kqn37",
default_card: "card_test_553i318hpgx4v3gaoar",
email: "chai1@aaaaa.com",
description: null,
created: "2016-08-23T10:45:45Z",
cards: {
object: "list",
from: "1970-01-01T00:00:00+00:00",
to: "2016-08-24T05:16:01+00:00",
offset: 0,
limit: 20,
total: 1,
order: null,
location: "/customers/cust_test_553i31es45xti9kqn37/cards",
data: [
{
object: "card",
id: "card_test_553i318hpgx4v3gaoar",
livemode: false,
location: "/customers/cust_test_553i31es45xti9kqn37/cards/card_test_553i318hpgx4v3gaoar",
country: "us",
city: null,
postal_code: null,
financing: "",
bank: "",
last_digits: "4242",
brand: "Visa",
expiration_month: 8,
expiration_year: 2016,
fingerprint: "wRHnsgn/CSUS5JbmdxnVU/U++p4MO7cSxLDLxy9bNdc=",
name: "Chai3",
security_code_check: true,
created: "2016-08-23T10:45:44Z"
}
]
}
},
{
object: "customer",
id: "cust_test_553i3d4vx14g3lmhg6b",
livemode: false,
location: "/customers/cust_test_553i3d4vx14g3lmhg6b",
default_card: "card_test_553i3d2iaf34w5mwoug",
email: "chai1@aaaa.com",
description: "Chai4",
created: "2016-08-23T10:46:40Z",
cards: {
object: "list",
from: "1970-01-01T00:00:00+00:00",
to: "2016-08-24T05:16:01+00:00",
offset: 0,
limit: 20,
total: 1,
order: null,
location: "/customers/cust_test_553i3d4vx14g3lmhg6b/cards",
data: [
{
object: "card",
id: "card_test_553i3d2iaf34w5mwoug",
livemode: false,
location: "/customers/cust_test_553i3d4vx14g3lmhg6b/cards/card_test_553i3d2iaf34w5mwoug",
country: "us",
city: null,
postal_code: null,
financing: "",
bank: "",
last_digits: "4242",
brand: "Visa",
expiration_month: 8,
expiration_year: 2016,
fingerprint: "wRHnsgn/CSUS5JbmdxnVU/U++p4MO7cSxLDLxy9bNdc=",
name: "Chai4",
security_code_check: true,
created: "2016-08-23T10:46:40Z"
}
]
}
},
{
object: "customer",
id: "cust_test_553i3kauidx4p876c46",
livemode: false,
location: "/customers/cust_test_553i3kauidx4p876c46",
default_card: "card_test_553i3k8pc6wt30hm7jn",
email: "chai5@ddfsdfs.com",
description: "Chai5",
created: "2016-08-23T10:47:14Z",
cards: {
object: "list",
from: "1970-01-01T00:00:00+00:00",
to: "2016-08-24T05:16:01+00:00",
offset: 0,
limit: 20,
total: 1,
order: null,
location: "/customers/cust_test_553i3kauidx4p876c46/cards",
data: [
{
object: "card",
id: "card_test_553i3k8pc6wt30hm7jn",
livemode: false,
location: "/customers/cust_test_553i3kauidx4p876c46/cards/card_test_553i3k8pc6wt30hm7jn",
country: "us",
city: null,
postal_code: null,
financing: "",
bank: "",
last_digits: "4242",
brand: "Visa",
expiration_month: 8,
expiration_year: 2016,
fingerprint: "wRHnsgn/CSUS5JbmdxnVU/U++p4MO7cSxLDLxy9bNdc=",
name: "Chai5",
security_code_check: true,
created: "2016-08-23T10:47:14Z"
}
]
}
},
{
object: "customer",
id: "cust_test_553rpjd2znleh6hcb1v",
livemode: false,
location: "/customers/cust_test_553rpjd2znleh6hcb1v",
default_card: "card_test_553rpjak58933vpkt1s",
email: "chai4@sdfsdf.com",
description: "Chai4",
created: "2016-08-24T03:10:04Z",
cards: {
object: "list",
from: "1970-01-01T00:00:00+00:00",
to: "2016-08-24T05:16:01+00:00",
offset: 0,
limit: 20,
total: 1,
order: null,
location: "/customers/cust_test_553rpjd2znleh6hcb1v/cards",
data: [
{
object: "card",
id: "card_test_553rpjak58933vpkt1s",
livemode: false,
location: "/customers/cust_test_553rpjd2znleh6hcb1v/cards/card_test_553rpjak58933vpkt1s",
country: "us",
city: null,
postal_code: null,
financing: "",
bank: "",
last_digits: "4242",
brand: "Visa",
expiration_month: 8,
expiration_year: 2016,
fingerprint: "wRHnsgn/CSUS5JbmdxnVU/U++p4MO7cSxLDLxy9bNdc=",
name: "Chai4",
security_code_check: true,
created: "2016-08-24T03:10:03Z"
}
]
}
},
{
object: "customer",
id: "cust_test_553sk4x0g0ydw9s7704",
livemode: false,
location: "/customers/cust_test_553sk4x0g0ydw9s7704",
default_card: "card_test_553sk4upsf76h4f8p1l",
email: "daikyo@dsfsdf.com",
description: "Daikyo",
created: "2016-08-24T04:36:59Z",
cards: {
object: "list",
from: "1970-01-01T00:00:00+00:00",
to: "2016-08-24T05:16:01+00:00",
offset: 0,
limit: 20,
total: 1,
order: null,
location: "/customers/cust_test_553sk4x0g0ydw9s7704/cards",
data: [
{
object: "card",
id: "card_test_553sk4upsf76h4f8p1l",
livemode: false,
location: "/customers/cust_test_553sk4x0g0ydw9s7704/cards/card_test_553sk4upsf76h4f8p1l",
country: "us",
city: null,
postal_code: null,
financing: "",
bank: "",
last_digits: "4242",
brand: "Visa",
expiration_month: 8,
expiration_year: 2028,
fingerprint: "wRHnsgn/CSUS5JbmdxnVU/U++p4MO7cSxLDLxy9bNdc=",
name: "Daikyo",
security_code_check: true,
created: "2016-08-24T04:36:59Z"
}
]
}
}
]
},
*_secretkey: "skey_test_x51z5fp9zfnev8d3dqww",
*_publickey: "pkey_test_51zx4rr9fpvp546yfysp"
}
*/
?>
