<?php

namespace tomi20v\phalswag\Model\Swagger;

use Phalcon\Config;

/**
 * Class ParameterFactory
 *
 * @package tomi20v\phalswag
 */
class ParameterFactory {

	static $_classTypePattern = '/^[a-zA-Z\_][a-zA-Z0-9\_\-\\\\]*$/';

	/**
	 * I build the parameter object, and inject fetch strategy
	 * @param Config $SwaggerParameter
	 * @return ParameterAbstract
	 */
	public function buildParameter(
		Config $SwaggerParameter
	) {

		// @todo if 'in'='body' then expand schema to parameters?

		// @todo I shall expand $ref's first...

		$parameterClassName = $this->_getParameterClassName($SwaggerParameter);

		$Parameter = new $parameterClassName($SwaggerParameter);

		return $Parameter;

	}

	/**
	 * I return class to be instantiated
	 * @param Config $SwaggerParameter
	 * @return string
	 * @throws \Exception
	 */
	private function _getParameterClassName(Config $SwaggerParameter) {

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
			$nameSpacePart . 'Parameter',
			'Parameter',
		];
		if ($typePart) {
			$classNames[] = $nameSpacePart . 'Parameter\\Parameter' . $typePart;
			$classNames[] = 'Parameter\\Parameter' . $typePart;
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

	/**
	 * @param string $uglyPart
	 * @return string
	 */
	private function _niceClassNamePart($uglyPart) {
		return ucfirst(strtolower(strtr($uglyPart, '-\\', '__')));
	}

}
