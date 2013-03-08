<?php
namespace Beech\Task\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-08-12 10:24
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Task Model
 *
 * @ODM\Document(indexed=true)
 */
class Priority extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * The priority label
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $label;

	/**
	 * The tasks
	 *
	 * @var array
	 * @ODM\Field(type="mixed")
	 */
	protected $tasks = array();

	/**
	 * Sets the priority label
	 *
	 * @param string $label
	 * @return void
	 */
	public function setLabel($label) {
		$this->label = $label;
	}

	/**
	 * Return the priority label
	 *
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * Add task to tasks
	 *
	 * @param Task $task
	 */
	public function addTask(\Beech\Task\Domain\Model\Task $task) {
		$this->tasks['tasks'][] = $task->getId();
	}

	/**
	 * Add task to tasks
	 *
	 * @param Task $task
	 */
	public function removeTask(\Beech\Task\Domain\Model\Task $task) {
		unset($this->tasks['tasks'][array_search($task->getId(), $this->tasks['tasks'])]);
	}

	/**
	 * Gets the tasks
	 *
	 * @return array
	 */
	public function getTasks() {
		if (isset($this->tasks['tasks']) && is_array($this->tasks['tasks'])) {
			return $this->tasks['tasks'];
		}
		return array();
	}

	/**
	 * Sets the tasks
	 *
	 * @param array $tasks
	 */
	public function setTasks(array $tasks) {
		$this->tasks['tasks'] = $tasks;
	}

}

?>