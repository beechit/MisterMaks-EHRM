<?php
namespace Beech\Party\Controller\Management;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Pieter Geurts <pieter@aleto.nl>
 * Date: 07-08-12 15:39
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * To-Do controller for the Beech.Party package
 *
 * @FLOW3\Scope("singleton")
 */
class ToDoController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \Beech\Party\Domain\Repository\ToDoRepository
	 * @FLOW3\Inject
	 */
	protected $toDoRepository;

	/**
	 * Shows a list of todos
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->toDoRepository->setDefaultOrderings(array('priority' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING));
		$this->view->assign('todos', $this->toDoRepository->findAll());
	}
}

?>