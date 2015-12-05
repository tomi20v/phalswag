<?php

namespace tomi20v\phalswag\Model\Swagger\Parameter;

use Phalcon\Config;
use tomi20v\phalswag\Exception\SwaggerDefinitionException;
use tomi20v\phalswag\Model\Swagger\ParameterAbstract;
use tomi20v\phalswag\Model\Swagger\ParameterFactory;
use tomi20v\phalswag\Model\Swagger\Swagger;

/**
 * Class ParameterArray
 *
 * @package tomi20v\phalswag
 *
 * @property Swagger $Swagger
 * @property ParameterFactory $ParameterFactory
 */
class ParameterArray extends ParameterAbstract {

	/**
	 * @param $Config
	 * @throws \Exception
	 */
	public function __construct($Config) {
		if (!isset($Config->items)) {
			throw new SwaggerDefinitionException('missing property: items');
		}
		parent::__construct($Config);
	}

	/**
	 * I build filter functions based on $this->_Swagger->collectionFormat
	 */
	protected function _buildFilters() {

//		if (!isset($this->_Swagger->collectionFormat)) {
//			throw new \Exception('missing property: collectionFormat');
//		}

		parent::_buildFilters();

		$collectionFormat = isset($this->_data->collectionFormat)
				? $this->_data->collectionFormat
				: 'csv';

		// first filter will cut $value to array if necessary
		$this->_filters[] = function($value) use ($collectionFormat) {
			if (is_null($value));
			elseif (is_array($value)) {
				if ($collectionFormat == 'multi') {
					throw new \Exception('TBI: MULTI');
				}
			}
			elseif (is_string($value)) {
				switch ($collectionFormat) {
				case 'ssv':
					$value = explode(' ', $value);
					break;
				case 'tsv':
					$value = explode('\\', $value);
					break;
				case 'pipes':
					$value = explode('|', $value);
					break;
				case 'csv':
				default:
					$value = explode(',', $value);
//					break;
				}
			}
			else {
				throw new \Exception('could not apply collectionFormat: ' . $collectionFormat);
			}
			return $value;
		};

		// I'll run each value through the pseudo-param entity's filter by setting the value and getting it back
		$ItemParameterSample = $this->ParameterFactory->buildParameter($this->_data->items);
		$this->_filters[] = function($value) use ($ItemParameterSample) {
			if (is_array($value)) {
				foreach ($value as &$eachValue) {
					$ItemParameterSample->setValue($eachValue);
					$eachValue = $ItemParameterSample->getValue();
				}
			}
			return $value;
		};
	}

	protected function _buildValidators() {
		parent::_buildValidators();
		// @see https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#user-content-parameterMaxItems
		// @see http://json-schema.org/latest/json-schema-validation.html#anchor42
		if (isset($this->_data->maxItems)) {
			$maxItems = $this->_data->maxItems;
			$this->_validators['maxItems'] = $this->ValidatorFactory->buildValidator(
				'Callback',
				[
					'message' => 'expected to have maximum ' . $maxItems . ' items',
					'callback' => function($value) use ($maxItems) {
						return (!empty($value) && (count($value) > $maxItems))
							? false
							: true;
					}
			]);
		}
		// @see https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#user-content-parameterMinItems
		// @see http://json-schema.org/latest/json-schema-validation.html#anchor45
		if (isset($this->_data->minItems)) {
			$minItems = $this->_data->minItems;
			$this->_validators['minItems'] = $this->ValidatorFactory->buildValidator(
				'Callback',
				[
					'message' => 'expected to have minimum ' . $minItems . ' items',
					'callback' => function($value) use ($minItems) {
						return (!empty($value) && (count($value) < $minItems))
							? false
							: true;
					}
			]);
		}
		// @see https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#user-content-parameterUniqueItems
		// @see http://json-schema.org/latest/json-schema-validation.html#anchor49
		if (isset($this->_data->uniqueItems) && $this->_data->uniqueItems) {
			$this->_validators['uniqueItems'] = $this->ValidatorFactory->buildValidator(
				'Callback',
				[
					'message' => 'expected to have unique items',
					'callback' => function($value) {
						if (!empty($value)) {
							if (count($value) != count(array_unique($value))) {
								return false;
							}
						}
						return true;
					}
			]);
		}
		// NOTE: this will overwrite parent's non multivalue enum validator
		// @see https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#user-content-parameterEnum
		// @see http://json-schema.org/latest/json-schema-validation.html#anchor76
		if (isset($this->_data->enum)) {
			$enum = $this->_data->enum;
			$this->_validators['enum'] = $this->ValidatorFactory->buildValidator(
				'MultiEnum',
				[
					'message' => 'expected values to be in',
					'enum' => (array) $enum,
				]
			);
		}
	}

}
