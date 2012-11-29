<?php
namespace Beech\Ehrm\Tests\Unit\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 31-10-12 16:20
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Ehrm\Domain\Model\Link as Link;

/**
 * Testcase for Link
 */
class LinkTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function testInstance() {
		$link = new Link();
		$this->assertInstanceOf('Beech\Ehrm\Domain\Model\Link', $link);
	}

	/**
	 * @test
	 */
	public function getterSetterTest() {
		$link = new Link();
		$link->setPackageKey('package');
		$link->setControllerName('controller');
		$link->setActionName('action');
		$link->setArguments(array('foo'));

		$this->assertEquals('package', $link->getPackageKey());
		$this->assertEquals('controller', $link->getControllerName());
		$this->assertEquals('action', $link->getActionName());
		$this->assertEquals(array('foo'), $link->getArguments());
	}

}
?>