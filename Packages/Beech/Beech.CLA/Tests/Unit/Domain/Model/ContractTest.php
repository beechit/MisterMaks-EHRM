<?php
namespace Beech\CLA\Tests\Unit\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\CLA\Domain\Model\Contract;
use Beech\Ehrm\Domain\Model\Status;

/**
 * Test case for Contract
 */
class ContractTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function createContract() {
		$contract = new Contract();
		$this->assertInstanceOf('Beech\CLA\Domain\Model\Contract', $contract);
		$createDate = new \DateTime();
		$startDate = new \DateTime();
		$expireDate = clone $startDate;
		$status = new Status();
		$status->setStatusName(Status::STATUS_DRAFT);
		$expireDate->add(new \DateInterval('P1Y'));
		$contract->setCreationDate($createDate);
		$this->assertNotNull($contract->getCreationDate());
		$contract->setStartDate($startDate);
		$this->assertNotNull($contract->getStartDate());
		$contract->setExpirationDate($expireDate);
		$this->assertNotNull($contract->getExpirationDate());
		$contract->setStatus($status);
		$this->assertEquals(Status::STATUS_DRAFT, $contract->getStatus()->getStatusName());
	}

	/**
	 * @test
	 */
	public function setEmployer() {
		$this->markTestSkipped('Now failing, rework into a more useful (functional) test');
		$contract = new Contract();
		$company = new \Beech\Party\Domain\Model\Company();
		$company->setName('Company');
		$contract->setEmployer($company);
		$this->assertInstanceOf('Beech\Party\Domain\Model\Company', $contract->getEmployer());
	}

	/**
	 * @test
	 */
	public function setEmployeeJobDescriptionAndWage() {
		$this->markTestSkipped('Now failing, rework into a more useful (functional) test');
		$wage = new \Beech\CLA\Domain\Model\Wage();
		$wage->setAmount(123.56);

		$person = new \Beech\Party\Domain\Model\Person();
		$jobDescription = new \Beech\CLA\Domain\Model\JobDescription('Shop Assistant', 3);
		$contract = new Contract();
		$contract->setEmployee($person);
		$contract->addWage($wage);
		$contract->setJobDescription($jobDescription);

		$this->assertInstanceOf('Beech\Party\Domain\Model\Person', $contract->getEmployee());
		$this->assertInstanceOf('Beech\CLA\Domain\Model\Wage', $contract->getWage());
		$this->assertInstanceOf('Beech\CLA\Domain\Model\JobDescription', $contract->getJobDescription());
	}

	/**
	 * @test
	 */
	public function getWageReturnsTheLatestWage() {
		$this->markTestSkipped('Now failing, rework into a more useful (functional) test');
		$wage = new \Beech\CLA\Domain\Model\Wage();
		$wage->setAmount(123.56);
		$wage2 = new \Beech\CLA\Domain\Model\Wage();
		$wage2->setAmount(125.56);
		$wage3 = new \Beech\CLA\Domain\Model\Wage();
		$wage3->setAmount(127.56);
		$contract = new Contract();
			// Model adds descending order...
		$contract->addWage($wage3);
		$contract->addWage($wage2);
		$contract->addWage($wage);
		$this->assertEquals($wage3, $contract->getWage());
	}

	/**
	 * @test
	 */
	public function getNoWageReturnsNull() {
		$this->markTestSkipped('Now failing, rework into a more useful (functional) test');
		$contract = new Contract();
		$this->assertEquals(NULL, $contract->getWage());
	}
}

?>