<?php
namespace Beech\Party\Tests\Unit\Domain\Model\Group;

use \Beech\Party\Domain\Model\GroupType;

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

class GroupTypeTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var \Beech\Party\Domain\Model\GroupType
	 */
	protected $groupType;

	/**
	 * Set up for tests
	 */
	public function setUp() {
		parent::setUp();
		$this->groupType = new GroupType();
	}

	/**
	 * @dataProvider groupTypeDataProvider
	 * @test
	 */
	public function settingGroupName($name) {
		$this->groupType->setName($name);
		$this->assertSame($this->groupType->getName(), $name);
	}

	/**
	 * @return array Signature: name
	 */
	public function groupTypeDataProvider() {
		return array(
			array('System users'),
			array('Guests'),
			array('Other Type 123')
		);
	}
}
?>