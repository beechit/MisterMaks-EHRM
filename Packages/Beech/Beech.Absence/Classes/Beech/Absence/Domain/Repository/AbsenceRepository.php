<?php
namespace Beech\Absence\Domain\Repository;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 09:52
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Absences
 *
 * @Flow\Scope("singleton")
 */
class AbsenceRepository extends \Radmiraal\CouchDB\Persistence\AbstractRepository {

	/**
	 * Get absence history based on person and absenceType
	 *
	 * @param $person
	 * @param $absenceType
	 * @return array
	 */
	public function findByPersonAndType($person, $absenceType) {
		$filter = array(
			'person' => $this->getQueryMatchValue($person),
			'absenceType' => $absenceType
		);
		return $this->backend->findBy($filter);
	}

	/**
	 * Get absence history bases on department and absenceType
	 *
	 * @param \Beech\Party\Domain\Model\Company $deparment
	 * @param string $absenceType
	 * @return array
	 *
	 * @todo make this a real couchDB query
	 */
	public function findByDepartmentAndType(\Beech\Party\Domain\Model\Company $deparment, $absenceType) {

		$filter = array(
			'absenceType' => $absenceType
		);

		$personIds = array();
		$absences = array();

		foreach ($deparment->getEmployees() as $person) {
			$personIds[] = $person->getId();
		};

		if ($personIds) {
			foreach ($this->backend->findBy($filter) as $absence) {
				if (in_array($absence->getPerson()->getId(), $personIds)) {
					$absences[] = $absence;
				}
			}
		}
		return $absences;
	}
}

?>