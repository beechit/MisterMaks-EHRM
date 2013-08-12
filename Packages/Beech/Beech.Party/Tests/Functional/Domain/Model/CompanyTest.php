<?php
namespace Beech\Party\Tests\Functional\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 28-08-12 17:23
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\Company;

/**
 * Persistence test for Company entity
 */
class CompanyTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var boolean
	 */
	protected $testableSecurityEnabled = TRUE;


	/**
	 * @var \Beech\Party\Domain\Repository\CompanyRepository
	 */
	protected $companyRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\ElectronicAddressRepository
	 */
	protected $electronicAddressRepository;
	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->companyRepository = $this->objectManager->get('Beech\Party\Domain\Repository\CompanyRepository');
		$this->electronicAddressRepository = $this->objectManager->get('Beech\Party\Domain\Repository\ElectronicAddressRepository');
	}

	/**
	 * @return array Company: companyName, chamberOfCommerce, electronicAddressType, address
	 */
	public function companiesDataProvider() {
		return array(
			array('Beech.IT', '212121212', 'email', 'beech@beech.it'),
			array('Emaux', '412121222', 'email', 'info@emaux.nl'),
			array('Google Inc.', '544543454', 'email', 'info@google.it'),
		);
	}

	/**
	 * Simple test for persistence a company
	 *
	 * @dataProvider companiesDataProvider
	 * @test
	 */
	public function companiesPersistingAndRetrievingWorksCorrectly($companyName, $chamberOfCommerce, $electronicAddressType,  $address) {
		$company = new Company();
		$company->setName($companyName);
		$company->setChamberOfCommerceNumber($chamberOfCommerce);

		$electronicAddress = new \Beech\Party\Domain\Model\ElectronicAddress();
		$electronicAddress->setElectronicAddressType($electronicAddressType);
		$electronicAddress->setAddress($address);
		$this->electronicAddressRepository->add($electronicAddress);

		$this->companyRepository->add($company);
		$this->persistenceManager->persistAll();
		$this->persistenceManager->clearState();

		$this->assertEquals(1, $this->companyRepository->countAll());
	}

	/**
	 * Simple test for persistence a company and departments
	 *
	 * @dataProvider companiesDataProvider
	 * @test
	 */
	public function companyWithDepartmentsPersistingAndRetrievingWorksCorrectly($companyName) {
		$company = new Company();
		$company->setName($companyName);
		$this->companyRepository->add($company);

		$departmentOne = new Company();
		$departmentOne->setName($companyName . ' - department 1');
		$this->companyRepository->add($departmentOne);

		$departmentTwo = new Company();
		$departmentTwo->setName($companyName . ' - department 2');
		$this->companyRepository->add($departmentTwo);

		$this->persistenceManager->persistAll();

		$this->assertEquals(3, $this->companyRepository->countAll());
		$this->assertCount(0, $company->getDepartments());

			// now add as departments
		$company->addDepartment($departmentOne);
		$company->addDepartment($departmentTwo);
		$this->companyRepository->update($company);

		$this->persistenceManager->persistAll();
		$this->persistenceManager->clearState();

		$this->assertCount(2, $company->getDepartments());

			// check departments have got the same parent
		list($persistedDepartmentOne, $persistedDepartmentTwo) = $this->companyRepository->findAll();
		$this->assertEquals($persistedDepartmentOne->getParentCompany(), $persistedDepartmentTwo->getParentCompany());
	}

	/**
	 * Simple test for removing departments
	 *
	 * @dataProvider companiesDataProvider
	 * @test
	 */
	public function removeDepartmentOfCompany($companyName) {
		$company = new Company();
		$company->setName($companyName);
		$this->companyRepository->add($company);

		$department = new Company();
		$department->setName($companyName . ' - department');
		$this->companyRepository->add($department);

			// first add department
		$company->addDepartment($department);
		$this->persistenceManager->persistAll();
		$this->assertCount(1, $company->getDepartments());

			// and now remove department
		$company->removeDepartment($department);
		$this->companyRepository->update($company);
		$this->persistenceManager->persistAll();
		$this->assertCount(0, $company->getDepartments());

	}

}

?>