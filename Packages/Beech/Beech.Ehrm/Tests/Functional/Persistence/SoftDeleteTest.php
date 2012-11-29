<?php
namespace Beech\Ehrm\Tests\Functional\Persistence;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-08-12 12:46
 * All code (c) Beech Applications B.V. all rights reserved
 */

use \Beech\Ehrm\Domain\Model\Log;
use \Beech\Party\Domain\Model\Company;

/**
 * Test suite for the SoftDelete functionality
 */
class SoftDeleteTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Ehrm\Domain\Repository\LogRepository
	 */
	protected $logRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\CompanyRepository
	 */
	protected $companyRepository;

	public function setUp() {
		parent::setUp();
		$this->logRepository = $this->objectManager->get('Beech\Ehrm\Domain\Repository\LogRepository');
		$this->companyRepository = $this->objectManager->get('Beech\Party\Domain\Repository\CompanyRepository');
	}

	/**
	 * @test
	 */
	public function anEntityCanBeCreatedPersistedAndRetrieved() {
		$this->assertEquals(0, $this->companyRepository->countAll());
		$this->assertEquals(0, $this->logRepository->countAll());

		$company = $this->createCompany('Foo', 1, 1, 'type', 'description', 'bar');
		$this->companyRepository->add($company);

		$log = $this->createLog('foo');
		$this->logRepository->add($log);

		$this->persistenceManager->persistAll();

		$this->assertEquals(1, $this->companyRepository->countAll());
		$this->assertEquals(1, $this->logRepository->countAll());
	}

	/**
	 * @test
	 */
	public function anEntityWithDeletedPropertyCanBeSoftDeletedBySettingDeletedManually() {
		$this->assertEquals(0, $this->companyRepository->countAll());

		$company = $this->createCompany('Foo', 1, 1, 'type', 'description', 'bar');
		$this->companyRepository->add($company);

		$this->persistenceManager->persistAll();

		$this->assertEquals(1, $this->companyRepository->countAll());

		$company->setDeleted(TRUE);
		$this->companyRepository->update($company);
		$this->persistenceManager->persistAll();

		$this->assertEquals(0, $this->companyRepository->countAll());
	}

	/**
	 * @test
	 */
	public function anEntityWithDeletedPropertyIsSoftDeletedIfTheRemoveMethodOfTheRepositoryIsCalled() {
		$this->assertEquals(0, $this->companyRepository->countAll());

		$company = $this->createCompany('Foo', 1, 1, 'type', 'description', 'bar');
		$this->companyRepository->add($company);

		$this->persistenceManager->persistAll();

		$this->assertEquals(1, $this->companyRepository->countAll());

		$this->companyRepository->remove($company);

		$this->persistenceManager->persistAll();

		$this->assertEquals(1, $this->companyRepository->countByDeleted(TRUE));
	}

	/**
	 * @test
	 */
	public function anEntityWithoutDeletedPropertyIsDeletedIfTheRemoveMethodOfTheRepositoryIsCalled() {
		$this->assertEquals(0, $this->logRepository->countAll());

		$log = $this->createLog('You did something!');
		$this->logRepository->add($log);

		$this->persistenceManager->persistAll();

		$this->assertEquals(1, $this->logRepository->countAll());

		$this->logRepository->remove($log);

		$this->persistenceManager->persistAll();

		$this->assertEquals(0, $this->logRepository->countAll());
	}

	/**
	 * @param string $name
	 * @param integer $companyNumber
	 * @param integer $chamberOfCommerceNumber
	 * @param string $type
	 * @param string $description
	 * @return \Beech\Party\Domain\Model\Company
	 */
	protected function createCompany($name, $companyNumber, $chamberOfCommerceNumber, $type, $description, $legalFrom) {
		$company = new Company();
		$company->setName($name);
		$company->setCompanyNumber($companyNumber);
		$company->setChamberOfCommerceNumber($chamberOfCommerceNumber);
		$company->setCompanyType($type);
		$company->setDescription($description);
		$company->setLegalForm($legalFrom);

		return $company;
	}

	/**
	 * @param string $message
	 * @param integer $severity
	 * @return \Beech\Ehrm\Domain\Model\Log
	 */
	protected function createLog($message, $severity = LOG_ALERT) {
		$log = new Log();
		$log->setMessage($message);
		$log->setSeverity($severity);

		return $log;
	}

}
?>