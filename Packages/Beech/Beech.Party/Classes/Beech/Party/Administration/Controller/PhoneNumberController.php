<?php
namespace Beech\Party\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\PhoneNumber as PhoneNumber;

/**
 * PhoneNumber controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class PhoneNumberController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Party\Domain\Model\PhoneNumber';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Party\Domain\Repository\PhoneNumberRepository';

	/**
	 * @param \Beech\Party\Domain\Model\PhoneNumber $phoneNumber A new phoneNumber to delete
	 * @Flow\IgnoreValidation("$phoneNumber")
	 * @return void
	 */
	public function deleteAction(PhoneNumber $phoneNumber) {
		$this->repository->remove($phoneNumber);
		$this->addFlashMessage('Remove a phone number.');
		$this->redirect('list', 'Person');
		//$this->redirect('edit', 'Person', NULL, array('person' => $phoneNumber->getParty()));
	}
}

?>