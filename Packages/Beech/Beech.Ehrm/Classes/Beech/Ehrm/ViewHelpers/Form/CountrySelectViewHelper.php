<?php
namespace Beech\Ehrm\ViewHelpers\Form;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 13-02-13 13:19
 * All code (c) Beech Applications B.V. all rights reserved
 */

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
		}
		return ($this->value === $value) ? TRUE : $this->isDefault($value);
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