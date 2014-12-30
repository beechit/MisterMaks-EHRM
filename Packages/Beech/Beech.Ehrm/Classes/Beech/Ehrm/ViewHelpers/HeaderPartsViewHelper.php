<?php
namespace Beech\Ehrm\ViewHelpers;

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

class HeaderPartsViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var \Beech\Ehrm\Utility\PreferencesUtility
	 * @Flow\Inject
	 */
	protected $preferencesUtility;

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
	 * @param boolean $loadJavaScriptLibraries
	 * @param boolean $loadJavaScriptSources
	 * @return string
	 */
	public function render($loadJavaScriptLibraries = TRUE, $loadJavaScriptSources = TRUE) {
		if ($loadJavaScriptLibraries === TRUE || $loadJavaScriptSources === TRUE) {
			$libraries = array();
			$sources = array();

			if ($loadJavaScriptLibraries === TRUE) {
				$libraries = $this->getJavascriptIncludes('libraries');
			}
			if ($loadJavaScriptSources === TRUE) {
				$sources = $this->getJavaScriptIncludes('sources');
			}

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

		return $this->javaScriptConfiguration() . $this->output;
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
			if (is_array($file) && isset($file['uri'])) {
				$source[$identifier]['path'] = $file['path'] = $uriBuilder
					->reset()
					->setFormat('js')
					->setCreateAbsoluteUri(TRUE)
					->uriFor(
						$file['uri']['action'],
						isset($file['uri']['arguments']) ? $file['uri']['arguments'] : array(),
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
	 * Read modules list from settings and generate array with urls mapping
	 *
	 * @return array
	 */
	protected function linkEmberRoutesToModules() {
		if (isset($this->settings['defaultActionsMapping']) && is_array($this->settings['defaultActionsMapping'])) {
			$defaultActionsMapping = $this->settings['defaultActionsMapping'];
		}
		if (isset($this->settings['moduleRoutes']) && is_array($this->settings['moduleRoutes'])) {
			$moduleRoutes = $this->settings['moduleRoutes'];
		}

		$modules = array();
		if (isset($defaultActionsMapping) && isset($moduleRoutes)) {
			foreach ($moduleRoutes as $params) {
				list($packageKey, $subpackage, $controller) = $params;
				$actionMapping = $defaultActionsMapping;
				if (isset($params[3])) {
					$actionMapping = array_merge($actionMapping, $params[3]);
				}
				$package = str_replace('.', '', $packageKey);
				foreach ($actionMapping as $action => $suffix) {
					if ($suffix === NULL) {
						continue;
					}
					$modules[$package . $subpackage . $controller . $suffix] = $this->controllerContext->getUriBuilder()
						->reset()
						->setCreateAbsoluteUri(TRUE)
						->uriFor($action, array(), $controller, $packageKey, $subpackage);
				}
			}
		}
		return $modules;
	}

	/**
	 * Add JavaScript configuration object to the document header
	 *
	 * @return string
	 */
	protected function javaScriptConfiguration() {

		$settings = array(
			'init' => (object)array(
				'onLoad' => array(),
				'afterInitialize' => array()
			),
			'locale' => $this->preferencesUtility->getApplicationPreference('locale'),
			'url' => array()
		);
		$predefinedModules = $this->linkEmberRoutesToModules();
			// TODO: those url's are just for loading modules by AJAX, should be replaced by ember-data
		$customModules = array(
			'userSettings' => $this->controllerContext->getUriBuilder()
				->reset()
				->setCreateAbsoluteUri(TRUE)
				->uriFor('index', array(), 'UserPreferences', 'Beech.Ehrm'),
			'documents' => $this->controllerContext->getUriBuilder()
				->reset()
				->setCreateAbsoluteUri(TRUE)
				->uriFor('index', array(), 'Document', 'Beech.Document'),
			'wizardManagementModule' => $this->controllerContext->getUriBuilder()
				->reset()
				->setCreateAbsoluteUri(TRUE)
				->uriFor('index', array(), 'Wizard', 'Beech.Ehrm')
		);
		$settings['url']['module'] = (object)array_merge($predefinedModules, $customModules);

		return sprintf('<script>var MM = %s;</script>', json_encode((object)$settings));
	}

}

?>