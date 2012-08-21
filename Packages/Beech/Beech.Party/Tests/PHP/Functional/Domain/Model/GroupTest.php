<?php
namespace Beech\Party\Tests\Functional\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-08-12 13:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use \Beech\Party\Domain\Model\Group;

/**
 */
class GroupTest extends \TYPO3\FLOW3\Tests\FunctionalTestCase {

	/**
	* @var boolean
	*/
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Party\Domain\Repository\GroupRepository
	 */
	protected $groupRepository;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->groupRepository = $this->objectManager->get('Beech\Party\Domain\Repository\GroupRepository');
	}

	/**
	 * @test
	 */
	public function nestedGroupsCanBePersistedAndRetrievingWorksCorrectly() {
		$group1 = new Group();
		$group1->setName('Group 1');

		$group2 = new Group();
		$group2->setName('Group 2');

		$group3 = new Group();
		$group3->setName('Group 3');
		$group1->addChild($group3);

		$group4 = new Group();
		$group4->setName('Group 4');
		$group3->addChild($group4);

		$this->groupRepository->add($group1);
		$this->groupRepository->add($group2);
		$this->groupRepository->add($group3);
		$this->groupRepository->add($group4);

		$this->persistenceManager->persistAll();

		$this->assertEquals(4, $this->groupRepository->countAll());

		$this->persistenceManager->clearState();

		$this->assertEquals(1, $this->groupRepository->countByName('Group 1'));
		$this->assertEquals(1, $this->groupRepository->countByName('Group 2'));
		$this->assertEquals(1, $this->groupRepository->countByName('Group 3'));
		$this->assertEquals(1, $this->groupRepository->countByName('Group 4'));

		$group1 = $this->groupRepository->findOneByName('Group 1');
		$this->assertEquals(1, $group1->getChildren()->count());

		$group2 = $this->groupRepository->findOneByName('Group 2');
		$this->assertEquals(0, $group2->getChildren()->count());

		$group3 = $this->groupRepository->findOneByName('Group 3');
		$this->assertEquals(1, $group3->getChildren()->count());
	}

}

?>