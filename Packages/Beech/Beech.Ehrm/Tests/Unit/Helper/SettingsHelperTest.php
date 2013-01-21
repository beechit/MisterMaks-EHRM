<?php
namespace Beech\Ehrm\Tests\Unit\Helper;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 09-10-12 09:56
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Http\Request as HttpRequest;
use TYPO3\Flow\Http\Uri;

/**
 * Testcase for SettingsHelper
 */
class SettingsHelperTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var \TYPO3\Flow\Mvc\Routing\RouterInterface
	 */
	protected $mockRouter;

	/**
	 * @var \TYPO3\Flow\Mvc\ActionRequest
	 */
	protected $mockMainRequest;

	/**
	 * @var \TYPO3\Flow\Mvc\Routing\UriBuilder
	 */
	protected $uriBuilder;

	/**
	 * @var \Beech\Ehrm\Helper\SettingsHelper
	 */
	protected $settingsHelper;

	/**
	 * Setup test
	 */
	public function setUp() {
		$this->settingsHelper = new \Beech\Ehrm\Helper\SettingsHelper();

		$settingsYaml = file_get_contents(__DIR__ . '/Fixtures/MenuSettings.yaml');
		$settings = \Symfony\Component\Yaml\Yaml::parse($settingsYaml);
		$this->settingsHelper->injectSettings($settings['Beech']['Ehrm']);

		$this->uriBuilder = $this->getAccessibleMock('TYPO3\Flow\Mvc\Routing\UriBuilder');
		$this->uriBuilder->expects($this->any())->method('uriFor')->will($this->returnValue('beech.ehrm/standard/index'));
		$this->uriBuilder->expects($this->any())->method('reset')->will($this->returnValue($this->uriBuilder));
		$this->uriBuilder->expects($this->any())->method('setFormat')->will($this->returnValue($this->uriBuilder));
		$this->uriBuilder->expects($this->any())->method('setCreateAbsoluteUri')->will($this->returnValue($this->uriBuilder));

		$this->settingsHelper->setUriBuilder($this->uriBuilder);
		$this->settingsHelper->convertMenuActionsToUrls();
	}

	/**
	 * @test
	 */
	public function sortSettingsByPrioritySortsCorrectly() {
		$data = array(
			array('priority' => 450, 'label' => 450),
			array('priority' => 10, 'label' => 10),
			array('priority' => 99, 'label' => 99),
			array('priority' => 40, 'label' => 40)
		);

		$this->assertEquals(
			array(
				1 => array('label' => 10),
				3 => array('label' => 40),
				2 => array('label' => 99),
				0 => array('label' => 450)
			),
			\Beech\Ehrm\Helper\SettingsHelper::sortSettingsByPriority($data)
		);
	}

	/**
	 * @test
	 */
	public function getMainMenuItemsReturnsValidItemsArray() {
		$matchValue = include(__DIR__ . '/Fixtures/FullMenuArray.php');

		$this->assertEquals(
			$matchValue,
			$this->settingsHelper->getMenuItems('main')
		);
	}
}
?>