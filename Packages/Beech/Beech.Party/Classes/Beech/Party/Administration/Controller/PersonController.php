<?php
namespace Beech\Party\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\Person as Person;

/**
 * Person controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class PersonController extends \Beech\Party\Controller\PersonController {

	/**
	 * @param \Beech\Party\Domain\Model\Person $person A new person to add
	 * @return void
	 */
	public function createAction(Person $person) {
		$this->repository->add($person);
		$this->addFlashMessage('Person is added');
		$this->redirect('edit', NULL, NULL, array('person' => $person, 'withDetails' => FALSE));
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person A new person to update
	 * @return void
	 */
	public function updateAction(Person $person) {
		$this->repository->update($person);
		$this->addFlashMessage('Person is updated.');
		$this->emberRedirect('#/administration/person');
	}
	/**
	 * @param \Beech\Absence\Domain\Model\Absence $absence A absence to add
	 *
	 * @return void
	 */
	public function addAbsenceAction(\Beech\Absence\Domain\Model\Absence $absence) {
		$absence->setParty($this->persistenceManager->getIdentifierByObject($absence->getParty()));
		$this->absenceRepository->add($absence);
		$this->addFlashMessage('Added.');
		$this->redirect('edit', NULL, NULL, array('person' => $absence->getParty()));
	}

	/**
	 * @param \Beech\Absence\Domain\Model\Absence $absence A absence to update
	 *
	 * @return void
	 */
	public function updateAbsenceAction(\Beech\Absence\Domain\Model\Absence $absence) {
		$absence->setParty($this->persistenceManager->getIdentifierByObject($absence->getParty()));
		$this->licenceRepository->update($absence);
		$this->addFlashMessage('Updated.');
		$this->redirect('edit', NULL, NULL, array('person' => $absence->getParty()));
	}

	/**
	 * @param \Beech\Absence\Domain\Model\Absence $absence A absence to remove
	 *
	 * @return void
	 */
	public function removeAbsenceAction(\Beech\Absence\Domain\Model\Absence $absence) {
		$person = $absence->getParty();
		$absence->setParty(NULL);
		$this->absenceRepository->update($absence);
		$this->addFlashMessage('Removed.');
		$this->redirect('edit', NULL, NULL, array('person' => $person));
	}

}

?>