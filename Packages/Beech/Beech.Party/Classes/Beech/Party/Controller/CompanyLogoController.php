<?php
namespace Beech\Party\Controller;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

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