<?php

namespace tomi20v\phalswag\Swagger\Parameter\Entity;

use tomi20v\phalswag\Swagger\Parameter\EntityAbstract;

/**
 * Class EntityBoolean
 *
 * @package tomi20v\phalswag
 */
class EntityBoolean extends EntityAbstract {

	protected function _buildFilters() {
		parent::_buildFilters();
		$this->_filters[] = function($value) {
			return $value ? true : false;
		};
	}

}
