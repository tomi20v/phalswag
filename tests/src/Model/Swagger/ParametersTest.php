<?php

namespace tomi20v\phalswag\Model\Swagger;

use Phalcon\Config;

/**
 * Class SwaggerTest
 */
class ParametersTest extends \AbstractTestCase {

	private $_any_name = 'anyname';
	private $_any_location = 'anylocation';
	private $_any_other_name = 'anyothername';
	private $_any_other_location = 'anyotherlocation';
	/** @var Config */
	private $_Config;
	/** @var Config */
	private $_P1;
	/** @var Config */
	private $_P2;

	public function setUp() {

		$P1 = new Config;
		$P1->name = $this->_any_name;
		$P1->location = $this->_any_location;
		$this->_P1 = $P1;

		$P2 = new Config;
		$P2->name = $this->_any_other_name;
		$P2->location = $this->_any_other_location;
		$this->_P2 = $P2;

		$Config = new Config;
		$Config[0] = $P1;
		$Config[1] = $P2;
		$this->_Config = $Config;

	}

	public function testShouldBuildAndReturnParams() {

		/** @var Parameters|\PHPUnit_Framework_MockObject_MockObject $Parameters */
		$Parameters = $this->_getMockedParameters();
		$Parameters->expects($this->any())
			->method('_buildParameters')
			->will($this->returnArgument(0));

		$Parameter = $Parameters->get($this->_any_name, $this->_any_location);
		$this->assertSame($this->_P1, $Parameter);

		$Parameter = $Parameters->get($this->_any_other_name, $this->_any_other_location);
		$this->assertSame($this->_P2, $Parameter);

	}

	public function testShouldReturnParameter() {

		$P2 = new TestableParameter(new Config);
		$P2->name = $this->_any_other_name;
		$P2->location = $this->_any_other_location;
		$this->_Config[1] = $P2;

		/** @var Parameters|\PHPUnit_Framework_MockObject_MockObject $Parameters */
		$Parameters = $this->_getMockedParameters();

		$Parameter = $Parameters->get($this->_any_other_name, $this->_any_other_location);
		$this->assertSame($P2, $Parameter);

	}

	public function testShouldBuildItemsWhenIterated() {
		$Parameters = $this->_getMockedParameters();
		$Parameters->expects($this->any())
			->method('_buildParameters')
			->willReturnArgument(0);

		foreach ($Parameters as $EachParameter) {
			$this->assertTrue($EachParameter instanceof Config);
			$this->assertContains($EachParameter, [$this->_P1, $this->_P2]);
		}

	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject
	 */
	private function _getMockedParameters() {

		$Parameters = $this->getMock(
			'tomi20v\phalswag\Model\Swagger\Parameters',
			['_buildParameters'],
			[$this->_Config]
		);
		return $Parameters;
	}

}
