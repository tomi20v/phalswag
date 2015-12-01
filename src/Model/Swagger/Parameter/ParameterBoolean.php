<?php

namespace tomi20v\phalswag\Model\Swagger\Parameter;

use tomi20v\phalswag\Model\Swagger\ParameterAbstract;

/**
 * Class ParameterBoolean
 *
 * @package tomi20v\phalswag
 */
class ParameterBoolean extends ParameterAbstract {

	protected function _buildFilters() {
		parent::_buildFilters();
		$this->_filters[] = function($value) {
			return $value ? true : false;
		};
	}

}
