<?php

namespace tomi20v\phalswag\Validation\Validator;

use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;
use Phalcon\Validation;

/**
 * Class ValidationValidatorCallback - I validate if all items in an array is an enum of my param
 *
 * @package tomi20v\phalswag
 */
class MultiEnum extends Validator implements ValidatorInterface {

	/**
	 * I do extra check so $this->_options['enum'] is an array
	 * @param null $options
	 * @throws \Exception
	 */
	public function __construct($options = null) {
		if (!is_array($options['enum'])) {
			throw new \Exception('expected array as enum');
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
		if (!empty($value)) {
			foreach ($value as $eachValue) {
				if (!in_array($eachValue, $this->_options['enum'])) {
					$message = isset($this->_options['message'])
						? $this->_options['message']
						: 'validation failed';
					$validator->appendMessage(new \Phalcon\Validation\Message($message, $attribute, 'callback'));
					return false;
				}
			}
		}
		return true;
	}

}
