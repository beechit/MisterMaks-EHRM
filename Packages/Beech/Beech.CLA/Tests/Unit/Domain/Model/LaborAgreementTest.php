<?php
namespace Beech\CLA\Tests\Unit\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 17-09-12 14:52
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\CLA\Domain\Model\LaborAgreement;

/**
 * Unit tests for LaborAgreement
 */
class LaborAgreementTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function createLaborAgreement() {
		$laborAgreement = new \Beech\CLA\Domain\Model\LaborAgreement();
		$laborAgreement->setName('Something');
		$this->assertInstanceOf('Beech\CLA\Domain\Model\LaborAgreement', $laborAgreement);
		$this->assertEquals('Something', $laborAgreement->getName());
	}


}

?>