<?php

namespace tomi20v\phalswag;

use Phalcon\Mvc\Model;
use tomi20v\phalswag\Model\Swagger\Operation;
use tomi20v\phalswag\Validation\Result;

/**
 * Class Validator
 */
class Validator {

	/**
	 * @param Model $Model
	 * @param \tomi20v\phalswag\Model\Swagger\Operation $Operation
	 * @return Result
	 */
	public function validate(Model $Model, Operation $Operation) {

		$errors = [];

		$Result = new Result($errors);

		return $Result;

	}

}
