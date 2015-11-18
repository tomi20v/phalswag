<?php

namespace tomi20v\phalswag\Model\Iterable;

trait ByKeyReferenceTrait {

	private $_fieldsNamesToIterate = [];

	protected function _setKeyReference() {
		$fieldNamesToIterate = [];
		foreach (static::$_fields as $eachKey => $eachField) {
			$fieldName = is_numeric($eachKey) ? $eachField : $eachKey;
			if (isset($this->_data->$fieldName)) {
				$fieldNamesToIterate[] = $fieldName;
			}
		}
		$this->_fieldsNamesToIterate = $fieldNamesToIterate;
		reset($this->_fieldsNamesToIterate);
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
