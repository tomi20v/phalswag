<?php

namespace tomi20v\phalswag\Http;

interface ResponseBuilderInterface {

	public function buildSuccess($result=null, $metaOrCnt=null);

	public function buildError($status, $errors=null, $result=null, $metaOrCnt=null);

}
