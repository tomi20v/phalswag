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
		if (isset($this->_data->maximum)) {
			$maximum = $this->_data->maximum;
			$exclusiveMaximum = isset($this->_data->exclusiveMaximum) && $this->_data->exclusiveMaximum;
			$this->_validators['maximum'] = $this->ValidatorFactory->buildValidator(
				'Callback',
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
		if (isset($this->_data->minimum)) {
			$minimum = $this->_data->minimum;
			$exclusiveMinimum = isset($this->_data->exclusiveMinimum) && $this->_data->exclusiveMinimum;
			$this->_validators['minimum'] = $this->ValidatorFactory->buildValidator(
				'Callback',
				[
					'message' => 'expected to be' . ($exclusiveMinimum ? ' exclusively' : '') . 'more than ' . $minimum ,
					'callback' => function($value) use ($minimum, $exclusiveMinimum) {
						return $exclusiveMinimum
							? $value > $minimum
							: $value >= $minimum;
					}
			]);
		}
		// @see https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#user-content-parameterMultipleOf
		// @see http://json-schema.org/latest/json-schema-validation.html#anchor14
		if (isset($this->_data->multipleOf)) {
			$multiplerOf = $this->_data->multipleOf;
			$this->_validators['multipleOf'] = $this->ValidatorFactory->buildValidator(
				'Callback',
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
		$this->_filters[] = 'realFloat';
	}

}
