<?php
namespace Beech\Absence\Tests\Unit\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 19-10-12 16:20
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Absence\Domain\Model\SicknessAbsence as SicknessAbsence;

/**
 * Testcase for SicknessAbsence
 */
class SicknessAbsenceTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function testInstance() {
		$sicknessAbsence = new SicknessAbsence();
		$this->assertInstanceOf('Beech\Absence\Domain\Model\SicknessAbsence', $sicknessAbsence);
	}
}
?>