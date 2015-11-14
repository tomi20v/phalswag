<?php

namespace tomi20v\phalswag\Model\Swagger;

use Phalcon\Config;
use tomi20v\phalswag\Model\AbstractCollection;

/**
 * Class Parameters
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
					$data = ParameterFactory::buildParameter($data);
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
	 * @return mixed|null
	 */
	public function current() {
		$key = $this->key();
		if ($key !== null) {
			$data = $this->_data->$key;
			if ($data instanceof Config) {
				$data = ParameterFactory::buildParameter($data);
				$this->_data->$key = $data;
			}
			return $data;
		}
		return null;
	}

}
