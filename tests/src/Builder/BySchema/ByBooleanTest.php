<?php

namespace tomi20v\phalswag\Builder\BySchema;

use Phalcon\Config;
use stdClass;
use tomi20v\phalswag\Model\Swagger\TestableSchema;

/**
 * Class ByBooleanTest
 */
class ByBooleanTest extends \AbstractTestCase {

	/**
	 * @dataProvider buildValueProvider
	 */
	public function testBuildValue($value, $expected) {

		$key = 'anykey';
		$Model = new stdClass;
		$Model->{$key} = $value;

		$Schema = new TestableSchema(new Config);

		$Builder = new ByBoolean();

		$result = $Builder->buildValue(
			$Model,
			$key,
			$Schema
		);

		$this->assertTrue(is_bool($result));
		$this->assertSame($expected, $result);

	}

	/**
	 * @return array
	 */
	public function buildValueProvider() {
		return [
			['asd', true],
			[0, false],
			[1, true],
			[true, true],
			[false, false],
		];
	}

}
