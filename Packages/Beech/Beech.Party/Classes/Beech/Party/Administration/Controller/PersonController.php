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
		$this->assetRepository->update($asset);
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
		$this->assetRepository->update($asset);
		$this->addFlashMessage('Removed.');
		$this->redirect('edit', NULL, NULL, array('person' => $person));
	}

	/**
	 * @param \Beech\Party\Domain\Model\Licence $licence A new licence to add
	 *
	 * @return void
	 */
	public function addLicenceAction(\Beech\Party\Domain\Model\Licence $licence) {
		$licence->setParty($this->persistenceManager->getIdentifierByObject($licence->getParty()));
		$this->licenceRepository->add($licence);
		$this->addFlashMessage('Added.');
		$this->redirect('edit', NULL, NULL, array('person' => $licence->getParty()));
	}

	/**
	 * @param \Beech\Party\Domain\Model\Licence $licence A licence to update
	 *
	 * @return void
	 */
	public function updateLicenceAction(\Beech\Party\Domain\Model\Licence $licence) {
		$licence->setParty($this->persistenceManager->getIdentifierByObject($licence->getParty()));
		$this->licenceRepository->update($licence);
		$this->addFlashMessage('Updated.');
		$this->redirect('edit', NULL, NULL, array('person' => $licence->getParty()));
	}

	/**
	 * @param \Beech\Party\Domain\Model\Licence $licence A licence to remove
	 *
	 * @return void
	 */
	public function removeLicenceAction(\Beech\Party\Domain\Model\Licence $licence) {
		$person = $licence->getParty();
		$licence->setParty(NULL);
		$this->licenceRepository->update($licence);
		$this->addFlashMessage('Removed.');
		$this->redirect('edit', NULL, NULL, array('person' => $person));
	}

}

?>