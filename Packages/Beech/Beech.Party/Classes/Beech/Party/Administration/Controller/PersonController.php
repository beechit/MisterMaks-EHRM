<?php
namespace Beech\Party\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Person controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class PersonController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Party\Domain\Model\Person';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Party\Domain\Repository\PersonRepository';

	/**
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @return void
	 */
	public function createAction(\Beech\Party\Domain\Model\Person $person) {
		$this->repository->add($person);
		$this->documentManager->merge($person->getDocument());
		$this->addFlashMessage('Created a new person.');
		$this->redirect('list');
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @return void
	 */
	public function showAction(\Beech\Party\Domain\Model\Person $person) {
		$this->view->assign('person', $person);
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @return void
	 */
	public function updateAction(\Beech\Party\Domain\Model\Person $person) {
		$this->repository->update($person);
		$this->addFlashMessage('Updated the person.');
		$this->redirect('list');
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @return void
	 */
	public function editAction(\Beech\Party\Domain\Model\Person $person) {
		$this->view->assign('person', $person);
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @return void
	 */
	public function deleteAction(\Beech\Party\Domain\Model\Person $person) {
		$this->repository->remove($person);
		$this->redirect('list');
	}

}

?>