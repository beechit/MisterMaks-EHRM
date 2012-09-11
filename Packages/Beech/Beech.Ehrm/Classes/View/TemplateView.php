<?php
namespace Beech\Ehrm\View;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-07-12 23:55
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * A custom TemplateView with dynamic path resolving
 */
class TemplateView extends \TYPO3\Fluid\View\TemplateView {

	/**
	 * Directory pattern for global partials.
	 *
	 * @var string
	 */
	private $partialPathAndFilenamePattern = '@partialRoot/@subpackage/@partial.@format';

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

	/**
	 * Resolve the partial path and filename based on $this->partialPathAndFilenamePattern.
	 * Overrides a parent method to support loading partial templates from the Beech.Ehrm package.
	 *
	 * Usage:
	 * 1) <f:render partial="PartialTemplateName"/>
	 * a) Is looking for template <PackageName>/Resources/Private/Partials/PartialTemplateName(.html)
	 * b) If template is not found it starts to look inside default package
	 * Beech.Ehrm/Resources/Private/Partials/PartialTemplateName(.html)
	 *
	 * 2) Now it supports also absolute, resource paths, ex:
	 * <f:render partial="resource://Beech.Ehrm/Private/Partials/PartialTemplateName.html"/>
	 *
	 * @param string $partialName The name of the partial
	 * @return string the full path which should be used. The path definitely exists.
	 * @throws \TYPO3\Fluid\View\Exception\InvalidTemplateResourceException
	 */
	protected function getPartialPathAndFilename($partialName) {
		try {
			return parent::getPartialPathAndFilename($partialName);
		} catch (\TYPO3\Fluid\View\Exception\InvalidTemplateResourceException $exception) {
				// Set path to default package
			$this->setPartialRootPath('resource://Beech.Ehrm/Private/Partials');
			$defaultPaths = $this->expandGenericPathPattern($this->partialPathAndFilenamePattern, TRUE, TRUE);
				// Additionally, extra check
			$extraPath = array($partialName);
			$paths = array_merge($defaultPaths, $extraPath);
			foreach ($paths as &$partialPathAndFilename) {
				$partialPathAndFilename = str_replace('@partial', $partialName, $partialPathAndFilename);
				if (file_exists($partialPathAndFilename)) {
					return $partialPathAndFilename;
				}
			}
		}
		throw new \TYPO3\Fluid\View\Exception\InvalidTemplateResourceException('The template files for partial "' . $partialName . '" could not be loaded.', 1225709595);
	}
}

?>