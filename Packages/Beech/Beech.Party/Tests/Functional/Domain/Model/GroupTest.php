<?php
namespace Beech\Party\Tests\Functional\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-08-12 13:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 */
class GroupTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	/**
	* @var boolean
	*/
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Party\Domain\Repository\GroupRepository
	 */
	protected $groupRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\Group\TypeRepository
	 */
	protected $typeRepository;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->groupRepository = $this->objectManager->get('Beech\Party\Domain\Repository\GroupRepository');
		$this->typeRepository = $this->objectManager->get('Beech\Party\Domain\Repository\Group\TypeRepository');
	}

	/**
	 * @test
	 */
	public function nestedGroupsCanBePersistedAndRetrievingWorksCorrectly() {

		$type = new \Beech\Party\Domain\Model\GroupType();
		$type->setName('Foo');
		$this->typeRepository->add($type);

		$group1 = new \Beech\Party\Domain\Model\Group();
		$group1->setName('Group 1');
		$group1->setType($type);

		$group2 = new \Beech\Party\Domain\Model\Group();
		$group2->setName('Group 2');
		$group2->setType($type);

		$group3 = new \Beech\Party\Domain\Model\Group();
		$group3->setName('Group 3');
		$group3->setType($type);

		$group1->addChild($group3);

		$group4 = new \Beech\Party\Domain\Model\Group();
		$group4->setName('Group 4');
		$group4->setType($type);

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