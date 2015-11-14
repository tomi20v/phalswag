<?php

namespace tomi20v\phalswag\Model\Swagger\Parameter;

use tomi20v\phalswag\Model\Swagger\ParameterAbstract;

/**
 * Class EntityInteger
 *
 * @package tomi20v\phalswag
 */
class EntityInteger extends ParameterNumber {

	protected function _buildFilters() {
		ParameterAbstract::_buildFilters();
		$this->_filters[] = 'int';
	}

}
