<?php
namespace Beech\Party\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\Licence as Licence;

/**
 * Licence controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class LicenceController extends \Beech\Party\Controller\LicenceController {

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \Beech\Party\Domain\Model\Licence $licence A licence to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Party\Domain\Model\licence $licence) {
		$licence->setParty($this->persistenceManager->getIdentifierByObject($licence->getParty()));
		$this->repository->add($licence);
		$this->addFlashMessage($this->translator->translateById('Added.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

	/**
	 * @param \Beech\Party\Domain\Model\licence $licence A licence to update
	 *
	 * @return void
	 */
	public function updateAction(\Beech\Party\Domain\Model\licence $licence) {
		$licence->setParty($this->persistenceManager->getIdentifierByObject($licence->getParty()));
		$this->repository->update($licence);
		$this->addFlashMessage($this->translator->translateById('Updated.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

	/**
	 * @param \Beech\Party\Domain\Model\licence $licence A licence to remove
	 *
	 * @return void
	 */
	public function removeAction(\Beech\Party\Domain\Model\licence $licence) {
		$licence->setParty(NULL);
		$this->repository->update($licence);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}
}

?>
