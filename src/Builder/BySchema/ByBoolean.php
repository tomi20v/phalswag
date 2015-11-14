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
	 * @param $Model
	 * @param $key
	 * @param SchemaAbstract $Schema
	 * @param BySchemaFactory $BuilderFactory
	 * @return null|string
	 * @throws InvalidModelForSchemaException
	 */
	public function buildValue($Model, $key, SchemaAbstract $Schema, BySchemaFactory $BuilderFactory) {

		$ret = null;

		if (isset($Model->$key)) {
			$value = $Model->$key;
			$ret = (bool)$value;
		}
		elseif (isset($Schema->required) && $Schema->required) {
			throw new InvalidModelForSchemaException;
		}

		return $ret;

	}


}
