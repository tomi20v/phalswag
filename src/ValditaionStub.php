<?php

namespace tomi20v\phalswag;

use Phalcon\Validation;
use Phalcon\Validation\MessageInterface;

/**
 * Class ValidationStub
 *
 * @package tomi20v\phalswag
 */
class ValidationStub extends Validation {

	protected $_value;

	protected $_messages = [];

	public function setValue($value) {
		$this->_messages = [];
		$this->_value = $value;
		return $this;
	}

	public function appendMessage(MessageInterface $message) {
		$this->_messages[$message->getField()] = $message->getMessage();
		return $this;
	}

	public function getValue($field) {
		return $this->_value;
	}

	public function getMessages() {
		return $this->_messages;
	}

}
