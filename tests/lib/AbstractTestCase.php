<?php

/**
 * Class AbstractTestCase
 */
abstract class AbstractTestCase extends PHPUnit_Framework_TestCase {

	protected function _expectsAny(
			\PHPUnit_Framework_MockObject_MockObject $Mock,
			$method,
			$with=null,
			$willReturn = null
	) {

		$args = func_get_args();
		array_unshift($args, $this->any());

		return call_user_func_array([$this, '_expects'], $args);

	}

	/**
	 * @param \PHPUnit_Framework_MockObject_MockObject $Mock
	 * @param $method
	 * @param null $with
	 * @param null $willReturn
	 * @return $this
	 */
	protected function _expectsOnce(
		\PHPUnit_Framework_MockObject_MockObject $Mock,
		$method,
		$with=null,
		$willReturn = null
	) {

		$args = func_get_args();
		array_unshift($args, $this->once());

		return call_user_func_array([$this, '_expects'], $args);

	}

	/**
	 * @param $times
	 * @param PHPUnit_Framework_MockObject_MockObject $Mock
	 * @param $method
	 * @param null $with
	 * @param null $willReturn
	 * @return $this
	 */
	protected function _expects(
		$times,
		\PHPUnit_Framework_MockObject_MockObject $Mock,
		$method,
		$with=null,
		$willReturn = null
	)
	{
		$step = $Mock->expects($times)->method($method);
		switch (func_num_args()) {
		case 4:
			$step = $step->with($with);
			/** fallthrough */
		case 5:
			$step = $step->willReturn($willReturn);
		}
		return $this;
	}

}
