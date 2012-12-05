<?php
namespace Beech\Ehrm\Tests\Functional\Persistence;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-08-12 12:46
 * All code (c) Beech Applications B.V. all rights reserved
 */

use \Beech\Party\Domain\Model\Company;

/**
 * Test suite for the SoftDelete functionality
 */
class SoftDeleteTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Ehrm\Tests\Functional\Fixtures\Domain\Repository\CompanyRepository
	 */
	protected $companyRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\CompanyRepository
	 */
	protected $fixtureCompanyRepository;

	public function setUp() {
		parent::setUp();
		$this->companyRepository = $this->objectManager->get('Beech\Party\Domain\Repository\CompanyRepository');
		$this->fixtureCompanyRepository = $this->objectManager->get('Beech\Ehrm\Tests\Functional\Fixtures\Domain\Repository\CompanyRepository');
	}

	/**
	 * @test
	 */
	public function anEntityCanBeCreatedPersistedAndRetrieved() {
		$this->assertEquals(0, $this->companyRepository->countAll());

		$company = $this->createCompany('Foo', 1, 1, 'type', 'description', 'bar');
		$this->companyRepository->add($company);

		$this->persistenceManager->persistAll();
		$this->documentManager->flush();

		$this->assertEquals(1, $this->companyRepository->countAll());
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
		$this->assertEquals(0, $this->fixtureCompanyRepository->countAll());

		$fixtureCompany = new \Beech\Ehrm\Tests\Functional\Fixtures\Domain\Model\Company();
		$fixtureCompany->setTitle('Foo');

		$this->fixtureCompanyRepository->add($fixtureCompany);
		$this->persistenceManager->persistAll();

		$this->assertEquals(1, $this->fixtureCompanyRepository->countAll());

		$this->fixtureCompanyRepository->remove($fixtureCompany);
		$this->persistenceManager->persistAll();

		$this->assertEquals(0, $this->fixtureCompanyRepository->countAll());
	}

	/**
	 * @param string $name
	 * @param integer $chamberOfCommerceNumber
	 * @return \Beech\Party\Domain\Model\Company
	 */
	protected function createCompany($name, $chamberOfCommerceNumber) {
		$company = new Company();
		$company->setName($name);
		$company->setChamberOfCommerceNumber($chamberOfCommerceNumber);

		return $company;
	}



}

?>