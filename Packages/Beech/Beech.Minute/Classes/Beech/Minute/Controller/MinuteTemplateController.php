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