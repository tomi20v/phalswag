<?php

namespace tomi20v\phalswag\Model\Swagger;

use Phalcon\Config;
use tomi20v\phalswag\Exception\InvalidKeyException;
use tomi20v\phalswag\Exception\UnimplementedException;

/**
 * Class Operation
 *
 * @package tomi20v\phalswag
 *
 * @property string $type
 * @property-read \tomi20v\phalswag\Model\Swagger\ParameterFactory $ParameterFactory
 */
abstract class SchemaAbstract extends AbstractItem {

	const KEY_PATTERN = '/^(x\-)?[a-z][a-zA-Z0-9]*$/';

	/**
	 * @param string $key
	 * @return null|string
	 * @throws InvalidKeyException
	 * @throws UnimplementedException
	 */
	public function __get($key) {

		$this->_checkKey($key);

		$ret = null;

		if (in_array($key, static::$_fields)) {
			if (isset($this->_data[$key])) {
				$ret = $this->_data[$key];
			}
		}
		elseif (isset(static::$_fields[$key])) {
			$ret = $this->_getMappedObject($key, static::$_fields[$key]);
		}
		return $ret;
	}

}
