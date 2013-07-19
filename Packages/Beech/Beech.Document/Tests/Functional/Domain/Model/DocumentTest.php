<?php
namespace Beech\Document\Tests\Functional\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 28-08-12 16:04
 * All code (c) Beech Applications B.V. all rights reserved
 */

/**
 * Functional test for DocumentPersistence
 */
class DocumentTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Document\Domain\Repository\DocumentRepository
	 */
	protected $documentRepository;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->documentRepository = $this->objectManager->get('Beech\Document\Domain\Repository\DocumentRepository');
		$this->documentRepository->injectDocumentManagerFactory($this->documentManagerFactory);
		$this->personRepository = $this->objectManager->get('Beech\Party\Domain\Repository\PersonRepository');
		$this->companyRepository = $this->objectManager->get('Beech\Party\Domain\Repository\CompanyRepository');
	}

	/**
	 * @test
	 */
	public function documentPersistingAndRetrievingWorksCorrectly() {
		$document = new \Beech\Document\Domain\Model\Document();
		$document->setName('Foo');
		$this->documentRepository->add($document);

		$this->documentManager->flush();

		$this->assertEquals(1, count($this->documentRepository->findAll()));
	}

	/**
	 * @test
	 */
	public function checkIfTypeOfDocumentPartyIsCorrect() {
		$person = new \Beech\Party\Domain\Model\Person();
		$person->setName(new \TYPO3\Party\Domain\Model\PersonName('Mr', 'B.A.', '', 'Baracus'));
		$this->personRepository->add($person);

		$document = new \Beech\Document\Domain\Model\Document();
		$document->setName('Personal report');
		$document->setParty($person);
		$this->documentRepository->add($document);

		$this->documentManager->flush();

		$this->assertEquals(1, $this->documentRepository->countAll());
		$this->assertInstanceOf('Beech\Party\Domain\Model\Person', $document->getParty());
		$this->assertInstanceOf('TYPO3\Party\Domain\Model\AbstractParty', $document->getParty());

		$company = new \Beech\Party\Domain\Model\Company();
		$company->setName('A Team');
		$this->companyRepository->add($company);

		$companyDocument = new \Beech\Document\Domain\Model\Document();
		$companyDocument->setName('Company report');
		$companyDocument->setParty($company);

		$this->documentRepository->add($companyDocument);
		$this->documentManager->flush();

		$this->assertEquals(2, $this->documentRepository->countAll());
		$this->assertInstanceOf('Beech\Party\Domain\Model\Company', $companyDocument->getParty());
		$this->assertInstanceOf('TYPO3\Party\Domain\Model\AbstractParty', $companyDocument->getParty());

	}
}

?>