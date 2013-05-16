<?php
namespace Beech\Party\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\Person as Person;

/**
 * Person controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class PersonController extends \Beech\Party\Controller\PersonController {

	/**
	 * @param \Beech\Party\Domain\Model\Person $person A new person to update
	 * @return void
	 */
	public function updateAction(Person $person) {
		$this->repository->update($person);
		$this->addFlashMessage('Person is updated.');
		$this->emberRedirect('#/administration/person');
	}

	/**
	 * @param \Beech\Party\Domain\Model\Asset $Asset A new asset to add
	 *
	 * @return void
	 */
	public function addAssetAction(\Beech\Party\Domain\Model\Asset $asset) {
		$asset->setParty($this->persistenceManager->getIdentifierByObject($asset->getParty()));
		$this->assetRepository->add($asset);
		$this->addFlashMessage('Added.');
		$this->redirect('edit', NULL, NULL, array('person' => $asset->getParty()));
	}

	/**
	 * @param \Beech\Party\Domain\Model\Asset $asset A asset to update
	 *
	 * @return void
	 */
	public function updateAssetAction(\Beech\Party\Domain\Model\Asset $asset) {
		$asset->setParty($this->persistenceManager->getIdentifierByObject($asset->getParty()));
		$this->educationRepository->update($asset);
		$this->addFlashMessage('Updated.');
		$this->redirect('edit', NULL, NULL, array('person' => $asset->getParty()));
	}

	/**
	 * @param \Beech\Party\Domain\Model\Asset $asset A asset to remove
	 *
	 * @return void
	 */
	public function removeAssetAction(\Beech\Party\Domain\Model\Asset $asset) {
		$person = $asset->getParty();
		$asset->setParty(NULL);
		$this->educationRepository->update($asset);
		$this->addFlashMessage('Removed.');
		$this->redirect('edit', NULL, NULL, array('person' => $person));
	}

}

?>