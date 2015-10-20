<?php

namespace tomi20v\phalswag\Http;

/**
 * Class ResponseBuilder - build REST responses. Note that all these responses are rest responses, the http* ones
 * 		map to the proper rest structure
 *
 * @package tomi20v\phalswag
 */
class ResponseBuilder implements ResponseBuilderInterface {

	public function buildSuccess($result=null, $metaOrCnt=null) {

		$Response = new Response;
		$Response
			->setSuccess(true)
			->setResult($result)
			->setMeta($metaOrCnt);
		return $Response;

	}

	public function buildError($status, $errors=null, $result=null, $metaOrCnt=null) {

		if (!is_numeric($status)) {
			$status = 404;
		}

		$Response = new Response;
		$Response->setStatusCode($status, null);
		$Response
			->setSuccess(false)
			->setResult($result)
			->setMeta($metaOrCnt);

		if (!is_null($errors)) {
			$Response->addErrors($errors);
		}

		return $Response;

	}

}
