<?php

namespace tomi20v\phalswag\Model\Swagger\Schema;

use Phalcon\Config;
use tomi20v\phalswag\Model\AbstractCollection;

/**
 * Class SchemaObjectProperties
 */
class SchemaObjectProperties extends AbstractCollection {

	protected static $_childClassName = ['tomi20v\phalswag\Model\Swagger\SchemaFactory', 'buildSchema'];

}
