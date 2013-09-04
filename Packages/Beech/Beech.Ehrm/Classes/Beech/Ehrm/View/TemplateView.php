<?php
namespace Beech\Ehrm\View;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 04-12-12 13:47
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 */
class TemplateView extends \TYPO3\Fluid\View\TemplateView {

	/**
	 * Override parent getPartialPathAndFilename method to support
	 * render partials also from other packages
	 *
	 * @param string $partialName The name of the partial
	 * @return string the full path which should be used. The path definitely exists.
	 * @throws \TYPO3\Fluid\View\Exception\InvalidTemplateResourceException
	 */
	protected function getPartialPathAndFilename($partialName) {
		$paths = $this->expandGenericPathPattern($this->options['partialPathAndFilenamePattern'], TRUE, TRUE);
		$partialNameArray = explode(':', $partialName);

		if (count($partialNameArray) === 2) {
			list($packageKey, $partialName) = $partialNameArray;
			$paths[] = 'resource://' . $packageKey . '/Private/Partials/@partial.html';
		}

		foreach ($paths as &$partialPathAndFilename) {
			$partialPathAndFilename = str_replace('@partial', $partialName, $partialPathAndFilename);

			if (file_exists($partialPathAndFilename)) {
				return $partialPathAndFilename;
			}
		}

		throw new \TYPO3\Fluid\View\Exception\InvalidTemplateResourceException('The template files "' . implode('", "', $paths) . '" could not be loaded.', 1225709595);
	}
}

?>