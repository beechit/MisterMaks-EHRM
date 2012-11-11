<?php
namespace Beech\Ehrm\ViewHelpers\Administration;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 18-10-12 20:57
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

class ModuleViewHelper extends \Famelo\Scaff\ViewHelpers\SubRequestViewHelper {

	/**
	 * @param string $namespace
	 * @param string $typoScriptPath
	 * @return string
	 */
	public function render($namespace = 'content', $typoScriptPath = NULL) {
		$pluginArguments = $this->controllerContext->getRequest()->getPluginArguments();
		if (isset($pluginArguments[$namespace])) {
			return parent::render($namespace, $typoScriptPath);
		}

		// TODO: implement module index
	}

}

?>