<?php
namespace Beech\Party\Controller\Management;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 16-10-12 15:41
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use \Beech\Party\Domain\Model\Group;

/**
 * Group controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class GroupController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @Flow\Inject
	 * @var \Beech\Party\Domain\Repository\GroupRepository
	 */
	protected $groupRepository;

	/**
	 * @Flow\Inject
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 */
	protected $personRepository;

	/**
	 * @Flow\Inject
	 * @var \Beech\Party\Domain\Repository\GroupTypeRepository
	 */
	protected $groupTypeRepository;

	/**
	 * Shows a list of groups
	 *
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('groups', $this->groupRepository->findAll());
	}

	/**
	 * Shows a single group object
	 *
	 * @param \Beech\Party\Domain\Model\Group $group The group to show
	 * @return void
	 */
	public function showAction(Group $group) {
		$this->view->assign('group', $group);
	}

	/**
	 * Shows a form for creating a new group object
	 *
	 * @return void
	 */
	public function newAction() {
		$this->view->assign('groupTypes', $this->groupTypeRepository->findAll()->toArray());
		$this->view->assign('members', $this->personRepository->findAll()->toArray());
		$this->view->assign('children', $this->groupRepository->findAll()->toArray());
	}

	/**
	 * Adds the given new group object to the group repository
	 *
	 * @param \Beech\Party\Domain\Model\Group $newGroup A new group to add
	 * @return void
	 */
	public function createAction(Group $newGroup) {
		$this->groupRepository->add($newGroup);
		$this->addFlashMessage('Created a new group.');
		$this->redirect('list');
	}

	/**
	 * Shows a form for editing an existing group object
	 *
	 * @param \Beech\Party\Domain\Model\Group $group The group to edit
	 * @return void
	 */
	public function editAction(Group $group) {
		$this->view->assign('groupTypes', $this->groupTypeRepository->findAll()->toArray());
		$this->view->assign('members', $this->personRepository->findAll()->toArray());
		$this->view->assign('children', $this->groupRepository->findPossibleChildren($group)->toArray());
		$this->view->assign('group', $group);
	}

	/**
	 * Updates the given group object
	 *
	 * @param \Beech\Party\Domain\Model\Group $group The group to update
	 * @return void
	 */
	public function updateAction(Group $group) {
		$this->groupRepository->update($group);
		$this->addFlashMessage('Updated the group.');
		$this->redirect('list');
	}

	/**
	 * Removes the given group object from the group repository
	 *
	 * @param \Beech\Party\Domain\Model\Group $group The group to delete
	 * @return void
	 */
	public function deleteAction(Group $group) {
		$this->groupRepository->remove($group);
		$this->addFlashMessage('Deleted a group.');
		$this->redirect('list');
	}

}

?>