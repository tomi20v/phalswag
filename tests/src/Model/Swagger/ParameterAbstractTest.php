<?php

namespace tomi20v\phalswag\Model\Swagger;

use Phalcon\Config;

/**
 * Class SwaggerTest
 */
class ParameterAbstractTest extends \AbstractTestCase {

	private $_oldMethod;

	public function testShouldSetDefaultValue() {
		$Config = new Config;
		$anyValue = 'any Value';
		$Config->default = $anyValue;
		$Parameter = new TestableParameter($Config);
		$this->assertEquals($anyValue, $Parameter->getValue());
	}

	public function testShouldGetName() {
		$Config = new Config;
		$anyName = 'any name';
		$Config->name = $anyName;
		$Parameter = new TestableParameter($Config);
		$this->assertEquals($anyName, $Parameter->getName());
	}

	public function testShouldHaveEmptyValueOnConstruct() {
		$Parameter = new TestableParameter(new Config);
		$this->assertNull($Parameter->getValue());
	}

	public function testShouldSetGetValue() {
		$any_value = 'any Value';
		$Parameter = new TestableParameter(new Config);
		$Parameter->setValue($any_value);
		$this->assertEquals($any_value, $Parameter->getValue());
	}

	public function testShouldFetchFromPath() {

		$anyName = 'any_name';
		$anyValue = 'any value';

		$Parameter = new TestableParameter(new Config([
			'name' => $anyName,
			'in' => 'path',
		]));

		$pathParams = [
			$anyName => $anyValue,
		];
		$Request = $this->getMock('Phalcon\Http\Request');
		$this->assertTrue($Parameter->fetch($pathParams, $Request));
		$this->assertEquals($anyValue, $Parameter->getValue());
	}

	public function testShouldFetchFromQuery() {

		$anyName = 'any_name';
		$anyValue = 'any value';

		$Parameter = new TestableParameter(new Config([
				'name' => $anyName,
				'in' => 'query',
		]));

		$Request = $this->getMock(
			'Phalcon\Http\Request',
			['hasQuery', 'getQuery']
		);
		$Request->expects($this->any())
			->method('hasQuery')
			->with($anyName)
			->willReturn(true);
		$Request->expects($this->any())
			->method('getQuery')
			->with($anyName)
			->willReturn($anyValue);

		$this->assertTrue($Parameter->fetch([], $Request));
		$this->assertEquals($anyValue, $Parameter->getValue());
	}

	/**
	 * @dataProvider fromDataProvider
	 */
	public function testShouldFetchFrom($in, $hasMethod, $getMethod, $method) {

		$anyName = 'any_name';
		$anyValue = 'any value';

		$Parameter = new TestableParameter(new Config([
				'name' => $anyName,
				'in' => $in,
		]));

		$Request = $this->getMock(
			'Phalcon\Http\Request',
			[$hasMethod, $getMethod]
		);
		$Request->expects($this->any())
			->method($hasMethod)
			->with($anyName)
			->willReturn(true);
		$Request->expects($this->any())
			->method($getMethod)
			->with($anyName)
			->willReturn($anyValue);

		$this->_setMethod($method);

		$this->assertTrue($Parameter->fetch([], $Request));
		$this->assertEquals($anyValue, $Parameter->getValue());

		$this->_restoreMethod();
	}

	public function fromDataProvider() {
		return [
			['query', 'hasQuery', 'getQuery', null],
			['formData', 'hasPost', 'getPost', 'POST'],
			['formData', 'hasPut', 'getPut', 'PUT'],
		];
	}

	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage TBI
	 */
	public function testShouldThrowOnDeleteMethod() {
		$anyName = 'any_name';
		$Parameter = new TestableParameter(new Config([
			'name' => $anyName,
			'in' => 'formData',
		]));
		$this->_setMethod('DELETE');
		$Parameter->fetch([], $this->getMock('Phalcon\Http\Request'));
		$this->_restoreMethod();
	}

	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage TBI
	 */
	public function testShouldThrowOnBody() {
		$anyName = 'any_name';
		$Parameter = new TestableParameter(new Config([
			'name' => $anyName,
			'in' => 'body',
		]));
		$Parameter->fetch([], $this->getMock('Phalcon\Http\Request'));
	}

	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage invalid
	 */
	public function testShouldThrowOnInvalidIn() {
		$anyName = 'any_name';
		$Parameter = new TestableParameter(new Config([
			'name' => $anyName,
			'in' => 'invalid',
		]));
		$Parameter->fetch([], $this->getMock('Phalcon\Http\Request'));
	}

