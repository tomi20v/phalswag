<?php

namespace tomi20v\phalswag\Builder\Http;

use tomi20v\phalswag\Http\Response;

/**
 * Class ResponseBuilderTest
 */
class ResponseBuilderTest extends \AbstractTestCase {

	public function testShouldBuildSuccess() {
		$Builder = $this->_getBuilder();
		$Response = $Builder->buildSuccess(null, null);
		$this->assertTrue($Response instanceof Response);
		$this->assertTrue($Response->getSuccess());
	}

	public function testShouldBuildError() {
		$Builder = $this->_getBuilder();
		$Response = $Builder->buildError(null);
		$this->assertTrue($Response instanceof Response);
		$this->assertFalse($Response->getSuccess());
	}

	public function testShouldBuildDefault404() {
		$Builder = $this->_getBuilder();
		$Result = $Builder->buildError(null);
		$this->assertContains('404', $Result->getStatusCode());
	}

	public function testShouldBuildWithParams() {

		$any_status = '500';
		$any_errors = ['any_error'];
		$any_result = 'any_result';
		$any_meta = ['any_meta'];

		$Response = $this->getMock(
			'tomi20v\phalswag\Http\Response',
			['setStatusCode','setResult','setMeta','addErrors']
		);

		$Response
			->expects($this->once())
			->method('setStatusCode')
			->with($any_status, null)
			->willReturn($Response);
		$this
			->_expectsOnce($Response, 'addErrors', $any_errors, $Response)
			->_expectsOnce($Response, 'setResult', $any_result, $Response)
			->_expectsOnce($Response, 'setMeta', $any_meta, $Response);

		/** @var \PHPUnit_Framework_MockObject_MockObject|ResponseBuilder $Builder */
		$Builder = $this->getMock(
			'tomi20v\phalswag\Builder\Http\ResponseBuilder',
			['_getResponseInstance']
		);
		$Builder
			->expects($this->any())
			->method('_getResponseInstance')
			->willReturn($Response);

		$Builder->buildError(
			$any_status,
			$any_errors,
			$any_result,
			$any_meta
		);

	}

	/**
	 * @return ResponseBuilder
	 */
	private function _getBuilder() {
		return new ResponseBuilder();
	}

}
