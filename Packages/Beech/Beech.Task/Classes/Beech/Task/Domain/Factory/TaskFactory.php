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
	 * @return Beech\Task\Domain\Model\Task $task
	 */
	public function createTask() {
		$task = new Task();

		$task->setPriority(1);
		$task->setCloseableByAssignee(TRUE);
		$task->setCreationDateTime(new \DateTime);
		$task->setDescription('A New Task');
		return $task;
	}

}

?>