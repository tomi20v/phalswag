<?php

namespace tomi20v\phalswag\Builder;

use Phalcon\Config;
use tomi20v\phalswag\Model\Swagger\SchemaAbstract;
use tomi20v\phalswag\Service\Builder\BySchemaFactory;

/**
 * Class BySchemaAbstractTest
 */
class BySchemaAbstractTest extends \AbstractTestCase {

	private $_any_Model;

	private $_any_Schema;

	private $_any_BuilderFactory;

	private $_any_key = 'any_key';

	public function setUp() {

		$this->_any_Model = new \stdClass();
		$this->_any_Schema = new TestableSchema(new Config);
		$this->_any_BuilderFactory = new BySchemaFactory();

	}

	public function testShouldBuild() {

		$any_value = 'any_value';
		$this->_any_Model->{$this->_any_key} = $any_value;
		$any_result = 'any_result';

		/** @var \PHPUnit_Framework_MockObject_MockObject|BySchemaAbstract $Mock */
		$Mock = $this->getMock(
			'tomi20v\phalswag\Builder\BySchemaAbstract',
			['_buildValue']
		);

		$Mock->expects($this->once())
			->method('_buildValue')
			->with($any_value, $this->_any_Schema)
			->willReturn($any_result);

		$result = $Mock->buildValue($this->_any_Model, $this->_any_key, $this->_any_Schema);
		$this->assertEquals($any_result, $result);

	}

	public function testShouldReturnNull() {

		/** @var \PHPUnit_Framework_MockObject_MockObject|BySchemaAbstract $Mock */
		$Mock = $this->getMock('tomi20v\phalswag\Builder\BySchemaAbstract');

		$result = $Mock->buildValue($this->_any_Model, $this->_any_key, $this->_any_Schema);
		$this->assertNull($result);

	}

	/**
	 * @expectedException \tomi20v\phalswag\Exception\InvalidModelForSchemaException
	 */
	public function testShouldThrowOnEmptyButRequiredKey() {

		$Config = new Config;
		$Config->required = true;
		$any_Schema = new TestableSchema($Config);

		/** @var \PHPUnit_Framework_MockObject_MockObject|BySchemaAbstract $Mock */
		$Mock = $this->getMock('tomi20v\phalswag\Builder\BySchemaAbstract', ['foo','_buildValue']);

		$Mock->buildValue($this->_any_Model, $this->_any_key, $any_Schema);

	}

}

/**
 * Class TestableSchema
 */
class TestableSchema extends SchemaAbstract {

	protected static $_fields = ['required'];

}
