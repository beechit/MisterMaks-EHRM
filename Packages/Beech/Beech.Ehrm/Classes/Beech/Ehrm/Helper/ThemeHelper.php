<?php
namespace Beech\Ehrm\Helper;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 26-10-12 23:08
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

class ThemeHelper {

	/**
	 * @var \Beech\Ehrm\Domain\Repository\ApplicationRepository
	 * @Flow\Inject
	 */
	protected $applicationRepository;

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

		$application = $this->applicationRepository->findApplication();
		if ($application !== NULL) {
			$defaultTheme = $application->getPreferences()->get('theme');

			if ($defaultTheme !== NULL && isset($availableThemes[$defaultTheme])) {
				return $availableThemes[$defaultTheme];
			}
		}

		return $availableThemes['Default'];
	}

}

?>