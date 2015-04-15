<?php

namespace tomi20v\phalswag\Mvc;

use Phalcon\Http\Response;
use Phalcon\Mvc\Model;
use tomi20v\phalswag\Swagger\Operation;

/**
 * Class Controller
 *
 * @package tomi20v\phalswag
 *
 * @property-read \tomi20v\phalswag\Swagger $Swagger
 * @property-read \Phalcon\Dispatcher $dispatcher
 */
abstract class Controller extends \Phalcon\Mvc\Controller {

	/**
	 * @var string define this to automaticly load swagger config file
	 */
	protected static $_swaggerFname;

	/**
	 * @var \Phalcon\Config
	 */
	protected $_SwaggerConfig;

	public function onConstruct() {
		if (!empty(static::$_swaggerFname)) {
			$this->_SwaggerConfig = $this->Swagger->getReader()->read(static::$_swaggerFname);
		}
	}

	public function buildResponse($success, $data, $meta=[]) {
		$Response = new Response;
		$content = $success
			? [
				'success' => true,
				'data' => $data,
				'meta' => $meta,
			]
			: [
				'success' => false,
				'errors' => $data,
			];
		$Response->setJsonContent($content);
		return $Response;
	}

	/**
	 * I will return a $Form object
	 *
	 * @param $operationId
	 * @param Model $Model
	 * @return Operation
	 */
	protected function _getBoundSwaggerAction($operationId, Model $Model) {

		// I could decorate this to a SwaggerOptions class buuut who's got time for that!?
		$SwaggerOperationConfig = $this->Swagger->findOperationConfigById($this->_SwaggerConfig, $operationId);

		$SwaggerOperation = $this->Swagger->getOperation($SwaggerOperationConfig);

		// @todo extract this to a RequestWithPathParams obut with nicer name
		$SwaggerOperation->bindRequest($this->dispatcher->getParams(), $this->request, $Model);

		return $SwaggerOperation;

	}

}
