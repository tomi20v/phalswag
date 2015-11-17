<?php

namespace tomi20v\phalswag\Model;

use Phalcon\Config;
use tomi20v\phalswag\Model\AbstractItem;
use tomi20v\phalswag\Exception\InvalidKeyException;
use tomi20v\phalswag\Exception\UnimplementedException;

/**
 * Class AbstractCollection
 */
abstract class AbstractCollection extends AbstractItem implements \Iterator {

	const KEY_PATTERN = '/^[a-z0-9][a-zA-Z0-9]*$/';

	protected static $_childClassName = '';

	/**
	 * @param Config $data
	 */
	public function __construct(Config $data) {
		$this->_data = $data;
	}
		/**
	 * @param string $key
	 * @return null
	 * @throws InvalidKeyException
	 * @throws UnimplementedException
	 */
	public function __get($key) {

		$this->_checkKey($key);

		return $this->_getMappedObject($key, static::$_childClassName);

	}

	/**
	 * @return mixed|null
	 */
	public function current() {
		$key = $this->key();
		if ($key !== null) {
			return $this->$key;
		}
		return null;
	}

	/**
	 * @return string|int
	 */
	public function key() {
		return key($this->_data);
	}

	/**
	 * @return mixed|null
	 */
	public function next() {
		next($this->_data);
		return $this->current();
	}

	/**
	 * @return mixed|null
	 */
	public function rewind() {
		reset($this->_data);
		return $this->current();
	}

	/**
	 * @return bool
	 */
	public function valid() {
		return $this->key() !== null;
	}

}
