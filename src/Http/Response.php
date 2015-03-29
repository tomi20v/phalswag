<?php

namespace tomi20v\phalswag\Http;

/**
 * Class Response
 *
 * @package tomi20v\phalswag
 */
class Response extends \Phalcon\Http\Response {

	protected $_content = [];

	protected $_rawContent = ['success'=>false, 'result'=>null];

	protected function _refreshContent() {
		$this->_content = json_encode($this->_rawContent);
		return $this;
	}

	public function setSuccess($success) {
		$this->_rawContent['success'] = $success ? true : false;
		return $this->_refreshContent();
	}

	public function addErrors($errors) {
		if (!isset($this->_rawContent['errors'])) {
			$this->_rawContent['errors'] = [];
		}
		foreach ($errors as $eachField=>$eachError) {
			if (!isset($this->_rawContent['errors'][$eachField])) {
				$this->_rawContent['errors'][$eachField] = [];
			}
			if (is_string($eachError)) {
				$this->_rawContent['errors'][$eachField][] = $eachError;
			}
			elseif (is_array($eachError)) {
				foreach ($eachError as $eachErrorMessage) {
					$this->_rawContent['errors'][$eachField][] = $eachErrorMessage;
				}
			}
		}
		return $this->_refreshContent();
	}

	public function addError($field, $error) {
		return $this->addErrors([ $field => $error ]);
	}

	public function setMeta($meta) {
		if (!isset($this->_rawContent['meta'])) {
			$this->_rawContent['meta'] = [];
		}
		foreach ($meta as $eachKey=>$eachmeta) {
			$this->_rawContent['meta'][$eachKey] = $eachmeta;
		}
		return $this->_refreshContent();
	}

	protected function _setMeta($key, $val) {
		if (!isset($this->_rawContent['meta'])) {
			$this->_rawContent['meta'] = [];
		}
		$this->_rawContent['meta'][$key] = $val;
		return $this->_refreshContent();
	}

	public function setAllCnt($allCnt) {
		return $this->_setMeta('allCnt', (int)$allCnt);
	}

	public function setFoundCnt($foundCnt) {
		return $this->_setMeta('foundCnt', (int)$foundCnt);
	}

	public function setCnt($cnt) {
		return $this->_setMeta('cnt', (int)$cnt);
	}

	public function setMetaSchema($model, $data) {
		if (!isset($this->_rawContent['meta'])) {
			$this->_rawContent['meta'] = [];
		}
		if (!isset($this->_rawContent['meta']['schema'])) {
			$this->_rawContent['meta']['schema'] = [];
		}
		$this->_rawContent['meta']['schema'][$model] = (array)$data;
		return $this->_refreshContent();
	}

}
