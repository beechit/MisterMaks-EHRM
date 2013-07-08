<?php
namespace Beech\Ehrm\Tests\Unit\Validation\Validator;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 15/7/13 2:23 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

/**
 * Testcase for the Abstract Validator
 *
 */
abstract class AbstractValidatorTestcase extends \TYPO3\Flow\Tests\UnitTestCase {

	protected $validatorClassName;

	/**
	 *
	 * @var \TYPO3\Flow\Validation\Validator\ValidatorInterface
	 */
	protected $validator;

	public function setUp() {
		$this->validator = $this->getValidator();
	}

	protected function getValidator($options = array()) {
		return $this->getAccessibleMock($this->validatorClassName, array('dummy'), array($options), '', TRUE);
	}

	protected function validatorOptions($options) {
		$this->validator = $this->getValidator($options);
	}
}

?>