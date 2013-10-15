<?php
namespace Beech\Ehrm\ViewHelpers\Form;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 13-06-13 13:19
 * All code (c) Beech Applications B.V. all rights reserved
 */

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
	protected function renderOptionTags($options) {
		$output = '';
		if ($this->hasArgument('prependOptionLabel')) {
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
				$output .= $this->renderOptionTag($value, $this->getLabel($option), $option) . chr(10);
			}
		}
		return $output;
	}

	/**
	 * Get label of option based on type of option param
	 *
	 * @param $option
	 * @return string
	 * @throws \TYPO3\Fluid\Core\ViewHelper\Exception
	 */
	protected function getLabel($option) {
		$label = '';
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
		return '<optgroup label="' . htmlspecialchars($label) . '">' . $this->renderOptionTags($options) . '</optgroup>';
	}

	/**
	 * Render one option tag
	 *
	 * @param string $value value attribute of the option tag (will be escaped)
	 * @param string $label content of the option tag (will be escaped)
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
		$output .= '>' . htmlspecialchars($label) . '</option>';

		return $output;
	}
}

?>