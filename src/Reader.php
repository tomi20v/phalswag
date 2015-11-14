<?php

namespace tomi20v\phalswag;
use Phalcon\Config\Adapter\Json;

/**
 * Class Reader
 *
 * @package tomi20v\phalswag
 */
class Reader {

	/**
	 * I read a config file and return it
	 * @param string $fname relative to $this->_configPath
	 * @return Json
	 */
	public function read($fname) {
		$Config = new Json($fname);
		return $Config;
	}

}
