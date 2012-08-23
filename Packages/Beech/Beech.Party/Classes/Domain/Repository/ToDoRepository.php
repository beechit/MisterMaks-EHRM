<?php
namespace Beech\Party\Domain\Repository;

/*                                                                        *
 * This script belongs to the FLOW3 package "Beech.Party".                *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * A repository for ToDo
 *
 * @FLOW3\Scope("singleton")
 */
class ToDoRepository extends \TYPO3\FLOW3\Persistence\Repository {

	/**
	 * @param string $controller
	 * @param string $action
	 * @param string $arguments serialized array of arguments
	 * @return void
	 */
	public function archiveTask($controller, $action, $arguments) {
		$query = $this->createQuery();

		$object = $query->matching(
			$query->logicalAnd(
				array(
					$query->equals('controller', $controller),
					$query->equals('action', $action),
					$query->equals('arguments', $arguments)
				)
			)
		)->execute()->getFirst();

		if (isset($object) && $object instanceof \Beech\Party\Domain\Model\ToDo) {
			$object->setArchived(TRUE);
			$this->update($object);
		}
	}

}
?>