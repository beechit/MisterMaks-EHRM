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
class BsnValidator extends AbstractValidator {

	/**
	 * Checks whether or not the BSN number provided passes 11 check.
	 *
	 * @param $value The bsn be checked.
	 * @return void
	 **/
	protected function isValid($value) {
		$bsn = trim($value);
		// list of numbers that pass the check but are invalid
		$invalidNumbers = array(
			'111111110',
			'999999990',
			'000000000');
		$result = TRUE;
		if (strlen($bsn) != 9 || !ctype_digit($bsn) || in_array($bsn, $invalidNumbers)) {
			$result = FALSE;
		}
		if ($result) {
			for ($i = 9, $sum = -$bsn % 10; $i > 1; $i--) {
				$sum += $i * $bsn{(9 - $i)};
			}
			$result = ($sum % 11 == 0);
		}


		if ($result === FALSE) {
			$this->addError('Format of input was not correct. Number<b>%1$s</b> is not valid BSN number.', 1374222998, array($value));
		}
	}
}
?>