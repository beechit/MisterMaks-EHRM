<?php
namespace Beech\Party\Administration\Controller;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\Person as Person;

/**
 * Person controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class PersonController extends \Beech\Party\Controller\PersonController {

	/**
	 * @var \Beech\Party\Domain\Repository\CompanyRepository
	 * @Flow\Inject
	 */
	protected $companyRepository;

	/**
	 * @param \Beech\Party\Domain\Model\Person $person A new person to add
	 * @return void
	 */
	public function createAction(Person $person = NULL) {
		$this->repository->add($person);
		$this->addFlashMessage('Person is added');
		$this->redirect('edit', NULL, NULL, array('person' => $person, 'withDetails' => FALSE));
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person A new person to update
	 * @return void
	 */
	public function updateAction(Person $person = NULL) {
		$this->repository->update($person);
		$this->addFlashMessage('Person is updated.');
		$this->redirect('edit', NULL, NULL, array('person' => $person, 'withDetails' => FALSE));
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