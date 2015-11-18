<?php

namespace tomi20v\phalswag\Model\Swagger;

use Phalcon\Config;

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
