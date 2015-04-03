<?php
namespace Beech\Ehrm\ViewHelpers\Form;

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

/**
 * This view helper generates a <select> dropdown list for the use with a form.
 *
 * = Basic usage =
 *
 * Usage is the same like in standard SelectViewHelper from TYPO3.Fluid package
 *
 * Example
 * <code title="Options with optgroup">
 * <f:form.select name="users" options="{documentTypes}"  />
 * </code>
 *
 * Only difference is when passed options variable is array of arrays like:
 * array(
 * 	'categoryOne' => array(
 * 		'typeOne' => [object],
 *		'typeTwo' => [object]),
 * 	'categoryTwo' => array(
 * 		'typeThree' => [object],
 * 		'typeFour' => [object],
 * 		...)
 * )
 * Then, key of array is use as a label of <optgroup>
 * and values of array are <option> inside <optgroup>
 *
 */
class SelectViewHelper extends \TYPO3\Fluid\ViewHelpers\Form\SelectViewHelper {

	/**
	 * Render the option tags.
	 *
	 * @param array $options the options for the form.
	 * @return string rendered tags.
	 */
	protected function renderOptionTags($options, $prependOptionValue = TRUE) {
		$output = '';
		if ($this->hasArgument('prependOptionLabel') && $prependOptionValue) {
			$value = $this->hasArgument('prependOptionValue') ? $this->arguments['prependOptionValue'] : '';
			$label = $this->arguments['prependOptionLabel'];
			$output .= $this->renderOptionTag($value, $label, FALSE) . chr(10);
		} elseif (empty($options)) {
			$options = array('' => '');
		}

		foreach ($options as $value => $option) {
			$children = array();
			if (is_array($option)) {
				$children = $option;
			}
			if(!empty($children)) {

				$output .= $this->renderOptionGroupTag($value, $children);
			} else {
				$output .= $this->renderOptionTag($value, (!empty($option) ? $this->getLabel($option): $option), $option) . chr(10);
			}
		}
		return $output;
	}

	/**
	 * Get label of option based on type of option param
	 *
	 * @param mixed $option
	 * @return string
	 * @throws \TYPO3\Fluid\Core\ViewHelper\Exception
	 */
	protected function getLabel($option) {
		$label = '';
		if (empty($option)) {
			return '';
		}
		if ($this->hasArgument('optionLabelField')) {
			$label = \TYPO3\Flow\Reflection\ObjectAccess::getProperty($option, $this->arguments['optionLabelField']);
			if (is_object($label)) {
				if (method_exists($label, '__toString')) {
					$label = (string)$label;
				} else {
					throw new \TYPO3\Fluid\Core\ViewHelper\Exception('Label value for object of class "' . get_class($label) . '" was an object without a __toString() method.' , 1247827553);
				}
			}
		} elseif (method_exists($option, '__toString')) {
			$label = (string)$option;
		} elseif ($this->persistenceManager->getIdentifierByObject($option) !== NULL) {
			$label = $this->persistenceManager->getIdentifierByObject($option);
		}
		return $label;
	}

	/**
	 * Render optgroup tag based with label and options
	 *
	 * @param $label
	 * @param $options
	 * @return string
	 */
	protected function renderOptionGroupTag($label, $options) {
		return '<optgroup label="' . htmlspecialchars($label) . '">' . $this->renderOptionTags($options, FALSE) . '</optgroup>';
	}

	/**
	 * Render one option tag
	 *
	 * @param string $value value attribute of the option tag (will be escaped)
	 * @param string $label content of the option tag (will be escaped)
	 * @param mixed $option
	 * @return string the rendered option tag
	 */
	protected function renderOptionTag($value, $label, $option = NULL) {
		$output = '<option value="' . htmlspecialchars($value) . '"';
		if ($this->isSelected($value)) {
			$output .= ' selected="selected"';
		}

		if ($this->hasArgument('translate')) {
			$label = $this->getTranslatedLabel($value, $label);
		}



		if ($option instanceof \Beech\Document\Domain\Model\DocumentType) {
			if ($option->getExpiration() != '') {
				$output.= ' expiration="'.$option->getExpiration().'"';
			}

			if ($option->getNumber() != '') {
				$output.= ' number="'.$option->getNumber().'"';
			}

			if ($option->getPeriod() != '') {
				$output.= ' period="'.$option->getPeriod().'"';
			}

			if ($option->getYear() != '') {
				$output.= ' year="'.$option->getYear().'"';
			}
		}

		$output .= '>' . htmlspecialchars($label) . '</option>';

		return $output;
	}
}

?>