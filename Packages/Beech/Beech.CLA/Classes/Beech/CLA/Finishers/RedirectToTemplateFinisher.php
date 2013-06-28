<?php
namespace Beech\CLA\Finishers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 05-06-12 10:47
 * All code (c) Beech Applications B.V. all rights reserved
 */

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