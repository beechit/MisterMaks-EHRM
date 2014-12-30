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
 * Validator for electronic addresses like: email, url, linkedIn etc
 */
class ElectronicAddressValidator extends AbstractValidator {

	/**
	 * Checks if the given value matches the specified regular expression.
	 *
	 * @param mixed $value The value that should be validated
	 * @return void
	 * @throws \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException
	 */
	protected function isValid($value) {
		$type = $value->getElectronicAddressType();
		$validationRules = $this->validationRules($type);
		$result = preg_match($validationRules['regularExpression'], $value->getAddress());
		if ($result === 0) {
			switch ($type) {
				case 'email':
					$typeLabel = 'E-mail';
					break;
				case 'url':
					$typeLabel = 'URL';
					break;
				case 'linkedin':
					$typeLabel = 'Linked In';
					break;
				default:
					$typeLabel = 'electronic address';

			}
			$this->addError('Format of your %1$s is not correct. There is <b>%2$s</b> and should be like <b>%3$s</b>', 1377002197, array($typeLabel, $value->getAddress(), $validationRules['expected']));

		}
		if ($result === FALSE) {
			throw new \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException('regularExpression "' . $validationRules['regularExpression'] . '"ElectronicAddressValidator contained an error.', 1370528826);
		}
	}

	/**
	 * Get array with rules for validation depends on type of electronic address
	 *
	 * TODO: Probably these rules should be read from config/yaml file
	 * @param $type
	 * @return array
	 */
	private function validationRules($type) {
		$rules = array();
		switch ($type) {
			case 'email':
				$rules['regularExpression'] = '/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/';
				$rules['expected'] = 'email@example.com';
				break;
			case 'url':
				$rules['regularExpression'] = '/(http:\/\/|https:\/\/|www.)([a-zA-Z0-9]+\.[a-zA-Z0-9\-]+|[a-zA-Z0-9\-]+)\.[a-zA-Z\.]{2,6}(\/[a-zA-Z0-9\.\?=\/\#%&\+-]+|\/|)/';
				$rules['expected'] = 'http://mistermaks.nl or www.mistermaks.nl';
				break;
			case 'linkedin':
				$rules['regularExpression'] = '/^[A-Za-z0-9\.\?=\/\#%&\+-]+$/';
				$rules['expected'] = '';
				break;
			default:
				$rules['regularExpression'] = '/(.*)/';
				$rules['expected'] = '';
		}

		return $rules;
	}

}

?>