<?php

namespace tomi20v\phalswag\Model;

use Phalcon\Config;
use tomi20v\phalswag\Exception\InvalidKeyException;
use tomi20v\phalswag\Exception\UnimplementedException;

/**
 * Class AbstractItem - an immutable model representation which can be built from a Config object and/but
 * 		lazy-inflates its elements when getting them
 */
abstract class AbstractItem implements \Iterator {

	const KEY_PATTERN = '/^[a-z][a-zA-Z0-9]*$/';

	const CHILD_CLASS_NAMESPACE = 'tomi20v\phalswag';

	protected static $_fields = [];

	private $_fieldsNamesToIterate = [];

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
		$fieldNamesToIterate = [];
		foreach (static::$_fields as $eachKey => $eachField) {
			$fieldName = is_numeric($eachKey) ? $eachField : $eachKey;
			if (isset($data->$fieldName)) {
				$fieldNamesToIterate[] = $fieldName;
			}
		}
		$this->_fieldsNamesToIterate = $fieldNamesToIterate;
		reset($this->_fieldsNamesToIterate);
	}

	/**
	 * @param string $key
	 * @param string $className
	 * @return null
	 * @throws UnimplementedException
	 */
	protected function _getMappedObject($key, $className) {

		if (is_string($className)) {
			$className = static::CHILD_CLASS_NAMESPACE . '\\' . $className;
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

	public function current()
	{
		if ($this->valid()) {
			$key = current($this->_fieldsNamesToIterate);
			return $this->_getField($key);
		}
		return null;
	}

	public function next()
	{
		$index = next($this->_fieldsNamesToIterate);
		return $index === false ? null : $this->_getField($index);
	}

	public function key()
	{
		return current($this->_fieldsNamesToIterate);
	}

	public function valid()
	{
		return key($this->_fieldsNamesToIterate) !== null;
	}

	public function rewind()
	{
		$key = reset($this->_fieldsNamesToIterate);
		return $this->_getField($key);
	}

	/**
	 * @param $key
	 * @return null|mixed
	 */
	private function _getField($key) {
		return isset($this->$key) ? $this->$key : null;
	}

}
