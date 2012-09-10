<?php
namespace Beech\Party\Tests\Functional;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 28-08-12 17:23
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Beech\Party\Domain\Model\Company;
use Beech\Party\Domain\Model\Address;

/**
 * Persistence test for Company entity
 */
class CompanyPersistenceTest extends \TYPO3\FLOW3\Tests\FunctionalTestCase {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var Beech\Party\Domain\Repository\CompanyRepository
	 */
	protected $companyRepository;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->companyRepository = $this->objectManager->get('Beech\Party\Domain\Repository\CompanyRepository');
	}

	/**
	 * @return array
	 */
	public function addressDataProvider() {
		return array(
			array('Langstraat', 12, 22, '5808BC', 1, Address::TYPE_PRIMARY_LIVING, 123, 'Test'),
			array('Wilhelminastraat', 9898, 4, '1111 AA', 2, Address::TYPE_BUSINESS_ADDRESS, 888, 'Nice address'),
			array('Bumbastraat', 66, 66, '9999 XX', 3, Address::TYPE_TEMPORARY_ADDRESS, 3333, 'More description')
		);
	}

	/**
	 * Get sample address data
	 *
	 * @param $index integer
	 * @return Address
	 */
	private function getAddress($index) {
		$addressData = $this->addressDataProvider();
		$address = new Address();
		$fieldIndex = 0;
		$address->setStreet($addressData[$index][$fieldIndex++]);
		$address->setHouseNumber($addressData[$index][$fieldIndex++]);
		$address->setResidence($addressData[$index][$fieldIndex++]);
		$address->setPostalCode($addressData[$index][$fieldIndex++]);
		$address->setCode($addressData[$index][$fieldIndex++]);
		$address->setType($addressData[$index][$fieldIndex++]);
		$address->setPostBox($addressData[$index][$fieldIndex++]);
		$address->setDescription($addressData[$index][$fieldIndex++]);
		return $address;
	}

	/**
	 * @return array Company: companyName, companyNumber, companyType, description, chamberOfCommerce, legalForm, address
	 */
	public function companiesDataProvider() {
		return array(
			array('Beech.IT', '123', 'Type 1', 'Nice company', '212121212', '', $this->getAddress(0)),
			array('Emaux', '222', 'Type 1', 'Other company', '412121222', '', $this->getAddress(1)),
			array('Google Inc.', '444', 'Type 2', 'Big company', '544543454', '', $this->getAddress(2)),
		);
	}

	/**
	 * Simple test for persistence a company
	 * TODO: adding email, departments
	 *
	 * @dataProvider companiesDataProvider
	 * @test
	 */
	public function companiesPersistingAndRetrievingWorksCorrectly($companyName, $companyNumber, $companyType, $description, $chamberOfCommerce, $legalForm, $address) {
		$company = new Company();
		$company->setName($companyName);
		$company->setCompanyNumber($companyNumber);
		$company->setCompanyType($companyType);
		$company->setDescription($description);
		$company->setChamberOfCommerceNumber($chamberOfCommerce);
		$company->setLegalForm($legalForm);

		$company->addAddress($address);

		$this->companyRepository->add($company);
		$this->persistenceManager->persistAll();

		$this->assertEquals(1, $this->companyRepository->countAll());

		$company = $this->companyRepository->findAll()->getFirst();

		$this->assertEquals(1, $company->getAddresses()->count());

		$this->persistenceManager->clearState();
	}
}

?>