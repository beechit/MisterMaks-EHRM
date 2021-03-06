<?php
namespace Beech\Ehrm\Controller;

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
 * Abstract controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class AbstractController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @var \Beech\Ehrm\Utility\PreferencesUtility
	 * @Flow\Inject
	 */
	protected $preferencesUtility;

	/**
	 * Redirects the request to another action and / or controller.
	 *
	 * Redirect will be sent to the client which then performs another request to the new URI.
	 *
	 * NOTE: This method only supports web requests and will throw an exception
	 * if used with other request types.
	 *
	 * @param string $actionName Name of the action to forward to
	 * @param string $controllerName Unqualified object name of the controller to forward to. If not specified, the current controller is used.
	 * @param string $packageKey Key of the package containing the controller to forward to. If not specified, the current package is assumed.
	 * @param array $arguments Array of arguments for the target action
	 * @param integer $delay (optional) The delay in seconds. Default is no delay.
	 * @param integer $statusCode (optional) The HTTP status code for the redirect. Default is "303 See Other"
	 * @param string $format The format to use for the redirect URI
	 * @return void
	 * @throws \TYPO3\Flow\Mvc\Exception\StopActionException
	 * @see forward()
	 * @api
	 */
	protected function redirect($actionName, $controllerName = NULL, $packageKey = NULL, array $arguments = NULL, $delay = 0, $statusCode = 303, $format = NULL) {
		if ($this->request->hasArgument('callback')) {
			if ($arguments === NULL) {
				$arguments['callback'] = $this->request->getArgument('callback');
			} else {
				$arguments = array(
					'callback' => $this->request->getArgument('callback')
				);
			}
		}

		parent::redirect($actionName, $controllerName, $packageKey, $arguments, $delay, $statusCode, $format);
	}

	/**
	 * A temporary solution to create a Ember proof redirect
	 *
	 * Because there is currently no way to translate a FLOW call/uri to a Ember route
	 * you have to do this manualy for now.
	 * @todo find a way to setup al routes in YAML and generate all Ember routes from that
	 * and create a utility to convert a FLOW uri to a Ember route
	 *
	 * @param $emberRoute
	 * @throws \TYPO3\Flow\Mvc\Exception\StopActionException
	 */
	protected function emberRedirect($emberRoute) {
		$escapedUri = htmlentities($emberRoute, ENT_QUOTES, 'utf-8');
		$this->response->setContent('redirect:' . $escapedUri . '');

		throw new \TYPO3\Flow\Mvc\Exception\StopActionException();
	}

	/**
	 * A template method for displaying custom error flash messages, or to
	 * display no flash message at all on errors. Override this to customize
	 * the flash message in your action controller.
	 *
	 * @return \TYPO3\Flow\Error\Message The flash message or FALSE if no flash message should be set
	 * @todo implement translations
	 */
	protected function getErrorFlashMessage() {
		return new \TYPO3\Flow\Error\Error('Validation error, ' . get_class($this) . ' ' . $this->actionMethodName, NULL, array(get_class($this), $this->actionMethodName));
	}

}

?>