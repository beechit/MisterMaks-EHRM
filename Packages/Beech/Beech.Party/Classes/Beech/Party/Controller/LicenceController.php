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
use Beech\Party\Domain\Model\Licence as Licence;

/**
 * Education controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class LicenceController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 * @Flow\Inject
	 */
	protected $objectManager;

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Party\Domain\Model\Licence';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Party\Domain\Repository\LicenceRepository';

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
		$this->view->assign('licences', $this->repository->findByParty($party));
	}

	/**
	 * @param \Beech\Party\Domain\Model\Licence $licence A licence to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Party\Domain\Model\Licence $licence = NULL) {
		$licence->setParty($this->persistenceManager->getIdentifierByObject($licence->getParty()));
		$this->repository->add($licence);
		$this->view->assign('licence', $licence);
		$this->view->assign('party', $licence->getParty());
		$this->view->assign('action', 'add');
	}

	public function newAction(){

	}

	/**
	 * @param \Beech\Party\Domain\Model\Licence $licence A licence to update
	 *
	 * @return void
	 */
	public function updateAction(\Beech\Party\Domain\Model\licence $licence = NULL) {
		if ($this->getControllerContext()->getRequest()->getArgument('action') === 'remove') {
			$this->redirect('remove', 'Licence', NULL, array('licence' => $licence, 'party' => $licence->getParty()));
		} else {
			$licence->setParty($this->persistenceManager->getIdentifierByObject($licence->getParty()));
			$this->repository->update($licence);
			$this->addFlashMessage($this->translator->translateById('Updated.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
			$this->view->assign('licence', $licence);
			$this->view->assign('party', $licence->getParty());
			$this->view->assign('action', 'update');
		}
	}

	/**
	 * @param \Beech\Party\Domain\Model\licence $licence A licence to remove
	 *
	 * @return void
	 */
	public function removeAction(\Beech\Party\Domain\Model\licence $licence = NULL) {
		$this->repository->remove($licence);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}
}

?>