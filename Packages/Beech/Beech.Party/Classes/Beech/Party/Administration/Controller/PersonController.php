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
	 * @return void
	 */
	public function createAction() {
		$this->storeData('add');
		$this->addFlashMessage('Created a new person.');
		$this->redirect('list');
	}

	/**
	 * @return void
	 */
	public function updateAction() {
		$this->storeData('update');
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

	/**
	 * Stores the data in the repository object
	 * @param string $method Either 'add' or 'update'
	 * @throws \Exception
	 * @return void
	 */
	protected function storeData($method) {
		$objects = $this->request->getInternalArgument('__objects');
		if (isset($objects[0]) && $objects[0] instanceof \Beech\Party\Domain\Model\Person) {
			$this->repository->$method($objects[0]);
		} else {
			throw \Exception('Required argument \$person not set');
		}
	}

}

?>