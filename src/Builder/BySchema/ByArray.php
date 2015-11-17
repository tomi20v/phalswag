<?php

namespace tomi20v\phalswag\Builder\BySchema;

use tomi20v\phalswag\Builder\BySchemaAbstract;
use tomi20v\phalswag\Service\Builder\BySchemaFactory;
use tomi20v\phalswag\Model\Swagger\SchemaAbstract;

/**
 * Class ByObject
 */
class ByArray extends BySchemaAbstract {

	/**
	 * @param $Value
	 * @param SchemaAbstract $Schema
	 * @param BySchemaFactory $BuilderFactory
	 * @return array
	 * @throws \tomi20v\phalswag\Exception\UnimplementedException
	 */
	protected function _buildValue($Value, SchemaAbstract $Schema) {

		$ret = [];

		/** @var SchemaAbstract $ItemSchema */
		$ItemSchema = $Schema->items;

		/** @var \tomi20v\phalswag\Model\Swagger\SchemaAbstract $EachProperty */
		foreach ($Value as $eachKey => $EachValue) {

			/** @var BySchemaFactory $BuilderFactory */
			$BuilderFactory = $this->BySchemaFactory;
			$Builder = $BuilderFactory->get($ItemSchema->type);

			$Items = new \stdClass();
			$Items->items = $EachValue;

			$ret[$eachKey] = $Builder->buildValue(
					$Items,
					'items',
					$ItemSchema
			);

		};

		return $ret;

	}

}
