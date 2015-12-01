<?php

namespace tomi20v\phalswag\Service\Builder;

use tomi20v\phalswag\Builder\BySchema\ByArray;
use tomi20v\phalswag\Builder\BySchema\ByInteger;
use tomi20v\phalswag\Builder\BySchema\ByObject;
use tomi20v\phalswag\Builder\BySchema\ByString;
use tomi20v\phalswag\Builder\BySchemaAbstract;
use tomi20v\phalswag\Exception\UnimplementedException;

/**
 * Class BySchemaFactory
 */
class BySchemaFactory {

	private $_builders = [];

	/**
	 * @param $type
	 * @return BySchemaAbstract
	 * @throws UnimplementedException
	 */
	public function get($type) {

		if (!isset($this->_builders[$type])) {

			switch ($type) {
				case 'object':
					$this->_builders[$type] = new ByObject();
					break;
				case 'array':
					$this->_builders[$type] = new ByArray();
					break;
				case 'string':
					$this->_builders[$type] = new ByString();
					break;
				case 'integer':
					$this->_builders[$type] = new ByInteger();
					break;
				default:
					throw new UnimplementedException('builder type: ' . $type);
			}

		}

		return $this->_builders[$type];

	}

}
