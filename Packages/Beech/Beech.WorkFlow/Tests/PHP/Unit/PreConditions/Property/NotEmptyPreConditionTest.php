<?php
namespace Beech\WorkFlow\Tests\Unit\PreConditions\Property;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 20-09-12 22:13
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Beech\Party\Domain\Model\Company as Company;
use Beech\Party\Domain\Model\Company\TaxData as TaxData;

/**
 * Unittests for the NotEmptyPreCondition
 */
class NotEmptyPreconditionTest extends \TYPO3\FLOW3\Tests\UnitTestCase {

	/**
	 * @return array
	 */
	public function dataProvider() {
		return array(
			array('name', $this->createCompany('Foo', 1), TRUE),
			array('taxData', $this->createCompany('Foo', 1, TRUE), TRUE),
			array('taxData', $this->createCompany('Foo', 1, FALSE), FALSE),
			array('name', $this->createCompany(array('1', '2'), 1), TRUE),
			array('name', $this->createCompany(array(), 1), FALSE),
			array('name', $this->createCompany('', 1), FALSE),
			array('name', $this->createCompany(' ', 1), FALSE),
			array('name', $this->createCompany(NULL, 1), FALSE),
			array('name', 'notAClassInstance', FALSE),
			array('noneExistingProperty', $this->createCompany('Foo', 1), FALSE),
		);
	}

	/**
	 * @dataProvider dataProvider
	 * @test
	 */
	public function notEmptyPreConditionIsValid($propertyName, $targetEntity, $expectedValue) {
		$notEmptyPrecondition = new \Beech\WorkFlow\PreConditions\Property\NotEmptyPreCondition();
		$notEmptyPrecondition->setPropertyName($propertyName);
		$notEmptyPrecondition->setTargetEntity($targetEntity);

		$this->assertEquals($expectedValue, $notEmptyPrecondition->isMet());
	}

	/**
	 * @param string $name
	 * @param integer $companyNumber
	 * @param boolean $addTaxData
	 * @return \Beech\Party\Domain\Model\Company
	 */
	protected function createCompany($name, $companyNumber, $addTaxData = false) {
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