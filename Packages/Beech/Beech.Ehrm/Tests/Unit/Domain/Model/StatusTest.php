<?php
namespace Beech\Ehrm\Tests\Unit\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 15-04-13 16:20
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Ehrm\Domain\Model\Status;

/**
 * Testcase for Status
 */
class StatusTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 */
	protected $configurationManager;

	/**
	 * setup
	 */
	public function setUp() {
		parent::setUp();
		$this->configurationManager = new \TYPO3\Flow\Configuration\ConfigurationManager(new \TYPO3\Flow\Core\ApplicationContext('Testing'));
		$this->inject($this->configurationManager, 'configurationSource', new \TYPO3\Flow\Configuration\Source\YamlSource());
		$this->configurationManager->registerConfigurationType('Models', \TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_PROCESSING_TYPE_DEFAULT, TRUE);
	}

	/**
	 * @test
	 */
	public function testInstance() {
		$status = new Status();
		$this->inject($status, 'configurationManager', $this->configurationManager);
		$this->assertInstanceOf('Beech\Ehrm\Domain\Model\Status', $status);
	}

	/**
	 * @test
	 */
	public function getterSetterTest() {
		$status = new Status();
		$this->inject($status, 'configurationManager', $this->configurationManager);
		$status->setStatusName(Status::STATUS_CANCELED);
		$this->assertEquals(Status::STATUS_CANCELED, $status->getStatusName());
	}

}

?>