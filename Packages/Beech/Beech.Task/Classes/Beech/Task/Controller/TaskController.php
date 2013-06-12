<?php
namespace Beech\Task\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 07-05-2013 10:22
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Task\Domain\Model\Task as Task;

class TaskController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \Beech\Task\Domain\Repository\TaskRepository
	 * @Flow\Inject
	 */
	protected $taskRepository;

	/**
	 * @var \Beech\Task\Domain\Repository\PriorityRepository
	 * @Flow\Inject
	 */
	protected $priorityRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 * @Flow\Inject
	 */
	protected $personRepository;

	/**
	 * @var \TYPO3\Flow\Mvc\FlashMessageContainer
	 * @Flow\Inject
	 */
	protected $flashMessageContainer;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $securityContext;

	/**
	 * @return void
	 */
	public function listAction() {
		if ($this->securityContext->getAccount()) {
			$this->view->assign('tasks', $this->taskRepository->findOpenTasksByPerson($this->securityContext->getAccount()->getParty()));
		}
	}

	/**
	 * @return void
	 */
	public function newAction() {

			// by default assign to loggedin user
		$task = new \Beech\Task\Domain\Model\Task();
		$task->setAssignedTo($this->securityContext->getAccount()->getParty());

		$this->view->assign('task', $task);
		$this->view->assign('priorities', $this->priorityRepository->findAll());
		$this->view->assign('persons', $this->personRepository->findAll());
	}

	/**
	 * Adds the given new task object to the task repository
	 *
	 * @param \Beech\Task\Domain\Model\Task $task A new task to add
	 * @return void
	 */
	public function createAction(Task $task) {
		$this->taskRepository->add($task);
		$this->addFlashMessage('Created a new task for "' . $task->getAssignedTo()->getName()->getFullname() . '"');
		$this->redirect('list');
	}

	/**
	 * Shows a single task object
	 *
	 * @param \Beech\Task\Domain\Model\Task $task The task to show
	 * @return void
	 */
	public function showAction(Task $task) {
		$this->view->assign('task', $task);
	}

	/**
	 * Updates the given task object
	 *
	 * @param \Beech\Task\Domain\Model\Task $task The task to update
	 * @return void
	 */
	public function updateAction(Task $task) {
		$this->taskRepository->update($task);
		$this->addFlashMessage('Updated the task');
		$this->redirect('list');
	}

	/**
	 * Shows a form for editing an existing task object
	 *
	 * @param \Beech\Task\Domain\Model\Task $task The task to edit
	 * @Flow\IgnoreValidation("$task")
	 * @return void
	 */
	public function editAction(Task $task) {
		$this->view->assign('priorities', $this->priorityRepository->findAll());
		$this->view->assign('persons', $this->personRepository->findAll());
		$this->view->assign('task', $task);
	}

	/**
	 * Close the given task object
	 *
	 * @param \Beech\Task\Domain\Model\Task $task The task to close
	 * @return void
	 */
	public function closeAction(Task $task) {

		$task->close();
		$this->addFlashMessage('Closed the task');
		$this->taskRepository->update($task);
		$this->redirect('list');
	}
}