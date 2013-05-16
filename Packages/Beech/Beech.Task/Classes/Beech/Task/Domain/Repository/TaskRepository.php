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
	 * @param array $filter
	 * @return array
	 */
	public function emberFindAll($filter = NULL) {

		$tasks = array();

		if(is_array($filter) && array_key_exists('ids', $filter)) {

			foreach($filter['ids'] as $id) {
				$tasks[] = $this->findByIdentifier($id);
			}

		} elseif(count($filter)) {

			$tasks = $this->backend->findBy($filter);
		} else {
			$tasks = $this->backend->findAll();
		}

		return $tasks;
	}

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $person
	 * @return array
	 */
	public function findOpenTasksByPerson(\TYPO3\Party\Domain\Model\AbstractParty $person, \Beech\Task\Domain\Model\Priority $priority = null) {

		$filter = array(
			'assignedTo' => $this->getQueryMatchValue($person),
			'closed' => FALSE
		);

		$_tasks = $this->backend->findBy($filter);
		$tasks = array();

		if($priority !== null) {
			foreach($_tasks as $task) {
				if($task->getPriority() && $task->getPriority()->getId() === $priority->getId()) {
					$tasks[] = $task;
				}
			}
		} else {
			$tasks = $_tasks;
		}

		return $tasks;
	}

	/**
	 * Find all open tasks
	 * @return array
	 */
	public function findOpenTasks() {
		return $this->backend->findBy(array(
			'closed' => FALSE
		));
	}
}

?>