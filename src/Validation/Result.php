<?php

namespace tomi20v\phalswag\Validation;

/**
 * Class Result
 */
class Result {

	private $_errors = [];

	/**
	 * Result constructor.
	 * @param $errors
	 */
	public function __construct($errors) {
		$this->_errors = $errors;
	}

	public function isSuccess() {
		return count($this->_errors) == 0;
	}

	public function getErrors() {
		return $this->_errors;
	}

}
