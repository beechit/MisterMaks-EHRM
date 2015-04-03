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
use Beech\Party\Domain\Model\ElectronicAddress as ElectronicAddress;

/**
 * ElectronicAddress controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class ElectronicAddressController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Party\Domain\Model\ElectronicAddress';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Party\Domain\Repository\ElectronicAddressRepository';

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
		$this->view->assign('electronicAddresses', $this->repository->findByParty($party));
	}

	/**
	 * @param \Beech\Party\Domain\Model\ElectronicAddress $electronicAddress A electronicAddress to add
	 * @Flow\Validate(argumentName="electronicAddress", type="Beech.Party:ElectronicAddress")
	 * @return void
	 */
	public function addAction(\Beech\Party\Domain\Model\ElectronicAddress $electronicAddress = NULL) {
		$electronicAddress->setParty($this->persistenceManager->getIdentifierByObject($electronicAddress->getParty()));
		$this->repository->add($electronicAddress);
		$this->view->assign('electronicAddress', $electronicAddress);
		$this->view->assign('party', $electronicAddress->getParty());
		$this->view->assign('action', 'add');
	}

	/**
	 * @param \Beech\Party\Domain\Model\ElectronicAddress $electronicAddress A electronicAddress to update
	 * @Flow\Validate(argumentName="electronicAddress", type="Beech.Party:ElectronicAddress")
	 * @return void
	 */
	public function updateAction(\Beech\Party\Domain\Model\ElectronicAddress $electronicAddress = NULL) {
		if ($this->getControllerContext()->getRequest()->getArgument('action') === 'remove') {
			$this->redirect('remove', 'ElectronicAddress', NULL, array('electronicAddress' => $electronicAddress, 'party' => $electronicAddress->getParty()));
		} else {
			$electronicAddress->setParty($this->persistenceManager->getIdentifierByObject($electronicAddress->getParty()));
			$this->repository->update($electronicAddress);
			$this->addFlashMessage($this->translator->translateById('Updated.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
			$this->view->assign('electronicAddress', $electronicAddress);
			$this->view->assign('party', $electronicAddress->getParty());
			$this->view->assign('action', 'update');
		}
	}

	/**
	 * @param \Beech\Party\Domain\Model\ElectronicAddress $electronicAddress A electronicAddress to remove
	 * @Flow\IgnoreValidation("$electronicAddress")
	 * @return void
	 */
	public function removeAction(\Beech\Party\Domain\Model\ElectronicAddress $electronicAddress = NULL) {
		$this->repository->remove($electronicAddress);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

}

?>