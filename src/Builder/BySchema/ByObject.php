<?php

namespace tomi20v\phalswag\Builder\BySchema;

use tomi20v\phalswag\Builder\BySchemaAbstract;
use tomi20v\phalswag\Service\Builder\BySchemaFactory;
use tomi20v\phalswag\Model\Swagger\SchemaAbstract;

/**
 * Class ByObject
 */
class ByObject extends BySchemaAbstract {

	/**
	 * @param $Value
	 * @param \tomi20v\phalswag\Model\Swagger\SchemaAbstract $Schema
	 * @param BySchemaFactory $BuilderFactory
	 * @return array
	 * @throws \tomi20v\phalswag\Exception\UnimplementedException
	 */
	protected function _buildValue($Value, SchemaAbstract $Schema)
	{

		$ret = [];

		$Properties = $Schema->properties;

		/** @var BySchemaFactory $bySchemaFactory */
		$bySchemaFactory = $this->BySchemaFactory;

		/** @var SchemaAbstract $EachProperty */
		foreach ($Properties as $eachKey => $EachProperty) {

			$Builder = $bySchemaFactory->get($EachProperty->type);
			$ret[$eachKey] = $Builder->buildValue($Value, $eachKey, $EachProperty);

		};

		return $ret;

	}


}
