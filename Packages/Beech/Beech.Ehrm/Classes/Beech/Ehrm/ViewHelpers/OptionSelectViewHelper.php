<?php
namespace Beech\Ehrm\ViewHelpers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 13-02-13 13:19
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 *
 */
class OptionSelectViewHelper extends \TYPO3\Fluid\ViewHelpers\Form\SelectViewHelper {

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * Store default option if its set in yaml
	 */
	protected $defaultOption;

	/**
	 * Initialize arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->overrideArgument('options', 'array', 'Associative array with internal IDs as key, and the values are displayed in the select box');
		$this->registerArgument('model', 'string', 'Package and model name, separated by :, ex. Beech.Ehrm:Log');
		$this->registerArgument('placeholder', 'string', 'The placeholder of the select field');
	}

	/**
	 * Renders the select field, with options from yaml
	 *
	 * @return string
	 */
	public function render() {
		if (isset($this->arguments['placeholder'])) {
			$this->tag->addAttribute('data-placeholder', $this->arguments['placeholder']);
		}
		list($packageKey, $model) = explode(':', $this->arguments['model']);
		$property = $this->arguments['property'];
		$modelsConfigurations = $this->configurationManager->getConfiguration('Models');
		if (isset($modelsConfigurations[$packageKey.'.Domain.Model.'.$model])) {
			$modelConfiguration = $modelsConfigurations[$packageKey.'.Domain.Model.'.$model];
			$propertyOptions = \TYPO3\Flow\Utility\Arrays::getValueByPath($modelConfiguration, 'properties.'.$property.'.options.values');
			$this->defaultOption = \TYPO3\Flow\Utility\Arrays::getValueByPath($modelConfiguration, 'properties.'.$property.'.default');
			if ($propertyOptions !== NULL) {
				$propertyOptionsValues = array();
				foreach($propertyOptions as $key => $value) {
					$propertyOptionsValues[$key] = $value;
				}
					//Add empty value to support placeholder in chosen select plugin
				$this->arguments['options'] = array_merge(array('' => ''), $propertyOptionsValues);
				return parent::render();
			}
		}

	}

	/**
	 * Render one option tag
	 *
	 * @param string $value value attribute of the option tag (will be escaped)
	 * @param string $label content of the option tag (will be escaped)
	 * @return string the rendered option tag
	 */
	protected function renderOptionTag($value, $label) {
		$output = '<option value="' . htmlspecialchars($value) . '"';
		if ($this->isSelected($value) || $this->isDefault($value)) {
			$output .= ' selected="selected"';
		}

		if ($this->hasArgument('translate')) {
			$label = $this->getTranslatedLabel($value, $label);
		}
		$output .= '>' . htmlspecialchars($label) . '</option>';

		return $output;
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