<?php

namespace tomi20v\phalswag\Model\Swagger\Parameter;

use tomi20v\phalswag\Model\Swagger\ParameterAbstract;

/**
 * Class ParameterInteger
 *
 * @package tomi20v\phalswag
 */
class ParameterInteger extends ParameterNumber {

	protected function _buildFilters() {
		ParameterAbstract::_buildFilters();
		$this->_filters[] = 'realInt';
	}

}
