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
	 * Directory pattern for global partials. Not part of the public API, should not be changed for now.
	 * @var string
	 */
	private $partialPathAndFilenamePattern = '@partialRoot/@subpackage/@partial.@format';

	/**
	 * @param string $actionName
	 * @return string
	 */
	public function render($actionName = NULL) {
		if ($this->controllerContext->getRequest()->getFormat() !== 'jsonp') {
			return parent::render($actionName);
		}

		try {
			$result = parent::render($actionName);
		} catch (\Exception $exception) {
			$result = sprintf('<div class="alert alert-error">%s</div>', $exception->getMessage());
		}

		$result = preg_replace('/(\t|\r|\n)/', '', $result);

		return sprintf(
			'%s(%s)',
			$this->controllerContext->getRequest()->getArgument('callback'),
			json_encode((object)array(
				'html' => $result
			))
		);
	}

	/**
	 * Resolve the path and file name of the layout file, based on
	 * $this->layoutPathAndFilename and $this->layoutPathAndFilenamePattern.
	 *
	 * In case a layout has already been set with setLayoutPathAndFilename(),
	 * this method returns that path, otherwise a path and filename will be
	 * resolved using the layoutPathAndFilenamePattern.
	 *
	 * @param string $layoutName Name of the layout to use. If none given, use "Default"
	 * @return string Path and filename of layout files
	 * @throws \TYPO3\Fluid\View\Exception\InvalidTemplateResourceException
	 */
	protected function getLayoutPathAndFilename($layoutName = 'Default') {
		if ($this->layoutPathAndFilename !== NULL) {
			return $this->layoutPathAndFilename;
		}
		$paths = $this->expandGenericPathPattern($this->layoutPathAndFilenamePattern, TRUE, TRUE);
		$layoutName = ucfirst($layoutName);
		foreach ($paths as &$layoutPathAndFilename) {
			$layoutPathAndFilename = str_replace('@layout', $layoutName, $layoutPathAndFilename);
			if (file_exists($layoutPathAndFilename)) {
				return $layoutPathAndFilename;
			} else {
				$layoutPathAndFilename = str_replace('.' . $this->controllerContext->getRequest()->getFormat(), '.html', $layoutPathAndFilename);
				if (file_exists($layoutPathAndFilename)) {
					return $layoutPathAndFilename;
				}
			}
		}
		throw new \TYPO3\Fluid\View\Exception\InvalidTemplateResourceException('The template files "' . implode('", "', $paths) . '" could not be loaded.', 1225709595);
	}

	/**
	 * Resolve the template path and filename for the given action. If $actionName
	 * is NULL, looks into the current request.
	 *
	 * @param string $actionName Name of the action. If NULL, will be taken from request.
	 * @return string Full path to template
	 * @throws \TYPO3\Fluid\View\Exception\InvalidTemplateResourceException
	 */
	protected function getTemplatePathAndFilename($actionName = NULL) {
		if ($this->templatePathAndFilename !== NULL) {
			return $this->templatePathAndFilename;
		}
		if ($actionName === NULL) {
			$actionName = $this->controllerContext->getRequest()->getControllerActionName();
		};
		$actionName = ucfirst($actionName);
		$subpackageKey = $this->controllerContext->getRequest()->getControllerSubpackageKey();
		$this->controllerContext->getRequest()->setControllerSubpackageKey(ucfirst($subpackageKey));
		$paths = $this->expandGenericPathPattern($this->templatePathAndFilenamePattern, FALSE, FALSE);

		foreach ($paths as &$templatePathAndFilename) {
			$templatePathAndFilename = str_replace('@action', $actionName, $templatePathAndFilename);
			if (file_exists($templatePathAndFilename)) {
				return $templatePathAndFilename;
			} else {
				$templatePathAndFilename = str_replace('.' . $this->controllerContext->getRequest()->getFormat(), '.html', $templatePathAndFilename);
				if (file_exists($templatePathAndFilename)) {
					return $templatePathAndFilename;
				}
			}
		}
		throw new \TYPO3\Fluid\View\Exception\InvalidTemplateResourceException('Template could not be loaded. I tried "' . implode('", "', $paths) . '"', 1225709595);
	}

	/**
	 * Resolve the partial path and filename based on $this->partialPathAndFilenamePattern.
	 *
	 * @param string $partialName The name of the partial
	 * @return string the full path which should be used. The path definitely exists.
	 * @throws \TYPO3\Fluid\View\Exception\InvalidTemplateResourceException
	 */
	protected function getPartialPathAndFilename($partialName) {
		$paths = $this->expandGenericPathPattern($this->partialPathAndFilenamePattern, TRUE, TRUE);
		foreach ($paths as &$partialPathAndFilename) {
			$partialPathAndFilename = str_replace('@partial', $partialName, $partialPathAndFilename);
			if (file_exists($partialPathAndFilename)) {
				return $partialPathAndFilename;
			} else {
				$partialPathAndFilename = str_replace('.' . $this->controllerContext->getRequest()->getFormat(), '.html', $partialPathAndFilename);
				if (file_exists($partialPathAndFilename)) {
					return $partialPathAndFilename;
				}
			}
		}
		throw new \TYPO3\Fluid\View\Exception\InvalidTemplateResourceException('The template files "' . implode('", "', $paths) . '" could not be loaded.', 1225709595);
	}

}

?>