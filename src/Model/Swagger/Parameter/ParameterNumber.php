<?php

namespace tomi20v\phalswag\Model\Swagger\Parameter;

use tomi20v\phalswag\Model\Swagger\ParameterAbstract;

/**
 * Class ParameterNumber
 *
 * @package tomi20v\phalswag
 */
class ParameterNumber extends ParameterAbstract {

	/**
	 * I cover:
	 * ParameterConfig->maximum
	 * ParameterConfig->minimum
	 * ParameterConfig->multipleOf
	 */
	protected function _buildValidators() {
		parent::_buildValidators();
		// @see https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#user-content-parameterMaximum
		// @see http://json-schema.org/latest/json-schema-validation.html#anchor17
		if (isset($this->_SwaggerConfig->maximum)) {
			$maximum = $this->_SwaggerConfig->maximum;
			$exclusiveMaximum = isset($this->_SwaggerConfig->exclusiveMaximum) && $this->_SwaggerConfig->exclusiveMaximum;
			$this->_validators['maximum'] = $this->ValidatorFactory->buildValidator(
				'ValidationValidatorCallback',
				[
					'message' => 'expected to be' . ($exclusiveMaximum ? ' exclusively' : '') . 'less than ' . $maximum,
					'callback' => function($value) use ($maximum, $exclusiveMaximum) {
						return $exclusiveMaximum
							? ($value < $maximum)
							: ($value <= $maximum);
						}
			]);
		}
		// @see https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#user-content-parameterMinimum
		// @see http://json-schema.org/latest/json-schema-validation.html#anchor21
		if (isset($this->_SwaggerConfig->minimum)) {
			$minimum = $this->_SwaggerConfig->minimum;
			$exclusiveMinimum = isset($this->_SwaggerConfig->exclusiveMinimum) && $this->_SwaggerConfig->exclusiveMinimum;
			$this->_validators['minimum'] = $this->ValidatorFactory->buildValidator(
				'ValidationValidatorCallback',
				[
					'message' => 'expected to be' . ($exclusiveMaximum ? ' exclusively' : '') . 'more than ' . $maximum ,
					'callback' => function($value) use ($minimum, $exclusiveMinimum) {
						return $exclusiveMinimum
							? $value > $minimum
							: $value >= $minimum;
					}
			]);
		}
		// @see https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#user-content-parameterMultipleOf
		// @see http://json-schema.org/latest/json-schema-validation.html#anchor14
		if (isset($this->_SwaggerConfig->multipleOf)) {
			$multiplerOf = $this->_SwaggerConfig->multipleOf;
			$this->_validators['multipleOf'] = $this->ValidatorFactory->buildValidator(
				'ValidationValidatorCallback',
				[
					'message' => 'expected to be multiple of ' . $multiplerOf,
					'callback' => function($value) use ($multiplerOf) {
							return $value % $multiplerOf == 0;
						}
				]);
		}
	}

	protected function _buildFilters() {
		parent::_buildFilters();
		$this->_filters[] = 'float';
	}

}
