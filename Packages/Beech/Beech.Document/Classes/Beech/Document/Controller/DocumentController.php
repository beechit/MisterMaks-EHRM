<?php
namespace Beech\Document\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use \Beech\Document\Domain\Model\Document;

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
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('documents', $this->documentRepository->findAll());
	}

	/**
	 * Adds the given new document object to the document repository
	 *
	 * @param \Beech\Document\Domain\Model\Document $document
	 * @return void
	 */
	public function createAction(\Beech\Document\Domain\Model\Document $document) {
		$this->documentRepository->add($document);
		$this->addFlashMessage($this->translator->translateById('document.documentUploaded', array(), NULL, NULL, 'Main', 'Beech.Document'));
		$this->redirect('index');
	}

	/**
	 * @param \Beech\Document\Domain\Model\Document $document
	 * @return void
	 */
	public function editAction(\Beech\Document\Domain\Model\Document $document) {
		$this->view->assign('document', $document);
	}

	/**
	 * @param \Beech\Document\Domain\Model\Document $document
	 * @return void
	 */
	public function showAction(\Beech\Document\Domain\Model\Document $document) {
		$this->view->assign('document', $document);
	}

	/**
	 * @param \Beech\Document\Domain\Model\Document $document
	 * @param integer $version
	 * @throws \Exception
	 * @return string
	 */
	public function showDocumentAction(\Beech\Document\Domain\Model\Document $document, $version) {
		$attachments = $document->getAttachments();
		if (!isset($attachments[$version])) {
			throw new \Exception('Document version %s not found', $version);
		}

			// TODO: Use the mimetype
		$this->response->setHeader('Content-Type', $attachments[$version]->getContentType());
		$this->response->setHeader('Content-Disposition', 'attachment; filename="' . $document->getName() . '"');
		$this->response->setContent($attachments[$version]->getRawData());

		return '';
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

}

?>