<?php

namespace tomi20v\phalswag\Model\Swagger;

use Phalcon\Config;
use Phalcon\Di\Injectable;
use Phalcon\Http\Request;
use Phalcon\Validation\Validator;

/**
 * Class ParameterAbstract
 *
 * @property-read \Phalcon\Filter $Filter
 * @property-read \tomi20v\phalswag\Validation\ValidatorFactory $ValidatorFactory
 * @property-read \tomi20v\phalswag\ValidationStub $ValidationStub
 */
abstract class ParameterAbstract extends Injectable {

	protected $_value = null;

	/**
	 * @var \Phalcon\Config
	 */
	protected $_data;

	/**
	 * @var bool I'll be true after a value has been fetched actually
	 */
	protected $_hasFetched = false;

	protected $_filters = [];

	protected $_validators = [];

	protected $_Validation;

	protected $_validationMessages = [];

	/**
	 * @param Config $Config
	 * @throws \Exception
	 */
	public function __construct(Config $Config) {
		$this->_data = $Config;
		$this->_buildFilters();
		$this->_buildValidators();
		if (isset($this->_data->default)) {
			$this->setValue($this->_data->default);
		}
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->_data->name;
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->_value;
	}

	/**
	 * I run $value through all filters then set it in $this->_value
	 * @param mixed $value
	 * @return $this
	 * @throws \Exception
	 */
	public function setValue($value) {

		$filteredValue = $value;

		foreach ($this->_filters as $eachFilter) {
			if (is_string($eachFilter)) {
				$filteredValue = $this->Filter->sanitize($filteredValue, $eachFilter);
			}
			elseif (is_callable($eachFilter)) {
				$filteredValue = call_user_func($eachFilter, $filteredValue);
			}
			else {
				throw new \Exception('invalid filter type');
			}
		}

		$this->_value = $filteredValue;

		return $this;

	}

	/**
	 * I fetch a value from pathparams and request
	 * @todo wrap pathparams and request in a composite
	 * @param $pathParams
	 * @param Request $Request
	 * @return bool
	 * @throws \Exception
	 */
	public function fetch($pathParams, Request $Request) {

		$this->value = null;
		$this->_hasFetched = false;
		$filters = [];

		$name = $this->_data->name;

		switch ($this->_data->in) {
		case 'path':
			if (array_key_exists($name, $pathParams)) {
				$this->_hasFetched = true;
				$this->setValue($pathParams[$name]);
			}
			break;
		case 'query':
			$this->_hasFetched = $Request->hasQuery($name);
			$this->setValue($Request->getQuery($name, $filters, null));
			break;
		case 'header':
			// this syntax won't work... yet???
			//$value = $Request->getHeader($name, $filters, null);
			$nameWithHttpPrefix = strtoupper(str_replace('-', '_', $name));
			if ($Request->hasServer($name) || $Request->hasServer($nameWithHttpPrefix)) {
				$this->setValue($Request->getHeader($name));
				$this->_hasFetched = true;
			}
			break;
		case 'formData':
			switch (strtolower($Request->getMethod())) {
			case 'post':
				if ($Request->hasPost($name)) {
					$this->_hasFetched = true;
					$this->setValue($Request->getPost($name, $filters, null));
				}
				break;
			case 'put':
				if ($Request->hasPut($name)) {
					$this->_hasFetched = true;
					$this->setValue($Request->getPut($name, $filters, null));
				}
				break;
				// parameters on delete request not supported yet
			case 'delete':
			default:
				throw new \Exception('TBI: ' . $Request->getMethod());
			}
			break;
		case 'body':
			throw new \Exception('TBI');
			break;
		default:
			throw new \Exception('invalid or not implemented "in" value: ' . $this->_data->in);
		}

		return $this->_hasFetched;

	}

	/**
	 * @return bool
	 */
	public function isValid() {
		$this->_validationMessages = [];
		/** @var Validator $EachValidator */
		foreach ($this->_validators as $eachField=>$EachValidator) {
			if (!$EachValidator->validate($this->ValidationStub->setValue($this->_value), $eachField)) {
				$this->_validationMessages = array_merge($this->_validationMessages, $this->ValidationStub->getMessages());
			}
		}
		return empty($this->_validationMessages);
	}

	/**
	 * @return array
	 */
	public function getValidationMessages() {
		return $this->_validationMessages;
	}

	/**
	 * I build filters into $this->_filters note items shall be a callable or a string for Phalcon/custom sanitizing
	 * I could be lazy-run...
	 */
	protected function _buildFilters() {
		$this->_filters = [];
	}

	/**
	 * I cover:
	 * Config->required
	 * Config->enum
	 */
	protected function _buildValidators() {
		$this->_validators = [];
		// @see https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#user-content-parameterRequired
		if (isset($this->_data->required) && $this->_data->required) {
			$this->_validators['required'] = $this->SwaggerService->buildValidator('PresenceOf');
		}
		// @see https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#user-content-parameterEnum
		// @see http://json-schema.org/latest/json-schema-validation.html#anchor76
		if (isset($this->_data->enum)) {
			$enum = $this->_data->enum;
			$this->_validators['enum'] = $this->SwaggerService->buildValidator(
				'InclusionIn',
				[
					'message' => 'expected values to be in ' . implode(',', (array)$enum),
					'domain' => (array) $enum,
				]
			);
		}


	}

}
