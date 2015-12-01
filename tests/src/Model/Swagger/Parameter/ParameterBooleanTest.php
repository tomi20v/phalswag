<?php

namespace tomi20v\phalswag\Model\Swagger\Parameter;

use Phalcon\Config;

/**
 * Class SwaggerTest
 */
class ParameterBooleanTest extends \AbstractTestCase {

	/**
	 * @dataProvider booleanProvider
	 */
	public function testShouldReturnBoolean($val) {
		$Parameter = new ParameterBoolean(new Config());
		$Parameter->setValue($val);
		$this->assertSame((bool)$val, $Parameter->getValue());
	}

	public function booleanProvider() {
		return [
			[0],
			[1],
			[-1],
			[true],
			[false],
			['asd'],
			['0'],
			[[]],
			[[1,2,3]],
			[new \stdClass()],
		];
	}

}
