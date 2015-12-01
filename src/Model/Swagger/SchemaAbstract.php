<?php

namespace tomi20v\phalswag\Model\Swagger;

use Phalcon\Config;

/**
 * Class Operation
 *
 * @package tomi20v\phalswag
 *
 * @property string $type
 * @property-read \tomi20v\phalswag\Model\Swagger\ParameterFactory $ParameterFactory
 */
abstract class SchemaAbstract extends AbstractItem {

	const KEY_PATTERN = '/^(x\-)?[a-z][a-zA-Z0-9]*$/';

}
