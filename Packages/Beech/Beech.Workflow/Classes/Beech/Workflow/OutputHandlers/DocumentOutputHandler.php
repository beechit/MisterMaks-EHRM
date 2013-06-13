<?php
namespace Beech\Workflow\OutputHandlers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 1/16/13 12:16 AM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * DocumentOutputHandler persists an entity
 */
class DocumentOutputHandler extends \Beech\Workflow\Core\AbstractOutputHandler implements \Beech\Workflow\Core\OutputHandlerInterface {

	/**
	 * @var \Doctrine\ODM\CouchDB\DocumentManager
	 */
	protected $documentManager;

	/**
	 * @var \Radmiraal\CouchDB\Persistence\DocumentManagerFactory
	 */
	protected $documentManagementFactory;

	/**
	 * @param \Radmiraal\CouchDB\Persistence\DocumentManagerFactory $documentManagerFactory
	 * @return void
	 */
	public function injectDocumentManagerFactory(\Radmiraal\CouchDB\Persistence\DocumentManagerFactory $documentManagerFactory) {
		$this->documentManagementFactory = $documentManagerFactory;
		$this->documentManager = $this->documentManagementFactory->create();
	}

	/**
	 * The document to persist
	 * @var object
	 */
	protected $document;

	/**
	 * Set the document to persist
	 * @param object $document
	 */
	public function setDocument($document) {
		$this->document = $document;
	}

	/**
	 * @return object
	 */
	public function getDocument() {
		return $this->document;
	}

	/**
	 * Execute this output handler class, persisting an document to its repository
	 * @return void
	 */
	public function invoke() {
		$this->documentManager->persist($this->document);
	}
}
?>