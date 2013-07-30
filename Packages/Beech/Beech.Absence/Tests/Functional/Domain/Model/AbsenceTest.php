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
	 *
	 * @test
	 */
	public function absenceCanBePersistedAndRetrieved() {

		$absence = new Absence();
		$absence->setStartDateTime(new \DateTime('2012-01-02 00:00:01'));
		$absence->setEndDateTime(new \DateTime('2013-01-02 00:00:01'));
		$absence->setRemark('this is a remark');
		$absence->setReportMethod('sms');
		$absence->setNeedsGrant('TRUE');
		$absence->setRequestStatus('accepted');
		$this->absenceRepository->add($absence);

		$anotherAbsence = new Absence();
		$anotherAbsence->setStartDateTime(new \DateTime('2012-01-02 00:00:01'));
		$anotherAbsence->setEndDateTime(new \DateTime('2013-01-02 00:00:01'));
		$anotherAbsence->setRemark('this is a remark');
		$anotherAbsence->setReportMethod('sms');
		$anotherAbsence->setNeedsGrant('TRUE');
		$anotherAbsence->setRequestStatus('accepted');
		$this->absenceRepository->add($anotherAbsence);

		$this->documentManager->flush();

		$this->assertEquals(2, $this->absenceRepository->countAll());
	}

	/**
	 * Simple test for retrieving persisted values
	 *
	 * @test
	 */
	public function comparePersistedAbsenceValues() {

		$absence = new Absence();
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
		$remark = $persistedAbsence[0]->getRemark();
		$needsGrant = $persistedAbsence[0]->getNeedsGrant();
		$reportMethod = $persistedAbsence[0]->getReportMethod();
		$this->assertEquals('this is a remark', $remark);
		$this->assertEquals('TRUE', $needsGrant);
		$this->assertEquals('sms', $reportMethod);
	}

	/**
	 * test create relations to person model.
	 *
	 * @test
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

	/**
	 * set template to absence.
	 *
	 * @test
	 */
	public function setRelationAbsenceToAbsenceArrangement() {

		$absenceArrangement = new AbsenceArrangement();
		$absenceArrangement->setAbsenceArrangementName('a name');
		$this->absenceArrangementRepository->add($absenceArrangement);

		$this->documentManager->flush();

		$this->assertEquals(1, $this->absenceArrangementRepository->countAll());

		$absence = new Absence();
		$absence->setAbsenceArrangement($absenceArrangement);
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
		$absenceArrangement = $persistedAbsence[0]->getAbsenceArrangement();
		$this->assertEquals('a name', $absenceArrangement->getAbsenceArrangementName());
	}

}
?>