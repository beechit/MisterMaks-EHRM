<?php
namespace Beech\Party\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-07-12 13:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use \Beech\Party\Domain\Model\Company;

/**
 * Company controller for the Beech.Party package and subpackage Administration
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
	 * Adds the given new company object to the company repository
	 *
	 * @param \Beech\Party\Domain\Model\Company $company A new company to add
	 * @return void
	 */
	public function createAction(Company $company) {
		$this->repository->add($company);
		$this->documentManager->merge($company->getDocument());
		$this->addFlashMessage('Created a new company');
		$this->redirect('list');
	}

	/**
	 * Shows a single company object
	 *
	 * @param \Beech\Party\Domain\Model\Company $company The company to show
	 * @return void
	 */
	public function showAction(Company $company) {
		$this->view->assign('company', $company);
	}

	/**
	 * Updates the given company object
	 *
	 * @param \Beech\Party\Domain\Model\Company $company The company to update
	 * @return void
	 */
	public function updateAction(Company $company) {
		$this->repository->update($company);
		$this->addFlashMessage('Updated the company.');
		$this->redirect('list');
	}

	/**
	 * Shows a form for editing an existing company object
	 *
	 * @param \Beech\Party\Domain\Model\Company $company The company to edit
	 * @return void
	 */
	public function editAction(Company $company) {
		$this->view->assign('company', $company);
	}

	/**
	 * Removes the given company object from the company repository
	 *
	 * @param \Beech\Party\Domain\Model\Company $company The company to delete
	 * @return void
	 */
	public function deleteAction(Company $company) {
		$this->repository->remove($company);
		$this->redirect('list');
	}

}

?>