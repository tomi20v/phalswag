<?php

namespace tomi20v\phalswag\Model\Swagger;

use Phalcon\Config;
use tomi20v\phalswag\Model\Swagger\SchemaAbstract;

/**
 * Class ParameterFactory
 *
 * @package tomi20v\phalswag
 */
class SchemaFactory {

	static $_classTypePattern = '/^[a-zA-Z\_][a-zA-Z0-9\_\-\\\\]*$/';

	public static function _niceClassNamePart($uglyPart) {
		return ucfirst(strtolower(strtr($uglyPart, '-\\', '__')));
	}

	/**
	 * @param Config $SwaggerSchema
	 * @return SchemaAbstract
	 * @throws \Exception
	 */
	public static function buildSchema(Config $SwaggerSchema) {

		// @todo I shall expand $ref's first...

		$className = static::_getClassName($SwaggerSchema);

		$Schema = new $className($SwaggerSchema);

		return $Schema;

	}

	/**
	 * @param Config $SwaggerSchema
	 * @return string
	 * @throws \Exception
	 */
	private static function _getClassName(Config $SwaggerSchema) {

		$nameSpacePart = get_called_class();
		if ($pos = strrpos($nameSpacePart, '\\')) {
			$nameSpacePart = substr($nameSpacePart, 0, $pos+1) . 'Schema\\';
		}
		else $nameSpacePart = 'Model\\Schema\\';

		$typePart = null;
		if (isset($SwaggerSchema->type) && preg_match(static::$_classTypePattern, $SwaggerSchema->type)) {
			$typePart = static::_niceClassNamePart($SwaggerSchema->type);
		}

		$classNames = [
			$nameSpacePart . 'SchemaObject',
			'SchemaObject',
		];
		if ($typePart) {
			$classNames[] = $nameSpacePart . 'Schema' . $typePart;
			$classNames[] = 'Schema' . $typePart;
		}
		$classNames = array_reverse($classNames);

		foreach ($classNames as $eachClassName) {
			if (class_exists($eachClassName)) {
				return $eachClassName;
			}
		}

		// this really shouldn't happen as I will find at least tomi20v\phalswag\ParameterAbstract class
		throw new \Exception();

	}

}
