<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\TwiML\Voice;

use Twilio\TwiML\TwiML;

class Say extends TwiML {
    /**
     * Say constructor.
     * 
     * @param string $message Message to say
     * @param array $attributes Optional attributes
     */
    public function __construct($message, $attributes = array()) {
        parent::__construct('Say', $message, $attributes);
    }

    /**
     * Add Voice attribute.
     * 
     * @param enum:Voice $voice Voice to use
     * @return TwiML $this.
     */
    public function setVoice($voice) {
        return $this->setAttribute('voice', $voice);
    }

    /**
     * Add Loop attribute.
     * 
     * @param integer $loop Times to loop message
     * @return TwiML $this.
     */
    public function setLoop($loop) {
        return $this->setAttribute('loop', $loop);
    }

    /**
     * Add Language attribute.
     * 
     * @param enum:Language $language Message langauge
     * @return TwiML $this.
     */
    public function setLanguage($language) {
        return $this->setAttribute('language', $language);
    }
}