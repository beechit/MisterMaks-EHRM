<?php
namespace Beech\WorkFlow\OutputHandlers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 03-09-2012 22:53
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\ToDo as Todo;

/**
 * TodoOutputHandler persists a ToDo entity when invoked
 */
class TodoOutputHandler implements \Beech\WorkFlow\Core\OutputHandlerInterface {

	/**
	 * @var \Beech\Party\Domain\Repository\ToDoRepository
	 * @Flow\Inject
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
	 * @return \Beech\Party\Domain\Model\ToDo
	 */
	public function getToDoEntity() {
		return $this->toDoEntity;
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