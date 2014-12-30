<?php
namespace Beech\Task\Command;

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
 * Server command controller for the Beech.Task package
 *
 * @todo: move this to utility class?
 * And trigger this as cron/maintenance from central place
 *
 * @Flow\Scope("singleton")
 */
class TaskCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \Beech\Task\Domain\Repository\TaskRepository
	 * @Flow\Inject
	 */
	protected $taskRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Log\SystemLoggerInterface
	 */
	protected $logger;

	/**
	 * Output info to console
	 * @var boolean
	 */
	protected $verbose;

	/**
	 * Escalate task
	 *
	 * @param string $taskId task identifier
	 * @return void
	 */
	public function escalateTaskCommand($taskId) {

		$this->verbose = TRUE;

		/** @var $task \Beech\Task\Domain\Model\Task */
		$task = $this->taskRepository->findByIdentifier($taskId);

		if(!$task) {
			$this->outputLine('Task not found');
		} elseif($task->isEscalated()) {
			$this->outputLine('Task already escalated');
		} elseif($task->isClosed()) {
			$this->outputLine('Task already closed/finished');
		} elseif($task->escalate()) {
			$this->output('Task is escalated to %s', $task->getEscalatedTask()->getAssignedTo()->getName()->getFullName());
		} else {
			$this->outputLine('Couldn\'t escalate task');
		}
	}

	/**
	 * Check tasks for escalation
	 *
	 * @param boolean $verbose output info to console
	 * @return void
	 */
	public function escalateCommand($verbose = FALSE) {

		$this->verbose = $verbose;
		$now = new \DateTime();
		$tasksProcessed = 0;

		$this->outputLine('Search for tasks that need to be escalated');

		/** @var $task \Beech\Task\Domain\Model\Task */
		foreach($this->taskRepository->findOpenTasks() as $task) {
			if(!$task->isEscalated() && $task->getAssignedTo() && $task->getEscalationDateTime()) {

				if($task->getEscalationDateTime() < $now) {

					$this->outputLine('Escalate task %s for %s', array($task->getDescription(), $task->getAssignedTo()->getName()->getFullName()));

					if(!$task->escalate()) {
						$this->logger->log(vprintf('Couldn\'t escalate task $s', array($task->getId())), LOG_ERR);
						$this->outputLine('Failed to escalate task %s', array($task->getId()));
					} else {
						$this->taskRepository->update($task);
					}
					$tasksProcessed++;
				} else {
					$this->outputLine('Task [%s] will be escalated on %s', array($task->getId(), $task->getEscalationDateTime()->formar('Y-m-d H:i')));
				}
			}
		}

		if($tasksProcessed == 0) {
			$this->outputLine('No tasks processed');
		}
	}

	/**
	 * Check all tasks and increase priority when needed
	 *
	 * @param boolean $verbose output info to console
	 * @return void
	 */
	public function increasePriorityCommand($verbose = FALSE) {

		$this->verbose = $verbose;
		$now = new \DateTime();
		$tasksProcessed = 0;

		$this->outputLine('Search for tasks where priority needs to be increased');

		/** @var $task \Beech\Task\Domain\Model\Task */
		foreach($this->taskRepository->findOpenTasks() as $task) {
			if($task->getNextPriorityIncreaseDateTime()) {
				if($task->getNextPriorityIncreaseDateTime() < $now) {
					$oldPriority = $task->getPriority();
					$task->increasePriority();
					if($oldPriority != $task->getPriority()) {
						$this->outputLine('Increase priority of task %s for %s from %d to %d', array($task->getDescription(), $task->getAssignedTo()->getName()->getFullName(), $oldPriority, $task->getPriority()));
						$this->logger->log(vprintf('Increase priority of task %s for %s from %d to %d', array($task->getDescription(), $task->getAssignedTo()->getName()->getFullName(), $oldPriority, $task->getPriority())), LOG_INFO);

						$this->taskRepository->update($task);
						$tasksProcessed++;
					} else {
						$this->outputLine('No priority increase so skipping...');
					}
				} else {
					$this->outputLine('Priority of task [%s] will be raised on %s', array($task->getId(), $task->getEscalationDateTime()->formar('Y-m-d H:i')));
				}
			}
		}

		if($tasksProcessed == 0) {
			$this->outputLine('No tasks processed');
		}
	}

	/**
	 * Check tasks for escalation and priority inceases
	 * triggers: task:escalate and task:increasepriority
	 *
	 * @param boolean $verbose output info to console
	 * @return void
	 */
	public function maintenanceCommand($verbose = FALSE) {
		$this->escalateCommand($verbose);
		$this->increasePriorityCommand($verbose);
	}

	/**
	 * Outputs specified text to the console window
	 * You can specify arguments that will be passed to the text via sprintf
	 * @see http://www.php.net/sprintf
	 *
	 * Only outputs content when $this->verbose is set
	 *
	 * @param string $text Text to output
	 * @param array $arguments Optional arguments to use for sprintf
	 * @return void
	 */
	protected function output($text, array $arguments = array()) {
		if($this->verbose) {
			parent::output($text, $arguments);
		}
	}
}