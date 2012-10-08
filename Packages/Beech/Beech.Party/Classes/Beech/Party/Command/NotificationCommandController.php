<?php
namespace Beech\Party\Command;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-09-12 23:17
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * TODO command controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class NotificationCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \Beech\Party\Domain\Repository\NotificationRepository
	 * @Flow\Inject
	 */
	protected $notificationRepository;

	/**
	 * @param string $label
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $party
	 * @param boolean $closeable
	 * @param boolean $sticky
	 * @return void
	 */
	public function createCommand($label, \TYPO3\Party\Domain\Model\AbstractParty $party, $closeable = TRUE, $sticky = FALSE) {
		$notification = new \Beech\Party\Domain\Model\Notification();
		$notification->setLabel($label);
		$notification->setParty($party);
		$notification->setCloseable($closeable);
		$notification->setSticky($sticky);

		$this->notificationRepository->add($notification);
		$this->outputLine('Notification created for "%s"', array($party->getName()->getFullName()));
	}

}

?>