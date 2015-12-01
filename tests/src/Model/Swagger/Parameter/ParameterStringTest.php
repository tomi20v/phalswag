<?php

namespace tomi20v\phalswag\Model\Swagger\Parameter;

use Phalcon\Config;

/**
 * Class SwaggerTest
 */
class ParameterStringTest extends \AbstractTestCase {

	/**
	 * @dataProvider inputValues
	 */
	public function testShouldfilterInput($val) {
		$Parameter = new ParameterString(new Config());
		$Parameter->setValue($val);
		$this->assertSame((string)$val, $Parameter->getValue());
	}

	public function inputValues() {
		return [
			['asd'],
			['<x>sdf</x>'],
			[123],
		];
	}

	/**
	 * @dataProvider formatDateInputs
	 */
	public function testShouldValidateFormatDate($format, $val, $result) {
		$Parameter = new ParameterString(new Config([
			'format' => $format,
		]));
		$Parameter->setValue($val);
		$this->assertEquals($result, $Parameter->isValid());
	}

	public function formatDateInputs() {
		return [
			['date', 'asd', false],
			['date', '2001', false],
			['date', '2001-12-12', true],
			['date-time', 'asd', false],
			['date-time', '2001', false],
			['date-time', '2001-12-12', false],
			['date-time', '2001-12-12T11:22', true],
			['password', '', true],
		];
	}

	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage unknown format
	 */
	public function testShouldThrowOnInvalidFormat() {
		$Parameter = new ParameterString(new Config([
			'format' => 'invalid',
		]));
		$Parameter->isValid();
	}

	/**
	 * @dataProvider validateParams
	 */
	public function testShouldValidate($what, $whatVal, $val, $result) {
		$Parameter = new ParameterString(new Config([
			$what => $whatVal,
		]));
		$Parameter->setValue($val);
		$this->assertEquals($result, $Parameter->isValid());
	}

	public function validateParams() {
		return [
			// pattern tests throw segfault :/
			['pattern', '/asd/', 'asd',  true],
			['pattern', '/^([0-9]+)$/', 'asd',  false],
			['minLength', 3, 'a', false],
			['minLength', 3, 'asd', true],
			['minLength', 3, 'aasd', true],
			['maxLength', 3, 'a', true],
			['maxLength', 3, 'asd', true],
			['maxLength', 3, 'aasd', false],
		];
	}

}
