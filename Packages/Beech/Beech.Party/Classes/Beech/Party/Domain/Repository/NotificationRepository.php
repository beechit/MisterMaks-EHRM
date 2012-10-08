<?php
namespace Beech\Party\Domain\Repository;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-09-12 12:26
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Notification
 *
 * @Flow\Scope("singleton")
 */
class NotificationRepository extends \TYPO3\Flow\Persistence\Repository {

	/**
	 * Deletes notifications from a given to-do
	 *
	 * @param \Beech\Party\Domain\Model\ToDo $toDo
	 */
	public function deleteByToDo(\Beech\Party\Domain\Model\ToDo $toDo) {
		$notificationObjects = $this->findByToDo($toDo);
		foreach ($notificationObjects as $notificationObject) {
			$this->remove($notificationObject);
		}
	}
}

?>