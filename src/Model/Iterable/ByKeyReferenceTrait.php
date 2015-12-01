<?php

namespace tomi20v\phalswag\Model\Iterable;

/**
 * Class ByKeyReferenceTrait - implements an iterator over a preset key set
 */
trait ByKeyReferenceTrait {

	private $_iterableKeys = [];

	/**
	 * will iterate keys in $this->_data which are defined in static::$_fields
	 */
	protected function _setIterableProperties() {
		$propertiesToIterate = [];
		foreach (static::$_fields as $eachKey => $eachProperty) {
			$fieldName = is_numeric($eachKey) ? $eachProperty : $eachKey;
			if (isset($this->_data->$fieldName)) {
				$propertiesToIterate[] = $fieldName;
			}
		}
		$this->_iterableKeys = $propertiesToIterate;
		reset($this->_iterableKeys);
	}

	/**
	 * @return mixed|null
	 */
	public function current()
	{
		if ($this->valid()) {
			$key = current($this->_iterableKeys);
			return $this->_getField($key);
		}
		return null;
	}

	/**
	 * @return mixed|null
	 */
	public function next()
	{
		$index = next($this->_iterableKeys);
		return $index === false ? null : $this->_getField($index);
	}

	/**
	 * @return mixed
	 */
	public function key()
	{
		return current($this->_iterableKeys);
	}

	/**
	 * @return bool
	 */
	public function valid()
	{
		return key($this->_iterableKeys) !== null;
	}

	/**
	 * @return mixed|null
	 */
	public function rewind()
	{
		$key = reset($this->_iterableKeys);
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
