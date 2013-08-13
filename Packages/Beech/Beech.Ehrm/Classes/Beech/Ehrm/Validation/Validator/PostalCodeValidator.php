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