<?php
namespace Beech\Party\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\ElectronicAddress as ElectronicAddress;

/**
 * ElectronicAddress controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class ElectronicAddressController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Party\Domain\Model\ElectronicAddress';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Party\Domain\Repository\ElectronicAddressRepository';

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \Beech\Party\Domain\Model\ElectronicAddress $electronicAddress A electronicAddress to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Party\Domain\Model\ElectronicAddress $electronicAddress) {
		$electronicAddress->setParty($this->persistenceManager->getIdentifierByObject($electronicAddress->getParty()));
		$this->repository->add($electronicAddress);
		$this->view->assign('electronicAddress', $electronicAddress);
		$this->view->assign('party', $electronicAddress->getParty());
		$this->view->assign('action', 'add');
	}

	/**
	 * @param \Beech\Party\Domain\Model\ElectronicAddress $electronicAddress A electronicAddress to update
	 *
	 * @return void
	 */
	public function updateAction(\Beech\Party\Domain\Model\ElectronicAddress $electronicAddress) {
		if ($this->getControllerContext()->getRequest()->getArgument('action') === 'remove') {
			$this->redirect('remove', 'ElectronicAddress', NULL, array('electronicAddress' => $electronicAddress, 'party' => $electronicAddress->getParty()));
		} else {
			$electronicAddress->setParty($this->persistenceManager->getIdentifierByObject($electronicAddress->getParty()));
			$this->repository->update($electronicAddress);
			$this->addFlashMessage($this->translator->translateById('Updated.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
			$this->view->assign('electronicAddress', $electronicAddress);
			$this->view->assign('party', $electronicAddress->getParty());
			$this->view->assign('action', 'update');
		}
	}

	/**
	 * @param \Beech\Party\Domain\Model\ElectronicAddress $electronicAddress A electronicAddress to remove
	 * @Flow\IgnoreValidation("$electronicAddress")
	 * @return void
	 */
	public function removeAction(\Beech\Party\Domain\Model\ElectronicAddress $electronicAddress) {
		$electronicAddress->setParty(NULL);
		$this->repository->update($electronicAddress);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}
}

?>