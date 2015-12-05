<?php

namespace tomi20v\phalswag\Model\Swagger;

use Phalcon\Config;

/**
 * Class Parameters
 *
 * @property ParameterFactory $ParameterFactory
 */
class Parameters extends AbstractCollection {

	protected static $_childClassName = '';

	/**
	 * @param $name
	 * @param $location
	 * @return null|ParameterAbstract
	 */
	public function get($name, $location) {

		$ret = null;

		foreach ($this->_data as $eachKey=>$data) {
			if ($data instanceof Config) {
				if (isset($data['name']) && isset($data['location']) &&
					$data['name'] == $name && $data['location'] == $location) {
					$data = $this->_buildParameters($data);
					$this->_data[$eachKey] = $data;
					$ret = $data;
					break;
				}
			}
			else {
				/** @var ParameterAbstract $data */
				if ($data->name == $name && $data->location == $location) {
					$ret = $data;
					break;
				}
			}
		}

		return $ret;

	}

	/**
	 * @return ParameterAbstract|null
	 */
	public function current() {
		$key = $this->key();
		if ($key !== null) {
			$data = $this->_data->$key;
			if ($data instanceof Config) {
				$data = $this->_buildParameters($data);
				$this->_data->$key = $data;
			}
			return $data;
		}
		return null;
	}

	/**
	 * @param Config $data
	 * @return ParameterAbstract
	 */
	protected function _buildParameters($data) {
		$data = $this->_getParameterFactory()->buildParameter($data);
		return $data;
	}

	/**
	 * @return ParameterFactory
	 */
	protected function _getParameterFactory() {
		static $ParameterFactory;
		if (!$ParameterFactory) {
			$ParameterFactory = new ParameterFactory();
		}
		return $ParameterFactory;
	}

}
