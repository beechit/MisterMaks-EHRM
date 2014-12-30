<?php
namespace Beech\CLA\Administration\Controller;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

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
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * Select contract template
	 * @param \Beech\Party\Domain\Model\Person $employee
	 */
	public function newAction($employee = NULL) {
		$this->view->assign('overrideConfiguration', array(
			'employee' => $employee
		));
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
		$this->addFlashMessage($this->translator->translateById('Created a new contract', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
		$this->redirect('show', 'Person', 'Beech.Party', array('person' => $contract->getEmployee()));
	}

	/**
	 * Shows a single contract object
	 *
	 * @param \Beech\CLA\Domain\Model\Contract $contract The contract to show
	 * @Flow\IgnoreValidation("$contract")
	 * @return void
	 */
	public function showAction(Contract $contract) {
		$this->view->assign('overrideConfiguration', array(
			'contract' => $contract
		));
		$this->view->assign('contract', $contract);
	}

	/**
	 * Shows a single contract object as pdf
	 *
	 * @param \Beech\CLA\Domain\Model\Contract $contract The contract to show
	 * @Flow\IgnoreValidation("$contract")
	 * @return void
	 */
	public function pdfAction(Contract $contract) {
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
		$this->addFlashMessage($this->translator->translateById('Updated the contract', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
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