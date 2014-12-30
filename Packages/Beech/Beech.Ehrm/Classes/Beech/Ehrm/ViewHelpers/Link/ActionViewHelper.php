<?php
namespace Beech\Ehrm\ViewHelpers\Link;

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
 * A view helper for creating links to actions.
 * = Examples =
 * <code title="Defaults">
 * <f:link.action>some link</f:link.action>
 * </code>
 * <output>
 * <a href="currentpackage/currentcontroller">some link</a>
 * (depending on routing setup and current package/controller/action)
 * </output>
 * <code title="Additional arguments">
 * <f:link.action action="myAction" controller="MyController" package="YourCompanyName.MyPackage" subpackage="YourCompanyName.MySubpackage" arguments="{key1: 'value1', key2: 'value2'}">some link</f:link.action>
 * </code>
 * <output>
 * <a href="mypackage/mycontroller/mysubpackage/myaction?key1=value1&amp;key2=value2">some link</a>
 * (depending on routing setup)
 * </output>
 * <code title="Link object as argument">
 * <f:link.action link="{link}">some link</f:link.action>
 * </code>
 * <output>
 * <a href="{link.packageKey}/{link.subpackageKey}/{link.controllerName}/{link.actionName}/?{link.arguments}">some link</a>
 * </output>
 *
 */
class ActionViewHelper extends \TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper {

	/**
	 * Initialize arguments
	 *
	 * @return void
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerTagAttribute('data-toggle', 'string', 'Bootstrap action bind to link (for instance: collapse, modal, modal-ajax)');
		$this->registerTagAttribute('data-target', 'string', 'Target element for the data-toggle effect');
		$this->registerTagAttribute('data-reload', 'string', 'Specifies the name of an anchor');
		$this->registerTagAttribute('modal-size', 'string', 'Specifies the size of the modal when linked should be opened in a Modal');
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
	 * @param \Beech\Ehrm\Domain\Model\Link $link If set, all params from link object will be used
	 * @return string The rendered link
	 * @throws \TYPO3\Fluid\Core\ViewHelper\Exception
	 */
	public function render($action = NULL, $arguments = NULL, $controller = NULL, $package = NULL, $subpackage = NULL, $section = '', $format = '',  array $additionalParams = array(), $addQueryString = FALSE, array $argumentsToBeExcludedFromQueryString = array(), $useParentRequest = FALSE, \Beech\Ehrm\Domain\Model\Link $link = NULL) {

		if ($link !== NULL) {
			if ($package === NULL) {
				$package = $link->getPackageKey();
			}
			if ($subpackage === NULL) {
				$subpackage = $link->getSubpackageKey();
			}
			if ($controller === NULL) {
				$controller = $link->getControllerName();
			}
			if ($action === NULL) {
				$action = $link->getActionName();
			}
			if ($arguments === NULL) {
				$arguments = $link->getArguments();
			}

			if ($link->getModal() !== NULL) {
				if ($this->tag->getAttribute('data-reload') === NULL) {
					$this->tag->addAttribute('data-reload', 'close-modal');
				}
				if ($this->tag->getAttribute('data-toggle') === NULL) {
					$this->tag->addAttribute('data-toggle', 'modal-ajax');
				}
				if ($this->tag->getAttribute('data-target') === NULL) {
					$this->tag->addAttribute('data-target', '#modal');
				}
				if ($this->tag->getAttribute('modal-size') === NULL) {
					$this->tag->addAttribute('modal-size', strlen($link->getModal()) > 1 ? $link->getModal() : 'large');
				}
			}
		}

		if ($arguments === NULL) {
			$arguments = array();
		}

		try {
			$output = parent::render($action, $arguments, $controller, $package, $subpackage, $section, $format, $additionalParams, $addQueryString, $argumentsToBeExcludedFromQueryString, $useParentRequest);
			return $output;
		} catch (\TYPO3\Fluid\Core\ViewHelper\Exception $exception) {
			return $this->renderChildren();
		}
	}
}

?>