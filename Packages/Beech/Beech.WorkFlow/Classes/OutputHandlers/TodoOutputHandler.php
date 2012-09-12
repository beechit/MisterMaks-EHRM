<?php
namespace Beech\WorkFlow\OutputHandlers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 03-09-2012 22:53
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Beech\Party\Domain\Model\ToDo as Todo;

/**
 * TodoOutputHandler persists a ToDo entity when invoked
 */
class TodoOutputHandler implements \Beech\WorkFlow\Core\OutputHandlerInterface {

	/**
	 * @var \Beech\Party\Domain\Repository\ToDoRepository
	 * @FLOW3\Inject
	 */
	protected $toDoRepository;

	/**
	 * The entity to persist
	 * @var \Beech\Party\Domain\Model\ToDo
	 */
	protected $toDoEntity;

	/**
	 * Set the entity to persist
	 * @param \Beech\Party\Domain\Model\ToDo $ToDo
	 */
	public function setToDoEntity(\Beech\Party\Domain\Model\ToDo $ToDo) {
		$this->toDoEntity = $ToDo;
	}

	/**
	 * Execute this output handler class, persisting a todo to the repository
	 * @return void
	 */
	public function invoke() {
		$this->toDoRepository->add($this->toDoEntity);
	}
}
?>