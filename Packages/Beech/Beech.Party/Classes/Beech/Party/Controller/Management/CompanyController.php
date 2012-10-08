<?php
namespace Beech\Party\Controller\Management;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-07-12 13:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\Company;

/**
 * Company controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class CompanyController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \Beech\Party\Domain\Repository\AddressRepository
	 * @Flow\Inject
	 */
	protected $addressRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\CompanyRepository
	 * @Flow\Inject
	 */
	protected $companyRepository;

	/**
	 * @var \Beech\Party\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * Shows a list of companies
	 *
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('companies', $this->companyRepository->findByParentCompany(NULL));
	}

	/**
	 * Shows a single company object
	 *
	 * @param \Beech\Party\Domain\Model\Company $company The company to show
	 * @return void
	 */
	public function showAction(Company $company) {
		$this->view->assign('departments', $this->companyRepository->findByParentCompany($company));
		$this->view->assign('company', $company);
	}

	/**
	 * Shows a form for creating a new company object
	 *
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * Adds the given new company object to the company repository
	 *
	 * @param \Beech\Party\Domain\Model\Company $newCompany A new company to add
	 * @return void
	 */
	public function createAction(\Beech\Party\Domain\Model\Company $newCompany) {
		$this->companyRepository->add($newCompany);
		$this->addFlashMessage($this->translator->translateId('flashmessage.createdCompany'));
		$this->redirect('list');
	}

	/**
	 * Shows a form for creating a new company object
	 *
	 * @param \Beech\Party\Domain\Model\Company $company The parent company
	 * @return void
	 */
	public function newDepartmentAction(\Beech\Party\Domain\Model\Company $company) {
		$this->view->assign('company', $company);
	}

	/**
	 * Adds the given new company object to the company repository
	 *
	 * @param \Beech\Party\Domain\Model\Company $newDepartment A new department to add
	 * @param \Beech\Party\Domain\Model\Company $company The parent company
	 * @return void
	 */
	public function addDepartmentAction(Company $newDepartment, Company $company) {
		$newDepartment->setParentCompany($company);
		$this->companyRepository->add($newDepartment);
		$this->addFlashMessage($this->translator->translateId('flashmessage.newDepartment'));
		$this->redirect('list');
	}

	/**
	 * Shows a form for editing an existing company object
	 *
	 * @param \Beech\Party\Domain\Model\Company $company The company to edit
	 * @Flow\IgnoreValidation("$company")
	 * @return void
	 */
	public function editAction(Company $company) {
		$this->view->assign('company', $company);
	}

	/**
	 * Updates the given company object
	 *
	 * @param \Beech\Party\Domain\Model\Company $company The company to update
	 * @return void
	 */
	public function updateAction(Company $company) {
		$this->companyRepository->update($company);
		$this->addFlashMessage($this->translator->translateId('flashmessage.updatedCompany'));
		$this->redirect('list');
	}

	/**
	 * Removes the given company object from the company repository
	 *
	 * @param \Beech\Party\Domain\Model\Company $company The company to delete
	 * @return void
	 */
	public function deleteAction(\Beech\Party\Domain\Model\Company $company) {
		$this->companyRepository->remove($company);
		$this->addFlashMessage($this->translator->translateId('flashmessage.deletedCompany'));
		$this->redirect('list');
	}

	/**
	 * Redirect to list action
	 *
	 * @return void
	 */
	public function redirectAction() {
		$this->redirect('list');
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $company
	 * @param \Beech\Party\Domain\Model\Address $newAddress
	 * @return void
	 */
	public function newAddressAction(\Beech\Party\Domain\Model\Company $company, \Beech\Party\Domain\Model\Address $newAddress = NULL) {
		$this->view->assign('company', $company);
		$this->view->assign('newAddress', $newAddress);
	}

	/**
	 * Adds the given new company object to the company repository
	 *
	 * @param \Beech\Party\Domain\Model\Company $company The company
	 * @param \Beech\Party\Domain\Model\Address $newAddress A new address to add
	 * @return void
	 */
	public function createAddressAction(\Beech\Party\Domain\Model\Company $company, \Beech\Party\Domain\Model\Address $newAddress) {
		$company->addAddress($newAddress);
		$this->companyRepository->update($company);
		$this->addFlashMessage($this->translator->translateId('flashmessage.addedAddress'));
		$this->redirect('show', 'Management\Company', NULL, array('company' => $company));
	}
}

?>