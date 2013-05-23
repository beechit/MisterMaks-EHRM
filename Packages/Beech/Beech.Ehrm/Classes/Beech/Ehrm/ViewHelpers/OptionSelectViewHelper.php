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
			$this->arguments['options'] = array($this->arguments['placeholder']);
		}
		list($packageKey, $model) = explode(':', $this->arguments['model']);
		$property = $this->arguments['property'];
		$modelsConfigurations = $this->configurationManager->getConfiguration('Models');
		if (isset($modelsConfigurations[$packageKey.'.Domain.Model.'.$model])) {
			$modelConfiguration = $modelsConfigurations[$packageKey.'.Domain.Model.'.$model];
			$propertyOptions = \TYPO3\Flow\Utility\Arrays::getValueByPath($modelConfiguration, 'properties.'.$property.'.options.values');
			if ($propertyOptions !== NULL) {
				$this->arguments['options'] = array_merge($this->arguments['options'], $propertyOptions);
				return parent::render();
			}
		}
	}

}