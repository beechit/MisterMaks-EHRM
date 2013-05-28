<?php
namespace Beech\Minute\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Minute\Domain\Model\MinuteTemplate as MinuteTemplate;

/**
 * MinuteTemplate controller for the Beech.Minute package
 *
 * @Flow\Scope("singleton")
 */
class MinuteTemplateController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Minute\Domain\Model\MinuteTemplate';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Minute\Domain\Repository\MinuteTemplateRepository';

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \Beech\Minute\Domain\Model\MinuteTemplate $minuteTemplate A minuteTemplate to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Minute\Domain\Model\MinuteTemplate $minuteTemplate) {
		$minuteTemplate->setParty($this->persistenceManager->getIdentifierByObject($minuteTemplate->getParty()));
		$this->repository->add($minuteTemplate);
		$this->addFlashMessage($this->translator->translateById('Added.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

	/**
	 * @param \Beech\Minute\Domain\Model\MinuteTemplate $minuteTemplate A minuteTemplate to update
	 *
	 * @return void
	 */
	public function updateAction(\Beech\Minute\Domain\Model\MinuteTemplate $minuteTemplate) {
		$minuteTemplate->setParty($this->persistenceManager->getIdentifierByObject($minuteTemplate->getParty()));
		$this->repository->update($minuteTemplate);
		$this->addFlashMessage($this->translator->translateById('Updated.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

	/**
	 * @param \Beech\Minute\Domain\Model\MinuteTemplate $minuteTemplate A minuteTemplate to remove
	 *
	 * @return void
	 */
	public function removeAction(\Beech\Minute\Domain\Model\MinuteTemplate $minuteTemplate) {
		$minuteTemplate->setParty(NULL);
		$this->repository->update($minuteTemplate);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

}
?>