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
class CompanyLogoController extends \Beech\Document\Controller\DocumentController {

	/**
	 * @var \Beech\Party\Domain\Repository\CompanyRepository
	 * @Flow\Inject
	 */
	protected $companyRepository;

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
	 * @param \Beech\Party\Domain\Model\Company $company
	 * @return void
	 */
	public function editAction(\Beech\Document\Domain\Model\Document $document = NULL, \Beech\Party\Domain\Model\Company $company = NULL) {
		$this->view->assign('document', $document);
		$this->view->assign('company', $company);
	}

	/**
	 * Save action, used to store  company logo
	 *
	 * @param \Beech\Document\Domain\Model\Document $document
	 * @param \Beech\Party\Domain\Model\Company $company
	 * @return void
	 */
	public function updateAction(\Beech\Document\Domain\Model\Document $document = NULL, \Beech\Party\Domain\Model\Company $company = NULL) {
		$documentType = $this->documentTypeRepository->findOneByTypeName(DocumentType::COMPANY_LOGO);
		$document->setDocumentType($documentType);
		$this->documentRepository->add($document);
		$identifier = $this->persistenceManager->getIdentifierByObject($document);
		$company->setLogo($identifier);
		$this->companyRepository->update($company);

		$this->emberRedirect('#/person/show/'.$company->getId());
	}
}

?>