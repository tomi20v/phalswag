<?php

namespace tomi20v\phalswag\Builder;

use Phalcon\Di\Injectable;
use tomi20v\phalswag\Exception\InvalidModelForSchemaException;
use tomi20v\phalswag\Service\Builder\BySchemaFactory;
use tomi20v\phalswag\Model\Swagger\SchemaAbstract;

/**
 * Class BySchemaAbstract
 */
abstract class BySchemaAbstract extends Injectable implements BySchemaInterface {

	/**
	 * @param $Model
	 * @param $key
	 * @param SchemaAbstract $Schema
	 * @param BySchemaFactory $BuilderFactory
	 * @return null
	 * @throws InvalidModelForSchemaException
	 */
	public function buildValue($Model, $key, SchemaAbstract $Schema, BySchemaFactory $BuilderFactory)
	{

		$ret = null;

		if (isset($Model->$key)) {

			$Value = $Model->$key;

			$ret = $this->_buildValue($Value, $Schema, $BuilderFactory);

		}
		elseif (isset($Schema->required) && $Schema->required) {
			throw new InvalidModelForSchemaException;
		}

		return $ret;

	}

	/**
	 * @param $Value
	 * @param SchemaAbstract $Schema
	 * @param BySchemaFactory $BuilderFactory
	 * @return mixed
	 */
	abstract protected function _buildValue($Value, SchemaAbstract $Schema, BySchemaFactory $BuilderFactory);

}
