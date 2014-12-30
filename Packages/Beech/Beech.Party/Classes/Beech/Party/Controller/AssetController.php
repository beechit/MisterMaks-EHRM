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
use Beech\Party\Domain\Model\Asset as Asset;

/**
 * Education controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class AssetController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 * @Flow\Inject
	 */
	protected $objectManager;

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Party\Domain\Model\Asset';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Party\Domain\Repository\AssetRepository';

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
		$this->view->assign('assets', $this->repository->findByParty($party));
	}

	/**
	 * @param \Beech\Party\Domain\Model\Asset $asset A asset to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Party\Domain\Model\Asset $asset = NULL) {
		$asset->setParty($this->persistenceManager->getIdentifierByObject($asset->getParty()));
		$this->repository->add($asset);
		$this->view->assign('asset', $asset);
		$this->view->assign('party', $asset->getParty());
		$this->view->assign('action', 'add');
	}

	public function newAction(){

	}

	/**
	 * @param \Beech\Party\Domain\Model\Asset $asset A asset to update
	 *
	 * @return void
	 */
	public function updateAction(\Beech\Party\Domain\Model\Asset $asset = NULL) {
		if ($this->getControllerContext()->getRequest()->getArgument('action') === 'remove') {
			$this->redirect('remove', 'Asset', NULL, array('asset' => $asset, 'person' => $asset->getParty()));
		} else {
			$asset->setParty($this->persistenceManager->getIdentifierByObject($asset->getParty()));
			$this->repository->update($asset);
			$this->addFlashMessage($this->translator->translateById('Updated.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
			$this->view->assign('asset', $asset);
			$this->view->assign('party', $asset->getParty());
			$this->view->assign('action', 'update');
		}
	}

	/**
	 * @param \Beech\Party\Domain\Model\Asset $asset A asset to remove
	 * @Flow\IgnoreValidation("$asset")
	 * @return void
	 */
	public function removeAction(\Beech\Party\Domain\Model\Asset $asset = NULL) {
		$this->repository->remove($asset);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

}

?>