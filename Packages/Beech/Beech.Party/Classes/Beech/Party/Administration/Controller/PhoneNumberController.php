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
	 * @param \Beech\Party\Domain\Model\PhoneNumber $phoneNumber A new phoneNumber to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Party\Domain\Model\PhoneNumber $phoneNumber) {

		$phoneNumber->setParty($this->persistenceManager->getIdentifierByObject($phoneNumber->getParty()));
		$this->repository->add($phoneNumber);
		$this->view->assign('phoneNumber', $phoneNumber);
		$this->view->assign('party', $phoneNumber->getParty());
		$this->view->assign('action', 'add');
		$this->addFlashMessage($this->translator->translateById('Added.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

	/**
	 * @param \Beech\Party\Domain\Model\PhoneNumber $phoneNumber A  phoneNumber to update
	 *
	 * @return void
	 */
	public function updateAction(\Beech\Party\Domain\Model\PhoneNumber $phoneNumber) {
		$phoneNumber->setParty($this->persistenceManager->getIdentifierByObject($phoneNumber->getParty()));
		$this->repository->update($phoneNumber);
		$this->addFlashMessage($this->translator->translateById('Updated.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
		$this->view->assign('phoneNumber', $phoneNumber);
		$this->view->assign('party', $phoneNumber->getParty());
		$this->view->assign('action', 'update');
	}

	/**
	 * @param \Beech\Party\Domain\Model\PhoneNumber $phoneNumber A new phoneNumber to remove
	 *
	 * @return void
	 */
	public function removeAction(\Beech\Party\Domain\Model\PhoneNumber $phoneNumber) {
		$phoneNumber->setParty(NULL);
		$this->repository->update($phoneNumber);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
		$this->addFlashMessage('Removed.');
	}

	/**
	 * A special action which is called if the originally intended action could
	 * not be called, for example if the arguments were not valid.
	 *
	 * The default implementation sets a flash message, request errors and forwards back
	 * to the originating action. This is suitable for most actions dealing with form input.
	 *
	 * @return string
	 */
	protected function errorAction() {
		$this->getControllerContext()->getResponse()->setStatus(500);
		return '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.parent::errorAction().'</div>';
	}
}

?>