<?php
namespace Beech\Absence\Tests\Functional\Domain\Model;

	/*
	 * This source file is proprietary property of Beech Applications B.V.
	 * Date: 31-07-2013 16:04
	 * All code (c) Beech Applications B.V. all rights reserved
	 */

/**
 * Functional test for AbsenceArrangement Persistence
 */
class AbsenceArrangementTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Absence\Domain\Repository\AbsenceArrangementRepository
	 */
	protected $absenceArrangementRepository;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->absenceArrangementRepository = $this->objectManager->get('Beech\Absence\Domain\Repository\AbsenceArrangementRepository');}

	/**
	 * @test
	 */
	public function AbsenceArrangementPersistingAndRetrievingWorksCorrectly() {
		$absenceArrangement = new \Beech\Absence\Domain\Model\AbsenceArrangement();
		$absenceArrangement->setAbsenceArrangement('Foo');
		$this->absenceArrangementRepository->add($absenceArrangement);

		$this->documentManager->flush();

		$this->assertEquals(1, count($this->absenceArrangementRepository->findAll()));
	}
}

?>