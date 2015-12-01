<?php

namespace tomi20v\phalswag\Model;

use Phalcon\Config;
use tomi20v\phalswag\Builder\MappedObjectTrait;
use tomi20v\phalswag\Exception\InvalidKeyException;
use tomi20v\phalswag\Exception\UnimplementedException;
use tomi20v\phalswag\Model\Iterable\ByKeyReferenceTrait;

/**
 * Class AbstractItem - an immutable model representation which can be built from a Config object and/but
 * 		lazy-inflates its elements when getting them
 */
abstract class AbstractItem implements \Iterator {

	use ByKeyReferenceTrait;

	use MappedObjectTrait;

	const KEY_PATTERN = '/^[a-z][a-zA-Z0-9]*$/';

	const CHILD_CLASS_NAMESPACE = 'tomi20v\phalswag';

	protected static $_fields = [];
	/** @var Config */
	protected $_data;

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
		$this->_setIterableProperties();
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
