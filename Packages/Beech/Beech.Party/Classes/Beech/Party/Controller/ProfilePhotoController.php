<?php
namespace Beech\Party\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Document\Domain\Model\DocumentType;
use TYPO3\Flow\Annotations as Flow;
use \Beech\Document\Domain\Model\Document;

/**
 *
 * @Flow\Scope("singleton")
 */
class ProfilePhotoController extends \Beech\Document\Controller\DocumentController {

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 * @Flow\Inject
	 */
	protected $personRepository;

	/**
	 * @var \Beech\Document\Domain\Repository\DocumentTypeRepository
	 * @Flow\Inject
	 */
	protected $documentTypeRepository;

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
	 * Notice: Its called 'changeAction' to have no conflicts with parent 'editAction'
	 *
	 * @param \Beech\Document\Domain\Model\Document $document
	 * @param \Beech\Party\Domain\Model\Person $party
	 * @return void
	 */
	public function editAction(\Beech\Document\Domain\Model\Document $document = NULL, \Beech\Party\Domain\Model\Person $party = NULL) {
		$this->view->assign('document', $document);
		$this->view->assign('party', $party);
	}

	/**
	 * Add action, used to store  profile photo
	 *
	 * @param \Beech\Document\Domain\Model\Document $document
	 * @param \Beech\Party\Domain\Model\Person $party
	 * @return void
	 */
	public function updateAction(\Beech\Document\Domain\Model\Document $document, \Beech\Party\Domain\Model\Person $party = NULL) {
		$documentType = $this->documentTypeRepository->findOneByTypeName(DocumentType::PROFILE_PHOTO);
		$document->setDocumentType($documentType);
		$this->documentRepository->add($document);
		$identifier = $this->persistenceManager->getIdentifierByObject($document);
		$party->setProfilePhoto($identifier);
		$this->personRepository->update($party);
		$this->redirect('edit', NULL, NULL, array('document' => $document, 'party' => $party));
	}
}

?>