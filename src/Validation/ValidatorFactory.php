<?php

namespace tomi20v\phalswag\Validation;

use Phalcon\Config;

/**
 * Class ValidationValidatorFactory - I am a DI shim so you can replace validators in your tests...
 *
 * @package tomi20v\phalswag
 */
class ValidatorFactory {

	protected $_validatorClasses = [
		'Callback' => 'tomi20v\phalswag\Validation\Validator\Callback',
		'MultiEnum' => 'tomi20v\phalswag\Validation\Validator\MultiEnum',
		'*' => 'Phalcon\Validation\Validator\\',
	];

	/**
	 * @param string $rule
	 * @param array $options
	 * @return mixed
	 */
	public function buildValidator($rule, array $options = []) {
		$className = array_key_exists($rule, $this->_validatorClasses)
			? $this->_validatorClasses[$rule]
			: $this->_validatorClasses['*'] . $rule;
		$Validator = new $className($options);
		return $Validator;
	}

}
