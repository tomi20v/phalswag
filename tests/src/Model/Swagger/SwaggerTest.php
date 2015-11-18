<?php

namespace tomi20v\phalswag\Model\Swagger;

use Phalcon\Config;

/**
 * Class SwaggerTest
 */
class SwaggerTest extends \AbstractTestCase {

	public function testFindsOperationById() {

		$anyOtherOperationId = 'anyOtherOperationId';

		$Swagger = $this->_getSampleSwagger($anyOtherOperationId);

		$Operation = $Swagger->getOperationById($anyOtherOperationId);

		$this->assertEquals($anyOtherOperationId, $Operation->operationId);

	}

	public function testReturnsNullIfOperationByIdNotFound() {

		$anyOtherOperationId = 'anyOtherOperationId';

		$Swagger = $this->_getSampleSwagger($anyOtherOperationId);

		$Operation = $Swagger->getOperationById('nonExistingId');

		$this->assertNull($Operation);

	}

	/**
	 * @param $anyOtherOperationId
	 * @return Swagger
	 */
	private function _getSampleSwagger($anyOtherOperationId) {

		$sample = [
			'paths' => [
				'/any/path' => [
					'get' => [
						'operationId' => 'anyOperationId',
					],
				],
				'/any/other/path' => [
					'post' => [
						'operationId' => $anyOtherOperationId . 'Post',
					],
					'put' => [
						'operationId' => $anyOtherOperationId,
					],
				],
			],
		];

		$Swagger = new Swagger(new Config($sample));
		return $Swagger;
	}

}
