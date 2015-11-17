<?php

namespace tomi20v\phalswag\Builder\BySchema;

use tomi20v\phalswag\Builder\BySchemaAbstract;
use tomi20v\phalswag\Model\Swagger\SchemaAbstract;
use tomi20v\phalswag\Swagger\Model\Swagger\Schema\SchemaObject;

/**
 * Class ByObject
 */
class ByString extends BySchemaAbstract {

	/**
	 * @param $Value
	 * @param SchemaAbstract $Schema
	 * @return string
	 */
	protected function _buildValue($Value, SchemaAbstract $Schema) {

		return (string) $Value;

	}

}
