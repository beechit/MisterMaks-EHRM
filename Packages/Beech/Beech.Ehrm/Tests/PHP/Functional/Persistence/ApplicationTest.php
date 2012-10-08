<?php
namespace Beech\Ehrm\Functional\Persistence;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 19-09-12 12:46
 * All code (c) Beech Applications B.V. all rights reserved
 */

use \Beech\Ehrm\Domain\Model\Application as Application;
use \Beech\Party\Domain\Model\Company as Company;

/**
 * Test suite for the Application model
 */
class ApplicationTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Ehrm\Domain\Repository\ApplicationRepository
	 */
	protected $applicationRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\CompanyRepository
	 */
	protected $companyRepository;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->applicationRepository = $this->objectManager->get('\Beech\Ehrm\Domain\Repository\ApplicationRepository');
		$this->companyRepository = $this->objectManager->get('\Beech\Party\Domain\Repository\CompanyRepository');
	}

	/**
	 * @test
	 */
	public function anEntityCanBeCreatedPersistedAndRetrieved() {
		$this->assertEquals(0, $this->companyRepository->countAll());
		$this->assertEquals(0, $this->applicationRepository->countAll());

		$company = new Company();
		$company->setName('Foo');
		$company->setChamberOfCommerceNumber('');

		$application = new Application();
		$application->setCompany($company);
		$this->applicationRepository->add($application);

		$this->persistenceManager->persistAll();

		$this->assertEquals(1, $this->companyRepository->countAll());
		$this->assertEquals(1, $this->applicationRepository->countAll());
	}

	/**
	 * @test
	 * @expectedException \TYPO3\Flow\Persistence\Exception\ObjectValidationFailedException
	 */
	public function anEntityWithoutCompanyThrowsAnError() {
		$this->assertEquals(0, $this->applicationRepository->countAll());

		$application = new Application();
		$this->applicationRepository->add($application);

		$this->persistenceManager->persistAll();
	}
}
?>