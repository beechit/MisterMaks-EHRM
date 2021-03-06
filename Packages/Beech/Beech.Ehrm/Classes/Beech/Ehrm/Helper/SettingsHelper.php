<?php
namespace Beech\Ehrm\Helper;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class SettingsHelper {

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @var \TYPO3\Flow\Security\Authorization\AccessDecisionManagerInterface
	 * @Flow\Inject
	 */
	protected $accessDecisionManager;

	/**
	 * @var \TYPO3\Flow\Mvc\Routing\UriBuilder
	 */
	protected $uriBuilder;

	/**
	 * @var \TYPO3\Flow\Core\Bootstrap
	 * @Flow\Inject
	 */
	protected $bootstrap;

	/**
	 * @var \Beech\Task\Domain\Repository\TaskRepository
	 * @Flow\Inject
	 */
	protected $taskRepository;

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @var \Beech\Ehrm\Log\ApplicationLoggerInterface
	 * @Flow\Inject
	 */
	protected $applicationLogger;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

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
		$this->uriBuilder = new \TYPO3\Flow\Mvc\Routing\UriBuilder();
		$this->uriBuilder->setRequest($this->bootstrap->getActiveRequestHandler()->getHttpRequest()->createActionRequest());
		$this->convertMenuActionsToUrls();
		$this->appendNumberOfOpenToDos();
	}

	/**
	 * @param \TYPO3\Flow\Mvc\Routing\UriBuilder $uriBuilder
	 * @return void
	 */
	public function setUriBuilder(\TYPO3\Flow\Mvc\Routing\UriBuilder $uriBuilder) {
		$this->uriBuilder = $uriBuilder;
	}

	/**
	 * @return void
	 */
	public function convertMenuActionsToUrls() {
		$defaults = \TYPO3\Flow\Utility\Arrays::arrayMergeRecursiveOverrule(
			array('package' => NULL, 'controller' => 'Standard', 'action' => 'index', 'format' => 'html', 'arguments' => array(), 'subpackage' => NULL),
			isset($this->settings['menu']['defaults']) ? $this->settings['menu']['defaults'] : array()
		);

		$keysToConvert = array('menu', 'modules');
		foreach ($keysToConvert as $identifier) {
			if (isset($this->settings[$identifier])) {
				foreach ($this->settings[$identifier] as $subIdentifier => $configuration) {
					if (isset($configuration['menu'])) {
						$this->convertMenuConfigurationPartToUrls($identifier, $subIdentifier, $defaults);
					}
				}
			}
		}
	}

	/**
	 * Appends the outstanding to-do's to the label
	 * @return void
	 */
	protected function appendNumberOfOpenToDos() {
		if (!$this->securityContext->isInitialized()
				|| !$this->securityContext->getAccount() instanceof \TYPO3\Flow\Security\Account) {
			return;
		}

			// TODO: optimize (would be cool if it was some kind of setting in Settings.yaml, like 'append: ...'
		foreach ($this->settings['menu'] as $subIdentifier => $configuration) {
			if (isset($configuration['menu'])) {
				foreach ($this->settings['menu'][$subIdentifier]['menu'] as $key => $item) {
					if ($key === 'task') {
						$this->settings['menu'][$subIdentifier]['menu']['task']['label']
							.= sprintf(' <span class="badge badge-info">%s</span>',
								$this->taskRepository->countOpenTasksByPerson(
									$this->securityContext->getAccount()->getParty()
								)
							);
					}
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
				$options = \TYPO3\Flow\Utility\Arrays::arrayMergeRecursiveOverrule($defaults, $menuItem);

				if (isset($options['action'])) {
					$this->settings[$identifier][$subIdentifier]['menu'][$index]['href'] = $this->uriBuilder
						->reset()
						->setCreateAbsoluteUri(TRUE)
						->setFormat($options['format'])
						->uriFor($options['action'], $options['arguments'], $options['controller'], $options['package'], $options['subpackage']);
				}
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
							$label = ($this->translator instanceof \TYPO3\Flow\I18n\Translator) ? $this->translator->translateByOriginalLabel($linkItem['label'], array(), NULL, NULL, 'Main', 'Beech.Ehrm') : $linkItem['label'];
							$linkItem['label'] = $label;
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
					$label = ($this->translator instanceof \TYPO3\Flow\I18n\Translator) ? $this->translator->translateByOriginalLabel($linkItem['label'], array(), NULL, NULL, 'Main', 'Beech.Ehrm') : $linkItem['label'];
					$linkItem['label'] = $label;
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
				$label = ($this->translator instanceof \TYPO3\Flow\I18n\Translator) ? $this->translator->translateByOriginalLabel($groupConfig['label'], array(), NULL, NULL, 'Main', 'Beech.Ehrm') :$groupConfig['label'];
				$items['group:' . $groupIdentifier] = array(
					'label' => $label,
					'items' => array()
				);
			}
		}

			// Get collection of all available links
		$linkCollection = $this->getMenuLinkCollection($identifier);

		foreach ($linkCollection as $linkItem) {
			$group = isset($linkItem['menuGroup']) ? str_replace($identifier . ':', '', $linkItem['menuGroup']) : NULL;
			if ($group !== NULL) {
				if (isset($menuGroups[$group])) {
					$items['group:' . $group]['items'][] = $linkItem;
				} else {
					$this->applicationLogger->log(sprintf('Menu group "%s" is used, but not available', $group), LOG_INFO);
				}
				unset($linkItem['menuGroup']);
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
		} catch (\TYPO3\Flow\Security\Exception\AccessDeniedException $e) {
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Get available languages
	 *
	 * @return array
	 */
	public function getAvailableLanguages() {
		if (isset($this->settings['languages'])) {
			$languageSetting = array();
			foreach ($this->settings['languages'] as $locale) {
				$languageSetting[$locale] = $locale;
			}
			return $languageSetting;
		} else {
			return array();
		}
	}

	public function getFlashMessagesTemplate() {
		if (isset($this->settings['viewHelpers']['templates']['Beech\Ehrm\ViewHelpers\FlashMessagesViewHelper'])) {
			$template = $this->settings['viewHelpers']['templates']['Beech\Ehrm\ViewHelpers\FlashMessagesViewHelper'];
			if (is_file($template)) {
				return $template;
			}
		}
		return '';
	}

	public function getFlashMessagesPartialRootPath() {
		if (isset($this->settings['viewHelpers']['partialRootPath'])) {
			return $this->settings['viewHelpers']['partialRootPath'];
		}
		return '';
	}
}

?>