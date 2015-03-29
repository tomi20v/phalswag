<?php

namespace tomi20v\phalswag\Http;

/**
 * Class ResponseBuilder
 *
 * @package tomi20v\phalswag
 */
class ResponseBuilder {

	public static function buildSuccess($result, $meta=[]) {
		$Response = new Response;
		$Response
			->setSuccess(true)
			->setResult($result)
			->setMeta($meta);
		return $Response;
	}

	public static function buildError($status, $errors, $result=null, $meta=[]) {
		$Response = new Response;
		$Response->setStatusCode($status, null);
		$Response
			->setSuccess(false)
			->setResult($result)
			->addErrors($errors)
			->setMeta($meta);
		return $Response;
	}

}
