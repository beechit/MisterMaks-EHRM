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
 *
 */
class CountrySelectViewHelper extends \TYPO3\Fluid\ViewHelpers\Form\SelectViewHelper {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Form\Factory\ArrayFormFactory
	 */
	protected $formFactory;

	/**
	 * @var string
	 */
	protected $value;

	/**
	 * set hard default value for country.
	 */
	protected $defaultOption = 'NL';

	// todo: this should be fetched from the yaml model later. issue MM-443

	/**
	 * Initialize arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->overrideArgument('options', 'array', 'Associative array with internal IDs as key, and the values are displayed in the select box');
		$this->registerArgument('placeholder', 'string', 'The placeholder of the select field');
	}

	/**
	 * Renders the text field, hidden field and required javascript
	 *
	 * @return string
	 */
	public function render() {
		if (isset($this->arguments['placeholder'])) {
			$this->tag->addAttribute('data-placeholder', $this->arguments['placeholder']);
		}
		$type = "Beech.Ehrm:CountrySelect";
		$presetConfiguration = $this->formFactory->getPresetConfiguration('wizard');

		if (isset($presetConfiguration['formElementTypes'][$type])) {
			$formElementConfiguration = $presetConfiguration['formElementTypes'][$type];
			$formElementClass = $formElementConfiguration['implementationClassName'];

			$element = new $formElementClass($this->arguments['property'], $type);
			$element->initializeFormElement();
			$properties = $element->getProperties();
			$this->arguments['options'] = array_merge(array('' => ''), $properties['options']);
		}
		$content = '';
		$content .= '<div class="input-prepend">';
		$content .= '	<span class="add-on country"><i style="background-position: -220px -195px;"></i></span>';
		$content .= parent::render();
		$content .= '</div>';
		return $content;
	}

	/**
	 * Check if a value is the selected one
	 *
	 * @param string $value Value to check for
	 * @return boolean TRUE if the value should be marked as selected; FALSE otherwise
	 */
	protected function isSelected($value) {
		if($this->value === NULL) {
			$this->value = $this->getValue();
			if($this->value === NULL) {
				$this->value = '';
			}
		};
		if ($this->value === '') {
			return $this->isDefault($value);
		} else {
			return ($this->value === $value);
		}
	}

	/**
	 * Render the option tags.
	 *
	 * @param mixed $value Value to check for
	 * @return boolean TRUE if the value should be marked a s selected; FALSE otherwise
	 */
	protected function isDefault($value) {
		if ($value === $this->defaultOption)
			return TRUE;
		else
			return FALSE;
	}
}