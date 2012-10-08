<?php
namespace Beech\Party\Tests\Unit\Domain\Model;

use \Beech\Party\Domain\Model\Company;
use \Beech\Party\Domain\Model\Person;
use \Beech\Party\Domain\Model\ElectronicAddress;
use \TYPO3\Party\Domain\Model\PersonName;

/**
 * Testcase for company
 */
class CompanyTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function settingSimpleProperties() {
		$company = new Company();
		$company->setName($this->getData(0, 0));
		$company->setCompanyNumber($this->getData(0, 1));
		$company->setCompanyType($this->getData(0, 2));
		$company->setDescription($this->getData(0, 3));
		$company->setLegalForm($this->getData(0, 4));
		$company->setChamberOfCommerceNumber($this->getData(0, 5));
		$this->assertSame($company->getName(), $this->getData(0, 0));
		$this->assertSame($company->getCompanyNumber(), $this->getData(0, 1));
		$this->assertSame($company->getCompanyType(), $this->getData(0, 2));
		$this->assertSame($company->getDescription(), $this->getData(0, 3));
		$this->assertSame($company->getLegalForm(), $this->getData(0, 4));
		$this->assertSame($company->getChamberOfCommerceNumber(), $this->getData(0, 5));
	}

	/**
	 * @test
	 */
	public function addTaxData() {
		$company = new Company();
			// check if company has no tax data
		$this->assertNull($company->getTaxData());
			// check if company has tax data
		$taxData = new \Beech\Party\Domain\Model\Company\TaxData();
		$company->setTaxData($taxData);
		$this->assertNotNull($company->getTaxData());
	}

	/**
	 * @test
	 */
	public function addAndRemoveDepartments() {
		$company = new Company();
			// check if company has no departments
		$this->assertEquals(0, $company->getDepartments()->count());
			// create first department
		$oneDepartment = new Company();
		$oneDepartment->setName('Department 1');
		$company->addDepartment($oneDepartment);
			// check if company has one department
		$this->assertEquals(1, $company->getDepartments()->count());
			// create second department
		$secondDepartment = new Company();
		$secondDepartment->setName('Department 2');
		$company->addDepartment($secondDepartment);
			// check if company has two departments
		$this->assertEquals(2, $company->getDepartments()->count());
		$company->removeDepartment($oneDepartment);
		$company->removeDepartment($secondDepartment);
			// check if departments were removed
		$this->assertEquals(0, $company->getDepartments()->count());
	}

	/**
	 * @test
	 */
	public function addAndRemoveAddresses() {
		$company = new Company();
			// check if company has no address
		$this->assertEquals(0, $company->getAddresses()->count());
			// create first department
		$address = new \Beech\Party\Domain\Model\Address();
		$company->addAddress($address);
			// check if company has address
		$this->assertEquals(1, $company->getAddresses()->count());
		$company->removeAddress($address);
			// check if address were removed
		$this->assertEquals(0, $company->getAddresses()->count());
	}

	/**
	 * @test
	 */
	public function addAndRemoveElectronicAddresses() {
		$company = new Company();
			// check if company has no electronic address
		$this->assertCount(0, $company->getElectronicAddresses());
			// add email
		$email = new ElectronicAddress();
		$email->setType(ElectronicAddress::TYPE_EMAIL);
		$company->setPrimaryElectronicAddress($email);
			// check if company has got electronic address
		$this->assertCount(1, $company->getElectronicAddresses());
			// check if its email
		$this->assertEquals(ElectronicAddress::TYPE_EMAIL, $company->getElectronicAddresses()->get(0)->getType());
			// add phone
		$phone = new ElectronicAddress();
		$phone->setType(ElectronicAddress::TYPE_PHONE);
		$company->addElectronicAddress($phone);
			// check if company has got two electronic addresses
		$this->assertCount(2, $company->getElectronicAddresses());
			// check if its phone
		$this->assertEquals(ElectronicAddress::TYPE_PHONE, $company->getElectronicAddresses()->get(1)->getType());
			// check if primary electronic address is email
		$this->assertEquals(ElectronicAddress::TYPE_EMAIL, $company->getPrimaryElectronicAddress()->getType());
			// removing data
		$company->removeElectronicAddress($email);
		$company->removeElectronicAddress($phone);
		$this->assertCount(0, $company->getElectronicAddresses());
	}

	/**
	 * @test
	 */
	public function setAsDeleted() {
		$company = new Company();
			// check if its not set as deleted
		$this->assertFalse($company->getDeleted());
			// check if its set as deleted
		$company->setDeleted(TRUE);
		$this->assertTrue($company->getDeleted());
	}

	/**
	 * @test
	 */
	public function checkParentCompanyOfDepartment() {
		$company = new Company();
		$company->setName('Company');
		$department = new Company();
		$department->setName('Department');
		$company->addDepartment($department);
		$this->assertEquals($company, $department->getParentCompany());
	}

	/**
	 * @test
	 */
	public function addAndRemoveContactPerson() {
		$company = new Company();
		$person = new Person();
		$person->addPersonName(new PersonName('Mr', 'John', '', 'Matrix'));
		$this->assertCount(0, $company->getContactPersons());
		$company->addContactPerson($person);
		$this->assertCount(1, $company->getContactPersons());
			// is it really the same person ?
		$this->assertEquals($person, $company->getContactPersons()->get(0));
			// are you sure ?
		$this->assertEquals('Mr John Matrix', $company->getContactPersons()->get(0)->getName()->getFullName());
			// John you are fired!
		$company->removeContactPerson($person);
		$this->assertCount(0, $company->getContactPersons());
	}

	/**
	 * @test
	 */
	public function setCollectionOfContactPersons() {
		$company = new Company();
		$john = new Person();
		$bob = new Person();
		$emmy = new Person();
		$company->addContactPerson($john);
		$company->addContactPerson($bob);
		$company->addContactPerson($emmy);
		$newCompany = new Company();
			// copy contact list...
		$newCompany->setContactPersons($company->getContactPersons());
		$this->assertEquals($company->getContactPersons(), $newCompany->getContactPersons());
	}

	/**
	 * @return array
	 */
	private function dataProvider() {
		return array(
			array('Beech.IT', '123', 'Type 1', 'Nice company', 'B.V.', '212121212'),
			array('Emaux', '222', 'Type 1', 'Other company', 'B.V.', '412121222'),
			array('Google Inc.', '444', 'Type 2', 'Big company', 'B.V.', '544543454'),
		);
	}

	/**
	 * @return string
	 */
	private function getData($row, $column) {
		$testData = $this->dataProvider();
		return $testData[$row][$column];
	}

}

?>