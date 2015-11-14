<?php

namespace tomi20v\phalswag\Model;

use Phalcon\Config;
use tomi20v\phalswag\Model\Swagger\Operation;

/**
 * Class Swagger - maybe I should be renamed to Helper...
 *
 * @package tomi20v\phalswag
 */
class Swagger extends AbstractItem {

	/** @var Config */
	protected $_data;

	protected static $_fields = [
		'swagger',
		'info' => 'SwaggerInfo',
		'host',
		'basePath',
		'schemes',
		'consumes',
		'produces',
		'paths' => 'Paths',
		'definitions' => 'Definitions',
		'parameters' => 'Parameters',
		'responses' => 'Responses',
		'securityDefinitions' => 'SecurityDefinitions',
		'security' => 'Security',
		'tags' => 'Tags',
		'externalDocs' => 'ExternalDocs',
	];

	/**
	 * @param string $operationId
	 * @return null|Operation
	 */
	public function getOperationById($operationId) {
		$className = static::CHILD_CLASS_NAMESPACE . 'Operation';
		if (isset($this->_data->paths)) {
			foreach ($this->_data->paths as $eachPathKey => &$EachPath) {
				/** @var Operation $EachOperation */
				foreach ($EachPath as $each_operation_key => &$EachOperation){
					if (isset($EachOperation->operationId) && $EachOperation->operationId == $operationId) {
						$Operation = $EachOperation;
						if (!$Operation instanceof $className) {
							$Operation = new $className($Operation, $eachPathKey, $each_operation_key);
							$EachOperation = $Operation;
						}
						return $Operation;
					}
				}
			}
		}
		return null;
	}

}
