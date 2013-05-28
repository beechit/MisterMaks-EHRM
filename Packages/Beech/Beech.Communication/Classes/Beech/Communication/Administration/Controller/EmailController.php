<?php
namespace Beech\Communication\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 24-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Communication\Domain\Model\Email;

/**
 * Email controller for the Beech.Communication package
 *
 * @Flow\Scope("singleton")
 */
class EmailController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Communication\Domain\Model\Email';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Communication\Domain\Repository\EmailRepository';

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \Beech\Communication\Domain\Model\Email $email A email to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Communication\Domain\Model\Email $email) {
		$email->setParty($this->persistenceManager->getIdentifierByObject($email->getParty()));
		$this->repository->add($email);
		$this->addFlashMessage($this->translator->translateById('Added.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

	/**
	 * @param \Beech\Communication\Domain\Model\Email $email A email to update
	 *
	 * @return void
	 */
	public function updateAction(\Beech\Communication\Domain\Model\Email $email) {
		$email->setParty($this->persistenceManager->getIdentifierByObject($email->getParty()));
		$this->repository->update($email);
		$this->addFlashMessage($this->translator->translateById('Updated.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

	/**
	 * @param \Beech\Communication\Domain\Model\Email $email A email to remove
	 *
	 * @return void
	 */
	public function removeAction(\Beech\Communication\Domain\Model\Email $email) {
		$email->setParty(NULL);
		$this->repository->update($email);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));;
	}

}
?>