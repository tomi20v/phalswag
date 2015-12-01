<?php

namespace tomi20v\phalswag\Model\Iterable;

/**
 * Class ByPropertyTrait - simply iterate a property of current object
 * requires $this->_iterableProperty to be defined
 */
trait ByPropertyTrait {

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
		$iterableProperty = $this->_iterableProperty;
		return key($this->{$iterableProperty});
	}

	/**
	 * @return mixed|null
	 */
	public function next() {
		$iterableProperty = $this->_iterableProperty;
		next($this->{$iterableProperty});
		return $this->current();
	}

	/**
	 * @return mixed|null
	 */
	public function rewind() {
		$iterableProperty = $this->_iterableProperty;
		reset($this->{$iterableProperty});
		return $this->current();
	}

	/**
	 * @return bool
	 */
	public function valid() {
		return $this->key() !== null;
	}

}
