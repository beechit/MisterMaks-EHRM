<?php
namespace Beech\Party\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\PhoneNumber as PhoneNumber;

/**
 * Address controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class AddressController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Party\Domain\Model\Address';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Party\Domain\Repository/AddressRepository';

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \Beech\Party\Domain\Model\Address $address A address to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Party\Domain\Model\Address $address) {
		$address->setParty($this->persistenceManager->getIdentifierByObject($address->getParty()));
		$this->repository->add($address);
		$this->view->assign('address', $address);
		$this->view->assign('party', $address->getParty());
		$this->view->assign('action', 'add');
	}

	/**
	 * @param \Beech\Party\Domain\Model\Address $address A address to update
	 *
	 * @return void
	 */
	public function updateAction(\Beech\Party\Domain\Model\Address $address) {
		If	($this->getControllerContext()->getRequest()->getArgument('action') === 'remove') {
			$this->redirect('remove', 'Address', NULL, array('address' => $address, 'party' => $address->getParty()));
		} else {
			$address->setParty($this->persistenceManager->getIdentifierByObject($address->getParty()));
			$this->repository->update($address);
			$this->addFlashMessage($this->translator->translateById('Updated.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
			$this->view->assign('address', $address);
			$this->view->assign('party', $address->getParty());
			$this->view->assign('action', 'update');
		}
	}

	/**
	 * @param \Beech\Party\Domain\Model\Address $address A address to remove
	 * @Flow\IgnoreValidation("address")
	 * @return void
	 */
	public function removeAction(\Beech\Party\Domain\Model\Address $address) {
		$address->setParty(NULL);
		$this->repository->update($address);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

}

?>