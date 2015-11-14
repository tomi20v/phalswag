<?php

namespace tomi20v\phalswag\Model\Swagger\Parameter;

use tomi20v\phalswag\Model\Swagger\ParameterAbstract;

/**
 * Class ParameterString
 *
 * @package tomi20v\phalswag
 */
class ParameterString extends ParameterAbstract {

	/**
	 * I cover:
	 * Config->format
	 * Config->patter
	 * Config->minlength
	 * Config->maxlength
	 * @throws \Exception
	 */
	protected function _buildValidators() {
		parent::_buildValidators();
		// @see https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#user-content-parameterFormat
		if (isset($this->_SwaggerConfig->format)) {
			switch ($this->_SwaggerConfig->format) {
			case 'date':
				$this->_validators['date'] = $this->ValidatorFactory->buildValidator(
					'Regex',
					[
						'message' => 'expected date eg YYYY-MM-DD',
						'pattern' => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',
					]);
				break;
			case 'date-time':
				$this->_validators['date-time'] = $this->ValidatorFactory->buildValidator(
					'Regex',
					[
						'message' => 'expected date eg YYYY-MM-DD',
						'pattern' => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}$/',
				]);
				break;
			case 'password':
				break;
			default:
				throw new \Exception('unknown format');
			}
		}
		// @see https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#user-content-parameterPattern
		// @see http://json-schema.org/latest/json-schema-validation.html#anchor33
		if (isset($this->_SwaggerConfig->pattern)) {
			$pattern = $this->_SwaggerConfig->pattern;
			$this->_validators['pattern'] = $this->ValidatorFactory->buildValidator(
				'Regex',
				[
					'message' => 'expected format: ' . $pattern,
					'pattern' => $pattern,
			]);
		}
		// @see https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#user-content-parameterMinLength
		// @see http://json-schema.org/latest/json-schema-validation.html#anchor29
		if (isset($this->_SwaggerConfig->minLength)) {
			$minLength = $this->_SwaggerConfig->minLength;
			$this->_validators['minLength'] = $this->ValidatorFactory->buildValidator(
				'StringLength',
				[
					'min' => $minLength,
					'messageMinumum' => 'expected not to be shorter than ' . $minLength,
			]);
		}
		// @see https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#user-content-parameterMaxLength
		// @see http://json-schema.org/latest/json-schema-validation.html#anchor26
		if (isset($this->_SwaggerConfig->maxLength)) {
			$maxLength = $this->_SwaggerConfig->maxLength;
			$this->_validators['maxLength'] = $this->ValidatorFactory->buildValidator(
				'StringLength',
				[
					'max' => $maxLength,
					'messageMaxumum' => 'expected not to be longar than ' . $maxLength,
			]);
		}
	}

	protected function _buildFilters() {
		parent::_buildFilters();
		$this->_filters[] = 'string';
	}

}
