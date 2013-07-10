<?php
namespace Beech\CLA\Tests\Functional\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-08-12 13:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 */
class JobPositionTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var boolean
	 */
	protected $testableSecurityEnabled = TRUE;

	/**
	 * @var \Beech\CLA\Domain\Repository\JobPositionRepository
	 */
	protected $jobPositionRepository;

	/**
	 */
	public function setUp() {
		parent::setUp();

		$this->jobPositionRepository = $this->objectManager->get('Beech\CLA\Domain\Repository\JobPositionRepository');

			// Create some dummy jobPositions
		for ($i = 1; $i <= 4; $i++) {
			$jobPositionVar = 'jobPosition' . $i;
			$$jobPositionVar = new \Beech\CLA\Domain\Model\JobPosition();
			$$jobPositionVar->setName('JobPosition ' . $i);
		}

		$this->jobPositionRepository->add($jobPosition1);
		$this->jobPositionRepository->add($jobPosition2);
		$this->jobPositionRepository->add($jobPosition3);
		$this->jobPositionRepository->add($jobPosition4);

		$this->documentManager->flush();

		/**
		 * Work arround for now!
		 *
		 * 1. new document object don't get a ID until the are persisted
		 *    in the ORM part you got ID's directly when you create a new object
		 * 2. OneToMany relations don't work in ODM like in ORM for now
		 *    persisting of child objects is not posible for current solution
		 *    currently all objects currently known by documentmanager will get
		 *    persisted every time you call ->flush()
		 *
		 * So we need to persist first to get the id and then add the children
		 *
		 *
		 * todo: find a desend fix/solution for this
		 */
		$jobPosition1->addChild($jobPosition3);
		$jobPosition3->addChild($jobPosition4);

		$this->documentManager->flush();
		$this->documentManager->clear();
	}

//
//	/**
//	 * @test
//	 */
//	public function jobPositionsCanBePersisted() {
//		$this->assertEquals(4, $this->jobPositionRepository->countAll());
//	}
//
//	/**
//	 * @test
//	 */
//	public function jobPositionsCanBeRetriedByName() {
//		$this->assertEquals(1, $this->jobPositionRepository->countByName('JobPosition 1'));
//		$this->assertEquals(1, $this->jobPositionRepository->countByName('JobPosition 2'));
//		$this->assertEquals(1, $this->jobPositionRepository->countByName('JobPosition 3'));
//		$this->assertEquals(1, $this->jobPositionRepository->countByName('JobPosition 4'));
//	}

	/**
	 * @test
	 */
	public function jobPositionsCanHaveSubGroups() {

		/**
		 * due to a strange bug the document manager doesn't return
		 * any data in the second and more test function
		 * Looks like there is a cache issue in de CouchDB layer
		 * So for now all test in this one
		 */

			// jobPositionsCanBePersisted()
		$this->assertEquals(4, $this->jobPositionRepository->countAll());

			// jobPositionsCanBeRetriedByName()
		$this->assertEquals(1, $this->jobPositionRepository->countByName('JobPosition 1'));
		$this->assertEquals(1, $this->jobPositionRepository->countByName('JobPosition 2'));
		$this->assertEquals(1, $this->jobPositionRepository->countByName('JobPosition 3'));
		$this->assertEquals(1, $this->jobPositionRepository->countByName('JobPosition 4'));

			// jobPositionsCanHaveSubGroups()
		$jobPosition1 = $this->jobPositionRepository->findOneByName('JobPosition 1');
		$this->assertEquals(1, $jobPosition1->getChildren()->count());

		$jobPosition2 = $this->jobPositionRepository->findOneByName('JobPosition 2');
		$this->assertEquals(0, $jobPosition2->getChildren()->count());

		$jobPosition3 = $this->jobPositionRepository->findOneByName('JobPosition 3');
		$this->assertEquals(1, $jobPosition3->getChildren()->count());
	}

}

?>