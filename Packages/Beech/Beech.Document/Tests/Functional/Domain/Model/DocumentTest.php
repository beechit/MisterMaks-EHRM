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
		$this->inject($this->documentRepository, 'reflectionService', $this->objectManager->get('TYPO3\Flow\Reflection\ReflectionService'));
		$this->inject($this->documentRepository, 'persistenceManager', $this->persistenceManager);
	}

	/**
	 * @test
	 */
	public function documentPersistingAndRetrievingWorksCorrectly() {
		for ($i = 0; $i < 10; $i ++) {
			$document = new \Beech\Document\Domain\Model\Document();
			$document->setName('Name ' . $i);
			$document->setDocumentType('Type ' . $i);
			$this->documentRepository->add($document);
		}

		$this->documentManager->flush();
		$this->assertEquals(10, count($this->documentRepository->findAll()));
	}
}
?>