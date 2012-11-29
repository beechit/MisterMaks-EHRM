<?php
namespace Beech\Party\Tests\Unit\Domain\Model\Company;

/**
 * Testcase for company
 */
class TaxDataTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var \Beech\Party\Domain\Model\Company\TaxData
	 */
	protected $taxData;

	/**
	 * Set up for tests
	 */
	public function setUp() {
		parent::setUp();
		$this->taxData = new \Beech\Party\Domain\Model\Company\TaxData();
	}

	/**
	 * @dataProvider taxDataDataProvider
	 * @test
	 */
	public function checkGetAndSetVatNumber($wageTaxNumber, $vatNumber) {
		$this->taxData->setVatNumber($vatNumber);
		$this->assertEquals($vatNumber, $this->taxData->getVatNumber());
	}

	/**
	 * @dataProvider taxDataDataProvider
	 * @test
	 */
	public function checkGetAndSetWageNumber($wageTaxNumber, $vatNumber) {
		$this->taxData->setWageTaxNumber($wageTaxNumber);
		$this->assertEquals($wageTaxNumber, $this->taxData->getWageTaxNumber());
	}

	/**
	 * @return array Signature: wageTaxNumber, vatNumber
	 */
	public function taxDataDataProvider() {
		return array(
			array('212121212', '534543543'),
			array('412121222', '2232322'),
			array('544543454', '756565656')
		);
	}
}

?>