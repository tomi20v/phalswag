<?php

namespace tomi20v\phalswag\Model;

use Phalcon\Config;
use stdClass;

/**
 * Class TestableItem
 */
class TestableItem extends AbstractItem {

	const CHILD_CLASS_NAMESPACE = 'tomi20v\phalswag';

	protected static $_fields = [
		'anyField',
		'anyOtherField' => 'Model\TestableItem',
		'anyNonExistingClassname' => 'anyNonExistingClassnamex',
	];

	public function __construct(Config $data) {
		parent::__construct($data);
		static::$_fields['anyCallableField'] = function($var) {
			$ret = new stdClass;
			$ret->var = $var;
			return $ret;
		};
	}

	public static function getExample() {
		$Config = new Config();
		$Config->anyField = 'any field';
		$InnerConfig = new Config();
		$InnerConfig->anyField = 'any inner field';
		$Config->anyOtherField = $InnerConfig;
		return new TestableItem($Config);
	}

}
