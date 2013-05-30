<?php
namespace Beech\Party\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\Asset as Asset;

/**
 * asset controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class AssetController extends \Beech\Ehrm\Controller\AbstractManagementController {

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
	 * @param \Beech\Party\Domain\Model\Asset $Asset A asset to add
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
		$asset->setParty(NULL);
		$this->repository->update($asset);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

}

?>
