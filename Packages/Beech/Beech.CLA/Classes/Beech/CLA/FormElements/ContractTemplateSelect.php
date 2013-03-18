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
	 * @var \Beech\CLA\Domain\Repository\ContractTemplateRepository
	 * @Flow\Inject
	 */
	protected $contractTemplateRepository;

	/**
	 * Initialize form element
	 * @return void
	 */
	public function initializeFormElement() {
		$contractTemplates = $this->contractTemplateRepository->findAll();
		$this->setLabel('Contract template');
		$options = array();
		foreach ($contractTemplates as $contractTemplate) {
			$templateName = $contractTemplate->getTemplateName();
			if (!empty($templateName)) {
				$options[$contractTemplate->getTemplateName()] = $templateName;
			}
		}
		$this->setProperty('options', $options);
	}

}
?>