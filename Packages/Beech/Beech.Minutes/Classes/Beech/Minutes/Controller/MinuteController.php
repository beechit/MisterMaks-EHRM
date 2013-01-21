<?php
namespace Beech\Minutes\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 03-10-12 12:26
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Minutes\Domain\Model\Minute;

/**
 * Minute controller for the Beech.Minutes package
 *
 * @Flow\Scope("singleton")
 */
class MinuteController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \Beech\Minutes\Domain\Repository\MinuteRepository
	 * @Flow\Inject
	 */
	protected $minuteRepository;

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * Shows a list of minutes
	 *
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('minutes', $this->minuteRepository->findAll());
	}

	/**
	 * Shows a single minute object
	 *
	 * @param \Beech\Minutes\Domain\Model\Minute $minute The minute to show
	 * @return void
	 */
	public function showAction(Minute $minute) {
		$this->view->assign('minute', $minute);
	}

	/**
	 * Shows a form for creating a new minute object
	 *
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * Adds the given new Minute object to the Minute repository
	 *
	 * @param \Beech\Minutes\Domain\Model\Minute $newMinute A new minute to add
	 * @return void
	 */
	public function createAction(\Beech\Minutes\Domain\Model\Minute $newMinute) {
		$this->minuteRepository->add($newMinute);
		$this->addFlashMessage($this->translator->translateById('flashMessage.createdMinute', array(), NULL, NULL, 'Main', 'Beech.Minutes'));
		$this->redirect('list');
	}

	/**
	 * Shows a form for editing an existing Minute object
	 *
	 * @param \Beech\Minutes\Domain\Model\Minute $minute The Minute to edit
	 * @Flow\IgnoreValidation("$minute")
	 * @return void
	 */
	public function editAction(Minute $minute) {
		$this->view->assign('minute', $minute);
	}

	/**
	 * Updates the given Minute object
	 *
	 * @param \Beech\Minutes\Domain\Model\Minute $minute The Minute to update
	 * @return void
	 */
	public function updateAction(\Beech\Minutes\Domain\Model\Minute $minute) {
		$this->minuteRepository->update($minute);
		$this->addFlashMessage($this->translator->translateById('flashMessage.updatedMinute', array(), NULL, NULL, 'Main', 'Beech.Minutes'));
		$this->redirect('list');
	}

	/**
	 * Removes the given Minute object from the Minute repository
	 *
	 * @param \Beech\Minutes\Domain\Model\Minute $minute The Minute to delete
	 * @return void
	 */
	public function deleteAction(\Beech\Minutes\Domain\Model\Minute $minute) {
		$this->minuteRepository->remove($minute);
		$this->addFlashMessage($this->translator->translateById('flashMessage.deletedMinute', array(), NULL, NULL, 'Main', 'Beech.Minutes'));
		$this->redirect('list');
	}

	/**
	 * Redirect to list action
	 *
	 * @return void
	 */
	public function redirectAction() {
		$this->redirect('list');
	}
}
?>