<?php
namespace Beech\Ehrm\ViewHelpers\Link;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 29-08-12 12:19
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * A view helper for creating link to edit actions.
 * = Examples =
 * <code title="Usage">
 * <ehrm:link.edit action="myAction" controller="MyController" package="YourCompanyName.MyPackage" subpackage="YourCompanyName.MySubpackage" arguments="{key1: 'value1', key2: 'value2'}">some link</ehrm:link.edit>
 * </code>
 * <output>
 * <a href="mypackage/mycontroller/mysubpackage/myaction?key1=value1&amp;key2=value2">some link</a>
 * (depending on routing setup)
 * </output>
 *
 */
class EditViewHelper extends \TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper {

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * Initialize arguments
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		parent::initializeArguments();

	}

	/**
	 * Render the link.
	 *
	 * @param string $action Target action
	 * @param array $arguments Arguments
	 * @param string $controller Target controller. If NULL current controllerName is used
	 * @param string $package Target package. if NULL current package is used
	 * @param string $subpackage Target subpackage. if NULL current subpackage is used
	 * @param string $section The anchor to be added to the URI
	 * @param string $format The requested format, e.g. ".html"
	 * @param array $additionalParams additional query parameters that won't be prefixed like $arguments (overrule $arguments)
	 * @param boolean $addQueryString If set, the current query parameters will be kept in the URI
	 * @param array $argumentsToBeExcludedFromQueryString arguments to be removed from the URI. Only active if $addQueryString = TRUE
	 * @param boolean $useParentRequest If set, the parent Request will be used instead of the current one
	 * @param string $dataToggle
	 * @param string $dataTarget
	 * @param string $dataReload
	 * @param string $modalSize
	 * @return string The rendered link
	 * @throws \TYPO3\Fluid\Core\ViewHelper\Exception
	 */
	public function render($action, $arguments = array(), $controller = NULL, $package = NULL, $subpackage = NULL, $section = '', $format = '',  array $additionalParams = array(), $addQueryString = FALSE, array $argumentsToBeExcludedFromQueryString = array(), $useParentRequest = FALSE, $dataToggle = NULL, $dataTarget = NULL, $dataReload = NULL, $modalSize = NULL) {
		// TODO: implement permission checking, if no rights to edit, don't display link
		$uriBuilder = $this->controllerContext->getUriBuilder();
		if ($useParentRequest) {
			$request = $this->controllerContext->getRequest();
			if ($request->isMainRequest()) {
				throw new \TYPO3\Fluid\Core\ViewHelper\Exception('You can\'t use the parent Request, you are already in the MainRequest.', 1360163536);
			}
			$uriBuilder = clone $uriBuilder;
			$uriBuilder->setRequest($request->getParentRequest());
		}

		$uriBuilder
			->reset()
			->setSection($section)
			->setCreateAbsoluteUri(TRUE)
			->setArguments($additionalParams)
			->setAddQueryString($addQueryString)
			->setArgumentsToBeExcludedFromQueryString($argumentsToBeExcludedFromQueryString)
			->setFormat($format);
		try {
			$uri = $uriBuilder->uriFor($action, $arguments, $controller, $package, $subpackage);
		} catch (\TYPO3\Flow\Exception $exception) {
			throw new \TYPO3\Fluid\Core\ViewHelper\Exception($exception->getMessage(), $exception->getCode(), $exception);
		}
		$this->tag->addAttribute('href', $uri);
		$this->tag->addAttribute('data-reload', $dataReload);
		$this->tag->addAttribute('data-toggle', ($dataToggle !== NULL) ? $dataToggle : 'modal-ajax');
		$this->tag->addAttribute('data-target', ($dataTarget !== NULL) ? $dataTarget : '#modal');
		$this->tag->addAttribute('modal-size', ($modalSize !== NULL) ? $modalSize : 'large');

		if ($this->tag->hasContent()) {
			$this->tag->setContent($this->renderChildren());
		} else {
			$this->tag->setContent('<i class="icon-pencil icon-white"></i> ' . $this->translator->translateById('action.edit', array(), NULL, NULL, 'Main', 'Beech.Party'));
		}

		$this->tag->forceClosingTag(TRUE);

		return $this->tag->render();
	}
}

?>