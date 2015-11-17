<?php

namespace tomi20v\phalswag\Builder\BySchema;

use Phalcon\Config;
use stdClass;
use tomi20v\phalswag\Model\Swagger\SchemaAbstract;

/**
 * Class ByStringTest
 */
class ByIntegerTest extends \AbstractTestCase {

	/**
	 * @dataProvider buildValueProvider
	 */
	public function testBuildValue($value, $expected) {

		$key = 'anykey';
		$Model = new stdClass;
		$Model->{$key} = $value;

		$Schema = new TestableSchema(new Config);

		$Builder = new ByInteger();

		$result = $Builder->buildValue(
				$Model,
				$key,
				$Schema
		);

		$this->assertTrue(is_int($result));
		$this->assertSame($expected, $result);

	}

	/**
	 * @return array
	 */
	public function buildValueProvider() {
		return [
				['asd', 0],
				['12asd', 12],
				['012asd', 12],
				[0, 0],
				[11, 11],
				[true, 1],
				[false, 0],
		];
	}

}
