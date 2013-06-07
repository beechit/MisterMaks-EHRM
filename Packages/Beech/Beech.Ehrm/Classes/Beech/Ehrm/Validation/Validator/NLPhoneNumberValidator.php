<?php
namespace Beech\Ehrm\Validation\Validator;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 7/6/13 2:23 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\Flow\Utility\TypeHandling;
use TYPO3\Flow\Validation\Validator\AbstractValidator;

/**
 *
 */
class NLPhoneNumberValidator extends AbstractValidator {

	/**
	 * @var string
	 */
	protected $regularExpression = '^\(?(0)[1-9]{2}\)?(\-|\s)?[0-9]{7}$|^\(?(0)[1-9]{3}\)?(\-|\s)?[0-9]{6}$';

	/**
	 * @var string
	 */
	protected $expected = '000 9999999';

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
			$this->addError('Format of your phone number is not correct. There is <b>%1$s</b> and should be <b>%2$s</b>', 1370528826, array($value, $this->expected));
		}
		if ($result === FALSE) {
			throw new \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException('regularExpression "' . $this->regularExpression . '"NLPhoneNumberValidator contained an error.', 1298273089);
		}
	}

}

?>