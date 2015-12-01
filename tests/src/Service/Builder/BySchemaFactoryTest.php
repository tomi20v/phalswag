<?php

namespace tomi20v\phalswag\Model\Swagger\Parameter;

use Phalcon\Config;
use tomi20v\phalswag\Service\Builder\BySchemaFactory;

/**
 * Class BySchemaFactoryTest
 */
class BySchemaFactoryTest extends \AbstractTestCase {

	/**
	 * @dataProvider builderData
	 */
	public function testShouldReturnCachedBuilders($type, $className) {
		$Factory = new BySchemaFactory();
		$Builder = $Factory->get($type);
		$this->assertInstanceOf($className, $Builder);
		$this->assertSame($Builder, $Factory->get($type));
	}

	public function builderData() {
		$ns = 'tomi20v\phalswag\Builder\BySchema\\';
		return [
			['object', $ns . 'ByObject'],
			['array', $ns . 'ByArray'],
			['string', $ns . 'ByString'],
			['integer', $ns . 'ByInteger'],
		];
	}

	/**
	 * @expectedException \tomi20v\phalswag\Exception\UnimplementedException
	 */
	public function testShouldThrowOnInvalidType() {
		$Factory = new BySchemaFactory();
		$Factory->get('invalidType');
	}

}
