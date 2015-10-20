<?php

namespace tomi20v\phalswag\Swagger;

/**
 * Class Reader
 *
 * @package tomi20v\phalswag
 */
class Reader {

	/**
	 * I read a config file and return it
	 * @param string $fname relative to $this->_configPath
	 * @return \Phalcon\Config\Adapter\Json
	 */
	public function read($fname) {
		$Config = new \Phalcon\Config\Adapter\Json($fname);
		return $Config;
	}

}
