<?php
namespace Beech\Ehrm\Helper;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Rens Admiraal <rens@beech.it>
 * Date: 03-05-12 22:50
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * @FLOW3\Scope("prototype")
 */
class SettingsHelper {

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @var \TYPO3\FLOW3\Security\Authorization\AccessDecisionManagerInterface
	 * @FLOW3\Inject
	 */
	protected $accessDecisionManager;

	/**
	 * @var \TYPO3\FLOW3\Mvc\Routing\UriBuilder
	 */
	protected $uriBuilder;

	/**
	 * @var \TYPO3\FLOW3\Core\Bootstrap
	 * @FLOW3\Inject
	 */
	protected $bootstrap;

	/**
	 * @param array $settings
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * Initalize the object
	 *
	 * @return void
	 */
	public function initializeObject() {
		$this->uriBuilder = new \TYPO3\FLOW3\Mvc\Routing\UriBuilder();
		$this->uriBuilder->setRequest($this->bootstrap->getActiveRequestHandler()->getHttpRequest()->createActionRequest());
		$this->convertMenuActionsToUrls();
	}

	/**
	 * @param \TYPO3\FLOW3\Mvc\Routing\UriBuilder $uriBuilder
	 * @return void
	 */
	public function setUriBuilder(\TYPO3\FLOW3\Mvc\Routing\UriBuilder $uriBuilder) {
		$this->uriBuilder = $uriBuilder;
	}

	/**
	 * @return void
	 */
	public function convertMenuActionsToUrls() {
		$defaults = \TYPO3\FLOW3\Utility\Arrays::arrayMergeRecursiveOverrule(
			array('package' => NULL, 'controller' => 'Standard', 'action' => 'index'),
			$this->settings['menu']['defaults']
		);

		$keysToConvert = array('menu', 'modules');

		foreach ($keysToConvert as $identifier) {
			foreach ($this->settings[$identifier] as $subIdentifier => $configuration) {
				if (isset($configuration['menu'])) {
					$this->convertMenuConfigurationPartToUrls($identifier, $subIdentifier, $defaults);
				}
			}
		}
	}

	/**
	 * @param string $identifier
	 * @param string $subIdentifier
	 * @param array $defaults
	 */
	protected function convertMenuConfigurationPartToUrls($identifier, $subIdentifier, array $defaults) {
		if (isset($this->settings[$identifier][$subIdentifier]['menu']) && is_array($this->settings[$identifier][$subIdentifier]['menu'])) {
			foreach ($this->settings[$identifier][$subIdentifier]['menu'] as $index => $menuItem) {
				$this->settings[$identifier][$subIdentifier]['menu'][$index]['href'] = $this->uriBuilder
					->reset()
					->setCreateAbsoluteUri(TRUE)
					->uriFor(
						isset($menuItem['action']) ? $menuItem['action'] : $defaults['action'],
						array(),
						isset($menuItem['controller']) ? $menuItem['controller'] : $defaults['controller'],
						isset($menuItem['package']) ? $menuItem['package'] : $defaults['package']
				);
			}
		}
	}

	/**
	 * Get all available links for a menu identifier
	 *
	 * TODO: optimize
	 * @param string $identifier
	 * @return array
	 */
	protected function getMenuLinkCollection($identifier) {
		$linkCollection = array();
		if (isset($this->settings['modules']) && is_array($this->settings['modules'])) {
			foreach ($this->settings['modules'] as $moduleConfiguration) {
				if (isset($moduleConfiguration['menu']) && is_array($moduleConfiguration['menu'])) {
					foreach ($moduleConfiguration['menu'] as $linkItem) {
						if (isset($linkItem['resource']) && !$this->hasAccessToResource($linkItem['resource'])) {
							continue;
						}
						if (!isset($linkItem['menuGroup']) || substr($linkItem['menuGroup'], 0, strlen($identifier) + 1) === $identifier . ':') {
							$linkCollection[] = $linkItem;
						}
					}
				}
			}
		}

		if (isset($this->settings['menu'][$identifier]['menu']) && is_array($this->settings['menu'][$identifier]['menu'])) {
			foreach ($this->settings['menu'][$identifier]['menu'] as $linkItem) {
				if (isset($linkItem['resource']) && !$this->hasAccessToResource($linkItem['resource'])) {
					continue;
				}
				if (!isset($linkItem['menuGroup']) || substr($linkItem['menuGroup'], 0, strlen($identifier) + 1) === $identifier . ':') {
					$linkCollection[] = $linkItem;
				}
			}
		}

		return $linkCollection;
	}

	/**
	 * @param string $identifier
	 * @return array
	 */
	public function getMenuItems($identifier) {
		$items = $menuGroups = array();

			// Get group configuration
		if (isset($this->settings['menu'][$identifier]['groups'])) {
			$menuGroups = self::sortSettingsByPriority($this->settings['menu'][$identifier]['groups']);
			foreach ($menuGroups as $groupIdentifier => $groupConfig) {
				$items['group:' . $groupIdentifier] = array(
					'label' => $groupConfig['label'],
					'items' => array()
				);
			}
		}

			// Get collection of all available links
		$linkCollection = $this->getMenuLinkCollection($identifier);

		foreach ($linkCollection as $linkItem) {
			$group = isset($linkItem['menuGroup']) ? str_replace($identifier . ':', '', $linkItem['menuGroup']) : NULL;

			if ($group !== NULL) {
				unset($linkItem['menuGroup']);
				$items['group:' . $group]['items'][] = $linkItem;
			} else {
				if (!isset($items['items'])) {
					$items['items'] = array();
				}
				$items['items'][] = $linkItem;
			}
		}

			// Cleanup empty groups
		foreach ($items as $key => $item) {
			if (isset($item['items'])) {
				if ($item['items'] === array()) {
					unset($items[$key]);
				} else {
					$items[$key]['items'] = self::sortSettingsByPriority($item['items']);
				}
			}
		}

		if (isset($items['items'])) {
			$items['items'] = self::sortSettingsByPriority($items['items']);
			$items += $items['items'];
			unset($items['items']);
		}

		return $items;
	}

	/**
	 * @static
	 * @param array $data
	 * @return array
	 */
	static public function sortSettingsByPriority(array $data) {
		uasort($data, function($a, $b) {
			if (isset($a['priority']) && isset($b['priority'])) {
				return $a['priority'] > $b['priority'];
			}
			if (isset($a['priority'])) {
				return TRUE;
			}
			if (isset($b['priority'])) {
				return FALSE;
			}
			return TRUE;
		});
		return array_map(
			function($a) {
				unset($a['priority']);
				return $a;
			},
			$data
		);
	}

	/**
	 * Check if we currently have access to the given resource
	 *
	 * @param string $resource The resource to check
	 * @return boolean TRUE if we currently have access to the given resource
	 */
	protected function hasAccessToResource($resource) {
		try {
			$this->accessDecisionManager->decideOnResource($resource);
		} catch (\TYPO3\FLOW3\Security\Exception\AccessDeniedException $e) {
			return FALSE;
		}

		return TRUE;
	}
}
?>