<?php
namespace Beech\WorkFlow\Tests\Unit;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 02-08-12 00:27
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\WorkFlow\Domain\Model\Action as Action;

/**
 */
class ActionTest extends \TYPO3\FLOW3\Tests\UnitTestCase {

	/**
	 * @var \Beech\WorkFlow\Domain\Model\Action
	 */
	protected $action;

	public function setUp() {
		$this->action = new Action();
	}

	/**
	 * @test
	 */
	public function actionCanBeCreated() {
		$this->assertInstanceOf('\Beech\WorkFlow\Domain\Model\Action', $this->action);
	}

	/**
	 * @return array
	 */
	public function invalidTargetArguments() {
		return array(
			array(''),
			array(intval(9)),
			array('SomeFoolishWrongNameWhichIsNoClassWithPrependingSlash'),
			array(array()),
			array(TRUE),
				// Contains no entity identifier
			array('\TYPO3\Party\Domain\Model\Person'),
				// Contains no leading backslash
			array('TYPO3\Party\Domain\Model\Person:gf82537a-e32c-405c-a7b3-fb8095ae2a03'),
				// Contains a non-hexadecimal character (the q after Person:)
			array('\TYPO3\Party\Domain\Model\Person:qf82537a-e32c-405c-a7b3-fb8095ae2a03'),
		);
	}

	/**
	 * @return array
	 */
	public function validTargetArguments() {
		return array(
			array('\Beech\Ehrm\Foo:7a1f60ac-e32c-405c-a7b3-fb8095ae2a03'),
			array('\TYPO3\Party\Domain\Model\Person:gf82537a-e32c-405c-a7b3-fb8095ae2a03'),
			array('\\TYPO3\Party\Domain\Model\Person:gf82537a-e32c-405c-a7b3-fb8095ae2a03'),
		);
	}

	/**
	 * @test
	 * @dataProvider invalidTargetArguments
	 * @expectedException \Beech\WorkFlow\Exception\InvalidArgumentException
	 */
	public function actionModelThrowsAnExceptionOnInvalidTargetNames($targetName) {
		$this->action->setTarget($targetName);
	}

	/**
	 * @test
	 * @dataProvider validTargetArguments
	 */
	public function actionModelAcceptsValidTargetNames($targetName) {
		$this->action->setTarget($targetName);
		$this->assertEquals($targetName, $this->action->getTargetClassName());
	}
}

?>