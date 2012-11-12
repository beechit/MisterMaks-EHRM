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
	 * @return array Signature: name, type
	 */
	public function documentsDataProvider() {
		return array(
			array('bla', 'type1'),
			array('$%&', 'type2'),
			array('poool', 'type3')
		);
	}

	/**
	 * @dataProvider documentsDataProvider
	 * @test
	 */
	public function documentsPersistingAndRetrievingWorksCorrectly($name, $type) {
		$document = new Document();
		$document->setName($name);
		$document->setType($type);

		$this->documentRepository->add($document);
		$this->persistenceManager->persistAll();
		$this->persistenceManager->clearState();
		$this->assertEquals(1, $this->documentRepository->countAll());
	}
}
?>