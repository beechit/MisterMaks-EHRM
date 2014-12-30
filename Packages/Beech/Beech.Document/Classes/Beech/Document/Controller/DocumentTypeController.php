<?php
namespace Beech\Document\Controller;

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