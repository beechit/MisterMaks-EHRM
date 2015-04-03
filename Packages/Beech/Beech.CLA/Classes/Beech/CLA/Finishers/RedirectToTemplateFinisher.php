<?php
namespace Beech\CLA\Finishers;

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
 * This finisher extends default RedirectFinisher
 */
class RedirectToTemplateFinisher extends \TYPO3\Form\Finishers\RedirectFinisher {


	/**
	 * @var \TYPO3\Flow\Mvc\Routing\UriBuilder
	 * @Flow\Inject
	 */
	protected $uriBuilder;

	/**
	 * Executes this finisher
	 *
	 * @return void
	 * @throws \TYPO3\Form\Exception\FinisherException
	 */
	public function executeInternal() {
		$formValues = $this->finisherContext->getFormValues();
		$formRuntime = $this->finisherContext->getFormRuntime();
		$request = $formRuntime->getRequest()->getMainRequest();
		foreach ($formValues as $name => $value) {
			$request->setArgument($name, $value);
		}
		$packageKey = $this->parseOption('package');
		$controllerName = strtolower($this->parseOption('controller'));
		$actionName = $this->parseOption('action');
		$arguments = array_merge($this->parseOption('arguments'), $request->getArguments());
		$delay = (integer)$this->parseOption('delay');
		$statusCode = $this->parseOption('statusCode') ? $this->parseOption('statusCode') : 303;

		$subpackageKey = NULL;
		if ($packageKey !== NULL && strpos($packageKey, '\\') !== FALSE) {
			list($packageKey, $subpackageKey) = explode('\\', $packageKey, 2);
		}

		if($this->parseOption('ember')) {
			$uriArguments = array();
			if (!empty($subpackageKey)) {
				$uriArguments[] = strtolower($subpackageKey);
			}
			$uriArguments[] = $controllerName;
			$uriArguments[] = $actionName;
			$uriArguments = array_merge($uriArguments, $formValues);
			$uri = '/#/' . implode('/', $uriArguments);
		} else {
			$this->uriBuilder->setRequest($request);
			$this->uriBuilder->setFormat($request->getFormat());
			$uri = $this->uriBuilder->setCreateAbsoluteUri(TRUE)->uriFor($actionName, $formValues, $controllerName, $packageKey, $subpackageKey);
		}

		$escapedUri = htmlentities($uri, ENT_QUOTES, 'utf-8');

		$response = $formRuntime->getResponse();
		$response->setContent('<html><head><meta http-equiv="refresh" content="' . intval($delay) . ';url=' . $escapedUri . '"/></head></html>');
		$response->setStatus($statusCode);
		if ($delay === 0) {
			$response->setHeader('Location', (string)$uri);
		}
		$response->send();
		exit;
	}

}
?>