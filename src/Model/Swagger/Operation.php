<?php

namespace tomi20v\phalswag\Model\Swagger;

use Phalcon\Config;
use tomi20v\phalswag\Model\AbstractItem;

/**
 * Class Operation
 *
 * @package tomi20v\phalswag
 *
 * @property-read \tomi20v\phalswag\Model\Swagger\ParameterFactory $ParameterFactory
 *
 * @property $tags
 * @property string $summary
 * @property string $description
 * @property string $operationId
 * @property string $consumes
 * @property string $produces
 * @property Parameters parameters
 * @property Responses $responses
 * @property $schemes
 * @property $deprecated
 * @property $security
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

	/**
	 * @param Config $Data
	 * @param string $path
	 * @param string $method
	 */
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

		/** @var \tomi20v\phalswag\Model\Swagger\ParameterAbstract $EachParameter */
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
		$ret = null;
		if (isset($this->_SwaggerParameters[$name]))
		{
			/** @var ParameterAbstract $Parameter */
			$Parameter = $this->_SwaggerParameters[$name];
			$ret = $Parameter->getDescription();
		}
		return $ret;
	}

	/**
	 * I return value of a field
	 * @param $name
	 * @return null
	 */
	public function getValue($name) {
		$ret = null;
		if (isset($this->_SwaggerParameters[$name]))
		{
			/** @var ParameterAbstract $Parameter */
			$Parameter = $this->_SwaggerParameters[$name];
			$ret = $Parameter->getValue();
		}
		return $ret;
	}

	/**
	 * I tell if parameter exists
	 * @param $name
	 * @return bool
	 */
	public function has($name) {
		return array_key_exists($name, $this->_SwaggerParameters);
	}

	/**
	 * iterate $this->_SwaggerParameters
	 * @return mixed
	 */
	public function current() {
		return current($this->_SwaggerParameters);
	}

	/**
	 * iterate $this->_SwaggerParameters
	 * @return mixed
	 */
	public function next() {
		return next($this->_SwaggerParameters);
	}

	/**
	 * iterate $this->_SwaggerParameters
	 * @return mixed
	 */
	public function key() {
		return key($this->_SwaggerParameters);
	}

	/**
	 * iterate $this->_SwaggerParameters
	 * @return bool
	 */
	public function valid() {
		return !is_null(key($this->_SwaggerParameters));
	}

	/**
	 * iterate $this->_SwaggerParameters
	 * @return mixed
	 */
	public function rewind() {
		return reset($this->_SwaggerParameters);
	}

}
