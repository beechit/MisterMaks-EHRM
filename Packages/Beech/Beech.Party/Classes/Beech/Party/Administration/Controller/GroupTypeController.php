<?php
namespace Beech\Party\Administration\Controller;

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