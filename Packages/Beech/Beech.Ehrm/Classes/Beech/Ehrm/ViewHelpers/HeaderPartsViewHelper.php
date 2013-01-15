<?php
namespace Beech\Ehrm\ViewHelpers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 29-08-12 12:19
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

class HeaderPartsViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var \Beech\Ehrm\Utility\PreferenceUtility
	 * @Flow\Inject
	 */
	protected $preferenceUtility;

	/**
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 * @Flow\Inject
	 */
	protected $authenticationManager;

	/**
	 * @var \Beech\Ehrm\Helper\ThemeHelper
	 * @Flow\Inject
	 */
	protected $themeHelper;

	/**
	 * @var array
	 */
	protected $settings = array();

	/**
	 * @var string
	 */
	protected $output = '';

	/**
	 * @var \TYPO3\Flow\Resource\Publishing\ResourcePublisher
	 * @Flow\Inject
	 */
	protected $resourcePublisher;

	/**
	 * @param array $settings
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * @var \TYPO3\Flow\Core\Bootstrap
	 * @Flow\Inject
	 */
	protected $bootstrap;

	/**
	 * @param boolean $loadJavaScript
	 * @return string
	 */
	public function render($loadJavaScript = TRUE) {
		$this->addJavaScriptConfiguration();

		if ($loadJavaScript === TRUE) {
			$this->output .= sprintf('<script src="%s"></script>', $this->resourcePublisher->getStaticResourcesWebBaseUri() . 'Packages/Beech.Ehrm/JavaScript/Init.js');

			$this->output .= chr(10) . chr(10);
			$libraries = $this->getJavascriptIncludes('libraries');
			$sources = $this->getJavaScriptIncludes('sources');

			$javaScripts = array_merge($libraries, $sources);

			foreach ($javaScripts as $javaScript) {
				if (substr($javaScript, 0, 4) === 'http') {
					$this->output .= sprintf('<script src="%s"></script>', $javaScript);
				} else {
					$this->output .= sprintf('<script src="%sPackages/%s"></script>', $this->resourcePublisher->getStaticResourcesWebBaseUri(), $javaScript);
				}

			}
		}

		$this->addStyleSheetIncludes();
		$this->addThemeStyleSheet();

		return $this->output;
	}

	/**
	 * @param string $type
	 * @return array
	 */
	protected function getJavaScriptIncludes($type) {
		$uriBuilder = new \TYPO3\Flow\Mvc\Routing\UriBuilder();
		$uriBuilder->setRequest($this->bootstrap->getActiveRequestHandler()->getHttpRequest()->createActionRequest());

		$source = \TYPO3\Flow\Utility\Arrays::getValueByPath($this->settings, array('resources', 'javaScript', $type));
		if (!is_array($source)) {
			return array();
		}

		$includeCollection = new \Beech\Ehrm\Utility\DependencyAwareCollection();
		foreach ($source as $identifier => $file) {
			if (isset($file['uri'])) {
				$source[$identifier]['path'] = $file['path'] = $uriBuilder
					->reset()
					->setFormat('js')
					->setCreateAbsoluteUri(TRUE)
					->setLinkProtectionEnabled(FALSE)
					->uriFor(
						$file['uri']['action'],
						$file['uri']['arguments'],
						$file['uri']['controller'],
						$file['uri']['package']
					);
			}

			$includeCollection->add($identifier, is_array($file) && isset($file['deps']) ? $file['deps'] : array());
		}

		$files = array();
		$sortedIncludes = $includeCollection->getItems();
		foreach ($sortedIncludes as $include) {
			$files[] = is_array($source[$include['identifier']]) ? $source[$include['identifier']]['path'] : $source[$include['identifier']];
		}
		return $files;
	}

	/**
	 * Adds the stylesheet for selected theme
	 *
	 * @return void
	 */
	protected function addThemeStyleSheet() {
		$theme = $this->themeHelper->getSelectedTheme();

			// Rare case scenario that no path is set because no stylesheet was found.
			// This should not occur but is technically possible.
		if (!empty($theme['path'])) {
			$this->output .= sprintf(
				'<link rel="stylesheet" type="text/css" href="%1$sPackages/%2$s" />',
				$this->resourcePublisher->getStaticResourcesWebBaseUri(),
				$theme['path']
			);
		}
	}

	/**
	 * Add default CSS includes
	 *
	 * @return void
	 */
	protected function addStyleSheetIncludes() {
		if (isset($this->settings['resources']['styleSheets']) && is_array($this->settings['resources']['styleSheets'])) {
			foreach ($this->settings['resources']['styleSheets'] as $styleSheet) {
				$this->output .= sprintf(
					'<link rel="stylesheet" type="text/css" href="%1$sPackages/%2$s" />',
					$this->resourcePublisher->getStaticResourcesWebBaseUri(),
					$styleSheet
				);
			}
		}
	}

	/**
	 * Add JavaScript configuration object to the document header
	 *
	 * @return void
	 */
	protected function addJavaScriptConfiguration() {
		$settings = array(
			'authenticated' => $this->authenticationManager->isAuthenticated(),
			'init' => (object)array(
				'onLoad' => array(),
				'afterInitialize' => array(),
				'preInitialize' => array()
			),
			'url' => (object)array(
				'module' => (object)array(
					'administration' => (object)array(
						'index' => $this->controllerContext->getUriBuilder()
							->reset()
							->setFormat('jsonp')
							->uriFor('list', array(), 'Person', 'Beech.Party', 'Administration'), // TODO: Make index page to show dynamic
						'menu' => $this->controllerContext->getUriBuilder()
							->reset()
							->setFormat('jsonp')
							->uriFor('menu', array('identifier' => 'administration'), 'Standard', 'Beech.Ehrm'),
					)
				)
			),
			'transitions' => (object)$this->getEmberRouterTransitionLinks(),
			'configuration' => (object)array(
				'restNotificationUri' => $this->controllerContext->getUriBuilder()
					->reset()
					->setFormat('json')
					->uriFor('list', array(), 'Rest\Notification', 'Beech.Ehrm')
			),
			'locale' => $this->preferenceUtility->getApplicationPreference('locale')
		);

		$this->output .= sprintf('<script>var MM = %s;</script>', json_encode((object)$settings));
	}

	/**
	 * @return array
	 */
	protected function getEmberRouterTransitionLinks() {
		$transitions = array();

		foreach ($this->settings['menu'] as $menuIdentifier => $menuConfiguration) {
			if (isset($menuConfiguration['menu'])) {
				foreach ($menuConfiguration['menu'] as $menuItemIdentifier => $menuItemConfiguration) {
					if (isset($menuItemConfiguration['transition'])) {

						$options = \TYPO3\Flow\Utility\Arrays::arrayMergeRecursiveOverrule(
							$this->settings['menu']['defaults'],
							$menuItemConfiguration
						);

						$transitions[$menuItemConfiguration['transition']] = array(
							'url' => $this->controllerContext->getUriBuilder()
								->reset()
								->setFormat('jsonp')
								->setCreateAbsoluteUri(TRUE)
								->uriFor(
									$options['action'],
									$options['arguments'],
									$options['controller'],
									$options['package'],
									isset($options['subpackage']) ? $options['subpackage'] : NULL
								)
						);
					}
				}
			}
		}

		return $transitions;
	}

}

?>