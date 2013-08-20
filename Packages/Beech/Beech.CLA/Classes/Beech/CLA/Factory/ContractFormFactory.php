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
	 * @var \Beech\Ehrm\Utility\PreferencesUtility
	 * @Flow\Inject
	 */
	protected $preferencesUtility;

	/**
	 * @var \Beech\CLA\Domain\Repository\ContractTemplateRepository
	 * @Flow\Inject
	 */
	protected $contractTemplateRepository;

	/**
	 * @var \Beech\CLA\Domain\Repository\JobDescriptionRepository
	 * @Flow\Inject
	 */
	protected $jobDescriptionRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 * @Flow\Inject
	 */
	protected $personRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\CompanyRepository
	 * @Flow\Inject
	 */
	protected $companyRepository;

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

			$contractTemplate = $this->contractTemplateRepository->findByIdentifier($this->factorySpecificConfiguration['contractTemplate']);

			$contract = new \Beech\CLA\Domain\Model\Contract();
			$contract->setContractTemplate($contractTemplate);
			$contract->setEmployee($this->personRepository->findByIdentifier($this->factorySpecificConfiguration['employee']));
			$contract->setJobDescription($this->jobDescriptionRepository->findByIdentifier($this->factorySpecificConfiguration['jobDescription']));
			$contract->setEmployer($this->companyRepository->findByIdentifier($this->preferencesUtility->getApplicationPreference('company')));

			/**
			 * @todo: flatten all Contract values (from yaml file) employee adress, jobtitle etc
			 * 		  so all values are static for contract and use these values in fluid template
			 */
			$contract->setJobTitle($contract->getJobDescription()->getJobTitle());

			while ($this->nextArticlesPage($contract, $page)) {
				++$this->pageIndex;
				$page = NULL;
			}

			/** @var $previewPage \Beech\CLA\FormElements\ContractPreviewPage */
			$previewPage = $this->form->createPage('previewPage', 'Beech.CLA:ContractPreviewPage');
			$previewPage->setLabel($contract->getContractTemplate()->getTemplateName());

			/** @var $contractHeader \Beech\CLA\FormElements\ContractHeaderSection */
			$contractHeader = $previewPage->createElement('contractHeader', 'Beech.CLA:ContractHeaderSection');
			$contractHeader->setProperty('contract', $contract);

			/** @var $contractFooter \Beech\CLA\FormElements\ContractFooterSection */
			$contractFooter = $previewPage->createElement('contractFooter', 'Beech.CLA:ContractFooterSection');
			$contractFooter->setProperty('contract', $contract);

			$contractFinisher = new \Beech\CLA\Finishers\ContractFinisher();
			$contractFinisher->setOptions(
				array(
					'contract' => $contract
				)
			);
			$this->form->addFinisher($contractFinisher);

			$summaryFinisher = new \Beech\Ehrm\Form\Finishers\ModalCloseConfirmationFinisher();
			$summaryFinisher->setOption('templatePathAndFilename', 'resource://Beech.CLA/Private/Templates/Administration/Contract/Summary.html');
			$summaryFinisher->setOption('actions', array('close' => '/#/administration/contracts/'.time()));
			$this->form->addFinisher($summaryFinisher);

		}
		return $this->form;
	}

	/**
	 * Create next page
	 * Return FALSE when there is no elements on page
	 *
	 * @param \Beech\CLA\Domain\Model\Contract $contract
	 * @param integer $articlesPage
	 * @return boolean
	 */
	protected function nextArticlesPage($contract, $articlesPage = NULL) {
		if ($articlesPage === NULL) {
			$articlesPage = $this->form->createPage('page' . $this->pageIndex);
		}

		/** @var $articlesSection \Beech\CLA\FormElements\ContractArticlesSection */
		$articlesSection = $articlesPage->createElement('articles' . $this->pageIndex, 'Beech.CLA:ContractArticlesSection');
		$articlesSection->setContract($contract);
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
