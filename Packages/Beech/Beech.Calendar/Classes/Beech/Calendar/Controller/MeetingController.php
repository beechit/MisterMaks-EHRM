<?php
namespace Beech\Calendar\Controller;

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