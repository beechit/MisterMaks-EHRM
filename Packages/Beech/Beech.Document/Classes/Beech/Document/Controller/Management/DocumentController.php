<?php
namespace Beech\Document\Controller\Management;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use \Beech\Document\Domain\Model\Document;
use \Beech\Document\Domain\Model\Resource;

/**
 * Document controller for the Beech.Document package
 *
 * @Flow\Scope("singleton")
 */
class DocumentController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @var \Beech\Document\Domain\Repository\DocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 * @Flow\Inject
	 */
	protected $objectManager;

	/**
	 * Shows a list of documents
	 *
	 * @param \Beech\Document\Domain\Model\Document $document
	 * @return void
	 */
	public function indexAction(\Beech\Document\Domain\Model\Document $document = NULL) {
		$this->view->assign('documents', $this->documentRepository->findAll());
		$this->view->assign('selectedDocument', $document);
	}

	/**
	 * Adds the given new document object to the document repository
	 *
	 * @param \Beech\Document\Domain\Model\Resource $newResource
	 * @return void
	 */
	public function createAction(\Beech\Document\Domain\Model\Resource $newResource = NULL) {
		$this->verifyResource($newResource);

		$newDocument = new \Beech\Document\Domain\Model\Document;
		$newDocument->setName($newResource->getOriginalResource()->getFilename());
		$newResource->setDocument($newDocument);
		$newDocument->addResource($newResource);
		$this->documentRepository->add($newDocument);

		$this->addFlashMessage($this->translator->translateById('document.documentUploaded', array(), NULL, NULL, 'Main', 'Beech.Document'));
		$this->redirect('index');
	}

	/**
	 * Shows a list of documents
	 *
	 * @param \Beech\Document\Domain\Model\Document $document
	 * @return void
	 */
	public function editAction(\Beech\Document\Domain\Model\Document $document = NULL) {
		$this->view->assign('document', $document);
	}

	/**
	 * Updates the given document object
	 *
	 * @param \Beech\Document\Domain\Model\Document $document The document to update
	 * @return void
	 */
	public function updateAction(\Beech\Document\Domain\Model\Document $document) {
		$this->documentRepository->update($document);
		$this->redirect('index');
	}

	/**
	 * Add resource to document given document object
	 *
	 * @param \Beech\Document\Domain\Model\Resource $newResource The document to update
	 * @param \Beech\Document\Domain\Model\Document $document The document to update
	 * @return void
	 */
	public function addResourceAction(\Beech\Document\Domain\Model\Resource $newResource, \Beech\Document\Domain\Model\Document $document) {
		$this->verifyResource($newResource);

		$newResource->setDocument($document);
		$document->addResource($newResource);
		$this->documentRepository->update($document);
		$this->redirect('index');
	}

	/**
	 * Shows a list of documents
	 *
	 * @param \Beech\Document\Domain\Model\Resource $resource
	 * @return void
	 */
	public function editResourceAction(\Beech\Document\Domain\Model\Resource $resource) {
		$this->view->assign('resource', $resource);
	}

	/**
	 * Updates the given document object
	 *
	 * @param \Beech\Document\Domain\Model\Resource $resource The document to update
	 * @param string $filename
	 * @return void
	 */
	public function updateResourceAction(\Beech\Document\Domain\Model\Resource $resource, $filename) {
		$resource->getOriginalResource()->setFilename($filename);
		$this->documentRepository->update($resource->getDocument());
		$this->redirect('index');
	}

	/**
	 * Removes the given resource object from the document
	 *
	 * @param \Beech\Document\Domain\Model\Resource $resource The resource to delete
	 * @return void
	 */
	public function deleteResourceAction(\Beech\Document\Domain\Model\Resource $resource) {
		$document = $resource->getDocument();
		$document->removeResource($resource);
		$this->documentRepository->update($document);
		$this->addFlashMessage($this->translator->translateById('document.documentDeleted', array(), NULL, NULL, 'Main', 'Beech.Document'));
		$this->redirect('index');
	}

	/**
	 * Removes the given document object
	 *
	 * @param \Beech\Document\Domain\Model\Document $document The document to delete
	 * @return void
	 */
	public function deleteAction(\Beech\Document\Domain\Model\Document $document) {
		$this->documentRepository->remove($document);
		$this->addFlashMessage($this->translator->translateById('document.documentDeleted', array(), NULL, NULL, 'Main', 'Beech.Document'));
		$this->redirect('index');
	}

	/**
	 * Checks if it is allowed to upload the file
	 *
	 * @param \TYPO3\Flow\Resource\Resource $resource
	 * @return boolean
	 */
	protected function isFileExtensionAllowed(\TYPO3\Flow\Resource\Resource $resource) {
		$allowedFileExtensions = $this->objectManager->getSettingsByPath(array('Document', 'allowedFileExtensions'));
		$allowedFileExtensions = \TYPO3\Flow\Utility\Arrays::trimExplode(',', $allowedFileExtensions);
		if (in_array($resource->getFileExtension(), $allowedFileExtensions)) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Verify if resource is valid
	 *
	 * @param $resource
	 */
	protected function verifyResource($resource) {
		if (!is_object($resource)) {
			$this->addFlashMessage($this->translator->translateById('document.noFileSelected', array(), NULL, NULL, 'Main', 'Beech.Document'));
			$this->redirect('index');
		}

		if ($this->isFileExtensionAllowed($resource->getOriginalResource()) === FALSE) {
			$this->addFlashMessage($this->translator->translateById('document.fileExtensionNotAllowed', array($resource->getOriginalResource()->getFileExtension()), NULL, NULL, 'Main', 'Beech.Document'));
			$this->redirect('index');
		}
	}
}

?>