<?php

namespace tomi20v\phalswag\Model\Swagger\Parameter;

use Phalcon\Config;

/**
 * Class SwaggerTest
 */
class ParameterIntegerTest extends \AbstractTestCase {

	/**
	 * @dataProvider inputValues
	 */
	public function testShouldfilterInput($val) {
		$Parameter = new ParameterInteger(new Config());
		$Parameter->setValue($val);
		$this->assertSame((int)$val, $Parameter->getValue());
	}

	public function inputValues() {
		return [
			[1],
			[0],
			[true],
			[null],
			['asd'],
			['0.123'],
		];
	}

}
