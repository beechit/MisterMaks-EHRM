<?php
namespace Beech\CLA\FormElements;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-02-13 09:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Contract template form element
 */
class ContractTemplateSelect extends \TYPO3\Form\Core\Model\AbstractFormElement {

	/**
	 * TODO: make this value configurable
	 * @var string
	 */
	protected $templateFolder = 'resource://Beech.Ehrm.Glastuinbouw/Private/Data/Contract/';

	/**
	 * Load template files based on templatesLocation
	 * @return array
	 */
	private function loadTemplates() {
		return \TYPO3\Flow\Utility\Files::readDirectoryRecursively($this->templateFolder, 'yaml');
	}

	/**
	 * Get name of template
	 * @param $template
	 * @return string
	 */
	private function getTemplateName($template) {
		$templateConfiguration = \Symfony\Component\Yaml\Yaml::parse($template);
		return (string)\TYPO3\Flow\Utility\Arrays::getValueByPath($templateConfiguration, 'contractTemplate.contractTemplateName');
	}

	/**
	 * Initialize form element
	 * @return void
	 */
	public function initializeFormElement() {
		$contractTemplatesArray = $this->loadTemplates();
		$this->setLabel('Contract template');
		foreach ($contractTemplatesArray as $key => $contractTemplate) {
			$templateName = $this->getTemplateName($contractTemplate);
			if (!empty($templateName)) {
				$contractTemplatesArray[$key] = $templateName;
			}
		}
		$this->setProperty('options', $contractTemplatesArray);
	}

}
?>