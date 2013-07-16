<?php
namespace Beech\Ehrm\Validation\Validator;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 7/6/13 2:23 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\Flow\Utility\TypeHandling;
use TYPO3\Flow\Validation\Validator\AbstractValidator;

/**
 *
 */
class DecimalValidator extends AbstractValidator {

	/**
	 * @var array
	 */
	protected $supportedOptions = array(
		'digits' => array(NULL, 'Total, maximal number of digits', 'string'),
		'decimal' => array(NULL, 'Number of digits after comma', 'string')
	);

	/**
	 * @var string
	 */
	protected $regularExpression = '/^[0-9]{1,%d}([,\.][0-9]{0,%d}){0,1}$/';

	/**
	 * @var string
	 */
	protected $expected = '1000,00';

	/**
	 * Checks if the given value matches the specified regular expression.
	 *
	 * @param mixed $value The value that should be validated
	 * @return void
	 * @throws \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException
	 */
	protected function isValid($value) {
		$options = $this->getOptions();

		$digits = isset($options['digits']) ? $options['digits'] : 6;
		$decimal = isset($options['decimal']) ? $options['decimal'] : 2;

		$result = preg_match(sprintf($this->regularExpression, $digits - $decimal, $decimal), $value);

		if ($result === 0) {
			$this->addError('Format of input was not correct. There is <b>%1$s</b> and should be <b>%2$s</b>', 1373962725, array($value, $this->expected));
		}
		if ($result === FALSE) {
			throw new \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException('regularExpression "' . $this->regularExpression . 'DecimalValidator contained an error.', 1298273089);
		}
	}
}

?>