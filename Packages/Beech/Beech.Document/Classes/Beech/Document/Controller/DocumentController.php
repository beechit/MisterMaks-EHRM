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
	 * @var \Beech\Document\Domain\Repository\DocumentTypeRepository
	 * @Flow\Inject
	 */
	protected $documentTypeRepository;

	/**
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 * @Flow\Inject
	 */
	protected $objectManager;

	/**
	 * Shows a list of documents
	 *
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $party
	 * @return void
	 */
	public function listAction(\TYPO3\Party\Domain\Model\AbstractParty $party = NULL) {
		$this->view->assign('documentCategories', $this->documentTypeRepository->findAllGroupedByCategories());
		if ($party != NULL) {
			$this->view->assign('documents', $this->documentRepository->findByParty($party));
		} else {
			$this->view->assign('documents', $this->documentRepository->findAll());
		}
	}

	public function newAction(\TYPO3\Party\Domain\Model\AbstractParty $party = NULL) {
		$this->view->assign('documentCategories', $this->documentTypeRepository->findAllGroupedByCategories());
		$this->view->assign('party', $party);
		if ($party != NULL) {
			$this->view->assign('documents', $this->documentRepository->findByParty($party));
		} else {
			$this->view->assign('documents', $this->documentRepository->findAll());
		}
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
		if ($document->getParty() != NULL) {
			$this->redirect('list', 'Document', 'Beech.Document', array('party' => $document->getParty()));
		} else {
			$this->redirect('list');
		}
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
	 * @Flow\IgnoreValidation("$document")
	 * @return string
	 */
	public function downloadAction(\Beech\Document\Domain\Model\Document $document) {

		$attachments = $document->getResources();
		$attachment = reset($attachments);

		$name = key($attachments);

		$this->response->setHeader('Content-Type', $attachment->getContentType());
		$this->response->setHeader('Content-Disposition', 'attachment; filename="' . $name . '"');
		$this->response->setContent($attachment->getRawData());

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
		$this->redirect('list');
	}

	/**
	 * Removes the given document object
	 *
	 * @param \Beech\Document\Domain\Model\Document $document The document to delete
	 * @Flow\IgnoreValidation("$document")
	 * @return void
	 */
	public function deleteAction(\Beech\Document\Domain\Model\Document $document) {
		$this->documentRepository->remove($document);
		$this->addFlashMessage($this->translator->translateById('document.documentDeleted', array(), NULL, NULL, 'Main', 'Beech.Document'));
		$this->emberRedirect('#/documents');
	}



}

?>