<?php
namespace Beech\Task\Domain\Repository;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-07-12 13:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Task
 *
 * @Flow\Scope("singleton")
 */
class TaskRepository extends \Radmiraal\CouchDB\Persistence\AbstractRepository {

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $person
	 * @return integer
	 */
	public function countOpenTasksByPerson(\TYPO3\Party\Domain\Model\AbstractParty $person) {
		return count($this->findOpenTasksByPerson($person));
	}

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $person
	 * @return array
	 */
	public function findOpenTasksByPerson(\TYPO3\Party\Domain\Model\AbstractParty $person) {
		return $this->backend->findBy(array(
			'assignedTo' => $this->getQueryMatchValue($person),
			'closed' => FALSE
		));
	}

}

?>