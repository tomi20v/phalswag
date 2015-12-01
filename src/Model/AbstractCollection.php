<?php

namespace tomi20v\phalswag\Model;

use Phalcon\Config;
use tomi20v\phalswag\Builder\MappedObjectTrait;
use tomi20v\phalswag\Exception\InvalidKeyException;
use tomi20v\phalswag\Exception\UnimplementedException;
use tomi20v\phalswag\Model\Iterable\ByPropertyTrait;

/**
 * Class AbstractCollection
 */
abstract class AbstractCollection implements \Iterator {

	use MappedObjectTrait;

	use ByPropertyTrait;

	const KEY_PATTERN = '/^[a-z0-9][a-zA-Z0-9]*$/';

	protected static $_childClassName = '';

	protected $_iterableProperty = '_data';

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
