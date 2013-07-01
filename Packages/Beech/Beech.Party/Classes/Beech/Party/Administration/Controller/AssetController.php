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
class AssetController extends \Beech\Party\Controller\AssetController {

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \Beech\Party\Domain\Model\Asset $Asset A new asset to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Party\Domain\Model\Asset $asset) {
		$asset->setParty($this->persistenceManager->getIdentifierByObject($asset->getParty()));
		$this->Repository->add($asset);
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
		$asset->setParty($this->persistenceManager->getIdentifierByObject($asset->getParty()));
		$this->Repository->update($asset);
		$this->addFlashMessage($this->translator->translateById('Updated.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
		$this->view->assign('asset', $Asset);
		$this->view->assign('party', $asset->getParty());
		$this->view->assign('action', 'update');
	}

	/**
	 * @param \Beech\Party\Domain\Model\Asset $asset A asset to remove
	 *
	 * @return void
	 */
	public function removeAction(\Beech\Party\Domain\Model\Asset $asset) {
		$person = $asset->getParty();
		$asset->setParty(NULL);
		$this->Repository->update($asset);
		$this->addFlashMessage('Removed.');
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

}

?>