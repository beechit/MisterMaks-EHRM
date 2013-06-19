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
	 */
	public function setUp() {
		parent::setUp();
		$this->companyRepository = $this->objectManager->get('Beech\Party\Domain\Repository\CompanyRepository');
	}

	/**
	 * @return array Company: companyName, chamberOfCommerce, legalForm
	 */
	public function companiesDataProvider() {
		return array(
			array('Beech.IT', '212121212'),
			array('Emaux', '412121222'),
			array('Google Inc.', '544543454'),
		);
	}

	/**
	 * Simple test for persistence a company
	 * TODO: adding email, departments
	 *
	 * @dataProvider companiesDataProvider
	 * @test
	 */
	public function companiesPersistingAndRetrievingWorksCorrectly($companyName, $chamberOfCommerce) {
		$company = new Company();
		$company->setName($companyName);
		$company->setChamberOfCommerceNumber($chamberOfCommerce);

		$this->companyRepository->add($company);
		$this->persistenceManager->persistAll();
		$this->persistenceManager->clearState();

		$this->assertEquals(1, $this->companyRepository->countAll());
	}

}

?>