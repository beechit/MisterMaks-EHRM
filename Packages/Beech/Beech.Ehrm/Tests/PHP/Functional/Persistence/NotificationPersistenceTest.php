<?php
namespace Beech\Ehrm\Tests\Functional\Persistence;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 28-08-12 17:23
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Persistence test for Notification entity
 */
class NotificationPersistenceTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Ehrm\Domain\Repository\NotificationRepository
	 */
	protected $notificationRepository;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->notificationRepository = $this->objectManager->get('Beech\Ehrm\Domain\Repository\NotificationRepository');
	}

	/**
	 * @return array
	 */
	public function notificationDataProvider() {
		$person = new \Beech\Party\Domain\Model\Person();
		$person->setName(new \Beech\Party\Domain\Model\PersonName('', 'John', 'Doe'));

		return array(
			array('Closeable TRUE, Sticky FALSE', $person, TRUE, FALSE),
			array('Closeable TRUE, Sticky TRUE', $person, TRUE, TRUE),
			array('Closeable FALSE, Sticky TRUE', $person, FALSE, TRUE),
			array('Closeable FALSE, Sticky FALSE', $person, FALSE, FALSE)
		);
	}

	/**
	 * Simple test for persistence a notification
	 *
	 * @dataProvider notificationDataProvider
	 * @test
	 */
	public function notificationPerstinceAndRetrievalWorksCorrectly($label, \TYPO3\Party\Domain\Model\AbstractParty $party, $closeable, $sticky) {
		$notification = new \Beech\Ehrm\Domain\Model\Notification();
		$notification->setLabel($label);
		$notification->setParty($party);
		$notification->setCloseable($closeable);
		$notification->setSticky($sticky);

		$this->notificationRepository->add($notification);

		$this->persistenceManager->persistAll();

		$this->assertEquals(1, $this->notificationRepository->countAll());

		$notification = $this->notificationRepository->findAll()->getFirst();

		$this->assertEquals('John Doe', $notification->getParty()->getName()->getFullName());
		$this->assertEquals($label, $notification->getLabel());
		$this->assertEquals($closeable, $notification->getCloseable());
		$this->assertEquals($sticky, $notification->getSticky());

		$this->persistenceManager->clearState();
	}
}

?>