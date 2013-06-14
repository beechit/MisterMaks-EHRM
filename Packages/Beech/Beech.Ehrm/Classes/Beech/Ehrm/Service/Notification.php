<?php
namespace Beech\Ehrm\Service;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 17-05-2013 15:33
 * All code (c) Beech Applications B.V. all rights reserved
 */

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
			$accountIntentifiers = array();

			/** @var $account \TYPO3\Flow\Security\Account */
			foreach($task->getAssignedTo()->getAccounts() as $account) {

				if(!$account->getAccountIdentifier()) {
					continue;
				}

				$notification = new \Beech\Ehrm\Domain\Model\Notification();
				$notification->setLevel(\Beech\Ehrm\Domain\Model\Notification::INFO);
				$notification->setLabel('Task added');
				$notification->setMessage('Task "'.$task->getDescription().'" created');
				$notification->setAccountIdentifier($account->getAccountIdentifier());

				$notificationRepository->add($notification);
				$notificationRepository->flushDocumentManager();

				$accountIntentifiers[] = $account->getAccountIdentifier();
			}

			// send signals to connected users
			if(count($accountIntentifiers)) {
				SendCommands::sendSignal('BeechTaskDomainModelTask:'.$task->getId(), $accountIntentifiers);
			}
		}
	}

	/**
	 * Send user a notification when a Task assigned to him is changed
	 *
	 * @param \Beech\Task\Domain\Model\Task $task
	 */
	public function taskChanged(\Beech\Task\Domain\Model\Task $task) {


		// don't notify about closed tasks
		if($task->isClosed()) return;

		// taks assign to person then send a notification
		if($task->getAssignedTo() instanceof \Beech\Party\Domain\Model\Person) {

			$notificationRepository = new \Beech\Ehrm\Domain\Repository\NotificationRepository();
			$accountIntentifiers = array();

			/** @var $account \TYPO3\Flow\Security\Account */
			foreach($task->getAssignedTo()->getAccounts() as $account) {

				if(!$account->getAccountIdentifier()) {
					continue;
				}

				$notification = new \Beech\Ehrm\Domain\Model\Notification();
				$notification->setLevel(\Beech\Ehrm\Domain\Model\Notification::INFO);
				$notification->setLabel('Task updated');
				$notification->setMessage('Task "'.$task->getDescription().'" is updated');
				$notification->setAccountIdentifier($account->getAccountIdentifier());

				$notificationRepository->add($notification);
				$notificationRepository->flushDocumentManager();

				$accountIntentifiers[] = $account->getAccountIdentifier();
			}

			// send signals to connected users
			if(count($accountIntentifiers)) {
				SendCommands::sendSignal('BeechTaskDomainModelTask:'.$task->getId(), $accountIntentifiers);
			}
		}
	}
}