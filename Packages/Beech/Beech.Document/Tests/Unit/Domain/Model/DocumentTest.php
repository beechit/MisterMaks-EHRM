<?php
namespace Beech\Document\Tests\Unit\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-08-12 21:05
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Document\Controller\Management\DocumentController;

/**
 * Unit test for the Document model
 */
class DocumentTest extends \TYPO3\Flow\Tests\UnitTestCase {

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
	public function nameGetterAndSetterReturnTheSame() {
		$document = new \Beech\Document\Domain\Model\Document;
		$this->inject($document, 'configurationManager', $this->configurationManager);
		$document->setName('Polski');
		$this->assertSame($document->getName(), 'Polski');
	}
}
?>