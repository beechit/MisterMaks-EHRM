<?php
namespace Beech\Communication\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Communication\Domain\Model\EmailTemplate as EmailTemplate;

/**
 * Emailtemplate controller for the Beech.Communication package
 *
 * @Flow\Scope("singleton")
 */
class EmailTemplateController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Communication\Domain\Model\EmailTemplate';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\CLA\Domain\Repository\EmailTemplateRepository';

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \Beech\Communication\Domain\Model\EmailTemplate $emailTemplate A emailTemplate to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Communication\Domain\Model\EmailTemplate $emailTemplate) {
		$emailTemplate->setParty($this->persistenceManager->getIdentifierByObject($emailTemplate->getParty()));
		$this->repository->add($emailTemplate);
		$this->addFlashMessage($this->translator->translateById('Added.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

	/**
	 * @param \Beech\Communication\Domain\Model\EmailTemplate $emailTemplate A emailTemplate to update
	 *
	 * @return void
	 */
	public function updateAction(\Beech\Communication\Domain\Model\EmailTemplate $emailTemplate) {
		$emailTemplate->setParty($this->persistenceManager->getIdentifierByObject($emailTemplate->getParty()));
		$this->repository->update($emailTemplate);
		$this->addFlashMessage($this->translator->translateById('Updated.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

	/**
	 * @param \Beech\Communication\Domain\Model\EmailTemplate $emailTemplate A emailTemplate to remove
	 *
	 * @return void
	 */
	public function removeAction(\Beech\Communication\Domain\Model\EmailTemplate $emailTemplate) {
		$emailTemplate->setParty(NULL);
		$this->repository->update($emailTemplate);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

}
?>