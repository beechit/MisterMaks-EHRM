<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 05-06-12 10:47
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Abstract controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class AbstractController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @var array
	 */
	protected $defaultViewObjectName = 'Beech\Ehrm\View\TemplateView';

	/**
	 *
	 */
	public function callActionMethod() {
		if ($this->request->getFormat() === 'jsonp') {
			try {
				parent::callActionMethod();
			} catch (\Exception $exception) {
				$this->response->setContent(sprintf('<div class="alert alert-error">%s</div>', $exception->getMessage()));
			}

			$this->response->setHeader('Content-Type', 'application/javascript');

			$content = $this->response->getContent();
			$content = str_replace(array("\n", "\r", "\t"), '', $content);

			$this->response->setContent(sprintf(
				'%s(%s)',
				$this->request->getArgument('callback'),
				json_encode((object)array(
					'html' => $content
				))
			));
		} else {
			parent::callActionMethod();
		}
	}

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

}

?>