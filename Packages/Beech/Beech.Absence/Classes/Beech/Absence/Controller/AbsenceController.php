<?php
namespace Beech\Absence\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Absence\Domain\Model\Absence as Absence;

/**
 * Absence controller for the Beech.Absence package
 *
 * @Flow\Scope("singleton")
 */
class AbsenceController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Absence\Domain\Model\Absence';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Absence\Domain\Repository\AbsenceRepository';

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \Beech\Absence\Domain\Model\Absence $absence A absence to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Absence\Domain\Model\Absence $absence) {
		$absence->setParty($this->persistenceManager->getIdentifierByObject($absence->getParty()));
		$this->repository->add($absence);
		$this->addFlashMessage($this->translator->translateById('Added', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

	/**
	 * @param \Beech\Absence\Domain\Model\Absence $absence A absence to update
	 *
	 * @return void
	 */
	public function updateAction(\Beech\Absence\Domain\Model\Absence $absence) {
		$absence->setParty($this->persistenceManager->getIdentifierByObject($absence->getParty()));
		$this->repository->update($absence);
		$this->addFlashMessage($this->translator->translateById('Updated', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

	/**
	 * @param \Beech\Absence\Domain\Model\Absence $absence A absence to remove
	 *
	 * @return void
	 */
	public function removeAction(\Beech\Absence\Domain\Model\Absence $absence) {
		$absence->setParty(NULL);
		$this->repository->update($absence);
		$this->addFlashMessage($this->translator->translateById('Removed', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}
}

?>