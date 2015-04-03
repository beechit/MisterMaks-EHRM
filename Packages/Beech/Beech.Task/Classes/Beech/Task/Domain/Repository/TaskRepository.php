<?php
namespace Beech\Task\Domain\Repository;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

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

		if (array_key_exists('ids', $filter)) {
			foreach ($filter['ids'] as $id) {
				$tasks[] = $this->findByIdentifier($id);
			}
		} elseif (count($filter)) {
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
		foreach ($filter as $key => $value) {
			switch ($key) {
				case 'ownTasks':
					unset($filter['ownTasks']);
					$filter['assignedTo'] = $this->getCurrentPartyIdentifier();
					break;
				default:
					if (in_array($value, array('true', 'false'))) {
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

		if ($priority !== NULL) {
			foreach ($_tasks as $task) {
				if ((int)$task->getPriority() === (int)$priority) {
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
	 *
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
	protected function emitTaskCreated(\Beech\Task\Domain\Model\Task $task) {
	}

	/**
	 * @param \Beech\Task\Domain\Model\Task $task
	 * @return void
	 * @Flow\Signal
	 */
	protected function emitTaskChanged(\Beech\Task\Domain\Model\Task $task) {
	}

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
			&& $this->securityContext->getAccount()->getParty() instanceof \Beech\Party\Domain\Model\Person
		) {
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