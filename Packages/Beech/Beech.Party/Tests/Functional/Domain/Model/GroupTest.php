<?php
namespace Beech\Party\Tests\Functional\Domain\Model;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 */
class GroupTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	/**
	* @var boolean
	*/
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var boolean
	 */
	protected $testableSecurityEnabled = TRUE;


	/**
	 * @var \Beech\Party\Domain\Repository\GroupRepository
	 */
	protected $groupRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\GroupTypeRepository
	 */
	protected $typeRepository;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->groupRepository = $this->objectManager->get('Beech\Party\Domain\Repository\GroupRepository');
		$this->typeRepository = $this->objectManager->get('Beech\Party\Domain\Repository\GroupTypeRepository');

			// Create some dummy groups
		$type = new \Beech\Party\Domain\Model\GroupType();
		$type->setName('Foo');
		$this->typeRepository->add($type);

		for ($i = 1; $i <= 4; $i ++) {
			$groupVar = 'group' . $i;
			$$groupVar = new \Beech\Party\Domain\Model\Group();
			$$groupVar->setName('Group ' . $i);
			$$groupVar->setType($type);
		}

		$group1->addChild($group3);
		$group3->addChild($group4);

		$this->groupRepository->add($group1);
		$this->groupRepository->add($group2);
		$this->groupRepository->add($group3);
		$this->groupRepository->add($group4);

		$this->persistenceManager->persistAll();
		$this->persistenceManager->clearState();
	}

	/**
	 * @test
	 */
	public function groupsCanBePersisted() {
		$this->assertEquals(4, $this->groupRepository->countAll());
	}

	/**
	 * @test
	 */
	public function groupsCanBeRetriedByName() {
		$this->assertEquals(1, $this->groupRepository->countByName('Group 1'));
		$this->assertEquals(1, $this->groupRepository->countByName('Group 2'));
		$this->assertEquals(1, $this->groupRepository->countByName('Group 3'));
		$this->assertEquals(1, $this->groupRepository->countByName('Group 4'));
	}

	/**
	 * @test
	 */
	public function groupsCanHaveSubGroups() {
		$group1 = $this->groupRepository->findOneByName('Group 1');
		$this->assertEquals(1, $group1->getChildren()->count());

		$group2 = $this->groupRepository->findOneByName('Group 2');
		$this->assertEquals(0, $group2->getChildren()->count());

		$group3 = $this->groupRepository->findOneByName('Group 3');
		$this->assertEquals(1, $group3->getChildren()->count());
	}

}

?>