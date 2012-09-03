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
	 * @var \Beech\Party\Domain\Repository\NotificationRepository
	 * @FLOW3\Inject
	 */
	protected $notificationRepository;

	/**
	 * @param \Beech\Party\Domain\Model\ToDo $toDo
	 * @return void
	 */
	public function archiveToDo(\Beech\Party\Domain\Model\ToDo $toDo) {
		$toDo->setArchived();

		$this->update($toDo);
		$this->notificationRepository->deleteByToDo($toDo);
	}

	/**
	 * @param $controllerName
	 * @param $controllerAction
	 * @param $controllerArgument
	 * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
	 */
	public function findByControllerActionAndArguments($controllerName, $controllerAction, $controllerArguments) {
		$query = $this->createQuery();
		$query->matching($query->logicalAnd($query->equals('controllerName', $controllerName),
											$query->equals('controllerAction', $controllerAction),
											$query->equals('controllerArguments', $controllerArguments)
											));
		return $query->execute()->getFirst();
	}
}
?>