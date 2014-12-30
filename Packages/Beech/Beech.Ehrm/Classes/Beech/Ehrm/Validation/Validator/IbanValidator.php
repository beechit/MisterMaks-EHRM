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