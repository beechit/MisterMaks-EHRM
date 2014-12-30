<?php
namespace Beech\Absence\Domain\Repository;

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
	 * Get absence history based on person and absenceType
	 *
	 * @param $person
	 * @param $absenceType
	 * @return array
	 */
	public function findActiveAbsence($person) {
		$absences = $this->findByPersonAndType($person, \Beech\Absence\Domain\Model\Absence::OPTION_SICKNESS);
		foreach ($absences as $index => $absence) {
			if ($absence->getEndDate() != NULL) {
				unset($absences[$index]);
			}
		}
		return $absences;
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