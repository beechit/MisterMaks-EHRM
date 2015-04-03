<?php
namespace Beech\Task\Domain\Factory;

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
	public static function createTask($priority = NULL, $description = NULL, \TYPO3\Party\Domain\Model\AbstractParty $assignedTo = NULL, $closeableByAssignee = FALSE, \TYPO3\Party\Domain\Model\AbstractParty $createdBy = NULL) {
		$task = new Task();

		if ($priority !== NULL) {
			$task->setPriority($priority);
		}
		$task->setCloseableByAssignee($closeableByAssignee);
		if ($assignedTo !== NULL) {
			$task->setAssignedTo($assignedTo);
		}
		if ($createdBy !== NULL) {
			$task->setCreatedBy($createdBy);
		}
		$task->setCreationDateTime(new \DateTime());
		if ($description !== NULL) {
			$task->setDescription($description);
		}
		return $task;
	}

}

?>