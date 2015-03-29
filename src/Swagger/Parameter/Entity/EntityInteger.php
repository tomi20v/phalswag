<?php

namespace tomi20v\phalswag\Swagger\Parameter\Entity;

use tomi20v\phalswag\Swagger\Parameter\EntityAbstract;

/**
 * Class EntityInteger
 *
 * @package tomi20v\phalswag
 */
class EntityInteger extends EntityNumber {

	protected function _buildFilters() {
		EntityAbstract::_buildFilters();
		$this->_filters[] = 'int';
	}

}
