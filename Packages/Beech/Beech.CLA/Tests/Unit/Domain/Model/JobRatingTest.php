<?php
namespace Beech\CLA\Tests\Unit\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 17-09-12 14:52
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\CLA\Domain\Model\JobRating;

/**
 * Unit tests for JobRating
 */
class JobRatingTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function createJobRating() {
		$jobRating = new JobRating();
		$jobRating->setLaborAgreement(new \Beech\CLA\Domain\Model\LaborAgreement());
		$this->assertInstanceOf('\Beech\CLA\Domain\Model\JobRating', $jobRating);
		$this->assertInstanceOf('\Beech\CLA\Domain\Model\LaborAgreement', $jobRating->getLaborAgreement());
	}
}

?>