<?php

namespace tomi20v\phalswag\Mvc;

use Phalcon\Mvc\Model;
use tomi20v\phalswag\Swagger;

/**
 * Class Controller
 *
 * @package tomi20v\phalswag
 *
 * @property-read Swagger $Swagger
 * @property-read \Phalcon\Dispatcher $dispatcher
 * @property \tomi20v\phalswag\Service\SwaggerService SwaggerService
 */
abstract class Controller extends \Phalcon\Mvc\Controller {

	/** @var string can be defined with $_swaggerFname */
	protected static $_swaggerPath;

	/**
	 * @var string define this to automaticly load swagger config file
	 */
	protected static $_swaggerFname;

	/**
	 * @var Swagger
	 */
	protected $_Swagger;

	public function onConstruct() {
		if (!empty(static::$_swaggerFname)) {
			$swaggerPath = implode('/', [
				trim(static::$_swaggerPath, '/'),
				trim(static::$_swaggerFname, '/'),
			]);
			$this->_Swagger = $this->SwaggerService->getSwagger($swaggerPath);
		}
	}

	/**
	 * I will return a $Form object
	 *
	 * @param $operationId
	 * @param Model $Model
	 * @return \tomi20v\phalswag\Swagger\Operation
	 */
	protected function _getBoundSwaggerOperation($operationId, Model $Model) {

		$SwaggerOperation = $this->SwaggerService->getOperationById($operationId, $this->_Swagger);

		$this->SwaggerService->bindRequest($Model, $SwaggerOperation, $this->dispatcher->getParams(), $this->request);

		return $SwaggerOperation;

	}

}
