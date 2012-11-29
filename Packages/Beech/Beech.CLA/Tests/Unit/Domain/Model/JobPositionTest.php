<?php
namespace Beech\CLA\Tests\Unit\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Beech\CLA\Domain\Model\JobPosition;

/**
 * Testcase for Job position
 */
class JobPositionTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function checkName() {
		$jobPosition = new JobPosition();
		$expectedName = 'Software Developer';
		$jobPosition->setName($expectedName);
		$this->assertEquals($expectedName, $jobPosition->getName());
	}

}

?>