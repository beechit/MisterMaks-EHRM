<?php
namespace Beech\CLA\Tests\Functional\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-09-12 21:50
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Test the actual persistence of actions
 */
class ContractTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\CLA\Domain\Repository\ContractRepository
	 */
	protected $contractRepository;

	/**
	 * @var \Beech\CLA\Domain\Repository\JobDescriptionRepository
	 */
	protected $jobDescriptionRepository;

	/**
	 * Setup a testcase
	 */
	public function setUp() {
		parent::setUp();
		$this->contractRepository = $this->objectManager->get('\Beech\CLA\Domain\Repository\ContractRepository');
		$this->contractRepository->injectDocumentManagerFactory($this->documentManagerFactory);
	}

	/**
	 * @test
	 */
	public function testBasicContractPersistence() {
		$contract = new \Beech\CLA\Domain\Model\Contract();
		$contract->setStatus(new \Beech\Ehrm\Domain\Model\Status());
		$contract->setCreationDate(new \DateTime);

		$wage = new \Beech\CLA\Domain\Model\Wage();
		$wage->setAmount(9999);
		$wage->setType($wage::TYPE_DAILY);
		$this->documentManager->persist($wage);

		$contract->addWage($wage);
		$this->contractRepository->add($contract);

		$this->documentManager->flush();

		$this->assertEquals(1, $this->contractRepository->countAll());
	}

	/**
	 * @test
	 * @expectedException \TYPO3\Flow\Persistence\Exception\ObjectValidationFailedException
	 */
	public function testContractWithoutWageDoesNotValidate() {
		$this->markTestSkipped('Skip till the object validation is fixed');
		$contract = new \Beech\CLA\Domain\Model\Contract();
		$contract->setStatus(new \Beech\Ehrm\Domain\Model\Status());
		$contract->setCreationDate(new \DateTime);
		$this->contractRepository->add($contract);

		$this->documentManager->flush();

		$this->assertEquals(1, $this->contractRepository->countAll());
	}
}
?>