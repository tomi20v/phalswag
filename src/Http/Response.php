<?php

namespace tomi20v\phalswag\Http;

use Phalcon\Http\jsonOptions;

/**
 * Class Response
 *
 * @package tomi20v\phalswag
 */
class Response extends \Phalcon\Http\Response {

	protected $_content = [];

	protected $_rawContent = ['success'=>false, 'result'=>null];

	/**
	 * @return mixed|null
	 */
	public function getSuccess() {
		return $this->_rawContent['success'];
	}

	/**
	 * @param bool $success
	 * @return $this
	 */
	public function setSuccess($success) {
		$this->_rawContent['success'] = $success ? true : false;
		return $this->_refreshContent();
	}

	/**
	 * @param array $errors
	 * @return $this
	 */
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

	/**
	 * @param string $field
	 * @param string $error
	 * @return $this
	 */
	public function addError($field, $error) {
		return $this->addErrors([ $field => $error ]);
	}

	/**
	 * @return array
	 */
	public function getErrors() {
		return isset($this->_rawContent['errors']) ? $this->_rawContent['errors'] : null;
	}

	/**
	 * @param mixed $result
	 * @return $this
	 */
	public function setResult($result) {
		$this->_rawContent['result'] = $result;
		return $this->_refreshContent();
	}

	/**
	 * @param array $meta
	 * @return $this
	 */
	public function setMeta($meta) {
		if (is_null($meta)) {
			unset($this->_rawContent['meta']);
		}
		else {
			if (!array_key_exists('meta', $this->_rawContent)) {
				$this->_rawContent['meta'] = [];
			}
			foreach ($meta as $eachKey=>$eachmeta) {
				$this->_rawContent['meta'][$eachKey] = $eachmeta;
			}
		}
		return $this->_refreshContent();
	}

	/**
	 * @param int $allCnt number of all items
	 * @return $this
	 */
	public function setAllCnt($allCnt) {
		return $this->_setMeta('allCnt', (int)$allCnt);
	}

	/**
	 * @param int $foundCnt number of found item if they were searched/filtered
	 * @return $this
	 */
	public function setFoundCnt($foundCnt) {
		return $this->_setMeta('foundCnt', (int)$foundCnt);
	}

	/**
	 * @param $cnt
	 * @return $this
	 */
	public function setCnt($cnt) {
		return $this->_setMeta('cnt', (int)$cnt);
	}

//	/**
//	 * @param key $model
//	 * @param array $data
//	 * @return $this
//	 */
//	public function setMetaSchema($model, $data) {
//		if (!isset($this->_rawContent['meta'])) {
//			$this->_rawContent['meta'] = [];
//		}
//		if (!isset($this->_rawContent['meta']['schema'])) {
//			$this->_rawContent['meta']['schema'] = [];
//		}
//		$this->_rawContent['meta']['schema'][$model] = (array)$data;
//		return $this->_refreshContent();
//	}

	/**
	 * @return $this
	 */
	private function _refreshContent() {
		$this->_content = json_encode($this->_rawContent);
		return $this;
	}

	/**
	 * @param string $key
	 * @param mixed $val
	 * @return $this
	 */
	private function _setMeta($key, $val) {
		if (!isset($this->_rawContent['meta'])) {
			$this->_rawContent['meta'] = [];
		}
		$this->_rawContent['meta'][$key] = $val;
		return $this->_refreshContent();
	}

}
