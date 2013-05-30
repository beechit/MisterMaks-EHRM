<?php
namespace Beech\Party\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\Licence as Licence;

/**
 * Licence controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class LicenceController extends \Beech\Ehrm\Controller\AbstractManagementController {

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
	 * @param \Beech\Party\Domain\Model\Licence $licence A licence to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Party\Domain\Model\licence $licence) {
		$licence->setParty($this->persistenceManager->getIdentifierByObject($licence->getParty()));
		$this->repository->add($licence);
		$this->view->assign('licence', $licence);
		$this->view->assign('party', $licence->getParty());
		$this->view->assign('action', 'add');
	}

	/**
	 * @param \Beech\Party\Domain\Model\licence $licence A licence to update
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
		$licence->setParty(NULL);
		$this->repository->update($licence);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}
}

?>
