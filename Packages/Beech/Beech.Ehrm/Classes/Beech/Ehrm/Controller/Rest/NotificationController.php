<?php
namespace Beech\Ehrm\Controller\Rest;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-09-12 15:27
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Notification Rest controller for the Beech.Ehrm package
 *
 */
class NotificationController extends \TYPO3\Flow\Mvc\Controller\RestController {

	/**
	 * @var \Beech\Ehrm\Domain\Repository\NotificationRepository
	 * @Flow\Inject
	 */
	protected $notificationRepository;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * @var \Beech\Party\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @return mixed
	 */
	public function listAction() {
		$currentParty = $this->securityContext->getAccount()->getParty();
		if (!$currentParty instanceof \TYPO3\Party\Domain\Model\AbstractParty) {
			return json_encode((object)array('result' => (object) array('status' => 'error')));
		}

		$notifications = $this->notificationRepository->findByParty($currentParty);
		$notificationArray = array();
		foreach ($notifications as $notification) {
			$labelTranslated = $this->translator->translateId('notification.' . $notification->getLabel());
			$notification->setLabel($labelTranslated);
			$notificationArray[] = $notification;
		}

		$this->view->assign(
			'notifications',
			$notificationArray
		);
	}

	/**
	 * @param \Beech\Ehrm\Domain\Model\Notification $resource
	 * @return string
	 */
	public function deleteAction(\Beech\Ehrm\Domain\Model\Notification $resource) {
		$currentParty = $this->securityContext->getAccount()->getParty();
		if (!$currentParty instanceof \TYPO3\Party\Domain\Model\AbstractParty
				|| $currentParty !== $resource->getParty()) {
			return json_encode((object) array('result' => (object) array('status' => 'error')));
		}

		$this->notificationRepository->remove($resource);

		return json_encode((object) array('result' => (object) array('status' => 'success')));
	}
}

?>