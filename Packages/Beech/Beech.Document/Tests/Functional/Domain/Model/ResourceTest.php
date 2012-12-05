<?php
namespace Beech\Document\Tests\Functional\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 28-08-12 16:04
 * All code (c) Beech Applications B.V. all rights reserved
 */

/**
 * Functional test for ResourcePersistence
 */
class ResourceTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Document\Domain\Repository\ResourceRepository
	 */
	protected $resourceRepository;

	/**
	 * @var \Beech\Document\Domain\Repository\DocumentRepository
	 */
	protected $documentRepository;

	/**
	 * @var \TYPO3\Flow\Resource\ResourceManager
	 */
	protected $resourceManager;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->resourceRepository = $this->objectManager->get('Beech\Document\Domain\Repository\ResourceRepository');
		$this->resourceRepository->injectDocumentManagerFactory($this->documentManagerFactory);
		$this->inject($this->resourceRepository, 'reflectionService', $this->objectManager->get('TYPO3\Flow\Reflection\ReflectionService'));
		$this->inject($this->resourceRepository, 'persistenceManager', $this->persistenceManager);

		$this->documentRepository = $this->objectManager->get('Beech\Document\Domain\Repository\DocumentRepository');
		$this->documentRepository->injectDocumentManagerFactory($this->documentManagerFactory);
		$this->inject($this->documentRepository, 'reflectionService', $this->objectManager->get('TYPO3\Flow\Reflection\ReflectionService'));
		$this->inject($this->documentRepository, 'persistenceManager', $this->persistenceManager);

		$this->resourceManager = $this->objectManager->get('TYPO3\Flow\Resource\ResourceManager');
	}

	/**
	 * @test
	 */
	public function resourcePersistingAndRetrievingWorksCorrectly() {
		$resume = new \Beech\Document\Domain\Model\Document();
		$resume->setName('Resume');
		$resume->setDocumentType($resume::TYPE_RESUME);
		$this->documentRepository->add($resume);

		$picture = new \Beech\Document\Domain\Model\Document();
		$picture->setName('Picture');
		$picture->setDocumentType($picture::TYPE_OTHER);
		$this->documentRepository->add($picture);

		$this->persistenceManager->persistAll();

		for ($i = 0; $i < 10; $i ++) {
			$resource = new \Beech\Document\Domain\Model\Resource();
			$resource->setDocument($resume);
			$this->resourceRepository->add($resource);
		}

		for ($i = 0; $i < 5; $i ++) {
			$resource = new \Beech\Document\Domain\Model\Resource();
			$resource->setDocument($picture);
			$this->resourceRepository->add($resource);
		}

		$this->documentManager->flush();

		$this->assertEquals(2, count($this->documentRepository->findAll()));
		$this->assertEquals(15, count($this->resourceRepository->findAll()));
		$this->assertEquals(10, count($this->resourceRepository->findByDocument($resume)));
		$this->assertEquals(10, $this->resourceRepository->countByDocument($resume));
		$this->assertEquals(5, count($this->resourceRepository->findByDocument($picture)));
		$this->assertEquals(5, $this->resourceRepository->countByDocument($picture));
	}

	/**
	 * @test
	 */
	public function resourceWithOriginalResourceCanBePersistedAndRetrieved() {
		$newItem = $this->resourceManager->importResource('resource://Beech.Document/Private/TestData/cgl.pdf');

		$resource = new \Beech\Document\Domain\Model\Resource();
		$resource->setOriginalresource($newItem);
		$this->resourceRepository->add($resource);

		$this->documentManager->flush();

		$persistedResources = $this->resourceRepository->findAll();
		$this->assertEquals(1, count($persistedResources));

		$persistedResource = array_pop($persistedResources);
		$this->assertEquals($newItem, $persistedResource->getOriginalResource());
	}
}
?>