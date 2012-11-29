<?php
namespace Beech\Party\Tests\Unit\Domain\Model;

use \Beech\Party\Domain\Model\Company;
use \Beech\Party\Domain\Model\Person;
use \Beech\Party\Domain\Model\ElectronicAddress;
use \Beech\Party\Domain\Model\PersonName;

/**
 * Testcase for company
 */
class CompanyTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var \Beech\Party\Domain\Model\Company
	 */
	protected $company;

	/**
	 * Set up for tests
	 */
	public function setUp() {
		parent::setUp();
		$this->company = new Company();
	}

	/**
	 * @dataProvider companyDataProvider
	 * @test
	 */
	public function settingSimpleProperties($name, $companyNumber, $companyType, $description, $legalForm, $chamberOfCommerceNumber) {
		$this->company->setName($name);
		$this->company->setCompanyNumber($companyNumber);
		$this->company->setCompanyType($companyType);
		$this->company->setDescription($description);
		$this->company->setLegalForm($legalForm);
		$this->company->setChamberOfCommerceNumber($chamberOfCommerceNumber);
		$this->assertSame($this->company->getName(), $name);
		$this->assertSame($this->company->getCompanyNumber(), $companyNumber);
		$this->assertSame($this->company->getCompanyType(), $companyType);
		$this->assertSame($this->company->getDescription(), $description);
		$this->assertSame($this->company->getLegalForm(), $legalForm);
		$this->assertSame($this->company->getChamberOfCommerceNumber(), $chamberOfCommerceNumber);
	}

	/**
	 * @test
	 */
	public function addTaxData() {
			// check if company has no tax data
		$this->assertNull($this->company->getTaxData());
			// check if company has tax data
		$taxData = new \Beech\Party\Domain\Model\Company\TaxData();
		$this->company->setTaxData($taxData);
		$this->assertNotNull($this->company->getTaxData());
	}

	/**
	 * @test
	 */
	public function addAndRemoveDepartments() {
			// check if company has no departments
		$this->assertEquals(0, $this->company->getDepartments()->count());
			// create first department
		$oneDepartment = new Company();
		$oneDepartment->setName('Department 1');
		$this->company->addDepartment($oneDepartment);
			// check if company has one department
		$this->assertEquals(1, $this->company->getDepartments()->count());
			// create second department
		$secondDepartment = new Company();
		$secondDepartment->setName('Department 2');
		$this->company->addDepartment($secondDepartment);
			// check if company has two departments
		$this->assertEquals(2, $this->company->getDepartments()->count());
		$this->company->removeDepartment($oneDepartment);
		$this->company->removeDepartment($secondDepartment);
			// check if departments were removed
		$this->assertEquals(0, $this->company->getDepartments()->count());
	}

	/**
	 * @test
	 */
	public function addAndRemoveAddresses() {
			// check if company has no address
		$this->assertEquals(0, $this->company->getAddresses()->count());
			// create first department
		$address = new \Beech\Party\Domain\Model\Address();
		$this->company->addAddress($address);
			// check if company has address
		$this->assertEquals(1, $this->company->getAddresses()->count());
		$this->company->removeAddress($address);
			// check if address were removed
		$this->assertEquals(0, $this->company->getAddresses()->count());
	}

	/**
	 * @test
	 */
	public function addAndRemoveElectronicAddresses() {
			// check if company has no electronic address
		$this->assertCount(0, $this->company->getElectronicAddresses());
			// add email
		$email = new ElectronicAddress();
		$email->setType(ElectronicAddress::TYPE_EMAIL);
		$this->company->setPrimaryElectronicAddress($email);
			// check if company has got electronic address
		$this->assertCount(1, $this->company->getElectronicAddresses());
			// check if its email
		$this->assertEquals(ElectronicAddress::TYPE_EMAIL, $this->company->getElectronicAddresses()->get(0)->getType());
			// add phone
		$phone = new ElectronicAddress();
		$phone->setType(ElectronicAddress::TYPE_PHONE);
		$this->company->addElectronicAddress($phone);
			// check if company has got two electronic addresses
		$this->assertCount(2, $this->company->getElectronicAddresses());
			// check if its phone
		$this->assertEquals(ElectronicAddress::TYPE_PHONE, $this->company->getElectronicAddresses()->get(1)->getType());
			// check if primary electronic address is email
		$this->assertEquals(ElectronicAddress::TYPE_EMAIL, $this->company->getPrimaryElectronicAddress()->getType());
			// removing data
		$this->company->removeElectronicAddress($email);
		$this->company->removeElectronicAddress($phone);
		$this->assertCount(0, $this->company->getElectronicAddresses());
	}

	/**
	 * @test
	 */
	public function setAsDeleted() {
			// check if its not set as deleted
		$this->assertFalse($this->company->getDeleted());
			// check if its set as deleted
		$this->company->setDeleted(TRUE);
		$this->assertTrue($this->company->getDeleted());
	}

	/**
	 * @test
	 */
	public function checkParentCompanyOfDepartment() {
		$department = new Company();
		$department->setName('Department');
		$this->company->addDepartment($department);
		$this->assertEquals($this->company, $department->getParentCompany());
	}

	/**
	 * @test
	 */
	public function addAndRemoveContactPerson() {
		$person = new Person();
		$person->addPersonName(new PersonName('Mr', 'John', '', 'Matrix'));
		$this->assertCount(0, $this->company->getContactPersons());
		$this->company->addContactPerson($person);
		$this->assertCount(1, $this->company->getContactPersons());
			// is it really the same person ?
		$this->assertEquals($person, $this->company->getContactPersons()->get(0));
			// are you sure ?
		$this->assertEquals('Mr John Matrix', $this->company->getContactPersons()->get(0)->getName()->getFullName());
			// John you are fired!
		$this->company->removeContactPerson($person);
		$this->assertCount(0, $this->company->getContactPersons());
	}

	/**
	 * @test
	 */
	public function setCollectionOfContactPersons() {
		$john = new Person();
		$bob = new Person();
		$emmy = new Person();
		$this->company->addContactPerson($john);
		$this->company->addContactPerson($bob);
		$this->company->addContactPerson($emmy);
		$newCompany = new Company();
			// copy contact list...
		$newCompany->setContactPersons($this->company->getContactPersons());
		$this->assertEquals($this->company->getContactPersons(), $newCompany->getContactPersons());
	}

	/**
	 * @return array Signature: name, companyNumber, companyType, description, legalForm, chamberOfCommerceNumber
	 */
	public function companyDataProvider() {
		return array(
			array('Beech.IT', '123', 'Type 1', 'Nice company', 'B.V.', '212121212'),
			array('Emaux', '222', 'Type 1', 'Other company', 'B.V.', '412121222'),
			array('Google Inc.', '444', 'Type 2', 'Big company', 'B.V.', '544543454'),
		);
	}

}

?>