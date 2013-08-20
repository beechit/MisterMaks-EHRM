<?php
namespace Beech\Party\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\PhoneNumber as PhoneNumber;

/**
 * PhoneNumber controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class PhoneNumberController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Party\Domain\Model\PhoneNumber';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Party\Domain\Repository\PhoneNumberRepository';

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $party
	 */
	public function listAction(\TYPO3\Party\Domain\Model\AbstractParty $party) {
		$this->view->assign('party', $party);
		$this->view->assign('phoneNumbers', $this->repository->findByParty($party));
	}

	/**
	 * @param \Beech\Party\Domain\Model\PhoneNumber $phoneNumber A new phoneNumber to add
	 * @Flow\Validate(argumentName="phoneNumber", type="Beech.Party:PhoneNumber")
	 * @return void
	 */
	public function addAction(\Beech\Party\Domain\Model\PhoneNumber $phoneNumber) {
		$phoneNumber->setParty($this->persistenceManager->getIdentifierByObject($phoneNumber->getParty()));
		$this->repository->add($phoneNumber);
		$this->view->assign('phoneNumber', $phoneNumber);
		$this->view->assign('party', $phoneNumber->getParty());
		$this->view->assign('action', 'add');
		}

	/**
	 * @param \Beech\Party\Domain\Model\PhoneNumber $phoneNumber A  phoneNumber to update
	 * @Flow\Validate(argumentName="phoneNumber", type="Beech.Party:PhoneNumber")
	 * @return void
	 */
	public function updateAction(\Beech\Party\Domain\Model\PhoneNumber $phoneNumber) {
		if ($this->getControllerContext()->getRequest()->getArgument('action') === 'remove') {
			$this->redirect('remove', 'PhoneNumber', NULL, array('phoneNumber' => $phoneNumber, 'party' => $phoneNumber->getParty()));
		} else {
			$phoneNumber->setParty($this->persistenceManager->getIdentifierByObject($phoneNumber->getParty()));
			$this->repository->update($phoneNumber);
			$this->addFlashMessage($this->translator->translateById('Updated.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
			$this->view->assign('phoneNumber', $phoneNumber);
			$this->view->assign('party', $phoneNumber->getParty());
			$this->view->assign('action', 'update');
		}
	}

	/**
	 * @param \Beech\Party\Domain\Model\PhoneNumber $phoneNumber A new phoneNumber to remove
	 * @Flow\IgnoreValidation("$phoneNumber")
	 * @return void
	 */
	public function removeAction(\Beech\Party\Domain\Model\PhoneNumber $phoneNumber) {
		$this->repository->remove($phoneNumber);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

}

?>