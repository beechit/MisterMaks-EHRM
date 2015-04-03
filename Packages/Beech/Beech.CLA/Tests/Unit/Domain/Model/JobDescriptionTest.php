<?php
namespace Beech\CLA\Tests\Unit\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Beech\CLA\Domain\Model\JobDescription;

/**
 * Testcase for Job position
 */
class JobDescriptionTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function checkName() {
		$jobDescription = new JobDescription();
		$expectedName = 'Software Developer';
		$jobDescription->setName($expectedName);
		$this->assertEquals($expectedName, $jobDescription->getName());
	}

}

?>