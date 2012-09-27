<?php
namespace Beech\Document\Tests\Functional;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 28-08-12 16:04
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Document\Domain\Model\Document;

/**
 * Functional test for DocumentPersistence
 */
class DocumentPersistenceTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var Beech\Document\Domain\Repository\DocumentRepository
	 */
	protected $documentRepository;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->documentRepository = $this->objectManager->get('Beech\Document\Domain\Repository\DocumentRepository');
	}

	/**
	 * @return array Signature: name
	 */
	public function documentsDataProvider() {
		return array(
			array('bla'),
			array('$%&'),
			array('poool')
		);
	}

	/**
	 * @dataProvider documentsDataProvider
	 * @test
	 */
	public function documentsPersistingAndRetrievingWorksCorrectly($name) {
		$document = new Document();
		$document->setName($name);
		$this->documentRepository->add($document);

		$this->persistenceManager->persistAll();

		$this->assertEquals(1, $this->documentRepository->countAll());

		$this->persistenceManager->clearState();
	}
}
?>