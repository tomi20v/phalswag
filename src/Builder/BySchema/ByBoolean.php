<?php

namespace tomi20v\phalswag\Builder\BySchema;

use tomi20v\phalswag\Builder\BySchemaAbstract;
use tomi20v\phalswag\Exception\InvalidModelForSchemaException;
use tomi20v\phalswag\Service\Builder\BySchemaFactory;
use tomi20v\phalswag\Model\Swagger\SchemaAbstract;
use tomi20v\phalswag\Swagger\Model\Swagger\Schema\SchemaObject;

/**
 * Class ByObject
 */
class ByBoolean extends BySchemaAbstract {

	/**
	 * @param $Value
	 * @param SchemaAbstract $Schema
	 * @return bool|null
	 */
	protected function _buildValue($Value, SchemaAbstract $Schema) {

		return (bool) $Value;

	}


}
