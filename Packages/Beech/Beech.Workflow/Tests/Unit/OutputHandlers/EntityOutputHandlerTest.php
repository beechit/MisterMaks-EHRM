<?php
namespace Beech\Workflow\Tests\Unit;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 24-09-12 00:27
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 */
class EntityOutputHandlerTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var \Beech\Workflow\OutputHandlers\EntityOutputHandler
	 */
	protected $outputHandler;

	/**
	 */
	public function setUp() {
		$this->outputHandler = new \Beech\Workflow\OutputHandlers\EntityOutputHandler();
	}

	/**
	 * @test
	 */
	public function entityOutputHandlerCanBeCreated() {
		$this->assertInstanceOf('\Beech\Workflow\OutputHandlers\EntityOutputHandler', $this->outputHandler);
	}
}
?>