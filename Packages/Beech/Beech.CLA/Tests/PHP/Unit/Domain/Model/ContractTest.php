<?php
namespace Beech\CLA\Tests\Unit\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\CLA\Domain\Model\Contract;

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
		$expireDate->add(new \DateInterval('P1Y'));
		$contract->setCreationDate($createDate);
		$this->assertNotNull($contract->getCreationDate());
		$contract->setStartDate($startDate);
		$this->assertNotNull($contract->getStartDate());
		$contract->setExpirationDate($expireDate);
		$this->assertNotNull($contract->getExpirationDate());
		$contract->setStatus(Contract::STATUS_DRAFT);
		$this->assertEquals(Contract::STATUS_DRAFT, $contract->getStatus());
	}

	/**
	 * @test
	 */
	public function setEmployer() {
		$contract = new Contract();
		$company = new \Beech\Party\Domain\Model\Company();
		$company->setName('Company');
		$contract->setEmployer($company);
		$this->assertInstanceOf('Beech\Party\Domain\Model\Company', $contract->getEmployer());
	}

	/**
	 * @test
	 */
	public function setEmployeeJobPositionAndWage() {
		$contract = new Contract();
		$person = new \Beech\Party\Domain\Model\Person();
		$wage = new \Beech\CLA\Domain\Model\Wage(123.56);
		$jobPosition = new \Beech\CLA\Domain\Model\JobPosition('Shop Assistant', 3);
		$contract->setEmployee($person);
		$contract->setWage($wage);
		$contract->setJobPosition($jobPosition);
		$this->assertInstanceOf('Beech\Party\Domain\Model\Person', $contract->getEmployee());
		$this->assertInstanceOf('Beech\CLA\Domain\Model\Wage', $contract->getWage());
		$this->assertInstanceOf('Beech\CLA\Domain\Model\JobPosition', $contract->getJobPosition());
	}

}

?>