<?php

use tomi20v\phalswag\Http\Response;
use tomi20v\phalswag\Http\ResponseBuilder;

/**
 * Created by PhpStorm.
 * User: t
 * Date: 4/10/15
 * Time: 3:03 AM
 */

class ResponseBuilderTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var ResponseBuilder
	 */
	protected $_ResponseBuilder;

	protected $_Stub;

	public function setUp() {
		$this->_ResponseBuilder = new ResponseBuilder;
		$this->_Stub = $this->getMock('tomi20v\phalswag\Http\ResponseBuilder', ['buildSuccess', 'buildError']);
	}

	/**
	 * @covers ResponseBuilder::build()
	 */
	public function testBuild() {

		$data = ['sample'];
		$meta = 42;

		$this->_Stub
			->expects($this->once())
			->method('buildSuccess')
			->with($this->equalTo($data), $this->equalTo($meta));

		$this->_Stub->build(true, $data, $meta);

		$errors = ['_'=>['internal']];
		$meta = null;

		$this->_Stub
			->expects($this->once())
			->method('buildError')
			->with($this->equalTo(null), $this->equalTo($errors), $this->equalTo($meta))
		;

		$this->_Stub->build(false, $errors, $meta);

	}

	/**
	 * @covers ResponseBuilder::buildSuccess()
	 */
	public function testBuildSuccess() {

		$result = 'asd';
		$meta = [42];
		$content = ['success'=>true, 'result'=>$result, 'meta'=>$meta];

		$Response = $this->_ResponseBuilder->buildSuccess($result, $meta);
		$this->assertTrue($Response instanceof Response);
		$this->assertEquals($content, json_decode($Response->getContent(), true));

	}

	/**
	 * @covers ResponseBuilder::buildError()
	 */
	public function testBuildError() {

		$status = 500;
		$errors = ['_'=>['internal']];
		$result = 'asd';
		$meta = [42];
		$content = ['success'=>false, 'errors'=>$errors, 'result'=>$result, 'meta'=>$meta];

		$Response = $this->_ResponseBuilder->buildError($status, $errors, $result, $meta);
		$this->assertTrue($Response instanceof Response);
		$this->assertStringStartsWith((string)$status, $Response->getHeaders()->get('Status'));

	}

	/**
	 * @covers ResponseBuilder::buildSimpleError
	 */
	public function testBuildSimpleError() {

		$status = 404;
		$field = 'asd';

		$this->_Stub
			->expects($this->once())
			->method('buildError')
			->with($status, [$field=>[$status]]);

		$this->_Stub->buildSimpleError($status, $field);

	}

	/**
	 * @covers ResponseBuilder::buildBuild200()
	 */
	public function testBuild200() {

		$result = 'asd';
		$metaOrCnt = null;

		$this->_Stub
			->expects($this->once())
			->method('buildSuccess')
			->with($result, $metaOrCnt);

		$this->_Stub->buildHttp200($result, $metaOrCnt);
	}

}
