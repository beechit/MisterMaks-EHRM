<?php
namespace Beech\Task\Aspect;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 1/13/13 11:29 AM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Aspect
 */
class WebSocketAspect {

	/**
	 * @var \Beech\Ehrm\Domain\Repository\NotificationRepository
	 * @Flow\Inject
	 */
	protected $notificationRepository;

	/**
	 * @Flow\Around("method(Beech\Socket\Server->pushMessage())")
	 * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint The current join point
	 * @return array
	 */
	public function addNotificationsToPushMessage(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		$currentResult = $joinPoint->getAdviceChain()->proceed($joinPoint);

		/**
		 * TODO: Fetch only notifications for current user, for this we need the session though which is not yet
		 * available in the websocket server
		 */
		$notifications = $this->notificationRepository->findAll();
		if (count($notifications) > 0) {
			$currentResult['notifications'] = array();

				// Send max 5 notifications per push
			$notifications = array_splice($notifications, 0, 5);
			foreach ($notifications as $notification) {
				$currentResult['notifications'][] = array('message' => $notification->getLabel());

					// TODO: do not delete if sticky / not closeable by user
				$this->notificationRepository->remove($notification);
			}

			$this->notificationRepository->flushDocumentManager();
		}

		return $currentResult;
	}

}

?>