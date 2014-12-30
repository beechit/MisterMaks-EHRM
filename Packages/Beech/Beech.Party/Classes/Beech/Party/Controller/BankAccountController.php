<?php
namespace Beech\Party\Controller;

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
use Beech\Party\Domain\Model\BankAccount as BankAccount;

/**
 * BankAccount controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class BankAccountController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Party\Domain\Model\BankAccount';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Party\Domain\Repository\BankAccountRepository';

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $party
	 */
	public function listAction(\TYPO3\Party\Domain\Model\AbstractParty $party = NULL) {
		$this->view->assign('party', $party);
		$this->view->assign('bankAccounts', $this->repository->findByParty($party));
	}

	/**
	 * @param \Beech\Party\Domain\Model\BankAccount $bankAccount A bankAccount to add
	 * @Flow\Validate(argumentName="bankAccount", type="Beech.Ehrm:Iban")
	 * @return void
	 */
	public function addAction(\Beech\Party\Domain\Model\BankAccount $bankAccount = NULL) {
		$bankAccount->setParty($this->persistenceManager->getIdentifierByObject($bankAccount->getParty()));
		$this->repository->add($bankAccount);
		$this->view->assign('bankAccount', $bankAccount);
		$this->view->assign('party', $bankAccount->getParty());
		$this->view->assign('action', 'add');
	}

	/**
	 * @param \Beech\Party\Domain\Model\BankAccount $bankAccount A bankAccount to update
	 * @Flow\Validate(argumentName="bankAccount", type="Beech.Ehrm:Iban")
	 * @return void
	 */
	public function updateAction(\Beech\Party\Domain\Model\BankAccount $bankAccount = NULL) {
		If	($this->getControllerContext()->getRequest()->getArgument('action') === 'remove') {
			$this->redirect('remove', 'BankAccount', NULL, array('bankAccount' => $bankAccount, 'party' => $address->getParty()));
		} else {
			$bankAccount->setParty($this->persistenceManager->getIdentifierByObject($bankAccount->getParty()));
			$this->repository->update($bankAccount);
			$this->addFlashMessage($this->translator->translateById('Updated.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
			$this->view->assign('bankAccount', $bankAccount);
			$this->view->assign('party', $bankAccount->getParty());
			$this->view->assign('action', 'update');
		}
	}

	/**
	 * @param \Beech\Party\Domain\Model\BankAccount $bankAccount A bankAccount to remove
	 * @Flow\IgnoreValidation("bankAccount")
	 * @return void
	 */
	public function removeAction(\Beech\Party\Domain\Model\BankAccount $bankAccount = NULL) {
		$this->repository->remove($bankAccount);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

}

?>