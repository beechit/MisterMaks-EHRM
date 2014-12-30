<?php
namespace Beech\Ehrm\Service;

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

use Beech\Socket\Service\SendCommands;

/**
 * Class Notification Service
 *
 * Slots that get triggerd and check if a
 * Notification needs to be created
 *
 * @package Beech\Ehrm\Service
 */
class Notification {

	/**
	 * Send user a notification when a new Task assigned to him is created
	 *
	 * @param \Beech\Task\Domain\Model\Task $task
	 */
	public function taskCreated(\Beech\Task\Domain\Model\Task $task) {

		// taks assign to person then send a notification
		if($task->getAssignedTo() instanceof \Beech\Party\Domain\Model\Person) {

			$notificationRepository = new \Beech\Ehrm\Domain\Repository\NotificationRepository();
			$accountIdentifiers = array();
			$notificationCreated = FALSE;

			/** @var $account \TYPO3\Flow\Security\Account */
			foreach($task->getAssignedTo()->getAccounts() as $account) {

				if(!$account->getAccountIdentifier()) {
					continue;
				}

				if (!$notificationCreated) {
					$notification = new \Beech\Ehrm\Domain\Model\Notification();
					$notification->setLevel(\Beech\Ehrm\Domain\Model\Notification::INFO);
					$notification->setLabel('Task added');
					$notification->setSticky(TRUE);
					$notification->setMessage('Task "'.$task->getDescription().'" created');
					$notification->setPerson($task->getAssignedTo());

					$notificationRepository->add($notification);
					$notificationRepository->flushDocumentManager();
					$notificationCreated = TRUE;
				}

				$accountIdentifiers[] = $account->getAccountIdentifier();
			}

			// send signals to connected users
			if(count($accountIdentifiers)) {
				SendCommands::sendSignal('BeechTaskDomainModelTask:'.$task->getId(), $accountIdentifiers);
			}
		}
	}

	/**
	 * Send user a notification when a Task assigned to him is changed
	 *
	 * @param \Beech\Task\Domain\Model\Task $task
	 */
	public function taskChanged(\Beech\Task\Domain\Model\Task $task) {

		// taks assign to person then send a notification
		if($task->getAssignedTo() instanceof \Beech\Party\Domain\Model\Person) {

			$notificationRepository = new \Beech\Ehrm\Domain\Repository\NotificationRepository();
			$accountIdentifiers = array();
			$notificationCreated = FALSE;

			/** @var $account \TYPO3\Flow\Security\Account */
			foreach($task->getAssignedTo()->getAccounts() as $account) {

				if(!$account->getAccountIdentifier()) {
					continue;
				}

				// don't notify about closed tasks
				if (!$task->isClosed() && !$notificationCreated) {
					$notification = new \Beech\Ehrm\Domain\Model\Notification();
					$notification->setLevel(\Beech\Ehrm\Domain\Model\Notification::INFO);
					$notification->setLabel('Task updated');
					$notification->setMessage('Task "'.$task->getDescription().'" is updated');
					$notification->setPerson($task->getAssignedTo());

					$notificationRepository->add($notification);
					$notificationRepository->flushDocumentManager();
					$notificationCreated = TRUE;
				}

				$accountIdentifiers[] = $account->getAccountIdentifier();
			}

			// send signals to connected users
			if(count($accountIdentifiers)) {
				SendCommands::sendSignal('BeechTaskDomainModelTask:'.$task->getId(), $accountIdentifiers);
			}
		}
	}
}