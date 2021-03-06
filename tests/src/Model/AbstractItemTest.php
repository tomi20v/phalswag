<?php

namespace tomi20v\phalswag\Model;

use Phalcon\Config;

/**
 * Class AbstractItemTest
 */
class AbstractItemTest extends \AbstractTestCase {

	public function testGetScalar() {

		$anyValue = 'any value';

		$Config = new Config;
		$Config->anyField = $anyValue;

		$Item = new TestableItem($Config);

		$this->assertSame($anyValue, $Item->anyField);

	}

	public function testGetByClassname() {

		$anyValue = 'any value';

		$InnerConfig = new Config();
		$InnerConfig->anyField = $anyValue;
		$InnerItem = new TestableItem($InnerConfig);

		$Config = new Config();
		$Config->anyOtherField = $InnerConfig;

		$Item = new TestableItem($Config);

		$this->assertEquals($InnerItem, $Item->anyOtherField);

	}

	public function testGetByCallable() {

		$anyValue = 'any value';

		$InnerConfig = new Config();
		$InnerConfig->anyField = $anyValue;

		$Config = new Config();
		$Config->anyCallableField = $InnerConfig;
		$Item = new TestableItem($Config);

		$this->assertEquals($InnerConfig, $Item->anyCallableField->var);

	}

	/**
	 * @expectedException tomi20v\phalswag\Exception\UnimplementedException
	 */
	public function testShouldThrowUnimplemented() {

		$anyValue = 'any value';

		$InnerConfig = new Config();
		$InnerConfig->anyField = $anyValue;

		$Config = new Config();
		$Config->anyNonExistingClassname = $InnerConfig;
		$Item = new TestableItem($Config);

		$Item->anyNonExistingClassname;

	}

	/**
	 * @expectedException tomi20v\phalswag\Exception\InvalidKeyException
	 * @expectedExceptionMessage a_s_d
	 */
	public function testGetShouldThrowOnInvalidKey() {
		$TestableItem = TestableItem::getExample();
		$TestableItem->a_s_d;
	}

	public function testIterator() {

		$TestableItem = TestableItem::getExample();

		$expectedFields = ['anyField', 'anyOtherField'];
		$typeAsserts = [
			function($that, $val) {
				$that->assertTrue(is_string($val));
			},
			function($that, $val) {
				$that->assertTrue($val instanceof TestableItem);
			}
		];

		foreach ($TestableItem as $eachKey => $eachValue) {
			$expectedField = reset($expectedFields);
			$this->assertEquals($expectedField, $eachKey);
			array_shift($expectedFields);
			/** @var callable $lambda */
			$lambda = array_shift($typeAsserts);
			$lambda($this, $eachValue);
		}

	}

	public function testCurrentReturnsItems() {
		$TestableItem = TestableItem::getExample();
		$this->assertEquals('any field', $TestableItem->current());
		$TestableItem->next();
		$current = $TestableItem->current();
		$this->assertTrue($current instanceof TestableItem);
	}

	public function testCurrentReturnsNullOnInvalid() {
		$TestableItem = TestableItem::getExample();
		$TestableItem->next();
		$TestableItem->next();
		$this->assertNull($TestableItem->current());
	}

}
