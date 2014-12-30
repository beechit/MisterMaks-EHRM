<?php
namespace Beech\Absence\Controller;

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
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 * @Flow\Inject
	 */
	protected $personRepository;

	/**
	 * @var \Beech\Absence\Domain\Repository\AbsenceArrangementRepository
	 * @Flow\Inject
	 */
	protected $absenceArrangementRepository;

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * List a history of sickness
	 *
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @param string $absenceType
	 * @param \Beech\Party\Domain\Model\Company $department
	 * @return void
	 */
	public function listAction(\Beech\Party\Domain\Model\Person $person = NULL, $absenceType = Absence::OPTION_SICKNESS, \Beech\Party\Domain\Model\Company $department = NULL) {

		if (!in_array($absenceType, array(Absence::OPTION_SICKNESS, Absence::OPTION_LEAVE))) {
			$absenceType = Absence::OPTION_SICKNESS;
		}

		if ($department !== NULL) {
			$absences = $this->repository->findByDepartmentAndType($department, $absenceType);
		} elseif ($person !== NULL) {
			$absences = $this->repository->findByPersonAndType($person, $absenceType);
		} else {
			$absences = $this->repository->findByAbsenceType($absenceType);
		}

		$this->view->assign('person', $person);
		$this->view->assign('absences', $absences);
		$this->view->assign('absenceType', $absenceType);
	}

	/**
	 * Shows a form for creating a person sick report
	 *
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @param string $absenceType
	 * @return void
	 */
	public function newAction(\Beech\Party\Domain\Model\Person $person = NULL, $absenceType = Absence::OPTION_SICKNESS) {

		$this->view->assign('persons', $this->personRepository->findAll());
		$absenceArrangements = $this->absenceArrangementRepository->findByAbsenceType($absenceType);
		$this->view->assign('absenceArrangements', $absenceArrangements);

		$absence = new Absence();
		$absence->setReportedTo($this->preferencesUtility->getCurrentUser());
		$absence->setPerson($person);
		$absence->setAbsenceType($absenceType);
		if ($absence->getAbsenceType() === 'leave') {
			$absence->setRequestStatus('pending');
		} else {
			$absence->setStartDate(new \TYPO3\Flow\Utility\Now());
		}
		$this->view->assign('absence', $absence);
	}

	/**
	 * @param \Beech\Absence\Domain\Model\Absence $absence
	 * @return void
	 */
	public function createAction(\Beech\Absence\Domain\Model\Absence $absence) {
		$this->repository->add($absence);
		$options = array('absence' => $absence);
		$personIdentifier = $this->persistenceManager->getIdentifierByObject($absence->getPerson(), '\Beech\Party\Domain\Model\Person');

		$this->emberRedirect('#/person/show/' . $personIdentifier . '/' . uniqid());
	}

	/**
	 * @param \Beech\Absence\Domain\Model\Absence $absence
	 * @return void
	 */
	public function approveAction(\Beech\Absence\Domain\Model\Absence $absence) {
		$this->view->assign('absence', $absence);
	}

	/**
	 * @param \Beech\Absence\Domain\Model\Absence $absence
	 * @return void
	 */
	public function recoveryAction(\Beech\Absence\Domain\Model\Absence $absence) {
		$absence->setRecoveryRegistrationDate(new \DateTime());
		$this->view->assign('persons', $this->personRepository->findAll());
		$this->view->assign('absence', $absence);
	}

	/**
	 * @param \Beech\Absence\Domain\Model\Absence $absence
	 * @return void
	 */
	public function editAction(\Beech\Absence\Domain\Model\Absence $absence) {

		$this->view->assign('absence', $absence);

		$this->view->assign('persons', $this->personRepository->findAll());
		$this->view->assign('absenceArrangements', $this->absenceArrangementRepository->findByAbsenceType($absence->getAbsenceType()));
	}

	/**
	 * @param \Beech\Absence\Domain\Model\Absence $absence
	 * @return void
	 */
	public function updateAction(\Beech\Absence\Domain\Model\Absence $absence) {
		$this->repository->update($absence);
		$options = array('absence' => $absence);
		$personIdentifier = $this->persistenceManager->getIdentifierByObject($absence->getPerson(), '\Beech\Party\Domain\Model\Person');

		$this->emberRedirect('#/person/show/' . $personIdentifier . '/' . uniqid());
	}
}
?>