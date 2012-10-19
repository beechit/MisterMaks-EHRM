<?php
namespace Beech\Calendar\Tests\Functional;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 15-10-12 17:23
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use \Beech\Absence\Domain\Model\Absence as Absence;

/**
 * Persistence test for Absence entity
 */
class AbsencePersistenceTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Calendar\Domain\Repository\AbsenceRepository
	 */
	protected $absenceRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 */
	protected $personRepository;

	/**
	 * @var \TYPO3\Flow\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @var \TYPO3\Flow\Security\AccountFactory
	 */
	protected $accountFactory;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->absenceRepository = $this->objectManager->get('Beech\Absence\Domain\Repository\AbsenceRepository');
		$this->personRepository = $this->objectManager->get('Beech\Party\Domain\Repository\PersonRepository');
		$this->accountRepository = $this->objectManager->get('TYPO3\Flow\Security\AccountRepository');
		$this->accountFactory = $this->objectManager->get('TYPO3\Flow\Security\AccountFactory');
	}

	/**
	 * Simple test for Absence persistence
	 *
	 * @test
	 */
	public function absenceCanBePersistedAndRetrieved() {
		$createUser = new \Beech\Party\Domain\Model\Person();
		$createUser->addPersonName(new \Beech\Party\Domain\Model\PersonName('', 'Bram', '', 'Verhaegh'));
		$createUser->addEmail('joe@initiator.nl');
		$this->personRepository->add($createUser);

		$personSubject = new \Beech\Party\Domain\Model\Person();
		$personSubject->addPersonName(new \Beech\Party\Domain\Model\PersonName('', 'Edward', '', 'Lenssen'));
		$personSubject->addEmail('jack@subject.nl');
		$this->personRepository->add($personSubject);

		$absence = new Absence();
		$absence->setCreateUser($createUser);
		$user = $absence->getCreateUser();
		$this->assertNotNull($user);
		$absence->setPersonSubject($personSubject);
		$absence->setCreationDateTime(new \DateTime('2012-01-01 00:00:01'));
		$absence->setStatus($absence::STATUS_PENDING);
		$absence->setType($absence::TYPE_VACATION);
		$absence->setStartDateTime(new \DateTime('2012-07-05 00:00:01'));
		$absence->setEndDateTime(new \DateTime('2012-07-24 00:00:01'));
		$absence->setReason('Why I want this absence');
		$absence->setRemark('This is ok');
		$this->absenceRepository->add($absence);

		$this->persistenceManager->persistAll();

		$persistedAbsence = $this->absenceRepository->findAll()->getFirst();

		$this->assertEquals($absence, $persistedAbsence);
	}
}

?>