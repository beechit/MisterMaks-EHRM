<?php
namespace Beech\Party\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

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
	 * @param \Beech\Party\Domain\Model\BankAccount $bankAccount A bankAccount to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Party\Domain\Model\BankAccount $bankAccount) {
		$bankAccount->setParty($this->persistenceManager->getIdentifierByObject($bankAccount->getParty()));
		$this->repository->add($bankAccount);
		$this->view->assign('bankAccount', $bankAccount);
		$this->view->assign('party', $bankAccount->getParty());
		$this->view->assign('action', 'add');
	}

	/**
	 * @param \Beech\Party\Domain\Model\BankAccount $bankAccount A bankAccount to update
	 *
	 * @return void
	 */
	public function updateAction(\Beech\Party\Domain\Model\BankAccount $bankAccount) {
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
	public function removeAction(\Beech\Party\Domain\Model\BankAccount $bankAccount) {
		$bankAccount->setParty(NULL);
		$this->repository->update($bankAccount);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

}

?>