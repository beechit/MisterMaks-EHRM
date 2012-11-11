<?php
namespace Beech\WorkFlow\Tests\Unit\PreConditions\Property;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 20-09-12 22:13
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\Company as Company;
use Beech\Party\Domain\Model\Company\TaxData as TaxData;

/**
 * Unittests for the EmptyPreCondition
 */
class EmptyPreconditionTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @return array
	 */
	public function dataProvider() {
		return array(
			array('name', $this->createCompany('Foo', 1), FALSE),
			array('taxData', $this->createCompany('Foo', 1, TRUE), FALSE),
			array('taxData', $this->createCompany('Foo', 1, FALSE), TRUE),
			array('name', $this->createCompany(array('1', '2'), 1), FALSE),
			array('name', $this->createCompany(array(), 1), TRUE),
			array('name', $this->createCompany('', 1), TRUE),
			array('name', $this->createCompany(' ', 1), TRUE),
			array('name', $this->createCompany(NULL, 1), TRUE),
			array('name', 'notAClassInstance', TRUE),
			array('noneExistingProperty', $this->createCompany('Foo', 1), TRUE),
		);
	}

	/**
	 * @dataProvider dataProvider
	 * @test
	 */
	public function emptyPreConditionIsValid($propertyName, $targetEntity, $expectedValue) {
		$emptyPrecondition = new \Beech\WorkFlow\PreConditions\Property\EmptyPreCondition();
		$emptyPrecondition->setPropertyName($propertyName);
		$emptyPrecondition->setTargetEntity($targetEntity);

		$this->assertEquals($expectedValue, $emptyPrecondition->isMet());
	}

	/**
	 * @param string $name
	 * @param integer $companyNumber
	 * @param boolean $addTaxData
	 * @return \Beech\Party\Domain\Model\Company
	 */
	protected function createCompany($name, $companyNumber, $addTaxData = FALSE) {
		$company = new Company();
		$company->setName($name);
		$company->setCompanyNumber($companyNumber);

		if ($addTaxData) {
			$taxData = new TaxData();
			$taxData->setVatNumber('123456');
			$taxData->setWageTaxNumber('789');
			$company->setTaxData($taxData);
		}

		return $company;
	}
}

?>