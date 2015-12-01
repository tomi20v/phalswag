<?php

namespace tomi20v\phalswag\Builder\BySchema;

use Phalcon\Config;
use Phalcon\Mvc\Model;
use tomi20v\phalswag\Model\Swagger\Schema\SchemaObject;
use tomi20v\phalswag\Model\Swagger\Schema\SchemaString;

/**
 * Class ByBooleanTest
 */
class ByObjectTest extends \AbstractTestCase {

	public function testBuildValue() {

		$anyValue = 'any value';
		$anyKey = 'anykey';
		$anyProperty = new SchemaString(new Config(['type' => 'string']));

		$Model = new TestByObjectModel();
		$Model->$anyKey = $anyValue;

		$BuilderMock = $this->getMock(
			'tomi20v\phalswag\Builder\BySchema\ByString',
			['buildValue']
		);
		$BuilderMock->expects($this->once())
			->method('buildValue')
			->with($anyValue, $anyKey, $anyProperty)
			->willReturn($anyValue);
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

		$Builder = new ByObject();
		$Builder->BySchemaFactory = $BySchemaFactory;

		$SchemaObject = new SchemaObject(new Config([
			'properties' => [
				$anyKey => [
					'type' => 'string',
				],
			],
		]));

		$result = $Builder->buildValue($Model, $anyKey, $SchemaObject);

		$this->assertEquals([$anyKey => $anyValue], $result);

	}

}

/**
 * Class TestByObjectModel
 */
class TestByObjectModel extends Model {
	public $anykey;
}
