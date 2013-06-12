<?php
namespace Beech\Task\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 07-05-2013 10:22
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Task\Domain\Model\Task as Task;
use TYPO3\Flow\Http\Message;
use TYPO3\Flow\Security\Exception;

class TaskController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \Beech\Task\Domain\Repository\TaskRepository
	 * @Flow\Inject
	 */
	protected $taskRepository;

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
		$this->view->assign('tasks', $this->taskRepository->findOpenTasksByPerson($this->getPerson()));
	}

	/**
	 * @return void
	 */
	public function newAction() {

			// by default assign to loggedin user
		$task = new \Beech\Task\Domain\Model\Task();
		$task->setAssignedTo($this->getPerson());

		$this->view->assign('task', $task);
		$this->view->assign('priorities', $this->getPriorityOptions());
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
		$this->emberRedirect('#/tasks');
	}

	/**
	 * Shows a single task object
	 *
	 * @param \Beech\Task\Domain\Model\Task $task The task to show
	 * @Flow\IgnoreValidation("$task")
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
		$this->emberRedirect('#/tasks');
	}

	/**
	 * Shows a form for editing an existing task object
	 *
	 * @param \Beech\Task\Domain\Model\Task $task The task to edit
	 * @Flow\IgnoreValidation("$task")
	 * @return void
	 */
	public function editAction(Task $task) {
		$this->view->assign('priorities', $this->getPriorityOptions());
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
		try{
			$task->close();
			$this->addFlashMessage('Closed the task');
			$this->taskRepository->update($task);
		} catch(\Beech\Task\Exception $exception) {
			$this->addFlashMessage($exception->getMessage(), '', \TYPO3\Flow\Error\Message::SEVERITY_ERROR);
		}
		$this->emberRedirect('#/tasks');
	}

	/**
	 * Get current loggedin user
	 *
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 * @throws \TYPO3\Flow\Security\Exception
	 */
	protected function getPerson() {

			// @todo make this nice
		if(!$this->securityContext->getAccount()) {
			throw new Exception('Not logged in');
		}

		return $this->securityContext->getAccount()->getParty();
	}

	/**
	 * Get priority options
	 *
	 * @return array
	 */
	protected function getPriorityOptions() {
		return array(
			0 => 'task.priority.0',
			1 => 'task.priority.1',
			2 => 'task.priority.2',
			3 => 'task.priority.3',
		);
	}
}