<?php

namespace tomi20v\phalswag\Mvc;

use Phalcon\Mvc\Model;
use tomi20v\phalswag\Http\Response;
use tomi20v\phalswag\Model\RequestModel;
use tomi20v\phalswag\Builder\Http\ResponseBuilder;
use tomi20v\phalswag\Model\Swagger;

/**
 * Class Controller
 *
 * @package tomi20v\phalswag
 *
 * @property-read \tomi20v\phalswag\Model\Swagger $Swagger
 * @property-read \Phalcon\Dispatcher $dispatcher
 * @property \tomi20v\phalswag\Service\SwaggerService SwaggerService
 * @property-read ResponseBuilder ResponseBuilder
 */
abstract class Controller extends \Phalcon\Mvc\Controller {

	/** @var string can be defined with $_swaggerFname */
	protected static $_swaggerPath;

	/**
	 * @var string define this to automatically load swagger config file
	 */
	protected static $_swaggerFname;

	/**
	 * @var \tomi20v\phalswag\Model\Swagger
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
	 * @param $operationId
	 * @param Model $RequestModel
	 * @param callable $lambda
	 * @param callable $responseBuilder
	 * @return Response
	 */
	protected function _process(
		$operationId,
		callable $lambda,
		Model $RequestModel = null,
		callable $responseBuilder = null
	) {

		$RequestModel = $RequestModel ?: new RequestModel();

		$Operation = $this->SwaggerService->getOperationById($operationId, $this->_Swagger);
		$this->SwaggerService->bindRequest($RequestModel, $Operation, $this->dispatcher->getParams(), $this->request);
		$Result = $this->SwaggerService->validate($RequestModel, $Operation);

		if (!is_null($responseBuilder)) {
			$Response = $responseBuilder(
				$RequestModel,
				$Result
			);
		}
		elseif ($Result->isSuccess()) {
			$Object = $lambda($RequestModel);
			$ResponseSchema = $this->SwaggerService->getResponseSchema(200, $Operation, $this->_Swagger);
			$Result = $this->SwaggerService->buildBySchema($Object, $ResponseSchema);
			$Response = $this->ResponseBuilder->buildSuccess($Result);
		}
		else {
			$Response = $this->ResponseBuilder->buildError(400);
		}

		return $Response;
	}

}
