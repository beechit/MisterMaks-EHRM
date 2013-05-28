<?php
namespace Beech\Party\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-07-12 13:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

use \Beech\Party\Domain\Model\GroupType as GroupType;

/**
 * GroupType controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class GroupTypeController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @Flow\Inject
	 * @var \Beech\Party\Domain\Repository\GroupTypeRepository
	 */
	protected $groupTypeRepository;

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * Shows a list of group types
	 *
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('groupTypes', $this->groupTypeRepository->findAll());
	}

	/**
	 * Shows a single group type object
	 *
	 * @param \Beech\Party\Domain\Model\GroupType $groupType The group type to show
	 * @return void
	 */
	public function showAction(GroupType $groupType) {
		$this->view->assign('groupType', $groupType);
	}

	/**
	 * Shows a form for creating a new group type object
	 *
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * Adds the given new group type object to the group type repository
	 *
	 * @param \Beech\Party\Domain\Model\GroupType $newGroupType A new group type to add
	 * @return void
	 */
	public function createAction(GroupType $newGroupType) {
		$this->groupTypeRepository->add($newGroupType);
		$this->addFlashMessage('Created a new group type.');
		$this->redirect('list');
	}

	/**
	 * Shows a form for editing an existing group type object
	 *
	 * @param \Beech\Party\Domain\Model\GroupType $groupType The group type to edit
	 * @return void
	 */
	public function editAction(GroupType $groupType) {
		$this->view->assign('groupType', $groupType);
	}

	/**
	 * Updates the given group type object
	 *
	 * @param \Beech\Party\Domain\Model\GroupType $groupType The group type to update
	 * @return void
	 */
	public function updateAction(GroupType $groupType) {
		$this->groupTypeRepository->update($groupType);
		$this->addFlashMessage('Updated the group type.');
		$this->redirect('list');
	}

	/**
	 * Removes the given group type object from the group type repository
	 *
	 * @param \Beech\Party\Domain\Model\GroupType $groupType The group type to delete
	 * @return void
	 */
	public function deleteAction(GroupType $groupType) {
		$this->groupTypeRepository->remove($groupType);
		$this->addFlashMessage('Deleted a group type.');
		$this->redirect('list');
	}

}

?>