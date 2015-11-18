<?php

namespace tomi20v\phalswag\Model;

use Phalcon\Config;
use stdClass;

/**
 * Class TestableItem
 */
class TestableCollection extends AbstractCollection {

	const CHILD_CLASS_NAMESPACE = 'tomi20v\phalswag\Model';

	protected static $_childClassName = 'TestableItem';

	public static function getExample() {
		$Config = new Config();
		$Config->anyField = 'any field';
		$InnerConfig = new Config();
		$InnerConfig->anyField = 'any inner field';
		$Config->anyOtherField = $InnerConfig;
		$Item = new TestableItem($Config);
		$CollectionConfig = new Config([$Item]);
		return new TestableCollection($CollectionConfig);
	}

}
