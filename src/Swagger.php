<?php

namespace tomi20v\phalswag;

/**
 * Class Swagger - maybe I should be renamed to Helper...
 *
 * @package tomi20v\phalswag
 */
class Swagger {

	/**
	 * @var string path to the swagger config folder
	 */
	protected $_configPath;

	/**
	 * @param $Config
	 * @param $what
	 * @param $RootConfig
	 */
	public function resolve($Config, $what, $RootConfig) {
		// todo here I shall extend structures which contain $ref reference!?
	}

	/**
	 * @param $Config
	 * @param $operationId
	 * @return array|null
	 */
	public function findOperationConfigById($Config, $operationId) {
		foreach ($Config->paths as $eachPath=>$EachPathData) {
			foreach ($EachPathData as $eachMethod=>$EachMethodData) {
				if (isset($EachMethodData['operationId']) && ($EachMethodData->operationId == $operationId)) {
					return new SwaggerOperationOptions($eachPath, $eachMethod, $EachMethodData, $Config);
				}
			}
		}
		return null;
	}

	/**
	 * @param $Config
	 * @param $what
	 * @param $RootConfig
	 */
	public function getOperation($SwaggerOptions) {
		return new \tomi20v\phalswag\swagger\Operation($SwaggerOptions);
	}

	/**
	 * I return Reader instance
	 * @return Reader
	 */
	public function getReader() {
		return new \tomi20v\phalswag\swagger\Reader($this->_configPath);
	}

	/**
	 * @param string $configPath
	 * @see $this->_configPath
	 */
	public function __construct($configPath) {
		$this->_configPath = $configPath;
	}

}
