<?php
namespace Beech\CLA\Factory;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-02-13 09:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Form\Core\Model\FormDefinition;

/**
 * Contract form factory
 */
class ContractFormFactory extends \TYPO3\Form\Factory\AbstractFormFactory {

	/**
	 * @var \Beech\Ehrm\Utility\Domain\ModelInterpreterUtility
	 * @Flow\Inject
	 */
	protected $modelInterpreterUtility;

	/**
	 * @var \Beech\Ehrm\Form\Helper\FieldDefaultValueHelper
	 * @Flow\Inject
	 */
	protected $fieldDefaultValueHelper;

	/**
	 * @var \TYPO3\Form\Core\Model\FormDefinition
	 */
	protected $form;

	/**
	 * Contains setting's values passed to form
	 *
	 * @var array
	 */
	protected $factorySpecificConfiguration;

	/**
	 * Initial page index
	 *
	 * @var integer
	 */
	protected $pageIndex = 1;

	/**
	 * Number of fields per page
	 *
	 * @var integer
	 */
	protected $fieldsPerPage;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @param array $settings
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * @param array $factorySpecificConfiguration
	 * @param string $presetName
	 * @return void
	 */
	protected function init($factorySpecificConfiguration, $presetName) {
		$this->factorySpecificConfiguration = $factorySpecificConfiguration;
		$formConfiguration = $this->getPresetConfiguration($presetName);
		$this->form = new FormDefinition('contractCreator', $formConfiguration);
		$this->fieldsPerPage = $this->settings['contractArticlesPerPage'];
	}

	/**
	 * @param array $factorySpecificConfiguration
	 * @param string $presetName
	 * @return \TYPO3\Form\Core\Model\FormDefinition
	 */
	public function build(array $factorySpecificConfiguration, $presetName) {
		$this->init($factorySpecificConfiguration, $presetName);
		if (!isset($this->factorySpecificConfiguration['contractTemplate'])) {
			$selectTemplateStep = $this->form->createPage('initialStep');

			$selectTemplateStep->createElement('contractTemplate', 'Beech.CLA:ContractTemplateSelect');
				// Employee field
			$selectTemplateStep->createElement('employee', 'Beech.Party:EmployeeSelect');
				// Job description field
			$selectTemplateStep->createElement('jobDescription', 'Beech.CLA:JobDescriptionSelect');
			$redirectFinisher = new \Beech\CLA\Finishers\RedirectToTemplateFinisher();
			$redirectFinisher->setOptions(
				array(
					'action' => 'start',
					'controller' => 'Contract',
					'package' => 'Beech.CLA\\Administration'
				)
			);
			$this->form->addFinisher($redirectFinisher);
		} else {
			$page = $this->form->createPage('page0');

				// add model properties to wizard (for preview and database finisher)
				// todo: should be objects and not model identifiers
			$contractProperties = $this->modelInterpreterUtility->getModelProperties('Beech.CLA', 'Contract');
			foreach ($contractProperties as $propertyName => $property) {
				$contractField = $page->createElement($propertyName, 'TYPO3.Form:HiddenField');
				$contractField->setLabel(isset($property['label']) ? $property['label'] : $propertyName );
				if (isset($this->factorySpecificConfiguration[$propertyName])) {
					$defaultValue = $this->factorySpecificConfiguration[$propertyName];
				} else {
					$defaultValue = $this->fieldDefaultValueHelper->getDefault($property);
				}
				$contractField->setDefaultValue($defaultValue);
			}

			while ($this->nextArticlesPage($page)) {
				$this->pageIndex++;
				$page = NULL;
			}

			$this->form->createPage('previewPage', 'Beech.CLA:ContractPreviewPage');
			$databaseFinisher = new \Beech\Ehrm\Form\Finishers\DatabaseFinisher();
			$databaseFinisher->setOptions(
				array(
					'model' => 'Contract',
					'package' => 'Beech.CLA'
				)
			);
			$this->form->addFinisher($databaseFinisher);

			$summaryFinisher = new \Beech\Ehrm\Form\Finishers\ModalCloseConfirmationFinisher();
			$summaryFinisher->setOption('templatePathAndFilename', 'resource://Beech.CLA/Private/Templates/Administration/Contract/Summary.html');
			$this->form->addFinisher($summaryFinisher);

		}
		return $this->form;
	}

	/**
	 * Create next page
	 * Return FALSE when there is no elements on page
	 *
	 * @return boolean
	 */
	protected function nextArticlesPage($articlesPage = NULL) {
		if ($articlesPage === NULL) {
			$articlesPage = $this->form->createPage('page' . $this->pageIndex);
		}
		$articlesSection = $articlesPage->createElement('articles' . $this->pageIndex, 'Beech.CLA:ContractArticlesSection');
		$articlesSection->setContractTemplate($this->factorySpecificConfiguration['contractTemplate']);
		$contractArticles = $articlesSection->getContractArticles(($this->pageIndex - 1) * $this->fieldsPerPage, $this->fieldsPerPage);
		$articlesSection->initializeFormElement();
		if (count($contractArticles) > 0) {
			return TRUE;
		} else {
			$this->form->removePage($articlesPage);
			return FALSE;
		}
	}
}

?>