<?php
namespace Beech\Party\Validation\Validator;

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
class PhoneNumberValidator extends AbstractValidator {

	/**
	 * Checks if the given value matches the specified regular expression.
	 *
	 * @param mixed $value The value that should be validated
	 * @return void
	 * @throws \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException
	 */
	protected function isValid($value) {
		$type = $value->getPhoneNumberType();
		$validationRules = $this->validationRules($type);
		$result = preg_match($validationRules['regularExpression'], $value->getPhoneNumber());
		if ($result === 0) {
			$this->addError('Format of your phone number is not correct. There is <b>%1$s</b> and should be like <b>%2$s</b>', 1370528826, array($value->getPhoneNumber(), $validationRules['expected']));
		}
		if ($result === FALSE) {
			throw new \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException('regularExpression "' . $validationRules['regularExpression'] . '"PhoneNumberValidator contained an error.', 1370528826);
		}
	}

	/**
	 * Get array with rules for validation depends on type of phone number
	 *
	 * TODO: Probably these rules should be read from config/yaml file
	 * @param $type
	 * @return array
	 */
	private function validationRules($type) {
		$rules = array();
		switch ($type) {
			case 'mobileNumber':
				$rules['regularExpression'] = '/(^\+31|^\+31\(0\)|^\(\+[0-9]{2}\)\(0\)|^0031|^0)(6)([0-9]{8}$|[0-9\-\s]{9}$)/';
				$rules['expected'] = '06 54232123';
				break;
			case 'foreignNumber':
				$rules['regularExpression'] = '/([+(\d]{1})(([\d+() -.]){5,16})([+(\d]{1})/';
				$rules['expected'] = '+31 654232123';
				break;
			default:
				$rules['regularExpression'] = '/(^0[0-9]{2})([0-9]{7}$|[0-9\-\s]{8}$)/';
				$rules['expected'] = '077 3030310';
		}

		return $rules;
	}
}

?>