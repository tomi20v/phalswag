<?php

namespace tomi20v\phalswag\Swagger;

/**
 * Class Reader
 *
 * @package tomi20v\phalswag
 */
class Reader {

	/**
	 * @var string path to the swagger config folder
	 */
	protected $_configPath;

	/**
	 * @see $this->_configPath
	 */
	public function __construct($configPath) {
		$this->_configPath = $configPath;
	}

	/**
	 * I read a config file and return it
	 * @param string $fname relative to $this->_configPath
	 * @return \Phalcon\Config\Adapter\Json
	 */
	public function read($fname) {
		$Config = new \Phalcon\Config\Adapter\Json(
			$this->_configPath . $fname
		);
		return $Config;
	}

}
