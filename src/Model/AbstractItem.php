<?php

namespace tomi20v\phalswag\Model;

use Phalcon\Config;
use tomi20v\phalswag\Exception\InvalidKeyException;
use tomi20v\phalswag\Exception\UnimplementedException;

/**
 * Class AbstractItem - an immutable model representation which can be built from a Config object and/but
 * 		lazy-inflates its elements when getting them
 */
abstract class AbstractItem {

	const KEY_PATTERN = '/^[a-z][a-zA-Z0-9]*$/';

	const CHILD_CLASS_NAMESPACE = 'tomi20v\\phalswag\\Model\\Swagger\\';

	protected static $_fields = [];

	protected $_data = [];

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

	/**
	 * @param string $key
	 * @return bool
	 * @throws InvalidKeyException
	 */
	public function __isset($key) {

		$this->_checkKey($key);

		return isset($this->_data[$key]);

	}

	/**
	 * @param Config $data
	 */
	public function __construct(Config $data) {
		$this->_data = $data;
	}

	/**
	 * @param string $key
	 * @param string $className
	 * @return null
	 * @throws UnimplementedException
	 */
	protected function _getMappedObject($key, $className) {

		if (is_string($className)) {
			$className = static::CHILD_CLASS_NAMESPACE . $className;
		}

		$data = null;

		if (isset($this->_data[$key])) {

			$data = $this->_data[$key];

			if (is_callable($className)) {

				if ($data instanceof Config) {
					$data = call_user_func($className, $data);
					$this->_data[$key] = $data;
				}

			}
			else {

				if (!class_exists($className)) {
					throw new UnimplementedException($className);
				}

				if (!$data instanceof $className) {
					$data = new $className($data);
					$this->_data[$key] = $data;
				}

			}

		}

		return $data;

	}

	/**
	 * @param string $key
	 * @throws InvalidKeyException
	 */
	protected function _checkKey($key)
	{
		if (!preg_match(static::KEY_PATTERN, $key))
		{
			throw new InvalidKeyException($key);
		}
	}

}
