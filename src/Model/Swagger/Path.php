<?php

namespace tomi20v\phalswag\Model\Swagger;

/**
 * Class Path
 */
class Path extends AbstractItem {

	protected static $_fields = [
		'get' => 'Operation',
		'put' => 'Operation',
		'post' => 'Operation',
		'delete' => 'Operation',
		'options' => 'Operation',
		'head' => 'Operation',
		'patch' => 'Operation',
		'parameters' => 'Parameters',
	];

}
