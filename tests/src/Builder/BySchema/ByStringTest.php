<?php

namespace tomi20v\phalswag\Builder\BySchema;

use Phalcon\Config;
use stdClass;
use tomi20v\phalswag\Model\Swagger\SchemaAbstract;

/**
 * Class ByStringTest
 */
class ByStringTest extends \AbstractTestCase {

	/**
	 * @dataProvider buildValueProvider
	 */
	public function testBuildValue($value) {

		$key = 'anykey';
		$Model = new stdClass;
		$Model->{$key} = $value;

		$Schema = new TestableSchema(new Config);

		$Builder = new ByString();

		$result = $Builder->buildValue(
			$Model,
			$key,
			$Schema
		);

		$this->assertTrue(is_string($result));

	}

	/**
	 * @return array
	 */
	public function buildValueProvider() {
		return [
			['asd'],
			[1],
			[true],
		];
	}

}

/**
 * Class TestableSchema
 */
class TestableSchema extends SchemaAbstract {}
