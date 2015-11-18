<?php

namespace tomi20v\phalswag\Builder;

use Phalcon\Config;
use tomi20v\phalswag\Exception\UnimplementedException;

/**
 * Class MappedObjectTrait
 */
trait MappedObjectTrait {

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

}
