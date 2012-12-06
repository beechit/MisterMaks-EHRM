<?php
namespace Beech\Party\Tests\Unit\Domain\Model\Group;

use \Beech\Party\Domain\Model\GroupType;

/**
 * Testcase for Group type
 */
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