<?php

namespace tomi20v\phalswag\Model\Swagger;

/**
 * Class Paths
 */
class Paths extends AbstractCollection {

	const KEY_PATTERN = '/^\/[a-z][a-zA-Z0-9\/{}]*$/';

	protected static $_childClassName = 'Path';



}
