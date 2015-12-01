<?php

namespace tomi20v\phalswag\Model\Swagger\Parameter;

use Phalcon\Config;

/**
 * Class ParameterNumberTest
 */
class ParameterNumberTest extends \AbstractTestCase {

	/**
	 * @dataProvider inputValues
	 */
	public function testShouldfilterInput($val) {
		$Parameter = new ParameterNumber(new Config());
		$Parameter->setValue($val);
		$this->assertSame((float)$val, $Parameter->getValue());
	}

	public function inputValues() {
		return [
			[1],
			[0],
			[1.23],
			[-0.43],
			[true],
			[null],
			['asd'],
			['0.123'],
		];
	}

	/**
	 * @dataProvider validatorDataProvider
	 */
	public function testValidators($validatorName,$validatorValue, $value, $expected) {
		$Parameter = new ParameterNumber(new Config([
			$validatorName => $validatorValue,
		]));
		$Parameter->setValue($value);
		$this->assertSame($expected, $Parameter->isValid());
	}

	public function validatorDataProvider() {
		return [
			['maximum', 2, 1, true],
			['maximum', 2, 2, true],
			['maximum', 2, 3, false],
			['minimum', 2, 3, true],
			['minimum', 2, 2, true],
			['minimum', 2, 1, false],
			['multipleOf', 2, 1, false],
			['multipleOf', 2, 2, true],
			['multipleOf', 2, 5, false],
			['multipleOf', 2, 6, true],
		];
	}
}
