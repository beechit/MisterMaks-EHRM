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
class IbanValidator extends AbstractValidator {

	/**
	 * @var string
	 */
	protected $regularExpression = '/^[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}([a-zA-Z0-9]?){0,16}/';

	/**
	 * @var string
	 */
	protected $expected = 'NL91ABNA0417164300';

	/**
	 * Checks if the given value matches the specified regular expression.
	 *
	 * @param mixed $value The value that should be validated
	 * @return void
	 * @throws \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException
	 */
	protected function isValid($value) {
		$bankAccountType = $value->getBankAccountType();
		if ($bankAccountType === 'bank') {
			$result = preg_match($this->regularExpression, $value->getAccountNumber());
			if ($result === 0) {
				$this->addError('Format of your Iban number is not correct. There is <b>%1$s</b> and should be <b>%2$s</b> for example', 1374140660, array($value->getAccountNumber(), $this->expected));
			}
			if ($result === FALSE) {
				throw new \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException('regularExpression "' . $this->regularExpression . '"IbanNumberValidator contained an error.', 1374140660);
			}
		}

	}

}

?>