<?php
namespace Beech\Minute\Controller;

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
use Beech\Minute\Domain\Model\Minute;

/**
 * Minute controller for the Beech.Minute package
 *
 * @Flow\Scope("singleton")
 */
class MinuteController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \Beech\Minute\Domain\Repository\MinuteRepository
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
	 * @param \Beech\Minute\Domain\Model\Minute $minute The minute to show
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
	 * @param \Beech\Minute\Domain\Model\Minute $newMinute A new minute to add
	 * @return void
	 */
	public function createAction(\Beech\Minute\Domain\Model\Minute $newMinute) {
		$this->minuteRepository->add($newMinute);
		$this->addFlashMessage($this->translator->translateById('Added.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
		$this->redirect('list');
	}

	/**
	 * Shows a form for editing an existing Minute object
	 *
	 * @param \Beech\Minute\Domain\Model\Minute $minute The Minute to edit
	 * @Flow\IgnoreValidation("$minute")
	 * @return void
	 */
	public function editAction(Minute $minute) {
		$this->view->assign('minute', $minute);
	}

	/**
	 * Updates the given Minute object
	 *
	 * @param \Beech\Minute\Domain\Model\Minute $minute The Minute to update
	 * @return void
	 */
	public function updateAction(\Beech\Minute\Domain\Model\Minute $minute) {
		$this->minuteRepository->update($minute);
		$this->addFlashMessage($this->translator->translateById('Updated', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
		$this->redirect('list');
	}

	/**
	 * Removes the given Minute object from the Minute repository
	 *
	 * @param \Beech\Minute\Domain\Model\Minute $minute The Minute to delete
	 * @return void
	 */
	public function deleteAction(\Beech\Minute\Domain\Model\Minute $minute) {
		$this->minuteRepository->remove($minute);
		$this->addFlashMessage($this->translator->translateById('Removed', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
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