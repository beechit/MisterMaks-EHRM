<?php
namespace Beech\Workflow\Tests\Unit;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 02-08-12 00:27
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Workflow\Domain\Model\Action as Action;

/**
 */
class ActionTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var \Beech\Workflow\Domain\Model\Action
	 */
	protected $action;

	/**
	 */
	public function setUp() {
		$this->action = new Action();
	}

	/**
	 * @test
	 */
	public function actionCanBeCreated() {
		$this->assertInstanceOf('\Beech\Workflow\Domain\Model\Action', $this->action);
	}

	/**
	 * @return array
	 */
	public function invalidArguments() {
		return array(
				// Argument is invalid, not a class instance
			array(array()),
				// Argument is invalid, not a class instance
			array('\Beech\Party\Domain\Model\Company'),
		);
	}

	/**
	 * @test
	 * @dataProvider invalidArguments
	 * @expectedException \Beech\Workflow\Exception\InvalidArgumentException
	 */
	public function actionModelThrowsAnExceptionOnInvalidArgument($targetEntity) {
		$this->action->setTarget($targetEntity);
	}
}
?>