<?php

namespace tomi20v\phalswag\Swagger;

/**
 * Class Operation
 *
 * @package tomi20v\phalswag
 *
 * @property-read \tomi20v\phalswag\Swagger\ParameterFactory $ParameterFactory
 */
class Operation extends \Phalcon\Di\Injectable implements \Iterator {

	protected $_path;

	protected $_method;

	protected $_SwaggerOperationConfig;

	protected $_SwaggerConfig;

	protected $_SwaggerParameters;

	protected $_data = [];

	protected $_isValidated = false;

	/**
	 * @var \Phalcon\Validation\Message\Group
	 */
	protected $_validationMessages = [];

	public function __construct(OperationOptions $Config) {

		$this->_path = $Config->path;
		$this->_method = $Config->method;
		$this->_SwaggerOperationConfig = $Config->SwaggerOperationConfig;

		$swaggerParameters = $this->_SwaggerOperationConfig->parameters;
		$ParameterFactory = $this->ParameterFactory;
		$parameters = [];

		foreach ($swaggerParameters as $EachSwaggerParameter) {
			$name = $EachSwaggerParameter->name;
			$parameters[$name] = $ParameterFactory->buildParameter(
				$EachSwaggerParameter,
				$Config->SwaggerConfig
			);
		}

		$this->_SwaggerParameters = $parameters;

	}

	/**
	 * I set $Model's properties and my $_data from $params, according to swagger params
	 * @param array[] $params array with possible keys path, get, post, put, request
	 * @param \Phalcon\Mvc\Model $Model shall be Phalcon\Mvc\Model or anything that has public properties or setters with signature setProperty($val)
	 * @param null|string[] $whitelist whitelist of fields to set in $Model
	 * @return $this
	 * @throws Exception
	 */
	public function bindRequest($pathParams, \Phalcon\Http\Request $Request, \Phalcon\Mvc\Model $Model=null, $whitelist=null) {

		$data = [];

		/**
		 * @var \tomi20v\phalswag\Swagger\Parameter\EntityAbstract $EachParameter
		 */
		foreach ($this->_SwaggerParameters as $EachParameter) {

			if ($EachParameter->fetch($pathParams, $Request)) {

				$data[$EachParameter->getName()] = $EachParameter->getValue();

				if (is_null($Model) || ($whitelist && !in_array($EachParameter->name, $whitelist)));
				else {
					$setterMethod = 'set' . ucfirst($EachParameter->getName());
					if (method_exists($Model, $setterMethod)) {
						call_user_func([$Model, $setterMethod], $EachParameter->getValue());
					}
					else {
						$Model->{$name} = $EachParameter->getValue();
					}
				}
			}

		}

		$this->_data = $data;

		$this->_isValidated = false;

		return $this;

	}

	/**
	 * I return if bound data is valid (and validate if necessary)
	 * @return bool
	 */
	public function isValid() {

		if (!$this->_isValidated) {

			$this->validate();

		}

		return empty($this->_validationMessages);

	}

	/**
	 * I validate bound data
	 * @return $this|bool
	 */
	public function validate() {

		$methodName = 'beforeValidator';
		if (method_exists($this, $methodName)) {
			if (!call_user_func([$this, $methodName], $this->_data)) {
				return false;
			}
		}

		$this->_validationMessages = [];

		foreach ($this->_SwaggerParameters as $EachParameter) {
			if (!$EachParameter->isValid()) {
				$validationMessages[$EachParameter->getName()] = $EachParameter->getValidationMessages();
			}
		}

		if (!empty($validationMessages)) {
			$this->_validationMessages = $validationMessages;
		}

		$this->_isValidated = true;

		return $this;

	}

	/**
	 * I return messages from validation
	 * @return array
	 */
	public function getValidationMessages() {
		return $this->_validationMessages;
	}

	/**
	 * I return validation messages for one field
	 * @param string $name
	 * @return array
	 */
	public function getMessagesFor($name) {
		return isset($this->_validationMessages[$name]) ? $this->_validationMessages[$name] : [];
	}

	/**
	 * I tell if a field has messages (is invalid)
	 * @param $name
	 * @return bool
	 */
	public function hasMessagesFor($name) {
		return isset($this->_validationMessages[$name]);
	}

	/**
	 * I return description for field
	 * @param $name
	 * @return null
	 */
	public function getLabel($name) {
		return isset($this->_SwaggerParameters[$name])
			? $this->_SwaggerParameters[$name]->getDescription()
			: null;
	}

	/**
	 * I return value of a field
	 * @param $name
	 * @return null
	 */
	public function getValue($name) {
		return isset($this->_SwaggerParameters[$name])
			? $this->_SwaggerParameters[$name]->getValue()
			: null;
	}

	/**
	 * I tell if parameter exists
	 * @param $name
	 * @return bool
	 */
	public function has($name) {
		return array_key_exists($name, $this->_SwaggerParameters);
	}

	// iterate $this->_SwaggerParameters
	public function current() {
		return current($this->_SwaggerParameters);
	}
	public function next() {
		return next($this->_SwaggerParameters);
	}
	public function key() {
		return key($this->_SwaggerParameters);
	}
	public function valid() {
		return !is_null(key($this->_SwaggerParameters));
	}
	public function rewind() {
		return reset($this->_SwaggerParameters);
	}

}