	public function testShouldBeValid() {
		$anyName = 'any_name';
		$Parameter = new TestableParameter(new Config([
			'name' => $anyName,
			'in' => 'path',
		]));
		$this->assertTrue($Parameter->isValid());
		$this->assertEmpty($Parameter->getValidationMessages());
	}

	public function testRequiredShouldBeChecked() {
		$anyName = 'any_name';
		$anyValue = 'any value';
		$Parameter = new TestableParameter(new Config([
			'name' => $anyName,
			'in' => 'path',
			'required' => true,
		]));
		$ValidationStub = $this->getMock(
			'tomi20v\phalswag\ValidationStub',
			['setValue', 'getMessages']
		);
		$ValidationStub->expects($this->any())
			->method('setValue')
			->with($anyValue)
			->willReturnCallback(function() use ($ValidationStub) {
				return $ValidationStub;
			});
		$ValidationStub->expects($this->any())
			->method('getMessages')
			->willReturn(['any_error']);
		$Parameter->ValidationStub = $ValidationStub;
		$Parameter->setValue($anyValue);

		$this->assertFalse($Parameter->isValid());
		$this->assertNotEmpty($Parameter->getValidationMessages());
	}

	public function testRequiredEnumBeChecked() {
		$anyName = 'any_name';
		$anyValue = 'any value';
		$Parameter = new TestableParameter(new Config([
			'name' => $anyName,
			'in' => 'path',
			'enum' => ['any_other_value'],
		]));
		$ValidationStub = $this->getMock(
			'tomi20v\phalswag\ValidationStub',
			['setValue', 'getMessages']
		);
		$ValidationStub->expects($this->any())
			->method('setValue')
			->with($anyValue)
			->willReturnCallback(function() use ($ValidationStub) {
				return $ValidationStub;
			});
		$ValidationStub->expects($this->any())
			->method('getMessages')
			->willReturn(['any_error']);
		$Parameter->ValidationStub = $ValidationStub;
		$Parameter->setValue($anyValue);

		$this->assertFalse($Parameter->isValid());
		$this->assertNotEmpty($Parameter->getValidationMessages());
	}

	public function testShouldRunStringFilters() {

		$anyValue = 'any value';
		$anyFilterName = 'anyFilterName';

		$MockedFilter = $this->getMock('Phalcon\Filter', ['sanitize']);
		$MockedFilter->expects($this->atLeastOnce())
			->method('sanitize')
			->willReturnCallback(function($value, $filter) use ($anyValue, $anyFilterName) {
				$this->assertEquals($anyValue, $value);
				$this->assertEquals($anyFilterName, $filter);
			});

		$Parameter = new TestableParameterWithFilter(new Config());
		$Parameter->testableInjectFilter($anyFilterName);

		$Parameter->Filter = $MockedFilter;

		$Parameter->setValue($anyValue);

	}

	public function testShouldRunCallableFilters() {

		$anyValue = 'any value';

		$wasCalled = false;
		$anyFilter = function($value) use (&$wasCalled, $anyValue) {
			$wasCalled = true;
			$this->assertSame($anyValue, $value);
		};

		$Parameter = new TestableParameterWithFilter(new Config());
		$Parameter->testableInjectFilter($anyFilter);

		$Parameter->setValue($anyValue);

		$this->assertTrue($wasCalled);

	}

	/**
	 * @expectedException \Exception
	 * @expectedExceptionMessage invalid filter type
	 */
	public function testShouldThrowOnInvalidFilter() {
		$Parameter = new TestableParameterWithFilter(new Config);
		$Parameter->testableInjectFilter(new \stdClass());
		$Parameter->setValue('any value');
	}

	private function _setMethod($method) {
		$this->_oldMethod = null;
		if ($method) {
			if (isset($_SERVER['REQUEST_METHOD'])) {
				$this->_oldMethod = $_SERVER['REQUEST_METHOD'];
			}
			$_SERVER['REQUEST_METHOD'] = $method;
		}
	}

	private function _restoreMethod() {
		if ($this->_oldMethod) {
			$_SERVER['REQUEST_METHOD'] = $this->_oldMethod;
		}
		else {
			unset($_SERVER['REQUEST_METHOD']);
		}
	}

}

/**
 * Class TestableParameterWithFilter
 */
class TestableParameterWithFilter extends TestableParameter {

	public function testableInjectFilter($filter) {
		$this->_filters[] = $filter;
	}

}
