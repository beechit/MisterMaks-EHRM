<?php
namespace Beech\WorkFlow\Tests\Functional;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Rens Admiraal <rens@beech.it>
 * Date: 27-08-12 21:05
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3,
	Beech\WorkFlow\Domain\Model\Action;

/**
 * Test the actual persistence of actions
 */
class PersistenceTest extends \TYPO3\FLOW3\Tests\FunctionalTestCase {

	/**
	* @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\WorkFlow\Domain\Repository\ActionRepository
	 */
	protected $actionRepository;

	/**
	 * Setup a testcase
	 */
	public function setUp() {
		parent::setUp();
		$this->actionRepository = $this->objectManager->get('\Beech\WorkFlow\Domain\Repository\ActionRepository');
	}

	/**
	 * @test
	 */
	public function anActionCanBeCreatedAndPersisted() {
		$action = new Action();
		$this->actionRepository->add($action);

		$this->persistenceManager->persistAll();

		$this->assertEquals(1, $this->actionRepository->countAll());
	}

	/**
	 * @test
	 */
	public function aPropertyValidatorCanBePersistedAndRetrieved() {
		$action = new Action();
		$namePropertyNotEmptyValidator = new \Beech\WorkFlow\Validators\Property\NotEmptyValidator();
		$action->addValidator($namePropertyNotEmptyValidator);
		$this->actionRepository->add($action);

		$this->persistenceManager->persistAll();

		$persistedAction = $this->actionRepository->findAll()->getFirst();

		$this->assertEquals($action, $persistedAction);
	}

	/**
	 * @test
	 */
	public function actionsCanBeRetrievedByStatus() {
		$statusList = array(
			Action::STATUS_EXPIRED,
			Action::STATUS_NEW,
			Action::STATUS_EXPIRED,
			Action::STATUS_FINISHED,
			Action::STATUS_NEW,
			Action::STATUS_EXPIRED,
			Action::STATUS_NEW,
			Action::STATUS_NEW,
			Action::STATUS_TERMINATED,
			Action::STATUS_STARTED
		);

		foreach ($statusList as $status) {
			$action = new Action();
			$action->setStatus($status);
			$this->actionRepository->add($action);
		}

		$this->persistenceManager->persistAll();

		$this->assertEquals(3, $this->actionRepository->countByStatus(Action::STATUS_EXPIRED));
		$this->assertEquals(4, $this->actionRepository->countByStatus(Action::STATUS_NEW));
		$this->assertEquals(1, $this->actionRepository->countByStatus(Action::STATUS_FINISHED));
		$this->assertEquals(1, $this->actionRepository->countByStatus(Action::STATUS_TERMINATED));
		$this->assertEquals(1, $this->actionRepository->countByStatus(Action::STATUS_STARTED));
	}


}

?>