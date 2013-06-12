<?php
namespace Beech\Task\Domain\Factory;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-08-12 10:24
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Task\Domain\Model\Task;

/**
 * @Flow\Scope("singleton")
 */
class TaskFactory {

	/**
	 * This function creates a model object for the task model.
	 *
	 * @param integer $priority
	 * @param string $description
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $assignedTo
	 * @param boolean $closeableByAssignee
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $createdBy
	 * @return \Beech\Task\Domain\Model\Task
	 */
	public static function createTask($priority, $description, \TYPO3\Party\Domain\Model\AbstractParty $assignedTo = NULL, $closeableByAssignee = FALSE, \TYPO3\Party\Domain\Model\AbstractParty $createdBy = NULL) {
		$task = new Task();
		$task->setPriority($priority);
		$task->setCloseableByAssignee($closeableByAssignee);
		if ($assignedTo !== NULL) {
			$task->setAssignedTo($assignedTo);
		}
		if ($createdBy !== NULL) {
			$task->setCreatedBy($createdBy);
		}
		$task->setCreationDateTime(new \DateTime());
		$task->setDescription($description);
		return $task;
	}

}

?>