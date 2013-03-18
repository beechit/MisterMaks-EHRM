<?php
namespace Beech\CLA\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-02-13 09:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use \Beech\CLA\Domain\Model\Contract;

/**
 * Contract controller for the Beech.CLA package  and subpackage Administration
 *
 * @Flow\Scope("singleton")
 */
class ContractController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\CLA\Domain\Model\Contract';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\CLA\Domain\Repository\ContractRepository';

	/**
	 * Select contract template
	 */
	public function newAction() {
	}

	/**
	 * @param string $contractTemplate
	 * @param string $employee
	 * @param string $jobDescription
	 */
	public function startAction($contractTemplate, $employee, $jobDescription) {
		$this->view->assign('overrideConfiguration', array(
			'contractTemplate' => $contractTemplate,
			'employee' => $employee,
			'jobDescription' => $jobDescription
		));
	}

	/**
	 * Adds the given new contract object to the contract repository
	 *
	 * @param \Beech\CLA\Domain\Model\Contract $contract A new contract to add
	 * @return void
	 */
	public function createAction(Contract $contract) {
		$this->repository->add($contract);
		$this->documentManager->merge($contract->getDocument());
		$this->addFlashMessage('Created a new contract');
		$this->redirect('list');
	}

	/**
	 * Shows a single contract object
	 *
	 * @param \Beech\CLA\Domain\Model\Contract $contract The contract to show
	 * @return void
	 */
	public function showAction(Contract $contract) {
		$this->view->assign('overrideConfiguration', array(
			'contract' => $contract
		));
		$this->view->assign('contract', $contract);
	}

	/**
	 * Updates the given contract object
	 *
	 * @param \Beech\CLA\Domain\Model\Contract $contract The contract to update
	 * @return void
	 */
	public function updateAction(Contract $contract) {
		$this->repository->update($contract);
		$this->addFlashMessage('Updated the contract.');
		$this->redirect('list');
	}

	/**
	 * Shows a form for editing an existing contract object
	 *
	 * @param \Beech\CLA\Domain\Model\Contract $contract The contract to edit
	 * @return void
	 */
	public function editAction(Contract $contract) {
		$this->view->assign('contract', $contract);
	}

	/**
	 * Removes the given contract object from the contract repository
	 *
	 * @param \Beech\CLA\Domain\Model\Contract $contract The contract to delete
	 * @return void
	 */
	public function deleteAction(Contract $contract) {
		$this->repository->remove($contract);
		$this->redirect('list');
	}

}

?>