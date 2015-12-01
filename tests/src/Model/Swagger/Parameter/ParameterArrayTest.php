<?php

namespace tomi20v\phalswag\Model\Swagger\Parameter;

use Phalcon\Config;

/**
 * Class SwaggerTest
 */
class ParameterArrayTest extends \AbstractTestCase {

	private $_anyArray = ['a','b','c'];

	/**
	 * @expectedException \tomi20v\phalswag\Exception\SwaggerDefinitionException
	 */
	public function testShouldThrowIfNotItemsDefined() {
		$Parameter = new ParameterArray(new Config());
	}

	/**
	 * @dataProvider collectionFormatValues
	 */
	public function testShouldfilterInputItemscollectionFormat($collectionFormat, $val) {
		$Parameter = new ParameterArray(new Config([
			'collectionFormat' => $collectionFormat,
			'items' => [
				'type' => 'string',
			]
		]));
		$Parameter->setValue($val);
		$this->assertSame($this->_anyArray, $Parameter->getValue());
	}

	/**
	 * @return array
	 */
	public function collectionFormatValues() {
		return [
			['ssv', implode(' ', $this->_anyArray)],
			['tsv', implode('\\', $this->_anyArray)],
			['pipes', implode('|', $this->_anyArray)],
			['csv', implode(',', $this->_anyArray)],
			[null, implode(',', $this->_anyArray)],
		];
	}

	/**
	 * @expectedException \Exception
	 */
	public function testShouldThrowOnInvalidValue() {
		$Parameter = new ParameterArray(new Config([
			'items' => [
				'type' => 'string',
			]
		]));
		$Parameter->setValue(new \stdClass());
	}

	/**
	 * @dataProvider itemsValues
	 */
	public function testShouldFilterItems($type, $raw, $expected) {
		$Parameter = new ParameterArray(new Config([
			'items' => [
				'type' => $type,
			],
		]));
		$Parameter->setValue($raw);
		$this->assertSame($expected, $Parameter->getValue());
	}

	/**
	 * @return array
	 */
	public function itemsValues() {
		return [
			['integer', '1,2,3,4', [1,2,3,4]],
			['integer', '1.1,2.1,3.1,4.1', [1,2,3,4]],
			['string', '1,2,3,4', ['1','2','3','4']],
			['number', '1,2,3,4', [1.0,2.0,3.0,4.0]],
			['number', '1.1,2.1,3.1,4.1', [1.1,2.1,3.1,4.1]],
			['number', [1.1,2.1,3.1,4.1], [1.1,2.1,3.1,4.1]],
		];
	}

	/**
	 * @expectedException \Exception
	 * @expectedExceptionMessage TBI
	 */
	public function testShouldThrowOnMulti() {
		$Parameter = new ParameterArray(new Config([
			'items' => ['type' => 'string'],
			'collectionFormat' => 'multi',
		]));
		$Parameter->setValue([]);
	}

	/**
	 * @dataProvider validatorValues
	 */
	public function testShouldValidate($validatorName, $validatorValue, $value, $expected) {
		$Parameter = new ParameterArray(new Config([
			'type' => 'array',
			$validatorName => $validatorValue,
			'items' => ['type' => 'string'],
		]));
		$Parameter->setValue($value);
		$this->assertEquals($expected, $Parameter->isValid());
	}

	public function validatorValues() {
		return [
			['maxItems', 3, [1,2], true],
			['maxItems', 3, [1,2,3], true],
			['maxItems', 3, [1,2,3,4], false],
			['minItems', 3, [1,2], false],
			['minItems', 3, [1,2,3], true],
			['minItems', 3, [1,2,3,4], true],
			['minItems', 3, [1,2,3,4], true],
			['uniqueItems', 1, [1,2,3,4], true],
			['uniqueItems', 1, [1,2,3,4,4], false],
			['uniqueItems', 0, [1,2,3,4], true],
			['uniqueItems', 0, [1,2,3,4,4], true],
			['enum', [1,2], [1,2], true],
			['enum', [1,2,3], [1,2], true],
			['enum', [1,2,3], [1,1,2], true],
			['enum', [1,2], [1,1,2,3], false],
		];
	}

}
