<?php
namespace Beech\Ehrm\Common\Helper;

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 */
class SettingsHelper {

	static public function convertMenuActionsToUrls(array &$settings, \TYPO3\FLOW3\Mvc\Routing\UriBuilder $uriBuilder) {
		foreach ($settings['modules'] as $moduleKey => $moduleConfig) {
			foreach ($moduleConfig['menu']['actions'] as $action => $menuAction) {
				$settings['modules'][$moduleKey]['menu']['actions'][$action]['link'] =
					$uriBuilder->reset()->uriFor(
						$menuAction['action'],
						array(),
						$menuAction['controller'],
						$menuAction['package']
					);
				unset(
					$settings['modules'][$moduleKey]['menu']['actions'][$action]['package'],
					$settings['modules'][$moduleKey]['menu']['actions'][$action]['controller'],
					$settings['modules'][$moduleKey]['menu']['actions'][$action]['action']
				);
			}
		}
	}

	static public function getMainMenuItems(array $settings) {
		$items = array();

		$menuGroups = self::sortSettingsByPriority($settings['menu']['groups']);

		foreach ($menuGroups as $groupId => $groupConfig) {
			$menuGroupItem = array(
				'label' => $groupConfig['label'],
				'items' => array()
			);

			foreach ($settings['modules'] as $moduleConfig) {
				foreach ($moduleConfig['menu']['actions'] as $menuAction) {
					if ($menuAction['menuGroup'] === $groupId) {
						$menuGroupItem['items'][] = array(
							'label' => $menuAction['label'],
							'priority' => $menuAction['priority'],
							'link' => $menuAction['link']
						);
					}
				}
			}
			if (empty($menuGroupItem['items'])) {
				unset($menuGroupItem['items']);
			} else {
				$menuGroupItem['items'] = self::sortSettingsByPriority($menuGroupItem['items']);
				$menuGroupItem['items'] = array_map(
					function($a) {
						unset($a['priority']);
						return $a;
					},
					$menuGroupItem['items']
				);
			}
			$items[] = $menuGroupItem;
		}

		return $items;
	}

	static public function sortSettingsByPriority(array $data) {
		uasort($data, function($a, $b) {
			return $a['priority'] > $b['priority'];
		});
		return $data;
	}
}
?>