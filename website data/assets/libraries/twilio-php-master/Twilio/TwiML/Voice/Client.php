<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\TwiML\Voice;

use Twilio\TwiML\TwiML;

class Client extends TwiML {
    /**
     * Client constructor.
     * 
     * @param string $name Client name
     * @param array $attributes Optional attributes
     */
    public function __construct($name, $attributes = array()) {
        parent::__construct('Client', $name, $attributes);
    }

    /**
     * Add Url attribute.
     * 
     * @param url $url Client URL
     * @return TwiML $this.
     */
    public function setUrl($url) {
        return $this->setAttribute('url', $url);
    }

    /**
     * Add Method attribute.
     * 
     * @param httpMethod $method Client URL Method
     * @return TwiML $this.
     */
    public function setMethod($method) {
        return $this->setAttribute('method', $method);
    }

    /**
     * Add StatusCallbackEvent attribute.
     * 
     * @param enum:Event $statusCallbackEvent Events to trigger status callback
     * @return TwiML $this.
     */
    public function setStatusCallbackEvent($statusCallbackEvent) {
        return $this->setAttribute('statusCallbackEvent', $statusCallbackEvent);
    }

    /**
     * Add StatusCallback attribute.
     * 
     * @param url $statusCallback Status Callback URL
     * @return TwiML $this.
     */
    public function setStatusCallback($statusCallback) {
        return $this->setAttribute('statusCallback', $statusCallback);
    }

    /**
     * Add StatusCallbackMethod attribute.
     * 
     * @param httpMethod $statusCallbackMethod Status Callback URL Method
     * @return TwiML $this.
     */
    public function setStatusCallbackMethod($statusCallbackMethod) {
        return $this->setAttribute('statusCallbackMethod', $statusCallbackMethod);
    }
}