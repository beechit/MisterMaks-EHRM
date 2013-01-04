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

}

?>