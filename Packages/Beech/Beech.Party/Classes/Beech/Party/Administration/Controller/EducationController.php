<?php
namespace Beech\Party\Administration\Controller;

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
class EducationController extends \Beech\Party\Controller\EducationController {

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
		$this->addFlashMessage($this->translator->translateById('Added.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

	/**
	 * @param \Beech\Party\Domain\Model\Education $education A education to update
	 *
	 * @return void
	 */
	public function updateAction(\Beech\Party\Domain\Model\Education $education) {
		$education->setParty($this->persistenceManager->getIdentifierByObject($education->getParty()));
		$this->repository->update($education);
		$this->addFlashMessage($this->translator->translateById('Updated.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
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