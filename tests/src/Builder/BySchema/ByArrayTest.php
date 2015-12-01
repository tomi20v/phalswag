<?php

namespace tomi20v\phalswag\Builder\BySchema;

use Phalcon\Config;
use Phalcon\Mvc\Model;
use tomi20v\phalswag\Model\Swagger\Schema\SchemaArray;

/**
 * Class ByBooleanTest
 */
class ByArrayTest extends \AbstractTestCase {

	public function testBuildValue() {

		$anyValue = [
			'any value',
			'any other value'
		];
		$anyKey = 'anykey';

		$Model = new TestByArrayModel();
		$Model->$anyKey = $anyValue;

		$BuilderMock = $this->getMock(
			'tomi20v\phalswag\Builder\BySchema\ByString',
			['buildValue']
		);
		$BuilderMock->expects($this->at(0))
			->method('buildValue')
			->willReturn($anyValue[0]);
		$BuilderMock->expects($this->at(1))
			->method('buildValue')
			->willReturn($anyValue[1]);
		$BySchemaFactory = $this->getMock(
			'tomi20v\phalswag\Service\Builder\BySchemaFactory',
			['get']
		);
		$BySchemaFactory->expects($this->any())
			->method('get')
			->will($this->returnCallback(
				function() use ($BuilderMock) {
					return $BuilderMock;
				}
			));

		$Builder = new ByArray();
		$Builder->BySchemaFactory = $BySchemaFactory;

		$SchemaObject = new SchemaArray(new Config([
			'type' => 'array',
			'items' => [
				'type' => 'string',
			],
		]));

		$result = $Builder->buildValue($Model, $anyKey, $SchemaObject);

		$this->assertEquals($anyValue, $result);

	}

}

class TestByArrayModel extends Model {
	public $anykey;
}


