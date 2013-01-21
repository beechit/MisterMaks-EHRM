<?php
namespace Beech\Ehrm\Tests\Functional\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 28-08-12 17:23
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Persistence test for Notification entity
 */
class NotificationTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var \Beech\Ehrm\Domain\Repository\NotificationRepository
	 */
	protected $notificationRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\CompanyRepository
	 */
	protected $companyRepository;

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->notificationRepository = $this->objectManager->get('Beech\Ehrm\Domain\Repository\NotificationRepository');
		$this->notificationRepository->injectDocumentManagerFactory($this->documentManagerFactory);
		$this->companyRepository = $this->objectManager->get('Beech\Party\Domain\Repository\CompanyRepository');
	}

	/**
	 * @return array
	 */
	public function notificationDataProvider() {
		return array(
			array('Closeable TRUE, Sticky FALSE', TRUE, FALSE),
			array('Closeable TRUE, Sticky TRUE', TRUE, TRUE),
			array('Closeable FALSE, Sticky TRUE', FALSE, TRUE),
			array('Closeable FALSE, Sticky FALSE', FALSE, FALSE)
		);
	}

	/**
	 * Simple test for persistence a notification
	 *
	 * @dataProvider notificationDataProvider
	 * @test
	 */
	public function notificationPersistenceAndRetrievalWorksCorrectly($label, $closeable, $sticky) {
		$company = new \Beech\Party\Domain\Model\Company();
		$company->setName('Foo');
		$this->companyRepository->add($company);

		$notification = new \Beech\Ehrm\Domain\Model\Notification();
		$notification->setLabel($label);
		$notification->setParty($company);
		$notification->setCloseable($closeable);
		$notification->setSticky($sticky);

		$this->notificationRepository->add($notification);
		$this->documentManager->flush();

		$this->assertEquals(1, $this->notificationRepository->countAll());

		$notifications = $this->notificationRepository->findAll();

		$this->assertEquals('Foo', $notifications[0]->getParty()->getName());
		$this->assertEquals($label, $notifications[0]->getLabel());
		$this->assertEquals($closeable, $notifications[0]->getCloseable());
		$this->assertEquals($sticky, $notifications[0]->getSticky());
	}

}

?>