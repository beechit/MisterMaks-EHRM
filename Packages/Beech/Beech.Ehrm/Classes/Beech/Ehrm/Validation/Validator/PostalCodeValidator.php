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
class PostalCodeValidator extends AbstractValidator {

	/**
	 * @var array
	 */
	protected $supportedOptions = array(
		'country' => array('NL', 'Country of postal code', 'string')
	);

	/**
	 * Checks if the given value matches the specified regular expression.
	 *
	 * @param mixed $value The value that should be validated
	 * @return void
	 * @throws \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException
	 */
	protected function isValid($value) {
		$options = $this->getOptions();
		$validationRules = $this->validationRules($options['country']);
		$result = preg_match($validationRules['regularExpression'], $value);
		if ($result === 0) {
			$this->addError('Format of your postal code is not correct. There is <b>%1$s</b> and should be like <b>%2$s</b>', 1298273089, array($value, $validationRules['expected']));
		}
		if ($result === FALSE) {
			throw new \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException('regularExpression "' . $validationRules['regularExpression'] . '"PostalCodeValidator contained an error.', 1298273089);
		}
	}

	/**
	 * Get array with rules for validation depends on country
	 *
	 * TODO: Probably these rules should be read from config/yaml file
	 * @param $country
	 * @return array
	 */
	private function validationRules($country) {
		$rules = array();
		switch ($country) {
			case 'NL':
				$rules['regularExpression'] = '/^[1-9]{1}[0-9]{3}[ ][A-Z]{2}/';
				$rules['expected'] = '9999 AA';
				break;
			case 'PL':
				$rules['regularExpression'] = '/^[0-9]{2}[-][0-9]{3}/';
				$rules['expected'] = '99-999';
				break;
			default:
				$rules['regularExpression'] = '/^[0-9A-Z]{1,10}/';
				$rules['expected'] = '???';
		}

		return $rules;
	}

}

?>