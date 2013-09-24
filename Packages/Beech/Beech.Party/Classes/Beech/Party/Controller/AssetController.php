<?php
namespace Beech\Party\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

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
	public function listAction(\TYPO3\Party\Domain\Model\AbstractParty $party) {
		$this->view->assign('party', $party);
		$this->view->assign('assets', $this->repository->findByParty($party));
	}

	/**
	 * @param \Beech\Party\Domain\Model\Asset $asset A asset to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Party\Domain\Model\Asset $asset) {
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
	public function updateAction(\Beech\Party\Domain\Model\Asset $asset) {
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
	public function removeAction(\Beech\Party\Domain\Model\Asset $asset) {
		$this->repository->remove($asset);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

}

?>