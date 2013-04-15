<?php
namespace Beech\Ehrm\Tests\Unit\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 15-04-13 16:20
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Ehrm\Domain\Model\Status;

/**
 * Testcase for Status
 */
class StatusTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function testInstance() {
		$status = new Status();
		$this->assertInstanceOf('Beech\Ehrm\Domain\Model\Status', $status);
	}

	/**
	 * @test
	 */
	public function getterSetterTest() {
		$status = new Status();
		$status->setStatusName(Status::STATUS_CANCELED);
		$this->assertEquals(Status::STATUS_CANCELED, $status->getStatusName());
	}

}
?>