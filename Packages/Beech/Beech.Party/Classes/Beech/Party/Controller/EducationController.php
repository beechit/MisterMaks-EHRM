<?php
namespace Beech\Party\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\Education as Education;

/**
 * Education controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class EducationController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Party\Domain\Model\Education';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Party\Domain\Repository\EducationRepository';

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \Beech\Party\Domain\Model\Education $Education A education to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Party\Domain\Model\Education $education) {
		$education->setParty($this->persistenceManager->getIdentifierByObject($education->getParty()));
		$this->repository->add($education);
		$this->view->assign('education', $education);
		$this->view->assign('party', $education->getParty());
	}

	/**
	 * @param \Beech\Party\Domain\Model\Education $education A education to update
	 *
	 * @return void
	 */
	public function updateAction(\Beech\Party\Domain\Model\Education $education) {
		if	($this->getControllerContext()->getRequest()->getArgument('action') === 'remove') {
			$this->redirect('remove', 'Education', NULL, array('education' => $education, 'party' => $education->getParty()));
		} else {
			$education->setParty($this->persistenceManager->getIdentifierByObject($education->getParty()));
			$this->repository->update($education);
			$this->view->assign('education', $education);
			$this->view->assign('party', $education->getParty());
			$this->view->assign('action', 'update');
		}
	}

	/**
	 * @param \Beech\Party\Domain\Model\Education $education A education to remove
	 *
	 * @return void
	 */
	public function removeAction(\Beech\Party\Domain\Model\Education $education) {
		$education->setParty(NULL);
		$this->repository->update($education);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

}

?>