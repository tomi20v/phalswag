<?php

namespace tomi20v\phalswag\Validation;

/**
 * Class ValidationValidatorFactory - I am a DI shim so you can replace validators in your tests...
 *
 * @package tomi20v\phalswag
 */
class ValidatorFactory {

	protected $_validatorClasses = [
		'Callback' => 'tomi20v\\phalswag\\ValidationValidatorCallback',
		'ArrayEnum' => 'tomi20v\\phalswag\\MultiEnum',
		'*' => 'Phalcon\\Validation\\Validator\\',
	];

	function __construct($validatorClasses=null) {
		if (!is_null($validatorClasses)) {
			$this->_validatorClasses = array_merge($this->_validatorClasses, $validatorClasses);
		}
	}

	public function buildValidator($rule, $options=[]) {
		$className = array_key_exists($rule, $this->_validatorClasses)
			? $this->_validatorClasses[$rule]
			: $this->_validatorClasses['*'] . $rule;
		$Validator = new $className($options);
		return $Validator;
	}

}
