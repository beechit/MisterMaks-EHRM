<?php
namespace Beech\Workflow\OutputHandlers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 31-05-2013 16:22
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;


class TaskOutputHandler extends \Beech\Workflow\Core\OutputHandlerAbstract implements \Beech\Workflow\Core\OutputHandlerInterface {

	/**
	 * @var \Beech\Task\Domain\Repository\TaskRepository
	 * @Flow\Inject
	 */
	protected $taskRepository;

	/**
	 * The task description
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * Priority of this task 0-3
	 *
	 * @var integer
	 */
	protected $priority;

	/**
	 * The task owner
	 *
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 */
	protected $assignedTo;

	/**
	 * Set assignedTo
	 *
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $assignedTo
	 */
	public function setAssignedTo($assignedTo) {
		$this->assignedTo = $assignedTo;
	}

	/**
	 * Set description
	 *
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Sets the priority, accepts 0-3
	 *
	 * @param integer $priority
	 * @return void
	 */
	public function setPriority($priority) {
		$this->priority = $priority;
	}

	/**
	 * Execute the output handler class
	 *
	 * @return mixed
	 */
	public function invoke() {
		$task = new \Beech\Task\Domain\Model\Task();
		$task->setAssignedTo($this->assignedTo);
		$task->setDescription($this->description);
		if($this->priority !== NULL) {
			$task->setPriority($this->priority);
		}

		$this->taskRepository->add($task);
	}

}