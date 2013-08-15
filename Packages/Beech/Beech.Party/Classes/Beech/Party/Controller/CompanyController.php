<?php
namespace Beech\Party\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\Company as Company;

/**
 * Company controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class CompanyController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Party\Domain\Model\Company';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Party\Domain\Repository\CompanyRepository';

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @var \Beech\Party\Domain\Repository\AddressRepository
	 * @Flow\Inject
	 */
	protected $addressRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PhoneNumberRepository
	 * @Flow\Inject
	 */
	protected $phoneNumberRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\ElectronicAddressRepository
	 * @Flow\Inject
	 */
	protected $electronicAddressRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\BankAccountRepository
	 * @Flow\Inject
	 */
	protected $bankAccountRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 * @Flow\Inject
	 */
	protected $personRepository;

	/**
	 * @var \Beech\Ehrm\Utility\PreferenceUtility
	 * @Flow\Inject
	 */
	protected $preferenceUtility;

	/**
	 * Shows company preview with departments
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('companies', $this->repository->findAll());
			// take initial company as a default
		$initialCompanyIdentifier = $this->preferenceUtility->getApplicationPreference('company');
		$this->view->assign('company', $this->repository->findByIdentifier($initialCompanyIdentifier));
	}

	/**
	 * Shows a list of companies
	 *
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('companies', $this->repository->findAll());
	}

	/**
	 * Shows a single company object
	 *
	 * @param \Beech\Party\Domain\Model\Company $company
	 * @Flow\IgnoreValidation("$company")
	 * @return void
	 */
	public function showAction(Company $company) {
		$this->view->assign('company', $company);
		$addresses = $this->addressRepository->findByParty($company->getId());
		$this->view->assign('addresses', $addresses);
		$phoneNumbers = $this->phoneNumberRepository->findByParty($company->getId());
		$this->view->assign('phoneNumbers', $phoneNumbers);
		$electronicAddresses = $this->electronicAddressRepository->findByParty($company->getId());
		$this->view->assign('electronicAddresses', $electronicAddresses);
		$bankAccounts = $this->bankAccountRepository->findByParty($company->getId());
		$this->view->assign('bankAccounts', $bankAccounts);
	}

	/**
	 * Edit a single company object
	 *
	 * @param \Beech\Party\Domain\Model\Company $company
	 * @Flow\IgnoreValidation("$company")
	 * @return void
	 */
	public function editAction(Company $company) {
		$this->view->assign('company', $company);
		$addresses = $this->addressRepository->findByParty($company->getId());
		$this->view->assign('addresses', $addresses);
		$phoneNumbers = $this->phoneNumberRepository->findByParty($company->getId());
		$this->view->assign('phoneNumbers', $phoneNumbers);
		$electronicAddresses = $this->electronicAddressRepository->findByParty($company->getId());
		$this->view->assign('electronicAddresses', $electronicAddresses);
		$persons = $this->personRepository->findAll();
		$this->view->assign('persons', $persons);
	}

	/**
	 * Shows a form for creating a new account object
	 *
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $company A new company to add
	 *
	 * @return void
	 */
	public function createAction(Company $company) {
		$this->repository->add($company);
		$this->addFlashMessage('Company is added');
		$this->emberRedirect('#/company');
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $company A new company to update
	 *
	 * @return void
	 */
	public function updateAction(Company $company) {
		$this->repository->update($company);
		$this->addFlashMessage('Company is updated');
		$this->emberRedirect('#/company');
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $company A new company to delete
	 * @return void
	 */
	public function deleteAction(Company $company) {
		$this->repository->remove($company);
			// persist manualy because GET requests will not be auto persisted
		$this->persistenceManager->persistAll();
		$this->addFlashMessage('Company is removed');
		$this->emberRedirect('#/company');
	}

}

?>