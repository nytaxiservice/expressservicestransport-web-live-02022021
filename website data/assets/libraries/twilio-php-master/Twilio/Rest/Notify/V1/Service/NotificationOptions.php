<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Notify\V1\Service;

use Twilio\Options;
use Twilio\Values;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 */
abstract class NotificationOptions {
    /**
     * @param string $identity The identity
     * @param string $tag The tag
     * @param string $body The body
     * @param string $priority The priority
     * @param integer $ttl The ttl
     * @param string $title The title
     * @param string $sound The sound
     * @param string $action The action
     * @param array $data The data
     * @param array $apn The apn
     * @param array $gcm The gcm
     * @param array $sms The sms
     * @param array $facebookMessenger The facebook_messenger
     * @param array $fcm The fcm
     * @param string $segment The segment
     * @param array $alexa The alexa
     * @param string $toBinding The to_binding
     * @return CreateNotificationOptions Options builder
     */
    public static function create($identity = Values::NONE, $tag = Values::NONE, $body = Values::NONE, $priority = Values::NONE, $ttl = Values::NONE, $title = Values::NONE, $sound = Values::NONE, $action = Values::NONE, $data = Values::NONE, $apn = Values::NONE, $gcm = Values::NONE, $sms = Values::NONE, $facebookMessenger = Values::NONE, $fcm = Values::NONE, $segment = Values::NONE, $alexa = Values::NONE, $toBinding = Values::NONE) {
        return new CreateNotificationOptions($identity, $tag, $body, $priority, $ttl, $title, $sound, $action, $data, $apn, $gcm, $sms, $facebookMessenger, $fcm, $segment, $alexa, $toBinding);
    }
}

class CreateNotificationOptions extends Options {
    /**
     * @param string $identity The identity
     * @param string $tag The tag
     * @param string $body The body
     * @param string $priority The priority
     * @param integer $ttl The ttl
     * @param string $title The title
     * @param string $sound The sound
     * @param string $action The action
     * @param array $data The data
     * @param array $apn The apn
     * @param array $gcm The gcm
     * @param array $sms The sms
     * @param array $facebookMessenger The facebook_messenger
     * @param array $fcm The fcm
     * @param string $segment The segment
     * @param array $alexa The alexa
     * @param string $toBinding The to_binding
     */
    public function __construct($identity = Values::NONE, $tag = Values::NONE, $body = Values::NONE, $priority = Values::NONE, $ttl = Values::NONE, $title = Values::NONE, $sound = Values::NONE, $action = Values::NONE, $data = Values::NONE, $apn = Values::NONE, $gcm = Values::NONE, $sms = Values::NONE, $facebookMessenger = Values::NONE, $fcm = Values::NONE, $segment = Values::NONE, $alexa = Values::NONE, $toBinding = Values::NONE) {
        $this->options['identity'] = $identity;
        $this->options['tag'] = $tag;
        $this->options['body'] = $body;
        $this->options['priority'] = $priority;
        $this->options['ttl'] = $ttl;
        $this->options['title'] = $title;
        $this->options['sound'] = $sound;
        $this->options['action'] = $action;
        $this->options['data'] = $data;
        $this->options['apn'] = $apn;
        $this->options['gcm'] = $gcm;
        $this->options['sms'] = $sms;
        $this->options['facebookMessenger'] = $facebookMessenger;
        $this->options['fcm'] = $fcm;
        $this->options['segment'] = $segment;
        $this->options['alexa'] = $alexa;
        $this->options['toBinding'] = $toBinding;
    }

    /**
     * The identity
     * 
     * @param string $identity The identity
     * @return $this Fluent Builder
     */
    public function setIdentity($identity) {
        $this->options['identity'] = $identity;
        return $this;
    }

    /**
     * The tag
     * 
     * @param string $tag The tag
     * @return $this Fluent Builder
     */
    public function setTag($tag) {
        $this->options['tag'] = $tag;
        return $this;
    }

    /**
     * The body
     * 
     * @param string $body The body
     * @return $this Fluent Builder
     */
    public function setBody($body) {
        $this->options['body'] = $body;
        return $this;
    }

    /**
     * The priority
     * 
     * @param string $priority The priority
     * @return $this Fluent Builder
     */
    public function setPriority($priority) {
        $this->options['priority'] = $priority;
        return $this;
    }

    /**
     * The ttl
     * 
     * @param integer $ttl The ttl
     * @return $this Fluent Builder
     */
    public function setTtl($ttl) {
        $this->options['ttl'] = $ttl;
        return $this;
    }

    /**
     * The title
     * 
     * @param string $title The title
     * @return $this Fluent Builder
     */
    public function setTitle($title) {
        $this->options['title'] = $title;
        return $this;
    }

    /**
     * The sound
     * 
     * @param string $sound The sound
     * @return $this Fluent Builder
     */
    public function setSound($sound) {
        $this->options['sound'] = $sound;
        return $this;
    }

    /**
     * The action
     * 
     * @param string $action The action
     * @return $this Fluent Builder
     */
    public function setAction($action) {
        $this->options['action'] = $action;
        return $this;
    }

    /**
     * The data
     * 
     * @param array $data The data
     * @return $this Fluent Builder
     */
    public function setData($data) {
        $this->options['data'] = $data;
        return $this;
    }

    /**
     * The apn
     * 
     * @param array $apn The apn
     * @return $this Fluent Builder
     */
    public function setApn($apn) {
        $this->options['apn'] = $apn;
        return $this;
    }

    /**
     * The gcm
     * 
     * @param array $gcm The gcm
     * @return $this Fluent Builder
     */
    public function setGcm($gcm) {
        $this->options['gcm'] = $gcm;
        return $this;
    }

    /**
     * The sms
     * 
     * @param array $sms The sms
     * @return $this Fluent Builder
     */
    public function setSms($sms) {
        $this->options['sms'] = $sms;
        return $this;
    }

    /**
     * The facebook_messenger
     * 
     * @param array $facebookMessenger The facebook_messenger
     * @return $this Fluent Builder
     */
    public function setFacebookMessenger($facebookMessenger) {
        $this->options['facebookMessenger'] = $facebookMessenger;
        return $this;
    }

    /**
     * The fcm
     * 
     * @param array $fcm The fcm
     * @return $this Fluent Builder
     */
    public function setFcm($fcm) {
        $this->options['fcm'] = $fcm;
        return $this;
    }

    /**
     * The segment
     * 
     * @param string $segment The segment
     * @return $this Fluent Builder
     */
    public function setSegment($segment) {
        $this->options['segment'] = $segment;
        return $this;
    }

    /**
     * The alexa
     * 
     * @param array $alexa The alexa
     * @return $this Fluent Builder
     */
    public function setAlexa($alexa) {
        $this->options['alexa'] = $alexa;
        return $this;
    }

    /**
     * The to_binding
     * 
     * @param string $toBinding The to_binding
     * @return $this Fluent Builder
     */
    public function setToBinding($toBinding) {
        $this->options['toBinding'] = $toBinding;
        return $this;
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Notify.V1.CreateNotificationOptions ' . implode(' ', $options) . ']';
    }
}