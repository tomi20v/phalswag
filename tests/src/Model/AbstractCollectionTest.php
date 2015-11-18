<?php

namespace tomi20v\phalswag\Model;

use Phalcon\Config;

/**
 * Class AbstractItemTest
 */
class AbstractCollectionTest extends \AbstractTestCase {

	public function testIterator() {

		$TestableItem = TestableItem::getExample();
		$Collection = new TestableCollection(
			new Config([$TestableItem])
		);

		$i = 0;
		foreach ($Collection as $eachKey => $eachItem) {
			$this->assertSame($TestableItem, $eachItem);
			$i++;
		}
		$this->assertEquals(1, $i);
	}

}
