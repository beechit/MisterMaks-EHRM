<?php
namespace Beech\Ehrm\Validation\Validator;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 6/6/13 2:23 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\Flow\Utility\TypeHandling;
use TYPO3\Flow\Validation\Validator\AbstractValidator;

/**
 *
 */
class PostalCodeValidator extends AbstractValidator {

	/**
	 * @var string
	 */
	protected $regularExpression = '/^[1-9][0-9]{3}[\s]?[A-Za-z]{2}$/i';

	/**
	 * @var string
	 */
	protected $expected = '9999 AA';

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
			$this->addError('Format of your postal code is not correct. There is <b>%1$s</b> and should be <b>%2$s</b>', 1373962750, array($value, $this->expected));
		}
		if ($result === FALSE) {
			throw new \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException('regularExpression "' . $this->regularExpression . '"PostalCodeValidator contained an error.', 1298273089);
		}
	}

}

?>