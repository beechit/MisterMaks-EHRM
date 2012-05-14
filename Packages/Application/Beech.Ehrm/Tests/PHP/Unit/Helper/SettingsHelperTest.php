<?php
namespace Beech\Ehrm\Tests\Unit\Helper;

/*                                                                        *
 * This script belongs to the FLOW3 package "Beech.Ehrm".                 *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Http\Request as HttpRequest;
use TYPO3\FLOW3\Http\Uri;

/**
 * Testcase for SettingsHelper
 */
class SettingsHelper extends \TYPO3\FLOW3\Tests\UnitTestCase {

	/**
	 * @var \TYPO3\FLOW3\Mvc\Routing\RouterInterface
	 */
	protected $mockRouter;

	/**
	 * @var \TYPO3\FLOW3\Mvc\ActionRequest
	 */
	protected $mockMainRequest;

	/**
	 * @var \TYPO3\FLOW3\Mvc\Routing\UriBuilder
	 */
	protected $uriBuilder;

	/**
	 * @var array
	 */
	protected $settings = array();

	public function setUp() {
		$this->uriBuilder = $this->getAccessibleMock('TYPO3\FLOW3\Mvc\Routing\UriBuilder');
		$this->uriBuilder->expects($this->any())->method('uriFor')->will($this->returnValue('beech.ehrm/standard/index'));
		$this->uriBuilder->expects($this->any())->method('reset')->will($this->returnValue($this->uriBuilder));

		$this->settings = array(
			'menu' => array(
				'groups' => array(
					'manage' => array(
						'priority' => 40,
						'label' => 'Beheer'
					),
					'new' => array(
						'priority' => 10,
						'label' => 'Nieuw'
					),
					'edit' => array(
						'priority' => 20,
						'label' => 'Bewerk'
					),
					'report' => array(
						'priority' => 30,
						'label' => 'Rapporteer'
					)
				)
			),
			'modules' => array(
				'admin' => array(
					'label' => 'Admin',
					'menu' => array(
						'actions' => array(
							'index' => array(
								'package' => 'Beech.Ehrm.Admin',
								'controller' => 'Standard',
								'action' => 'index',
								'priority' => 20,
								'label' => 'Admin index',
								'menuGroup' => 'manage'
							),
							'index2' => array(
								'package' => 'Beech.Ehrm.Admin',
								'controller' => 'Standard',
								'action' => 'index',
								'priority' => 10,
								'label' => 'Admin index',
								'menuGroup' => 'manage'
							),
						)
					)
				),
				'timecard' => array(
					'label' => 'Tijdregistratie',
					'menu' => array(
						'actions' => array(
							'index3' => array(
								'package' => 'Beech.Ehrm.Admin',
								'controller' => 'Standard',
								'action' => 'index',
								'priority' => 20,
								'label' => 'Admin index',
								'menuGroup' => 'new'
							),
							'index4' => array(
								'package' => 'Beech.Ehrm.Admin',
								'controller' => 'Standard',
								'action' => 'index',
								'priority' => 10,
								'label' => 'Admin index',
								'menuGroup' => 'edit'
							),
						)
					)
				),
				'absence' => array(
					'label' => 'Verlof & verzuim',
					'menu' => array(
						'actions' => array(
							'index5' => array(
								'package' => 'Beech.Ehrm.Admin',
								'controller' => 'Standard',
								'action' => 'index',
								'priority' => 20,
								'label' => 'Admin index',
								'menuGroup' => 'report'
							),
							'index6' => array(
								'package' => 'Beech.Ehrm.Admin',
								'controller' => 'Standard',
								'action' => 'index',
								'priority' => 10,
								'label' => 'Admin index',
								'menuGroup' => 'new'
							),
						)
					)
				)
			)
		);

		\Beech\Ehrm\Helper\SettingsHelper::convertMenuActionsToUrls($this->settings, $this->uriBuilder);
	}

	/**
	 * @test
	 */
	public function sortSettingsByPrioritySortsCorrectly() {
		$matchValue = array(
			'new'		=> array('priority' => 10, 'label' => 'Nieuw'),
			'edit'		=> array('priority' => 20, 'label' => 'Bewerk'),
			'report'	=> array('priority' => 30, 'label' => 'Rapporteer'),
			'manage'	=> array('priority' => 40, 'label' => 'Beheer'),
		);

		$this->assertEquals(
			$matchValue,
			\Beech\Ehrm\Helper\SettingsHelper::sortSettingsByPriority($this->settings['menu']['groups'])
		);
	}

	/**
	 * @test
	 */
	public function getMainMenuItemsReturnsValidItemsArray() {
		$matchValue = array(
			array(
				'label' => 'Nieuw',
				'items' => array(
					1 => array(
						'label' => 'Admin index',
						'link' => 'beech.ehrm/standard/index',
					),
					0 => array(
						'label' => 'Admin index',
						'link' => 'beech.ehrm/standard/index',
					)
				)
			),
			array(
				'label' => 'Bewerk',
				'items' => array(
					0 => array(
						'label' => 'Admin index',
						'link' => 'beech.ehrm/standard/index',
					)
				)
			),
			array(
				'label' => 'Rapporteer',
				'items' => array(
					0 => array(
						'label' => 'Admin index',
						'link' => 'beech.ehrm/standard/index',
					)
				)
			),
			array(
				'label' => 'Beheer',
				'items' => array(
					1 => array(
						'label' => 'Admin index',
						'link' => 'beech.ehrm/standard/index',
					),
					0 => array(
						'label' => 'Admin index',
						'link' => 'beech.ehrm/standard/index',
					)
				)
			),
		);

		$this->assertEquals(
			$matchValue,
			\Beech\Ehrm\Helper\SettingsHelper::getMainMenuItems($this->settings)
		);
	}
}
?>