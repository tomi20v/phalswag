<?php

namespace tomi20v\phalswag\Http;

/**
 * Class ResponseBuilder
 *
 * @package tomi20v\phalswag
 */
class ResponseBuilder {

	public function build($success, $dataOrErrors, $metaOrCnt=null) {
		if (is_numeric($metaOrCnt) && (intval($metaOrCnt) == $metaOrCnt)) {
			$metaOrCnt = ['cnt' => $metaOrCnt];
		}
		die('HOO');
		$Response = $success
			? $this->buildSuccess($dataOrErrors, $metaOrCnt)
			: $this->buildError(null, $dataOrErrors, null, $metaOrCnt);
		return $Response;
	}

	public function buildSuccess($result, $metaOrCnt=null) {
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

	public function buildSimpleError($status, $field) {
		return $this->buildError($status, [$field=>[$status]]);
	}

	public function buildHttp200($result, $metaOrCnt) {
		return $this->buildSuccess($result, $metaOrCnt);
	}

	public function buildHttp201($newUrls, $metaOrCnt) {
		$Response = $this->buildSuccess($newUrls, $metaOrCnt);
		$Response
			->setStatusCode(201, null)
			->setHeader('Location', reset($newUrls));
		return $Response;
	}

	public function buildHttp400($errors=null, $result=null, $metaOrCnt=null) {
		return $this->buildError(400, $errors, $result, $metaOrCnt);
	}

	public function buildHttp401($result=null, $metaOrCnt=null) {
		$Response = new Response;
		$Response
			->setStatusCode(401, null)
			->setResult($result)
			->setMeta($metaOrCnt);
	}

	public function buildHttp404($errors=null, $result=null) {
		return $this->buildError(404, $errors, $result);
	}

	public function buildHttp500() {
		return $this->buildError(500, ['_'=>'internal']);
	}

}
