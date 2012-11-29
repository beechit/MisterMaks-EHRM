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
	public function checkAmount() {
		$wage = new Wage();
		$wage->setAmount(9999);
		$this->assertEquals(9999, $wage->getAmount());
	}

	/**
	 * @test
	 */
	public function checkStatus() {
		$wage = new Wage();
		$wage->setType(Wage::TYPE_DAILY);
		$this->assertEquals(Wage::TYPE_DAILY, $wage->getType());
	}

}
?>