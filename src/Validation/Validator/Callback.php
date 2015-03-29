<?php

namespace tomi20v\phalswag\Validation\Validator;

use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;
use Phalcon\Validation;

/**
 * Class ValidationValidatorCallback - I validate using a passed callback
 *
 * @package tomi20v\phalswag
 */
class Callback extends Validator implements ValidatorInterface {

	/**
	 * I do extra check so $this->_options['callback'] is a callable for sure
	 * @param null $options
	 * @throws \Exception
	 */
	public function __construct($options = null) {
		if (!is_callable($options['callback'])) {
			throw new \Exception('expected valid callback');
		}
		parent::__construct($options);
	}

	/**
	 * @see \Phalcon\Validation\Validator::validate
	 * @param Validation $validator
	 * @param $attribute
	 * @return bool
	 */
	public function validate(Validation $validator, $attribute) {
		$value = $validator->getValue($attribute);
		$result = call_user_func($this->_options['callback'], $value);
		if (!$result) {
			$message = isset($this->_options['message'])
				? $this->_options['message']
				: 'validation failed';
			$validator->appendMessage(new Message($message, $attribute, 'callback'));
		}
		return $result ? true : false;
	}

}
