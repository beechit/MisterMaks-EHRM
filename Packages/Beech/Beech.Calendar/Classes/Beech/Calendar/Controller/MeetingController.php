<?php
namespace Beech\Calendar\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Calendar\Domain\Model\Meeting as Meeting;

/**
 * Meeting controller for the Beech.Calendar package
 *
 * @Flow\Scope("singleton")
 */
class MeetingController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Calendar\Domain\Model\Meeting';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Calendar\Domain\Repository\MeetingRepository';

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \Beech\Calendar\Domain\Model\Meeting $meeting A meeting to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Calendar\Domain\Model\Meeting $meeting) {
		$meeting->setParty($this->persistenceManager->getIdentifierByObject($meeting->getParty()));
		$this->repository->add($meeting);
		$this->addFlashMessage($this->translator->translateById('Added', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

	/**
	 * @param \Beech\Calendar\Domain\Model\Meeting $meeting A meeting to update
	 *
	 * @return void
	 */
	public function updateAction(\Beech\Calendar\Domain\Model\Meeting $meeting) {
		$meeting->setParty($this->persistenceManager->getIdentifierByObject($meeting->getParty()));
		$this->repository->update($meeting);
		$this->addFlashMessage($this->translator->translateById('Updated', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

	/**
	 * @param \Beech\Calendar\Domain\Model\Meeting $meeting A meeting to remove
	 *
	 * @return void
	 */
	public function removeAction(\Beech\Calendar\Domain\Model\Meeting $meeting) {
		$meeting->setParty(NULL);
		$this->repository->update($meeting);
		$this->addFlashMessage($this->translator->translateById('Removed', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

}
?>