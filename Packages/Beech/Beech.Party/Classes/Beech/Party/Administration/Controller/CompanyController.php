<?php
namespace Beech\Party\Administration\Controller;

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
	 * Shows a list of companies
	 *
	 * @return void
	 */
	public function listAction() {
		$companies = $this->repository->findAll();
		foreach ($companies as $company) {
			$identifier = $this->persistenceManager->getIdentifierByObject($company);
			$company->id = $identifier;
		}
		$this->view->assign('companies', $this->repository->findAll());
	}

	/**
	 * Shows a single company object
	 *
	 * @param \Beech\Party\Domain\Model\Company $company
	 * @return void
	 */
	public function showAction(Company $company) {
		$this->view->assign('company', $company);
	}

	/**
	 * Edit a single company object
	 *
	 * @param \Beech\Party\Domain\Model\Company $company
	 * @return void
	 */
	public function editAction(Company $company) {
		$identifier = $this->persistenceManager->getIdentifierByObject($company);
		$company->id = $identifier;
		$this->view->assign('company', $company);
		$addresses = $this->addressRepository->findByParty($identifier);
		$this->view->assign('addresses', $addresses);
		$phoneNumbers = $this->phoneNumberRepository->findByParty($identifier);
		$this->view->assign('phoneNumbers', $phoneNumbers);
		$electronicAddresses = $this->electronicAddressRepository->findByParty($identifier);
		$this->view->assign('electronicAddresses', $electronicAddresses);
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
		$this->redirect('list');
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $company A new company to update
	 *
	 * @return void
	 */
	public function updateAction(Company $company) {
		$this->repository->update($company);
		$this->addFlashMessage('Company is updated.');
		$this->emberRedirect('#/administration/company');
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $company A new company to delete
	 *
	 * @return void
	 */
	public function deleteAction(Company $company) {
		$this->repository->remove($company);
		$this->addFlashMessage('Company is removed .');
		$this->redirect('list');
	}

}
?>