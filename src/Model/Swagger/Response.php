<?php

namespace tomi20v\phalswag\Model\Swagger;

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
