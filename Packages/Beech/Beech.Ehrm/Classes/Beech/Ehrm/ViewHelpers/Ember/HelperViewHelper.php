<?php
namespace Beech\Ehrm\ViewHelpers\Ember;

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

		if (!empty($action)) {
			return '{{' . $type . ' "' . $action . '"}}';
		}

		return '';
	}
}

?>