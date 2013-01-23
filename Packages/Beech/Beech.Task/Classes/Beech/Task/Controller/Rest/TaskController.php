<?php
namespace Beech\Task\Controller\Rest;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-09-12 15:27
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Notification Rest controller for the Beech.Ehrm package
 *
 */
class TaskController extends \TYPO3\Flow\Mvc\Controller\RestController {

	/**
	 * @var \Beech\Task\Domain\Repository\TaskRepository
	 * @Flow\Inject
	 */
	protected $taskRepository;

	/**
	 * @var \Beech\Party\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * List all open tasks
	 *
	 * @return void
	 */
	public function listAction() {

		$tasks = $this->taskRepository->findAll();
		$priorities = array(0 => 'Low', 1 => 'Normal', 2 => 'High', 3 => 'Immediate');
		$this->view->assign(
			'priorities',
			$priorities
		);
		$this->view->assign(
			'tasks',
			$tasks
		);
	}

}

?>