<?php

namespace tomi20v\phalswag\Swagger\Parameter\Entity;

use tomi20v\phalswag\Swagger\Parameter\EntityAbstract;

/**
 * Class EntityArray
 *
 * @package tomi20v\phalswag
 *
 * @property-read \tomi20v\phalswag\Swagger $Swagger
 * @property-read \tomi20v\phalswag\Swagger\ParameterFactory $ParameterFactory
 */
class EntityArray extends EntityAbstract {

	/**
	 * @var \tomi20v\phalswag\Swagger\Entity
	 */
	protected $_ItemsParameterEntity;

	public function __construct($SwaggerConfig) {
		parent::__construct($SwaggerConfig);
		if (!isset($this->_SwaggerConfig->items)) {
			throw new \Exception('missing property: items');
		}
		$this->_ItemsParameterEntity = $this->ParameterFactory->buildParameter($this->_SwaggerConfig->items, null);
	}

	protected function _buildValidators() {
		parent::_buildValidators();
		// @see https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#user-content-parameterMaxItems
		// @see http://json-schema.org/latest/json-schema-validation.html#anchor42
		if (isset($this->_SwaggerConfig->maxItems)) {
			$maxItems = $this->_SwaggerConfig->maxItems;
			$this->_validators['maxItems'] = $this->ValidatorFactory->buildValidator(
				'ValidationValidatorCallback',
				[
					'message' => 'expected to have maximum ' . $maxItems . ' items',
					'callback' => function($value) use ($maxItems) {
						if (!empty($value) && (count($value) > $maxItems)) {
							return false;
						}
						return true;
					}
			]);
		}
		// @see https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#user-content-parameterMinItems
		// @see http://json-schema.org/latest/json-schema-validation.html#anchor45
		if (isset($this->_SwaggerConfig->minItems)) {
			$minItems = $this->_SwaggerConfig->minItems;
			$this->_validators['minItems'] = $this->ValidatorFactory->buildValidator(
				'ValidationValidatorCallback',
				[
					'message' => 'expected to have minimum ' . $minItems . ' items',
					'callback' => function($value) use ($minItems) {
						if (!empty($value) && (count($value) < $minItems)) {
							return false;
						}
						return true;
					}
			]);
		}
		// @see https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#user-content-parameterUniqueItems
		// @see http://json-schema.org/latest/json-schema-validation.html#anchor49
		if (isset($this->_SwaggerConfig->uniqueItems) && $this->_SwaggerConfig->uniqueItems) {
			$this->_validators['uniqueItems'] = $this->ValidatorFactory->buildValidator(
				'ValidationValidatorCallback',
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
		// @see https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#user-content-parameterEnum
		// @see http://json-schema.org/latest/json-schema-validation.html#anchor76
		if (isset($this->_SwaggerConfig->enum)) {
			$enum = $this->_SwaggerConfig->enum;
			$this->_validators['enum'] = $this->ValidatorFactory->buildValidator(
				'ArrayEnum',
				[
					'message' => 'expected values to be in ' . implode(',', $enum),
					'domain' => $enum,
			]);
		}
	}

	protected function _getItemsParameterEntity() {
		static $ItemsParameterEntity;
		if (is_null($ItemsParameterEntity)) {
			$ItemsParameterEntity = $this->ParameterFactory->buildParameter($this->_SwaggerConfig->items, null);
		}
		return $ItemsParameterEntity;
	}

	/**
	 * I build filter functions based on $this->_SwaggerConfig->collectionFormat
	 */
	protected function _buildFilters() {

//		if (!isset($this->_SwaggerConfig->collectionFormat)) {
//			throw new \Exception('missing property: collectionFormat');
//		}

		parent::_buildFilters();

		$collectionFormat = isset($this->_SwaggerConfig->collectionFormat)
			? $this->_SwaggerConfig->collectionFormat
			: 'csv';

		// first filter will cut $value to array if necessary
		$this->_filters[] = function($value) use ($collectionFormat) {
			if (is_null($value));
			elseif (is_array($value) && ($collectionFormat == 'multi')) {
				throw new \Exception('MULTI');
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
					break;
				}
			}
			else {
				throw new \Exception('could not apply collectionFormat: ' . $collectionFormat);
			}
			return $value;
		};

		// I'll run each value through the pseudo-param entity's filter by setting the value and getting it back
//		$ItemsParameterEntity = $this->_ItemsParameterEntity;
		$ItemsParameterEntity = $this->_getItemsParameterEntity();
		$this->_filters[] = function($value) use ($ItemsParameterEntity) {
			// @todo here I have to call validation of $this->_ItemsParameterEntity on every $eachItem
			if (is_array($value)) {
				foreach ($value as &$eachValue) {
					$ItemsParameterEntity->setValue($eachValue);
					$eachValue = $ItemsParameterEntity->getValue();
				}
			}
			return $value;
		};
	}

}
