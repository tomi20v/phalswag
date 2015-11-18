<?php

namespace tomi20v\phalswag\Service;

use Phalcon\Http\Request;

use Phalcon\Mvc\Model;
use Phalcon\Validation\ValidatorInterface;
use tomi20v\phalswag\Exception\InvalidModelForSchemaException;
use tomi20v\phalswag\Exception\UnimplementedException;
use tomi20v\phalswag\Model\Swagger\ParameterAbstract;
use tomi20v\phalswag\Service\Builder\BySchemaFactory;
use tomi20v\phalswag\Model\Swagger\Swagger;
use tomi20v\phalswag\Model\Swagger\Operation;
use tomi20v\phalswag\Reader;
use tomi20v\phalswag\Model\Swagger\SchemaAbstract;
use tomi20v\phalswag\Validation\ValidatorFactory;
use tomi20v\phalswag\Validator;

/**
 * Class SwaggerService
 */
class SwaggerService {

	/** @var Reader */
	private $_Reader;

	/**
	 * @var ValidatorFactory
	 */
	private $_ValidatorFactory;
	/**
	 * @var BySchemaFactory
	 */
	private $_BuilderFactory;

	/**
	 * @param Reader $Reader
	 * @param ValidatorFactory $validatorFactory
	 * @param BySchemaFactory $BuilderFactory
	 */
	public function __construct(
		Reader $Reader,
		ValidatorFactory $validatorFactory,
		BySchemaFactory $BuilderFactory
	) {
		$this->_Reader = $Reader;
		$this->_ValidatorFactory = $validatorFactory;
		$this->_BuilderFactory = $BuilderFactory;
	}

	/**
	 * @param mixed $source
	 * @return Swagger
	 */
	public function getSwagger($source) {
		$data = $this->_Reader->read($source);
		return new Swagger($data);
	}

	/**
	 * @param string $operationId
	 * @param Swagger $Swagger
	 * @return Operation
	 */
	public function getOperationById($operationId, Swagger $Swagger) {
		return $Swagger->getOperationById($operationId);
	}

	/**
	 * @param Model $Model
	 * @param Operation $Operation
	 * @param array $pathParams
	 * @param Request $Request
	 * @param array|null $whiteList
	 * @return $this
	 * @throws \Exception
	 */
	public function bindRequest(
		Model $Model,
		Operation $Operation,
		array $pathParams,
		Request $Request,
		array $whiteList = null
	) {

		$data = [];

		$parameters = $Operation->parameters;

		if (!empty($parameters)) {

			/** @var ParameterAbstract $EachParameter */
			foreach ($parameters as $EachParameter) {

				if ($EachParameter->fetch($pathParams, $Request)) {

					$name = $EachParameter->getName();

					$data[$name] = $EachParameter->getValue();

					if (is_null($Model) || ($whiteList && !in_array($EachParameter->name, $whiteList)));
					else {
						$setterMethod = 'set' . ucfirst($name);
						if (method_exists($Model, $setterMethod)) {
							call_user_func([$Model, $setterMethod], $EachParameter->getValue());
						}
						else {
							$Model->$name = $EachParameter->getValue();
						}
					}
				}

			}

		}

	}

	/**
	 * @param Model $Model
	 * @param Operation $operation
	 * @return \tomi20v\phalswag\Validation\Result
	 */
	public function validate(Model $Model, Operation $operation) {

		$Validator = new Validator();

		return $Validator->validate($Model, $operation);

	}

	/**
	 * @return ValidatorInterface
	 */
	public function buildValidator() {
		return call_user_func_array([$this->_ValidatorFactory, 'buildValidator'], func_get_args());
	}

	/**
	 * @param string $responseCode
	 * @param Operation $Operation
	 * @param Swagger $Swagger
	 * @return SchemaAbstract|null
	 */
	public function getResponseSchema($responseCode, Operation $Operation, Swagger $Swagger) {

		$Responses = $Operation->responses;
		$Schema = null;

		foreach ($Responses as $eachKey => $EachResponse) {
			if ($eachKey == $responseCode) {
				if (isset($EachResponse->schema)) {
					$Schema = $EachResponse->schema;
				};
				break;
			}
		}

		return $Schema;

	}

	/**
	 * @param $Model
	 * @param SchemaAbstract $Schema
	 * @return null
	 * @throws InvalidModelForSchemaException
	 * @throws UnimplementedException
	 */
	public function buildBySchema($Model, SchemaAbstract $Schema) {

		$ResultSchema = $Schema->properties->result;

		$Builder = $this->_BuilderFactory->get($ResultSchema->type);

		$WrappedModel = new \stdClass();
		$WrappedModel->result = $Model;

		$result = $Builder->buildValue($WrappedModel, 'result', $ResultSchema, $this->_BuilderFactory);

		return $result;

	}

}
