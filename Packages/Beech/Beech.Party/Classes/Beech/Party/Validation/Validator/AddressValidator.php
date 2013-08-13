<?php
namespace Beech\Party\Validation\Validator;

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
class AddressValidator extends AbstractValidator {

	/**
	 * Checks if the given address is correct
	 *
	 * @param mixed $value The value that should be validated
	 * @return void
	 * @throws \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException
	 */
	protected function isValid($value) {
		$postalCodeValidator = new \Beech\Ehrm\Validation\Validator\PostalCodeValidator(array(
			'country' => $value->getCountry()
		));
		$result = $postalCodeValidator->validate($value->getPostal());
		if ($result->hasErrors() === TRUE) {
			foreach ($result->getErrors() as $error) {
				$this->result->addError($error);
			}
		}
	}

}

?>