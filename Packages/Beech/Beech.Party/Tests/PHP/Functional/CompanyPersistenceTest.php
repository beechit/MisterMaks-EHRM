<?php
namespace Beech\Party\Tests\Functional;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Karol Kamiński <karol@beech.it>
 * Date: 28-08-12 17:23
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Beech\Party\Domain\Model\Company;

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
	 * @return array Signature: firstName, middleName, lastName, emailAddress
	 */
	public function companiesDataProvider() {
		return array(
			array('Beech.IT', '123', 'Type 1', 'Nice company', '212121212', ''),
			array('Emaux', '222', 'Type 1', 'Other company', '412121222', ''),
			array('Google Inc.', '444', 'Type 2', 'Big company', '544543454', ''),
		);
	}

	/**
	 * Simple test for persistence a company
	 *
	 * TODO: adding email, departments, addresses
	 *
	 * @dataProvider companiesDataProvider
	 * @test
	 */
	public function companiesPersistingAndRetrievingWorksCorrectly($companyName, $companyNumber, $companyType, $description, $chamberOfCommerce, $legalForm) {
		$company = new Company();
		$company->setName($companyName);
		$company->setCompanyNumber($companyNumber);
		$company->setCompanyType($companyType);
		$company->setDescription($description);
		$company->setChamberOfCommerceNumber($chamberOfCommerce);
		$company->setLegalForm($legalForm);
		$this->companyRepository->add($company);
		$this->persistenceManager->persistAll();

		$this->assertEquals(1, $this->companyRepository->countAll());

		$this->persistenceManager->clearState();
	}
}

?>