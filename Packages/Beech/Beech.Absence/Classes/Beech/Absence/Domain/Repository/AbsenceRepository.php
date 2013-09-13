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
}

?>