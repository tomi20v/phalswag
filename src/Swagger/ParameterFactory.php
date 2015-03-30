<?php

namespace tomi20v\phalswag\Swagger;

/**
 * Class ParameterFactory
 *
 * @package tomi20v\phalswag
 */
class ParameterFactory {

	static $_classTypePattern = '/^[a-zA-Z\_][a-zA-Z0-9\_\-\\\\]*$/';

	public static function _niceClassNamePart($uglyPart) {
		return ucfirst(strtolower(strtr($uglyPart, '-\\', '__')));
	}

	/**
	 * I return class to be instantiated
	 * @param \Phalcon\Config $SwaggerParameter
	 * @return string
	 * @throws \Exception
	 */
	protected static function _getParameterClassName(\Phalcon\Config $SwaggerParameter) {

		$nameSpacePart = get_called_class();
		if ($pos = strrpos($nameSpacePart, '\\')) {
			$nameSpacePart = substr($nameSpacePart, 0, $pos+1);
		}
		else $nameSpacePart = '';

		$typePart = null;
		if (isset($SwaggerParameter->type) && preg_match(static::$_classTypePattern, $SwaggerParameter->type)) {
			$typePart = static::_niceClassNamePart($SwaggerParameter->type);
		}

		$classNames = [
			$nameSpacePart . 'Parameter\\Entity\\Entity',
			'Parameter\\Entity\\Entity',
		];
		if ($typePart) {
			$classNames[] = $nameSpacePart . 'Parameter\\Entity\Entity' . $typePart;
			$classNames[] = 'Parameter\\Entity\Entity' . $typePart;
		}
		$classNames = array_reverse($classNames);

		foreach ($classNames as $eachClassName) {
			if (class_exists($eachClassName)) {
				return $eachClassName;
			}
		}

		// this really shouldn't happen as I will find at least tomi20v\phalswag\EntityAbstract class
		throw new \Exception();

	}

	/**
	 * I build the parameter object, and inject fetch strategy
	 * @param \Phalcon\Config $SwaggerParameter
	 * @param \Phalcon\Config $SwaggerConfig - I need it to resolve refs
	 * @return \tomi20v\phalswag\ParameterInstance
	 */
	public static function buildParameter(
		\Phalcon\Config $SwaggerParameter,
		\Phalcon\Config $SwaggerConfig=null
	) {

		// @todo if 'in'='body' then expand schema to parameters?

		// @todo I shall expand $ref's first...

		$parameterClassName = static::_getParameterClassName($SwaggerParameter);

		$Parameter = new $parameterClassName($SwaggerParameter);

		return $Parameter;

	}

}
