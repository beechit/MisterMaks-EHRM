<?php
namespace Beech\Document\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Document\Domain\Model\DocumentType;

/**
 * Document Category controller for the Beech.Document package
 *
 * @Flow\Scope("singleton")
 */
class DocumentTypeController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Document\Domain\Model\DocumentType';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Document\Domain\Repository\DocumentTypeRepository';

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \Beech\Document\Domain\Model\DocumentType $documentType A DocumentType to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Document\Domain\Model\DocumentType $documentType) {
		$documentType->setParty($this->persistenceManager->getIdentifierByObject($documentType->getParty()));
		$this->repository->add($documentType);
		$this->addFlashMessage($this->translator->translateById('Added.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

	/**
	 * @param \Beech\Document\Domain\Model\DocumentType $documentType A documentType to update
	 *
	 * @return void
	 */
	public function updateAction(\Beech\Document\Domain\Model\DocumentType $documentType) {
		$documentType->setParty($this->persistenceManager->getIdentifierByObject($documentType->getParty()));
		$this->repository->update($documentType);
		$this->addFlashMessage($this->translator->translateById('Updated', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

	/**
	 * @param \Beech\Document\Domain\Model\DocumentType $documentType A documentType to remove
	 *
	 * @return void
	 */
	public function removeAction(\Beech\Document\Domain\Model\DocumentType $documentType) {
		$documentType->setParty(NULL);
		$this->repository->update($documentType);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));

	}

}
?>