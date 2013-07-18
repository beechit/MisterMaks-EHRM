<?php
namespace Beech\Ehrm\Validation\Validator;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 7/18/13 11:38 AM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\Flow\Utility\TypeHandling;
use TYPO3\Flow\Validation\Validator\AbstractValidator;

/**
 *
 */
class PostBoxNumberValidator extends AbstractValidator {

	/**
	 * @var string
	 */
	protected $regularExpression = '/^[0-9]{1,5}/';

	/**
	 * @var string
	 */
	protected $expected = '10 or 1234 of 12345';

	/**
	 * Checks if the given value matches the specified regular expression.
	 *
	 * @param mixed $value The value that should be validated
	 * @return void
	 * @throws \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException
	 */
	protected function isValid($value) {
		$result = preg_match($this->regularExpression, $value);
		if ($result === 0) {
			$this->addError('Format of your postbox number is not correct. There is <b>%1$s</b> and should be like <b>%2$s for example</b>', 1374142865, array($value, $this->expected));
		}
		if ($result === FALSE) {
			throw new \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException('regularExpression "' . $this->regularExpression . '"PostBoxNumberValidator contained an error.', 1374142865);
		}
	}

}

?>