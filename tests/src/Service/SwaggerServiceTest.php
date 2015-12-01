<?php

namespace tomi20v\phalswag\Model\Swagger\Parameter;

use Phalcon\Config;
use Phalcon\Mvc\Model;
use tomi20v\phalswag\Model\Swagger\Operation;
use tomi20v\phalswag\Model\Swagger\Swagger;
use tomi20v\phalswag\Reader;
use tomi20v\phalswag\Service\Builder\BySchemaFactory;
use tomi20v\phalswag\Service\SwaggerService;
use tomi20v\phalswag\Validation\ValidatorFactory;

/**
 * Class BySchemaFactoryTest
 */
class SwaggerServiceTest extends \AbstractTestCase {

	public function testShouldRead() {

		$anySource = 'any source';
		$anyConfig = new Config();

		/** @var \PHPUnit_Framework_MockObject_MockObject|Reader $MockedReader */
		$MockedReader = $this->getMock('tomi20v\phalswag\Reader');
		$MockedReader->expects($this->once())
			->method('read')
			->with($anySource)
			->willReturn($anyConfig);

		$SwaggerService = new SwaggerService(
			$MockedReader,
			new ValidatorFactory(),
			new BySchemaFactory()
		);

		$Result = $SwaggerService->getSwagger($anySource);
		$this->assertTrue($Result instanceof Swagger);

	}

	public function testShouldFindOperationById() {
		$anyOperationId = 'anyOperationId';
		$anyResult = new Operation(new Config);

		$SwaggerService = new SwaggerService(
			new Reader(),
			new ValidatorFactory(),
			new BySchemaFactory()
		);
		$Swagger = $this->getMock(
			'tomi20v\phalswag\Model\Swagger\Swagger',
			['getOperationById'],
			[new Config]
		);
		$Swagger->expects($this->atLeastOnce())
			->method('getOperationById')
			->with($anyOperationId)
			->willReturn($anyResult);

		$Result = $SwaggerService->getOperationById($anyOperationId, $Swagger);
		$this->assertSame($anyResult, $Result);
	}

	public function testShouldBindRequest() {
		$Model = new TestableModel;
	}

}

class TestableModel extends Model {

}
