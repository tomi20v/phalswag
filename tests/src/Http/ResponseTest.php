<?php

namespace tomi20v\phalswag\Http;

/**
 * Class ResponseTest
 */
class ResponseTest extends \AbstractTestCase {

	private $_any_errors;

	public function setUp() {
		$this->_any_errors = [
			'any_field' => [
				'any_error',
			],
			'any_other_field' => [
				'any_other_error',
			],
		];
	}

	/**
	 * @dataProvider booleansProvider
	 */
	public function testGetSetSuccess($success) {

		$Response = new Response();

		$Response->setSuccess($success);

		$this->assertSame($success, $Response->getSuccess());

	}

	/**
	 * @return array
	 */
	public function booleansProvider() {
		return [
			[true],
			[false]
		];
	}

	public function testNoInitialErrors() {
		$Response = new Response();
		$this->assertNull($Response->getErrors());
	}

	public function testAddErrors() {
		$Response = new Response();
		$Response->addErrors($this->_any_errors);
		$this->assertSame($this->_any_errors, $Response->getErrors());
	}

	public function testAddErrorsInStringFormat() {
		$add_errors = [
			'any_field' => 'any_error',
		];
		$expected_errors = [
			'any_field' => ['any_error'],
		];
		$Response = new Response();
		$Response->addErrors($add_errors);
		$this->assertSame($expected_errors, $Response->getErrors());
	}

	public function testAddError() {
		$Response = new Response();
		foreach ($this->_any_errors as $eachField => $eachErrors) {
			$Response->addError($eachField, $eachErrors);
		}
		$this->assertSame($this->_any_errors, $Response->getErrors());
	}

	public function testSetResult() {
		$any_result = 'asd';
		$Response = new Response();
		$Response->setResult($any_result);
		$this->assertContains($any_result, $Response->getContent());
	}

	public function testSetMeta() {
		$Response = new Response();
		$Response->setMeta(['a' => 'b']);
		$this->assertContains('meta', $Response->getContent());
		$Response->setMeta(null);
		$this->assertNotContains('meta', $Response->getContent());
	}

	public function testSetAllCnt() {
		$any_cnt = 123;
		$Response = new Response();
		$Response->setAllCnt($any_cnt);
		$this->assertContains('"meta":{"allCnt":123', $Response->getContent());
	}

	public function testSetFoundCnt() {
		$any_cnt = 123;
		$Response = new Response();
		$Response->setFoundCnt($any_cnt);
		$this->assertContains('"meta":{"foundCnt":123', $Response->getContent());
	}

	public function testSetCnt() {
		$any_cnt = 123;
		$Response = new Response();
		$Response->setCnt($any_cnt);
		$this->assertContains('"meta":{"cnt":123', $Response->getContent());
	}

}
