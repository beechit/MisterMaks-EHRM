<?php
namespace Beech\Ehrm\ViewHelpers\Ember;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 29-08-12 12:19
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

class HelperViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * This method return a {{action ...}} tag that can be used
	 * by EmberJS.
	 * Can be either type 'action' or type 'view'
	 *
	 * @param null $action
	 * @param string $type
	 * @return string
	 */
	public function render($action = NULL, $type = 'action') {
		if ($action === NULL) {
			$action = $this->renderChildren();
		}

		return '{{' . $type . ' ' . $action . '}}';
	}
}