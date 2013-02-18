<?php
namespace Beech\CLA\Factory;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-02-13 09:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Form\Core\Model\FormDefinition;

class ContractFormFactory extends \TYPO3\Form\Factory\AbstractFormFactory {

	/**
	 * @var \Beech\Ehrm\Utility\Domain\ModelInterpreterUtility
	 * @Flow\Inject
	 */
	protected $modelInterpreter;

	/**
	 * @param array $factorySpecificConfiguration
	 * @param string $presetName
	 * @return \TYPO3\Form\Core\Model\FormDefinition
	 */
	public function build(array $factorySpecificConfiguration, $presetName) {
		$formConfiguration = $this->getPresetConfiguration($presetName);
		$form = new FormDefinition('contractCreator', $formConfiguration);

		$initialStep = $form->createPage('initialStep');

		$employeeField = $initialStep->createElement('employee', 'Beech.Party:EmployeeSelect');

		$contractTemplateField = $initialStep->createElement('contractTemplate', 'Beech.CLA:ContractTemplateSelect');

		$jobDescriptionField = $initialStep->createElement('jobDescription', 'Beech.CLA:JobDescriptionSelect');

		$articlesStep = $form->createPage('articlesStep');

		$articlesSection = $articlesStep->createElement('articles', 'Beech.CLA:ContractArticlesSection');

		$databaseFinisher = new \Beech\Ehrm\Finishers\DatabaseFinisher();
		$databaseFinisher->setOptions(
			array('model' => 'Contract', 'package' => 'Beech.CLA')
		);
		$form->addFinisher($databaseFinisher);

		$redirectFinisher = new \TYPO3\Form\Finishers\RedirectFinisher();
		$redirectFinisher->setOptions(
			array('action' => 'list')
		);
		$form->addFinisher($redirectFinisher);
		return $form;
	}
}
