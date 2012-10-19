<?php
namespace Beech\Absence\Tests\Unit\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 19-10-12 16:20
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Absence\Domain\Model\Registration as Registration;

/**
 * Testcase for Registration
 */
class RegistrationTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function testInstance() {
		$registration = new Registration();
		$this->assertInstanceOf('Beech\Absence\Domain\Model\Registration', $registration);
	}
}
?>