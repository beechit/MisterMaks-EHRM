<?php
namespace Beech\Task\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 07-08-12 15:39
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * To-Do controller for the Beech.Task package
 *
 * @Flow\Scope("singleton")
 */
class TaskController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \Beech\Task\Domain\Repository\TaskRepository
	 * @Flow\Inject
	 */
	protected $taskRepository;

	/**
	 * @var \Beech\Task\Domain\Factory\TaskFactory
	 * @Flow\Inject
	 */
	protected $taskFactory;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 * @Flow\Inject
	 */
	protected $personRepository;

	/**
	 * Shows a list of tasks
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->taskRepository->add($this->taskFactory->createTask());
		$this->view->assign('tasks', $this->taskRepository->findOpenTasks());
		$this->view->assign('priorities', array(0 => 'low', 1 => 'normal', 2 => 'high', 3 => 'immediate'));
	}

	/**
	 * Edit a task
	 *
	 * @param \Beech\Task\Domain\Model\Task $task
	 * @return void
	 */
	public function editAction(\Beech\Task\Domain\Model\Task $task) {
		$this->view->assign('task', $task);
		$this->view->assign('priorities', array(0 => 'low', 1 => 'normal', 2 => 'high', 3 => 'immediate'));
	}

	/**
	 * Update a task
	 *
	 * @param \Beech\Task\Domain\Model\Task $task
	 * @return void
	 */
	public function updateAction(\Beech\Task\Domain\Model\Task $task) {
		$this->taskRepository->update($task);
		$this->redirect('index');
	}

	/**
	 * Creates a task
	 *
	 * @param \Beech\Task\Domain\Model\Task $newTask
	 * @return void
	 */
	public function createAction(\Beech\Task\Domain\Model\Task $newTask) {
		if (is_object($this->securityContext->getAccount())
			&& $this->securityContext->getAccount()->getParty() instanceof \TYPO3\Party\Domain\Model\AbstractParty) {
				$newTask->setOwner($this->securityContext->getAccount()->getParty());
		}

		$this->taskRepository->add($newTask);
		$this->redirect('index');
	}

	/**
	 * A task is marked done
	 *
	 * @param \Beech\Task\Domain\Model\Task $task
	 * @return void
	 */
	public function setDoneAction(\Beech\Task\Domain\Model\Task $task) {
		$task->close();
		$this->taskRepository->update($task);
		$this->redirect('index');
	}

}

?>