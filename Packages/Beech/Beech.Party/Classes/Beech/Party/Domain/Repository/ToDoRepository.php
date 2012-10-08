<?php
namespace Beech\Party\Domain\Repository;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-07-12 13:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for ToDo
 *
 * @Flow\Scope("singleton")
 */
class ToDoRepository extends \TYPO3\Flow\Persistence\Repository {

	/**
	 * @var \Beech\Party\Domain\Repository\NotificationRepository
	 * @Flow\Inject
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
	 * @param string $controllerName
	 * @param string $controllerAction
	 * @param string $controllerArguments
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findOneByControllerActionAndArguments($controllerName, $controllerAction, $controllerArguments) {
		$query = $this->createQuery();
		$query->matching($query->logicalAnd(
			$query->equals('controllerName', $controllerName),
			$query->equals('controllerAction', $controllerAction),
			$query->equals('controllerArguments', $controllerArguments)
		));
		return $query->execute()->getFirst();
	}
}

?>