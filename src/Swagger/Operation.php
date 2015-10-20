<?php

namespace tomi20v\phalswag\Swagger;

use Phalcon\Config;
use tomi20v\phalswag\AbstractItem;

/**
 * Class Operation
 *
 * @package tomi20v\phalswag
 *
 * @property-read \tomi20v\phalswag\Swagger\ParameterFactory $ParameterFactory
 */
class Operation extends AbstractItem implements \Iterator {

	protected $_path;

	protected $_method;

	protected $_data = [];

	protected static $_fields = [
		'tags',
		'summary',
		'description',
		'externalDocs' => 'ExternalDocs',
		'operationId',
		'consumes',
		'produces',
		'parameters' => 'Parameters',
		'responses' => 'Responses',
		'schemes',
		'deprecated',
		'security' => 'SecurityRequirement',
	];


	protected $_SwaggerOperationConfig;

	protected $_SwaggerConfig;

	protected $_SwaggerParameters;

	protected $_isValidated = false;

	/**
	 * @var \Phalcon\Validation\Message\Group
	 */
	protected $_validationMessages = [];

	public function __construct(Config $Data, $path, $method) {

		parent::__construct($Data);

		$this->_path = $path;
		$this->_method = $method;

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
