<?php
namespace Beech\Party\Controller;

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
use Beech\Party\Domain\Model\PhoneNumber as PhoneNumber;

/**
 * PhoneNumber controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class PhoneNumberController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Party\Domain\Model\PhoneNumber';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Party\Domain\Repository\PhoneNumberRepository';

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $party
	 */
	public function listAction(\TYPO3\Party\Domain\Model\AbstractParty $party = NULL) {
		$this->view->assign('party', $party);
		$this->view->assign('phoneNumbers', $this->repository->findByParty($party));
	}

	/**
	 * @param \Beech\Party\Domain\Model\PhoneNumber $phoneNumber A new phoneNumber to add
	 * @Flow\Validate(argumentName="phoneNumber", type="Beech.Party:PhoneNumber")
	 * @return void
	 */
	public function addAction(\Beech\Party\Domain\Model\PhoneNumber $phoneNumber = NULL) {
		$phoneNumber->setParty($this->persistenceManager->getIdentifierByObject($phoneNumber->getParty()));
		$this->repository->add($phoneNumber);
		$this->view->assign('phoneNumber', $phoneNumber);
		$this->view->assign('party', $phoneNumber->getParty());
		$this->view->assign('action', 'add');
	}

	/**
	 * @param \Beech\Party\Domain\Model\PhoneNumber $phoneNumber A  phoneNumber to update
	 * @Flow\Validate(argumentName="phoneNumber", type="Beech.Party:PhoneNumber")
	 * @return void
	 */
	public function updateAction(\Beech\Party\Domain\Model\PhoneNumber $phoneNumber = NULL) {
		if ($this->getControllerContext()->getRequest()->getArgument('action') === 'remove') {
			$this->redirect('remove', 'PhoneNumber', NULL, array('phoneNumber' => $phoneNumber, 'party' => $phoneNumber->getParty()));
		} else {
			$phoneNumber->setParty($this->persistenceManager->getIdentifierByObject($phoneNumber->getParty()));
			$this->repository->update($phoneNumber);
			$this->addFlashMessage($this->translator->translateById('Updated.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
			$this->view->assign('phoneNumber', $phoneNumber);
			$this->view->assign('party', $phoneNumber->getParty());
			$this->view->assign('action', 'update');
		}
	}

	/**
	 * @param \Beech\Party\Domain\Model\PhoneNumber $phoneNumber A new phoneNumber to remove
	 * @Flow\IgnoreValidation("$phoneNumber")
	 * @return void
	 */
	public function removeAction(\Beech\Party\Domain\Model\PhoneNumber $phoneNumber = NULL) {
		$this->repository->remove($phoneNumber);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

}

?>