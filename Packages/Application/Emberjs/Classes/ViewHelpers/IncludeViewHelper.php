<?php
namespace Emberjs\ViewHelpers;

/*                                                                        *
 * This script belongs to the FLOW3 package "Emberjs".                    *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 *
 * @FLOW3\Scope("singleton")
 */
class IncludeViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @FLOW3\Inject
	 * @var TYPO3\FLOW3\Resource\Publishing\ResourcePublisher
	 */
	protected $resourcePublisher;

	/**
	 * @param string $version
	 * @param boolean $loadJQuery
	 * @param boolean $loadEmberData
	 * @return void
	 * @throws TYPO3\FLOW3\Exception
	 */
	public function render($version = NULL, $loadJQuery = FALSE, $loadEmberData = FALSE) {
		$includes = '';
		if ($loadJQuery) {
			$includes .= '<script type="text/javascript" src="' . $this->getJqueryFilePath() . '"></script>';
		}

		$includes .= '<script type="text/javascript" src="' . $this->getEmberjsFilePath($version) . '"></script>';

		if ($loadEmberData) {
			$includes .= '<script type="text/javascript" src="' . $this->getEmberDataFilePath() . '"></script>';
		}
		return $includes;
	}

	/**
	 * Get path to Emberjs library
	 *
	 * @param string $version
	 * @return string
	 * @throws \TYPO3\FLOW3\Exception
	 */
	protected function getEmberjsFilePath($version = NULL) {
		if ($version === NULL) {
			$files = \TYPO3\FLOW3\Utility\Files::readDirectoryRecursively(
				'resource://Emberjs/Public/Core/',
				'js'
			);
			natsort($files);
			$file = array_pop($files);
		} else {
			$file = \TYPO3\FLOW3\Utility\Files::getUnixStylePath(
				'resource://Emberjs/Public/Core/ember-' . $version . '.min.js'
			);
			if (!is_file($file)) {
				throw new \TYPO3\FLOW3\Exception('Version ' . $version . ' of Emberjs not found');
			}
		}

		return $this->resourcePublisher->getStaticResourcesWebBaseUri() . 'Packages/Emberjs/Core/' . basename($file);
	}

	/**
	 * Get path to EmberData package
	 *
	 * @return string
	 */
	protected function getEmberDataFilePath() {
		return $this->resourcePublisher->getStaticResourcesWebBaseUri() . 'Packages/Emberjs/EmberData/ember-data.min.js';
	}

	/**
	 * Get path to jQuery library
	 *
	 * @return string
	 */
	protected function getJqueryFilePath() {
		return $this->resourcePublisher->getStaticResourcesWebBaseUri() . 'Packages/Emberjs/jquery-1.7.2.min.js';
	}
}
?>