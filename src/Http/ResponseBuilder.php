<?php

namespace tomi20v\phalswag\Http;

/**
 * Class ResponseBuilder - build REST responses. Note that all these responses are rest responses, the http* ones
 * 		map to the proper rest structure
 *
 * @package tomi20v\phalswag
 */
class ResponseBuilder {

	/**
	 * @param array|int $metaOrCnt
	 * @return array
	 */
	protected function _transformMetaOrCnt($metaOrCnt) {
		if (is_numeric($metaOrCnt) && (intval($metaOrCnt) == $metaOrCnt)) {
			$ret = ['cnt' => $metaOrCnt];
		}
		else {
			$ret = $metaOrCnt;
		}
		return $ret;
	}

	public function build($success, $dataOrErrors, $metaOrCnt=null) {
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
			->setMeta($this->_transformMetaOrCnt($metaOrCnt));
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
			->setMeta($this->_transformMetaOrCnt($metaOrCnt));

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
			->setMeta($this->_transformMetaOrCnt($metaOrCnt));
	}

	public function buildHttp404($errors=null, $result=null) {
		return $this->buildError(404, $errors, $result);
	}

	public function buildHttp500() {
		return $this->buildError(500, ['_'=>'internal']);
	}

}
