<?php
namespace Beech\Ehrm\View;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Rens Admiraal <rens@beech.it>
 * Date: 23-07-12 23:55
 * All code (c) Beech Applications B.V. all rights reserved
 */

/**
 */
class TemplateView extends \TYPO3\Fluid\View\TemplateView {

	/**
	 * Resolve the path and file name of the layout file, based on
	 * $this->layoutPathAndFilename and $this->layoutPathAndFilenamePattern.
	 *
	 * Overrides the parent method to add support for loading the layout from
	 * the Beech.Ehrm common package if it's not available in current package.
	 *
	 * This enables using the same layout over multiple packages.
	 *
	 * @param string $layoutName Name of the layout to use. If none given, use "Default"
	 * @return string Path and filename of layout files
	 * @throws \TYPO3\Fluid\View\Exception\InvalidTemplateResourceException
	 */
	protected function getLayoutPathAndFilename($layoutName = 'Default') {
		try {
			$layoutPathAndFilename = parent::getLayoutPathAndFilename($layoutName);
		} catch (\TYPO3\Fluid\View\Exception\InvalidTemplateResourceException $exception) {
			$this->layoutRootPathPattern = str_replace('@packageResourcesPath', 'resource://Beech.Ehrm', $this->layoutRootPathPattern);
			$layoutPathAndFilename = parent::getLayoutPathAndFilename($layoutName);
		}

		return $layoutPathAndFilename;
	}
}

?>