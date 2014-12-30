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
class ThemeHelper {

	/**
	 * @var \Beech\Ehrm\Utility\PreferencesUtility
	 * @Flow\Inject
	 */
	protected $preferencesUtility;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @var string
	 */
	protected $themePath = 'resource://Beech.Ehrm/Public/StyleSheets/Themes/';

	/**
	 * @param array $settings
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * Get available themes
	 *
	 * @return array
	 */
	public function getAvailableThemes() {
		$themes = array();

			// Read themes from disk
		$iterator = new \DirectoryIterator($this->themePath);
		foreach ($iterator as $file) {
			$styleSheet = \TYPO3\Flow\Utility\Files::concatenatePaths(array($file->getPathname(), 'style.css'));
			if (!$file->isDot() && $file->isDir() && is_file($styleSheet)) {
				$themes[$file->getFileName()] = array(
					'name' => $file->getFileName(),
					'package' => 'Beech.Ehrm',
					'path' => 'Beech.Ehrm/StyleSheets/Themes/' . $file->getFileName() . '/style.css'
				);
			}
		}

			// Read themes from settings
		if (isset($this->settings['resources']['themes']) && is_array($this->settings['resources']['themes'])) {
			foreach ($this->settings['resources']['themes'] as $themeName => $themeConfiguration) {
					// TODO: Check for actual existence of the style file
				if (!isset($themes[$themeName]) && isset($themeConfiguration['package'])) {
					$themes[$themeName] = array(
						'path' => $themeConfiguration['package'] . 'StyleSheets/Themes/' . $themeName . '/css/style.min.css',
						'package' => $themeConfiguration['package']
					);
				}
				if (isset($themeConfiguration['name'])) {
					$themes[$themeName]['name'] = $themeConfiguration['name'];
				}
			}
		}

		return $themes;
	}

	/**
	 * Get selected theme for application
	 *
	 * @return array
	 */
	public function getSelectedTheme() {
		$availableThemes = $this->getAvailableThemes();

		$defaultTheme = $this->preferencesUtility->getApplicationPreference('theme', FALSE);
		if ($defaultTheme !== NULL && isset($availableThemes[$defaultTheme])) {
			return $availableThemes[$defaultTheme];
		}

		return $availableThemes['Default'];
	}

}

?>