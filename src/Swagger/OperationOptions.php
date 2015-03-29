<?php

namespace tomi20v\phalswag\Swagger;

/**
 * Class OperationOptions - I am a data container
 *
 * @package tomi20v\phalswag
 */
class OperationOptions {

	public $path;

	public $method;

	public $SwaggerOperationConfig;

	public $SwaggerConfig;

	function __construct($path, $method, \Phalcon\Config $SwaggerOperationConfig, \Phalcon\Config $SwaggerConfig) {
		$this->path = $path;
		$this->method = $method;
		$this->SwaggerOperationConfig = $SwaggerOperationConfig;
		$this->SwaggerConfig = $SwaggerConfig;
	}

}
