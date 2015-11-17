<?php

namespace tomi20v\phalswag\Builder\Http;

use tomi20v\phalswag\Http\Response;

/**
 * Class ResponseBuilder - build REST responses. Note that all these responses are rest responses, the http* ones
 * 		map to the proper rest structure
 *
 * @package tomi20v\phalswag
 */
class ResponseBuilder {

	/**
	 * @param array|null $result
	 * @param int|array|null $metaOrCnt
	 * @return Response
	 */
	public function buildSuccess($result=null, $metaOrCnt=null) {

		$Response = $this->_getResponseInstance();
		$Response
			->setSuccess(true)
			->setResult($result)
			->setMeta($metaOrCnt);
		return $Response;

	}

	/**
	 * @param int $status
	 * @param array|null $errors
	 * @param array|null $result
	 * @param array|null $meta
	 * @return Response
	 */
	public function buildError($status, $errors=null, $result=null, $meta=null) {

		if (!is_numeric($status)) {
			$status = 404;
		}

		$Response = $this->_getResponseInstance();
		$Response->setStatusCode($status, null);
		$Response
			->setSuccess(false)
			->setResult($result)
			->setMeta($meta);

		if (!is_null($errors)) {
			$Response->addErrors($errors);
		}

		return $Response;

	}

	/**
	 * @return Response
	 */
	protected function _getResponseInstance() {
		return new Response();
	}

}
