<?php

namespace tomi20v\phalswag\Model\Swagger;

use Phalcon\Config;

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

		if (isset($this->paths)) {

			/** @var Paths $Paths */
			$Paths = $this->paths;

			/** @var Path $EachPath */
			foreach ($Paths as $eachPathKey => $EachPath) {

				/** @var Operation $EachOperation */
				foreach ($EachPath as $eachOperationKey => $EachOperation) {
					$eachOperationId = isset($EachOperation->operationId) ? $EachOperation->operationId : null;
					if ($eachOperationId == $operationId) {
						return $EachOperation;
					}
				}

			}

		}

		return null;

	}

}
