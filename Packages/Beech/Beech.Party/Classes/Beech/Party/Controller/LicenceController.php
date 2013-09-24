<?php
namespace Beech\Party\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\Licence as Licence;

/**
 * Education controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class LicenceController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 * @Flow\Inject
	 */
	protected $objectManager;

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Party\Domain\Model\Licence';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Party\Domain\Repository\LicenceRepository';

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
		$this->view->assign('licences', $this->repository->findByParty($party));
	}

	/**
	 * @param \Beech\Party\Domain\Model\Licence $licence A licence to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Party\Domain\Model\Licence $licence) {
		$licence->setParty($this->persistenceManager->getIdentifierByObject($licence->getParty()));
		$this->repository->add($licence);
		$this->view->assign('licence', $licence);
		$this->view->assign('party', $licence->getParty());
		$this->view->assign('action', 'add');
	}

	public function newAction(){

	}

	/**
	 * @param \Beech\Party\Domain\Model\Licence $licence A licence to update
	 *
	 * @return void
	 */
	public function updateAction(\Beech\Party\Domain\Model\licence $licence) {
		if ($this->getControllerContext()->getRequest()->getArgument('action') === 'remove') {
			$this->redirect('remove', 'Licence', NULL, array('licence' => $licence, 'party' => $licence->getParty()));
		} else {
			$licence->setParty($this->persistenceManager->getIdentifierByObject($licence->getParty()));
			$this->repository->update($licence);
			$this->addFlashMessage($this->translator->translateById('Updated.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
			$this->view->assign('licence', $licence);
			$this->view->assign('party', $licence->getParty());
			$this->view->assign('action', 'update');
		}
	}

	/**
	 * @param \Beech\Party\Domain\Model\licence $licence A licence to remove
	 *
	 * @return void
	 */
	public function removeAction(\Beech\Party\Domain\Model\licence $licence) {
		$this->repository->remove($licence);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}
}

?>