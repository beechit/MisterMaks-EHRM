<?php
namespace Beech\CLA\Tests\Unit\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 17-09-12 10:52
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\CLA\Domain\Model\Wage;

/**
 * Test case for Wage
 */
class WageTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function setAndGetAmount() {
		$wage = new Wage();
		$wage->setAmount(9999);
		$this->assertEquals(9999, $wage->getAmount());
	}

	/**
	 * @test
	 */
	public function setAndGetStatus() {
		$wage = new Wage();
		$wage->setWageType(Wage::TYPE_DAILY);
		$this->assertEquals(Wage::TYPE_DAILY, $wage->getWageType());
	}

	/**
	 * @test
	 */
	public function setAndGetDescription() {
		$wage = new Wage();
		$wage->setDescription('This is a dummy description');
		$this->assertEquals('This is a dummy description', $wage->getDescription());
	}

	/**
	 * @test
	 */
	public function onCreationCreationDateTimeIsSet() {
		$wage = new Wage();
		$this->assertInstanceOf('DateTime', $wage->getCreationDateTime());
	}
}
?>