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
 * Address controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class AddressController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Party\Domain\Model\Address';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Party\Domain\Repository\AddressRepository';

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
		$this->view->assign('addresses', $this->repository->findByParty($party));
	}

	/**
	 * @param \Beech\Party\Domain\Model\Address $address A address to add
	 * @Flow\Validate(argumentName="address", type="Beech.Party:Address")
	 * @return void
	 */
	public function addAction(\Beech\Party\Domain\Model\Address $address = NULL) {
		$address->setParty($this->persistenceManager->getIdentifierByObject($address->getParty()));
		$this->repository->add($address);
		$this->view->assign('address', $address);
		$this->view->assign('party', $address->getParty());
		$this->view->assign('action', 'add');
	}

	/**
	 * @param \Beech\Party\Domain\Model\Address $address A address to update
	 * @Flow\Validate(argumentName="address", type="Beech.Party:Address")
	 * @return void
	 */
	public function updateAction(\Beech\Party\Domain\Model\Address $address = NULL) {
		if	($this->getControllerContext()->getRequest()->getArgument('action') === 'remove') {
			$this->redirect('remove', 'Address', NULL, array('address' => $address, 'party' => $address->getParty()));
		} else {
			$address->setParty($this->persistenceManager->getIdentifierByObject($address->getParty()));
			$this->repository->update($address);
			$this->addFlashMessage($this->translator->translateById('Updated.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
			$this->view->assign('address', $address);
			$this->view->assign('party', $address->getParty());
			$this->view->assign('action', 'update');
		}
	}

	/**
	 * @param \Beech\Party\Domain\Model\Address $address A address to remove
	 * @Flow\IgnoreValidation("address")
	 * @return void
	 */
	public function removeAction(\Beech\Party\Domain\Model\Address $address = NULL) {
		$this->repository->remove($address);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

}

?>