<?php

namespace tomi20v\phalswag\Model\Swagger\Schema;

use Phalcon\Config;
use tomi20v\phalswag\Model\Swagger\SchemaAbstract;

/**
 * Class SchemaObject
 *
 * @property string $type
 * @property SchemaObjectProperties $properties
 */
class SchemaObject extends SchemaAbstract {

	protected static $_fields = [
		'type',
		'properties' => 'Schema\SchemaObjectProperties',
	];

}
