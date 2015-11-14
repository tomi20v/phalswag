<?php

namespace tomi20v\phalswag\Builder;

use tomi20v\phalswag\Service\Builder\BySchemaFactory;
use tomi20v\phalswag\Model\Swagger\SchemaAbstract;

/**
 * Interface BySchemaInterface - these objects effectively just filter a model's data as defined in $Schema
 * @package tomi20v\phalswag\Builder
 */
interface BySchemaInterface {

	/**
	 * @param $Model
	 * @param SchemaAbstract $Schema
	 * @param BySchemaFactory $BuilderFactory
	 * @return array|null
	 */
	public function buildValue($Model, $key, SchemaAbstract $Schema, BySchemaFactory $BuilderFactory);

}
