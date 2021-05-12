<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Monitor\V1;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class EventTest extends HolodeckTestCase {
    public function testFetchRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->monitor->v1->events("AEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")->fetch();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://monitor.twilio.com/v1/Events/AEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'
        ));
    }

    public function testFetchResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "actor_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "actor_type": "account",
                "description": null,
                "event_data": {
                    "friendly_name": {
                        "previous": "SubAccount Created at 2014-10-03 09:48 am",
                        "updated": "Mr. Friendly"
                    }
                },
                "event_date": "2014-10-03T16:48:25Z",
                "event_type": "account.updated",
                "links": {
                    "actor": "https://api.twilio.com/2010-04-01/Accounts/ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                    "resource": "https://api.twilio.com/2010-04-01/Accounts/ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
                },
                "resource_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "resource_type": "account",
                "sid": "AEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "source": "api",
                "source_ip_address": "10.86.6.250",
                "url": "https://monitor.twilio.com/v1/Events/AEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
            }
            '
        ));

        $actual = $this->twilio->monitor->v1->events("AEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")->fetch();

        $this->assertNotNull($actual);
    }

    public function testReadRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->monitor->v1->events->read();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://monitor.twilio.com/v1/Events'
        ));
    }

    public function testReadFullResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "events": [
                    {
                        "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "actor_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "actor_type": "account",
                        "description": null,
                        "event_data": {
                            "friendly_name": {
                                "previous": "SubAccount Created at 2014-10-03 09:48 am",
                                "updated": "Mr. Friendly"
                            }
                        },
                        "event_date": "2014-10-03T16:48:25Z",
                        "event_type": "account.updated",
                        "links": {
                            "actor": "https://api.twilio.com/2010-04-01/Accounts/ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                            "resource": "https://api.twilio.com/2010-04-01/Accounts/ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
                        },
                        "resource_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "resource_type": "account",
                        "sid": "AEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "source": "api",
                        "source_ip_address": "10.86.6.250",
                        "url": "https://monitor.twilio.com/v1/Events/AEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
                    }
                ],
                "meta": {
                    "first_page_url": "https://monitor.twilio.com/v1/Events?PageSize=50&Page=0",
                    "key": "events",
                    "next_page_url": null,
                    "page": 0,
                    "page_size": 50,
                    "previous_page_url": null,
                    "url": "https://monitor.twilio.com/v1/Events?PageSize=50&Page=0"
                }
            }
            '
        ));

        $actual = $this->twilio->monitor->v1->events->read();

        $this->assertGreaterThan(0, count($actual));
    }

    public function testReadEmptyResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "events": [],
                "meta": {
                    "first_page_url": "https://monitor.twilio.com/v1/Events?PageSize=50&Page=0",
                    "key": "events",
                    "next_page_url": null,
                    "page": 0,
                    "page_size": 50,
                    "previous_page_url": null,
                    "url": "https://monitor.twilio.com/v1/Events?PageSize=50&Page=0"
                }
            }
            '
        ));

        $actual = $this->twilio->monitor->v1->events->read();

        $this->assertNotNull($actual);
    }
}