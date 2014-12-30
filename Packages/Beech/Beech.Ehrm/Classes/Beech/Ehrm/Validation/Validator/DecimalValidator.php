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
			$this->addError('Format of input was not correct. There is <b>%1$s</b> and should be like <b>%2$s</b>', 1373962725, array($value, $this->expected));
		}
		if ($result === FALSE) {
			throw new \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException('regularExpression "' . $this->regularExpression . 'DecimalValidator contained an error.', 1298273089);
		}
	}
}

?>