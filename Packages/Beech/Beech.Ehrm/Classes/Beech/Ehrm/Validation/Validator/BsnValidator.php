<?php
namespace Beech\Ehrm\Validation\Validator;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

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