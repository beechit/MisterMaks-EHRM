<?php
namespace Beech\Task\Domain\Repository;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-07-12 13:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Task
 *
 * @Flow\Scope("singleton")
 */
class TaskRepository extends \Radmiraal\CouchDB\Persistence\AbstractRepository {

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $person
	 * @return integer
	 */
	public function countOpenTasksByPerson(\TYPO3\Party\Domain\Model\AbstractParty $person) {
		return count($this->findOpenTasksByPerson($person));
	}


	/**
	 * @param array $filter
	 * @return array
	 */
	public function emberFindAll($filter = NULL) {

		$tasks = array();

		if(!is_array($filter)) {
			$filter = array();
		}
		// @todo: find a better alternative for this
		//        only show own not closed tasks
		//        EmberData doesn't recognise new elements when you don't call find() without parameters
		if(!array_key_exists('ids', $filter)) {
			$filter['ownTasks'] = TRUE;
			if(!array_key_exists('closed', $filter)) {
				$filter['closed'] = FALSE;
			}
		}

		if(array_key_exists('ids', $filter)) {
			foreach($filter['ids'] as $id) {
				$tasks[] = $this->findByIdentifier($id);
			}
		} elseif(count($filter)) {
			$tasks = $this->findBy($filter);
		} else {
			$tasks = $this->backend->findAll();
		}

		return $tasks;
	}

	/**
	 * @param $filter
	 * @return array
	 */
	protected function findBy($filter) {

		// map some custom filters
		foreach($filter as $key => $value) {
			switch($key) {
				case 'ownTasks':
					unset($filter['ownTasks']);
					$filter['assignedTo'] = $this->getCurrentPartyIdentifier();
					break;
				default:
					if(in_array($value, array('true','false'))) {
						$filter[$key] = $value == 'true' ? TRUE : FALSE;
					}
			}
		}

		return $this->backend->findBy($filter);
	}


	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $person
	 * @param integer $priority
	 * @return array
	 */
	public function findOpenTasksByPerson(\TYPO3\Party\Domain\Model\AbstractParty $person, $priority = NULL) {

		$filter = array(
			'assignedTo' => $this->getQueryMatchValue($person),
			'closed' => FALSE
		);

		$_tasks = $this->findBy($filter);
		$tasks = array();

		if($priority !== NULL) {
			foreach($_tasks as $task) {
				if((int)$task->getPriority() === (int)$priority) {
					$tasks[] = $task;
				}
			}
		} else {
			$tasks = $_tasks;
		}

		return $tasks;
	}

	/**
	 * Find all open tasks
	 * @return array
	 */
	public function findOpenTasks() {
		return $this->backend->findBy(array(
			'closed' => FALSE
		));
	}

	/**
	 * @param \Beech\Task\Domain\Model\Task $task
	 * @return void
	 * @Flow\Signal
	 */
	protected function emitTaskCreated(\Beech\Task\Domain\Model\Task $task) {}

	/**
	 * @param \Beech\Task\Domain\Model\Task $task
	 * @return void
	 * @Flow\Signal
	 */
	protected function emitTaskChanged(\Beech\Task\Domain\Model\Task $task) {}

	/**
	 * @param \Beech\Task\Domain\Model\Task $task
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 * @return void
	 */
	public function add($task) {
		parent::add($task);

		$this->emitTaskCreated($task);
	}

	/**
	 * @param \Beech\Task\Domain\Model\Task $task
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 * @return void
	 */
	public function update($task) {
		parent::update($task);

		$this->emitTaskChanged($task);
	}

	/**
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	protected function getCurrentParty() {
		if (isset($this->securityContext)
			&& $this->securityContext->getAccount() instanceof \TYPO3\Flow\Security\Account
			&& $this->securityContext->getAccount()->getParty() instanceof \Beech\Party\Domain\Model\Person) {
			return $this->securityContext->getAccount()->getParty();
		}
		return NULL;
	}

	/**
	 * @return string
	 */
	protected function getCurrentPartyIdentifier() {
		$currentParty = $this->getCurrentParty();

		if ($currentParty !== NULL) {
			return $this->persistenceManager->getIdentifierByObject($currentParty);
		}
		return NULL;
	}
}

?>