<?php
namespace Beech\Party\Tests\Unit\Domain\Model\Company;

/**
 * Testcase for company
 */
class TaxDataTest extends \TYPO3\FLOW3\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function checkGetAndSetVatNumber() {
		$taxData = new \Beech\Party\Domain\Model\Company\TaxData();
		$taxData->setVatNumber('123123');
		$this->assertEquals('123123', $taxData->getVatNumber());
	}

	/**
	 * @test
	 */
	public function checkGetAndSetWageNumber() {
		$taxData = new \Beech\Party\Domain\Model\Company\TaxData();
		$taxData->setWageTaxNumber('111222');
		$this->assertEquals('111222', $taxData->getWageTaxNumber());
	}
}

?>