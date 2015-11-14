<?php

namespace tomi20v\phalswag\Model\Swagger;

use tomi20v\phalswag\Model\AbstractItem;

/**
 * Class Response
 */
class Response extends AbstractItem {

	protected static $_fields = [
		'description',
		'schema' => ['tomi20v\phalswag\Model\Swagger\SchemaFactory', 'buildSchema'],
		'headers' => 'Headers',
		'examples' => 'Examples',
	];

}
