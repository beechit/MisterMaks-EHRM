<?php
namespace Beech\Communication\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Communication\Domain\Model\MessageTemplate as MessageTemplate;

/**
 * Messagetemplate controller for the Beech.Communication package
 *
 * @Flow\Scope("singleton")
 */
class MessageTemplateController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Communication\Domain\Model\MessageTemplate';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\CLA\Domain\Repository\MessageTemplateRepository';

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \Beech\Communication\Domain\Model\MessageTemplate $messageTemplate A messageTemplate to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Communication\Domain\Model\MessageTemplate $messageTemplate) {
		$messageTemplate->setParty($this->persistenceManager->getIdentifierByObject($messageTemplate->getParty()));
		$this->repository->add($messageTemplate);
		$this->addFlashMessage($this->translator->translateById('Added.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

	/**
	 * @param \Beech\Communication\Domain\Model\MessageTemplate $messageTemplate A messageTemplate to update
	 *
	 * @return void
	 */
	public function updateAction(\Beech\Communication\Domain\Model\MessageTemplate $messageTemplate) {
		$messageTemplate->setParty($this->persistenceManager->getIdentifierByObject($messageTemplate->getParty()));
		$this->repository->update($messageTemplate);
		$this->addFlashMessage($this->translator->translateById('Updated.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

	/**
	 * @param \Beech\Communication\Domain\Model\MessageTemplate $messageTemplate A messageTemplate to remove
	 *
	 * @return void
	 */
	public function removeAction(\Beech\Communication\Domain\Model\MessageTemplate $messageTemplate) {
		$messageTemplate->setParty(NULL);
		$this->repository->update($messageTemplate);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

}
?>