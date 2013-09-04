<?php
namespace Beech\Absence\Tests\Functional\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 30-07-2013 15:20
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Beech\Absence\Domain\Model\Absence,
	Beech\Absence\Domain\Model\AbsenceArrangement;

/**
 * Persistence test for Absence entities
 */
class AbsenceTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Absence\Domain\Repository\AbsenceRepository
	 */
	protected $absenceRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 */
	protected $personRepository;

	/**
	 * @var \Beech\Absence\Domain\Repository\AbsenceArrangementRepository
	 */
	protected $absenceArrangementRepository;


	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->absenceRepository = $this->objectManager->get('Beech\Absence\Domain\Repository\AbsenceRepository');
		$this->absenceRepository->injectDocumentManagerFactory($this->documentManagerFactory);
		$this->personRepository = $this->objectManager->get('Beech\Party\Domain\Repository\PersonRepository');
		$this->absenceArrangementRepository = $this->objectManager->get('Beech\Absence\Domain\Repository\AbsenceArrangementRepository');
		$this->absenceArrangementRepository->injectDocumentManagerFactory($this->documentManagerFactory);
	}

	/**
	 * Simple test for persisting an absence registration
	 * @test
	 */
	public function absenceCanBePersistedAndRetrieved() {

		$absence = new Absence();
		$absence->setStartDate(new \DateTime('2012-01-02 00:00:01'));
		$absence->setEndDate(new \DateTime('2013-01-02 00:00:01'));
		$this->absenceRepository->add($absence);

		$anotherAbsence = new Absence();
		$anotherAbsence->setStartDate(new \DateTime('2012-01-02 00:00:01'));
		$anotherAbsence->setEndDate(new \DateTime('2013-01-02 00:00:01'));
		$this->absenceRepository->add($anotherAbsence);

		$this->documentManager->flush();

		$this->assertEquals(2, $this->absenceRepository->countAll());
	}

	/**
	 * test create relations to person model.
	 *
	 *
	 */
	public function setRelationAbsenceToPerson() {
		$initiator = new \Beech\Party\Domain\Model\Person();
		$initiator->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'Joe', '', 'Initiator'));
		$this->personRepository->add($initiator);

		$personSubject = new \Beech\Party\Domain\Model\Person();
		$personSubject->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'Jack', '', 'Subject'));
		$this->personRepository->add($personSubject);

		$absence = new Absence();
		$absence->setPersonInitiator($initiator);
		$absence->setPersonSubject($personSubject);
		$absence->setStartDateTime(new \DateTime('2012-01-02 00:00:01'));
		$absence->setEndDateTime(new \DateTime('2013-01-02 00:00:01'));
		$absence->setRemark('this is a remark');
		$absence->setReportMethod('sms');
		$absence->setNeedsGrant('TRUE');
		$absence->setRequestStatus('accepted');
		$this->absenceRepository->add($absence);

		$this->documentManager->flush();

		$persistedAbsence = $this->absenceRepository->findAll();

		$this->assertEquals($absence, $persistedAbsence[0]);
		$personSubject = $persistedAbsence[0]->getPersonSubject();

		$this->assertEquals('Jack Subject', $personSubject->getName()->getFullName());
		$initiator = $persistedAbsence[0]->getPersonInitiator();
		$this->assertEquals('Joe Initiator', $initiator->getName()->getFullName());
	}

}
?>